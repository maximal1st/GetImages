<?php

namespace maximalist\GetImages;

class PageTest extends \PHPUnit_Framework_TestCase {
	
	function testParse() {
		$page = new Page( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html' );
		$page->parse();
		$this->assertCount( 2, $page->getLinks(), 'Links count' );
		$this->assertCount( 4, $page->getImages(), 'Images count' );
	}
	
}