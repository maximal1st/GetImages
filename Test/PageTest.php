<?php

namespace maximalist\GetImages\Test;

use maximalist\GetImages\Page;

class PageTest extends \PHPUnit_Framework_TestCase {
	
	function testParse() {
		$page = new Page( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html' );
		$page->parse();
		$this->assertCount( 2, $page->getLinks(), 'Links count' );
		$this->assertCount( 3, $page->getImages(), 'Images count' );
	}

	function testAbsUrl() {
		$page = new Page( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html' );
		$this->assertEquals( $page->absUrl( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/level2/test2.html' ), 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/level2/test2.html' );
		$this->assertEquals( $page->absUrl( '/maximal1st/GetImages/master/Test/level2/test2.html' ), 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/level2/test2.html' );
	}

}
