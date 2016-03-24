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
		foreach( $page->getImages() as $image )
		{
			$image = new Image( $image );
			$image->load(); 
		}
		foreach( $page->getPages() as $page )
		{
			$harvest = new Harvest( $page );
			$harvest->make();
		}
	}

}
