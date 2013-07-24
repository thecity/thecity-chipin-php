<?php

/* *******************************************
 // This is a demo file to show usage.
 //
 // @package TheCity-Chipin
 // @author Wes Hays <wes@onthecity.org>
 ******************************************* */

require_once 'lib/the_city_chipin.php';


$key = '123';
$token = '456';
$campus_id = '76560'; // Dave H's Campus
$fund_id = '7447'; // Easter Offering Fund
$start_date = '2013-05-01';
$chipin = new TheCityChipin($key, $token, $campus_id, $fund_id, $start_date);


?>