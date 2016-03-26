<?php

namespace maximalist\GetImages;

class HarvestTest extends \PHPUnit_Framework_TestCase {
	
	function testMake() {
		$h = new Harvest( 'http://umj.com.ua', __DIR__.'/img', 2 );
		$h->make();
		print_r( $h );
	}

}
