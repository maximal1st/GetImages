<?php
/**
 * @package maximalist\GetImages
 */

require 'vendor/autoload.php';

$h = new maximalist\GetImages\Harvest( 'http://umj.com.ua', __DIR__.'/img', 2 );
$h->make();
