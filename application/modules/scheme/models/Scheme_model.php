<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scheme_Model extends CI_Model {

    function get_scheme_summary($category) {
        $sql = 'SELECT sc.subcategory as category, count(s.id) as total, count(tender.id) as tender, count(wo.id) as wo
            FROM scheme_category sc LEFT JOIN scheme s ON s.category_id=sc.id and s.isactive=1
            LEFT JOIN (SELECT id, category_id FROM scheme WHERE isactive=1 AND istender=1) as tender ON tender.category_id=s.category_id AND tender.id=s.id
            LEFT JOIN (SELECT id, category_id FROM scheme WHERE isactive=1 AND iswo=1) as wo ON wo.category_id=s.category_id AND wo.id=s.id
            WHERE sc.category="' . $category . '" AND sc.isactive=1
GROUP BY sc.subcategory ORDER BY sc.id';
        $query = $this->db->query($sql);
        return $query->result();
    }
    function get_rpt_state_summary($category) {
        $this->db->distinct();
        $this->db->select('d.name as district,sg.name as agency, s.scheme_id as project_id, s.name as project_name,c.subcategory as ridf_tranche,sy.name as project_type,s.length as road_length, s.sanctioned_cost as approved_amount,s.admin_date as admin_approval_date,st.call_no as tender_maturing,sw.wo_date as workorder_issued_date');
        $this->db->where(array(
            'c.category' => $category,
            's.isactive' => 1
        ));
        $this->db->join(SCHEME_CATEGORY . ' c', 's.category_id=c.id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(SCHEME_TYPE . ' sy', 's.type_id=sy.id and sy.isactive=1', 'left');
        $this->db->join(SCHEME_TENDER . ' st', 'st.scheme_id=s.id and st.isactive=1', 'left');
        $this->db->join(SCHEME_WO . ' sw', 'sw.scheme_id=s.id and sw.isactive=1', 'left');
        $this->db->join(SCHEME_AGENCY . ' sg', 's.agency_id=sg.id and sg.isactive=1', 'left');
        $query = $this->db->get(SCHEME . ' s');
        return $query->result();
    }
    
    function get_agency_progress() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        // $where .= $this->filter_with_agency();
        $sql = 'select ca.subcategory,ty.name,s.sanctioned_cost,s.admin_date,s.call_no,wo.wo_no
        from scheme s join division d on s.district_id=d.id join scheme_wo wo on wo.scheme_id=s.id 
        join scheme_category ca on ca.id =s.id
        join scheme_type ty on ty.id =s.id
        where s.isactive=1  order by d.name ';
        $query = $this->db->query($sql);
        return $query->result();
    }
    function get_scheme_list($district_id, $category_id, $type_id) {
        $this->db->select('s.id, d.name as district, s.scheme_id, s.name, c.subcategory as category, t.name as type, '
                . 'a.name as agency, s.length, s.unit, s.sanctioned_cost, s.admin_no, s.admin_date, s.istender, s.iswo');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.category_id' => $category_id,
            's.type_id' => $type_id,
            's.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(CATEGORY . ' c', 's.category_id=c.id');
        $this->db->join(TYPE . ' t', 's.type_id=t.id');
        $this->db->join(AGENCY . ' a', 's.agency_id=a.id');
        $this->db->order_by('d.name, a.name, s.name');
        $query = $this->db->get(SCHEME . ' s');
        return $query->result();
    }

    function get_scheme_info($id) {
        $this->db->where('id', $id);
        $query = $this->db->get(SCHEME);
        return $query->row();
    }

    function get_category_list($category) {
        $this->db->select('id, subcategory as name');
        $this->db->where(array(
            'category' => $category,
            'isactive' => 1
        ));
        $query = $this->db->get(CATEGORY);
        return $query->result();
    }

    function get_project_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(TYPE);
        return $query->result();
    }
    function get_syoptic_agency_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(AGENCY);
       // echo $this->db->last_query(); exit;
        return $query->result();
    }
    // function get_tranche_type_list() {
    //     $this->db->select('id, subcategory');
    //     $this->db->where(array(
    //         'isactive' => 1
    //     ));
    //     $query = $this->db->get(CATEGORY);
    //     return $query->result();
    // }
    function get_agency_list($category) {
        $this->db->select('id, name');
        $this->db->where(array(
            $category => 1,
            'isactive' => 1,
            'id >' => 3
        ));
        $this->db->order_by('name');
        $query = $this->db->get(AGENCY);
        return $query->result();
    }

    function get_road_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(ROAD_TYPE);
        return $query->result();
    }

    function get_work_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(WORK_TYPE);
        return $query->result();
    }

    function get_constitution_list($type) {
        $this->db->select('id, name');
        $this->db->where(array(
            'type' => $type,
            'isactive' => 1
        ));
        $this->db->order_by('name');
        $query = $this->db->get(CONSTITUTION);
        return $query->result();
    }

    function get_tender_list($district_id, $category_id, $type_id) {
        $this->db->select('s.id, d.name as district, b.name as block, s.scheme_id, s.name, c.subcategory as category, '
                . 't.name as type, a.name as agency, s.length, s.unit, s.sanctioned_cost, s.admin_no, s.admin_date, s.call_no, s.retender');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.category_id' => $category_id,
            's.type_id' => $type_id,
            's.istender' => 0,
            's.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(CATEGORY . ' c', 's.category_id=c.id');
        $this->db->join(TYPE . ' t', 's.type_id=t.id');
        $this->db->join(AGENCY . ' a', 's.agency_id=a.id');
        $this->db->order_by('d.name, b.name');
        $query = $this->db->get(SCHEME . ' s');
        return $query->result();
    }

    function get_tender_info($scheme_id) {
        $this->db->where(array(
            'scheme_id' => $scheme_id,
            'isactive' => 1
        ));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get(SCHEME_TENDER);
        return $query->num_rows() > 0 ? $query->row() : '';
    }

    function get_call_no($scheme_id) {
        $call_no = 1;
        $this->db->select('call_no, isnextcall, isretender, isactive');
        $this->db->where(array(
            'scheme_id' => $scheme_id,
            'isactive >' => 0
        ));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get(SCHEME_TENDER);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $call_no = $row->isretender == 1 ? 1 : ($row->call_no + 1);
        }
        return $call_no;
    }

    function get_wo_list($district_id, $category_id, $type_id) {
        $this->db->select('s.id, d.name as district, b.name as block, s.scheme_id, s.name, c.subcategory as category, '
                . 't.name as type, a.name as agency, s.length, s.unit, s.sanctioned_cost, s.admin_no, s.admin_date');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.category_id' => $category_id,
            's.type_id' => $type_id,
            's.istender' => 1,
            's.iswo' => 0,
            's.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(CATEGORY . ' c', 's.category_id=c.id');
        $this->db->join(TYPE . ' t', 's.type_id=t.id');
        $this->db->join(AGENCY . ' a', 's.agency_id=a.id');
        $this->db->order_by('d.name, b.name');
        $query = $this->db->get(SCHEME . ' s');
        return $query->result();
    }

    function get_wo_info($scheme_id) {
        $this->db->where(array(
            'scheme_id' => $scheme_id,
            'isactive' => 1
        ));
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get(SCHEME_WO);
        return $query->num_rows() > 0 ? $query->row() : '';
    }

    function save($data) {
        $this->db->trans_start();
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'name' => $data['name'],
            'sanctioned_cost' => $data['sanctioned_cost'],
            'category_id' => $data['category_id'],
            'type_id' => $data['type_id'],
            'road_type_id' => $data['road_type_id'] != '' ? $data['road_type_id'] : null,
            'work_type_id' => $data['work_type_id'] != '' ? $data['work_type_id'] : null,
            'note' => $data['note'] != '' ? $data['note'] : null,
            'length' => $data['length'],
            'unit' => $data['unit'],
            'agency_id' => $data['agency_id'],
            'admin_no' => $data['admin_no'],
            'admin_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))),
            'district_id' => $data['district_id'],
            'block_id' => implode(',', $data['block_id']),
            'mp_id' => $data['mp_id'] != '' ? implode(',', $data['mp_id']) : null,
            'mla_id' => $data['mla_id'] != '' ? implode(',', $data['mla_id']) : null
        );
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(SCHEME, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(SCHEME, $input);
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

    function tender_save($data) {
        $this->db->trans_start();
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'call_no' => $data['call_no'],
            'nit_no' => $data['nit_no'],
            'nit_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['nit_date']))),
            'bid_submission_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['bid_submission_date']))),
            'bid_opening_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['bid_opening_date']))),
            'technical_evaluation' => $data['technical_evaluation'] != '' ? $data['technical_evaluation'] : null,
            'tender_committee_date' => $data['tender_committee_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['tender_committee_date']))) : null,
            'financial_bid_opening_date' => $data['financial_bid_opening_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['financial_bid_opening_date']))) : null,
            'tender_matured' => $data['tender_matured'] != '' ? $data['tender_matured'] : null,
            'aot_issue_date' => $data['aot_issue_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['aot_issue_date']))) : null,
            'lop_issue_date' => $data['lop_issue_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['lop_issue_date']))) : null,
            'islocked' => $data['islocked'] != null ? $data['islocked'] : 0
        );
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(SCHEME_TENDER, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(SCHEME_TENDER, $input);
            $id = $this->db->insert_id();
        }
        if ($data['call_no'] == 1) {
            $input = array(
                'call_no' => 1
            );
            $this->db->where('id', $data['scheme_id']);
            $this->db->update(SCHEME, $input);
        }
        if ($data['islocked'] == 1) {
            $input = array(
                'istender' => 1
            );
            $this->db->where('id', $data['scheme_id']);
            $this->db->update(SCHEME, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }

    function save_next_call($data) {
        $input = array(
            'isnextcall' => 1,
            'remarks' => $data['remarks'],
            'isactive' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update(SCHEME_TENDER, $input);
        $this->db->select('call_no');
        $this->db->where('id', $data['scheme_id']);
        $query = $this->db->get(SCHEME);
        $call_no = $query->row()->call_no;
        $input = array(
            'call_no' => ($call_no + 1)
        );
        $this->db->where('id', $data['scheme_id']);
        $this->db->update(SCHEME, $input);
        return true;
    }

    function save_retender($data) {
        $input = array(
            'isretender' => 1,
            'remarks' => $data['remarks'],
            'isactive' => 2
        );
        $this->db->where('id', $data['id']);
        $this->db->update(SCHEME_TENDER, $input);
        $this->db->select('retender');
        $this->db->where('id', $data['scheme_id']);
        $query = $this->db->get(SCHEME);
        $retender = $query->row()->retender;
        $input = array(
            'call_no' => 1,
            'retender' => ($retender + 1)
        );
        $this->db->where('id', $data['scheme_id']);
        $this->db->update(SCHEME, $input);
        return true;
    }

    function wo_save($data) {
        $this->db->trans_start();
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
            'wo_no' => $data['wo_no'],
            'completion_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['completion_date']))),
            'contractor_name' => $data['contractor_name'],
            'contractor_pan' => $data['contractor_pan'],
            'contract_rate' => $data['contract_rate'],
            'awarded_cost' => $data['awarded_cost'],
            'barchart_given' => isset($data['bar_chart_given']) && $data['bar_chart_given'] != '' ? $data['bar_chart_given'] : null,
            'security_deposite_cost' => isset($data['security_deposite_cost']) && $data['security_deposite_cost'] != '' ? $data['security_deposite_cost'] : null,
            'additional_ps_cost' => $data['additional_ps_cost'] != '' ? $data['additional_ps_cost'] : null,
            'additional_ps_lapse_date' => $data['additional_ps_lapse_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['additional_ps_lapse_date']))) : null,
            'dlp' => $data['dlp'] != '' ? $data['dlp'] : null,
            'dlp_period' => $data['dlp_period'] != '' ? $data['dlp_period'] : null,
            'car_insurance' => $data['car_insurance'] != '' ? $data['car_insurance'] : null,
            'islocked' => isset($data['islocked']) && $data['islocked'] != null ? $data['islocked'] : 0
        );
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(SCHEME_WO, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(SCHEME_WO, $input);
            $id = $this->db->insert_id();
        }
        if ($data['islocked'] == 1) {
            $input = array(
                'iswo' => 1
            );
            $this->db->where('id', $data['scheme_id']);
            $this->db->update(SCHEME, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }
    function get_rpt_districtwise_list($selected){
        $this->db->distinct();
        $this->db->select('d.name as district, COUNT(s.id) as approved_projects, round(sum(s.sanctioned_cost)/100000000, 2) as approved_amount, count(st.id) as tender_invited, sum(st.islocked) as tender_matured, count(sw.id) as wo_issued, round(sum(sw.awarded_cost)/100000000, 2) as wo_amount');
        $this->db->where(array(
            's.isactive' => 1
        ));
        if($selected['district_id'] > 0) {
            $this->db->where('s.district_id', $selected['district_id']);
        }
        if($selected['category_id'] > 0) {
            $this->db->where('s.category_id', $selected['category_id']);
        }
        // if($selected['type_id'] > 0) {
        //     $this->db->where('s.type_id', $selected['type_id']);
        // }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(SCHEME_TENDER . ' st', 'st.scheme_id=s.id and st.isactive=1', 'left');
        $this->db->join(SCHEME_WO . ' sw', 'sw.scheme_id=s.id and sw.isactive=1', 'left');
        $this->db->group_by('d.name');
        $this->db->order_by('d.name');
        $query = $this->db->get(SCHEME . ' s');
        //echo $this->db->last_query(); exit;
        return $query->result();
    }
    function get_rpt_workwise_list($selected){
        $this->db->distinct();
        $this->db->select('d.name as district,sg.name as agency,s.length,s.name as project_name,s.id as project_id,sc.subcategory as ridf_tranche,sy.name as project_type,s.sanctioned_cost as amount');
    $this->db->where(array(
     's.isactive' => 1
    ));
    $this->db->join(DIVISION . ' d', 's.district_id=d.id');
    $this->db->join(SCHEME_CATEGORY . ' sc', 's.category_id=sc.id');
    $this->db->join(SCHEME_TYPE . ' sy', 's.type_id=sy.id and sy.isactive=1', 'left');
    $this->db->join(SCHEME_TENDER . ' st', 'st.scheme_id=s.id and st.isactive=1', 'left');
    $this->db->join(SCHEME_AGENCY . ' sg', 's.agency_id=sg.id and sg.isactive=1', 'left');
    $query = $this->db->get(SCHEME . ' s');
    //echo $this->db->last_query(); exit;
    return $query->result();
}
//Projects approved but Tender not invited
function get_rpt_workwise_list_1($category,$selected){
  $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
  FROM scheme s 
  join division d on s.district_id=d.id 
  join scheme_category sc on s.category_id=sc.id 
  join scheme_type sy on s.type_id=sy.id
  join scheme_agency sg on s.agency_id=sg.id 
  WHERE s.call_no=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
  $query = $this->db->query($sql);
       return $query->result();
    } 
    //Projects for which tender invited but not matured
    function get_rpt_workwise_list_2($category, $selected){
       $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
       FROM scheme s 
       join division d on s.district_id=d.id 
       join scheme_category sc on s.category_id=sc.id 
       join scheme_type sy on s.type_id=sy.id
       join scheme_agency sg on s.agency_id=sg.id 
       WHERE s.call_no>0 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
   $query = $this->db->query($sql);
   return $query->result();
    }
    //Projects for which tender in First Call
    function get_rpt_workwise_list_3($category,$selected){
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.call_no=1 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);
          return $query->result();

    
    }
    //Projects for which tender in Second Call
     function get_rpt_workwise_list_4($category,$selected){
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.call_no=2 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);
    return $query->result();
       
     }
     //Projects for which tender in Third Call
    function get_rpt_workwise_list_5($category,$selected){
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.call_no=3 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);
    return $query->result();

    }
    //Projects for which tender in Fourth Call and above
    function get_rpt_workwise_list_6($category,$selected){
        
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.call_no>3 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);  
    return $query->result();
    }
    //Projects for which tender matured but Projects order not issued
    function get_rpt_workwise_list_7($category,$selected){
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.istender=1 and s.iswo=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
        $query = $this->db->query($sql);
        return $query->result();
    }
    //Projects for which Projects order issued but Projects not started
    function get_rpt_workwise_list_8($category,$selected){
        $sql ='SELECT DISTINCT d.name as district, sg.name as agency, s.scheme_id, s.name, sc.subcategory as category, sy.name as type, s.length, round(s.sanctioned_cost/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.iswo=1 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 group by d.name, sg.name, sc.subcategory, sy.name';
        $query = $this->db->query($sql);
        return $query->result();
    }
    function get_rpt_workwise_list_9($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_10($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_11($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_12($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_13($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_14($category,$selected){
        $sql='';
        $query = $this->db->query($sql);
    }
    function get_rpt_workwise_list_15($category,$selected){
        $sql='';
        $query = $this->db->query($sql); 
    }
    function get_rpt_workwise_list_16($category,$selected){
        $sql='';
        $query = $this->db->query($sql);
    }
    function get_rpt_workwise_list_17($category,$selected){
        $sql='';
        $query = $this->db->query($sql);
    }
    function get_rpt_agency_progress($selected){
        $this->db->distinct();
        $this->db->select('d.name as district,sg.name as agency, COUNT(s.id) as approved_projects, round(sum(s.sanctioned_cost)/100000000, 2) as approved_amount, count(st.id) as tender_invited, sum(st.islocked) as tender_matured, count(sw.id) as wo_issued, round(sum(sw.awarded_cost)/100000000, 2) as wo_amount');
        $this->db->where(array(
    's.isactive' => 1
        ));
        if($selected['district_id'] > 0) {
            $this->db->where('s.district_id', $selected['district_id']);
        }
        if($selected['category_id'] > 0) {
            $this->db->where('s.category_id', $selected['category_id']);
        }
        if($selected['type_id'] > 0) {
            $this->db->where('s.type_id', $selected['type_id']);
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(SCHEME_TENDER . ' st', 'st.scheme_id=s.id and st.isactive=1', 'left');
        $this->db->join(SCHEME_WO . ' sw', 'sw.scheme_id=s.id and sw.isactive=1', 'left');
        $this->db->join(SCHEME_AGENCY . ' sg', 's.agency_id=sg.id and sg.isactive=1', 'left');
        $this->db->group_by('d.name');
        $this->db->order_by('d.name');
        $query = $this->db->get(SCHEME . ' s');
        //echo $this->db->last_query(); exit;
        return $query->result();
    }
   
function get_rpt_synoptic($category, $selected){
    $this->db->select('d.name as district,sg.name as agency,sc.subcategory as ridf_tranche,sy.name as project_type,s.sanctioned_cost as amount');
    $this->db->where(array(
        'sc.category' => $category,
     's.isactive' => 1
    ));
    if($selected['district_id'] > 0) {
        $this->db->where('s.district_id', $selected['district_id']);
    }
    if($selected['category_id'] > 0) {
        $this->db->where('s.category_id', $selected['category_id']);
    }
    if($selected['type_id'] > 0) {
        $this->db->where('s.type_id', $selected['type_id']);
    }
    if($selected['agency_id'] > 0) {
        $this->db->where('s.agency_id', $selected['agency_id']);
    }
    // if($selected['synoptic_id'] > 0) {
    //     $this->db->where('synoptic_id', $selected['synoptic_id']);
    // }
    $this->db->join(DIVISION . ' d', 's.district_id=d.id');
    $this->db->join(SCHEME_CATEGORY . ' sc', 's.category_id=sc.id');
    $this->db->join(SCHEME_TYPE . ' sy', 's.type_id=sy.id and sy.isactive=1', 'left');
    $this->db->join(SCHEME_TENDER . ' st', 'st.scheme_id=s.id and st.isactive=1', 'left');
    $this->db->join(SCHEME_AGENCY . ' sg', 's.agency_id=sg.id and sg.isactive=1', 'left');
    $query = $this->db->get(SCHEME . ' s');
    //echo $this->db->last_query(); exit;
    return $query->result();
}

function build_filter_condition($selected) {
    $where = '';
    if($selected['district_id'] > 0) {
        $where .= ' and district_id=' . $selected['district_id'];
    }
    if($selected['category_id'] > 0) {
        $where .= ' and category_id=' . $selected['category_id'];
    }
    if($selected['type_id'] > 0) {
        $where .= ' and type_id=' . $selected['type_id'];
    }
    if($selected['agency_id'] > 0) {
        $where .= ' and agency_id=' . $selected['agency_id'];
    }
    return $where;
}
//Projects approved but Tender not invited
function get_rpt_synoptic_1($category,$selected){
$where = $this->build_filter_condition($selected);
$sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
FROM scheme s 
join division d on s.district_id=d.id 
join scheme_category sc on s.category_id=sc.id 
join scheme_type sy on s.type_id=sy.id
join scheme_agency sg on s.agency_id=sg.id 
WHERE s.call_no=1 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 
'.$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
$query = $this->db->query($sql);
   return $query->result();
} 
//Projects for which tender invited but not matured
function get_rpt_synoptic_2($category,$selected){
    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.call_no=1 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 ' .$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);
       return $query->result();
}
//Projects for which tender in First Call
function get_rpt_synoptic_3($category,$selected){
    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.call_no=1 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 ' .$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
$query = $this->db->query($sql);
return $query->result();

}
//Projects for which tender in Second Call
 function get_rpt_synoptic_4($category,$selected){

    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.call_no=2 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 ' .$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
$query = $this->db->query($sql);
   
       return $query->result();
 }
 //Projects for which tender in Third Call
function get_rpt_synoptic_5($category,$selected){
   
    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.call_no=3 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 ' .$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
$query = $this->db->query($sql);
   
       return $query->result();
}
//Projects for which tender in Fourth Call and above
function get_rpt_synoptic_6($category,$selected){

    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.call_no>3 and s.istender=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 ' .$where . ' group by d.name, sg.name, sc.subcategory, sy.name';
$query = $this->db->query($sql);
   
       return $query->result();
}
//Projects for which tender matured but Projects order not issued
function get_rpt_synoptic_7($category,$selected){
    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
    FROM scheme s 
    join division d on s.district_id=d.id 
    join scheme_category sc on s.category_id=sc.id 
    join scheme_type sy on s.type_id=sy.id
    join scheme_agency sg on s.agency_id=sg.id 
    WHERE s.istender=1 and s.iswo=0 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 
    '.$wwhere .' group by d.name, sg.name, sc.subcategory, sy.name';
    $query = $this->db->query($sql);
    return $query->result();
}
//Projects for which Projects order issued but Projects not started
function get_rpt_synoptic_8($category,$selected){
    $where = $this->build_filter_condition($selected);
    $sql ='SELECT DISTINCT d.name as district, sg.name as agency, sc.subcategory as category, sy.name as type, count(s.id) as no_of_projects, round(sum(s.sanctioned_cost)/1000000, 2) as amount
        FROM scheme s 
        join division d on s.district_id=d.id 
        join scheme_category sc on s.category_id=sc.id 
        join scheme_type sy on s.type_id=sy.id
        join scheme_agency sg on s.agency_id=sg.id 
        WHERE s.iswo=1 and s.isactive=1 and sc.category="' . $category . '" and sc.isactive=1 '.$where .' group by d.name, sg.name, sc.subcategory, sy.name';
        $query = $this->db->query($sql);
        return $query->result();
}

function get_rpt_synoptic_9($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_10($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_11($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_12($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_13($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_14($category,$selected){
    $sql='';
    $query = $this->db->query($sql);
}
function get_rpt_synoptic_15($category,$selected){
    $sql='';
    $query = $this->db->query($sql); 
}
function get_rpt_synoptic_16($category,$selected){
    $sql='';
    $query = $this->db->query($sql);
}
function get_rpt_synoptic_17($category,$selected){
    $sql='';
    $query = $this->db->query($sql);
}
}
