<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notice_Model extends CI_Model {
    function get_notice_list($id = 0) {
        $this->db->select('id, name, document,memo_no,date');
        $this->db->where(array(
            'isactive' => 1
        ));
        if($id > 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get('notice');
        return $id > 0 ? $query->row() : $query->result();
    }
    function save($data) {
        $this->db->trans_start();
        $id = $data['id'];
        if (sizeof($data) > 0) {
            $input = array(                
                'name' => $data['name'],
                'memo_no' => $data['memo_no'],
                'date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['date']))),
                
            );

            if ($data['id'] > 0) {
                $this->db->where('id', $data['id']);
                $this->db->update('notice', $input);
                $id = $data['id'];
            } else {
                $this->db->insert('notice', $input);
                $id = $this->db->insert_id();
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return $id;
        } else {
            $this->db->trans_commit();
            return $id;
        }
    }
  
    function remove($id) {
        $input['isactive'] = -1;
        $this->db->where('id', $id);
        $this->db->update('notice', $input);
        return TRUE;
    }

}