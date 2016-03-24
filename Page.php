<?php

namespace maximalist\GetImages;

class Page {

	private $url;
	private $host;
	private links = [];
	private images = [];
	
	function __construct( string $url ) { 
		$this->url = $url;

		$url = parse_url( $url );
		$this->host = $url['host'];
	}

/**
 * Collect links to other pages and images
 */
	function parse() {
		$html = file_get_contents( $this->url );
		if( $html === false )
			throw new \Exception( "Can't read ".$this->url );

		$dom = new \DOMDocument();
		libxml_use_internal_errors( true );
		$dom->loadHTML( $html );
		libxml_clear_errors();

		$links = $dom->getElementsByTagName( 'img' );
		foreach( $links as $link )
		{
			$src = $this->absUrl( $link->getAttribute('src') );
			$this->images[$src] = '';
		}

		$links = $dom->getElementsByTagName( 'a' );
		foreach( $links as $link )
		{
			$href = $this->absUrl( $link->getAttribute('href') );
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
		return 'http://'.$this->host.(!empty( $url['path'] ) ? $url['path'] : '');
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

}
