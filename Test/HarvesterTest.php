<?php

namespace maximalist\GetImages\Test;

use maximalist\GetImages\Harvester;

class HarvesterTest extends \PHPUnit_Framework_TestCase {

	function testIsSuitable() {
		$h = new Harvester( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html', __DIR__.'/img', 2 );
		$this->assertTrue( $h->isSuitable( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/level2/test2.html' ) );
		$this->assertFalse( $h->isSuitable( 'http://php.net/manual/ru' ) );
	}

	function testRun() {
		$h = new Harvester( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html', __DIR__.'/img', 2 );
		$h->run();
		$this->assertCount( 2, $h->getLinks(), 'Links count' );
		$this->assertCount( 2, $h->getImages(), 'Images count' );
	}

}
