<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/MyGoogleCal.php';

$my_google_cal = new MyGoogleCal();
$event = array(
  'summary' => "返却期限:確率論 / 舟木直久著",
  'start' => array('date' => '2016-03-29'),
  'end' => array('date' => '2016-03-29')
);
$my_google_cal->insertEvent($event);
