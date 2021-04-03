<?php
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Methods: GET, POST');
	header("Access-Control-Allow-Headers: X-Requested-With");

// Assuming you installed from Composer:
// Assuming you installed from Composer:
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$search = $_GET['q'] ?? '';
$url = 'https://m.apkpure.com/id/search?q='.$search; //url

$dom = new Dom;
$dom->loadFromUrl($url);
$items = $dom->find('.bs-s a');
$result = [];

foreach ($items as $key => $item) {
	$res['icon'] = $item->find('img')[0]->getAttribute('data-original');
	$res['name'] = $item->find('.d1')[0]->text;
	$res['url'] = $item->href;
	array_push($result, $res);
}

// echo "<pre>";
// print_r($result);
// echo "</pre>";
echo json_encode($result);