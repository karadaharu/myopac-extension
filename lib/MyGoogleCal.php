<?php

require_once 'vendor/autoload.php';

define('APPLICATION_NAME', 'Google Calendar API PHP Quickstart');
define('CREDENTIALS_PATH', __DIR__ . '/data/calendar-token.json');
define('CLIENT_SECRET_PATH', __DIR__ . '/data/client_secret.json');

// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/calendar-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Calendar::CALENDAR)
));

class MyGoogleCal {
  var $client;
  var $service;
  var $calendarId;

  function MyGoogleCal($code = NULL) {
    $this->client = $this->getClient($code);
    $this->service = new Google_Service_Calendar($this->client);
    $this->calendarId =  'primary';
  }

  /**
   * Returns an authorized API client.
   * @return Google_Client the authorized client object
   */
  function getClient($code = NULL) {
    $client = new Google_Client();
    $client->setApplicationName(APPLICATION_NAME);
    $client->setScopes(SCOPES);
    $client->setAuthConfigFile(CLIENT_SECRET_PATH);
    $client->setAccessType('offline');

    // Load previously authorized credentials from a file.
    $credentialsPath = $this->expandHomeDirectory(CREDENTIALS_PATH);
    if (file_exists($credentialsPath)) {
      $accessToken = file_get_contents($credentialsPath);
    } else {
      if ( $code == NULL ) {
        // Request authorization from the user.
        $authUrl = $client->createAuthUrl();
        // redirect to $authUrl
        header("Location: ".$authUrl);
        die();
      }
      // printf("Open the following link in your browser:\n%s\n", $authUrl);
      // print 'Enter verification code: ';
      // $authCode = trim(fgets(STDIN));
      $authCode = $code;

      // Exchange authorization code for an access token.
      $accessToken = $client->authenticate($authCode);
      // エラー処理する

      // Store the credentials to disk.
      if(!file_exists(dirname($credentialsPath))) {
        mkdir(dirname($credentialsPath), 0700, true);
      }
      file_put_contents($credentialsPath, $accessToken);
      printf("Credentials saved to %s\n", $credentialsPath);
    }
    $client->setAccessToken($accessToken);

    // Refresh the token if it's expired.
    if ($client->isAccessTokenExpired()) {
      $client->refreshToken($client->getRefreshToken());
      file_put_contents($credentialsPath, $client->getAccessToken());
    }
    return $client;
  }

  /**
   * Expands the home directory alias '~' to the full path.
   * @param string $path the path to expand.
   * @return string the expanded path.
   */
  function expandHomeDirectory($path) {
    $homeDirectory = getenv('HOME');
    if (empty($homeDirectory)) {
      $homeDirectory = getenv("HOMEDRIVE") . getenv("HOMEPATH");
    }
    return str_replace('~', realpath($homeDirectory), $path);
  }

  /**
   * イベントを登録
   * @param array array for Google Calendar event
   */
  function insertEvent($event){
    $this->deleteSameEvent($event);
    $event = new Google_Service_Calendar_Event($event);
    $event = $this->service->events->insert($this->calendarId, $event);
    return $event;
  }

  /**
   * 同じタイトルのイベントを消す
   * @param array array for Google Calendar event
   */
  function deleteSameEvent($event) {
    $params_opt = array(
      "q" => $event["summary"]
    );
    $events_exists = $this->service->events->listEvents($this->calendarId, $params_opt);
    while(true) {
      foreach ($events_exists->getItems() as $e) {
        if ( $e->getSummary() == $event["summary"] ) {
          $this->service->events->delete($this->calendarId, $e->id);
        }
      }
      $pageToken = $events_exists->getNextPageToken();
      if ($pageToken) {
        $optParams = array('pageToken' => $pageToken);
        $events_exists = $service->events->listEvents('primary', $optParams);
      } else {
        break;
      }
    }
  }
}
