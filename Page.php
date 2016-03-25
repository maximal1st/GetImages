<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

/**
 * Page manipulations
 *
 * @since 0.2
 *
 * @property string $url    Page URL
 * @property string $host   
 * @property array  $links  Collection of found pages
 * @property array  $images Collection of found images
 *
 * @method string absUrl( string $url )
 * @method array  getLinks()
 * @method array  getImages()
 *
 * @uses   DOMDocument to parse HTML
 *
 */
class Page {

	private $url;
	private $host;
	private $links = [];
	private $images = [];
	
	function __construct( string $url ) { 
		$this->url = $url;

		$url = parse_url( $url );
		$this->host = $url['scheme'].'://'.$url['host'];
	}

/**
 * Collect links to other pages and images
 *
 * @todo Check image by mime type
 */
	function parse() {
		$html = file_get_contents( $this->url );
		if( $html === false )
			throw new \Exception( "Can't load ".$this->url );

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( $html );
		libxml_clear_errors();

		$links = $dom->getElementsByTagName( 'img' );
		foreach( $links as $link )
		{
			$src = $this->absUrl( $link->getAttribute( 'src' ) );
			if( preg_match( '/\.(jpg|png|gif)$/', $src ) )
				$this->images[$src] = '';
		}

		$links = $dom->getElementsByTagName( 'a' );
		foreach( $links as $link )
		{
			$href = $this->absUrl( $link->getAttribute( 'href' ) );
			$this->pages[$href] = '';
		}
	}

/**
 * Make link absolute
 *
 * @param string $url Link URL
 * @return string Absolute link
 */
	private function absUrl( string $url ) {
		$url = parse_url( $url );
		return $this->host.(!empty( $url['path'] ) ? $url['path'] : '');
	}

/**
 * Return collection of found page links
 *
 * @return array Links collection
 */
	function getLinks() {
		return array_keys( $this->links );
	}

/**
 * Return collection of found images
 *
 * @return array Images collection
 */
	function getImages() {
		return array_keys( $this->images );
	}

}
