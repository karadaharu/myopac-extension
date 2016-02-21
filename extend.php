<?php
require __DIR__ . '/vendor/autoload.php';
date_default_timezone_set('Asia/Tokyo');

$url = "https://opac.dl.itc.u-tokyo.ac.jp/myopac/index.php";
$url_list = "https://opac.dl.itc.u-tokyo.ac.jp/opac-service/srv_odr_stat.php";
$headers = array("Accept" => "text/html");
$body = array("LOGIN_USERID"=>"6044439136", "LOGIN_PASS"=>"imakarakk02");
$get_params = array("LANG"=>0, "psp"=>1, "LOGIN_FIRST"=>1);
$response = Unirest\Request::post($url,$headers, $body);

$cookies = array();
foreach( $response->headers["Set-Cookie"] as $cookie_str) {
  preg_match('/^(.+?)=(.+?);/',$cookie_str, $match);
  $cookies[$match[1]] = $match[0];
}
var_dump($cookies);

$cookies_header = implode($cookies, " ");
Unirest\Request::cookie($cookies_header);
$response = Unirest\Request::get($url_list,$headers, $get_params);

$dom = new DOMDocument;
@$dom->loadHTML($response->body);
$xpath = new DOMXPath($dom);
foreach ( $xpath->query('//div/table') as $node ) {
  foreach ($xpath->query('tr/td[position()=5]', $node) as $ch_node) { // 返却期限
    var_dump($ch_node->nodeValue);
  }
}

$event = new Google_Service_Calendar_Event(array(
  'start' => array('dateTime' => '2016-02-28T17:00:00-07:00'),
  'end' => array('dateTime' => '2016-02-28T17:00:00-09:00'),
));
$calendarId = 'primary';
$event = $service->events->insert($calendarId, $event);
printf('Event created: %s\n', $event->htmlLink);


///// Requests
// Requests::register_autoloader();
// $res = Requests::post($url, array(), $body);
// var_dump($res->headers);


////// Goutte
// $client = new Goutte\Client();
// $crawler = $client->request('post', $url, $body);
// var_dump($crawler);
// $cookies = $client->getCookieJar()->all();
// $client = new Goutte\Client();
// $client->getCookieJar()->updateFromSetCookie($cookies);
// $crawler = $client->request('get', $url_list, $get_params);
