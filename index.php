<?php

/* *******************************************
 // This is a demo file to show usage.
 //
 // @package TheCity-Chipin
 // @author Wes Hays <wes@onthecity.org>
 ******************************************* */

require_once 'city_keys.php';

require_once 'lib/the_city_chipin.php';

$campus_id = '76560'; // Dave H's Campus
$fund_id = '7447'; // Easter Offering Fund
$start_date = '2013-05-01';

$chipin = new TheCityChipin($campus_id, $fund_id, $start_date);

$campuses = TheCityChipin::campus_options();
print_r($campuses);

foreach($campuses as $id => $name) {
  echo "$name \n";
  print_r(TheCityChipin::fund_options($id));
}

?>