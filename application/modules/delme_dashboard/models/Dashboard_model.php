<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_Model extends CI_Model {

    function project_funding() {
//        $session = $this->common->get_session();
//        $this->db->distinct();
//        $this->db->select('c.subcategory as category, count(hd.id) as cnt');
//        $this->db->where('c.isactive', 1);
//        if ($session['district_id'] > 0) {
//            $this->db->where('district_id', $session['district_id']);
//        }
//        $this->db->join(PROJECT_HD . ' hd', 'hd.category_id=c.id and hd.isactive=1');
//        $this->db->group_by('c.subcategory');
//        $query = $this->db->get(CATEGORY . ' c');
//        return $query->result();
    }

    function project_status($district_id = 0) {
//        $session = $this->common->get_session();
//        $this->db->distinct();
//        $this->db->select('iscompleted, COUNT(id) as cnt');
//        $this->db->where(array(
//            'isactive' => 1
//        ));
//        if ($session['district_id'] > 0) {
//            $this->db->where('district_id', $session['district_id']);
//        } else if ($district_id > 0) {
//            $this->db->where('district_id', $district_id);
//        }
//        $this->db->group_by('iscompleted');
//        $this->db->order_by('iscompleted');
//        $query = $this->db->get(PROJECT_HD);
//        return $query->result();
    }

    function districtwise_project_status() {
//        $this->db->distinct();
//        $this->db->select('hd.district_id, d.name');
//        $this->db->where(array(
//            'hd.isactive' => 1
//        ));
//        $this->db->join(DIVISION . ' d', 'hd.district_id=d.id');
//        $this->db->order_by('d.name');
//        $query = $this->db->get(PROJECT_HD . ' hd');
//        $result = $query->result();
//        $arr = array();
//        foreach ($result as $row) {
//            $status = $this->project_status($row->district_id);
//            $status_piechart = array();
//            foreach ($status as $s) {
//                $status_piechart[] = $s->cnt;
//            }
//            $input = array(
//                'id' => $row->district_id,
//                'district' => $row->name,
//                'data' => $status_piechart
//            );
//            $arr[] = $input;
//        }
//        return $arr;
    }

    // Pathashree Dashboard

    function get_dashboard_count() {
        $session = $this->common->get_session();
        $this->db->select('COUNT(id) as approved_scheme, SUM(length) as length, SUM(cost) as sanctioned_amount, COUNT(tender_number) as tender_invited');
        $this->db->where(
                array(
                    's.survey_status' => 6,
                    's.isactive' => 1
                )
        );
        if ($session['role_id'] == 12) {
            $this->db->where('district_id = ' . $session['district_id']);
        }
        $query = $this->db->get(SRRP . ' s');

        return $query->row();
    }

    function get_tender_and_wo_count() {
        $session = $this->common->get_session();
        // $this->db->select('COUNT(id) as tender_matured, SUM(length) as length, SUM(cost) as tender_amount');
        // $this->db->where(
        //     array(
        //         's.tender_status' => 2,
        //         's.isactive' => 1
        //     )
        // );
        // if ($session['role_id'] == 12) {
        //     $this->db->where('district_id = ' . $session['district_id']);
        // }
        // $query = $this->db->get(SRRP . ' s');
        // return $query->row();
        $by_district = $session['role_id'] == 12 ? 'AND district_id = ' . $session['district_id'] : '';
        $sql = "SELECT COUNT(id) as tender_matured, SUM(length) as tender_matured_length, SUM(cost) as tender_amount FROM srrp WHERE isactive = 1 AND tender_status = 2 " . $by_district;
        $query = $this->db->query($sql);
        $tender_matured_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_order, SUM(s.length) as work_order_length, SUM(wo.awarded_cost) as sanctioned_amount FROM srrp s JOIN srrp_wo wo ON wo.srrp_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 " . $by_district;
        $query = $this->db->query($sql);
        $work_order_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_progress, SUM(s.length) as work_progress_length, SUM(wo.awarded_cost) as sanctioned_amount FROM srrp s JOIN srrp_wo wo ON wo.srrp_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 AND s.pp_status = 5 " . $by_district;
        $query = $this->db->query($sql);
        $work_order_progress_count = $query->row();

        return array(
            'tender_matured_count' => $tender_matured_count,
            'work_order_count' => $work_order_count,
            'work_order_progress_count' => $work_order_progress_count
        );
    }

    // get RIDF ccount

    function get_ridf_count() {
//        $session = $this->common->get_session();
//        $this->db->select('category_id, COUNT(id) as scheme, SUM(length) as length, SUM(sanctioned_cost) as amount');
//        $this->db->where(
//                array(
//                    // 's.survey_status' => 6,
//                    's.isactive' => 1
//                )
//        );
//        if ($session['role_id'] == 12) {
//            $this->db->where('district_id = ' . $session['district_id']);
//        }
//        $this->db->group_by('category_id');
//        $query = $this->db->get(PROJECT_HD . ' s');
//
//        return $query->result();
    }

}
