<?php

define('APP_ROOT', __DIR__);

require_once APP_ROOT. '/vendor/autoload.php';
require_once APP_ROOT. '/lib/MyGoogleCal.php';


$my_google_cal = new MyGoogleCal();

/* cron への登録 */
if(($cron = popen("/usr/bin/crontab -", "w"))){
  $command = APP_ROOT . "/check.php";
  $job = "0 */5 * * * " . $command;
  fputs($cron, $job);
  pclose($cron);
}
