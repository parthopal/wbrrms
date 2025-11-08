<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Fund_Model extends CI_Model {

    function get_activity_info($id) {
        $this->db->where('id', $id);
        $query = $this->db->get(ACTIVITY);
        return $query->row();
    }

    function get_fund_list($category, $activity_id) {
        $this->db->where(array(
            'category' => $category,
            'activity_id' => $activity_id,
            'isactive' => 1
        ));
        $query = $this->db->get(FUND);
        return $query->result();
    }

    function get_fund_info($id) {
        $this->db->where(array(
            'id' => $id
        ));
        $query = $this->db->get(FUND);
        return $query->row();
    }

    function get_agency_list($agency_id) {
        $this->db->select('id, name');
        $this->db->where_in('id', explode(',', $agency_id));
        $query = $this->db->get(AGENCY);
        return $query->result();
    }

    function get_against_list($activity_id, $parent_id, $expenditure, $district_id = 0, $block_id = 0, $from_agency_id = 0) {
        $this->db->select('id, order_no, order_date');
        $this->db->where(array(
            'activity_id' => $parent_id,
            'expenditure' => $expenditure,
            'isactive' => 1
        ));
        if ($district_id > 0 && $activity_id > 4) {
            $this->db->where('district_id', $district_id);
        }
        if ($block_id > 0 && $activity_id == 6) {
            $this->db->where('block_id', $block_id);
        }
        if ($from_agency_id > 0 && $activity_id == 7) {
            $this->db->where('from_agency_id', $from_agency_id);
        }
        $query = $this->db->get(FUND);
//        echo $this->db->last_query();
//        exit;
        return $query->result();
    }

    function get_against_ref($against_id) {
        $sql = 'SELECT order_no, order_date, amount FROM scheme_fund WHERE id=' . $against_id . ' AND isactive=1';
        $query = $this->db->query($sql);
        return $query->row();
    }

    function get_pending_amount($against_id) {
        $sql = 'SELECT SUM(amount * IF(id=' . $against_id . ', 1, -1)) as amount FROM scheme_fund WHERE (id=' . $against_id . ' OR against_id=' . $against_id . ') AND isactive=1';
        $query = $this->db->query($sql);
        return $query->row()->amount;
    }

    function save($data) {
        $this->db->trans_start();
        $input = array(
            'accounting_year' => '2023-24',
            'category' => $data['category'],
            'activity_id' => $data['activity_id'],
            'activity_date' => isset($data['activity_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['activity_date']))) : date('Y-m-d'),
            'expenditure' => $data['expenditure'],
            'order_no' => $data['order_no'],
            'order_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['order_date']))),
            'district_id' => array_key_exists('district_id', $data) && $data['district_id'] != '' ? $data['district_id'] : 0,
            'block_id' => array_key_exists('block_id', $data) && $data['block_id'] != '' ? $data['block_id'] : 0,
            'from_agency_id' => $data['from_agency_id'],
            'to_agency_id' => $data['to_agency_id'] != '' ? $data['to_agency_id'] : 0,
            'against_id' => array_key_exists('against_id', $data) && $data['against_id'] != '' ? $data['against_id'] : 0,
            'amount' => $data['amount'],
            'remarks' => $data['remarks']
        );
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(FUND, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(FUND, $input);
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
