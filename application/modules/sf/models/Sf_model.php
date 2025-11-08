<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SF_Model extends CI_Model {

    function get_dashboard_count() {
        $session = $this->common->get_session();
        $this->db->select('sum(approved_scheme) as approved_scheme, sum(approved_length) as length, sum(approved_amount) as sanctioned_amount, sum(tender_invited) as tender_invited,  sum(tender_matured) as tender_matured, sum(tm_length) as lender_length, sum(tm_amount) as tender_amount, sum(wo_issued) as wo_no, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,(sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,(sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length, (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,sum(progress_100) as completed, 
        sum(progress_100_length) as completed_length, 
        sum(progress_100_amount) as completed_amount');
        if ($session['role_id'] == 12) {
            $this->db->where('district_id = ' . $session['district_id']);
        }
        $query = $this->db->get(SF_REPORT . ' s');
        // echo $this->db->last_query();exit;
        return $query->row();
    }

    function get_tender_and_wo_count() {
        $session = $this->common->get_session();
        $by_district = $session['role_id'] == 12 ? 'AND district_id = ' . $session['district_id'] : '';
        $sql = "SELECT COUNT(id) as tender_matured, SUM(length) as tender_matured_length, SUM(cost) as tender_amount FROM sf WHERE isactive = 1 AND tender_status = 2 " . $by_district;
        $query = $this->db->query($sql);
        $tender_matured_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_order, SUM(s.length) as work_order_length, SUM(wo.awarded_cost) as sanctioned_amount FROM sf s JOIN sf_wo wo ON wo.sf_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 " . $by_district;
        $query = $this->db->query($sql);
        $work_order_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_progress, SUM(s.length) as work_progress_length, SUM(wo.awarded_cost) as sanctioned_amount FROM sf s JOIN sf_wo wo ON wo.sf_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 AND s.pp_status = 5 " . $by_district;
        $query = $this->db->query($sql);
        $work_order_progress_count = $query->row();
        return array(
            'tender_matured_count' => $tender_matured_count,
            'work_order_count' => $work_order_count,
            'work_order_progress_count' => $work_order_progress_count
        );
    }

    function get_survey_list($district_id, $status = 0) {
        $session = $this->common->get_session();
        $this->db->select('s.id, s.tag, d.name as district, b.name as block, gp.name as gp, s.village, s.ref_no, s.name, s.agency, s.work_type, s.road_type, s.survey_status, s.isactive');
        $this->db->where(
                array(
                    's.district_id' => $district_id,
                    's.isactive' => 1
                )
        );
        if ($session['role_id'] < 4) {
            switch ($status) {
                case 0:
                    $this->db->where('s.survey_status < 6');
                    $this->db->where('s.isactive', 1);
                    break;
                case 1:
                    $this->db->where('s.survey_status', 6);
                    $this->db->where('s.isactive', 1);
                    break;
                case 2:
                    $this->db->where('s.survey_status', 6);
                    $this->db->where('s.isactive', -10);
                    break;
                default:
                    break;
            }
        } else {
            $this->db->where('s.survey_status < 6');
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id', 'left');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id', 'left');
        $this->db->order_by('b.name');
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function get_scheme_info($id) {
        $this->db->where(array('id' => $id));
        $query = $this->db->get(SF);
        return $query->row();
    }

    function get_survey_pending_list($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.road_type, s.work_type, s.agency, s.survey_status as status, s.cost');
        $this->db->where(
                array(
                    's.district_id' => $district_id,
                    's.gp_id>' => 0,
                    's.survey_status<' => 3,
                    's.isactive' => 1
                )
        );
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
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
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function get_project_info($id) {
        $this->db->select('d.name as district, b.name as block, gp.name as gp, s.name, s.village, s.agency, s.length, s.road_type, s.work_type, s.survey_status as status');
        $this->db->where('s.id', $id);
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get(SF . ' s');
        return $query->row();
    }

    function save($data) {
        // var_dump($data);exit;
        $session = $this->common->get_session();
        $this->db->trans_start();
        $id = $data['id'];
        $input = array(
            'tag' => $data['tag'],
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'gp_id' => isset($data['gp_id']) ? implode(',', $data['gp_id']) : 0,
            'village' => $data['village'],
            'agency' => $data['agency'],
            'length' => $data['length'],
            'proposed_length' => $data['length'],
            'approved_length' => $data['length'],
            'work_type' => $data['work_type'],
            'road_type' => $data['road_type']
        );
        if ($session['role_id'] < 4) {
            $input['name'] = $data['name'];
            $input['length'] = $data['length'];
            $input['proposed_length'] = $data['length'];
            $input['approved_length'] = $data['length'];
            if ($data['isapproved'] > 0) {
                $input['cost'] = $data['cost'];
                $input['admin_approval_no'] = $data['admin_no'];
                $input['admin_approval_date'] = $data['admin_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))) : null;
            }
        }
        $input['survey_status'] = $data['isapproved'] > 0 ? 6 : 0;
        $flag = true;
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(SF, $input);
            $flag = false;
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(SF, $input);
            $id = $this->db->insert_id();
        }
        if ($flag) {
            if ($data['isapproved'] > 0) {
                $this->db->select('name');
                $this->db->where('id', $data['district_id']);
                $query = $this->db->get(DIVISION);
                $district_name = $query->row()->name;
                $ref_no = str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $data['agency'] . '/' . $district_name . '/STATEFUND/2023';
                $this->db->where(array(
                    'ref_no' => $ref_no
                ));
                $query = $this->db->get(SF);
                if ($query->num_rows() > 0) {
                    $ref_no = str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $data['agency'] . '/' . $district_name . '/STATEFUND/2023';
                }
            } else {
                $ref_no = 'TMP' . $id . '_' . rand();
            }
            $input = array(
                'ref_no' => $ref_no
            );
            $this->db->where('id', $id);
            $this->db->update(SF, $input);
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
        $this->db->update(SF, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'sf_id' => $data['id'],
            'survey_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['survey_date']))),
            'length' => $data['length'],
            'surveyor_name' => $data['surveyor_name'],
            'surveyor_designation' => $data['surveyor_designation'],
            'surveyor_mobile' => $data['surveyor_mobile'],
            'status' => $data['status']
        );
        $this->db->insert(SF_SURVEY_LOG, $input);
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
        $this->db->update(SF, $input);
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
        foreach ($data as $k => $v) {
            $this->db->where('id', $k);
            $this->db->update(SF, $input);
        }
        return true;
    }

    function forwarded($role_id, $lotno, $path) {
        switch ($role_id) {
            case 2:
            case 3:
                $input = array(
                    'sa_approval_date' => date('Y-m-d'),
                    'sa_lot_doc' => $path,
                    'survey_status' => 6
                );
                $this->db->where('sa_lot_no', $lotno);
                break;
            case 7:
                $input = array(
                    'se_approval_date' => date('Y-m-d'),
                    'se_lot_no' => $lotno,
                    'se_lot_doc' => $path,
                    'survey_status' => 5
                );
                $this->db->where('se_lot_no', $lotno);
                break;
            case 12:
                $input = array(
                    'dm_approval_date' => date('Y-m-d'),
                    'dm_lot_doc' => $path,
                    'survey_status' => 4
                );
                $this->db->where('dm_lot_no', $lotno);
                break;
            case 13:
            case 14:
                $input = array(
                    'survey_lot_doc' => $path,
                    'survey_status' => 3
                );
                $this->db->where('survey_lot_no', $lotno);
                break;
            case 15:
            case 16:
            case 17:
                $input = array(
                    'survey_lot_doc' => $path,
                    'survey_status' => 4
                );
                $this->db->where('survey_lot_no', $lotno);
                break;
            default:
                break;
        }
        $this->db->update(SF, $input);
        return true;
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
            $this->db->where('block_id', $session['block_id']);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('block_id', explode(',', $session['block_id']));
        }
        $this->db->where('admin_approval_no is null');
        $query = $this->db->get(SF);
        return $query->result();
    }

    function get_lot_list($district_id, $lotno) {
        $session = $this->common->get_session();
        $role_id = $session['role_id'];
        $lot_ref = ($role_id == 2 || $role_id == 3) ? 's.sa_lot_no' : ($role_id == 7 ? 's.se_lot_no' : ($role_id == 12 ? 's.dm_lot_no' : 's.survey_lot_no'));
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, ' . $lot_ref . ' as lotno, s.name, s.length, s.road_type, s.work_type, s.agency, s.survey_status as status, s.cost');
        $this->db->where(
                array(
                    's.district_id' => $district_id,
                    's.survey_status>' => 1,
                    's.isactive' => 1
                )
        );
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
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function get_approval_list($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.proposed_length, s.length, s.road_type, s.work_type, s.agency, s.survey_status as status, s.cost');
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
        $query = $this->db->get(SF . ' s');
//        echo $this->db->last_query();
//        exit;
        return $query->result();
    }

    function return_to_prev($data) {
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
                    $sql = 'SELECT agency FROM sf WHERE id=' . $d;
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
                            'survey_status' => 2
                        );
                    }
                    break;
                case 12:
                    $input = array(
                        'survey_lot_no' => null,
                        'survey_lot_doc' => null,
                        'survey_status' => 2
                    );
                    break;
                default:
                    break;
            }
            $this->db->where('id', $d);
            $this->db->update(SF, $input);
            //echo $this->db->last_query(); exit;
        }
        return true;
    }

    function remove_survey_list($id) {
        $input = array(
            'isactive' => -2
        );
        $this->db->where('id', $id);
        $this->db->update(SF, $input);
    }

    function get_scheme_not_implemented($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('nil.sf_id as id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.road_type, s.work_type, s.agency, s.proposed_length, nil.status_id as status, nil.remarks, nil.created');
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
        $this->db->join('(SELECT * FROM sf_not_implemented tnil JOIN (SELECT sf_id as c_sf_id, MAX(id) as max_id FROM sf_not_implemented GROUP BY sf_id) mtnil ON tnil.id = mtnil.max_id)' . ' nil', 's.id=nil.sf_id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->order_by('s.id', 'DESC');
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function delete_not_traceable($id) {

        $input['isactive'] = -2;
        $this->db->where('id', $id);
        $this->db->update(SF, $input);
        return TRUE;
    }

    function get_approved_list($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, '
                . 's.road_type, s.work_type, s.agency, s.survey_status as status, s.cost, s.ref_no, '
                . 's.admin_approval_date, s.admin_approval_no, s.admin_approval_doc as lot_doc');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.survey_status' => 6,
            's.isactive' => 1
                )
        );
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
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
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id', 'left');
        $query = $this->db->get(SF . ' s');
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function print_lot($lotno) {
        $this->db->select('d.name as district, b.name as block, s.agency, gp.name as gp, s.name, s.length, s.work_type, s.road_type, s.cost');
        $this->db->where('(s.survey_lot_no="' . $lotno . '" or s.dm_lot_no="' . $lotno . '" or s.se_lot_no="' . $lotno . '" or s.sa_lot_no="' . $lotno . '")');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get(SF . ' s');
        //echo $this->db->last_query(); exit;
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
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function not_traceable_save($data) {
        $this->db->trans_start();
        $input = array(
            'isactive' => '-1'
        );
        $this->db->where('id', $data['id']);
        $this->db->update(SF, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'sf_id' => $data['id'],
            'status_id' => $data['status'],
            'remarks' => $data['remarks']
        );
        $this->db->insert(NOT_IMPLEMENTED_LOG, $input);
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
    function get_tender_list($district_id, $block_id) {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.agency, s.survey_status as status, s.cost, s.tender_number, s.tender_publication_date, s.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where(
                array(
                    's.district_id' => $district_id,
                    's.survey_status' => 6,
                    's.isactive' => 1
                )
        );
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
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
        $this->db->join('(SELECT * FROM sf_tender_log tl JOIN (SELECT sf_id as c_sf_id, MAX(id) as max_id FROM sf_tender_log GROUP BY sf_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.sf_id', 'left');
        // $this->db->join(TENDER_LOG . ' t', 's.id=t.sf_id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id', 'left');
        $query = $this->db->get(SF . ' s');
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
        $this->db->update(SF, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'sf_id' => $data['id'],
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
        $this->db->insert(SF_TENDER_LOG, $input);

        //manage for now - sujay
        /* $sql = 'UPDATE sf_tender_log SET tender_status=2 WHERE bid_matured_status=1';
          $this->db->query($sql);

          $sql = 'UPDATE sf SET tender_status=2 WHERE id in (SELECT DISTINCT sf_id FROM sf_tender_log WHERE bid_matured_status = 1)';
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

    function get_tender_info($id) {
        $this->db->select('d.name as district, b.name as block, gp.name as gp,  s.village, s.ref_no, s.name, s.agency, s.length, s.tender_number, s.tender_publication_date, s.tender_end_date, s.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where('s.id', $id);
        $this->db->join('(SELECT * FROM sf_tender_log tl JOIN (SELECT sf_id as c_sf_id, MAX(id) as max_id FROM sf_tender_log GROUP BY sf_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.sf_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get(SF . ' s');
        return $query->row();
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
        $this->db->update(SF, $input);
        return TRUE;
    }

    function get_wo_info($id) {
        $this->db->select('wo.id, sr.id as sf_id, sr.name, sr.district_id, wo.wo_no, '
                . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
                . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
                . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
                . 'wo.dlp_submitted, wo.document, wo.assigned_engineer, wo.designation, wo.mobile');
        $this->db->where(array(
            'sr.id' => $id
        ));
        $this->db->join(SF_WO . ' wo', 'wo.sf_id=sr.id and wo.isactive=1', 'left');
        $query = $this->db->get(SF . ' sr');
        return $query->row();
    }

    function get_wo_list($district_id = 0, $block_id = 0) {
        $session = $this->common->get_session();
        $this->db->select('d.name as district, b.name as block, s.name,s.agency, s.tender_number, '
                . 'date_format(s.tender_publication_date, "%d/%m/%Y") as tender_publication_date, date_format(s.tender_end_date, "%d/%m/%Y") as tender_end_date, '
                . 'wo.id, s.id as sf_id, wo.wo_no, date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, '
                . 'date_format(wo.completion_date, "%d/%m/%Y") as completion_date, wo.assigned_engineer, wo.mobile, wo.document');
        $this->db->where(array(
            's.survey_status' => 6,
            's.tender_status' => 2,
            's.isactive' => 1
        ));
        if ($district_id > 0) {
            $this->db->where('district_id', $district_id);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        } else if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(SF_WO . ' wo', 'wo.sf_id=s.id', 'left');
        $this->db->group_by('wo.id, s.id, s.name, d.name, wo.wo_no, wo.wo_date, wo.contractor, wo.completion_date');
        $this->db->order_by('d.name');
        $query = $this->db->get(SF . ' s');
        return $query->result();
    }

    function wo_save($data) {
        $this->db->trans_start();
        $sf_id = isset($data['sf_id']) ? $data['sf_id'] : 0;
        $this->db->select('id, mobile');
        $this->db->where('sf_id', $sf_id);
        $query = $this->db->get(SF_WO);
        $id = $query->num_rows() > 0 ? $query->row()->id : 0;
        if ($sf_id > 0) {
            if (array_key_exists('wo_no', $data)) {
                $input = array(
                    'sf_id' => $sf_id,
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
                $this->db->update(SF_WO, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(SF_WO, $input);
                $id = $this->db->insert_id();
            }
            $this->db->select('user_id');
            $this->db->where('username', $data['mobile']);
            $query = $this->db->get(LOGIN);
            if ($query->num_rows() > 0) {
                $user_id = $query->row()->user_id;
                $sql = 'UPDATE sf SET pe_user_id=' . $user_id . ' WHERE id=' . $sf_id;
                $this->db->query($sql);
            } else {
                $this->db->select('district_id');
                $this->db->where('id', $sf_id);
                $query = $this->db->get(SF);
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
                $sql = 'UPDATE sf SET pe_user_id=' . $user_id . ' WHERE id=' . $sf_id;
                $this->db->query($sql);
            }
            $input = array(
                'work_order' => array_key_exists('wo_no', $data) ? $data['wo_no'] : '',
                'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                'wo_status' => 2
            );
            $this->db->where('id', $sf_id);
            $this->db->update(SF, $input);
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
        $this->db->where('sf_id', $id);
        $this->db->update(SF_WO, $input);
        return TRUE;
    }

    function get_wp_list($user_id, $status) {
        $where = 'WHERE s.survey_status=6 AND s.isactive=1 AND s.pe_user_id=' . $user_id;
        switch ($status) {
            case 0: case 5:
                $where .= ' AND s.pp_status=' . $status;
                break;
            case 1: case 2: case 3: case 4:
                $where .= ' AND s.pp_status > 0 and pp_status < 5';
                break;
            default:
                break;
        }
        $sql = 'SELECT s.id, b.name as block, g.name as gp, s.ref_no, s.name, s.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status '
                . 'FROM sf s JOIN division b ON s.block_id=b.id JOIN division g ON s.gp_id=g.id ' . $where;
        $query = $this->db->query($sql);
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
                . 'FROM sf s JOIN division b ON s.block_id=b.id JOIN division g ON s.gp_id=g.id ' . $where;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function wp_save($data) {
        $progress = $data['wp_progress'];
        $pp_status = $progress < 26 ? 1 : (($progress > 25 && $progress < 51) ? 2 : (($progress > 50 && $progress < 76) ? 3 : (($progress > 75 && $progress < 100) ? 4 : ($progress == 100 ? 5 : 0))));
        $input = array(
            'created' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_date']))),
            'sf_id' => $data['id'],
            'physical_progress' => $progress,
            'progress_remarks' => $data['remarks'],
            'pp_status' => $pp_status
        );
        if (array_key_exists('wp_start_date', $data)) {
            $input['wo_start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_start_date'])));
        }
        $this->db->insert(SF_PROGRESS, $input);
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
        $this->db->update(SF, $input);
        return $id;
    }

################################### QM #########################################

    function get_qm_list($month, $year) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : '';
        if ($session['block_id'] > 0) {
            $where .= ' and s.block_id in ' . explode(',', $session['block_id']);
        }
        switch ($session['role_id']) {
            case 13:
                $where .= ' and s.agency="ZP"';
                break;
            case 14:
                $where .= ' and s.agency="BLOCK"';
                break;
            case 15:
                $where .= ' and s.agency="SRDA"';
                break;
            case 16:
                $where .= ' and s.agency="MBL"';
                break;
            case 17:
                $where .= ' and s.agency="AGRO"';
                break;
            default:
                break;
        }
        $sql = 'SELECT CONCAT(u.id, "-", qm.month, "-", qm.year) as id, u.name as sqm, u.mobile,
            GROUP_CONCAT(DISTINCT d.name) as district, COUNT(sf_id) as total
            FROM sf_qm as qm JOIN division d ON qm.district_id=d.id JOIN sf s ON s.id=qm.sf_id JOIN user u ON u.id=qm.sqm_id
            WHERE qm.isactive=1 and qm.month=' . $month . ' and qm.year=' . $year . $where . ' GROUP BY u.name, u.mobile ORDER BY u.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_qm_caption($month, $year, $sqm_id) {
        $sql = 'SELECT distinct u.name as sqm, m.name as month, qm.year FROM sf_qm as qm JOIN user u ON u.id=qm.sqm_id JOIN month m ON qm.month=m.id WHERE qm.isactive=1 AND qm.month=' . $month . ' AND qm.year=' . $year . ' AND qm.sqm_id=' . $sqm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_qm_details($month, $year, $sqm_id) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : ' ';
        if ($session['block_id'] > 0) {
            $where .= ' and s.block_id in ' . explode(',', $session['block_id']);
        }
        switch ($session['role_id']) {
            case 13:
                $where .= ' and s.agency="ZP"';
                break;
            case 14:
                $where .= ' and s.agency="BLOCK"';
                break;
            case 15:
                $where .= ' and s.agency="SRDA"';
                break;
            case 16:
                $where .= ' and s.agency="MBL"';
                break;
            case 17:
                $where .= ' and s.agency="AGRO"';
                break;
            default:
                break;
        }
        $sql = 'SELECT d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, s.image1, s.image2, s.image3
            FROM sf_qm as qm JOIN sf s ON s.id=qm.sf_id JOIN sf_wo wo ON wo.sf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
            WHERE qm.isactive=1 AND qm.month=' . $month . ' AND qm.year=' . $year . ' AND qm.sqm_id=' . $sqm_id . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_sqm_list() {
        $session = $this->common->get_session();
        $this->db->select('id, name');
        $this->db->where(array(
            'role_id' => 5,
            'isactive' => 1
        ));
        if ($session['role_id'] == 5) {
            $this->db->where('id', $session['user_id']);
        }
        $query = $this->db->get(USER);
        return $query->result();
    }

    function get_scheme_list($district_id, $sqm_id = 0, $month = 0, $year = 0) {
        $sql = 'SELECT s.id, d.name as district, b.name as block, s.name, s.agency, s.length, s.wo_start_date, s.physical_progress, qm.inspection_date, qm.overall_grade, ifnull(qm.atr_action, 2) as atr_action, total.cnt, ifnull(q.id, 0) as selected
            FROM sf s JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
            LEFT JOIN sf_qm q ON s.id=q.sf_id and q.sqm_id=' . $sqm_id . ' and q.month=' . $month . ' and q.year=' . $year . ' and q.isactive=1
            LEFT JOIN (SELECT sq.sf_id, sqi.inspection_date, sqi.overall_grade, sqi.atr_action FROM sf_qm sq JOIN sf_qm_inspection sqi ON sqi.qm_id=sq.id WHERE sqi.inspection_date=(SELECT max(inspection_date) from sf_qm_inspection as sqi1 join sf_qm sq1 on sqi1.qm_id=sq1.id WHERE sq1.sf_id=sq.sf_id)) as qm ON qm.sf_id=s.id
            LEFT JOIN (SELECT DISTINCT sf_id, count(sf_id) as cnt FROM sf_qm GROUP BY sf_id) as total ON total.sf_id=s.id
            WHERE s.survey_status=6 AND s.isactive = 1 AND s.district_id=' . $district_id . ' ORDER BY d.name ASC, b.name ASC, agency ASC, s.physical_progress DESC';
        $query = $this->db->query($sql);
        echo $this->db->last_query();
        exit;
        return $query->result();
    }

    function get_inspection_list($sqm_id, $month, $year) {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, ur.name as sqm, ur.mobile as sqm_mobile, u.mobile, qm.status
            FROM sf_qm as qm JOIN sf s ON s.id=qm.sf_id JOIN sf_wo wo ON wo.sf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status<2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_report_list($sqm_id, $month, $year) {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, u.mobile, ur.name as sqm, ur.mobile as sqm_mobile, qm.status, sqi.inspection_date, sqi.overall_grade, sqi.document
            FROM sf_qm as qm JOIN sf_qm_inspection sqi ON sqi.qm_id=qm.id JOIN sf s ON s.id=qm.sf_id JOIN sf_wo wo ON wo.sf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status=2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_caption($qm_id) {
        $sql = 'SELECT s.name, s.agency FROM sf_qm as qm JOIN sf s ON qm.sf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_selection($qm_id) {
        $sql = 'SELECT s.name, s.agency FROM sf_qm as qm JOIN sf s ON qm.sf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_image_list($id) {
        $this->db->select('i.description, i.image');
        $this->db->where(array(
            'hd.qm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join(SF_QM_INSPECTION_IMAGE . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get(SF_QM_INSPECTION . ' hd');
        return $query->result();
    }

    function qm_save($data) {
        // var_dump($data);exit;
        $this->db->trans_start();
        $input = array(
            'isactive' => -1
        );
        //var_dump($input);exit;
        $this->db->where(array(
            'district_id' => $data['district_id'],
            'sqm_id' => $data['sqm_id'],
            'year' => $data['year'],
            'month' => $data['month']
        ));
        //var_dump($input);exit;
        $this->db->update(SF_QM, $input);
        foreach ($data['chk'] as $row) {
            $input = array(
                'district_id' => $data['district_id'],
                'sf_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month'],
                'isactive' => 1
            );
            $this->db->where(array(
                'sf_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month']
            ));
            // var_dump($input);exit;
            $query = $this->db->get(SF_QM);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $query->row()->id);
                $this->db->update(SF_QM, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(SF_QM, $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function qm_save_start($qm_id, $agency) {
        $this->db->trans_start();
        $id = 0;
        $this->db->where('qm_id', $qm_id);
        $query = $this->db->get(SF_QM_INSPECTION);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'qm_id' => $qm_id,
                'agency' => $agency
            );
            $this->db->insert(SF_QM_INSPECTION, $input);
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

    function qm_save_inspection_image($i, $id, $desc) {
        $this->db->trans_start();
        $this->db->where(array(
            'inspection_id' => $id,
            'seq_id' => $i + 1
        ));
        $query = $this->db->get(SF_QM_INSPECTION_IMAGE);

        $image_id = $query->num_rows() > 0 ? $query->row()->id : 0;
        //var_dump($image_id);exit;
        $input = array(
            'inspection_id' => $id,
            'seq_id' => $i + 1,
            'description' => $desc[$i]
        );
        //var_dump($input);exit;
        if ($image_id > 0) {
            $this->db->where('id', $image_id);
            $this->db->update(SF_QM_INSPECTION_IMAGE, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(SF_QM_INSPECTION_IMAGE, $input);
            $image_id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $image_id;
    }

    function get_oqrc_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(SF_OQRC);
        return $query->result();
    }

    function get_qm_oqrc_list($qm_id) {
        $this->db->select('o.id, o.name, r.value,i.inspection_date,i.overall_grade,i.atr_action');
        $this->db->where(array(
            'i.qm_id' => $qm_id,
            'r.isactive' => 1
        ));
        $this->db->join(SF_QM_INSPECTION . ' i', 'i.qm_id=q.id');
        $this->db->join(SF_QM_INSPECTION_REPORT . ' r', 'r.inspection_id=i.id');
        $this->db->join(SF_OQRC . ' o', 'r.oqrc_id=o.id');
        $query = $this->db->get(SF_QM . ' q');
        return $query->result();
    }

    function get_overall_grade($id) {
        $this->db->select('overall_grade');
        $this->db->where('qm_id', $id);
        $query = $this->db->get(SF_QM_INSPECTION);
        //echo $this->db->last_query(); exit;
        return $query->num_rows() > 0 ? $query->row()->overall_grade : '';
    }

    function qm_save_submit($data) {
        $this->db->trans_start();
        $this->db->select('id');
        $this->db->where('qm_id', $data['qm_id']);
        $query = $this->db->get(SF_QM_INSPECTION);
        $inspection_id = $query->row()->id;
        if ($inspection_id > 0) {
            $input = array(
                'isactive' => -1
            );
            $this->db->where('inspection_id', $inspection_id);
            $this->db->update(SF_QM_INSPECTION_REPORT, $input);
            foreach ($data['oqrc'] as $row) {
                $arr = explode('_', $row);
                $this->db->select('id');
                $this->db->where(array(
                    'inspection_id' => $inspection_id,
                    'oqrc_id' => $arr[0]
                ));
                $query = $this->db->get(SF_QM_INSPECTION_REPORT);
                $input = array(
                    'inspection_id' => $inspection_id,
                    'oqrc_id' => $arr[0],
                    'value' => ucwords($arr[1]),
                    'isactive' => 1
                );
                if ($query->num_rows() > 0) {
                    $this->db->where('id', $query->row()->id);
                    $this->db->update(SF_QM_INSPECTION_REPORT, $input);
                } else {
                    $input['created'] = date('Y-m-d');
                    $this->db->insert(SF_QM_INSPECTION_REPORT, $input);
                }
            }
            $input = array(
                'inspection_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['inspection_date']))),
                'overall_grade' => $data['overall_grade'],
                'agency' => $data['agency']
            );
            $this->db->where('id', $inspection_id);
            $this->db->update(SF_QM_INSPECTION, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $inspection_id;
    }

    function get_atr_list($month, $year) {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : '';
        // $sql = 'SELECT ins.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as sqm, u.mobile, qm.status, ins.document, ins.overall_grade, ins.atr
        //     FROM sf_qm as qm JOIN sf s ON s.id=qm.sf_id JOIN sf_wo wo ON wo.sf_id=s.id JOIN sf_qm_inspection ins ON ins.qm_id=qm.id
        //     JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON qm.sqm_id=u.id
        //     WHERE qm.isactive=1 AND qm.status=2 AND ins.overall_grade<>"S" AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';

        $sql = 'SELECT ins.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as sqm, u.mobile, qm.status, ins.document, ins.overall_grade, ins.atr FROM sf_qm as qm JOIN sf s ON s.id=qm.sf_id JOIN sf_wo wo ON wo.sf_id=s.id
        JOIN sf_qm_inspection ins ON ins.qm_id=qm.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON qm.sqm_id=u.id WHERE qm.isactive=1 AND qm.status=2 AND ins.overall_grade<>"S" AND ins.inspection_date=(SELECT max(sqi.inspection_date) FROM sf_qm_inspection as sqi JOIN sf_qm as sq ON sqi.qm_id=sq.id WHERE sq.sf_id=qm.sf_id) AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function qm_save_atr_review($data) {
        // var_dump($data);exit;
        $this->db->trans_start();
        $input = array(
            'atr_action' => $data['atr'],
            'atr_comments' => $data['remarks']
        );
        if ($data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update(SF_QM_INSPECTION, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }

################################################################################
################################ REPORT ########################################

    function get_tag_list() {
        $this->db->distinct();
        $this->db->select('tag');
        $this->db->where('tag is not null');
        $query = $this->db->get(SF);
        return $query->result();
    }

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

    function get_rpt_state_summary($tag = '') {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? ($where == '' ? 'WHERE ' : 'and ') . 'tag="' . $tag . '"' : '';
        $sql = 'select district, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from sf_report ' . $where . ' group by district order by district ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_agency_progress($tag = '') {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'WHERE district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? ($where == '' ? 'WHERE ' : 'and ') . 'tag="' . $tag . '"' : '';
        $sql = 'select district, agency, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from sf_report ' . $where . ' group by district, agency order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_road_type_progress($road_type, $tag = '') {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'AND district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' AND block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? 'and tag="' . $tag . '"' : '';
        $sql = 'select district, agency, road_type, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from sf_report WHERE road_type="' . $road_type . '" ' . $where . ' group by district, agency, road_type order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_type_progress($work_type, $tag = '') {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'AND district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' AND block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? 'and tag="' . $tag . '"' : '';
        $sql = 'select district, agency, work_type,sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(progress_25) + sum(progress_50) + sum(progress_75) + sum(progress_99)) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(progress_25_amount) + sum(progress_50_amount) + sum(progress_75_amount) + sum(progress_99_amount)) as ongoing_amount,
            sum(progress_100) as completed, sum(progress_100_length) as completed_length, sum(progress_100_amount) as completed_amount
            from sf_report WHERE work_type="' . $work_type . '" ' . $where . ' group by district, agency, work_type order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_ps_work_status($district_id = 0, $block_id = 0, $tag = '') {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? ($where != '' ? 'and ' : '') . 'tag="' . $tag . '"' : '';
        $where = strlen($where) > 0 ? 'where ' . $where : '';
        $sql = 'select district, block, agency, sum(approved_scheme) as approved_scheme, sum(approved_length) as approved_length, sum(approved_amount) as approved_amount,
            sum(tender_invited) as tender_invited, sum(tender_matured) as tender_matured, sum(wo_issued) as wo_issued, sum(wo_length) as wo_length, sum(wo_amount) as wo_amount,
            sum(progress_25) as progress_25, sum(progress_50) as progress_50, sum(progress_75) as progress_75, sum(progress_99) as progress_99,
            (sum(ifnull(progress_25,0)) + sum(ifnull(progress_50,0)) + sum(ifnull(progress_75,0)) + sum(ifnull(progress_99,0))) as ongoing,
            (sum(ifnull(progress_25_length,0)) + sum(ifnull(progress_50_length,0)) + sum(ifnull(progress_75_length,0)) + sum(ifnull(progress_99_length,0))) as ongoing_length,
            (sum(ifnull(progress_25_amount,0)) + sum(ifnull(progress_50_amount,0)) + sum(ifnull(progress_75_amount,0)) + sum(ifnull(progress_99_amount,0))) as ongoing_amount,
            sum(ifnull(progress_100,0)) as completed, sum(ifnull(progress_100_length,0)) as completed_length, sum(ifnull(progress_100_amount,0)) as completed_amount
            from sf_report ' . $where . ' group by district,block, agency order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_progress($district_id = 0, $block_id = 0, $wp_id = 0, $tag = '') {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $this->filter_with_agency();
        $where .= $tag != '' ? 'and tag="' . $tag . '"' : '';
        $where .= $wp_id == 6 ? ' and s.pp_status >=0 and s.pp_status <=5' : ' and s.pp_status =' . $wp_id;
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length, wo.awarded_cost, s.road_type, s.work_type, '
                . 's.wo_start_date, s.pp_status, s.physical_progress,s.image1,s.image2,s.image3, wp.wp_date as last_work_date, wp.date_count as inspection_count from sf s '
                . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join sf_wo wo on wo.sf_id=s.id '
                . 'LEFT JOIN (SELECT sf_id, max(created) As wp_date,wo_start_date,count(created) as date_count FROM sf_progress GROUP BY sf_id) wp ON s.id = wp.sf_id AND s.wo_start_date = wp.wo_start_date '
                . 'where s.survey_status=6 and s.isactive=1 ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_progress_details($sf_id) {
//        $db = $this->load->database('rpt', TRUE);
        $sql = 'SELECT s.name, sp.created, sp.wo_start_date, sp.physical_progress, sp.location1, sp.image1, sp.location2, sp.image2, sp.location3,
        sp.image3, sp.progress_remarks FROM sf_progress sp join sf s on sp.sf_id=s.id WHERE sp.sf_id=' . $sf_id . ' ORDER BY sp.created';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_qm_summary($start_date, $end_date) {
        $sql = 'SELECT d.id, d.name as district, sqm.agency, sqm.overall_grade, COUNT(sqm.overall_grade) as cnt
        FROM sf_qm as qm
        JOIN sf_qm_inspection sqm ON sqm.qm_id=qm.id
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
                . 'from sf s join division d on s.district_id=d.id '
                . 'join division b on s.block_id=b.id join sf_wo wo on wo.sf_id=s.id '
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
        $this->db->update(SF, $input);
        $this->db->select('wo_start_date');
        $this->db->where('id', $data['id']);
        $query = $this->db->get(SF);
        $wo_start_date = $query->row()->wo_start_date;
        $input = array(
            'created' => date('Y-m-d'),
            'sf_id' => $data['id'],
            'wo_start_date' => $wo_start_date,
            'physical_progress' => $pp,
            'pp_status' => $pp_status,
            'progress_remarks' => 'updated manually on ' . date('d/m/Y')
        );
        $this->db->insert(SF_PROGRESS, $input);
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
                . 'from sf s join division d on s.district_id=d.id '
                . 'join division b on s.block_id=b.id join sf_wo wo on wo.sf_id=s.id '
                . 'where s.survey_status=6 and s.isactive=1 ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }
}
