<?php
/**
 * @package maximalist\GetImages
 */

require_once( 'Harvest.php' );

$h = new maximalist\GetImages\Harvest( 'http://umj.com.ua', '/home/maxim/Work/Test/img', 2 );
$h->make();
