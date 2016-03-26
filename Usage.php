<?php
/**
 * @package maximalist\GetImages
 */

namespace maximalist\GetImages;

require 'vendor/autoload.php';

$h = new Harvester( 'http://umj.com.ua', __DIR__.'/img', 2 );
$h->run();

$p = new Page( 'https://raw.githubusercontent.com/maximal1st/GetImages/master/Test/test.html' );
$p->parse();
print_r( $p );

$i = new Image( 'http://php.net/images/logo.php' );
// $i->load();
print_r( $i->getType() );
print_r( md5( $i->getData() ) );
$i->save();
