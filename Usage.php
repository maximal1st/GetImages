<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

require 'vendor/autoload.php';

$h = new Harvest( 'http://umj.com.ua', __DIR__.'/img', 2 );
$h->make();

$i = new Image( 'http://php.net/images/logo.php' );
// $i->load();
print_r( $i->getType() );
