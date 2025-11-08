<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RIDF_Model extends CI_Model {

    function get_scheme_list($district_id,  $category_id, $type_id) {
        $sql = 'SELECT r.id, r.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac, a.name as agency, r.category_id, r.scheme_id, r.length, r.sanctioned_cost, r.admin_date, r.admin_no, r.road_type_name as work_type, r.length, r.isactive, r.note, r.survey_status, r.tender_status, r.wo_status from ridf as r '
                .'JOIN division as d on d.id = r.district_id '
                .'JOIN division as b on b.id = r.block_id '
                .'LEFT JOIN assembly_constituency as ac on r.ac_id = ac.id '
                .'JOIN scheme_agency as a on a.id = r.agency_id '
                .'where r.isactive = 1 and a.ridf = 1 and r.district_id = '.$district_id.' and r.category_id='.$category_id.' and r.work_type_id = '.$type_id
                .' order by r.name';
        $query = $this->db->query($sql);
        // print_r($query->result());
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }
    function get_scheme_summary() {
        $sql = 'SELECT sc.subcategory as category, count(s.id) as total, count(tender.id) as tender, count(wo.id) as wo
            FROM scheme_category sc LEFT JOIN ridf s ON s.category_id=sc.id and s.isactive=1
            LEFT JOIN (SELECT id, category_id FROM scheme WHERE isactive=1 AND istender=1) as tender ON tender.category_id=s.category_id AND tender.id=s.id
            LEFT JOIN (SELECT id, category_id FROM scheme WHERE isactive=1 AND iswo=1) as wo ON wo.category_id=s.category_id AND wo.id=s.id
            WHERE sc.isactive=1 and sc.category = "ridf" GROUP BY sc.subcategory ORDER BY sc.id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_tender_and_wo_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'and district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $sql = "SELECT COUNT(id) as tender_matured, ifnull(SUM(length),0) as tender_matured_length, SUM(cost) as tender_amount FROM ridf "
                . "WHERE isactive = 1 AND tender_status = 2 " . $where;
        $query = $this->db->query($sql);
        $tender_matured_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_order, ifnull(SUM(s.length),0) as work_order_length, SUM(wo.awarded_cost) as sanctioned_amount FROM ridf s JOIN ridf_wo wo ON wo.ridf_id = s.id "
                . "WHERE s.isactive = 1 AND s.wo_status = 2 " . $where;
        $query = $this->db->query($sql);
        $work_order_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_progress, ifnull(SUM(s.length),0) as work_progress_length, SUM(wo.awarded_cost) as sanctioned_amount "
                . "FROM ridf s JOIN ridf_wo wo ON wo.ridf_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 AND s.pp_status = 5 " . $where;
        $query = $this->db->query($sql);
        $work_order_progress_count = $query->row();
        return array(
            'tender_matured_count' => $tender_matured_count,
            'work_order_count' => $work_order_count,
            'work_order_progress_count' => $work_order_progress_count
        );
    }

    function get_ac_list($district_id = 0) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('a.id, concat(a.no, " ", a.name, " ", IF((a.reserved is null or a.reserved = ""), "", concat("(", a.reserved, ")"))) as name');
        $this->db->where(array(
            'a.isactive' => 1,
            'a.district_id' => $district_id
        ));
        $query = $this->db->get(AC . ' a');
        // echo $this->db->last_query(); exit;
        return $query->result();
    }
    function get_assembly_list() {
        // $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('a.id, concat(a.no, " ", a.name, " ", IF((a.reserved is null or a.reserved = ""), "", concat("(", a.reserved, ")"))) as name');
        $this->db->where(array(
            'a.isactive' => 1
        ));
        $query = $this->db->get(AC . ' a');
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_scheme_info($id) {
        // $this->db->select('*');
        $this->db->where(array('id' => $id));
        $query = $this->db->get('ridf');
    //   echo  $this->db->last_query($query); exit;
        return $query->row();
    }

    function get_survey_pending_list($district_id, $block_id, $ac_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, '
                . 'concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, '
                . 'b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.road_type, s.work_type, s.agency, '
                . 's.survey_status as status, s.cost, s.return_cause');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.gp_id>' => 0,
            's.survey_status<' => 3,
            's.isactive' => 1
        ));
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        }
        if ($ac_id > 0) {
            $this->db->where('s.ac_id', $ac_id);
        }
        switch ($session['role_id']) {
            case 13:
                $this->db->where('s.agency', 'ZP');
                break;
            case 14:
                $this->db->where('s.agency', 'BLOCK');
                break;
            case 15:
                $this->db->where('s.agency', 'SRDA');
                break;
            case 16:
                $this->db->where('s.agency', 'MBL');
                break;
            case 17:
                $this->db->where('s.agency', 'AGRO');
                break;
            default:
                break;
        }
        $this->db->where('s.survey_lot_no is null');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function get_project_info($id) {
        $this->db->select('d.name as district, b.name as block, s.name, s.village, s.agency, s.length, s.road_type, s.work_type, s.survey_status as status');
        $this->db->where('s.id', $id);
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->row();
    }

    function save($data) {
        $this->db->trans_start();
        // print_r($input); exit;
        $this->db->select('id,name');
        $this->db->where('id', $data['ac_id']);
        $query = $this->db->get('assembly_constituency');
        $ac_name = $query->row();
        $this->db->select('name');
        $this->db->where('id', $data['agency_id']);
        $query = $this->db->get('scheme_agency');
        $agency_name = $query->row()->name;
        $this->db->select('name');
        $this->db->where('id',$data['type_id']);
        $query = $this->db->get('scheme_type');
        $type_name = $query->row()->name;
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'name' => $data['name'],
            'sanctioned_cost' => $data['sanctioned_cost'],
            'category_id' => $data['category_id'],
            // 'type_id' => $data['type_id'],
            'road_type_id' => $data['road_type_id'] != '' ? $data['road_type_id'] : null,
            'road_type_name' => $type_name,
            'work_type_id' => $data['type_id'] != '' ? $data['type_id'] : null,
            'note' => $data['note'] != '' ? $data['note'] : null,
            'length' => $data['length'],
            'unit' => $data['unit'],
            'agency_id' => $data['agency_id'],
            'agency' => $agency_name,
            'admin_no' => $data['admin_no'],
            'admin_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))),
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'ac_id' => $data['ac_id'],
            'ac' => $ac_name->name,
            'survey_status' => 1,
            // 'survey_date' => date('Y-m-d')
        );
        // print_r($input); exit;
        $id = $data['id'];
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update('ridf', $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert('ridf', $input);
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

    function survey_save($data) {
        $this->db->trans_start();
        $input = array(
            'survey_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['survey_date']))),
            'length' => $data['length'],
            'survey_status' => $data['status']
        );
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        $input = array(
            'created' => date('Y-m-d'),
            'ridf_id' => $data['id'],
            'survey_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['survey_date']))),
            'length' => $data['length'],
            'surveyor_name' => $data['surveyor_name'],
            'surveyor_designation' => $data['surveyor_designation'],
            'surveyor_mobile' => $data['surveyor_mobile'],
            'status' => $data['status']
        );
        $this->db->insert('ridf_SURVEY_LOG', $input);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function survey_vec_save($data) {
        $input = array(
            'cost' => $data['cost']
        );
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        return true;
    }

    function create_lot_no($role_id, $data, $lotno) {
        switch ($role_id) {
            case 2:
            case 3:
                $input = array(
                    'sa_lot_no' => $lotno
                );
                break;
            case 7:
                $input = array(
                    'se_lot_no' => $lotno
                );
                break;
            case 12:
                $input = array(
                    'dm_lot_no' => $lotno
                );
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
                $input = array(
                    'survey_lot_no' => $lotno
                );
                break;
            default:
                break;
        }
        $input['return_cause'] = '';
        foreach ($data as $k => $v) {
            $this->db->where('id', $k);
            $this->db->update('ridf', $input);
        }
        return true;
    }

    

    function get_district_list() {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            'd.level_id' => 2,
            'd.isactive' => 1
        ));
        switch ($session['role_id']) {
            case 2:
            case 3:
                $this->db->where('sa_lot_no is not null');
                $this->db->where('survey_status', 5);
                break;
            case 7:
                $this->db->where('se_lot_no is not null');
                $this->db->where('survey_status', 4);
                break;
            case 12:
                $this->db->where('dm_lot_no is not null');
                $this->db->where('survey_status', 3);
                break;
            case 13:
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'ZP');
                $this->db->where('survey_status', 2);
                break;
            case 14:
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'BLOCK');
                $this->db->where('survey_status', 2);
                break;
            case 15:
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'SRDA');
                $this->db->where('survey_status', 2);
                break;
            case 16:
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'MBL');
                $this->db->where('survey_status', 2);
                break;
            case 17:
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'AGRO');
                $this->db->where('survey_status', 2);
                break;
            default:
                break;
        }
        if ($session['district_id'] > 0) {
            $this->db->where_in('s.district_id', explode(',', $session['district_id']));
        }
        $this->db->where('admin_approval_no is null');
        $this->db->order_by('d.name');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function get_lotno_list($district_id) {
        $session = $this->common->get_session();
        $this->db->distinct();
        switch ($session['role_id']) {
            case 2:
            case 3:
                $this->db->select('sa_lot_no as lotno');
                $this->db->where('sa_lot_no is not null');
                $this->db->where('survey_status', 5);
                break;
            case 7:
                $this->db->select('se_lot_no as lotno');
                $this->db->where('se_lot_no is not null');
                $this->db->where('survey_status', 4);
                break;
            case 12:
                $this->db->select('dm_lot_no as lotno');
                $this->db->where('dm_lot_no is not null');
                $this->db->where('survey_status', 3);
                break;
            case 13:
                $this->db->select('survey_lot_no as lotno');
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'ZP');
                $this->db->where('survey_status', 2);
                break;
            case 14:
                $this->db->select('survey_lot_no as lotno');
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'BLOCK');
                $this->db->where('survey_status', 2);
                break;
            case 15:
                $this->db->select('survey_lot_no as lotno');
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'SRDA');
                $this->db->where('survey_status', 2);
                break;
            case 16:
                $this->db->select('survey_lot_no as lotno');
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'MBL');
                $this->db->where('survey_status', 2);
                break;
            case 17:
                $this->db->select('survey_lot_no as lotno');
                $this->db->where('survey_lot_no is not null');
                $this->db->where('agency', 'AGRO');
                $this->db->where('survey_status', 2);
                break;
            default:
                break;
        }
        if ($district_id > 0) {
            $this->db->where('district_id', $district_id);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('block_id', explode(',', $session['block_id']));
        }
        $this->db->where('admin_approval_no is null');
        $query = $this->db->get('ridf');
        return $query->result();
    }

    function get_lot_list($district_id, $lotno) {
        $session = $this->common->get_session();
        $role_id = $session['role_id'];
        $lot_ref = ($role_id == 2 || $role_id == 3) ? 's.sa_lot_no' : ($role_id == 7 ? 's.se_lot_no' : ($role_id == 12 ? 's.dm_lot_no' : 's.survey_lot_no'));
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, gp.name as gp, s.ref_no, ' . $lot_ref . ' as lotno, s.name, s.length, s.road_type, s.work_type, s.agency, s.survey_status as status, s.cost');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.survey_status>' => 1,
            's.isactive' => 1
        ));
        switch ($session['role_id']) {
            case 2:
            case 3:
                $this->db->where('s.sa_lot_no', $lotno);
                break;
            case 7:
                $this->db->where('s.se_lot_no', $lotno);
                break;
            case 12:
                $this->db->where('s.dm_lot_no', $lotno);
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
                $this->db->where('s.survey_lot_no', $lotno);
                break;
            default:
                break;
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
//        echo $this->db->last_query();
//        exit;
        return $query->result();
    }

    function get_approval_list($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, '
                . 'concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, '
                . 'b.name as block, s.ref_no, s.name, s.proposed_length, s.length, s.road_type, s.work_type, s.agency, '
                . 's.survey_status as status, s.cost, s.return_cause');
        $this->db->where(
                array(
                    's.district_id' => $district_id,
                    's.isactive' => 1
                )
        );
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        }
        switch ($session['role_id']) {
            case 2:
            case 3:
                $this->db->select('s.se_lot_doc as lot_doc, s.se_lot_no as lot_no');
                $this->db->where('s.survey_status', 5);
                $this->db->where('s.sa_lot_no is null');
                break;
            case 7:
                $this->db->select('if((s.agency="srda" || s.agency="mbl" || s.agency="agro"), s.survey_lot_doc, s.dm_lot_doc) as lot_doc, if((s.agency="srda" || s.agency="mbl" || s.agency="agro"), s.survey_lot_no, s.dm_lot_no) as lot_no');
                $this->db->where('s.survey_status', 4);
                $this->db->where('s.se_lot_no is null');
                break;
            case 12:
                $this->db->select('s.survey_lot_doc as lot_doc, s.survey_lot_no as lot_no');
                $this->db->where('s.survey_status', 3);
                $this->db->where('s.dm_lot_no is null');
                break;
            default:
                break;
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
//        echo $this->db->last_query();
//        exit;
        return $query->result();
    }

    function return_to_prev($data, $msg) {
        $session = $this->common->get_session();
        foreach ($data as $d) {
            switch ($session['role_id']) {
                case 2:
                case 3:
                    $input = array(
                        'se_approval_date' => null,
                        'se_lot_no' => null,
                        'se_lot_doc' => null,
                        'survey_status' => 4
                    );
                    break;
                case 7:
                    $sql = 'SELECT agency FROM ridf WHERE id=' . $d;
                    $query = $this->db->query($sql);
                    $agency = $query->row()->agency;
                    if ($agency == 'BLOCK' || $agency == 'ZP') {
                        $input = array(
                            'dm_approval_date' => null,
                            'dm_lot_no' => null,
                            'dm_lot_doc' => null,
                            'survey_status' => 3
                        );
                    } else {
                        $input = array(
                            'survey_lot_no' => null,
                            'survey_lot_doc' => null,
                            'survey_status' => 0
                        );
                    }
                    break;
                case 12:
                    $input = array(
                        'survey_lot_no' => null,
                        'survey_lot_doc' => null,
                        'survey_status' => 0
                    );
                    break;
                default:
                    break;
            }
            $input['return_cause'] = $msg;
            $this->db->where('id', $d);
            $this->db->update('ridf', $input);
        }
        return true;
    }

    function remove_survey_list($id) {
        $input = array(
            'isactive' => -2
        );
        $this->db->where('id', $id);
        $this->db->update('ridf', $input);
    }

    function get_scheme_not_implemented($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('nil.ridf_id as id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.road_type, s.work_type, s.agency, s.proposed_length, nil.status_id as status, nil.remarks, nil.created');
        $this->db->where(array(
            's.isactive' => -1
                )
        );
        if ($district_id > 0) {
            $this->db->where('s.district_id', $district_id);
        }
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        }
        $this->db->join('(SELECT * FROM ridf_not_implemented tnil JOIN (SELECT ridf_id as c_ridf_id, MAX(id) as max_id FROM ridf_not_implemented GROUP BY ridf_id) mtnil ON tnil.id = mtnil.max_id)' . ' nil', 's.id=nil.ridf_id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->order_by('s.id', 'DESC');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function delete_not_traceable($id) {
        $input['isactive'] = -2;
        $this->db->where('id', $id);
        $this->db->update('ridf', $input);
        return TRUE;
    }

    function get_approved_list($district_id, $category_id = 0, $type_id = 0) {
        // echo  $district_id . $category_id .$type_id; exit;
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.name, s.length, '
                . 's.work_type_id, s.work_type_name, s.agency, s.sanctioned_cost, '
                . 's.admin_date, s.admin_no, s.admin_approval_doc as lot_doc');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.isactive' => 1
        ));
        if ($category_id > 0) {
            $this->db->where('s.category_id', $category_id);
        }
        if ($type_id > 0) {
            $this->db->where('s.road_type_id', $type_id);
        }
        switch ($session['role_id']) {
            case 13:
                $this->db->where('s.agency', 'ZP');
                break;
            case 14:
                $this->db->where('s.agency', 'BLOCK');
                break;
            case 15:
                $this->db->where('s.agency', 'WBSRDA');
                break;
            case 16:
                $this->db->where('s.agency', 'MBL');
                break;
            case 17:
                $this->db->where('s.agency', 'AGRO');
                break;
            default:
                break;
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join('scheme_agency' . ' a', 's.agency_id=a.id');
        $this->db->join('scheme_type' . ' st', 's.road_type_id=st.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function print_lot($district_id, $lotno) {
        $this->db->select('d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.agency, gp.name as gp, s.name, s.length, s.work_type, s.road_type, s.cost');
        $this->db->where(array(
            's.isactive' => 1,
            's.district_id' => $district_id,
            's.survey_status>' => 1
        ));
        $this->db->where('(s.survey_lot_no="' . $lotno . '" or s.dm_lot_no="' . $lotno . '" or s.se_lot_no="' . $lotno . '" or s.sa_lot_no="' . $lotno . '")');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
//        echo $this->db->last_query();
//        exit;
        return $query->result();
    }

    ################################################################################

    function get_state_approval_list($district_id, $block_id) {
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.road_type, s.agency, s.survey_status as status');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.block_id' => $block_id,
            's.gp_id>' => 0,
            's.survey_status' => 3,
            's.isactive' => 1
                )
        );
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function back_to_inbox($id) {
        $input = array(
            'survey_lot_no' => null,
            'survey_lot_doc' => null
        );
        $this->db->where(array(
            'id' => $id,
            'survey_status' => 0
        ));
        $this->db->update('ridf', $input);
    }

    function not_traceable_save($data) {
        $this->db->trans_start();
        $input = array(
            'isactive' => '-1'
        );
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        $input = array(
            'created' => date('Y-m-d'),
            'ridf_id' => $data['id'],
            'status_id' => $data['status'],
            'remarks' => $data['remarks']
        );
        $this->db->insert('ridf_NOT_IMPLEMENTED_LOG', $input);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // Tender
   function get_tender_list($district_id, $category_id, $type_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.name, s.length, s.agency, s.tender_number, s.tender_publication_date, s.tender_status, s.sanctioned_cost, s.scheme_id, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where(array(
           's.district_id' => $district_id,
            's.category_id' => $category_id,
            's.road_type_id' => $type_id,
            's.tender_status' => 0,
            's.isactive' => 1
        ));
        // if ($block_id > 0) {
        //     $this->db->where('s.block_id', $block_id);
        // } else if ($session['block_id'] > 0) {
        //     $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        // }
        // if ($ac_id > 0) {
        //     $this->db->where('s.ac_id', $ac_id);
        // }
        switch ($session['role_id']) {
            case 13:
                $this->db->where('s.agency', 'ZP');
                break;
            case 14:
                $this->db->where('s.agency', 'BLOCK');
                break;
            case 15:
                $this->db->where('s.agency', 'SRDA');
                break;
            case 16:
                $this->db->where('s.agency', 'MBL');
                break;
            case 17:
                $this->db->where('s.agency', 'AGRO');
                break;
            default:
                break;
        }
        $this->db->join('(SELECT * FROM ridf_tender_log tl JOIN (SELECT ridf_id as c_ridf_id, MAX(id) as max_id FROM ridf_tender_log GROUP BY ridf_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.ridf_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function tender_save($data) {
        // var_dump($data);exit;
        $this->db->trans_start();
        $input = array(
            'tender_number' => $data['tender_number'],
            'tender_publication_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['tender_publication_date'])))
        );
        // if ($data['tender_status'] == 0) {
        //     $input['tender_status'] = 1;
        // }
        $input['tender_status'] = $data['tender_status'];

        if (isset($data['bid_matured_status']) && !is_null($data['bid_matured_status']) && $data['bid_matured_status'] == 0) {
            $input['tender_status'] = 3;
        }

        if (isset($data['bid_matured_status']) && !is_null($data['bid_matured_status']) && $data['bid_matured_status'] == 1) {
            $input['tender_status'] = 2;
        }
        // var_dump($input);exit;

        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        $input = array(
            'created' => date('Y-m-d'),
            'ridf_id' => $data['id'],
            'tender_number' => $data['tender_number'],
            'tender_publication_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['tender_publication_date'])))
        );
        // if ($data['tender_status'] == 0) {
        //     $input['tender_status'] = 1;
        // }
        $input['tender_status'] = $data['tender_status'];

        if (isset($data['bid_matured_status']) && !is_null($data['bid_matured_status']) && $data['bid_matured_status'] == '0') {
            $input['tender_status'] = 3;
        }
        if (isset($data['bid_matured_status']) && !is_null($data['bid_matured_status']) && $data['bid_matured_status'] == 1) {
            $input['tender_status'] = 2;
        }

        if (!is_null($data['bid_opeaning_date']) && strlen($data['bid_opeaning_date']) == 10) {
            $input['bid_opeaning_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['bid_opeaning_date'])));
        }
        if (!is_null($data['bid_closing_date']) && strlen($data['bid_closing_date']) == 10) {
            $input['bid_closing_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['bid_closing_date'])));
        }
        if (!is_null($data['evaluation_status']) && strlen($data['evaluation_status']) > 0) {
            $input['evaluation_status'] = $data['evaluation_status'];
        }
        if (isset($data['bid_opening_status']) && !is_null($data['bid_opening_status']) && strlen($data['bid_opening_status']) > 0) {
            $input['bid_opening_status'] = $data['bid_opening_status'];
        }
        if (isset($data['bid_matured_status']) && !is_null($data['bid_matured_status']) && strlen($data['bid_matured_status']) > 0) {
            $input['bid_matured_status'] = $data['bid_matured_status'];
        }
        // var_dump($data);exit;
        $this->db->insert('ridf_TENDER_LOG', $input);

        //manage for now - sujay
        /* $sql = 'UPDATE ridf_tender_log SET tender_status=2 WHERE bid_matured_status=1';
          $this->db->query($sql);

          $sql = 'UPDATE ridf SET tender_status=2 WHERE id in (SELECT DISTINCT ridf_id FROM ridf_tender_log WHERE bid_matured_status = 1)';
          $this->db->query($sql); */

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }


    function tender_benefitted_save($data) {
        // var_dump($data);exit;
        $input = array(
            'total_population' => $data['total_population'],
            'total_households' => $data['total_households'],
            'no_of_village' => $data['no_of_village'],
            'tender_status' => 1
        );
        // var_dump($input);exit;
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        return TRUE;
    }

    function get_wo_info($id) {
        $this->db->select('wo.id, sr.id as ridf_id, sr.name, sr.district_id, wo.wo_no, '
                . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
                . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
                . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
                . 'wo.dlp_submitted, wo.document, wo.assigned_engineer, wo.designation, wo.mobile');
        $this->db->where(array(
            'sr.id' => $id
        ));
        $this->db->join('ridf_WO' . ' wo', 'wo.ridf_id=sr.id and wo.isactive=1', 'left');
        $query = $this->db->get('ridf' . ' sr');
        return $query->row();
    }

    function get_wo_list($district_id = 0, $category_id = 0, $type_id = 0) {
        $session = $this->common->get_session();
        // $type_id = $type_id != '' ? $type_id : '';
        $this->db->select('d.name as district, b.name as block, ac.name as ac, s.name,s.agency, s.tender_number, '
                . 'date_format(s.tender_publication_date, "%d/%m/%Y") as tender_publication_date, date_format(s.tender_end_date, "%d/%m/%Y") as tender_end_date, '
                . 'wo.id, s.id as ridf_id, wo.wo_no, date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, '
                . 'date_format(wo.completion_date, "%d/%m/%Y") as completion_date, wo.assigned_engineer, wo.mobile, wo.document');
        $this->db->where(array(
            's.tender_status' => 2,
            's.isactive' => 1,
            's.district_id'=> $district_id,
            's.category_id'=> $category_id,
            's.work_type_id'=> $type_id

        ));
        // if ($district_id > 0) {
        //     $this->db->where('s.district_id', $district_id);
        // }
        // if ($category_id > 0) {
        //     $this->db->where('s.category_id', $category_id);
        // }
        // if ($type_id > 0) {
        //     $this->db->where('s.road_type_id', $type_id);
        // }
        $this->db->join('scheme_type' . ' type', 's.road_type_id=type.id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join('scheme_category' . ' sc', 's.category_id=sc.id');
        $this->db->join('ridf_WO' . ' wo', 'wo.ridf_id=s.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $this->db->group_by('wo.id, s.id, s.name, d.name, wo.wo_no, wo.wo_date, wo.contractor, wo.completion_date');
        $this->db->order_by('d.name');
        $query = $this->db->get('ridf' . ' s');
        // echo $this->db->last_query($query); exit;
        // print_r($query); exit;
        return $query->result();
    }

    function wo_save($data) {
        $this->db->trans_start();
        $ridf_id = isset($data['ridf_id']) ? $data['ridf_id'] : 0;
        $this->db->select('id, mobile');
        $this->db->where('ridf_id', $ridf_id);
        $query = $this->db->get('ridf_wo');
        $id = $query->num_rows() > 0 ? $query->row()->id : 0;
        if ($ridf_id > 0) {
            if (array_key_exists('wo_no', $data)) {
                $input = array(
                    'ridf_id' => $ridf_id,
                    'wo_no' => $data['wo_no'],
                    'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                    'contractor' => $data['contractor'],
                    'pan_no' => $data['pan_no'],
                    'rate' => $data['rate'],
                    'awarded_cost' => $data['awarded_cost'],
                    'completion_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['completion_date']))),
                    'barchart_given' => $data['barchart_given'],
                    'ps_cost' => $data['ps_cost'] != '' ? $data['ps_cost'] : NULL,
                    'lapse_date' => $data['lapse_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['lapse_date']))) : NULL,
                    'additional_ps_cost' => $data['additional_ps_cost'] != '' ? $data['additional_ps_cost'] : NULL,
                    'dlp' => $data['dlp'] != '' ? $data['dlp'] : 0,
                    'dlp_period' => $data['dlp_period'],
                    'dlp_submitted' => $data['dlp_submitted'],
                    'assigned_engineer' => $data['assigned_engineer'],
                    'designation' => $data['designation'],
                    'mobile' => $data['mobile'],
                    'isactive' => 1,
                    'islocked' => 1
                );
            } else {
                $input = array(
                    'assigned_engineer' => $data['assigned_engineer'],
                    'designation' => $data['designation'],
                    'mobile' => $data['mobile'],
                    'isactive' => 1,
                    'islocked' => 1
                );
            }
            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->update('ridf_wo', $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert('ridf_wo', $input);
                $id = $this->db->insert_id();
            }
            $this->db->select('user_id');
            $this->db->where('username', $data['mobile']);
            $query = $this->db->get(LOGIN);
            if ($query->num_rows() > 0) {
                $user_id = $query->row()->user_id;
                $sql = 'UPDATE ridf SET pe_user_id=' . $user_id . ' WHERE id=' . $ridf_id;
                $this->db->query($sql);
            } else {
                $this->db->select('district_id');
                $this->db->where('id', $ridf_id);
                $query = $this->db->get('ridf');
                $district_id = $query->row()->district_id;
                $input = array(
                    'created' => date('Y-m-d'),
                    'role_id' => 20,
                    'district_id' => $district_id,
                    'name' => $data['assigned_engineer'],
                    'mobile' => $data['mobile']
                );
                $this->db->insert(USER, $input);
                $user_id = $this->db->insert_id();
                $input = array(
                    'created' => date('Y-m-d'),
                    'user_id' => $user_id,
                    'username' => $data['mobile'],
                    'password' => $this->encryption->encrypt(DEFAULT_PWD)
                );
                $this->db->insert(LOGIN, $input);
                $sql = 'UPDATE ridf SET pe_user_id=' . $user_id . ' WHERE id=' . $ridf_id;
                $this->db->query($sql);
            }
            $input = array(
                'work_order' => array_key_exists('wo_no', $data) ? $data['wo_no'] : '',
                'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                'wo_status' => 2
            );
            $this->db->where('id', $ridf_id);
            $this->db->update('ridf', $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        } else {
            $this->db->trans_commit();
            return $id;
        }
    }

    function wo_remove($id) {
        $input['isactive'] = -1;
        $this->db->where('ridf_id', $id);
        $this->db->update('ridf_wo', $input);
        $input = array(
            'work_order' => null,
            'wo_date' => null,
            'wo_status' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('ridf', $input);
        return TRUE;
    }

    function get_wp_list($user_id, $status) {
        $where = 'WHERE r.isactive=1 AND r.pe_user_id=' . $user_id;
        switch ($status) {
            case 0: case 5:
                $where .= ' AND r.pp_status=' . $status;
                break;
            case 1: case 2: case 3: case 4:
                $where .= ' AND r.pp_status > 0 and pp_status < 5';
                break;
            default:
                break;
        }
        $sql = 'SELECT r.id, b.name as block, r.name, r.wo_start_date, r.physical_progress, r.progress_remarks, r.pp_status '
                . 'FROM ridf r JOIN division b ON r.block_id=b.id ' . $where;
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query);exit;
        return $query->result();
    }

    function get_wp_info($user_id, $id, $status) {
        $where = 'WHERE s.id=' . $id . ' AND s.survey_status=6 AND s.isactive=1 AND s.pe_user_id=' . $user_id;
        switch ($status) {
            case 0: case 5:
                $where .= ' AND s.pp_status=' . $status;
                break;
            case 1: case 2: case 3: case 4:
                $where .= ' AND s.pp_status > 0 and s.pp_status < 5';
                break;
            default:
                break;
        }
        $sql = 'SELECT s.id, b.name as block, g.name as gp, s.ref_no, s.name, s.length, s.road_type, s.work_type, '
                . 's.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status '
                . 'FROM ridf s JOIN division b ON s.block_id=b.id JOIN division g ON s.gp_id=g.id ' . $where;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function wp_save($data) {
        $progress = $data['wp_progress'];
        // print_r($progress); exit;
        $pp_status = $progress < 26 ? 1 : (($progress > 25 && $progress < 51) ? 2 : (($progress > 50 && $progress < 76) ? 3 : (($progress > 75 && $progress < 100) ? 4 : ($progress == 100 ? 5 : 0))));
        // echo '<pre>';
        // // print_r($progress);
        $input = array(
            'created' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_date']))),
            'ridf_id' => $data['id'],
            'physical_progress' => $progress,
            'progress_remarks' => $data['remarks'],
            'pp_status' => $pp_status
        );
        // print_r($input); exit;
        if (array_key_exists('wp_start_date', $data)) {
            $input['wo_start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_start_date'])));
        }
        $this->db->insert('ridf_progress', $input);
        $id = $this->db->insert_id();
        $input = array(
            'physical_progress' => $progress,
            'pp_status' => $pp_status,
            'progress_remarks' => $data['remarks']
        );
        if (array_key_exists('wp_start_date', $data)) {
            $input['wo_start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_start_date'])));
        }
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        return $id;
    }
    

    function get_inspection_list($sqm_id, $month, $year) {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, ur.name as sqm, ur.mobile as sqm_mobile, u.mobile, qm.status
            FROM ridf_qm as qm JOIN ridf s ON s.id=qm.ridf_id JOIN ridf_wo wo ON wo.ridf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status<2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_report_list($sqm_id, $month, $year) {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, u.mobile, ur.name as sqm, ur.mobile as sqm_mobile, qm.status, sqi.inspection_date, sqi.overall_grade, sqi.document
            FROM ridf_qm as qm JOIN ridf_qm_inspection sqi ON sqi.qm_id=qm.id JOIN ridf s ON s.id=qm.ridf_id JOIN ridf_wo wo ON wo.ridf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status=2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_caption($qm_id) {
        $sql = 'SELECT s.name, s.agency FROM ridf_qm as qm JOIN ridf s ON qm.ridf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_selection($qm_id) {
        $sql = 'SELECT s.name, s.agency FROM ridf_qm as qm JOIN ridf s ON qm.ridf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_category_list() {
        $this->db->select('id, subcategory as name');
        $this->db->where(array(
            'category' => 'RIDF',
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
    function get_agency_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1,
            'id >' => 3,
            'ridf' => 1
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

    function get_constitution_list($district_id = 0) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('a.id, concat(a.no, " ", a.name, " ", IF((a.reserved is null or a.reserved = ""), "", concat("(", a.reserved, ")"))) as name');
        $this->db->where(array(
            's.isactive' => 1
        ));
        $this->db->where('s.district_id=a.district_id');
        if ($session['district_id'] > 0) {
            $this->db->where('s.district_id in (' . $session['district_id'] . ')');
        } else if ($district_id > 0) {
            $this->db->where('s.district_id', $district_id);
        }
        $this->db->order_by('a.id');
        $this->db->join('ridf' . ' s', 's.ac_id=a.id');
        $query = $this->db->get(AC . ' a');
        return $query->result();
    }

################################################################################
################################ REPORT ########################################

    function filter_with_agency() {
        $session = $this->common->get_session();
        $filter = '';
        switch ($session['role_id']) {
            case 13:
                $filter = ' and agency="ZP"';
                break;
            case 14:
                $filter = ' and agency="BLOCK"';
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

    function get_rpt_state_summary($ac_id = 0) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $ac_id > 0 ? (strlen($where) > 0 ? ' and' : 'WHERE ') . 'ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'select district, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from ridf_report ' . $where . ' group by district order by district ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_agency_progress($ac_id = 0) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'select district, agency, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from ridf_report ' . $where . ' group by district, agency order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_road_type_progress($road_type, $ac_id = 0) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'AND district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' AND block_id in (' . $session['block_id'] . ')' : '';
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'select district, agency, road_type, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from ridf_report WHERE road_type="' . $road_type . '" ' . $where . ' group by district, agency, road_type order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_type_progress($work_type, $ac_id = 0) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'AND district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' AND block_id in (' . $session['block_id'] . ')' : '';
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'select district, agency, work_type,sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from ridf_report WHERE work_type="' . $work_type . '" ' . $where . ' group by district, agency, work_type order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_ps_work_status($district_id = 0, $block_id = 0, $ac_id = 0) {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? '  district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'select district, block, agency, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(ifnull(progress_25,0)) + sum(ifnull(progress_50,0)) + sum(ifnull(progress_75,0)) + sum(ifnull(progress_99,0))) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(ifnull(progress_25_amount,0)) + sum(ifnull(progress_50_amount,0)) + sum(ifnull(progress_75_amount,0)) + sum(ifnull(progress_99_amount,0))) as ongoing_amount,
            sum(ifnull(progress_100,0)) as completed, sum(ifnull(progress_100_length,0)) as completed_length, sum(ifnull(progress_100_amount,0)) as completed_amount
            from ridf_report ' . (strlen($where)>0? 'where ' . $where : '') . ' group by district,block, agency order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_progress($district_id = 0, $block_id = 0, $wp_id = 0, $ac_id = 0) {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $wp = $wp_id != 6 ? 's.pp_status ='.$wp_id : 's.pp_status >=0 and s.pp_status <=5';
        // $where .= '';
        $where .= $this->filter_with_agency();
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length, wo.awarded_cost, s.road_type, s.work_type, '
                . 's.wo_start_date, s.pp_status, s.physical_progress,s.image1,s.image2,s.image3,  wp.wp_date as last_work_date, wp.date_count as inspection_count from ridf s '
                . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join ridf_wo wo on wo.ridf_id=s.id '
                . 'JOIN (SELECT ridf_id, max(created) As wp_date,wo_start_date,count(created) as date_count FROM ridf_progress GROUP BY ridf_id) wp ON s.id = wp.ridf_id AND s.wo_start_date = wp.wo_start_date '
                . 'where s.survey_status=6 and s.isactive=1 and ' . $wp . ' ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_rpt_work_progress_details($ridf_id) {
//        $db = $this->load->database('rpt', TRUE);
        $sql = 'SELECT s.name, sp.created, sp.wo_start_date, sp.physical_progress, sp.location1, sp.image1, sp.location2, sp.image2, sp.location3,
        sp.image3, sp.progress_remarks FROM ridf_progress sp join ridf s on sp.ridf_id=s.id WHERE sp.ridf_id=' . $ridf_id . ' ORDER BY sp.created';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_qm_summary($start_date, $end_date) {
        $sql = 'SELECT d.id, d.name as district, sqm.agency, sqm.overall_grade, COUNT(sqm.overall_grade) as cnt
        FROM ridf_qm as qm
        JOIN ridf_qm_inspection sqm ON sqm.qm_id=qm.id
        JOIN division d ON qm.district_id=d.id
        WHERE sqm.isactive=1 AND sqm.inspection_date>="' . date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . '" AND sqm.inspection_date<="' . date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . '"
        GROUP BY d.name, sqm.agency, sqm.overall_grade ORDER BY d.name, sqm.agency';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_update_status($district_id = 0, $block_id = 0, $wp_id = 0) {
        $session = $this->common->get_session();
        //var_dump($session); exit;
        $where = $session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : ($district_id > 0 ? ' and district_id in (' . $district_id . ')' : '');
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : ($block_id > 0 ? ' and block_id in (' . $block_id . ')' : '');
        $where .= $this->filter_with_agency();
        $where .= $session['role_id'] == 20 ? ' and s.pe_user_id=' . $session['user_id'] : '';
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length,s.approved_length, wo.awarded_cost, s.road_type, s.work_type, '
                . 's.wo_start_date, s.pp_status, s.physical_progress, s.financial_progress, s.bill_paid, s.bill_due, isupdated '
                . 'from ridf s join division d on s.district_id=d.id '
                . 'join division b on s.block_id=b.id join ridf_wo wo on wo.ridf_id=s.id '
                . 'where s.survey_status=6 and s.isactive=1 and s.pp_status=' . $wp_id . ' ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function update_save($data) {
        $pp = $data['pp'];
        $pp_status = $pp < 25 ? 1 : (($pp > 24 && $pp < 50) ? 2 : (($pp > 49 && $pp < 75) ? 3 : (($pp > 74 && $pp < 100) ? 4 : ($pp == 100 ? 5 : 0))));
        $data['isupdated'] = $data['isupdated'] == '' ? 1 : ($data['isupdated'] + 1);
        $input = array(
            'length' => $data['length'],
            'physical_progress' => $pp,
            'pp_status' => $pp_status,
            'progress_remarks' => 'updated manually on ' . date('d/m/Y'),
            'financial_progress' => $data['fp'],
            'bill_paid' => $data['bp'],
            'bill_due' => $data['bd'],
            'isupdated' => $data['isupdated']
        );
        $this->db->where('id', $data['id']);
        $this->db->update('ridf', $input);
        $this->db->select('wo_start_date');
        $this->db->where('id', $data['id']);
        $query = $this->db->get('ridf');
        $wo_start_date = $query->row()->wo_start_date;
        $input = array(
            'created' => date('Y-m-d'),
            'ridf_id' => $data['id'],
            'wo_start_date' => $wo_start_date,
            'physical_progress' => $pp,
            'pp_status' => $pp_status,
            'progress_remarks' => 'updated manually on ' . date('d/m/Y')
        );
        $this->db->insert('ridf_progress', $input);
        return true;
    }

    function get_rpt_updated_status($district_id = 0, $block_id = 0, $status = 0) {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and s.district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and s.district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and s.block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and s.block_id in (' . $session['block_id'] . ')' : '');
        $where .= $this->filter_with_agency();
        $where .= $status > 0 ? ' and s.isupdated > 0' : 'and s.isupdated=0';
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length,s.approved_length, wo.awarded_cost, s.road_type, s.work_type, '
                . 's.wo_start_date, s.pp_status, s.physical_progress, s.financial_progress, s.bill_paid, s.bill_due, isupdated '
                . 'from ridf s join division d on s.district_id=d.id '
                . 'join division b on s.block_id=b.id join ridf_wo wo on wo.ridf_id=s.id '
                . 'where s.survey_status=6 and s.isactive=1 ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_approval_progress($ac_id = 0) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $ac_id > 0 ? (strlen($where) > 0 ? ' and' : 'WHERE ') . 'ac_id=' . $ac_id : '';
        $where .= $this->filter_with_agency();
        $sql = 'SELECT d.name as district, s.agency, count(s.id) as total_work, sum(s.length) as total_length, '
                . 'round(sum(s.cost)/100000,2) as total_amount, sum(if(s.isactive=-1, 1, 0)) as not_implemented, '
                . 'sum(if(s.survey_status=0 and s.isactive=1, 1, 0)) as yet_to_start, '
                . 'sum(if(s.survey_status=1 and s.isactive=1, 1, 0)) as on_going_survey, '
                . 'sum(if(s.survey_status=2 and s.isactive=1, 1, 0)) as survey_completed, '
                . 'sum(if(s.survey_status=3 and s.isactive=1, 1, 0)) as dm_level, '
                . 'sum(if(s.survey_status=4 and s.isactive=1, 1, 0)) as se_level, '
                . 'sum(if(s.survey_status=5 and s.isactive=1, 1, 0)) as state_level, '
                . 'sum(if(s.survey_status=6 and s.isactive=1, 1, 0)) as approved, '
                . 'sum(if(s.survey_status=6 and s.isactive=1, s.length, 0)) as approved_length, '
                . 'round(sum(if(s.survey_status=6 and s.isactive=1, s.cost, 0))/100000,2) as approved_amount '
                . 'FROM ridf s JOIN division d ON s.district_id=d.id ' . $where . ' '
                . 'GROUP BY d.name, s.agency ORDER BY d.name, s.agency';
        $query = $this->db->query($sql);
        return $query->result();
    }

}
