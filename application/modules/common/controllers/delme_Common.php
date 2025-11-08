<?php

/**
 * P&RD Development
 *
 * It's controlling the login activity
 *
 * @package		ard
 * @author		EMDEE
 * @copyright	Copyright (c) 2020, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com) & Imtiaz Kabir
 * @since		Version 1.0,[Created: 01-Apr-2020]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct(); //csrf_check();
        $this->load->model('common_model');
        $this->data = array();
    }

    function get_division_list() {
        $level_id = $this->input->get('level_id');
        $parent_id = $this->input->get('parent_id');
        echo json_encode($this->common_model->get_division_list($level_id, $parent_id));
    }

    function get_road_list() {
        $district_id = $this->input->get('district_id');
        $block_id = $this->input->get('block_id');
        $panchayat_id = $this->input->get('panchayat_id');
        echo json_encode($this->common_model->get_road_list($district_id, $block_id, $panchayat_id));
    }

    function get_designation_list() {
        echo json_encode($this->common_model->get_designation_list());
    }

    function insert_user_district_level() {
//        echo $this->encryption->encrypt('password');
//        echo $this->encryption->decrypt('54c47e0c2649bc034e35f8427ae97698d99d9be506f1123605a91e152b8aba54f43d034a6509d03ff336d39316206b764f22d425e24dc4f1745093836a2253221eY07CIBDdLyN8cWKgfldnYUo9lEjpGGq/RKHwgTHfY=');
//        exit;
        $this->db->distinct();
        $this->db->where(array(
            'level_id' => 2,
            'isactive' => 1
        ));
        $this->db->order_by('name');
//        $this->db->limit(1);
        $query = $this->db->get(DIVISION);
        $result = $query->result();
        foreach ($result as $r) {
            $sql = 'INSERT INTO um_user (created, name, level_id, division_id, role_id, designation_id, parent_id) '
                    . 'VALUES ("2021-01-11", "' . ucwords(strtolower($r->name)) . ' Admin", 2, ' . $r->id . ', 2, 1, 2)';
            $this->db->query($sql);
            $user_id = $this->db->insert_id();
            $sql = 'INSERT INTO um_user_login (created, user_id, username, password) '
                    . 'VALUES ("2021-01-11", ' . $user_id . ', "' . str_replace(' ', '', strtolower($r->name)) . '", "54c47e0c2649bc034e35f8427ae97698d99d9be506f1123605a91e152b8aba54f43d034a6509d03ff336d39316206b764f22d425e24dc4f1745093836a2253221eY07CIBDdLyN8cWKgfldnYUo9lEjpGGq/RKHwgTHfY=")';
            $this->db->query($sql);
        }
    }

    function dmlogin() {
        $sql = 'select id, code, name from division where level_id=2 order by code';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $input = array(
                'created' => date('Y-m-d'),
                'role_id' => 12,
                'district_id' => $row->id,
                'block_id' => 0,
                'name' => 'DM ' . $row->name
            );
            $this->db->insert(USER, $input);
            $user_id = $this->db->insert_id();
            $input = array(
                'created' => date('Y-m-d'),
                'user_id' => $user_id,
                'username' => 'dm19' . $row->code . '01',
                'password' => $this->encryption->encrypt(DEFAULT_PWD),
                'isactive' => 1
            );
            $this->db->insert(LOGIN, $input);
        }
    }

    function zplogin() {
        $sql = 'select id, code, name from division where level_id=2 order by code';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $input = array(
                'created' => date('Y-m-d'),
                'role_id' => 13,
                'district_id' => $row->id,
                'block_id' => 0,
                'name' => 'ZP ' . $row->name
            );
            $this->db->insert(USER, $input);
            $user_id = $this->db->insert_id();
            $input = array(
                'created' => date('Y-m-d'),
                'user_id' => $user_id,
                'username' => 'aeo19' . $row->code . '01',
                'password' => $this->encryption->encrypt(DEFAULT_PWD),
                'isactive' => 1
            );
            $this->db->insert(LOGIN, $input);
        }
    }

    function srdalogin() {
        $sql = 'select id, code, name from division where level_id=2 order by code';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $input = array(
                'created' => date('Y-m-d'),
                'role_id' => 15,
                'district_id' => $row->id,
                'block_id' => 0,
                'name' => 'WBSRDA ' . $row->name
            );
            $this->db->insert(USER, $input);
            $user_id = $this->db->insert_id();
            $input = array(
                'created' => date('Y-m-d'),
                'user_id' => $user_id,
                'username' => 'srda19' . $row->code . '01',
                'password' => $this->encryption->encrypt(DEFAULT_PWD),
                'isactive' => 1
            );
            $this->db->insert(LOGIN, $input);
        }
    }

    function bdologin() {
        $sql = 'select id, parent_id, code, name from division where level_id=3 order by code';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $input = array(
                'created' => date('Y-m-d'),
                'role_id' => 14,
                'district_id' => $row->parent_id,
                'block_id' => $row->id,
                'name' => 'BDO ' . $row->name
            );
            $this->db->insert(USER, $input);
            $user_id = $this->db->insert_id();
            $input = array(
                'created' => date('Y-m-d'),
                'user_id' => $user_id,
                'username' => 'bdo19' . $row->code . '01',
                'password' => $this->encryption->encrypt(DEFAULT_PWD),
                'isactive' => 1
            );
            $this->db->insert(LOGIN, $input);
        }
    }

    function districtid() {
        $sql = 'SELECT DISTINCT d.id, s.district FROM srr_project s JOIN division d on lower(REPLACE(d.name, " ", ""))=lower(REPLACE(s.district, " ", "")) where d.level_id=2 and s.district_id=0';
        $query = $this->db->query($sql);
        $result = $query->result();
        $i = 1;
        foreach ($result as $row) {
            $sql = 'UPDATE srr_project SET district_id=' . $row->id . ' WHERE lower(REPLACE(district, " ", ""))="' . strtolower(str_replace(' ', '', $row->district)) . '"';
            $this->db->query($sql);
            $i++;
        }
        echo 'total: ' . $i;
    }

    function blockid() {
        $sql = 'SELECT DISTINCT district_id as id FROM srr_project WHERE district_id > 0';
        $query = $this->db->query($sql);
        $district = $query->result();
        foreach ($district as $d) {
            $sql = 'SELECT DISTINCT d.id, s.block FROM srr_project s JOIN division d on lower(REPLACE(d.name, " ", ""))=lower(REPLACE(s.block, " ", "")) where d.level_id=3 and s.block_id=0 and s.district_id=d.parent_id and s.district_id=' . $d->id;
            $query = $this->db->query($sql);
            $result = $query->result();
            $i = 1;
            foreach ($result as $row) {
                $sql = 'UPDATE srr_project SET block_id=' . $row->id . ' WHERE lower(REPLACE(block, " ", ""))="' . strtolower(str_replace(' ', '', $row->block)) . '" and district_id=' . $d->id;
                $this->db->query($sql);
                $i++;
            }
        }
        echo 'total: ' . $i;
    }

    function gpid() {
        $sql = 'SELECT DISTINCT block_id as id FROM srr_project WHERE block_id > 0';
        $query = $this->db->query($sql);
        $block = $query->result();
        foreach ($block as $b) {
            $sql = 'SELECT DISTINCT d.id, s.gp FROM srr_project s JOIN division d on lower(REPLACE(d.name, " ", ""))=lower(REPLACE(s.gp, " ", "")) where d.level_id=4 and s.gp_id=0 and s.block_id=d.parent_id and s.block_id=' . $b->id;
            $query = $this->db->query($sql);
            $result = $query->result();
            $i = 1;
            foreach ($result as $row) {
                $sql = 'UPDATE srr_project SET gp_id=' . $row->id . ' WHERE lower(REPLACE(gp, " ", ""))="' . strtolower(str_replace(' ', '', $row->gp)) . '" and block_id=' . $b->id;
                $this->db->query($sql);
                $i++;
            }
        }
        echo 'total: ' . $i;
    }

    function schemeid() {
        $sql = 'select id from srrp where ref_no is null';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            var_dump($row);
        }
    }

    function approved_scheme() {
        $this->db->select('max(scheme_no) as scheme_no');
        $query = $this->db->get(SRRP);
        $scheme_no = $query->row()->scheme_no;
        $this->db->select('s.id, s.agency, d.name as district');
        $this->db->where('survey_status', 6);
        $this->db->where('ref_no is null');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $query = $this->db->get(SRRP . ' s');
        $result = $query->result();
        //echo $this->db->last_query(); exit;
        foreach ($result as $row) {
            $scheme_no++;
            $ref_no = str_pad($scheme_no, 5, '0', STR_PAD_LEFT) . '/' . $row->agency . '/' . $row->district . '/RASTASHREE/2023';
            $input = array(
                'scheme_no' => $scheme_no,
                'ref_no' => $ref_no
            );
            $this->db->where('id', $row->id);
            $this->db->update(SRRP, $input);
        }
    }

    function srrp_report() {
        $sql = 'TRUNCATE table srrp_report';
        $this->db->query($sql);
        $sql = 'SELECT DISTINCT s.district_id, d.name as district, s.block_id, b.name as block, s.agency, s.road_type, '
                . 's.work_type FROM srrp s JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id '
                . 'WHERE s.ref_no is not null AND s.survey_status=6 AND s.isactive=1 ORDER BY d.name, s.agency, s.road_type, s.work_type';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $input = array(
                'district_id' => $row->district_id,
                'district' => $row->district,
                'block_id' => $row->block_id,
                'block' => $row->block,
                'agency' => $row->agency,
                'road_type' => $row->road_type,
                'work_type' => $row->work_type
            );
            $this->db->insert('srrp_report', $input);
        }
    }

    function update_srrp_report() {
        $sql = 'SELECT id, district_id, block_id, agency, road_type, work_type FROM srrp_report ORDER BY id';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $this->_update_scheme_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_approved_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_tender_invited_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_tender_te_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_tender_fe_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_tender_tm_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_benefitted_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_wo_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
        }
    }

    function update_srrp_report_daily() {
        $sql = 'SELECT id, district_id, block_id, agency, road_type, work_type FROM srrp_report ORDER BY id';
        $query = $this->db->query($sql);
        $result = $query->result();
        foreach ($result as $row) {
            $this->_update_progress_25_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_progress_50_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_progress_75_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_progress_99_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
            $this->_update_progress_100_info($row->id, $row->district_id, $row->block_id, $row->agency, $row->road_type, $row->work_type);
        }
    }

    function _update_scheme_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp '
                . 'where isactive=1 and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'scheme' => $row->cnt,
            'length' => $row->length,
            'amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_approved_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp '
                . 'where survey_status=6 and isactive=1 and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'approved_scheme' => $row->cnt,
            'approved_length' => $row->length,
            'approved_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_tender_invited_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp '
                . 'where survey_status=6 and isactive=1 and tender_number is not null '
                . 'and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'tender_invited' => $row->cnt,
            'ti_length' => $row->length,
            'ti_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_tender_te_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost '
                . 'from srrp s join tender_log tl on tl.srrp_id=s.id '
                . 'where s.survey_status=6 and s.isactive=1 and s.tender_number is not null and tl.evaluation_status=1 '
                . 'and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'te_completed' => $row->cnt,
            'te_length' => $row->length,
            'te_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_tender_fe_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost '
                . 'from srrp s join tender_log tl on tl.srrp_id=s.id where s.survey_status=6 and s.isactive=1 '
                . 'and s.tender_number is not null and tl.bid_opening_status=1 '
                . 'and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'fe_completed' => $row->cnt,
            'fe_length' => $row->length,
            'fe_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_tender_tm_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(distinct s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost '
                . 'from srrp s join tender_log tl on tl.srrp_id=s.id where s.survey_status=6 and s.isactive=1 '
                . 'and s.tender_number is not null and tl.bid_matured_status=1 '
                . 'and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'tender_matured' => $row->cnt,
            'tm_length' => $row->length,
            'tm_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_benefitted_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(distinct block_id) as block, count(distinct gp_id) as gp, ifnull(SUM(no_of_village),0) as village, '
                . 'ifnull(SUM(total_households),0) as households, ifnull(SUM(total_population),0) as population '
                . 'from srrp where survey_status=6 and isactive=1 and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'block_covered' => $row->block,
            'gp_covered' => $row->gp,
            'village_covered' => $row->village,
            'household_benefitted' => $row->households,
            'population_benefitted' => $row->population
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_wo_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(s.length),0) as length, ifnull(sum(wo.awarded_cost),0) as cost '
                . 'from srrp s join srrp_wo wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.wo_status=2 '
                . 'and district_id=' . $district_id . ' and block_id=' . $block_id .
                ' and agency="' . $agency . '" and road_type="' . $road_type . '" and work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'wo_issued' => $row->cnt,
            'wo_length' => $row->length,
            'wo_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_progress_25_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, round(sum((s.length*s.physical_progress)/100), 2) as length, sum(wo.awarded_cost) as cost '
                . 'from srrp s join srrp_wo as wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.pp_status=1 '
                . 'and s.district_id=' . $district_id . ' and s.block_id=' . $block_id .
                ' and s.agency="' . $agency . '" and s.road_type="' . $road_type . '" and s.work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_25' => $row->cnt,
            'progress_25_length' => $row->length,
            'progress_25_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_progress_50_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, round(sum((s.length*s.physical_progress)/100), 2) as length, sum(wo.awarded_cost) as cost '
                . 'from srrp s join srrp_wo as wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.pp_status=2 '
                . 'and s.district_id=' . $district_id . ' and s.block_id=' . $block_id .
                ' and s.agency="' . $agency . '" and s.road_type="' . $road_type . '" and s.work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_50' => $row->cnt,
            'progress_50_length' => $row->length,
            'progress_50_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_progress_75_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, round(sum((s.length*s.physical_progress)/100), 2) as length, sum(wo.awarded_cost) as cost '
                . 'from srrp s join srrp_wo as wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.pp_status=3 '
                . 'and s.district_id=' . $district_id . ' and s.block_id=' . $block_id .
                ' and s.agency="' . $agency . '" and s.road_type="' . $road_type . '" and s.work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_75' => $row->cnt,
            'progress_75_length' => $row->length,
            'progress_75_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_progress_99_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, round(sum((s.length*s.physical_progress)/100), 2) as length, sum(wo.awarded_cost) as cost '
                . 'from srrp s join srrp_wo as wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.pp_status=4 '
                . 'and s.district_id=' . $district_id . ' and s.block_id=' . $block_id .
                ' and s.agency="' . $agency . '" and s.road_type="' . $road_type . '" and s.work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_99' => $row->cnt,
            'progress_99_length' => $row->length,
            'progress_99_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

    function _update_progress_100_info($id, $district_id, $block_id, $agency, $road_type, $work_type) {
        $sql = 'select count(s.id) as cnt, round(sum((s.length*s.physical_progress)/100), 2) as length, sum(wo.awarded_cost) as cost '
                . 'from srrp s join srrp_wo as wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.pp_status=5 '
                . 'and s.district_id=' . $district_id . ' and s.block_id=' . $block_id .
                ' and s.agency="' . $agency . '" and s.road_type="' . $road_type . '" and s.work_type="' . $work_type . '"';
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_100' => $row->cnt,
            'progress_100_length' => $row->length,
            'progress_100_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report', $input);
    }

################################################################################

    function srrp_report_consolidated() {
        $this->db->select('id, district_id, agency');
        $query = $this->db->get('srrp_report_consolidated');
        $result = $query->result();
        foreach ($result as $row) {
            $this->update_scheme_info($row->id, $row->district_id, $row->agency);
            $this->update_approved_info($row->id, $row->district_id, $row->agency);
            $this->update_tender_invited_info($row->id, $row->district_id, $row->agency);
            $this->update_tender_te_info($row->id, $row->district_id, $row->agency);
            $this->update_tender_fe_info($row->id, $row->district_id, $row->agency);
            $this->update_tender_tm_info($row->id, $row->district_id, $row->agency);
            $this->update_benefitted_info($row->id, $row->district_id, $row->agency);
            $this->update_wo_info($row->id, $row->district_id, $row->agency);
            $this->update_progress_25_info($row->id, $row->district_id, $row->agency);
            $this->update_progress_50_info($row->id, $row->district_id, $row->agency);
            $this->update_progress_75_info($row->id, $row->district_id, $row->agency);
            $this->update_progress_99_info($row->id, $row->district_id, $row->agency);
            $this->update_progress_100_info($row->id, $row->district_id, $row->agency);
        }
    }

    function update_scheme_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp where isactive=1 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'scheme' => $row->cnt,
            'length' => $row->length,
            'amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_approved_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp where survey_status=6 and isactive=1 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'approved_scheme' => $row->cnt,
            'approved_length' => $row->length,
            'approved_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_tender_invited_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp where survey_status=6 and isactive=1 and tender_number is not null and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'tender_invited' => $row->cnt,
            'ti_length' => $row->length,
            'ti_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_tender_te_info($id, $district_id, $agency) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp s join tender_log tl on tl.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.tender_number is not null and tl.evaluation_status=1 and s.agency="' . $agency . '" and s.district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'te_completed' => $row->cnt,
            'te_length' => $row->length,
            'te_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_tender_fe_info($id, $district_id, $agency) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp s join tender_log tl on tl.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.tender_number is not null and tl.bid_opening_status=1 and s.agency="' . $agency . '" and s.district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'fe_completed' => $row->cnt,
            'fe_length' => $row->length,
            'fe_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_tender_tm_info($id, $district_id, $agency) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(length),0) as length, ifnull(sum(cost),0) as cost from srrp s join tender_log tl on tl.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.tender_number is not null and tl.bid_matured_status=1 and s.agency="' . $agency . '" and s.district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'tender_matured' => $row->cnt,
            'tm_length' => $row->length,
            'tm_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_benefitted_info($id, $district_id, $agency) {
        $sql = 'select count(distinct block_id) as block, count(distinct gp_id) as gp, ifnull(SUM(no_of_village),0) as village, ifnull(SUM(total_households),0) as households, ifnull(SUM(total_population),0) as population from srrp where survey_status=6 and isactive=1 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'block_covered' => $row->block,
            'gp_covered' => $row->gp,
            'village_covered' => $row->village,
            'household_benefitted' => $row->households,
            'population_benefitted' => $row->population
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_wo_info($id, $district_id, $agency) {
        $sql = 'select count(s.id) as cnt, ifnull(sum(s.length),0) as length, ifnull(sum(wo.awarded_cost),0) as cost from srrp s join srrp_wo wo on wo.srrp_id=s.id where s.survey_status=6 and s.isactive=1 and s.wo_status=2 and s.agency="' . $agency . '" and s.district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'wo_issued' => $row->cnt,
            'wo_length' => $row->length,
            'wo_amount' => $row->cost
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_progress_25_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt from srrp where survey_status=6 and isactive=1 and pp_status=1 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_25' => $row->cnt
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_progress_50_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt from srrp where survey_status=6 and isactive=1 and pp_status=2 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_50' => $row->cnt
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_progress_75_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt from srrp where survey_status=6 and isactive=1 and pp_status=3 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_75' => $row->cnt
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_progress_99_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt from srrp where survey_status=6 and isactive=1 and pp_status=4 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_99' => $row->cnt
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function update_progress_100_info($id, $district_id, $agency) {
        $sql = 'select count(id) as cnt from srrp where survey_status=6 and isactive=1 and pp_status=5 and agency="' . $agency . '" and district_id=' . $district_id;
        $query = $this->db->query($sql);
        $row = $query->row();
        $input = array(
            'progress_100' => $row->cnt
        );
        $this->db->where('id', $id);
        $this->db->update('srrp_report_consolidated', $input);
    }

    function replace_sf_duplicate_refno() {
        $sql = 'SELECT SUBSTRING_INDEX(ref_no, "/", 1) as random, COUNT(SUBSTRING_INDEX(ref_no, "/", 1)) as cnt FROM  sf 
        group by random HAVING COUNT(SUBSTRING_INDEX(ref_no, "/", 1)) > 1';
        $query = $this->db->query($sql);
        $result = $query->result();
        if($query->num_rows() > 0) {
            foreach($result as $row) {
                $sql = 'select s.id, s.district_id, d.name, s.agency from sf s join division d on s.district_id=d.id where ref_no like "' . $row->random . '/%"';
                $query = $this->db->query($sql);
                $subresult = $query->result();
                foreach($subresult as $subrow) {
                    $id = $subrow->id;
                    $ref_no = str_pad($subrow->district_id . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $subrow->agency . '/' . $subrow->name . '/STATEFUND/2023';
                    $input = array(
                        'ref_no' => $ref_no
                    );
                    $this->db->where('id', $id);
                    $this->db->update(SF, $input);
                }
            }
        }
    }
}

?>
