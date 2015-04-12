<?php
// It may take a whils to crawl a site ...
header( 'Content-type: text/html; charset=utf-8' );
ini_set('max_execution_time', 3000);
@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
ini_set("output_buffering", 1);
ob_implicit_flush(1);
set_time_limit(0);

require "vendor/autoload.php";
require "lib/PHPCrawl/libs/PHPCrawler.class.php";
require "lib/ganon.php";
require "core/MySQLDb.php";
require "core/QAScanner.php";
require "core/ScanCore.php";
require "core/Plugin.php";

$qascanner = new QAScanner();
$qascanner->addPlugin('EmptyA');
$qascanner->addPlugin('ScriptTags');
//$qascanner->addURL('http://www.mea.klyp.co/');
$qascanner->addURL('http://localhost');
$qascanner->scan();
