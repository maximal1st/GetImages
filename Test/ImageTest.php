<?php

namespace maximalist\GetImages;

class ImageTest extends \PHPUnit_Framework_TestCase {

	function testLoad() {
	}

	function testSave() {
	}

	function testGetType() {
		$i = new Image( 'http://php.net/images/logo.php' );
		$this->assertEquals( $i->getType(), 'image/svg+xml; charset=us-ascii' );
	}

}
