<?php

namespace maximalist\GetImages;

class PageTest extends \PHPUnit_Framework_TestCase {
	
	function testGetLinks() {
		$page = new Page( __DIR__.'/Test/test.html' );
		$this->assertCount( 0, $page->getLinks() );
	}

	function testGetImages() {
		$page = new Page( __DIR__.'/Test/test.html' );
		$this->assertCount( 0, $page->getImages() );
	}

	function testParse() {
		$page = new Page( __DIR__.'/Test/test.html' );
		$page->parse();
		$this->assertCount( 1, $page->getLinks() );
		$this->assertCount( 3, $page->getImages() );
	}
	
}