#!/usr/bin/php -q
<?php
$urls = array('abc.google1.com');
$methods = array(
	'_callShortenerServiceCurl',
	'_callShortenerServiceStream',
	'_callShortenerServiceSocket'
);

define('RESULT_OK_SLOW', '{"urls":{"abc.google1.com\/sur.co.uk":"hj","abc.google1.com\/sur.ly":"hk","abc.google1.com":"hl","abc.google1.com":"B","youtube.com\/videos":"hm","youtube.com":"dz"},"errors":[]}');

define('RESULT_OK_NORM', '{"urls":{"abc.google1.com":"B"},"errors":[]}');

$testSuits = array(
	'NORMAL_STATE' => array(
		'host' => 'sur.ly',
		'path' => '/shorten/',
		'timeout' => 10,
		'urls' => $urls,
		'expectedResult' => RESULT_OK_NORM
	),
	'SLOW_PASS' => array(
		'host' => 'sur.ly',
		'path' => '/slow.php',
		'timeout' => 2,
		'urls' => $urls,
		'expectedResult' => null
	),
	'SLOW_TIMEOUT' => array(
		'host' => 'sur.ly',
		'path' => '/slow.php',
		'timeout' => 3,
		'urls' => $urls,
		'expectedResult' => RESULT_OK_SLOW
	),
);


// import Surly library
require_once dirname(__FILE__).'/../Surly.php';

foreach ($testSuits as $name => $suit) {
	echo "= $name = ", PHP_EOL;

	$surl = new Surly();

	$surl->apiHost = $suit['host'];
	$surl->apiPath = $suit['path'];
	$surl->timeout = $suit['timeout'];

	foreach ($methods as $method) {
		$t = microtime(true);

		$result = $surl->{$method}($suit['urls']);

		$t2 = microtime(true) - $t;
		$t2 = round($t2, 2);

		$result = trim($result);

		if ($t2 > $suit['timeout']) {
			red($method, $t2, 'took more time than '. $suit['timeout']);
		}
		else if ($result != $suit['expectedResult']) {
			red($method, $t2, 'unexpected result: '.$result);
		}
		else
			green($method, $t2);
	}

	echo PHP_EOL;
}

exit ('Done');

function cecho ($str, $color)
{
	echo "\033[", $color, "m";
	echo $str;
	echo "\033[0m";
}

function red($method, $time, $reason)
{
	cecho('- '. $method . ' failed in '. $time. 's: '. $reason, '1;31');
	echo PHP_EOL;
}

function green($method, $time)
{
	cecho('+ '. $method . ' passed in '. $time. 's', '1;32');
	echo PHP_EOL;
}

