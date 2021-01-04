<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Register extends CI_Controller {
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
        $language = $this->lang->language;
	if ($this->session_model->data['id'] != NULL) {
		redirect("main");
	} elseif (
		isset($_POST		 ) && 
		isset($_POST['username' ]) && 
		isset($_POST['password' ]) && 
		isset($_POST['passwordr']) && 
		isset($_POST['email'    ])
	) {
		$match = $this->session_model->register($_POST['username'], $_POST['password'], $_POST['passwordr'], $_POST['email']);
		if ($match === TRUE) {
			// redirect!
			redirect('login');
		} else {
		    $language = array_merge($language, array('currenterror'=>$match));
		}
	}
        $this->template->set('data',$language);
        $this->template->view('default/template','register',$this->lang->language);
	
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
