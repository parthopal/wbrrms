<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Proposal_Model extends CI_Model {
    function get_proposal_list($id = 0) {
        $this->db->select('id,district_id,block_id,action_taken,gp_id,unit,approximate_cost,road_type,work_type,name,unit,action_taken,,approximate_cost,road_name, agency,length,letter,contactno,information,date,image');
        $this->db->where(array(
            'isactive' => 1
        ));
        if($id > 0) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get(PROPOSAL);
        return $id > 0 ? $query->row() : $query->result();
    }
    function get_proposal_info($district_id = 0, $block_id = 0) {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $sql = 'select s.id, d.name as district, b.name as block, g.name as gp, s.road_type,s.work_type,s.road_name,s.name, s.agency, s.length, s.letter,s.unit,s.action_taken,s.approximate_cost,s.contactno,s.information,date_format(s.date, "%d/%m/%Y") as date,s.image '
                . 'from proposal s '
                . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join division g on s.gp_id=g.id '
                . 'where s.isactive=1  ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
       // var_dump($this->db->last_query());exit;
        return $query->result();
    }

    function save($data) {
        $this->db->trans_start();
        $input = array(
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'gp_id' => $data['gp_id'],
            'name' => $data['name'],
            'road_name' => $data['road_name'],
            'road_type' => $data['road_type'],
            'work_type' => $data['work_type'],
            'contactno' => $data['contactno'] != '' ? $data['contactno'] : NULL,
            'letter' => $data['letter'],
            'unit' => $data['unit'],
            'approximate_cost' => $data['approximate_cost'] != '' ? $data['ps_cost'] : NULL,
            'date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['date']))),
            'length' => $data['length'] != '' ? $data['length'] : NULL,
            'agency' => $data['agency'],
            'information' => $data['information'],
            'action_taken' => $data['action_taken']
        );
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update(PROPOSAL, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(PROPOSAL, $input);
            $id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return $id;
        }
    }
    function remove($id) {
        $input['isactive'] = -1;
        $this->db->where('id', $id);
        $this->db->update('proposal', $input);
        return TRUE;
    }



   
}
