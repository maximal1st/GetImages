<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

/**
 * Downloads a HTML document and extracts hyperlinks and image URLs
 *
 * @since 0.2
 *
 * @property string $url    Page URL
 * @property string $host   Page host
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
		if( filter_var( $url, FILTER_VALIDATE_URL ) === false )
			throw new \Exception( "Invalid URL" );

		$this->url = $url;

		$url = parse_url( $url );
		$this->host = $url['scheme'].'://'.$url['host'];
		if( !empty( $url['port'] ) )
			$this->host .= ':'.$url['port'];
	}

/**
 * Collect links to other pages and images
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
			$this->images[$src] = '';
		}

		$links = $dom->getElementsByTagName( 'a' );
		foreach( $links as $link )
		{
			$href = $this->absUrl( $link->getAttribute( 'href' ) );
			$this->links[$href] = '';
		}
	}

/**
 * Make link absolute
 *
 * @param string $url Link URL
 * @return string Absolute link
 */
	function absUrl( string $url ) {
		$url = parse_url( $url );
		$s = '';
		if( !empty( $url['scheme'] ) )
			$s .= $url['scheme'].'://';
		$s .= !empty( $url['host'] ) ? $url['host'] : $this->host;
		if( !empty( $url['port'] ) )
			$s .= ':'.$url['port'];
		if( !empty( $url['path'] ) )
			$s .= $url['path'];
		return $s;
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
