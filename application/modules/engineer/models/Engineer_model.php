<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Engineer_Model extends CI_Model
{

    function get_engineer_list()
    {
        $this->db->select('e.*, d.name as home_district');
        $this->db->where('e.isactive', 1);
        $this->db->join(DIVISION . ' d', 'e.district_id=d.id', 'left');
        $query = $this->db->get(ENGINEER . ' e');
        return $query->result();
    }

    function get_engineer_info($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get(ENGINEER);
        return $query->row();
    }

    function get_service_book($id)
    {
        $this->db->select('s.id, e.id as engineer_id, e.name, s.sb_date, s.sb_doc');
        $this->db->where('e.id', $id);
        $this->db->join(SERVICE_BOOK . ' s', 's.engineer_id=e.id AND s.isactive = 1', 'left');
        $this->db->order_by('s.sb_date, s.id');
        $query = $this->db->get(ENGINEER . ' e');
        return $query->result();
    }


    function get_leave_records($id)
    {
        $this->db->select('l.id, e.id as engineer_id, e.name, l.lr_date, l.lr_doc');
        $this->db->where('e.id', $id);
        $this->db->join(LEAVE_RECORDS . ' l', 'l.engineer_id=e.id AND l.isactive = 1', 'left');
        $this->db->order_by('l.lr_date, l.id');
        $query = $this->db->get(ENGINEER . ' e');
        return $query->result();
    }

    function save($data)
    {
        $this->db->trans_start();
        $id = isset($data['id']) ? $data['id'] : 0;
        $input = array(
            'name' => $data['name'],
            'designation' => $data['designation'],
            'district_id' => $data['district_id'],
            'caste' => $data['caste'],
            'dob' => date('Y-m-d', strtotime(str_replace('/', '-', $data['dob']))),
            'doj_sae' => $data['doj_sae'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doj_sae']))) : null,
            'doc_sae' => $data['doc_sae'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doc_sae']))) : null,
            'doj_ae' => $data['doj_ae'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doj_ae']))) : null,
            'doc_ae' => $data['doc_ae'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doc_ae']))) : null,
            'doj_ee' => $data['doj_ee'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doj_ee']))) : null,
            'doj_se' => $data['doj_se'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['doj_se']))) : null,
            'doj_se_tag' => $data['doj_se_tag'],
            'se_promotional_order' => $data['se_promotional_order'],
            'dor' => $data['dor'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['dor']))) : null,
            'posting' => $data['posting'],
            'remarks' => $data['remarks'],
            'updated_on' => date('Y-m-d')
        );
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(ENGINEER, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(ENGINEER, $input);
            $id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }

    
}
