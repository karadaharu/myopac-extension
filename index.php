<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/MyGoogleCal.php';


$my_google_cal = new MyGoogleCal();
$event = array(
  'start' => array('dateTime' => '2016-03-28T17:00:00-07:00'),
  'end' => array('dateTime' => '2016-03-28T17:00:00-09:00'),
);
$my_google_cal->insertEvent($event);

// // Get the API client and construct the service object.
// $client = getClient($_GET['code']);
// $service = new Google_Service_Calendar($client);

// $event = new Google_Service_Calendar_Event(array(
//   'start' => array('dateTime' => '2016-02-28T17:00:00-07:00'),
//   'end' => array('dateTime' => '2016-02-28T17:00:00-09:00'),
// ));
// $calendarId = 'primary';
// $event = $service->events->insert($calendarId, $event);

// // // Print the next 10 events on the user's calendar.
// $calendarId = 'primary';
// $optParams = array(
//   'maxResults' => 10,
//   'orderBy' => 'startTime',
//   'singleEvents' => TRUE,
//   'timeMin' => date('c'),
// );
// $results = $service->events->listEvents($calendarId, $optParams);
// //
// if (count($results->getItems()) == 0) {
//   print "No upcoming events found.\n";
// } else {
//   print "Upcoming events:\n";
//   foreach ($results->getItems() as $event) {
//     $start = $event->start->dateTime;
//     if (empty($start)) {
//       $start = $event->start->date;
//     }
//     printf("%s (%s)\n", $event->getSummary(), $start);
//   }
// }
