<?php
namespace User\Models;

class User {
    public $_id;
    public $_username;
    public $_email;
    public $_salt;
    public $_password;

    public function __construct(){

    }

    public function exchangeArray($data) { 
        $this->_id = (!empty($data['id'])) ? $data['id'] : null; 
        $this->_username = (!empty($data['username'])) ? $data['username'] : null; 
        $this->_email = (!empty($data['email'])) ? $data['email'] : null; 
        $this->_salt = (!empty($data['salt'])) ? $data['salt'] : null; 
        $this->_password = (!empty($data['password'])) ? $data['password'] : null; 
    }

    public function toValues(){
        return [
            'id' => $this->_id,
            'username' => $this->_username,
            'email' => $this->_email,
            'salt' => $this->_salt,
            'password' => $this->_password
        ];
    }
}
?>