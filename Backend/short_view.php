<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

// Assuming you installed from Composer:
// Assuming you installed from Composer:
require "vendor/autoload.php";
use PHPHtmlParser\Dom;

$view = $_GET['view'] ?? '';
$url = 'https://m.apkpure.com'.$view; //url

$dom = new Dom;
$dom->loadFromUrl($url);
$items = $dom->find('.p10');

$result = [];

$result['icon'] = $items[0]->find('img')[0]->getAttribute('src'); //icon
$result['name'] = $items[0]->find('.p1')[0]->innerText; //name app
$result['version'] = $items[0]->find('span')[0]->innerText; //version sdk
$result['publisher'] = $items[0]->find('span')[1]->innerText; //publisher
$result['download'] = $dom->find('.down > a')[0]->getAttribute('href'); //download link
$result['rating'] = $dom->find('.details-star')[0]->text; //rating
$result['size'] = $dom->find('.down .fsize span')[0]->text; //size

// echo "<pre>";
// print_r($result);
// echo "</pre>";
echo json_encode($result);