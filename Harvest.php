<?php

require_once( 'Page.php' );

namespace maximalist\GetImages;

class Harvest {

	private $url;
	private $path;

	function __construct( $url, $path ) {
		$this->url = $url;
		$this->path = $path;
	}

	function make() {
		$page = new Page( $this->url );
		foreach( $page->getImages() as $url )
		{
			$image = new Image( $url );
			$image->load(); 
		}
		foreach( $page->getLinks() as $url )
		{
			$harvest = new Harvest( $url );
			$harvest->make();
		}
	}

}
