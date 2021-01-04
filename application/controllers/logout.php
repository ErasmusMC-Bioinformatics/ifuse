<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *         http://example.com/index.php/login
     *    - or -  
     *         http://example.com/index.php/login/index
     */
    public function index() {
        $match = $this->session_model->logout();
        // redirect!
        redirect('login');
    }

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
