<?php

namespace maximalist\GetImages;

class HarvestTest extends \PHPUnit_Framework_TestCase {
	
	function testMake() {
// 		$h = new Harvest( 'http://umj.com.ua', __DIR__.'/img', 2 );
		$h = new Harvest( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test', __DIR__.'/img', 2 );
		$h->make();
		print_r( $h->getLinks() );
		print_r( $h->getImages() );
// 		$this->assertCount( 2, $h->getLinks(), 'Links count' );
// 		$this->assertCount( 4, $h->getImages(), 'Images count' );
	}

}
