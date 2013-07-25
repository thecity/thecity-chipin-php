<?php

  /** 
   * Project:    Plaza-PHP
   * File:       the_city.php
   *
   * @author Wes Hays <wes@onthecity.org> 
   * @link https://github.com/thecity/thecity-plaza-php
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

    // An instance of the CityAPI
    private $ca;

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
    }


    /**
     * Returns an Array containing Fund IDs and Name.  The Array key is the fund ID and the Array value is the fund name.
     * These are only funds that can be given to online.
     *
     * Example:
     * Array(7447 => 'General Fund', 10546 => 'Building Fund')
     *
     * @return Array
     */
    public static function campus_options() {  
      $ca = new CityApi(); 
      $retval = array();
      $current_page = 1;
      $total_pages = 0;
      $options = array('page' => $current_page);

      do {
        $json = $ca->campuses_index($options);
        $results = json_decode($json, true);

        $per_page = $results['per_page'];
        $total_pages = $results['total_pages'];
        $total_entries = $results['total_entries'];
        $current_page = $results['current_page'];

        foreach ($results['campuses'] as $fund) {
          $retval[$fund['id']] = $fund['name'];
        }
      } while($current_page < $total_pages);

      return $retval;
    }



    /**
     * Returns an Array containing Fund IDs and Name.  The Array key is the fund ID and the Array value is the fund name.
     * These are only funds that can be given to online.
     *
     * Example:
     * Array(7447 => 'General Fund', 10546 => 'Building Fund')
     *
     * @return Array
     */
    public static function fund_options($campus_id) {  
      $ca = new CityApi(); 
      $retval = array();
      $current_page = 1;
      $total_pages = 0;
      $options = array('page' => $current_page, 'campus_id' => $campus_id);

      do {
        $json = $ca->funds_index($options);
        $results = json_decode($json, true);

        $per_page = $results['per_page'];
        $total_pages = $results['total_pages'];
        $total_entries = $results['total_entries'];
        $current_page = $results['current_page'];

        foreach ($results['funds'] as $fund) {
          $retval[$fund['id']] = $fund['name'];
        }
      } while($current_page < $total_pages);

      return $retval;
    }
    
  }

?>