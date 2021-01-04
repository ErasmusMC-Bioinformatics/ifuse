<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Delete extends CI_Controller {
    public $fileName = null;
    
    public function __construct() {
        parent::__construct();
        $this->fileName = $this->userfiles->getFile();
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
        self::delete();
    }
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/blog
     *    - or -  
     *         http://example.com/index.php/blog/index
     */
    public function delete() {
        $this->userfiles->removeFile();
        
        $this->template->set('data',$this->lang->language);
        $this->template->view('default/template','delete',$this->lang->language);
    }
    
    public function session() {
        $this->load->helper('file');
        delete_files(pathinfo($this->fileName,PATHINFO_DIRNAME), TRUE);
        rmdir(pathinfo($this->fileName,PATHINFO_DIRNAME));
        session_unset();
        
        $this->template->set('data',$this->lang->language);
        $this->template->view('default/template','delete',$this->lang->language);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */