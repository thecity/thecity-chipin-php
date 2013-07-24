<?php

  /** 
   * Project:    Plaza-PHP
   * File:       the_city.php
   *
   * @author Wes Hays <wes@onthecity.org> 
   * @link https://github.com/thecity/thecity-plaza-php
   * @version 1.0a
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

    // The City Admin API key.
    private $key;

    // The City Admin API token.
    private $token;

    // The date to start checking for donations for the specified fund_id.
    private $start_date;    

    /**
     *  Constructor.
     *
     * @param string $key The City Admin API key.
     * @param string $token The City Admin API token.
     * @param string $campus_id The campus to pull donations for.
     * @param string $fund_id The fund ID to pull donations for.
     * @param string $start_date The date to start polling for donations to the specified fund.
     */
    public function __construct($key, $token, $campus_id, $fund_id, $start_date) {
      $this->key = $key;
      $this->token = $token;
      $this->campus_id = $campus_id;
      $this->fund_id = $fund_id;
      $this->start_date = $start_date;    
    }

    public function load_groups() {
      $ca = new CityApi();
      $results = $ca->groups_count();
      echo $results;
    }
    
  }

?>