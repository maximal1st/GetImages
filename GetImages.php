<?php
/**
 * Created especially for purpose of testing for PHP-developer position in hexa.com.ua
 *
 * @package maximalist\GetImages
 *
 * @author Maxim Levchenko <maximl1st@gmail.com>
 *
 * @version 0.1
 *
 * How to use:
 *
 * $o = new GetImages( 'http://umj.com.ua' );
 * $o->setDepth( 2 );
 * $o->goBot();
 * print_r( $o->getPages() );
 * print_r( $o->getImages() );
 * $o->setPath( '/home/maxim/Work/Test/img' );
 * $o->loadImages();
 *
 */

namespace maximalist\GetImages;

/**
 * It's about traverse site pages and collect images
 *
 * @since 0.1
 *
 * @property string  $base   Base to convert locale URL to absolute
 * @property integer $depth  How deep browse URL
 * @property array   $pages  Collection of found pages
 * @property array   $images Collection of found images
 * @property string  $path   Directory to store downloaded images
 *
 * @method void setDepth( int $depth )
 * @method void setPath( string $path )
 * @method bool isDeeper( string $url )
 * @method string absUrl( string $url )
 * @method void goBot()
 * @method array getPages()
 * @method array getImages()
 * @method void loadImages()
 *
 * @uses   DOMDocument to parse HTML
 *
 * @throws Exception
 *
 * @todo Think about changing algorithm to not use static fields
 */
class GetImages {
	private $url;
	private static $base = '';
	private static $depth = 1;
	private static $pages = [];
	private static $images = [];
	private static $path = '/tmp';

/**
 * @param string $url URL from which start site browsing
 */
	function __construct( $url ) {
		$this->url = $url;
		if( empty( self::$base ) )
		{
			$url = parse_url( $url );
			if( $url['scheme'] != 'http' )
				throw new \Exception( 'Correct URL needed' );
			if( self::$base != $url['host'] )
			{
				self::$base = $url['host'];
				self::$pages = [];
				self::$images = [];
			}
		}
	}
	
/**
 * Set depth of site browsing
 *
 * @param int $depth Level to stop. Root is 1
 */
	function setDepth( $depth ) {
		self::$depth = $depth;
	}

/**
 * Set target directory for images
 *
 * @param string $path Path to store downloaded images
 */
	function setPath( $path ) {
		self::$path = $path;
	}

/**
 * Check link to exceed browsing level
 *
 * @param string $url Link URL
 * @return boolean True if link level exceed threshold
 */
	private function isDeeper( $url ) {
		$url = parse_url( $url );
		return !empty( $url['path'] ) && count( explode( '/', $url['path'] ) ) > self::$depth;
	}

/**
 * Make link absolute
 *
 * @param string $url Link URL
 * @return string Absolute link
 */
	private function absUrl( $url ) {
		$url = parse_url( $url );
		if( !empty( $url['host'] ) && $url['host'] != self::$base )
			return '';
		return 'http://'.self::$base.(!empty( $url['path'] ) ? $url['path'] : '');
	}

/**
 * Browse site to fill collection of pages and images
 */
	function goBot() {
		if( empty( $this->url ) )
			return;

		$html = file_get_contents( $this->url );

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( $html );
		libxml_clear_errors();

		$links = $dom->getElementsByTagName( 'img' );
		foreach( $links as $link )
		{
			$src = $this->absUrl( $link->getAttribute('src') );
			if( preg_match( '/\.(jpg|png|gif)$/', $src ) )
				self::$images[$src] = '';
		}

		$links = $dom->getElementsByTagName( 'a' );

		foreach( $links as $link )
		{
			$href = $this->absUrl( $link->getAttribute('href') );
			if( !empty( $href ) && !array_key_exists( $href, self::$pages ) && !$this->isDeeper( $href ) )
			{
				self::$pages[$href] = '';
				$o = get_class();
				$o = new $o( $this->absUrl( $href ) );
				// $o->setDepth( $this->depth );
				$o->goBot();
				// $this->pages = array_merge( $this->pages, $o->getPages() );
				// $this->images = array_merge( $this->images, $o->getImages() );
			}
		}
	}

/**
 * Return collection of found pages
 *
 * @return array Pages collection
 */
	function getPages() {
		return array_keys( self::$pages );
	}

/**
 * Return collection of found images
 *
 * @return array Images collection
 */
	function getImages() {
		return array_keys( self::$images );
	}

/**
 * Download previously collected images to target directory
 */
	function loadImages() {
		if( !is_dir( self::$path ) && !mkdir( self::$path, 0777, true ) )
			throw new \Exception( 'Can\'t create target directory '.self::$path );
		foreach( $this->getImages() as $file )
		{
			$name = self::$path.'/'.basename( $file );
			$data = file_get_contents( $file );
			if( $data === false )
				throw new \Exception( 'Can\'t read '.$file );
			if( file_put_contents( $name, $data ) === false )
				throw new \Exception( 'Can\'t write '.$name );
		}
	}
}
