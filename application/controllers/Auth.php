<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //load model
        $this->load->model('Auth_model', 'auth');
        $this->load->library('form_validation');
    }
    
    // user profile
    public function index() {
        if ($this->session->userdata('ci_session_key_generate') == FALSE) {
            redirect('auth/login'); // the user is not logged in, redirect them!
        } else {
            $data = array();
            $data['metaDescription'] = 'User Profile';
            $data['metaKeywords'] = 'User Profile';
            $data['title'] = "User Profile";
            print_r($_SESSION);
            $sessionArray = $this->session->userdata('ci_seesion_key');
            $this->auth->setUserName($sessionArray['user_name']);
            $this->load->view('auth/index', $data);
        }
    }
 

    // index method
    public function login() {        
        $data = array();
        $data['metaDescription'] = 'Login';
        $data['metaKeywords'] = 'Login';
        $data['title'] = "Login";
        
        $this->load->view('auth/login', $data);
    }
    
    // index method
    public function register() {        
        $data = array();
        $data['metaDescription'] = 'Register';
        $data['metaKeywords'] = 'Register';
        $data['title'] = "Register";

        $this->load->view('auth/register', $data);
    }

    // Action Register
    public function actionRegister()
    {
        $this->load->library('form_validation');
        // field name, error message, validation rules
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('username', 'User Name', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('email', 'Your Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[4]|max_length[32]');
        $this->form_validation->set_rules('confirm_password', 'Password Confirmation', 'trim|required|matches[password]');
     
        if ($this->form_validation->run() == FALSE) {
          $this->register();
        } else {
          // post values
          $name = $this->input->post('name');
          $username = $this->input->post('username');
          $email = $this->input->post('email');
          $password = $this->input->post('password');
          $verificationCode = uniqid();
          // set post values
          $this->auth->setName($name);
          $this->auth->setUserName($username);
          $this->auth->setEmail($email);
          $this->auth->setPassword(MD5($password));
          $this->auth->setStatus(1);
          $this->auth->setVerificationCode($verificationCode);
          // insert values in database
          $this->auth->createUser();
          redirect('auth/index');
        }
    }

    // action login method
    function doLogin() {        
        // Check form  validation
        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'User Name', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            //Field validation failed.  User redirected to login page
            // $this->login();
        } else {
          $sessArray = array();
            //Field validation succeeded.  Validate against database
            $username = $this->input->post('user_name');
            $password = $this->input->post('password');
            
            $this->auth->setUserName($username);
            $this->auth->setPassword($password);
            //query the database
            $result = $this->auth->login();

            if (!empty($result) && count($result) > 0) {
                foreach ($result as $row) {
                    $authArray = array(
                        'user_id' => $row->user_id,
                        'user_name' => $row->user_name,
                        'email' => $row->email
                    );
                    $this->session->set_userdata('ci_session_key_generate', TRUE);
                    $this->session->set_userdata('ci_seesion_key', $authArray);
                    // remember me
                    if(!empty($this->input->post("remember"))) {
	                    setcookie ("loginId", $username, time()+ (10 * 365 * 24 * 60 * 60));  
	                    setcookie ("loginPass",	$password,	time()+ (10 * 365 * 24 * 60 * 60));
                    } else {
	                    setcookie ("loginId",""); 
	                    setcookie ("loginPass","");
                    }                    
                }
                redirect('auth/index');
            } else {
                $this->login();
            }
        }
    }

    //logout method
    public function logout() {
        $this->session->unset_userdata('ci_seesion_key');
        $this->session->unset_userdata('ci_session_key_generate');
        $this->session->sess_destroy();
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
        $this->output->set_header("Pragma: no-cache");
        redirect('auth/login');
    }   

}

