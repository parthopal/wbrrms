<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Log_Model extends CI_Model
{

    function get_logs_call_list($session, $status)
    {
        $this->db->select('c.id, t.name as type, c.created as date, c.ref_no, u.name, c.contact_person, c.contact_no, c.contact_email, c.remarks, c.document, c.status, c.reason, d.name as district, b.name as block');
        $this->db->where(array(
            'c.isactive' => 1,
            'c.status' => $status
        ));
        if ($session['role_id'] > 3) {
            $this->db->where('c.user_id', $session['user_id']);
        }
        $this->db->join(LOGS_TYPE . ' t', 'c.type_id=t.id');
        $this->db->join(USER . ' u', 'c.user_id=u.id');
        $this->db->join(DIVISION . ' d', 'u.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'u.block_id=b.id', 'left');
        $query = $this->db->get(LOGS_CALL . ' c');

        return $query->result();
    }


    function get_logs_call_info($id)
    {
        $this->db->select('id, type_id, ref_no, scheme_ref_no, contact_person, contact_no, contact_email, remarks, document');
        $this->db->where('id', $id);
        $query = $this->db->get(LOGS_CALL);
        return $query->row();
    }

    function get_logs_type_list()
    {
        $this->db->select('id, name');
        $this->db->where('isactive', 1);
        $query = $this->db->get(LOGS_TYPE);
        return $query->result();
    }

    function save($user_id, $data)
    {
        $this->db->trans_start();
        $input = array(
            'type_id' => $data['type_id'],
            'scheme_ref_no' => $data['scheme_ref_no'],
            'user_id' => $user_id,
            'contact_person' => $data['contact_person'],
            'contact_no' => $data['contact_no'],
            'contact_email' => $data['contact_email'],
            'remarks' => $data['remarks'],
            'isactive' => 1
        );
        if ($data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update(LOGS_CALL, $input);
            $id = $data['id'];
        } else {
            $input['created'] = date('Y-m-d');
            $input['ref_no'] = strtotime(date('YmdHi')) . rand(0, 999);
            $this->db->insert(LOGS_CALL, $input);
            $id = $this->db->insert_id();
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

    function remove($id)
    {
        $input['isactive'] = -1;
        $this->db->where('id', $id);
        $this->db->update(LOGS_CALL, $input);
        return TRUE;
    }

    
    // call log view 

    function get_logs_calls_info($ref_no, $status)
    {


        $this->db->select('c.id, t.name as type, c.scheme_ref_no, c.ref_no, u.name, c.contact_person, c.contact_no, c.contact_email, c.remarks, c.document, c.status, c.reason, d.name as district,  b.name as block');
        $this->db->where(array(
            'c.isactive' => 1,
            'c.ref_no' => $ref_no,
            'c.status' => $status,
        ));
        $this->db->join(LOGS_TYPE . ' t', 'c.type_id=t.id');
        $this->db->join(USER . ' u', 'c.user_id=u.id');
        $this->db->join(DIVISION . ' d', 'u.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'u.block_id=b.id', 'left');

        $query = $this->db->get(LOGS_CALL . ' c');


        return $query->row();
    }


    function resolve($id,$remarks) {
        $input = array(
            'reason' => $remarks,
            'status' => 1
        );
        
        $this->db->where('id', $id);
        $this->db->update(LOGS_CALL, $input);
        return TRUE;
    }

    function reject($id, $remarks) {
        $input = array(
            'reason' => $remarks,
            'status' => -1
        );
        $this->db->where('id ', $id);
        $this->db->update(LOGS_CALL, $input);
        return true;
    }


}

