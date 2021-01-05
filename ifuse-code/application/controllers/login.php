<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
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
	if ($this->session_model->data['id'] != NULL) {
		redirect("main");
	} elseif (
		isset($_POST		) && 
		isset($_POST['username']) && 
		isset($_POST['password']) && 
		isset($_POST['remember'])
	) {
		$match = $this->session_model->login($_POST['username'], $_POST['password'], $_POST['remember']);
		if ($match == TRUE) {
			// redirect!
			redirect('main');
		}
	}
        $this->template->set('data',$this->lang->language);
        $this->template->view('default/template','login',$this->lang->language);
    }
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
