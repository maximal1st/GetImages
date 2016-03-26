<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

/**
 * Loads image data and save it to file system
 *
 * @since 0.2
 *
 * @property string $url  Image URL
 * @property string $name Image file name
 * @property string $data Image data
 *
 * @method void load()
 * @method void save( string $path )
 * @method string getData()
 * @method string getType()
 * @method string getName()
 *
 * @uses   finfo to get image file type
 *
 * @throws Exception
 *
 * @todo Name cleanup and uniqueness check
 */
class Image {

	private $url;
	private $name = null;
	private $data = null;
	
/**
 * @param string $url Image URL
 */
	function __construct( string $url ) { 
		if( filter_var( $url, FILTER_VALIDATE_URL ) === false )
			throw new \Exception( "Invalid URL" );

		$this->url = $url;
	}

/**
 * Load image data from URL
 */
	function load() {
		$this->data = file_get_contents( $this->url );
		if( $this->data === false )
			throw new \Exception( "Can't load ".$this->url );
		$this->name = basename( $this->url );
	}

/**
 * Save image data to file
 *
 * @param string $path Path to store image file
 */
	function save( string $path ) {
// 		if( is_file( $path ) )
// 			throw new \Exception( "File with the target directory name '".$path."' exist" );
		if( !is_dir( $path ) && !mkdir( $path, 0777, true ) )
				throw new \Exception( "Can't create target directory ".$path );
		if( $this->data === null )
			$this->load();
		$name = $path.DIRECTORY_SEPARATOR.$this->name;
		if( file_put_contents( $name, $this->data ) === false )
			throw new \Exception( "Can't write ".$name );
	}

/**
 * Get image data
 *
 * @return string Image data
 */
	function getData() {
		if( $this->data === null )
			$this->load();
		return $this->data;
	}

/**
 * Get mime type
 *
 * @return string Mime type
 */
	function getType() {
		if( $this->data === null )
			$this->load();
		$finfo = new \finfo( FILEINFO_MIME );
		return $finfo->buffer( $this->data );
	}

/**
 * Get file name
 *
 * @return string File name
 */
	function getName() {
		return $this->name;
	}

}
