<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Userfiles {
    private $CI = null;
    public  $basepath = null;
    public  $tmppath  = null;
    private $folder   = null;
    private $config   = array();
    
    
    /**
    * put your comment there...
    * 
    */
    public function __construct() {
        $this->CI =& get_instance();
	$this->CI->load->model('session_model');
        $this->basepath = $this->tmppath = realpath(dirname(__FILE__)."/../../TMP/");
        
        $session = session_id();
        if (empty($session)) {session_start();}
        
        #if (isset($_SESSION["TIMESTAMP"]) && ($_SESSION["TIMESTAMP"] < (USER_FILES_NAME_NEW_MAP - USER_FILES_EXTRA_TIME_ON_SERVER))) {
        #    unset($_SESSION["TIMESTAMP"]);
        #}
        
        self::reset();
    }
    
    /**
    * put your comment there...
    * 
    */
    public function __destruct() {
        self::update();
        self::store();
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $file
    * @param mixed $name
    */
    public function add_file($file, $opt=array()) {
        if (!headers_sent()) {
        
            if (!isset($_SESSION["TIMESTAMP"])) {
                $_SESSION["TIMESTAMP"] = $this->CI->session_model->data['id'];
                self::update();
            }
            
            $mnt = self::getConfig("FILES_AMOUNT", "-1");
            $fid = intval($mnt) + 1;
            
            $cur_name = genRandomString() . "." . pathinfo($file["name"],PATHINFO_EXTENSION);
            
            $old = umask(0);
			
            chmod($this->folder, 0777);
            if (($opt[0] == "format:cge") || ($opt[0] == "format:fm")) {
                self::update();
                $nfile = self::getFolder().'/'.$_FILES["iFile"]["name"];
                $dir = pathinfo($nfile,PATHINFO_DIRNAME);
                
                move_uploaded_file($file["tmp_name"], $nfile);
                chmod($nfile, 0777);
                
                // create files to run R script
                $in  = preg_replace("/\\\/","/","{$nfile}");
                $out = preg_replace("/\\\/","/","{$this->folder}/{$cur_name}");
                $r   = preg_replace("/\\\/","/","{$this->folder}/R_script.r");
                $awk = preg_replace("/\\\/","/","{$this->folder}/../../R/fusionmap2ifuse.sh");
                $sh  = preg_replace("/\\\/","/","{$this->folder}/shell.".((strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') ? "sh" : "bat"));
                preg_match_all("/([\d]+)/",$opt[2],$match);
                $hg  = (isset($match[0][0])) ? $match[0][0] : 19;
                #$hg = 19;
                
                $fh = fopen($r, 'w');
                fwrite($fh, 
                    "filein<-\"{$in}_\"\n".
                    "fileout<-\"{$out}\"\n".
                    "hgfile<-\"".preg_replace("/\\\/","/","{$this->folder}/../../R/ucscgeneshg{$hg}.txt")."\"\n".
                    "ogdata<-read.delim(filein,sep=\"\\t\",quote=\"\",row.names=1,stringsAsFactors=F)\n".
                    "source(\"".preg_replace("/\\\/","/","{$this->folder}/../../R/ngs_sv_prelims.R")."\")\n".
                    "source(\"".preg_replace("/\\\/","/","{$this->folder}/../../R/ngs_sv.R")."\")\n");
                fclose($fh);
				chmod($r, 0777);
                
                $fh = fopen($sh, 'w');
				$content = "";
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$content = //"set\n".
						"dir \"{$this->folder}\"\n".
						'sed -e "/^#/d" -e "s/\t$//g" -e "/^$/d" "'.$in."\" > \"{$in}_\"\n".
						'R -f "'.$r.'"'."\n".
						'del "'.preg_replace("/\\//","\\","{$r}")."\"\n".
						'del "'.preg_replace("/\\//","\\","{$in}")."\"\n".
						'del "'.preg_replace("/\\//","\\","{$in}_")."\"\n";
				} elseif (strtoupper(substr(PHP_OS, 0, 3)) === 'LIN') {
					$content = //"set\n".
						'dir "'.preg_replace("/\\\\/","/","{$this->folder}")."\"\n".
						(($opt[0] == "format:fm")?"sh {$awk} \"".$in."\" \"".$in."_\"\n":"").                // AWK fusionMap to iFuse
						(($opt[0] == "format:fm")?"mv -f \"".$in."_\" \"".$in."\"\n":"").                    // back to origional file
						'sed -e "/^#/d" -e "s/\t$//g" -e "/^$/d" "'.$in."\" > \"{$in}_\"\n".                 // remove columns at the end of each row and leading (comment) rows
						'R -f "'.$r.'"'."\n".                                                                // compute relationships etc... (Elizabeths R)
						'rm "'.preg_replace("/\\\\/","/","{$r}")."\"\n".                                     // 
						'rm "'.preg_replace("/\\\\/","/","{$in}")."\"\n".                                    // 
						'rm "'.preg_replace("/\\\\/","/","{$in}_")."\"\n";                                   // 
				}
				fwrite($fh, $content);
                fclose($fh);
				chmod($sh, 0777);
                
                // Run program background
                //execInBackground($sh); //helper
                $out = shell_exec($sh);
                unlink($sh);
                //print($out);
                
                // Delete Temp. files
                if(file_exists($out)) {
                    chmod($out, 0777);
                }
                
                
                //recurse_copy($src,$dest); //helper
                //$this->userfiles->setConfig('testkey','testdescription');
            } elseif ($opt[0] == "format:tdf") {
                rename($file["tmp_name"], $this->folder . "/" . $cur_name);
            }
            if (file_exists($this->folder . "/" . $cur_name)) {
                chmod($this->folder . "/" . $cur_name, 0777);
            }
            umask($old);
            
            $reference =  $this->sequenceloader->opt["reference"];            
            $seqDir = "{$this->userfiles->basepath}/../R/{$reference}/";
				
				
            if (is_dir($seqDir)) {
                // create multiple files
                try{
                    $filename = $this->folder . "/" . $cur_name;
                    $numoffiles = splitfile($filename, 250, (($opt[1]=="header:true")?1:0));
                    $isset = false;
                    for ($i = 0; $i < $numoffiles; $i++) {
                        if (!$isset) {
                            self::setConfig("FILES_AMOUNT"        , $fid);
                            self::setConfig("CURRENT_FILE"        , $fid);
                            //$isset = true;
                        }
                        self::setConfig("FILES[{$fid}][CUR_NAME]" , "{$cur_name}.{$i}");
                        self::setConfig("FILES[{$fid}][TIMESTAMP]", date("F j, Y, g:i a"));
                        self::setConfig("FILES[{$fid}][ORG_NAME]" , pathinfo($file["name"], PATHINFO_BASENAME) . " (part {$i})");
                        self::setConfig("FILES[{$fid}][OPTIONS]"  , implode(";",$opt));
                        $fid++;
                    }
                    unlink($filename);
                } catch (Exception $e) {
                    //echo($e->getMessage()."<br />\n");
                    # File could not be analysed, probably because the file does not exist (not created by R script if file is incorrect)
                }
            } else {
                // create only one file
                self::setConfig("FILES_AMOUNT"            , $fid);
                self::setConfig("CURRENT_FILE"            , $fid);
                self::setConfig("FILES[{$fid}][CUR_NAME]" , "{$cur_name}");
                self::setConfig("FILES[{$fid}][TIMESTAMP]", date("F j, Y, g:i a"));
                self::setConfig("FILES[{$fid}][ORG_NAME]" , pathinfo($file["name"], PATHINFO_BASENAME));
                self::setConfig("FILES[{$fid}][OPTIONS]"  , implode(";",$opt));
            }
                
            self::store();
            self::reset();
            return $fid;
        } else return false;
    }
    
    /**
    * put your comment there...
    * 
    * @param string $key
    * @param mixed $value
    */
    public function setConfig($key, $value) {
        $this->config[$key] = $value;
    }
    
    /**
    * put your comment there...
    * 
    * @param string $key
    * @param mixed $default
    */
    public function getConfig($key, $default = null) {
        if (!isset($this->config[$key]) && ($default != null)) {
            self::setConfig($key, $default);
        }
        return isset($this->config[$key]) ? $this->config[$key] : $default;
    }
    
    /**
    * put your comment there...
    * 
    */
    public function update() {
        if (isset($_SESSION["TIMESTAMP"])) {
            $this->folder = $this->basepath . "/" . $_SESSION["TIMESTAMP"];
            
            // make dir if not exists
            if (!is_dir($this->folder) && !file_exists($this->folder)) {
                #self::createPathToFile($this->folder);
				$old = umask(0);
				
                mkdir($this->folder,0777,true);
				chmod($this->folder, 0777);
				umask($old);
            }
        } else {
            $this->folder = null;
        }
    }
    
    /**
    * put your comment there...
    * 
    */
    public function reset() {
	$_SESSION["TIMESTAMP"] = $this->CI->session_model->data['id'];

        if (isset($_SESSION["TIMESTAMP"])) {
            $this->folder = $this->basepath . "/" . $_SESSION["TIMESTAMP"];
            $this->config = array();
            
            if ((is_dir($this->folder)) && ($config = self::read_file($this->folder . "/Config.bak"))) {
                $config = preg_split("/\n/",$config);
                
                for ($i = 0; $i < sizeof($config); $i++) {
                    $config[$i] = preg_split("/=/",$config[$i],2);
                    if (sizeof($config[$i]) == 2) {
                        $this->config[$config[$i][0]] = $config[$i][1];
                        
                    }
                }
            }
        } else {
            $this->folder = null;
            $this->config = array();
        }
    }
    
    /**
    * put your comment there...
    * 
    */
    private function store() {
        self::update();
        
        if (isset($_SESSION["TIMESTAMP"])) {
            if (is_dir($this->folder)) {
                $data = "";
                
                foreach ($this->config as $key => $value) {
                    if ($value == null) {$value=0;}
                        $data .= $key . "=" . $value . "\n";
                    //}
                }
                
                self::write_file($this->folder . "/Config.bak", $data);
            }
        }
    }
    
    /**
    * put your comment there...
    * 
    */
    private function read_file($file) {
        $data = false;
        if (file_exists($file)) {
            $handle = fopen($file, 'r');
            $data = filesize($file) > 0 ? fread($handle, filesize($file)) : "";
            fclose($handle);
        }
        return $data;
    }
    
    /**
    * put your comment there...
    * 
    * @param string $file
    */
    /*private function createPathToFile($file) {
        $file = preg_split("/[\/\\\]/",$file);
        array_pop($file);
        $path = null;
        
        foreach ($file as $seg) {
            $path .= $seg . (($path == null)?null:'/');
            if (!empty($path) && !is_dir($path)) {
                mkdir($path);
            }
        }
    }*/
    
    /**
    * put your comment there...
    * 
    */
    private function write_file($file, $data) {
        $r = true;
        try {
            #self::createPathToFile($file);
            $dir = pathinfo($file,PATHINFO_DIRNAME);
            if (!file_exists($dir)) {
                $old = umask(0);
                mkdir($dir,0777,true);
				chmod($dir, 0777);
				umask($old);
            }
            
			// fix
			$old = umask(0);
			chmod($dir, 0777);
			umask($old);
			
            $handle = fopen($file, 'w');
			
			$old = umask(0);
			chmod($file,0777);
			umask($old);
			
            fwrite($handle, $data);
            fclose($handle);
            return $r;
        } catch (Exception $e) {
            $r =  $e->getMessage();
        }
        return $r;
    }
    
    /**
    * put your comment there...
    * 
    */
    public function getBasePath() {
        return $this->basepath;
    }
    
    /**
    * put your comment there...
    * 
    */
    public function getFolder() {
        return $this->folder;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $id
    * @return mixed
    */
    public function getFile($id = null, $opt = "CUR_NAME") {
        $id = $id == null ? self::getConfig("CURRENT_FILE") : $id;
        $r = ($opt == "CUR_NAME") ? self::getFolder() . "/" : "";
        
        return !is_null($id) ? $r . self::getConfig("FILES[" . $id . "][" . $opt ."]")  : null;
    }
    
    /**
    * put your comment there...
    * 
    * @param mixed $id
    */
    public function removeFile($id = null) {
        $id = $id == null ? self::getConfig("CURRENT_FILE") : $id;
        $delfile = self::getFile($id);
        
        if ($id == self::getConfig("CURRENT_FILE")) {
            for ($i=self::getConfig('FILES_AMOUNT'); $i>=0; $i--) {
                if ($i != $id) {
                    $file = self::getFile($i);
                    if (file_exists($file) && is_file($file)) {
                        self::setConfig("CURRENT_FILE",$i);
                        break;
                    }
                }
            }
        }
        
        if (file_exists($delfile)) {
            if (!is_null($delfile) && file_exists($delfile) && is_file($delfile)) {
                unlink($delfile);
            }
            if (!is_null($delfile."seq") && file_exists($delfile."seq") && is_file($delfile."seq")) {
                unlink($delfile."seq");
            }
        }
        
        
        
        if (!file_exists($delfile)) {
            self::setConfig("FILES[$id][CUR_NAME]" ,null);
            self::setConfig("FILES[$id][TIMESTAMP]",null);
            self::setConfig("FILES[$id][ORG_NAME]" ,null);
            self::setConfig("FILES[$id][OPTIONS]"  ,null);
            return true;
        } else {
            return false;
        }
    }

    public function getFileCount() {
	$tn = ($this->getConfig("FILES_AMOUNT", -1)+1);
	$n = 0;
	
	for ($i = 0; $i < $tn; $i++) {
		$filename = $this->getFile($i);
		if (file_exists($filename) && is_file($filename)) {
			$n++;
		}
	}
	return $n;
    }
}


# The split-file function
function splitfile($readfilename, $maxlines = 500, $headerlines = 0, $mode = 'w', $chmod = 0755) {
    # Validate input
    $correctinput = (
        is_string ($readfilename) && # Name of the file to be split
        is_integer($maxlines    ) && # Max amount of lines per file.
        is_integer($headerlines ) && # Amount of lines representing the header of each file
        is_integer($chmod       ) && # Set new file permissions (CHMOD) to ...
        (   is_string($mode     ) && # fopen() - function mode (write, append, etc)
            preg_match(
                "/^(w|w+|a|a+|x|x+|c|c+)$/",
                strtolower($mode)
            ) === 1
        )
    );
    
    # Amount of files created
    $amountoffiles = 0;
    
    # File exists and readable?
    if (is_readable($readfilename) && $correctinput) {
        # Set umask
        $oldmask       = umask(0777);
        
        # Read file
        $readfile      = file($readfilename);

        # Header extraction
        $header = array();
        foreach ($readfile as $line_num => &$line) {
            # If header has equel or more than the given amount of lines, break out of the loop
            if (sizeof($header) >= $headerlines) {break;}
            
            # Make link to line in file
            $header[$line_num] =& $line;
            
            # Break link to line
            unset($readfile[$line_num]);
        }
        
        # Ammount of lines is reduced by header
        $maxlines -= sizeof($header);
        
        # Open new file
        $currentfile   = "{$readfilename}.{$amountoffiles}";
        $filehandle    = fopen($currentfile, $mode);
        
        # Dump header in file.
        fwrite($filehandle, (implode("\n",$header)));
        
        # Per line writing/appending to the new file
        foreach ($readfile as $line_num => &$line) {
            # Max line count? Switch file!
            if (($line_num != 0) && (($line_num % $maxlines) == 0)) {
                # Close current filehandle
                fclose($filehandle);
                chmod($currentfile, $chmod);
                
                # Open new filehandle
                $amountoffiles = $line_num / $maxlines;
                $currentfile = "{$readfilename}.{$amountoffiles}";
                $filehandle = fopen($currentfile, $mode);
                
                # Dump header in file.
                fwrite($filehandle, (implode("\n",$header)));
            }
            # Write
            fwrite($filehandle, $line);
        }

        # Close file handle
        fclose($filehandle);
        chmod($currentfile, $chmod);
        
        # Reset umask
        umask($oldmask);
        
        # First file created ended with a zero. (Zero-indexed)
        $amountoffiles++;
    } elseif (!$correctinput) {
        # Error: parameters are incorrect
        throw new Exception("Check your parameters");
    } else {
        # Error: no such file
        $currentdir = shell_exec("pwd");
        throw new Exception("No such file or file is not readable ('{$readfilename}' in '{$currentdir}')");
    }
    
    # Return amount of files created
    return($amountoffiles);
}

/* End of file userfiles.php */
/* Location: ./application/libraries/userfiles.php */
