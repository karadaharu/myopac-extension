<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/MyGoogleCal.php';

date_default_timezone_set('Asia/Tokyo');

class MyOpac{
  var $url_base;
  var $headers;
  var $my_google_cal;

  function MyOpac(){
    $this->url_base = "https://opac.dl.itc.u-tokyo.ac.jp";
    $this->headers = array("Accept" => "text/html");
    $this->my_google_cal = new MyGoogleCal();
  }

  /**
   * MyOpacにログイン、クッキーをヘッダーにセットする
   * @param $id int USER ID
   * @return array of string(cookie)
   */
  function setCookieOfMyOpac( $id, $pass){
    $url = $this->url_base . "/myopac/index.php";
    $params_login = array("LOGIN_USERID"=>$id, "LOGIN_PASS"=>$pass);

    $response = Unirest\Request::post($url,$this->headers, $params_login);
    $cookies = array();
    foreach( $response->headers["Set-Cookie"] as $cookie_str) {
      preg_match('/^(.+?)=(.+?);/',$cookie_str, $match);
      $cookies[$match[1]] = $match[0];
    }
    $cookies_header = implode($cookies, " ");
    Unirest\Request::cookie($cookies_header);
    echo $response->body;
    return $cookies;
  }

  /**
   * 予約を確認して必要な処理をする
   */
  function checkBooking(){
    $url_list = $this->url_base . "/opac-service/srv_odr_stat.php";
    $params_get = array("LANG"=>0, "psp"=>1, "LOGIN_FIRST"=>1);
    $response = Unirest\Request::get($url_list,$this->headers, $params_get);
    $dom = new DOMDocument;
    @$dom->loadHTML($response->body);
    $xpath = new DOMXPath($dom);

    foreach ( $xpath->query('//form/div/table/tr') as $node ) {
      if ( ($date = $xpath->query('td[position()=5]', $node)[0]->nodeValue) == NULL) {
        continue;
      }

      updateGoogleCal();

      // 延長
      if ( $date == date("Y-m-d") ) {
        $id_book = $xpath->query('td[position()=2]', $node)[0]->nodeValue;
        $id_phpsess = $xpath->query('//input[@name="PHPSESSID"]')[0]->nodeValue;
        extendDate($id_book, $id_phpsess);
      }
    }
  }

  /**
   * 予約を延長する
   *
   * @param $id_book int
   * @param $id_phpses string
   */
  function extendDate($id_book, $id_phpsess) {
    $url_extre = $this->url_base . "/opac-service/srv_odr_stat.php";
    $params_extre = array();
    $params_extre["act"] = "extre";
    $params_extre["BOOKID"] =$id_book;
    $params_extre["DISP"] = "re";
    $params_extre["LANG"] = "0";
    $params_extre["psp"] = "1";
    $params_extre["PHPSESSID"] = $id_phpsess;
    $response = Unirest\Request::post($url_extre, $this->headers, $params_extre);
  }

  /**
   * Google Calendarに返却日を入れる
   // すでにあるかどうかチェックしないといけない
   */
  function updateGoogleCal(){
    $event = array();
    $date = str_replace('.', '-', $date);
    $event['start'] = array('date' => $date);
    $event['end'] = array('date' => $date);

    $title = $xpath->query('td[position()=7]', $node)[0]->nodeValue;
    $event['summary'] = '返却期限:'.$title;
    test();
  }
}


$myopac = new MyOpac();
$myopac->setCookieOfMyOpac("6044439136", "imakarakk02");
$myopac->checkBooking();
