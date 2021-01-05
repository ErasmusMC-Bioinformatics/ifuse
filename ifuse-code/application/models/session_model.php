<?php
class Session_model extends CI_Model {
    public $data = array(
        'id'    => NULL,
        'name'  => NULL,
        'email' => NULL
    );
    
    /**
    * Constructor
    */
    public function __construct() {
        parent::__construct();
        // Assign the CodeIgniter object to a variable
        $CI =& get_instance();
        
        // load the database and connect to MySQL
        $CI->load->database();
        // Initialize the user
        $this->initialize();
    }
    
    /**
    * Validates provided $username and $password
    * 
    * @param mixed $username
    * @param mixed $password
    */
    public function login($username, $password, $remember = 'no') {
        $match   = $this->validate($username, $password);
        $id_user = $this->session->userdata('id_user');
        
        if (!empty($id_user)) {
            // You are already logged in.
        } elseif ($match["match"] === TRUE) {
	    $this->session->set_userdata('id_user', $match["id"]);
            
            // Any need to remember that you are logged in?
            if (strtolower($remember) == 'yes') {
                // Generate a Auth key
                $key = substr(random_string("sha1"), 0, 45);
                
                // Get/Set Auth key in cookie
                $this->db->where('id_user', $match["id"]);
                $this->db->update(
                    'users',
                    array("user_authkey" => $key)
                );
                
                // Set Auth key in sessions
                $this->session->set_userdata('autologin', 
                    json_encode(
                        array(
                            "id"   => $match["id"],
                            "auth" => $key
                        )
                    )
                );
            } else {
                // Empty Auth key in database users
                $this->db->where('id_user', $match["id"]);
                $this->db->update(
                    'users', 
                    array("user_authkey" => NULL)
                );
            }
        }
        $this->initialize();
        return $match["match"];
    }
    
    /**
    * Validates provided $username, $password and $email and if possible, creates a new record in the `users` database...
    * 
    * @param mixed $username
    * @param mixed $password
    * @param mixed $retype
    * @param mixed $email
    */
    public function register($username, $password, $retype, $email) {
        $error = '';
        //$match   = $this->validate($username, $password); // check if can login with this data.
        $id_user = $this->session->userdata('id_user');
        
        if (!empty($id_user)) {
            // You are already logged in.
        } else {
	    // is already in the database?
	    $query = $this->db->query('SELECT count(*) AS cnt FROM users WHERE user_name=?;', $username);
            $uresult = ($query->result());
	    $query = $this->db->query('SELECT count(*) AS cnt FROM users WHERE user_email=?;', $email);
            $eresult = ($query->result());

            // everything all right??
            $isemail = preg_match("/^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/", strtoupper($email)) === 1;
            $knowspw = ($password == $retype);
            $isnindb = (($uresult[0]->cnt == 0) && ($eresult[0]->cnt == 0));
	    
	    if (!$isemail) {
                return "E-Mail does not seem right";
            } elseif (empty($email)) {
                return "Empty E-Mail field";
            } elseif (!$knowspw) {
                return "Passwords don't match";
            } elseif (empty($password)) {
                return "Empty Password fields";
            } elseif (!$isnindb) {
                return "Email or Username already in use";
            } elseif (empty($username)) {
                return "Empty Username field"; 
	    } else {
	        // all good, instert
                $this->db->query("INSERT INTO users VALUES (NULL, '".mysql_real_escape_string($username)."', password('".mysql_real_escape_string($password)."'), '".mysql_real_escape_string($email)."', NULL, NULL)");
		return TRUE;
	    }
        }
        return $error;
    }
    
    /**
    * Check if user provided username and password match an entry in the database
    * 
    * @param mixed $username
    * @param mixed $password
    */
    private function validate($username, $password) {       
        // Set the query to get count and get the user id attached to the $username and $password
        $query = $this->db->query(
            "SELECT `id_user` as id, count(*) AS cnt FROM users WHERE user_email=? AND user_password=password(?);",
            array(
                $username,
                $password
            )
        );
        
        // Catch result into $result
        $result = $query->result();
        // Return whether there is one matching record and the corresponding user id
        return array('match' => ($result[0]->cnt == 1), 'id' => $result[0]->id);
    }
    
    /**
    * Resets the session cookie so the user is logged off.
    * Also delete autologin cookie...
    * 
    */
    public function logout() {
        // Empty session cookie
        $this->db->where('id_user', $this->data['id']);
        $this->db->update(
            'users', 
            array("user_authkey" => NULL)
        );
        
        // Empty Auth key
        $this->session->unset_userdata('id_user');
        $this->session->unset_userdata('autologin');
    }
    
    /**
    * initializes all the data needed for the user
    * 
    */
    private function initialize() {
        // Set variables
        $timestamp = $this->session->userdata('timestamp');
        $cur_time  = time();
        
        // Set variables for further processing
        $expired   = (
            ($this->session->userdata('timestamp') < ($cur_time - (getConfig('user_online_time')))) // Time someone is logged in
            &&
            (!empty($timestamp)) // Cause if not you are a new user
        );
        $autologin = json_decode($this->session->userdata('autologin'), TRUE);
        
        // If user has not recently visited a page (and should not autologin), reset user
        if ($expired &&  isset($autologin['id']) &&  isset($autologin['auth'])
                     && !empty($autologin['id']) && !empty($autologin['auth'])
        ) {
            // Validate user autherization
            $query = $this->db->query(
                "SELECT count(*) AS cnt FROM users WHERE id_user=? AND user_authkey=?;",
                array(
                    $autologin['id'],
                    $autologin['auth']
                )
            );
            
            // Catch result into $result
            $result = $query->result();
            
            // Return whether there is one matching record and the corresponding user id
            if ($result[0]->cnt == 1) {
                $this->session->set_userdata('id_user', $autologin['id']);
            } else {
                $this->logout();
            }
        } elseif ($expired) {
            $this->logout();
        }
        
        // Update timestamp...
        $this->session->set_userdata('timestamp', $cur_time);
        
        // ...and get user id...
        $id_user = $this->session->userdata('id_user');
        
        // ...or set default id (guest account).
        $id_user = empty($id_user) ? 1 : $id_user;
        
        // Get user data
        $query = $this->db->query(
            "SELECT
                `users`.`id_user` AS 'id',
                `users`.`user_name` AS 'name',
                `users`.`user_email` AS 'email'
            FROM
                `users`
            WHERE
                `users`.`id_user` = ?;",
            array(
                $id_user
            )
        );
        
        // Catch user data-result into $result
        $result = $query->result();
        
        // Set user data in object
        if ($query->num_rows() == 1) {
            $row = $query->row(); 
            
            $this->data = array(
                'id'    => $row->id,
                'name'  => $row->name,
                'email' => $row->email,
            );
            
            // Set timestamp you last visited a page
            $this->db->where('id_user', $this->data['id']);
            $this->db->update('users', array("user_last_pageview" => $cur_time));
            
        }
    }
}
?>
