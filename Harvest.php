<?php
/**
 * Created especially for purpose of testing for PHP-developer position in hexa.com.ua
 *
 * @package maximalist\GetImages
 * @version 0.2
 * @author  Maxim Levchenko <maximl1st@gmail.com>
 * @example Usage.php
 */

namespace maximalist\GetImages;

// require_once( 'Page.php' );
// require_once( 'Image.php' );

/**
 * It's about traverse site pages and load images
 *
 * @since 0.2
 *
 * @property string  $url    Site URL
 * @property string  $path   Path to store downloaded images
 * @property integer $depth  How deep browse site
 * @property array   $pages  Collection of found pages
 * @property array   $images Collection of found images
 * @property integer $errnum Errors number
 *
 * @method void make()
 * @method void reset()
 * @method bool isDeeper( string $url )
 */
class Harvest {

	private $url;
	private $path;
	private $depth;
	// Using static members is the simplest way to control over repeats
	private static $pages = [];
	private static $images = [];
	private static $errnum = 0;

	function __construct( string $url, string $path, int $depth = 1 ) {
		$this->url = $url;
		$this->path = $path;
		$this->depth = $depth;
	}

/**
 * Traverse site to load images
 */
	function make() {
		if( !is_dir( $this->path ) && !mkdir( $this->path, 0777, true ) )
				throw new \Exception( "Can't create target directory ".$this->path );

		$page = new Page( $this->url );
		$page->parse();

		foreach( $page->getImages() as $url )
			if( !array_key_exists( md5( $url ), self::$images ) )
			{
				self::$images[md5( $url )] = '';
				$image = new Image( $url );
				try {
					$image->load();
					$image->save( $this->path );
				} catch( \Exception $e ) {
					if( self::$errnum > 100 )
						throw new \Exception( "Too many errors" );
				}
			}

		foreach( $page->getLinks() as $url )
			if( !array_key_exists( md5( $url ), self::$pages ) && !$this->isDeeper( $url ) )
			{
				self::$pages[md5( $url )] = '';
				$harvest = new Harvest( $url, $this->path, $this->depth );
				try {
					$harvest->make();
				} catch( \Exception $e ) {
					if( self::$errnum > 100 )
						throw new \Exception( "Too many errors" );
				}
			}
	}

/**
 * Reset internal data. Use before start traverse in new site
 */
	function reset() {
		self::$pages = [];
		self::$images = [];
		self::$errnum = 0;
	}

/**
 * Check link to exceed browsing level
 *
 * @param string $url Link URL
 * @return boolean True if link level exceed threshold
 */
	private function isDeeper( $url ) {
		$url = parse_url( $url );
		return !empty( $url['path'] ) && count( explode( '/', $url['path'] ) ) > $this->depth;
	}

}
