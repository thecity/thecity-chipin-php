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
   

  /**
   * Includes the autoloader.
   */
  include_once(dirname(__FILE__) . '/auto_load.php');


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

    // An instance of the CityAPI.
    private $ca;

    // Stores the designation totals.
    private $totals;

    /**
     *  Constructor.
     *
     * @param string $campus_id The campus to pull donations for.
     * @param string $fund_id The fund ID to pull donations for.
     * @param string $start_date The date to start polling for donations to the specified fund.
     */
    public function __construct($campus_id, $fund_id, $start_date) {
      $this->campus_id = $campus_id;
      $this->fund_id = $fund_id;
      $this->start_date = $start_date;   
      $this->ca = new CityApi(); 
      $this->totals = array();
    }


    /**
     * Returns an Array containing Fund IDs and Name.  The Array key is the fund ID and the Array value is the fund name.
     * These are only funds that can be given to online.
     *
     * Example:
     * array(7447 => 'General Fund', 10546 => 'Building Fund')
     *
     * @return Array
     */
    public static function campus_options() {  
      $ca = new CityApi(); 
      $retval = array();
      $current_page = 1;
      $total_pages = 0;

      do {
        $json = $ca->campuses_index(array('page' => $current_page));
        $results = json_decode($json, true);

        $per_page = $results['per_page'];
        $total_pages = $results['total_pages'];
        $total_entries = $results['total_entries'];
        $current_page = $results['current_page'];

        foreach ($results['campuses'] as $fund) {
          $retval[$fund['id']] = $fund['name'];
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
     * @return Array
     */
    public static function fund_options($campus_id) {  
      $ca = new CityApi(); 
      $retval = array();
      $current_page = 1;
      $total_pages = 0;

      do {
        $json = $ca->funds_index(array('page' => $current_page, 'campus_id' => $campus_id));
        $results = json_decode($json, true);

        $per_page = $results['per_page'];
        $total_pages = $results['total_pages'];
        $total_entries = $results['total_entries'];
        $current_page = $results['current_page'];

        foreach ($results['funds'] as $fund) {
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
     * @param string $white_list (optional) A while list of designations (memo field) to filter the donations on. 
     *
     * @return Array
     */
    public function donations($white_list = array()) {  
      $ca = new CityApi(); 
      $retval = array();
      $json = $ca->donations_index(
        array(
          'campus_id' => $this->campus_id, 
          'fund_id' => $this->fund_id, 
          'start_date' => $this->start_date,
          'paginate' => 'false'
        )
      );
      $donations_found = json_decode($json, true);

      foreach ($donations_found as $donation) {
        $key = preg_replace('/\s+/', ' ', trim($donation['note']));   
        $key = strtolower($key);     
        if(empty($white_list)) {
          if(empty($key)) { $key = 'unknown'; }
        } else {
          if(!in_array($key, $white_list)) { $key = null; }
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