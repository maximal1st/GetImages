<?php
/**
 * Created especially for purpose of testing for PHP-developer position in hexa.com.ua
 *
 * @package maximalist\GetImages
 * @version 0.3
 * @author  Maxim Levchenko <maximl1st@gmail.com>
 * @example Usage.php
 */

namespace maximalist\GetImages;

/**
 * It's about traverse site pages and load images
 *
 * @since 0.2
 *
 * @property string  $url    Site URL
 * @property string  $host   Site host
 * @property string  $path   Path to store downloaded images
 * @property integer $depth  How deep browse site
 * @property array   $links  Collection of found pages
 * @property array   $images Collection of found images
 * @property integer $errnum Errors number
 *
 * @method void run()
 * @method void reset()
 * @method bool isSuitable( string $url )
 *
 * @todo Think about changing algorithm to not use static members
 */
class Harvester {

	const MAX_ERRORS = 100;

	private $url;
	private $host;
	private $path;
	private $depth;
	// Using static members is the simplest way to control doubles
	private static $links = [];
	private static $images = [];
	private static $errnum = 0;

	function __construct( string $url, string $path, int $depth = 1 ) {
		if( filter_var( $url, FILTER_VALIDATE_URL ) === false )
			throw new \Exception( "Invalid URL" );
		if( filter_var( $depth, FILTER_VALIDATE_INT ) === false )
			throw new \Exception( "Invalid depth" );

		$this->url = $url;
		$this->path = $path;
		$this->depth = $depth;
		$url = parse_url( $url );
		$this->host = $url['host'];
	}

/**
 * Traverse site to load images
 */
	function run() {
		$page = new Page( $this->url );
		$page->parse();

		foreach( $page->getImages() as $url )
			if( $this->isSuitable( $url ) && !array_key_exists( $url, self::$images ) )
			{
				self::$images[$url] = '';
				$image = new Image( $url );
				try {
					if( preg_match( '/^image\/(gif|jpeg|png)/', $image->getType() ) )
						$image->save( $this->path );
				} catch( \Exception $e ) {
					self::$errnum++;
					if( self::$errnum > self::MAX_ERRORS )
						throw new \Exception( "Too many errors" );
				}
			}

		foreach( $page->getLinks() as $url )
			if( $this->isSuitable( $url ) && $this->depth - 1 >= 0 && !array_key_exists( $url, self::$links ) )
			{
				self::$links[$this->url] = '';
				$harvest = new Harvester( $url, $this->path, $this->depth - 1 );
				try {
					$harvest->run();
				} catch( \Exception $e ) {
					self::$errnum++;
					if( self::$errnum > self::MAX_ERRORS )
						throw new \Exception( "Too many errors" );
				}
			}
	}

/**
 * Reset internal data. Use before start traverse in new site
 */
	function reset() {
		self::$links = [];
		self::$images = [];
		self::$errnum = 0;
	}

/**
 * Check URL to have appropriate host
 *
 * @param string $url Link URL
 * @return boolean True if URL have appropriate host
 */
	function isSuitable( $url ) {
		$url = parse_url( $url );
		return $this->host == $url['host'];
	}

/**
 * Return collection of found page links
 *
 * @return array Links collection
 */
	function getLinks() {
		return array_keys( self::$links );
	}

/**
 * Return collection of found images
 *
 * @return array Images collection
 */
	function getImages() {
		return array_keys( self::$images );
	}

}
