<?php
/**
 * Handles R commands
 * By Don De Lange
 * Modified by Jos van Nijnatten
 */    

if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class R_handler {   
    public function __construct() {}
   
   /**
    * Run an R file
    *
    * @access  public
    * @param   string   file        R file
    * @return  string   result     result string
    */

   function runFile($filepath, $input_file, $groupinfo_file, $output_path){       
       $command = "R --no-save --no-restore -q --file=$filepath $input_file $groupinfo_file $output_path";
                                                                                           
       //print "COMMAND:<br /><br />$command";exit;
       $result = shell_exec($command);

       shell_exec("cd $output_path;mogrify -rotate 90 -density 128 -fuzz 20% -transparent white -format png *.ps;rm *.ps");
        
       return nl2br($result);
    }
    
    
   /**
    * Get the installed version of R
    *
    * @access  public
    * @return  string   version     version number
    */ 
   function getRVersion(){
        $words = preg_split("/ /", shell_exec("R --version"));
        
        if(count($words)>2){            return $words[2];
        }else{                          return null;        
        }
    }
}
?>