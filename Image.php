<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

/**
 * Image manipulations
 *
 * @since 0.2
 *
 * @property string $url  Image URL
 * @property string $data Image data
 *
 * @method void load()
 * @method void save( string $path )
 *
 * @throws Exception
 */
class Image {

	private $url;
	private $data = null;
	
/**
 * @param string $url Image URL
 */
	function __construct( string $url ) { 
		$this->url = $url;
	}

/**
 * Load image data from URL
 */
	function load() {
		$this->data = file_get_contents( $this->url );
		if( $this->data === false )
			throw new \Exception( "Can't load ".$this->url );
	}

/**
 * Save image data to file
 * @param string $path Path to store image file
 */
	function save( string $path ) {
		if( $this->data )
		{
			$name = $path.'/'.basename( $this->url );
			if( file_put_contents( $name, $this->data ) === false )
				throw new \Exception( "Can't write ".$name );
		}
	}

}
