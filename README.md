# The City Chipin PHP Library #

This PHP library is meant to be used with The City (Software to enable communication and community in your church).  

This library that wraps the donation endpoints for The City Admin API (api.onthecity.org) so that donations can be segmented for a campaign registry.  

TheCity API docs  
http://api.onthecity.org


## Requirements ##
PHP >= 5.3  
* It may work with older versions but it was developed on PHP 5.3.

## Installing ##
Clone, fork or download the package.

## Usage ##

### Submodules ###

```
~> git submodule init  
~> git submodule update  
```

The submodule is thecity-admin-php API wrapper developed by johnroberts (https://github.com/johnroberts).


### The City API Key/Token ###

```
define('APIKEY', '*** YOUR API KEY ***');  
define('USERTOKEN', '*** YOUR API TOKEN ***');  
```

### Including the library ###

```
require_once 'lib/the_city_chipin.php';  
```

### Load campus options ###

```
$campuses = TheCityChipin::campus_options();  
```


### Load fund options ###

```
$funds = TheCityChipin::fund_options($campus_id);  
```

### Load donation grouped by designation ###

```
$campus_id = '32316';    
$fund_id = '10549';         
$start_date = '2013-07-01';
$end_date = '2013-07-20';   // Optional 

$chipin = new TheCityChipin($campus_id, $fund_id, $start_date, $end_date);

$donation_designation_groups = $chipin->donations();

// The designation_totals() function can be called after donations() function has been called.
$donation_designation_totals = $chipin->designation_totals();
```
  

## WordPress ##

If you use WordPress there is a WordPress plugin that uses this library.  
https://github.com/thecity/thecity-chipin-wordpres  <== NOT SET YET
  

## Contributors

John Roberts: https://github.com/johnroberts

  
## Contributing ##

If you want to help out fork the project and send me a pull request.  You can also send me feature requests.
  
  
## License ##

This plugin is released under the MIT license. Please contact weshays  
(http://github.com/weshays) for any questions.
