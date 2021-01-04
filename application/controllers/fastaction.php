<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fastaction extends CI_Controller {
    public $fileName = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->fileName = $this->userfiles->getFile();
    }
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/analyse
     *    - or -  
     *         http://example.com/index.php/analyse/index
     */
    public function index() {
        $segment = $this->uri->segment(2);
        
        list($key,$value) = preg_split("/:/",$segment);
        
        if (!empty($key)) {
            $this->userfiles->setConfig($key,$value);
        }
        
        echo("<html><head><title> </title><script language=\"JavaScript\">function refreshParent() {window.opener.location.href = window.opener.location.href; if (window.opener.progressWindow){window.opener.progressWindow.close();}window.close();}</script></head><body onunload=\"refreshParent()\"><script type=\"text/javascript\">window.close();</script></body></html>");
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
