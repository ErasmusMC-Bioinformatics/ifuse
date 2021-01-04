<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analyse extends CI_Controller {
    public $fileName = null;
    
    public function __construct() {
        parent::__construct();
        
        $this->fileName = $this->userfiles->getFile();
        $this->load->library('ifuseloader',array("fileName" => $this->fileName));
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
        //$this->ifuseloader->organize($this->ifuseloader->getData(false)); // Done infile
        $this->template->set('data',$this->lang->language);
        $this->template->view('default/template','analyse',$this->lang->language);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */
