<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_model extends CI_Model {
    // Declaration of a variables   
    private $_name;
    private $_userName;
    private $_email;
    private $_password; 
    private $_status;
    private $_verificationCode;  

    public function setName($name)
    {
        $this->_name = $name;
    }
    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }
    public function setPassword($password) 
    {
        $this->_password = $password;
    }
    public function setEmail($email)
    {
        $this->_email = $email;
    }
    public function setStatus($status)
    {
        $this->_status = $status;
    }
    public function setVerificationCode($verificationCode) {
        $this->_verificationCode = $verificationCode;
    }
 

    public function createUser()
    {
        $data = array(
          'name' => $this->_name,
          'email' => $this->_email,
          'user_name' => $this->_userName,
          'password' => $this->_password,
          'status' => $this->_status,
          'verification_code' => $this->_verificationCode,
        );
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    // login method and password verify
    function login() {
        $this->db->select('id as user_id, user_name, email, password');
        $this->db->from('users');
        $this->db->where('email', $this->_userName);
        $this->db->where('verification_code', 1);
        $this->db->where('status', 1);
        //{OR}
        $this->db->or_where('user_name', $this->_userName);
        $this->db->where('verification_code', 1);
        $this->db->where('status', 1);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $result = $query->result();
            foreach ($result as $row) {
                if ($this->verifyHash($this->_password, $row->password) == TRUE) {
                    return $result;
                } else {
                    return FALSE;
                }
            }
        } else {
            return FALSE;
        }
    }
    
    // password verify
    public function verifyHash($password, $vpassword) {
        if (password_verify($password, $vpassword)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
?>
