<?php

class Session_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function check_session() {
        if (!$this->session->userdata('user')) {
            redirect('login');
        }
    }

    function set_session($user_id) {
        $this->db->select('u.id as user_id, u.name, u.district_id, u.block_id, u.role_id, r.name as role, r.name as designation, l.username');
        $this->db->where(array(
            'u.id' => $user_id,
            'u.isactive' => 1
        ));
        $this->db->join(ROLE . ' r', 'u.role_id=r.id');
        $this->db->join(LOGIN . ' l', 'l.user_id=u.id');
        $query = $this->db->get(USER . ' u');
        $user = $query->row();
        $this->session->set_userdata('user', $this->encryption->encrypt(json_encode($user)));
        return TRUE;
    }

}

?>