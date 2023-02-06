<?php
header("Access-Control-Allow-Origin: *"); // Allow website access to this API
header("Content-type: application/json; charset=utf-8");; // API support JSON and UTF-8
header("Access-Control-Allow-Methods: GET");; // methods Access |GET| |POST| ...
header("Access-Control-Max-Age: 3600"); // MAX Age for this Json by second
header("Access-Control-Allow-Headers: *"); // give to browser access without error


if(!isset($_GET['all'][2 - _HOST_]))
    die('error api 1');

$query = "SELECT * FROM `categorys` WHERE `restaurant_id` = ".$_GET['all'][2 - _HOST_];
$result = mysqli_query($GLOBALS['connectionSQL'], $query);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
if(!$categories) die('error api 2');

$tempCat = array();
$catNum = 0;
foreach ($categories as $category) {
    $itemNum = 0;
    $query = "SELECT * FROM `items` WHERE `restaurant_id` = " . $category['restaurant_id'] . " AND `category_id` = " . $category['id'];
    $result = mysqli_query($GLOBALS['connectionSQL'], $query);
    $items = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $tempItem = array();

    foreach ($items as $item) {
        $tempItem['item_'.$itemNum] = ['Name' =>$item['name'],'Details' => $item['details'],'Photo' => $item['photo'],'Price' =>$item['price']];
        $itemNum++;
    }

    $tempCat['category_'.$catNum]=['Name'=>$category['name'],'Items'=>$tempItem];
    $catNum++;
}

print_r(json_encode($tempCat,JSON_UNESCAPED_UNICODE));
