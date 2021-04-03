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
$items = $dom->find('.dd');
$result = [];

foreach ($items as $key => $item) {
	array_push($result, [
		'url' => $item->getAttribute('href'),
		'icon' => $item->find('img')[0]->getAttribute('data-original'),
		'name' => $item->find('.p1')[0]->text,
		'star' => $item->find('.star')[0]->text,
		'publisher' => $item->find('.p2')[0]->text
	]);
}

// echo "<pre>";
// print_r($result);
// echo "</pre>";
echo json_encode($result);