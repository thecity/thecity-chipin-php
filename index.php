<?php

/* *******************************************
 // This is a demo file to show usage.
 //
 // @package TheCity-Chipin
 // @author Wes Hays <wes@onthecity.org>
 ******************************************* */

require_once 'city_keys.php';

require_once 'lib/the_city_chipin.php';

$campus_id = '32316'; // Sparks campus
$fund_id = '10549'; // Sparks Expansion 2013
$start_date = '2013-07-01';
$end_date = '2013-07-20';

$campuses = TheCityChipin::campus_options($api_key, $user_token);
print_r($campuses);

foreach($campuses as $id => $name) {
  echo "$name ($id) \n";
  print_r(TheCityChipin::fund_options($api_key, $user_token, $id));
}


$chipin = new TheCityChipin($api_key, $user_token, $campus_id, $fund_id, $start_date, $end_date);

print_r($chipin->donations());
print_r($chipin->designation_totals());

?>