<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Download extends CI_Controller {
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
        self::download();
    }
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/blog
     *    - or -  
     *         http://example.com/index.php/blog/index
     */
    public function download() {
        $this->template->set('data',$this->lang->language);
        $this->template->view('default/template','download',$this->lang->language);
    }
}

/* End of file main.php */
/* Location: ./application/controllers/main.php */