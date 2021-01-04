<?php
error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('html_errors', false);


  /***********************************************************************************
  * Class - represents a set of data (fusion genes)                                  *
  * First in the class hierachy                                                      *
  * Extends CLI                                                                      *
  ***********************************************************************************/
  class ifusefilevalidator {
      private $data           = array();
      private $CI             = null;
      private $defaultHeaders = array(
        ''                          => '/^([0-9]*)$/', // csv-file row number
        'Row.names'                 => '/^(([0-9]*)|NA)$/',
        //'Sample.ID'                 => '/^([0-9a-zA-Z.]*)$/',
        'Related.Junctions'         => '/^(rj([0-9]+)|NA)$/',
        'Associated.Junctions'      => '/^(aj([0-9]+)|NA)$/',
        'Shared.Genes'              => '/^(sg([0-9]+)|NA)$/',
        'Gene.Mismatch'             => '/^(yes|no|NA)$/',
        'Single.Event'              => '/^(interchromosomal|inversion or deletion|inversion|inversion or translocation|deletion)$/',
        'Fusion.Gene'               => '/^(NA|same orientation|opposing)$/',
        'Left.Position.in.CDS'      => '/^(yes|no|NA)$/',
        'Right.Position.in.CDS'     => '/^(yes|no|NA)$/',
        'Left.Position.in.Exon'     => '/^(yes|no|NA)$/',
        'Right.Position.in.Exon'    => '/^(yes|no|NA)$/',
        //'Match.LeftNormal'          => '/^(left|right|NA)$/',
        //'Match.RightNormal'         => '/^(left|right|NA)$/',
        'Gene.Left.name2'           => '/^([a-zA-Z0-9\-]*)$/',
        'Gene.Right.name2'          => '/^([a-zA-Z0-9\-]*)$/',
        'Gene.Left.name'            => '/^(NA|(NM_(.*)))$/',
        'Gene.Left.chrom'           => '/^(NA|(chr(([0-9]*)|[xymXYM])))$/',
        'Gene.Left.strand'          => '/^(NA|\+|\-)$/',
        'Gene.Left.txStart'         => '/^(NA|([0-9]+))$/',
        'Gene.Left.txEnd'           => '/^(NA|([0-9]+))$/',
        'Gene.Left.cdsStart'        => '/^(NA|([0-9]+))$/',
        'Gene.Left.cdsEnd'          => '/^(NA|([0-9]+))$/',
        'Gene.Left.exonStarts'      => '/^(NA|(([0-9]*(\:?))*))$/',//'/^(NA|([0-9]*,))$/',
        'Gene.Left.exonEnds'        => '/^(NA|(([0-9]*(\:?))*))$/',//'/^(NA|([0-9]*,))$/',
        'Gene.Right.name'           => '/^(NA|(NM_(.+)))$/',
        'Gene.Right.chrom'          => '/^(NA|(chr(([0-9]*)|[xymXYM])))$/',
        'Gene.Right.strand'         => '/^(NA|\+|\-)$/',
        'Gene.Right.txStart'        => '/^(NA|([0-9]+))$/',
        'Gene.Right.txEnd'          => '/^(NA|([0-9]+))$/',
        'Gene.Right.cdsStart'       => '/^(NA|([0-9]+))$/',
        'Gene.Right.cdsEnd'         => '/^(NA|([0-9]+))$/',
        'Gene.Right.exonStarts'     => '/^(NA|(([0-9]*(\:?))*))$/',//'/^(NA|([0-9]*,))$/',
        'Gene.Right.exonEnds'       => '/^(NA|(([0-9]*(\:?))*))$/',//'/^(NA|([0-9]*,))$/',
        'Junction.LeftChr'          => '/^(NA|(chr(([0-9]*)|[xymXYM])))$/',
        'Junction.LeftStrand'       => '/^(NA|\+|\-)$/',
        'Junction.LeftPosition'     => '/^(NA|([0-9]+))$/',
        'Junction.LeftStart'        => '/^(NA|([0-9]+))$/',
        'Junction.LeftEnd'          => '/^(NA|([0-9]+))$/',
        'Junction.RightChr'         => '/^(NA|(chr(([0-9]*)|[xymXYM])))$/',
        'Junction.RightStrand'      => '/^(NA|\+|\-)$/',
        'Junction.RightPosition'    => '/^(NA|([0-9]+))$/',
        'Junction.RightStart'       => '/^(NA|([0-9]+))$/',
        'Junction.RightEnd'         => '/^(NA|([0-9]+))$/',
        'Junction.LeftLength'       => '/^([0-9]+)$/',
        'Junction.RightLength'      => '/^([0-9]+)$/',
        'TransitionSequence'        => '/^(([acgtACGT]*)|([#]*))$/',
        'TransitionLength'          => '/^([0-9]+)$/',
        'AssembledSequence'         => '/^([acgtACGT#]*)$/');
      private $constrains     = array(
        'Gene.Left.chrom'           => 'Junction.LeftChr',
        'Gene.Right.chrom'          => 'Junction.RightChr');
      private $options        = array(
        'hasHeader'                 => "true");
      private $addnull        = false;
      public $header = null;
      
      /**
      * Constructer for the Class
      */
      public function __construct($data = null) {
          $this->data =  $data;
          $this->CI   =& get_instance();
          $this->CI->load->library('sequenceloader');
      }
      
      /**
      * Destructer for the Class
      */
      public function __destruct() {}
      
      /**
      * Test if data is valid; in a structure that can be processed
      * $data given is processed and only real data (no header) left
      * 
      * @param array $data
      * @param array $opt
      * @param mixed &$correct_data
      * @param mixed &$error_messages
      * 
      * @return boolean
      */
      public function validate($data = null, $opt = null, &$correct_data = null, &$error_messages = null) {
          $data   = is_null($data) ? (is_array($this->data) ? $this->data : self::string_to_table_array($this->data)) : (is_array($data) ? $data : self::string_to_table_array($data));
          $header = array_keys($this->defaultHeaders);
          $error  = false;
          $table  = array();
                    
          if (is_array($opt)) {
              $optKeys = array_keys($opt);
              for ($i = 0; $i < sizeof($opt); $i++) {
                  $this->options[$key[$i]] =& $opt[$i];
              }
          }
                    
          
          if (is_array($data)) {  
              $temp  = array();
              $errm  = array();
              
              if (strtolower(self::getOption('hasHeader'))=="true") { // should have a header, else use perdefined default order of columns
                  $header = $data[0];
                  $this->header = $header;
                  unset($data[0]);
                  $data = array_values($data);
              }
              
              
              // Convert CG to our standard
              $CGarray = array(
                  'Junction CG.ID'              => 'Row.names',
                  'Junction TransitionLength'   => 'TransitionLength',
                  'Junction TransitionSequence' => 'TransitionSequence',
                  'Junction AssembledSequence'  => 'AssembledSequence'
              );
              //var_dump($this->header[43],$this->header[44],$header[43],$header[44]);//remove
              $cgKey = array_keys($CGarray);
              for ($i=0; $i<sizeof($CGarray); $i++) {//here
                  for ($j=0; $j<sizeof($header); $j++) {
                      if ($header[$j] == $cgKey[$i]) {
                          $header[$j] = $CGarray[$cgKey[$i]];
                      }
                  }
              }
              //var_dump($this->header[43],$this->header[44],$header[43],$header[44]);//remove
              
              if (!in_array('', $header)) {
                  $header = array_merge(array(''),$header);
                  $this->addnull = true;
                  
              }
              
              // spaces to periods
              foreach ($header as &$head) {
                  $head = preg_replace("/ /",".",$head);
              }
              
              
              // header must contain some comumns
              if (in_array(''                      ,$header) && # Row number..
                  in_array('Row.names'             ,$header) &&
                  //in_array('Sample.ID'             ,$header) &&
                  in_array('Related.Junctions'     ,$header) && 
                  in_array('Associated.Junctions'  ,$header) && 
                  in_array('Shared.Genes'          ,$header) && 
                  in_array('Gene.Mismatch'         ,$header) && 
                  in_array('Single.Event'          ,$header) && 
                  in_array('Fusion.Gene'           ,$header) && 
                  in_array('Left.Position.in.CDS'  ,$header) && 
                  in_array('Right.Position.in.CDS' ,$header) && 
                  in_array('Left.Position.in.Exon' ,$header) && 
                  in_array('Right.Position.in.Exon',$header) && 
                  //in_array('Match.LeftNormal'      ,$header) && 
                  //in_array('Match.RightNormal'     ,$header) && 
                  in_array('Gene.Left.name2'       ,$header) && 
                  in_array('Gene.Right.name2'      ,$header) && 
                  in_array('Gene.Left.name'        ,$header) && 
                  in_array('Gene.Left.chrom'       ,$header) && 
                  in_array('Gene.Left.strand'      ,$header) && 
                  in_array('Gene.Left.txStart'     ,$header) && 
                  in_array('Gene.Left.txEnd'       ,$header) && 
                  in_array('Gene.Left.cdsStart'    ,$header) && 
                  in_array('Gene.Left.cdsEnd'      ,$header) && 
                  in_array('Gene.Left.exonStarts'  ,$header) && 
                  in_array('Gene.Left.exonEnds'    ,$header) && 
                  in_array('Gene.Right.name'       ,$header) && 
                  in_array('Gene.Right.chrom'      ,$header) && 
                  in_array('Gene.Right.strand'     ,$header) && 
                  in_array('Gene.Right.txStart'    ,$header) && 
                  in_array('Gene.Right.txEnd'      ,$header) && 
                  in_array('Gene.Right.cdsStart'   ,$header) && 
                  in_array('Gene.Right.cdsEnd'     ,$header) && 
                  in_array('Gene.Right.exonStarts' ,$header) && 
                  in_array('Gene.Right.exonEnds'   ,$header) && 
                  in_array('Junction.LeftChr'      ,$header) && 
                  in_array('Junction.LeftStrand'   ,$header) && 
                  in_array('Junction.LeftPosition' ,$header) && 
                  in_array('Junction.LeftStart'    ,$header) && 
                  in_array('Junction.LeftEnd'      ,$header) && 
                  in_array('Junction.RightChr'     ,$header) && 
                  in_array('Junction.RightStrand'  ,$header) && 
                  in_array('Junction.RightPosition',$header) && 
                  in_array('Junction.RightStart'   ,$header) && 
                  in_array('Junction.RightEnd'     ,$header) && 
                  in_array('TransitionSequence'    ,$header) && 
                  in_array('TransitionLength'      ,$header) && 
                  in_array('AssembledSequence'     ,$header)
              ) { // header must contain certain things
                  for ($i=0; $i<count($data); $i++) {
                      $err = false;
                      // rows
                      if (is_array($data[$i]) && ((count($data[$i])+($this->addnull?1:0)) == (count($header)))) { // test value of cell
                          if ($this->addnull) {
                              $data[$i] = array_merge(array(''),$data[$i]);
                          }
                          
                          // cols
                          for ($k=0; ($k<(count($data[$i])-($this->addnull?1:0)) && (!$err)); $k++) {
                              $j = $k +($this->addnull?1:0);
                              if (is_array($data[$i][$j])) {
                                  $array_temp = implode(":",$data[$i][$j]);
                                  if (isset($this->defaultHeaders[$header[$j]]) && preg_match($this->defaultHeaders[$header[$j]],$array_temp)) { // test values of cell
                                      $temp[$header[$j]] =& $data[$i][$j];
                                  } else {
                                      $err = true;
                                      $error = true;
                                      /*if (empty($errm)) { //TODO
                                          echo("<table><tr><td><br />");
                                          var_dump($this->header,$this->header[$k],$k);
                                          echo("</td><td>");
                                          var_dump($header,$header[$j],$j);
                                          echo("</td><td>");
                                          var_dump($this->defaultHeaders,$this->defaultHeaders[$header[$j]],$j);
                                          echo("</td><td>");
                                          var_dump($data[$i],$data[$i][$j],$j);
                                          echo("</td></tr></table>");
                                      }*/
                                      $errm[] = "Line $i column $k ({$header[$j]}) can not be validated using REGEX(&#39;". $this->defaultHeaders[$header[$j]]."&#39;)<br />&#39;".self::limitchr($array_temp)."&#39; (array)";
                                  }
                              } else {
                                  if (isset($this->defaultHeaders[$header[$j]]) && preg_match($this->defaultHeaders[$header[$j]],$data[$i][$j])) { // test value of cell
                                      $temp[$header[$j]] =& $data[$i][$j];
                                  } elseif (!$err) {
                                      $err = true;
                                      $error = true;
                                      /*if (empty($errm)) { //TODO
                                          echo("<table><tr><td>");
                                          var_dump($this->header,$this->header[$k],$k);
                                          echo("</td><td>");
                                          var_dump($header,$header[$j],$j);
                                          echo("</td><td>");
                                          var_dump($this->defaultHeaders,$this->defaultHeaders[$header[$j]],$j);
                                          echo("</td><td>");
                                          var_dump($data[$i],$data[$i][$j],$j);
                                          echo("</td></tr></table>");
                                      }*/
                                      $errm[] = "Line $i column $k ({$header[$j]}) can not be validated using REGEX(&#39;". (isset($this->defaultHeaders[$header[$j]]) ? $this->defaultHeaders[$header[$j]] : "N/A")."&#39;)<br />&#39;".self::limitchr($data[$i][$j])."&#39;";
                                  }
                              }
                          }
                      } else {
                          $err    = true;
                          $error  = true;
                          if (count($data[$i]) == 1) {
				if ($data[$i][0] != "") {
					$errm[] = "Line ".($i+(self::getOption('hasHeader')?1:0))." does not have the same column count as the header. Its probably an empty line.";
				}
			  } else {
				$errm[] = "Line ".($i+(self::getOption('hasHeader')?1:0))." does not have the same column count as the header (".count($data[$i])."!=".count($header).")";
			  }
                      }
                      
                      // constrains
                      if (!$err) {
                          $err = self::checkConstrains($temp,$this->constrains,$errm, $i+(self::getOption('hasHeader')?1:0));
                          $error = $err?true:$error;
                      }
                      
                      // add to array
                      if (!$err) {
                          $table[$i] =& $temp;
                      }
                      
                      // add some columns
                      if (!$err) {
                          // Direction
                          $table[$i][ 'left.direction'] = ((strtolower($table[$i][ "Gene.Left.strand"]) == 'na' ? 0 : intval($table[$i]["Gene.Left.strand" ].'1')) * intval($table[$i]["Junction.LeftStrand" ].'1'));
                          $table[$i]['right.direction'] = ((strtolower($table[$i]["Gene.Right.strand"]) == 'na' ? 0 : intval($table[$i]["Gene.Right.strand"].'1')) * intval($table[$i]["Junction.RightStrand"].'1'));
                          
                          // Donor/Acceptor (/Left/Right) Colors
                          if       (($table[$i]['left.direction'] == $table[$i]['right.direction']) && ($table[$i]['left.direction'] == -1)) {
                              $table[$i][ 'left.color'] = '#C5007C';   $table[$i][ 'left.donor'] = false;
                              $table[$i]['right.color'] = '#8CC700';   $table[$i]['right.donor'] = true;
                          } elseif (($table[$i]['left.direction'] == $table[$i]['right.direction']) && ($table[$i]['left.direction'] ==  1)) {
                              $table[$i][ 'left.color'] = '#8CC700';   $table[$i][ 'left.donor'] = true;
                              $table[$i]['right.color'] = '#C5007C';   $table[$i]['right.donor'] = false;
                          } else {
                              $table[$i][ 'left.color'] = '#FF6600';   $table[$i][ 'left.donor'] = null;
                              $table[$i]['right.color'] = '#00A3C7';   $table[$i]['right.donor'] = null;
                          }
                          
                          // Left Event Min
                          $table[$i][ 'left.emin'] = $table[$i][ 'left.direction'] ? (
                                     $table[$i][ "Junction.LeftStrand"] == '+' ? (
                                         $table[$i][ 'Gene.Left.txStart']
                                     ) : (
                                         $table[$i][ 'Junction.LeftPosition']
                                     )
                                 ) 
                                 : 
                                 $table[$i][ 'Junction.LeftStart'];
                          // Left Event Max       
                          $table[$i][ 'left.emax'] = $table[$i][ 'left.direction'] ? (
                                     $table[$i][ "Junction.LeftStrand"] == '+' ? (
                                         $table[$i][ 'Junction.LeftPosition']
                                     ) : (
                                         $table[$i][ 'Gene.Left.txEnd']
                                     )
                                 ) 
                                 : 
                                 $table[$i][ 'Junction.LeftEnd'];
                          // Right Event Min
                          $table[$i]['right.emin'] = $table[$i]['right.direction'] ? (
                                     $table[$i]["Junction.RightStrand"] == '+' ? (
                                         $table[$i]['Junction.RightPosition']
                                     ) : (
                                         $table[$i]['Gene.Right.txStart']
                                     )
                                 ) 
                                 : 
                                 $table[$i]['Junction.RightStart'];
                          // Right Event Min
                          $table[$i]['right.emax'] = $table[$i]['right.direction'] ? (
                                     $table[$i]["Junction.RightStrand"] == '+' ? (
                                         $table[$i]['Gene.Right.txEnd']
                                     ) : (
                                         $table[$i]['Junction.RightPosition']
                                     )
                                 ) 
                                 : 
                                 $table[$i]['Junction.RightEnd'];
                          
                          // Event Length
                          $table[$i][    'left.elength'] = self::getDifference(intval($table[$i][ "left.emin"]),intval($table[$i][ "left.emax"]));
                          $table[$i][   'right.elength'] = self::getDifference(intval($table[$i]["right.emin"]),intval($table[$i]["right.emax"]));
                          $table[$i][        'e.length'] = $table[$i][ 'left.elength'] + $table[$i]['right.elength'];
                          
                          // Reverse???
                          $table[$i][         'reverse'] = (($table[$i][ 'left.direction'] == 0) || ($table[$i]['right.direction'] == 0 )) ?  (($table[$i][ 'left.direction'] == -1) || ($table[$i]['right.direction'] == -1)) : ($table[$i][ 'left.direction'] == $table[$i]['right.direction'] &= -1);
                          $table[$i][            'both'] = (($table[$i][ 'left.direction'] == 1) || ($table[$i]['right.direction'] == -1));
                          
                          // Complement???
                          $table[$i][ 'left.complement'] = $table[$i][ 'left.direction'] == -1;
                          $table[$i]['right.complement'] = $table[$i]['right.direction'] == -1;
                          
                          // Add full length Sequence coordinates
                          $this->CI->sequenceloader->add_coordinates($table[$i][  'Gene.Left.chrom'],$table[$i][  'Gene.Left.txStart'],$table[$i][  'Gene.Left.txEnd']);
                          //$this->CI->sequenceloader->add_coordinates($table[$i][ 'Junction.LeftChr'],$table[$i][ 'Junction.LeftStart'],$table[$i][ 'Junction.LeftEnd']);
                          $this->CI->sequenceloader->add_coordinates($table[$i][ 'Gene.Right.chrom'],$table[$i][ 'Gene.Right.txStart'],$table[$i][ 'Gene.Right.txEnd']);
                          //$this->CI->sequenceloader->add_coordinates($table[$i]['Junction.RightChr'],$table[$i]['Junction.RightStart'],$table[$i]['Junction.RightEnd']);
                          
                      }
                      
                      unset($temp);
                  }
              } else {
                  $error = true;
                  $errm[] = "Header does not contain all the required information";
              }
                
              $correct_data   = array_values($table);
              $error_messages = $errm;
              
              return $error;
          } else {
              return !$error;
          }
      }
      
      /**
      * Split $data into rows and columns
      * 
      * @param string $data          Data from the file to analyse
      */
      private function string_to_table_array($data) {
          // rows
          $data = preg_replace("/\"/","",$data);
          $data = explode("<br />",preg_replace("/(\r\n|\r|\n)/","<br />",(string) $data));
          for ($i=0; $i<count($data); $i++) {
              // cols
              $data[$i] = explode("\t",$data[$i]);
              for ($j=0; $j<count($data[$i]); $j++) {
                  //  cells
                  if (strpos($data[$i][$j],",")) {
                      $data[$i][$j] = explode(",",$data[$i][$j]);
                  }
              }
          }
          return $data;
      }
      
      /**
      * put your comment there...
      * 
      * @param mixed $data
      * @param mixed $constrains
      * @param mixed $errm
      */
      private function checkConstrains(&$data,$constrains,&$errm, $j) {
          $err = false;
          
          if (is_array($data) && is_array($constrains)) {
              $keys = array_keys($constrains);
              for ($i=0; $i<sizeof($keys); $i++) {
                  if (isset($data[$keys[$i]]) && isset($data[$constrains[$keys[$i]]])) {
                      if (isset($data[$keys[$i]]) != isset($data[$constrains[$keys[$i]]])) {
                          $err    = true;
                          $errm[] = "Line $j can not be validated using constrain (&#39;{$keys[$i]}&#39; == &#39;{$constrains[$keys[$i]]}&#39;)";
                      }
                  }
              }
          }
          
          return $err;
      }
      
      /**
      * put your comment there...
      * 
      * @param string $key
      */
      private function getOption($key) {
          return isset($this->options[$key]) ? $this->options[$key] : null;
      }
      
      /**
      * put your comment there...
      * 
      * @param string $key
      * @param string $value
      */
      public function setOption($key, $value) {
          $this->options[$key] = $value;
      }
      
      /**
      * put your comment there...
      * 
      * @param mixed $str
      * @param mixed $len
      */
      private function limitchr($str,$len = 100) {
          return (strlen($str) > $len) ? (substr($str, 0, ($len-3)) . "...") : $str;
      }
      
      /**
      * put your comment there...
      * 
      * @param int $a
      * @param int $b
      */
      public function getDifference($a, $b) {
          $a = intval($a);
          $b = intval($b);
          return $a > $b ? $a - $b : $b - $a;
      }
  }
?>
