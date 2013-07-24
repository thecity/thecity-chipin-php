<?php

  /** 
   * Project:    TheCity-Chipin
   * File:       auto_load.php
   *
   * @author Wes Hays <wes@onthecity.org> 
   * @link https://github.com/thecity/thecity-chipin-php
   * @package TheCity
   */

  define("APIKEY","8d326a8d354fcdeb97cb2a7b83561ac654d4ad53"); // The City API key to use by default
  define("USERTOKEN", "5a2aeeddac031b0a"); // The City API user token by default

   /**
    * The path to the lib directory.
    */
  define('ONTHECITY_LIB_DIR', dirname(__FILE__));

  require_once(ONTHECITY_LIB_DIR . '/thecity-admin-php/lib/ca-main.php');
  
  // /** 
  //  * This class auto loads objects needed when requested.
  //  *
  //  * @package TheCity
  //  */
  // class OnTheCityChipinClassAutoloader {
    
  //   /**
  //    * Registers this class to be called if a requested object does not exist.
  //    */
  //   function __construct() {   
  //     spl_autoload_register(array($this, 'loader'));
  //   }
    
    
  //   /**
  //    * @ignore
  //    */
  //   private function loader($class) {  
  //     $admin_api_lib_path = ONTHECITY_LIB_DIR . '/thecity-admin-php/lib/ca-main.php';

  //     // $file_name = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $class));
  //     // if(file_exists($loaders_path . $file_name . '.php')) { require_once($loaders_path . $file_name . '.php'); }
  //     // if(file_exists($cache_path . $file_name . '.php')) { require_once($cache_path . $file_name . '.php'); }
  //     // if(file_exists($cache_db_path . $file_name . '.php')) { require_once($cache_db_path . $file_name . '.php'); }
  //     // if(file_exists($cache_file_path . $file_name . '.php')) { require_once($cache_file_path . $file_name . '.php'); }
  //     // if(file_exists($plaza_file_path . $file_name . '.php')) { require_once($plaza_file_path . $file_name . '.php'); }
  //   }
  // }
  
  
  // $autoloader = new OnTheCityChipinClassAutoloader();

?>