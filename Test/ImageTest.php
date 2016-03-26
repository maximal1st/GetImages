<?php

namespace maximalist\GetImages;

class ImageTest extends \PHPUnit_Framework_TestCase {

	function testLoad() {
		$i = new Image( 'http://php.net/images/logo.php' );
		$this->assertEquals( md5( $i->getData() ), '3faf3d392fb84ba0b76b33e28c5c0d66' );
	}

	function testSave() {
		$path = __DIR__.DIRECTORY_SEPARATOR.'img';
		if( is_dir( $path ) )
		{
			array_map( 'unlink', glob( $path.DIRECTORY_SEPARATOR.'*' ) );
			rmdir( $path );
		}
		$i = new Image( 'http://php.net/images/notes-add@2x.png' );
		$i->save( $path );
		$this->assertEquals( strlen( $i->getData() ), filesize( $path.DIRECTORY_SEPARATOR.$i->getName() ) );
	}

	function testGetType() {
		$i = new Image( 'http://php.net/images/logo.php' );
		$this->assertEquals( $i->getType(), 'image/svg+xml; charset=us-ascii' );
	}

}
