<?php
  Class SequenceLoader {
      private      $preurl = "http://genome.ucsc.edu/cgi-bin/das/*/dna?";
      private          $CI = null;
      public          $opt = null;
      private $coordinates = array();
      public    $sequences = array();

      public function __construct() {
          $this->CI =& get_instance();
          $this->CI->load->library('userfiles')

      public function __destruct() {}

      public function add_coordinates($chrom,$start,$end) {
          if ((strtolower($chrom) != 'na') && (strtolower($start) != 'na') && (strtolower($end) != 'na')) {
              $this->coordinates[$chrom][$start][$end] = null;
          }
      }

      public function concat_sequences(&$dataset) {
          if (is_array($dataset) && (sizeof($dataset) > 0) && !isset($dataset[0]['Gene.Left.Sequence']) ) {
              $currentFile = $this->CI->userfiles->getFile().".seq";
              $sequences =& $this->sequences;;
              $tmp = array_filter(explode(";",$this->CI->userfiles->getFile($currentFile,'OPTIONS')));
              while($temp = each($tmp)) {
                list($key, $value) = explode(":", $temp[1]);
                $this->opt[strtolower($key)] = $value;
              }



              // open genomic reference files
              $seqDir = "{$this->CI->userfiles->basepath}/../R/{$this->opt['reference']}/";
              if (is_dir($seqDir)) {
                  $this->CI->ifuseloader->organize($dataset);

                  if (is_array($this->coordinates)) {
                      $chroms = array_keys($this->coordinates);
                      foreach ($chroms as $chrom) {
                          $chromfile = $seqDir.$chrom;
                          $cur_chrom = preg_replace("/(chr|CHR)/","",$chrom); // Current Chromosome!
                          $old = umask(0777);
                          if (is_file($chromfile)) {
                              $handle = fopen($chromfile, 'r'); // Open Chromosome file!
                              if (is_array($this->coordinates[$chrom])) {
                                  $starts = array_keys($this->coordinates[$chrom]);
                                  foreach ($starts as $start) {
                                      $cur_start = $start; // Current Start Position!
                                      fseek($handle,$start,SEEK_SET); // Set pointer to position...
                                      if (is_array($this->coordinates[$chrom][$start])) {
                                          $ends = array_keys($this->coordinates[$chrom][$start]);
                                          foreach ($ends as $end) {
                                              $cur_end = $end; // Current End position
                                              if (is_null($this->coordinates[$chrom][$start][$end])) {
                                                  $sequences[$cur_chrom][$cur_start][$cur_end] = fread($handle, (intval($end)-intval($start)));
                                              }
                                          }
                                      }
                                  }
                              }
                              fclose($handle);
                          }
                          umask($old);
                      }
                  }
                  $c = array_keys($sequences);
                  $s = isset($c[0]) ? array_keys($sequences[$c[0]]) : array();
                  if(isset($s[0]) && isset($sequences[$c[0]][$s[0]])) {
                      $e = array_keys($sequences[$c[0]][$s[0]]);
                  } else {
                      $e = NULL;
                  }

              // download sequence files from UCSC DAS Server to our server (file or per request)
              } else {
                  $coordinates = array();
		          if (is_array($this->coordinates)) {
			          $chromosomes = array_keys($this->coordinates);
			          foreach ($chromosomes as $chromosome) {
				          if (is_array($this->coordinates[$chromosome])) {
					          $starts = array_keys($this->coordinates[$chromosome]);
					          foreach ($starts as $start) {
						          if (is_array($this->coordinates[$chromosome][$start])) {
							          $ends = array_keys($this->coordinates[$chromosome][$start]);
							          foreach ($ends as $end) {
								          if (is_null($this->coordinates[$chromosome][$start][$end])) {
									          $coordinates[] = "segment=$chromosome:".(intval($start)+1).",$end";
								          }
							          }
						          }
					          }
				          }
			          }
		          }

		          $url = implode("&",$coordinates);
                  $pre = preg_replace("/\*/",$this->opt['reference'],$this->preurl);
		          $f_url = $pre . $url;


		          if (!file_exists($currentFile)) {
                      // download sequence files from UCSC DAS Server to our server
                      if (!ini_get('allow_url_fopen') == 0) {
                          $max = 8190; // max -length of a url (apache default)
                          if (strlen($pre . $f_url) > $max) {
                              $i=0; // offset
                              $urlsegs = array();

                              //split url in parts
                              while (($i < strlen($url)) && ($url != implode("&",$urlsegs)) && (strlen($url) != strlen(implode("&",$urlsegs)))) {
                                  if ($i == 0) {
                                      $urlsegs[] = substr($url,0,strrpos(substr($url,0,($max - strlen($pre))),"&"));
                                  } else {
                                      $urlsegs[] = substr(
                                          $url,
                                          strlen(implode("&",$urlsegs)),
                                          (
                                              strrpos(substr($url,strlen(implode("&",$urlsegs)),($max - strlen($pre))),"&") > 0 ?
                                              strrpos(substr($url,strlen(implode("&",$urlsegs)),($max - strlen($pre))),"&") :
                                              PHP_INT_MAX
                                          )
                                      );
                                  }
                                  //if ($i > 0) { break; }
                                  $i = strlen(implode("&",$urlsegs));
                              }

                              // including prefix
                              foreach ($urlsegs as &$urlseg) {
                                  $urlseg = $pre . $urlseg;
                              }
                              // now we have got several url's that can be downloaded and (TODO) need to be concatenated
                              //TODO
                              /// get all the sequences and concat them together to one file
                          } else {
                              copy($f_url, $currentFile);
                          }
			          } else {
			              $currentFile = $f_url;
			          }
                  }

                  if (file_exists($currentFile)) {
                      if ($DASDNA = @simplexml_load_file($currentFile)) {
                          foreach ($DASDNA->SEQUENCE as $sequence) {
                              preg_match("/([0-9mMxXyY]+)/", $sequence['id'],$matches);
                                  $chr = (!empty($matches[0])?$matches[0]:preg_replace("/chr/","",$sequence['id']));
                              $sequences[(string)$chr][(string)($sequence['start']-1)][(string)$sequence['stop']] = (string)str_replace(array("\r", "\n", "\t", " "), '',$sequence->DNA[0]);
                                                                               // ^ Index is zero again
                          }
                      }
                  }
              }

              if (is_array($dataset)) {
                  foreach ($dataset as &$event) {
                      $event[    'Gene.Left.Sequence'] = null;
                      $event[   'Gene.Right.Sequence'] = null;
                      $event[ 'JunctionLeft.Sequence'] = null;
                      $event['JunctionRight.Sequence'] = null;
                  }

                  $done=false;
                  foreach ($dataset as &$event) {
                      $event[    'Gene.Left.Sequence'] = isset($sequences[preg_replace("/chr/","",strtolower($event[  'Gene.Left.chrom']))][intval($event[  'Gene.Left.txStart'])][$event[  'Gene.Left.txEnd']]) ? $sequences[preg_replace("/chr/","",strtolower($event[  'Gene.Left.chrom']))][intval($event[  'Gene.Left.txStart'])][$event[  'Gene.Left.txEnd']] : null;
                      $event[   'Gene.Right.Sequence'] = isset($sequences[preg_replace("/chr/","",strtolower($event[ 'Gene.Right.chrom']))][intval($event[ 'Gene.Right.txStart'])][$event[ 'Gene.Right.txEnd']]) ? $sequences[preg_replace("/chr/","",strtolower($event[ 'Gene.Right.chrom']))][intval($event[ 'Gene.Right.txStart'])][$event[ 'Gene.Right.txEnd']] : null;
                      $event[ 'JunctionLeft.Sequence'] = isset($sequences[preg_replace("/chr/","",strtolower($event[ 'Junction.LeftChr']))][intval($event[ 'Junction.LeftStart'])][$event[ 'Junction.LeftEnd']]) ? $sequences[preg_replace("/chr/","",strtolower($event[ 'Junction.LeftChr']))][intval($event[ 'Junction.LeftStart'])][$event[ 'Junction.LeftEnd']] : null;
                      $event['JunctionRight.Sequence'] = isset($sequences[preg_replace("/chr/","",strtolower($event['Junction.RightChr']))][intval($event['Junction.RightStart'])][$event['Junction.RightEnd']]) ? $sequences[preg_replace("/chr/","",strtolower($event['Junction.RightChr']))][intval($event['Junction.RightStart'])][$event['Junction.RightEnd']] : null;
                  }
              }
          }
      }
  }

  function strand($dir) {
      return ($dir==1?'+':($dir==-1?'-':'?'));
  }
?>
