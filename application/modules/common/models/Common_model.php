<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_session() {
        $user = $this->session->userdata('user');
        return json_decode($this->encryption->decrypt($user), true);
    }

    function get_menu_list($level_id, $parent_id) {
        $session = $this->get_session();
        $this->db->select('m.id, m.name, m.class, m.link, m.has_child');
        $this->db->where(array(
            'm.level_id' => $level_id,
            'm.parent_id' => $parent_id,
            'm.isactive' => 1,
            'm.sequence >' => 0
        ));
        if ($session['role_id'] > 2) {
            $this->db->join(MENU_ROLE . ' mr', 'mr.menu_id=m.id AND mr.level_id=m.level_id AND mr.isactive=1');
            $this->db->join(USER . ' u', 'u.role_id=mr.role_id AND u.id=' . $session['user_id']);
        }
        $this->db->order_by('m.sequence');
        $query = $this->db->get(MENU . ' m');
        return $query->result();
    }

    function get_role_list() {
        $session = $this->get_session();
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1,
            'id > ' => 2
        ));
        if ($session['role_id'] > 2) {
            $this->db->where('parent_id', $session['role_id']);
        }
        $this->db->order_by('sequence');
        $query = $this->db->get(ROLE);
        return $query->result();
    }

    function get_district_list() {
        $session = $this->get_session();
        $this->db->distinct();
        $this->db->select('id, name');
        $this->db->where(array(
            'level_id' => 2,
            'isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('id', explode(',', $session['district_id']));
        }
        $this->db->order_by('name');
        $query = $this->db->get(DIVISION);
        return $query->result();
    }

    function get_block_list($district_id) {
        $session = $this->get_session();
        $this->db->distinct();
        $this->db->select('id, name');
        $this->db->where(array(
            'level_id' => 3,
            'parent_id' => $district_id,
            'isactive' => 1
        ));
        if ($session['block_id'] > 0) {
            $this->db->where_in('id', explode(',', $session['block_id']));
        }
        $this->db->order_by('name');
        $query = $this->db->get(DIVISION);
        return $query->result();
    }

    function get_gp_list($block_id) {
        $this->db->distinct();
        $this->db->select('id, name');
        $this->db->where(array(
            'level_id' => 4,
            'parent_id' => $block_id,
            'isactive' => 1
        ));
        $this->db->order_by('name');
        $query = $this->db->get(DIVISION);
        return $query->result();
    }

    function get_project_list($district_id, $block_id) {
        $this->db->select('id, name');
        $this->db->where(array(
            'district_id' => $district_id,
            'isactive' => 1,
        ));
        if ($block_id > 0) {
            $this->db->where('FIND_IN_SET(' . $block_id . ', block_id) > 0');
        }
        $query = $this->db->get(PROJECT_HD);
        return $query->result();
    }

    function get_table_id($table, $where = array()) {
        $this->db->select('id');
        $this->db->where($where);
        $query = $this->db->get($table);
        return $query->num_rows() > 0 ? $query->row()->id : 0;
    }

    function get_table_data($table, $where = array()) {
        $this->db->where('isactive', 1);
        $this->db->where($where);
        $query = $this->db->get($table);
        return $query->result();
    }

//    function save($table, $data, $where = '') {
//        foreach ($data as $k => $v) {
//            array_walk_recursive($v, 'Common_Model::filter');
//            if ($where != '') {
//                $this->db->where($where);
//                $query = $this->db->update($table, $v);
//            } else {
//                $query = $this->db->insert($table, $v);
//            }
//        }
//        return $this->db->insert_id();
//    }
//
//    function get_table_id($table, $name) {
//        $this->db->select('id');
//        $this->db->where('LOWER(REPLACE(name, " ", ""))="' . strtolower(str_replace(' ', '', $name)) . '"');
//        $query = $this->db->get($table);
//        $id = 0;
//        if ($query->num_rows() > 0) {
//            $id = $query->row()->id;
//        } else {
//            $input = array(
//                'name' => trim($name)
//            );
//            $this->db->insert($table, $input);
//            $id = $this->db->insert_id();
//        }
////        return $query->num_rows() == 1 ? $query->row()->id : '';
//        return $id;
//    }
//
//    function get_table_id_by_code($table, $code) {
//        $this->db->select('id');
//        $this->db->where('LOWER(REPLACE(code, " ", ""))="' . strtolower(str_replace(' ', '', $code)) . '"');
//        $query = $this->db->get($table);
//        return $query->num_rows() == 1 ? $query->row()->id : '';
//    }
//
//    ############################################################################
////    function get_menu_list($parent_id, $level_id) {
////        $key = $this->session->userdata('user');
////        $k = json_decode($this->encryption->decrypt($key), true);
////        $division_level_id = $k['level_id'];
////        $user_id = $k['user_id'];
////        $role_id = $k['role_id'];
////        $this->db->select('m.id, m.name, m.link, m.class, m.icon, m.level_id, m.parent_id, m.has_child, m.isactive');
////        $this->db->where(array(
////            'm.isactive' => '1',
////            // 'm.parent_id' => $parent_id,
////            'm.level_id' => $level_id
////        ));
////        if($parent_id!=''){
////            $this->db->where('m.parent_id', $parent_id);
////        }
////        if ($role_id > 1) {
////            $this->db->where('um.division_level_id', $division_level_id);
////            $this->db->where('um.role_id', $role_id);
////            $this->db->join(USER_MENU . ' um', 'um.menu_id=m.id AND um.isactive=1');
////        }
////        $this->db->order_by('m.sequence');
////        $query = $this->db->get(MENU . ' m');
////        return $query->result();
////    }
//
//    function get_menu_list($level_id, $parent_id) {
//        $key = $this->session->userdata('user');
//        $k = json_decode($this->encryption->decrypt($key), true);
//        $this->db->select('m.id, m.name, m.class, m.link, m.has_child');
//        $this->db->where(array(
//            'm.level_id' => $level_id,
//            'm.parent_id' => $parent_id,
//            'm.isactive' => 1
//        ));
//        if ($k['role_id'] > 2) {
//            $this->db->join(MENU_ROLE . ' mr', 'mr.menu_id=m.id AND mr.level_id=m.level_id AND mr.isactive=1');
//            $this->db->join(USER . ' u', 'u.role_id=mr.role_id AND u.id=' . $k['id']);
//        }
//        $this->db->order_by('m.sequence');
//        $query = $this->db->get(MENU . ' m');
//        return $query->result();
//    }
//
//    function get_division_list($level_id, $parent_id) {
//        $this->db->distinct();
//        $this->db->select('id, name');
//        $this->db->where(array(
//            'parent_id' => $parent_id,
//            'level_id' => $level_id,
//            'isactive' => 1
//        ));
//        if ($level_id == 3) {
//            $this->db->where('municipality', 0);
//        }
//        $this->db->order_by('name');
//        $query = $this->db->get(DIVISION);
//        return $query->result();
//    }
//
//    function get_road_list($district_id, $block_id, $panchayat_id) {
//        $this->db->select('id, road_name as name');
//        $this->db->where(array(
//            'district_id' => $district_id,
//            'block_id' => $block_id,
//            'isactive' => 1,
//            'isapproved' => 2,
//        ));
//        if ($panchayat_id != '') {
//            $this->db->where('panchayat_id', $panchayat_id);
//        }
//        $query = $this->db->get(ROAD);
//        return $query->result();
//    }
//
//    function get_designation_list() {
//        $this->db->select('id, name');
//        $this->db->where(array(
//            'isactive' => 1
//        ));
//        $query = $this->db->get(DESIGNATION);
//        return $query->result();
//    }
    ############################################################################
}

?>
