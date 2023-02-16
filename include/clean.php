<?php
if (strstr($_SERVER['REQUEST_URI'],'<') || strstr($_SERVER['REQUEST_URI'],'>')
    || strstr($_SERVER['REQUEST_URI'],'\'') || strstr($_SERVER['REQUEST_URI'],'"')) {
    $error['code'] = 403;
    $error['details'] = 'You tried to use an invalid link';
    $error['files'] = 'a turtle 🐎';
    require 'pages/error.php';
}

//$_SERVER['REQUEST_URI'] = str_replace(_DIR_FROM_ROOT_,'',$_SERVER['REQUEST_URI']);

$url = explode('/',$_SERVER['REQUEST_URI']);
$item = $url[2 - _HOST_];

if (strstr($item, "sw.js")) {
    header('Content-Type: application/javascript');
    readfile('sw.js');
    die();
}
if (strstr($item, "manifest.json")) {
    header('Content-Type: application/json; charset=utf-8');
    readfile('manifest.json');
    die();
}

if (strstr($item, "*")) require('pages/error.php');
if (strstr($item, ")")) require('pages/error.php');
if (strstr($item, "(")) require('pages/error.php');
if (strstr($item, "<")) require('pages/error.php');
if (strstr($item, "$")) require('pages/error.php');
if (strstr($item, ".")) require('pages/error.php');
if (strstr($item, "@")) require('pages/error.php');
if (strstr($item, "!")) require('pages/error.php');
if (strstr($item, ">")) require('pages/error.php');
if (strstr($item, "~")) require('pages/error.php');
if (strstr($item, "-")) require('pages/error.php');
if (strstr($item, ";")) require('pages/error.php');
if (strstr($item, '"')) require('pages/error.php');
if (strstr($item, "‘")) require('pages/error.php');
if (strstr($item, "’")) require('pages/error.php');
if (strstr($item, "`")) require('pages/error.php');
if (strstr($item, "'")) require('pages/error.php');
if (strstr($item, "¬")) require('pages/error.php');
if (strstr($item, "؟")) require('pages/error.php');
if (strstr($item, "?")) {header('Location: '._HOME_._DIR_FROM_ROOT_.'home');die();}

unset($tmp);

foreach ($url as $item) {
    if (trim($item)) {
        $tmp[] = str_replace('%20','_',trim($item));
    }
}

if (!$tmp[1 - _HOST_]) {
    $tmp[1 - _HOST_] = 'home';
}
$_GET['job'] = $tmp[1 - _HOST_];
$_GET['all'] = $tmp;

if ($_SERVER['REQUEST_METHOD'] != 'GET' && $_SERVER['REQUEST_METHOD'] != 'POST' ) {
    $error['code'] = 'AR001';
    require 'pages/error.php';
}
//if ($_GET['pages'] != 'process' || $_POST['todo'] != 'upload') {
//    unset($_FILE);
//    unset($_FILES);
//}
unset($_REQUEST);
//unset($_SERVER);
// @TODO Clean SERVER vars
unset($_ENV);
//unset($GLOBALS);
