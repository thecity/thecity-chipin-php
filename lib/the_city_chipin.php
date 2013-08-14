<?php

  /** 
   * Project:    Plaza-PHP
   * File:       the_city.php
   *
   * @author Wes Hays <wes@onthecity.org> 
   * @link https://github.com/thecity/thecity-chipin-php
   * @version 1.0
   * @package TheCity
   */
   

  include_once(dirname(__FILE__) . '/thecity-admin-php/lib/ca-main.php');   


  /** 
   * This class is meant to be a wrapper for the OnTheCity.org API.
   *
   * @package TheCity
   */
  class TheCityChipin {

    // The campus to pull donations for.
    private $campus_id;

    // The fund ID to pull donations for.
    private $fund_id;

    // The date to start checking for donations for the specified fund_id.
    private $start_date;   

    // The last date for checking donations for the specified fund_id.
    private $end_date;   

    // An instance of the CityAPI.
    private $ca;

    // Stores the designation totals.
    private $totals;

    /**
     *  Constructor.
     * @param string $apikey The City API key to use.
     * @param string $usertoken The City API user token. 
     * @param string $campus_id The campus to pull donations for.
     * @param string $fund_id The fund ID to pull donations for.
     * @param string $start_date The date to start polling for donations to the specified fund.
     * @param string $end_date The last date to check for donations to the specified fund.
     */
    public function __construct($apikey, $usertoken, $campus_id, $fund_id, $start_date, $end_date = null) {
      $this->campus_id = $campus_id;
      $this->fund_id = $fund_id;
      $this->start_date = $start_date;   
      $this->end_date = $end_date;   
      $this->totals = array();

      $this->ca = new CityApi(); 
      $this->ca->set_key($apikey);
      $this->ca->set_token($usertoken);
    }


    /**
     * Returns an Array containing Campus IDs and Name.  The Array key is the campus ID and the Array value is the campus name.
     * These are only funds that can be given to online.
     *
     * Example:
     * array(24532 => 'Reno', 34354 => 'Sparks')
     *
     * @param string $apikey The City API key to use.
     * @param string $usertoken The City API user token.      
     *
     * @return Array
     */
    public static function campus_options($apikey, $usertoken) {  
      $ca = new CityApi(); 
      $ca->set_key($apikey);
      $ca->set_token($usertoken);

      $retval = array();
      $current_page = 1;
      $total_pages = 0;

      do {
        $json = $ca->campuses_index(array('page' => $current_page));
        $results = json_decode($json, true);

        $per_page = isset($results['per_page']) ? $results['per_page'] : 0;
        $total_pages = isset($results['total_pages']) ? $results['total_pages'] : 0;
        $total_entries = isset($results['total_entries']) ? $results['total_entries'] : 0;
        $current_page = isset($results['current_page']) ? $results['current_page'] : 0;
        $campuses = isset($results['campuses']) ? $results['campuses'] : array();

        foreach ($campuses as $campus) {
          $retval[$campus['id']] = $campus['name'];
        }
        $current_page++;
      } while($current_page < $total_pages);

      return $retval;
    }



    /**
     * Returns an Array containing Fund IDs and Name.  The Array key is the fund ID and the Array value is the fund name.
     * These are only funds that can be given to online.
     *
     * Example:
     * array(7447 => 'General Fund', 10546 => 'Building Fund')
     *
     * @param string $apikey The City API key to use.
     * @param string $usertoken The City API user token.   
     *
     * @return Array
     */
    public static function fund_options($apikey, $usertoken, $campus_id) {  
      $ca = new CityApi(); 
      $ca->set_key($apikey);
      $ca->set_token($usertoken);

      $retval = array();
      $current_page = 1;
      $total_pages = 0;

      do {
        $json = $ca->funds_index(array('page' => $current_page, 'campus_id' => $campus_id));
        $results = json_decode($json, true);

        $per_page = isset($results['per_page']) ? $results['per_page'] : 0;
        $total_pages = isset($results['total_pages']) ? $results['total_pages'] : 0;
        $total_entries = isset($results['total_entries']) ? $results['total_entries'] : 0;
        $current_page = isset($results['current_page']) ? $results['current_page'] : 0;
        $funds = isset($results['funds']) ? $results['funds'] : array();

        foreach ($funds as $fund) {
          $retval[$fund['id']] = $fund['name'];
        }
        $current_page++;
      } while($current_page < $total_pages);

      return $retval;
    }


    /**
     * Returns an array of confirmed donations grouped by the memo field which was used to specify the designation 
     * for the fund: chairs, sound equipment, classrooms, etc.
     *
     * Example:
     * array('chairs' => array( array('entered_on' => '2013-05-01', 'amount_cents' => 2500), 
     *                          array('entered_on' => '2013-05-02', 'amount_cents' => 3500), 
     *                          array('entered_on' => '2013-05-02', 'amount_cents' => 10000)),
     *       'classrooms' => array( array('entered_on' => '2013-05-01', 'amount_cents' => 7500), 
     *                              array('entered_on' => '2013-05-01', 'amount_cents' => 4500), 
     *                              array('entered_on' => '2013-05-01', 'amount_cents' => 20000))    
     *
     *
     * @param string $apikey The City API key to use.
     * @param string $usertoken The City API user token.   
     * @param string $white_list (optional) A while list of designations (memo field) to filter the donations on. 
     *
     * @return Array
     */
    public function donations($white_list = array()) {  
      $retval = array();
      $options = array(
        'campus_id' => $this->campus_id, 
        'fund_id' => $this->fund_id, 
        'start_date' => $this->start_date,
        'paginate' => 'false'
      );
      if(!empty($this->end_date)) { $options['end_date'] = $this->end_date; }
      $lowercase_white_list = array_map('strtolower', $white_list);
      
      $json = $this->ca->donations_index($options);
      $donations_found = json_decode($json, true);

      foreach ($donations_found as $donation) {
        $key = preg_replace('/\s+/', ' ', trim($donation['note']));   
        $key = strtolower($key);     
        if(empty($lowercase_white_list)) {
          if(empty($key)) { $key = 'unknown'; }
        } else {
          if(!in_array($key, $lowercase_white_list)) { $key = null; }
        }
        if($key != null) {
          if(!array_key_exists($key, $retval)) { 
            $retval[$key] = array(); 
            $this->totals[$key] = 0;
          }
          $amount_cents = intval(floatval($donation['amount']) * 100);
          $retval[$key][] = array('amount_cents' => $amount_cents, 'entered_on' => $donation['date']);
          $this->totals[$key] += $amount_cents;
        }
      }

      return $retval;
    }


    /**
     * Returns an array of designations and their totals. The totals are in cents.
     *
     * Example:
     * array('chairs' => 74500, 'classrooms' => 98500)    
     *
     * @return Array
     */
    public function designation_totals() {
      return $this->totals;
    }
    
  }

?>