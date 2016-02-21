<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/MyOpac.php';

$myopac = new MyOpac();
$json = file_get_contents(__DIR__ . '/users.json');
$json = mb_convert_encoding($json, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
$users = json_decode($json, true)['users'];
foreach ($users as $user) {
  var_dump($user);
  $myopac->setCookieOfMyOpac($user['id'], $user['pass']);
  $myopac->checkBooking();
}
