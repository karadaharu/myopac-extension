<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/MyGoogleCal.php';

// $my_google_cal = new MyGoogleCal();

/* cron への登録 */
if(($cron = popen("/usr/bin/crontab -", "w"))){
  $command = __DIR__ . "/check.php";
  $job = "0 */5 * * * " . $command;
  fputs($cron, $job);
  pclose($cron);
}
