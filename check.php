<?php

define('APP_ROOT', __DIR__);
require_once APP_ROOT . '/vendor/autoload.php';
require_once APP_ROOT . '/lib/MyOpac.php';

$myopac = new MyOpac();
$json = file_get_contents( APP_ROOT . '/data/users.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$users = json_decode($json, true)['users'];
foreach ($users as $user) {
  var_dump($user);
  $myopac->setCookieOfMyOpac($user['id'], $user['pass']);
  $myopac->checkBooking();
}
