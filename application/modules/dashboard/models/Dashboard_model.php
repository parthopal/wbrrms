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

    function filter_with_agency() {
        $session = $this->common->get_session();
        $filter = '';
        switch ($session['role_id']) {
            case 13:
                $filter = ' and agency="ZP"';
                break;
            case 14:
                //$filter = ' and agency="BLOCK"';
                break;
            case 15:
                $filter = ' and agency="SRDA"';
                break;
            case 16:
                $filter = ' and agency="MBL"';
                break;
            case 17:
                $filter = ' and agency="AGRO"';
                break;
            default:
                break;
        }
        return $filter;
    }

    function get_rpt_general($scheme_id, $district_id, $block_id = 0) {
        $session = $this->common->get_session();
        $table = '';
        switch ($scheme_id) {
            case 1:
                $table = "srrp";
                break;
            case 2:
                $table = "ssm";
                break;
            case 3:
                $table = "sf";
                break;
            default :
                break;
        }
        $where = ' s.district_id=' . $district_id;
        $where .= $block_id > 0 ? ' and s.block_id =' . $block_id : ($session['block_id'] > 0 ? ( ' and s.block_id in(s.block_id= ' . explode(',', $session['block_id'])) : '');
        $where .= $this->filter_with_agency();
        /*$sql = 'select d.name as district, b.name as block, s.name, s.agency as agency, count(s.scheme_no) as approved_scheme, sum(s.approved_length) as approved_length, sum(s.cost) as approved_amount,
        count(s.tender_number) as tender_invited, if(s.tender_status = 2,count(s.tender_status),0) as tender_matured, if(s.wo_status = 2,count(s.tender_status),0) as wo_issued, round(sum(if(s.wo_status=2,s.length,0)),2) as wo_length, sum(if(s.wo_status=2,s.cost,0)) as wo_amount,
        if(s.pp_status=1,COUNT(s.pp_status),0) as progress_25, if(s.pp_status=2,COUNT(s.pp_status),0) as progress_50, if(s.pp_status=3,COUNT(s.pp_status),0) as progress_75,if(s.pp_status=4,COUNT(s.pp_status),0) as progress_99,
        if(pp_status=1,COUNT(pp_status),0) + (if(pp_status=2,COUNT(pp_status),0)) + (if(pp_status=3,COUNT(pp_status),0)) + (if(pp_status=1,COUNT(pp_status),0)) as ongoing,
        round((sum(if(s.pp_status=1,s.approved_length,0))) + (sum(if(s.pp_status=2,s.approved_length,0))) + (sum(if(s.pp_status=3,s.approved_length,0))) + (sum(if(s.pp_status=2,s.approved_length,0))),2) as ongoing_length,
        (sum(if(s.pp_status=1,s.cost,0))) + (sum(if(s.pp_status=2,cost,0))) + (sum(if(s.pp_status=3,cost,0))) + (sum(if(s.pp_status=4,cost,0))) as ongoing_amount,
        if(s.pp_status=5,COUNT(s.pp_status),0) as completed, round((sum(if(s.pp_status=5,s.approved_length,0))),2) as completed_length, sum(if(s.pp_status=5,cost,0)) as completed_amount
        from ' . $table . ' as s join division d on s.district_id=d.id join division b on s.block_id=b.id where s.isactive = 1 and ' . $where . ' group by name order by district, s.agency ';*/
		
		$sql = 'select d.name as district, b.name as block, s.name, s.agency as agency, count(s.scheme_no) as approved_scheme, sum(s.approved_length) as approved_length, sum(s.cost) as approved_amount,
        count(s.tender_number) as tender_invited, if(s.tender_status = 2,count(s.tender_status),0) as tender_matured, if(s.wo_status = 2,count(s.tender_status),0) as wo_issued, round(sum(if(s.wo_status=2,s.length,0)),2) as wo_length, sum(if(s.wo_status=2,wo.awarded_cost,0)) as wo_amount,
        if(s.pp_status=1,COUNT(s.pp_status),0) as progress_25, if(s.pp_status=2,COUNT(s.pp_status),0) as progress_50, if(s.pp_status=3,COUNT(s.pp_status),0) as progress_75,if(s.pp_status=4,COUNT(s.pp_status),0) as progress_99,
        if(pp_status=1,COUNT(pp_status),0) + (if(pp_status=2,COUNT(pp_status),0)) + (if(pp_status=3,COUNT(pp_status),0)) + (if(pp_status=1,COUNT(pp_status),0)) as ongoing,
        round((sum(if(s.pp_status=1,s.approved_length,0))) + (sum(if(s.pp_status=2,s.approved_length,0))) + (sum(if(s.pp_status=3,s.approved_length,0))) + (sum(if(s.pp_status=2,s.approved_length,0))),2) as ongoing_length,
        (sum(if(s.pp_status=1,s.cost,0))) + (sum(if(s.pp_status=2,cost,0))) + (sum(if(s.pp_status=3,cost,0))) + (sum(if(s.pp_status=4,cost,0))) as ongoing_amount,
        if(s.pp_status=5,COUNT(s.pp_status),0) as completed, round((sum(if(s.pp_status=5,s.approved_length,0))),2) as completed_length, sum(if(s.pp_status=5,cost,0)) as completed_amount
        from ' . $table . ' as s join division d on s.district_id=d.id join division b on s.block_id=b.id join ' . $table . '_wo as wo on wo.' . $table . '_id=s.id where s.isactive = 1 and ' . $where . ' group by name order by district, s.agency ';
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }
}
