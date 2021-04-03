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
if(count($dom->find('.details-tube')) > 0) {
	$result['thumbnail']['embed'] = $dom->find('.details-tube')[0]->getAttribute('data-src'); //link youtube
	$result['thumbnail']['youtube'] = $dom->find('.details-tube .tube-lazy')[0]->getAttribute('data-original'); //thumbnail
}
$images = [];
foreach ($dom->find('.screen-pswp img') as $key => $img) {
	array_push($images, $img->getAttribute('data-original'));
}
$result['thumbnail']['images'] = $images;
$result['describe'] = $dom->find('.describe')[0]->innerText; //deskripsi
$result['describe_html'] = $dom->find('.describe')[0]->innerHTML; //deskripsi html
$result['whatnew'] = $dom->find('.whatnew')[0]->innerText; //whats new
$result['whatnew_html'] = $dom->find('.whatnew')[0]->innerHTML; //whats new html
$tags = [];
foreach ($dom->find('.tag_list a') as $key => $tag) {
	array_push($tags, $tag->text);
}
$result['tags'] = $tags;
$result['additional'] = $dom->find('.additional')[0]->innerText; //additional
$result['additional_html'] = $dom->find('.additional')[0]->innerHTML; //additional html

// recommended
$recommend = [];
foreach ($dom->find('.bs-s')[0]->find('a') as $key => $re) {
	array_push($recommend, [
		'link' => $re->getAttribute('href'),
		'icon' => $re->find('img')[0]->getAttribute('data-original'),
		'name' => $re->find('.d1')[0]->text,
		'rating' => $re->find('.star')[0]->text
	]);
}
$result['recommend'] = $recommend;

// from publisher
$samepub = [];
foreach ($dom->find('.bs-s')[1]->find('a') as $key => $re) {
	array_push($samepub, [
		'link' => $re->getAttribute('href'),
		'icon' => $re->find('img')[0]->getAttribute('data-original'),
		'name' => $re->find('.d1')[0]->text,
		'rating' => $re->find('.star')[0]->text,
	]);
}
$result['samepub'] = $samepub;

$tags = $dom->find('.tag_list li a');
$tags_arr = [];
foreach ($tags as $key => $tag) {
	array_push($tags_arr, $tag->text);
}

$result['tags'] = $tags_arr;

// echo "<pre>";
// print_r($result);
// echo "</pre>";
echo json_encode($result);