<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Template {
        var $template_data = array();
        
        function set($name, $value)
        {
            $this->template_data[$name] = $value;
        }
    
        function view($template = '', $view = '')
        {
            $this->CI =& get_instance();
	    $allowed = array('login','register');
	    if (($this->CI->session_model->data['id'] == NULL) && (!in_array($view, $allowed))) {
                redirect("login");
            } else {
                $this->set('contents', $view);
                return $this->CI->parser->parse($template, $this->template_data);
	    }
        }
}

/* End of file template.php */
/* Location: ./system/application/libraries/template.php */
