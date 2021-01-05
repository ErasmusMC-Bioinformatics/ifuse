<?php
  $helpFile =  <<<HELP
Description:
  .

Usage:
  >php cli.php [options]

Options:
  -help    Print this help text
  -debug   Sets error_reporting on
  -A       Prints all steps

HELP;
  error_reporting(-1); // report all errors until CLI is loaded and user preferences are set
  
  
  /***********************************************************************************
  * Class - intended to use with CLI                                                 *
  ***********************************************************************************/
    class cli {
      private $CLIvalues = Array();
      private $printAll  = false;
      
      /**
      * Constructer for the CLI Class
      */
      public function __construct() {
          if (PHP_SAPI=='cli') {
              global $argv; // values from the CLI
              for ($i=0; $i<count($argv); $i++) {
                  // parameters need to start with a '-'
                  if (strpos($argv[$i],"-") === 0) {
                      $argv[$i] = preg_replace("/^\-/","",$argv[$i]);
                      
                      // parameters can be like '-f=file.txt'
                      if (strpos($argv[$i],"=") > 0) {
                          $temp = explode("=",$argv[$i],2);
                          $this->CLIvalues[$temp[0]] = $temp[1];
                     } else {
                          // parameters can be like '-f "file.txt"' or '-f file.txt'
                          if (isset($argv[$i+1]) && !(strpos($argv[$i+1],"-") === 0) && !(strpos($argv[$i+1],"\"") === 0)) {
                              $this->CLIvalues[$argv[$i]] = $argv[($i+1)];
                          } else {
                              // parametesr can just be '-f', which results in '-f=true' as boolean
                              $this->CLIvalues[$argv[$i]] = true;
                          }
                      }
                  }
              }
          } else {
              exit("This script is intended for CLI (Command Line Interface) usage.\n");
          }
          
          // Enter debug mode, if set
          ini_set('display_errors',(self::getCLIValue("debug", false)?1:0));
          error_reporting(self::getCLIValue("debug", false)?-1:0);
          
          // Print help lines, if requested
          if (self::getCLIValue("help", false)) {
              global $helpFile;
              exit($helpFile);
          }
          
          // Enter Print All mode, if set
          print(($this->printAll = self::getCLIValue("A", $this->printAll))?"Print all steps done\t(On)\n":null);
      }
      
      /**
      * Destructer for the CLI Class
      */
      public function __destruct() {
          self::p("(Finished)",1);
      }
      
      /**
      * Get parameter value from CLI
      * 
      * @param string $key           The key of the parameter
      * @param mixed $default        Default value to return if $key is not found (default = null)
      * @return mixed
      */
      public function getCLIValue($key,$default = null) {
          // set default value
          if (!isset($this->CLIvalues[$key])) {
              $this->CLIvalues[$key] = $default;
          }
          
          $r = $this->CLIvalues[$key];
          
          // match type (sorta...)
          if ((strtolower($r) == "true") || (strtolower($r) == "false")) {
              return (strtolower($r)=="true");
          } elseif (is_numeric($r)) {
              return (integer) intval($r);
          } else {
              return $r;
          }
      }
      
      /**
      * Print text to screen if CLI parameter -A is given
      * 
      * @param mixed $str
      */
      public function p($str,$l=false) {
          if ($this->printAll) {
              print "$str" . ($l?"\n":"\t");
          }
      }
  }
?>
