<?php

/* *******************************************
 // This is a demo file to show usage.
 //
 // @package TheCity-Chipin
 // @author Wes Hays <wes@onthecity.org>
 ******************************************* */

define("APIKEY","8d326a8d354fcdeb97cb2a7b83561ac654d4ad53");
define("USERTOKEN", "5a2aeeddac031b0a");     

require_once 'lib/the_city_chipin.php';

$campus_id = '76560'; // Dave H's Campus
$fund_id = '7447'; // Easter Offering Fund
$start_date = '2013-05-01';

$chipin = new TheCityChipin($campus_id, $fund_id, $start_date);

print_r($chipin->fund_options());

?>