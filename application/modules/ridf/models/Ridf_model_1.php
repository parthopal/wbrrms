<?php

defined('BASEPATH') or exit('No direct script access allowed');

class RIDF_Model extends CI_Model
{

    function get_scheme_list($district_id = null, $category_id = null, $type_id = null)
    {
        $sql = 'SELECT r.id, r.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac, 
                   a.name as agency, r.category_id,sc.subcategory as funding, r.scheme_id, r.length, 
                   r.sanctioned_cost, r.admin_date, r.admin_no, r.road_type_name as work_type, 
                   r.length, r.isactive, r.note, r.survey_status, r.tender_status, r.wo_status 
            FROM ridf as r 
            JOIN division as d ON d.id = r.district_id 
            JOIN division as b ON b.id = r.block_id 
            LEFT JOIN assembly_constituency as ac ON r.ac_id = ac.id 
            JOIN scheme_agency as a ON a.id = r.agency_id 
            JOIN scheme_category as sc ON sc.id = r.category_id 
            WHERE r.isactive = 1 AND a.ridf = 1';

        if (!empty($district_id)) {
            $sql .= ' AND r.district_id = ' . $district_id;
        }
        if (!empty($category_id)) {
            $sql .= ' AND r.category_id = ' . $category_id;
        }
        if (!empty($type_id)) {
            $sql .= ' AND r.work_type_id = ' . $type_id;
        }

        $sql .= ' ORDER BY r.name';

        $query = $this->db->query($sql);

        return $query->result();
    }

