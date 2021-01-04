<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Open extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/blog
     *    - or -  
     *         http://example.com/index.php/blog/index
     */
    public function index() {
        $this->load->library('ifuseloader',array("fileName" => ""));
        
        $SESSIONID = $this->uri->segment(2, FALSE);
        
        if (!is_bool($SESSIONID) && is_numeric($SESSIONID)) {
            if (file_exists($this->userfiles->tmppath . "/" . $SESSIONID . "/Config.bak")) {
                $session = session_id();
                if (empty($session)) {session_start();}
                
                $_SESSION["TIMESTAMP"] = $SESSIONID;
                $this->userfiles->reset();
                header('location: '.site_url('continues'));
            } else {
                header('location: '.base_url());
            }
        } else {
            header('location: '.base_url());
        }
    }
}
/* End of file main.php */
/* Location: ./application/controllers/main.php */