    function get_scheme_summary()
    {
        $sql = " SELECT 
            sc.subcategory AS category, 
            COUNT(s.id) AS total, 
            COUNT(CASE WHEN s.tender_status IN (1, 2) THEN 1 END) AS tender, 
            COUNT(CASE WHEN s.wo_status IN (1, 2) THEN 1 END) AS wo
        FROM 
            scheme_category sc
        LEFT JOIN 
            ridf s ON s.category_id = sc.id AND s.isactive = 1
        WHERE 
            sc.isactive = 1 
            AND sc.category = 'ridf'
        GROUP BY sc.subcategory
        ORDER BY sc.id ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_tender_and_wo_count()
    {
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

    function get_ac_list($district_id = 0)
    {
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

    function get_assembly_list()
    {
        $this->db->distinct();
        $this->db->select('a.id, concat(a.no, " ", a.name, " ", IF((a.reserved is null or a.reserved = ""), "", concat("(", a.reserved, ")"))) as name');
        $this->db->where(array(
            'a.isactive' => 1
        ));
        $query = $this->db->get(AC . ' a');
        return $query->result();
    }

    function get_rpt_assembly_list()
    {
        $this->db->distinct();
        $this->db->select('a.id, concat(a.no, " ", a.name, " ", IF((a.reserved is null or a.reserved = ""), "", concat("(", a.reserved, ")"))) as name');
        $this->db->where(array(
            'a.isactive' => 1
        ));
        $this->db->join(RIDF . ' r', 'r.ac_id=a.id');
        $query = $this->db->get(AC . ' a');
        return $query->result();
    }

    function get_scheme_info($id)
    {
        // $this->db->select('*');
        $this->db->where(array('id' => $id));
        $query = $this->db->get('ridf');
        //   echo  $this->db->last_query($query); exit;
        return $query->row();
    }

    function save($data)
    {
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
        $this->db->where('id', $data['type_id']);
        $query = $this->db->get('scheme_type');
        $type_name = $query->row()->name;
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'name' => $data['name'],
            'sanctioned_cost' => $data['sanctioned_cost'],
            'category_id' => $data['category_id'],
            'type_id' => $data['type_id'],
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
            'survey_status' => 6,
        );
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

    // Tender
    function get_tender_list($district_id, $category_id, $type_id)
    {
        $session = $this->common->get_session();
        $this->db->select('s.id,d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.name, s.length, s.agency, s.tender_number, s.tender_publication_date, s.tender_status, s.sanctioned_cost, s.scheme_id, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');

        $this->db->where(array(
            's.isactive' => 1
        ));
        $district_id > 0 ? $this->db->where('s.district_id', $district_id) : '';
        $category_id > 0 ? $this->db->where('s.category_id', $category_id) : '';
        $type_id > 0 ? $this->db->where('s.work_type_id', $type_id) : '';
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
        $this->db->join('(SELECT * FROM ridf_tender_log tl JOIN (SELECT ridf_id as c_ridf_id, MAX(id) as max_id FROM ridf_tender_log GROUP BY ridf_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.ridf_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id', 'left');
        $query = $this->db->get('ridf' . ' s');
        return $query->result();
    }

    function get_tender_info($id)
    {
        $this->db->select('d.name as district, b.name as block, r.name, r.agency, r.length,r.scheme_id, r.tender_number, r.tender_publication_date, r.tender_end_date, r.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where('r.id', $id);
        $this->db->join('(SELECT * FROM ridf_tender_log tl JOIN (SELECT ridf_id as c_ridf_id, MAX(id) as max_id FROM ridf_tender_log GROUP BY ridf_id) mtl ON tl.id = mtl.max_id)' . ' t', 'r.id=t.ridf_id', 'left');
        $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'r.block_id=b.id');
        $query = $this->db->get(RIDF . ' r');
        return $query->row();
    }

    function tender_save($data)
    {
        // var_dump($data);exit;
        $this->db->trans_start();
        $input = array(
            'tender_number' => $data['tender_number'],
            'tender_publication_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['tender_publication_date']))),
        );
        if ($data['tender_status'] == 0) {
            $input['tender_status'] = 1;
        }
        // $input['tender_status'] = $data['tender_status'];


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
            'tender_publication_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['tender_publication_date']))),
        );
        if ($data['tender_status'] == 0) {
            $input['tender_status'] = 1;
        }
        // $input['tender_status'] = $data['tender_status'];

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
        $this->db->insert(RIDF_TENDER_LOG, $input);

        //manage for now - sujay
        // $sql = 'UPDATE ridf_tender_log SET tender_status=3 WHERE bid_matured_status=0';
        // $this->db->query($sql);

        $sql = "UPDATE ridf_tender_log
            SET tender_status = CASE
                WHEN bid_matured_status = 1 THEN 2
                WHEN bid_matured_status = 0 THEN 3
                WHEN evaluation_status = 1 OR bid_opening_status = 1 THEN 1
                ELSE tender_status
            END";
        $this->db->query($sql);

        // $sql = 'UPDATE ridf SET tender_status=2 WHERE id in (SELECT DISTINCT ridf_id FROM ridf_tender_log WHERE bid_matured_status = 1)';
        // $this->db->query($sql);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function get_wo_info($id)
    {
        $this->db->select('wo.id, sr.id as ridf_id, sr.name, sr.district_id, wo.wo_no, '
            . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
            . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
            . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
            . 'wo.dlp_submitted, wo.document');
        $this->db->where(array(
            'sr.id' => $id
        ));
        $this->db->join('ridf_wo' . ' wo', 'wo.ridf_id=sr.id and wo.isactive=1', 'left');
        $query = $this->db->get('ridf' . ' sr');
        return $query->row();
    }

    function get_wo_list($district_id = 0, $category_id = 0, $type_id = 0)
    {
        $session = $this->common->get_session();
        $this->db->select('wo.id, d.name AS district, b.name AS block, ac.name as ac, r.name, r.agency, r.tender_number, DATE_FORMAT(r.tender_publication_date, "%d/%m/%Y") AS tender_publication_date, 
            DATE_FORMAT(r.tender_end_date, "%d/%m/%Y") AS tender_end_date, wo.id, r.id AS ridf_id, wo.wo_no, DATE_FORMAT(wo.wo_date, "%d/%m/%Y") AS wo_date, wo.contractor, 
            DATE_FORMAT(wo.completion_date, "%d/%m/%Y") AS completion_date, wo.mobile, wo.document');
        if ($district_id != 0) {
            $this->db->where('r.district_id', $district_id);
        }
        if ($category_id != 0) {
            $this->db->where('r.category_id', $category_id);
        }
        if ($type_id != 0) {
            $this->db->where('r.work_type_id', $type_id);
        }
        $this->db->where([
            'r.tender_status' => 2,
            'r.isactive' => 1
        ]);

        //$this->db->join(TYPE . ' t', 'r.road_type_id = t.id');
        $this->db->join(DIVISION . ' AS d', 'r.district_id = d.id');
        $this->db->join(DIVISION . ' AS b', 'r.block_id = b.id');
        //$this->db->join(CATEGORY . ' c', 'r.category_id = c.id');
        $this->db->join(RIDF_WO . ' AS wo', 'wo.ridf_id = r.id', 'left');
        $this->db->join(AC . ' ac', 'r.ac_id = ac.id', 'left');
        //$this->db->group_by(['wo.id', 's.id', 's.name', 'd.name', 'wo.wo_no', 'wo.wo_date', 'wo.contractor', 'wo.completion_date']);
        $this->db->order_by('d.name, b.name');
        $query = $this->db->get(RIDF . ' r');
        return $query->result();
    }

    function wo_save($data)
    {
        $this->db->trans_start();
        $ridf_id = isset($data['ridf_id']) ? $data['ridf_id'] : 0;
        $this->db->select('id, mobile');
        $this->db->where('ridf_id', $ridf_id);
        $query = $this->db->get(RIDF_WO);
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
                    'isactive' => 1,
                    'islocked' => 1
                );
                if ($id > 0) {
                    $this->db->where('id', $id);
                    $this->db->update(RIDF_WO, $input);
                } else {
                    $input['created'] = date('Y-m-d');
                    $this->db->insert(RIDF_WO, $input);
                    $id = $this->db->insert_id();
                }
                $input = array(
                    'work_order' => array_key_exists('wo_no', $data) ? $data['wo_no'] : '',
                    'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                    'wo_status' => 2
                );
                $this->db->where('id', $ridf_id);
                $this->db->update('ridf', $input);
            }
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

    function wo_remove($id)
    {
        $input['isactive'] = -1;
        $this->db->where('ridf_id', $id);
        $this->db->update(RIDF_WO, $input);
        $input = array(
            'work_order' => null,
            'wo_date' => null,
            'wo_status' => 0
        );
        $this->db->where('id', $id);
        $this->db->update('ridf', $input);
        return TRUE;
    }

    function get_wp_list($status)
    {
        $session = $this->common->get_session();

        $this->db->select('r.id, d.name AS district, b.name AS block, r.name, r.wo_start_date, r.physical_progress, r.progress_remarks, r.pp_status, r.scheme_id AS ref_no');
        $this->db->from(RIDF . ' AS r');
        $this->db->join(DIVISION . ' AS b', 'r.block_id = b.id');
        $this->db->join(DIVISION . ' AS d', 'r.district_id = d.id');

        $this->db->where(['r.isactive' => 1, 'r.wo_status' => 2]);

        if (!empty($session['district_id']) && $session['district_id'] > 0) {
            $this->db->where('r.district_id', $session['district_id']);
        }

        if (in_array($status, [0, 5])) {
            $this->db->where('r.pp_status', $status);
        } elseif (in_array($status, [1, 2, 3, 4])) {
            $this->db->where('r.pp_status >=', 1);
            $this->db->where('r.pp_status <=', 4);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function get_wp_info($id, $status)
    {
        $where = 'WHERE s.id=' . $id . ' AND s.isactive=1 ';
        switch ($status) {
            case 0:
            case 5:
                $where .= ' AND s.pp_status=' . $status;
                break;
            case 1:
            case 2:
            case 3:
            case 4:
                $where .= ' AND s.pp_status > 0 and s.pp_status < 5';
                break;
            default:
                break;
        }
        $sql = 'SELECT s.id, b.name as block, s.name, s.length, s.road_type_id, s.work_type_id, '
            . 's.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status, s.scheme_id as ref_no '
            . 'FROM ridf s JOIN division b ON s.block_id=b.id  ' . $where;
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query);exit;
        // echo print_r($query);exit;
        return $query->row();
    }

    function wp_save($data)
    {
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
        $this->db->insert(RIDF_PROGRESS, $input);
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
        $this->db->update(RIDF, $input);
        return $id;
    }

    function get_wp_document($id)
    {
        $this->db->select('r.name as name, rp.physical_progress as physical_progress, rp.image1, rp.image2, rp.image3');
        $this->db->from(RIDF . ' AS r');
        $this->db->join(RIDF_PROGRESS . ' AS rp', 'r.id = rp.ridf_id');
        $this->db->where('rp.ridf_id', $id);
        $query = $this->db->get();
        return $query->result();
    }

    function get_inspection_list($sqm_id, $month, $year)
    {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, ur.name as sqm, ur.mobile as sqm_mobile, u.mobile, qm.status
            FROM ridf_bridge_qm as qm JOIN ridf s ON s.id=qm.ridf_id JOIN ridf_wo wo ON wo.ridf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status<2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_report_list($sqm_id, $month, $year)
    {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, u.mobile, ur.name as sqm, ur.mobile as sqm_mobile, qm.status, sqi.inspection_date, sqi.overall_grade, sqi.document
            FROM ridf_bridge_qm as qm JOIN ridf_bridge_qm_inspection sqi ON sqi.qm_id=qm.id JOIN ridf s ON s.id=qm.ridf_id JOIN ridf_wo wo ON wo.ridf_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
            WHERE qm.isactive=1 AND qm.status=2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_caption($qm_id)
    {
        $sql = 'SELECT s.name, s.agency FROM ridf_bridge_qm as qm JOIN ridf s ON qm.ridf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_selection($qm_id)
    {
        $sql = 'SELECT s.name, s.agency FROM ridf_bridge_qm as qm JOIN ridf s ON qm.ridf_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_category_list()
    {
        $this->db->select('id, subcategory as name');
        $this->db->where(array(
            'category' => 'RIDF',
            'isactive' => 1
        ));
        $query = $this->db->get(CATEGORY);
        return $query->result();
    }

    function get_project_type_list()
    {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(TYPE);
        return $query->result();
    }

    function get_agency_list()
    {
        $this->db->distinct();
        $this->db->select('a.id, a.name');
        $this->db->where(array(
            'r.isactive' => 1
        ));
        $this->db->join(RIDF . ' r', 'r.agency_id=a.id');
        $this->db->order_by('name');
        $query = $this->db->get(AGENCY . ' a');
        return $query->result();
    }

    function get_road_type_list()
    {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(ROAD_TYPE);
        return $query->result();
    }

    function get_work_type_list()
    {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(WORK_TYPE);
        return $query->result();
    }

    function get_constitution_list($district_id = 0)
    {
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

    function filter_with_agency()
    {
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

    function get_rpt_state_summary($ac_id = 0)
    {
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

    function get_rpt_agency_progress($ac_id = 0)
    {
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

    function get_rpt_road_type_progress($road_type, $ac_id = 0)
    {
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

    function get_rpt_work_type_progress($work_type, $ac_id = 0)
    {
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

    function get_rpt_ps_work_status($district_id = 0, $block_id = 0, $ac_id = 0)
    {
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
            from ridf_report ' . (strlen($where) > 0 ? 'where ' . $where : '') . ' group by district,block, agency order by district, agency ';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_work_progress($district_id = 0, $block_id = 0, $wp_id = 0, $ac_id = 0)
    {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $wp = $wp_id != 6 ? 's.pp_status =' . $wp_id : 's.pp_status >=0 and s.pp_status <=5';
        // $where .= '';
        $where .= $this->filter_with_agency();
        $sql = 'select s.id, d.name as district, b.name as block, s.name, sa.name as agency, s.length, wo.awarded_cost, srt.name as road_type, swt.name as work_type, '
            . 's.wo_start_date, s.pp_status, s.physical_progress,s.image1,s.image2,s.image3,  wp.wp_date as last_work_date, wp.date_count as inspection_count from ridf s '
            . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join ridf_wo wo on wo.ridf_id=s.id join scheme_agency sa on s.agency_id=sa.id '
            . 'join scheme_road_type srt on s.road_type_id=srt.id join scheme_work_type swt on s.work_type_id=swt.id '
            . 'JOIN (SELECT ridf_id, max(created) As wp_date,wo_start_date,count(created) as date_count FROM ridf_progress GROUP BY ridf_id) wp ON s.id = wp.ridf_id AND s.wo_start_date = wp.wo_start_date '
            . 'where s.survey_status=6 and s.isactive=1 and ' . $wp . ' ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_rpt_work_progress_details($ridf_id)
    {
        //        $db = $this->load->database('rpt', TRUE);
        $sql = 'SELECT s.name, sp.created, sp.wo_start_date, sp.physical_progress, sp.location1, sp.image1, sp.location2, sp.image2, sp.location3,
        sp.image3, sp.progress_remarks FROM ridf_progress sp join ridf s on sp.ridf_id=s.id WHERE sp.ridf_id=' . $ridf_id . ' ORDER BY sp.created';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_qm_summary($start_date, $end_date)
    {
        $sql = 'SELECT d.id, d.name as district, sqm.agency, sqm.overall_grade, COUNT(sqm.overall_grade) as cnt
        FROM ridf_bridge_qm as qm
        JOIN ridf_bridge_qm_inspection sqm ON sqm.qm_id=qm.id
        JOIN division d ON qm.district_id=d.id
        WHERE sqm.isactive=1 AND sqm.inspection_date>="' . date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . '" AND sqm.inspection_date<="' . date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . '"
        GROUP BY d.name, sqm.agency, sqm.overall_grade ORDER BY d.name, sqm.agency';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_update_status($district_id = 0, $block_id = 0, $wp_id = 0)
    {
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

    function update_save($data)
    {
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

    function get_rpt_updated_status($district_id = 0, $block_id = 0, $status = 0)
    {
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

    function get_rpt_approval_progress($ac_id = 0)
    {
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

    #################################### RIDF BRIDGE #######################################

    function get_bridge_overview_count()
    {
        $sql = 'SELECT count(id) as total,if(isactive=-1,count(id),0) as bridge_delete, if(isactive = 1,COUNT(id),0) as approve FROM ridf_bridge';
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }

    function get_bridge_list($district_id, $block_id, $category_id)
    {
        $block_id = $block_id == 0 ? '' : ' and rb.block_id=' . $block_id;
        $category_id = $category_id == 0 ? '' : ' and category_id = ' . $category_id;
        $sql = 'SELECT rb.id, rb.name, d.name as district, b.name as block, rb.agency, date_format(rb.aot_date, "%d/%m/%Y") as aot_date, date_format(rb.wo_date, "%d/%m/%Y") as wo_date, date_format(rb.completion_date, "%d/%m/%Y") as complete_date, sc.subcategory as funding, rb.isactive from ridf_bridge as rb '
            . 'JOIN division as d on rb.district_id = d.id '
            . 'JOIN division as b on rb.block_id = b.id '
            . 'JOIN scheme_category as sc on rb.category_id = sc.id '
            . 'where rb.isactive = 1 and rb.district_id = ' . $district_id . $block_id . $category_id
            . ' order by rb.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }

    function get_bridge_info($id)
    {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'and district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] != 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $sql = 'SELECT rb.id, rb.district_id, rb.block_id, rb.category_id, rb.name, rb.agency, rb.aot_date as aot_date, rb.wo_date as wo_date, rb.completion_date as complete_date, rb.isactive from ridf_bridge as rb '
            . 'where rb.isactive = 1 and rb.id = ' . $id . $where;
        $query = $this->db->query($sql);
        // print_r($query->result());
        // echo $this->db->last_query($query); exit;
        return $query->row();
    }

    function bridge_save($data)
    {
        if ($data['block_id'] == 0) {
            return false;
        } else {
            $this->db->trans_start();
            $input = array(
                'name' => $data['bridge_name'],
                'agency' => $data['agency'],
                'aot_date' => $data['aot_date'] == '' ? NULL : date('Y-m-d', strtotime(str_replace('/', '-', $data['aot_date']))),
                'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                'completion_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['complete_date']))),
                'district_id' => $data['district_id'],
                'block_id' => $data['block_id'],
                'category_id' => $data['funding_id']
            );
            $id = $data['id'];
            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->update(RIDF_BRIDGE, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(RIDF_BRIDGE, $input);
                $id = $this->db->insert_id();
                // echo($id);
            }
            $sql = 'SELECT id from ridf_bridge where id=' . $id;
            $query = $this->db->query($sql);
            $bridge_id = $query->row('id');
            $input_qm = array(
                'created' => date('Y-m-d'),
                'bridge_id' => $bridge_id,
                'agency' => $data['agency']
            );
            if ($id > 0) {
                $input_qm_update = array(
                    'agency' => $data['agency']
                );
                $this->db->where('bridge_id', $bridge_id);
                $this->db->update(RIDF_BRIDGE_QM, $input_qm_update);
                $this->db->where('bridge_id', $bridge_id);
                $this->db->update(RIDF_BRIDGE_INSPECTION, $input_qm_update);
            } else {
                $this->db->insert(RIDF_BRIDGE_QM, $input_qm);
                $this->db->insert(RIDF_BRIDGE_INSPECTION, $input_qm);
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

    function bridge_delete($id)
    {
        $input = array(
            'isactive' => -1
        );
        $this->db->where('id', $id);
        $this->db->update(RIDF_BRIDGE, $input);
        return true;
    }

    ################################ Bridge QM #################################

    function get_sqm_list()
    {
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

    function get_bridge_qm_list($month, $year)
    {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : '';
        $where .= $session['role_id'] == 5 ? ' and qm.sqm_id=' . $session['user_id'] : '';
        $sql = 'SELECT CONCAT(u.id, "-", qm.month, "-", qm.year,"-",qm.bridge_id) as id, u.name as sqm, u.mobile, qm.status,s.name, qm.physical_progress, qm.financial_progress,
            d.name as district, b.name as block, bridge_id as total
            FROM ridf_bridge_qm as qm JOIN division d ON qm.district_id=d.id JOIN ridf_bridge s ON s.id=qm.bridge_id JOIN division b ON s.block_id=b.id JOIN user u ON u.id=qm.sqm_id
            WHERE qm.isactive=1 and qm.month=' . $month . ' and qm.year=' . $year . $where . ' ORDER BY u.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_bridge_qm_scheme_list($district_id, $sqm_id = 0, $month = 0, $year = 0)
    {
        $sqm_id = $sqm_id == '' ? 0 : $sqm_id;
        $sql = 'SELECT s.id, d.name as district, b.name as block, s.name, s.agency, s.wo_date as wo_start_date, q.physical_progress, q.inspection_date, q.overall_grade, ifnull(q.id, 0) as selected
            FROM ridf_bridge s JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
            LEFT JOIN ridf_bridge_qm q ON s.id=q.bridge_id and q.sqm_id=' . $sqm_id . ' and q.month=' . $month . ' and q.year=' . $year . ' and q.isactive=1
            LEFT JOIN (SELECT sq.bridge_id, sq.inspection_date, sq.overall_grade FROM ridf_bridge_qm sq WHERE sq.inspection_date=(SELECT max(sqi1.inspection_date) from ridf_bridge_qm as sqi1 join ridf_bridge_qm sq1 on sqi1.sqm_id=sq1.id WHERE sq1.bridge_id=sq.bridge_id)) as qm ON qm.bridge_id=s.id
            LEFT JOIN (SELECT DISTINCT bridge_id, count(bridge_id) as cnt FROM ridf_bridge_qm GROUP BY bridge_id) as total ON total.bridge_id=s.id
            WHERE s.isactive = 1 AND s.district_id=' . $district_id . ' ORDER BY d.name ASC, b.name ASC, agency ASC, q.physical_progress DESC';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function bridge_qm_save($data)
    {
        $this->db->trans_start();
        $input = array(
            'isactive' => -1
        );
        $this->db->where(array(
            'bridge_id' => $data['bridge_id'],
            'district_id' => $data['district_id'],
            'sqm_id' => $data['sqm_id'],
            'year' => $data['year'],
            'month' => $data['month']
        ));
        $this->db->update(RIDF_BRIDGE_QM, $input);
        foreach ($data['chk'] as $row) {
            $input = array(
                'district_id' => $data['district_id'],
                'bridge_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month'],
                'isactive' => 1
            );
            $this->db->where(array(
                'bridge_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month']
            ));
            $query = $this->db->get(RIDF_BRIDGE_QM);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $query->row()->id);
                $this->db->where('bridge_id', $data['bridge_id']);
                $this->db->update(RIDF_BRIDGE_QM, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(RIDF_BRIDGE_QM, $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function get_bridge_qm_caption($qm_id, $bridge_id)
    {
        $this->db->select('qm.bridge_id, b.name, b.agency, qm.physical_progress, qm.financial_progress, qm.current_work_of_stage, qm.overall_grade, qm.document, qm.inspection_date, qm.remarks');
        $this->db->where(array(
            'qm.sqm_id' => $qm_id,
            'bridge_id' => $bridge_id,
            'qm.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE . ' b', 'qm.bridge_id=b.id');
        $query = $this->db->get(RIDF_BRIDGE_QM . ' qm');
        return $query->result();
    }

    function get_bridge_qm_image_list($id, $bridge_id)
    {
        $this->db->select('i.description, i.image');
        $this->db->where(array(
            'hd.bridge_id' => $bridge_id,
            'hd.sqm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE_QM_IMAGE . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get(RIDF_BRIDGE_QM . ' hd');
        return $query->result();
    }

    function get_bridge_qm_total_image_list($id, $bridge_id)
    {
        $this->db->select('count(i.seq_id) as seq_id');
        $this->db->where(array(
            'hd.bridge_id' => $bridge_id,
            'hd.sqm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE_QM_IMAGE . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get(RIDF_BRIDGE_QM . ' hd');
        return $query->result();
    }

    function bridge_qm_save_start($qm_id, $data)
    {
        $this->db->trans_start();
        $id = 0;
        $this->db->where('sqm_id', $qm_id);
        $this->db->where('bridge_id', $data['bridge_id']);
        $query = $this->db->get(RIDF_BRIDGE_QM);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'sqm_id' => $qm_id,
                'agency' => $data['agency']
            );
            $this->db->insert(RIDF_BRIDGE_QM, $input);
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

    function bridge_qm_save_image($i, $id, $desc)
    {
        $this->db->trans_start();
        $this->db->where(array(
            'inspection_id' => $id,
            'seq_id' => $i + 1
        ));
        $query = $this->db->get(RIDF_BRIDGE_QM_IMAGE);

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
            $this->db->update(RIDF_BRIDGE_QM_IMAGE, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(RIDF_BRIDGE_QM_IMAGE, $input);
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

    function bridge_qm_save_submit($data)
    {
        $this->db->trans_start();
        $this->db->select('id');
        $this->db->where('sqm_id', $data['qm_id']);
        $query = $this->db->get(RIDF_BRIDGE_QM);
        $id = $query->row()->id;
        if ($id > 0) {
            $input = array(
                'inspection_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['inspection_date']))),
                'overall_grade' => $data['overall_grade'],
                'agency' => $data['agency']
            );
            $this->db->where('sqm_id', $data['qm_id']);
            $this->db->where('bridge_id', $data['bridge_id']);
            $this->db->update(RIDF_BRIDGE_QM, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }

    ############################################################################
    ########################### Bridge INSPECTION ##############################

    function get_bridge_inspection_scheme_list($district_id, $sqm_id = 0, $month = 0, $year = 0)
    {
        $sqm_id = $sqm_id == '' ? 0 : $sqm_id;
        $sql = 'SELECT s.id, d.name as district, b.name as block, s.name, s.agency, q.length,q.fundation, q.length, q.width, q.hfl, q.ofl, q.obstruction, q.traffic_category, q.lbl, q.proposed_bridge, q.super_structure, q.linear_waterway, q.linear_water_provided, total.cnt, ifnull(q.id, 0) as selected
            FROM ridf_bridge s JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
            LEFT JOIN ridf_bridge_inspection q ON s.id=q.bridge_id and q.sqm_id=' . $sqm_id . ' and q.month=' . $month . ' and q.year=' . $year . ' and q.isactive=1
            LEFT JOIN (SELECT sq.bridge_id, sqi.inspection_date FROM ridf_bridge_inspection sq JOIN ridf_bridge_inspection sqi ON sqi.sqm_id=sq.id WHERE sqi.inspection_date=(SELECT max(sqi1.inspection_date) from ridf_bridge_inspection as sqi1 join ridf_bridge_qm sq1 on sqi1.sqm_id=sq1.id WHERE sq1.bridge_id=sq.bridge_id)) as qm ON qm.bridge_id=s.id
            LEFT JOIN (SELECT DISTINCT bridge_id, count(bridge_id) as cnt FROM ridf_bridge_inspection GROUP BY bridge_id) as total ON total.bridge_id=s.id
            WHERE s.isactive = 1 AND s.district_id=' . $district_id . ' ORDER BY d.name ASC, b.name ASC, agency ASC';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function bridge_inspection_save($data)
    {
        $this->db->trans_start();
        $input = array(
            'isactive' => -1
        );
        $this->db->where(array(
            'bridge_id' => $data['bridge_id'],
            'district_id' => $data['district_id'],
            'sqm_id' => $data['sqm_id'],
            'year' => $data['year'],
            'month' => $data['month']
        ));
        $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
        foreach ($data['chk'] as $row) {
            $input = array(
                'district_id' => $data['district_id'],
                'bridge_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month'],
                'isactive' => 1
            );
            $this->db->where(array(
                'bridge_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month']
            ));
            $query = $this->db->get(RIDF_BRIDGE_INSPECTION);
            if ($query->num_rows() > 0) {
                $this->db->where('id', $query->row()->id);
                $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(RIDF_BRIDGE_INSPECTION, $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function get_bridge_inspection_list($month, $year)
    {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : '';
        if ($session['block_id'] > 0) {
            $where .= ' and s.block_id in ' . explode(',', $session['block_id']);
        }
        switch ($session['role_id']) {
            case 5:
                $where .= ' and qm.sqm_id=' . $session['user_id'];
                break;
            default:
                break;
        }
        $sql = 'SELECT CONCAT(u.id, "-", qm.month, "-", qm.year,"-",qm.bridge_id) as id, u.name as sqm, u.mobile, qm.status,s.name,s.agency,u.mobile as sqm_mobile,
            d.name as district, b.name as block, bridge_id as total
            FROM ridf_bridge_inspection as qm JOIN ridf_bridge s ON s.id=qm.bridge_id JOIN division d ON qm.district_id=d.id JOIN division b ON s.block_id=b.id 
            JOIN user u ON u.id=qm.sqm_id
            WHERE qm.isactive=1 and qm.month=' . $month . ' and qm.year=' . $year . $where . ' ORDER BY u.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_bridge_inspection_caption($qm_id, $bridge_id)
    {
        $this->db->select('i.bridge_id, b.name, b.agency, b.agency, i.inspection_date, i.road_obstruction, i.length, i.fundation, i.length, i.width, i.hfl, i.ofl, i.obstruction, i.traffic_category, i.lbl, i.proposed_bridge, i.super_structure, i.linear_waterway, i.linear_water_provided, i.document, i.remarks');
        $this->db->where(array(
            'i.sqm_id' => $qm_id,
            'i.bridge_id' => $bridge_id,
            'i.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE . ' b', 'i.bridge_id=b.id');
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION . ' i');
        return $query->result();
    }

    function get_bridge_inspection_image_list($id, $bridge_id)
    {
        $this->db->select('i.description, i.image');
        $this->db->where(array(
            'hd.bridge_id' => $bridge_id,
            'hd.sqm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE_INSPECTION_IMAGE . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION . ' hd');
        return $query->result();
    }

    function get_bridge_inspection_total_image_list($id, $bridge_id)
    {
        $this->db->select('count(i.seq_id) as seq_id');
        $this->db->where(array(
            'hd.bridge_id' => $bridge_id,
            'hd.sqm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join(RIDF_BRIDGE_INSPECTION_IMAGE . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION . ' hd');
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }

    function bridge_inspection_save_start($qm_id, $data)
    {
        $this->db->trans_start();
        $id = 0;
        $this->db->where('sqm_id', $qm_id);
        $this->db->where('bridge_id', $data['bridge_id']);
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION);
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'sqm_id' => $qm_id,
                'agency' => $data['agency']
            );
            $this->db->insert(RIDF_BRIDGE_INSPECTION, $input);
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

    function bridge_inspection_save_image($i, $id, $desc)
    {
        $this->db->trans_start();
        $this->db->where(array(
            'inspection_id' => $id,
            'seq_id' => $i + 1
        ));
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION_IMAGE);

        $image_id = $query->num_rows() > 0 ? $query->row()->id : 0;
        //var_dump($image_id);exit;
        $input = array(
            'inspection_id' => $id,
            'seq_id' => $i + 1,
            'description' => $desc[$i]
        );
        if ($image_id > 0) {
            $this->db->where('id', $image_id);
            $this->db->update(RIDF_BRIDGE_INSPECTION_IMAGE, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(RIDF_BRIDGE_INSPECTION_IMAGE, $input);
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

    function bridge_inspection_save_submit($data)
    {
        $road_obstruction = $data['road_obstruction'] == '1' ? "Cleared Approach road" : "Obstructed Approach road";
        $this->db->trans_start();
        $this->db->select('id');
        $this->db->where('sqm_id', $data['inspection_id']);
        $query = $this->db->get(RIDF_BRIDGE_INSPECTION);
        $inspection_id = $query->row()->id;
        if ($inspection_id > 0) {
            $input = array(
                'inspection_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['inspection_date']))),
                'road_obstruction' => $road_obstruction,
                'agency' => $data['agency'],
                'remarks' => $data['remarks']
            );
            $this->db->where('sqm_id', $data['inspection_id']);
            $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $inspection_id;
    }

    ############################################################################
    #######################   RIDF Requisition   ###############################

    function get_curr_requisition_list($selected)
    {
        $where = $selected['district_id'] > 0 ? ' AND r.district_id=' . $selected['district_id'] : '';
        $where .= $selected['category_id'] > 0 ? ' AND r.category_id=' . $selected['category_id'] : '';
        $sql = 'SELECT DISTINCT rr.id, c.subcategory as category, d.name as district, rr.ridf_id, r.name, rr.ra_id, ifnull(rr.ref_no, "") as ref_no, rr.memo_no, '
            . 'date_format(rr.memo_date, "%d/%m/%Y") as memo_date, rr.physical_progress, rr.claimed_amt, rr.dpr_amt, rr.contigency_amt, '
            . 'date_format(rr.approved_date, "%d/%m/%Y") as approved_date,rr.approved_claimed_amt,rr.approved_dpr_amt,rr.approved_contigency_amt, rr.claimed_expenditure_amt, rr.dpr_expenditure_amt, rr.contigency_expenditure_amt	, ifnull(rr.claimed_doc, "") as claimed_doc, '
            . 'ifnull(rr.approved_doc, "") as approved_doc FROM ridf_requisition rr JOIN ridf r ON rr.ridf_id=r.id JOIN division d ON r.district_id=d.id '
            . 'JOIN scheme_category c ON rr.category_id=c.id WHERE rr.isactive = 1' . $where . ' AND rr.ra_id = (SELECT max(ra_id) FROM ridf_requisition WHERE rr.ridf_id=ridf_id GROUP BY ridf_id) '
            . 'ORDER BY d.name, c.subcategory, r.name, rr.ra_id';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_ridf_agency_list()
    {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('a.id, a.name');
        $this->db->where('r.isactive', 1);
        $session['district_id'] > 0 ? $this->db->where_in('r.district_id', explode(',', $session['district_id'])) : '';
        $this->db->join(RIDF . ' r', 'r.agency_id=a.id');
        $this->db->order_by('name');
        $query = $this->db->get(AGENCY . ' a');
        return $query->result();
    }

    function get_work_list($selected)
    {
        $this->db->select('r.id, r.name, r.scheme_id');
        $this->db->where([
            'r.district_id' => $selected['district_id'],
            'r.category_id' => $selected['category_id'],
            'r.agency_id' => $selected['agency_id'],
            'r.isactive' => 1
        ]);
        $this->db->join(RIDF_WO . ' wo', 'wo.ridf_id=r.id');
        $this->db->order_by('r.name');
        $query = $this->db->get(RIDF . ' r');
        return $query->result();
    }

    function get_work_info($ridf_id, $id = 0)
    {
        $this->db->select('d.name as district, b.name as block, c.subcategory as category, r.scheme_id, r.name, a.name as agency, r.length, r.unit, r.sanctioned_cost, wo.awarded_cost, max(rr.physical_progress) as physical_progress, '
            . 'sum(ifnull(rr.approved_amt,0)) as approved_amt, sum(ifnull(rr.expenditure_amt,0)) as expenditure_amt, sum(ifnull(rr.dpr_amt,0)) as dpr, sum(ifnull(rr.contigency_amt,0)) as contigency');
        $this->db->where(array(
            'r.id' => $ridf_id,
            'wo.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'r.block_id=b.id');
        $this->db->join(CATEGORY . ' c', 'r.category_id=c.id');
        $this->db->join(AGENCY . ' a', 'r.agency_id=a.id');
        $this->db->join(RIDF_WO . ' wo', 'wo.ridf_id=r.id');
        $this->db->join(RIDF_REQUISITION . ' rr', 'rr.ridf_id=r.id and rr.id<' . $id, 'left');
        $this->db->group_by('d.name, b.name, c.subcategory, r.scheme_id, r.name, a.name, r.length, r.unit, r.sanctioned_cost, wo.awarded_cost');
        $query = $this->db->get(RIDF . ' r');
        return $query->row();
    }

    function get_prev_requisition_list($ridf_id, $id = 0)
    {
        $this->db->select('rr.id, c.subcategory as category, r.name as work, rr.ra_id, ifnull(rr.ref_no, "") as ref_no, rr.memo_no, date_format(rr.memo_date, "%d/%m/%Y") as memo_date, '
            . 'rr.physical_progress, rr.claimed_amt, rr.dpr_amt, rr.contigency_amt, date_format(rr.approved_date, "%d/%m/%Y") as approved_date,rr.approved_claimed_amt, rr.approved_dpr_amt, rr.approved_contigency_amt, rr.approved_amt,rr.claimed_expenditure_amt, rr.dpr_expenditure_amt, rr.contigency_expenditure_amt, rr.expenditure_amt, '
            . 'ifnull(rr.claimed_doc, "") as claimed_doc, ifnull(rr.approved_doc, "") as approved_doc');
        $this->db->where(array(
            'rr.ridf_id' => $ridf_id,
            'rr.isactive' => 1
        ));
        $id > 0 ? $this->db->where('rr.id<' . $id) : '';
        $this->db->join(RIDF . ' r', 'rr.ridf_id=r.id');
        $this->db->join(CATEGORY . ' c', 'rr.category_id=c.id');
        $this->db->order_by('rr.ra_id');
        $query = $this->db->get(RIDF_REQUISITION . ' rr');
        return $query->result();
    }

    function get_curr_requisition_info($id)
    {
        $this->db->select('ra_id, ifnull(ref_no, "") as ref_no, memo_no, date_format(memo_date, "%d/%m/%Y") as memo_date, physical_progress, claimed_amt, dpr_amt, contigency_amt');
        $this->db->where('id', $id);
        $query = $this->db->get(RIDF_REQUISITION);
        return $query->row();
    }
    function get_total_requisition_info($ridf_id)
    {
        // print_r($ridf_id);exit;
        $this->db->select('
        SUM(CASE WHEN ref_no IS NOT NULL THEN IFNULL(claimed_amt, 0) ELSE 0 END) AS total_claimed_amt,
        SUM(CASE WHEN ref_no IS NOT NULL THEN IFNULL(dpr_amt, 0) ELSE 0 END) AS total_dpr_amt,
        SUM(CASE WHEN ref_no IS NOT NULL THEN IFNULL(contigency_amt, 0) ELSE 0 END) AS total_contigency_amt,
        SUM(ifnull(approved_claimed_amt,0)) AS total_approved_claimed_amt, SUM(ifnull(approved_dpr_amt,0)) AS total_approved_dpr_amt, SUM(ifnull(approved_contigency_amt,0)) AS total_approved_contigency_amt, SUM(ifnull(claimed_expenditure_amt,0)) AS total_claimed_expenditure_amt, SUM(ifnull(dpr_expenditure_amt,0)) AS total_dpr_expenditure_amt, SUM(ifnull(contigency_expenditure_amt,0)) AS total_contigency_expenditure_amt');
        $this->db->where('ridf_id', $ridf_id);
        $this->db->group_by('ridf_id');
        $query = $this->db->get(RIDF_REQUISITION);
        return $query->row();
    }

    function requisition_save($data)
    {
        // print_r($data['contigency']);exit;
        $this->db->trans_start();
        $ref_no = '';
        if (isset($data['iscurrent']) && $data['iscurrent'] > 0) {
            $this->db->select('a.name, d.code');
            $this->db->where('r.id', $data['ridf_id']);
            $this->db->join(AGENCY . ' a', 'r.agency_id=a.id');
            $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
            $query = $this->db->get(RIDF . ' r');
            $row = $query->row();
            $ref_no = 'RIDF/' . $row->name . '/' . $row->code . '/' . $data['ridf_id'] . '/' . $data['hdn_ra_id'];
        }
        $id = $data['id'];
        $input = array(
            'category_id' => $data['category_id'],
            'ridf_id' => $data['ridf_id'],
            'ra_id' => $data['hdn_ra_id'],
            'memo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['memo_date']))),
            'memo_no' => $data['memo_no'],
            'ref_no' => strlen($ref_no) > 0 ? $ref_no : null,
            'physical_progress' => $data['physical_progress'],
            'claimed_amt' => isset($data['claimed']) && $data['claimed'] > 0 ? $data['claimed_amt'] : 0,
            'dpr_amt' => isset($data['dpr']) && $data['dpr'] > 0 ? $data['dpr_amt'] : 0,
            'contigency_amt' => isset($data['contigency']) && $data['contigency'] > 0 ? $data['contigency_amt'] : 0,
            'approved_date' => isset($data['iscurrent']) && $data['iscurrent'] > 0 ? null : ($data['approved_date'] != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $data['approved_date']))) : null),
            'approved_claimed_amt' => isset($data['claimed']) && $data['claimed'] > 0 ? $data['app_claimed_amt'] : 0,
            'approved_dpr_amt' => isset($data['dpr']) && $data['dpr'] > 0 ? $data['app_dpr_amt'] : 0,
            'approved_contigency_amt' => isset($data['contigency']) && $data['contigency'] > 0 ? $data['app_contigency_amt'] : 0
        );
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(RIDF_REQUISITION, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(RIDF_REQUISITION, $input);
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

    

    function expenditure_save($data)
    {
        $update = [];
        if (!empty($data['claimed_expenditure_amt'])) {
            $update['claimed_expenditure_amt'] = $data['claimed_expenditure_amt'];
        }
        if (!empty($data['dpr_expenditure_amt'])) {
            $update['dpr_expenditure_amt'] = $data['dpr_expenditure_amt'];
        }
        if (!empty($data['contigency_expenditure_amt'])) {
            $update['contigency_expenditure_amt'] = $data['contigency_expenditure_amt'];
        }

        if (!empty($update)) {
            $this->db->where('id', $data['id']);
            $this->db->update(RIDF_REQUISITION, $update);  
        }
    }



    function requisition_delete($id)
    {
        $input = array(
            'isactive' => -1
        );
        $this->db->where('id', $id);
        $this->db->update('ridf_requisition', $input);
        return true;
    }

    // ################################ Post DLP Maintenance #########################################


    function get_postdlp_info($id)
    {
        $this->db->select('r.district_id as district_id, d.name as district, r.block_id as block_id, b.name as block, r.name, r.agency, r.length,r.scheme_id');
        $this->db->where('r.id', $id);
        $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'r.block_id=b.id');
        $query = $this->db->get(RIDF . ' r');
        return $query->row();
    }

    function get_postdlp_list($district_id = null, $category_id = null, $type_id = null)
    {
        $session = $this->common->get_session();
        $block_id = isset($session['block_id']) ? $session['block_id'] : null;
        $agencyFilter = '';
        switch ($session['role_id']) {
            case 13:
                $agencyFilter = " AND r.agency = 'ZP'";
                break;
            case 14:
                $agencyFilter = " AND r.agency = 'BLOCK'";
                break;
            case 15:
                $agencyFilter = " AND r.agency = 'WBSRDA'";
                break;
            case 16:
                $agencyFilter = " AND r.agency = 'MBL'";
                break;
            case 17:
                $agencyFilter = " AND r.agency = 'AGRO'";
                break;
            default:
                break;
        }

        $sql = 'SELECT r.id, r.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac, 
                   a.name as agency, r.category_id, sc.subcategory as funding, r.scheme_id, 
                    r.road_type_name as work_type, r.postdlp_maintenance as pdm              
            FROM ridf as r 
            JOIN division as d ON d.id = r.district_id 
            JOIN division as b ON b.id = r.block_id 
            LEFT JOIN assembly_constituency as ac ON r.ac_id = ac.id 
            JOIN scheme_agency as a ON a.id = r.agency_id 
            JOIN scheme_category as sc ON sc.id = r.category_id 
            WHERE r.isactive = 1 AND a.ridf = 1 AND r.physical_progress = 100';

        $sql .= $agencyFilter;

        if (!empty($district_id)) {
            $sql .= ' AND r.district_id = ' . $district_id;
        }
        if (!empty($category_id)) {
            $sql .= ' AND r.category_id = ' . $category_id;
        }
        if (!empty($type_id)) {
            $sql .= ' AND r.work_type_id = ' . $type_id;
        }
        if (!empty($block_id)) {
            $sql .= ' AND r.block_id = ' . $block_id;
        }

        $sql .= ' ORDER BY r.name';

        $query = $this->db->query($sql);
        return $query->result();
    }

    public function postdlp_save($data)
    {
        $input = array(
            'ridf_id' => $data['id'],
            'carriage_type' => $data['carriage_type'],
            'existing_carriage_value' => $data['existing_carriage_value'],
            'roadway_type' => $data['roadway_type'],
            'existing_width' => $data['existing_width'],
            'existing_bt_length' => $data['existing_bt_length'],
            'existing_cc_length' => $data['existing_cc_length'],
            'existing_total_length' => $data['existing_total_length'],
            'prop_bt_length' => $data['prop_bt_length'],
            'prop_cc_length' => $data['prop_cc_length'],
            'prop_total_length' => $data['prop_total_length'],
            'existing_traffic' => $data['existing_traffic'],
            'prop_traffic' => $data['prop_traffic'],
            'initial_rehabilitation_cost' => $data['initial_rehabilitation_cost'],
            'renewal_cost' => $data['renewal_cost'],
            'routine_year1' => $data['routine_year1'],
            'routine_year2' => $data['routine_year2'],
            'routine_year3' => $data['routine_year3'],
            'routine_year4' => $data['routine_year4'],
            'routine_year5' => $data['routine_year5'],
            'routine_total' => $data['routine_total'],
            'total_project_cost' => $data['total_project_cost'],
            'created' => date('Y-m-d')
        );

        $this->db->insert(RIDF_POSTDLP_MAINTENANCE, $input);
        $insert_id = $this->db->insert_id();

        $this->db->where('id', $data['id']);
        $this->db->update(RIDF, array('postdlp_maintenance' => 1));

        return $insert_id;
    }

    function get_postdlp_view($id)
    {
        $this->db->select('r.district_id as district_id, d.name as district, r.block_id as block_id, b.name as block, r.name, r.agency, r.length,r.scheme_id, rp.carriage_type, rp.existing_carriage_value, rp.roadway_type, rp.existing_width, rp.estimation_file, rp.existing_bt_length, rp.existing_cc_length, rp.existing_total_length, rp.prop_bt_length, rp.prop_cc_length, rp.prop_total_length, rp.existing_traffic, rp.prop_traffic, rp.initial_rehabilitation_cost, rp.renewal_cost, rp.routine_year1, rp.routine_year2, rp.routine_year3, rp.routine_year4, rp.routine_year5, rp.routine_total, rp.total_project_cost');
        $this->db->where('r.id', $id);
        $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'r.block_id=b.id');
        $this->db->join(RIDF_POSTDLP_MAINTENANCE . ' rp', 'r.id = rp.ridf_id');
        $query = $this->db->get(RIDF . ' r');
        return $query->row();
    }
}
