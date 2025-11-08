<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Capex_Model extends CI_Model
{

    // #############  Common use #########################
    function get_category_list()
    {
        $this->db->select('id, subcategory AS name');
        $this->db->from(CATEGORY);
        $this->db->where([
            'category' => 'CAPEX',
            'isactive' => 1
        ]);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        }
        return [];
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
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1,
            'id >' => 3,
            'capex' => 1
        ));
        $this->db->order_by('name');
        $query = $this->db->get(AGENCY);
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

    function agency_role_filter($role_id)
    {
        switch ($role_id) {
            case 13:
                return 'ZP';
            case 14:
                return 'BLOCK';
            case 15:
                return 'WBSRDA';
            case 16:
                return 'MBL';
            case 17:
                return 'AGRO';
            default:
                return null;
        }
    }



    // #############  CAPEX Master #################################

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
            capex s ON s.category_id = sc.id AND s.isactive = 1
        WHERE 
            sc.isactive = 1 
            AND sc.category = 'capex'
        GROUP BY sc.subcategory
        ORDER BY sc.id ";

        $query = $this->db->query($sql);
        return $query->result();
    }


    function get_scheme_list($district_id = null, $category_id = null, $type_id = null)
    {
        $session = $this->common->get_session();
        $agency = $this->agency_role_filter($session['role_id']);
        $this->db->select('c.id, c.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac,
        a.name as agency, c.category_id, sc.subcategory as funding, c.scheme_id, c.length,
        c.sanctioned_cost, c.admin_date, c.admin_no, c.road_type_name as work_type,
        c.length, c.isactive, c.note, c.survey_status, c.tender_status, c.wo_status');

        $this->db->from(CAPEX . ' as c');
        $this->db->join(DIVISION . ' as d', 'd.id = c.district_id');
        $this->db->join(DIVISION . ' as b', 'b.id = c.block_id');
        $this->db->join(AC . ' as ac', 'c.ac_id = ac.id', 'left');
        $this->db->join(AGENCY . ' as a', 'a.id = c.agency_id');
        $this->db->join(CATEGORY . ' as sc', 'sc.id = c.category_id');
        $this->db->where('c.isactive', 1);
        $this->db->where('a.capex', 1);

        if (!empty($district_id)) {
            $this->db->where('c.district_id', $district_id);
        }
        if (!empty($category_id)) {
            $this->db->where('c.category_id', $category_id);
        }
        if (!empty($type_id)) {
            $this->db->where('c.work_type_id', $type_id);
        }

        if (!empty($agency)) {
            $this->db->where('c.agency', $agency);
        }
        $this->db->order_by('c.name');

        $query = $this->db->get();
        return $query->result();
    }

    function get_scheme_info($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get(CAPEX);
        //   echo  $this->db->last_query($query); exit;
        return $query->row();
    }

    function save($data)
    {
        $this->db->trans_start();

        // Get Assembly Constituency name
        $this->db->select('name');
        $this->db->where('id', $data['ac_id']);
        $ac = $this->db->get('assembly_constituency')->row();
        $ac_name = $ac ? $ac->name : null;

        // Get Agency name
        $this->db->select('name');
        $this->db->where('id', $data['agency_id']);
        $agency = $this->db->get('scheme_agency')->row();
        $agency_name = $agency ? $agency->name : null;

        // Get Type name
        $this->db->select('name');
        $this->db->where('id', $data['type_id']);
        $type = $this->db->get('scheme_type')->row();
        $type_name = $type ? $type->name : null;

        // Prepare insert/update data
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'name' => $data['name'],
            'sanctioned_cost' => $data['sanctioned_cost'],
            'category_id' => $data['category_id'],
            'type_id' => $data['type_id'],
            'road_type_id' => !empty($data['road_type_id']) ? $data['road_type_id'] : null,
            'road_type_name' => $type_name,
            'work_type_id' => !empty($data['type_id']) ? $data['type_id'] : null,
            'note' => !empty($data['note']) ? $data['note'] : null,
            'length' => $data['length'],
            'unit' => $data['unit'],
            'agency_id' => $data['agency_id'],
            'agency' => $agency_name,
            'admin_no' => $data['admin_no'],
            'admin_date' => !empty($data['admin_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))) : null,
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'ac_id' => $data['ac_id'],
            'ac' => $ac_name,
            'survey_status' => 1,
        );

        // Insert or Update
        if (!empty($data['id']) && (int)$data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('capex', $input);
            $id = $data['id'];
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert('capex', $input);
            $id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        return $id;
    }
    // #################    Tender   ########################
    function get_tender_list($district_id, $category_id, $type_id)
    {
        $session = $this->common->get_session();
        $agency = $this->agency_role_filter($session['role_id']);

        $this->db->select('c.id, d.name as district, CONCAT(ac.no, " ", ac.name, " ", IF((ac.reserved IS NULL OR ac.reserved = ""), "", CONCAT("(", ac.reserved, ")"))) as ac, b.name as block, c.name, c.length, c.agency, c.tender_number, c.tender_publication_date, c.tender_status, c.sanctioned_cost, c.scheme_id, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status ');

        $this->db->from(CAPEX . ' AS c');

        $this->db->join('(SELECT * FROM capex_tender_log tl JOIN (SELECT capex_id as c_capex_id, MAX(id) as max_id FROM capex_tender_log GROUP BY capex_id ) mtl ON tl.id = mtl.max_id) as t', 'c.id = t.capex_id', 'left');

        $this->db->join(DIVISION . ' d', 'c.district_id = d.id');
        $this->db->join(DIVISION . ' b', 'c.block_id = b.id');
        $this->db->join(AC . ' ac', 'c.ac_id = ac.id', 'left');

        $this->db->where('c.isactive', 1);

        if ($district_id > 0) {
            $this->db->where('c.district_id', $district_id);
        }

        if ($category_id > 0) {
            $this->db->where('c.category_id', $category_id);
        }

        if ($type_id > 0) {
            $this->db->where('c.work_type_id', $type_id);
        }

        // Role-based filtering
        if (!empty($agency)) {
            $this->db->where('c.agency', $agency);
        }

        $query = $this->db->get();
        return $query->result();
    }

    function get_tender_info($id)
    {
        $this->db->select('d.name AS district, b.name AS block, c.name, c.agency, c.length, c.scheme_id, c.tender_number, c.tender_publication_date, c.tender_end_date, c.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status,
        t.bid_opening_status, t.bid_matured_status ');

        $this->db->from(CAPEX . ' AS c');

        $subquery = "(SELECT tl.* FROM capex_tender_log tl INNER JOIN (SELECT capex_id AS c_capex_id, MAX(id) AS max_id FROM capex_tender_log GROUP BY capex_id) mtl ON tl.id = mtl.max_id ) AS t ";
        $this->db->join($subquery, 'c.id = t.capex_id', 'left');

        $this->db->join(DIVISION . ' AS d', 'c.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' AS b', 'c.block_id = b.id', 'left');

        $this->db->where('c.id', $id);

        $query = $this->db->get();
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
        $this->db->update(CAPEX, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'capex_id' => $data['id'],
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
        $this->db->insert(CAPEX_TENDER_LOG, $input);


        $sql = "UPDATE capex_tender_log
            SET tender_status = CASE
                WHEN bid_matured_status = 1 THEN 2
                WHEN bid_matured_status = 0 THEN 3
                WHEN evaluation_status = 1 OR bid_opening_status = 1 THEN 1
                ELSE tender_status
            END";
        $this->db->query($sql);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    // ###############   Work Order   #################

    function get_wo_list($district_id = 0, $category_id = 0, $type_id = 0)
    {
        $session = $this->common->get_session();
        $agency = $this->agency_role_filter($session['role_id']);

        $this->db->select('d.name AS district, b.name AS block, ac.name AS ac, c.name, c.agency, c.tender_number,
        DATE_FORMAT(c.tender_publication_date, "%d/%m/%Y") AS tender_publication_date, DATE_FORMAT(c.tender_end_date, "%d/%m/%Y") AS tender_end_date, wo.id, c.id AS capex_id, wo.wo_no, DATE_FORMAT(wo.wo_date, "%d/%m/%Y") AS wo_date, wo.contractor, DATE_FORMAT(wo.completion_date, "%d/%m/%Y") AS completion_date, wo.mobile, wo.document');

        // Apply filters if provided
        if ($district_id != 0) {
            $this->db->where('c.district_id', $district_id);
        }
        if ($category_id != 0) {
            $this->db->where('c.category_id', $category_id);
        }
        if ($type_id != 0) {
            $this->db->where('c.work_type_id', $type_id);
        }

        $this->db->where([
            'c.tender_status' => 2,
            'c.isactive' => 1
        ]);
        if (!empty($agency)) {
            $this->db->where('c.agency', $agency);
        }

        $this->db->from(CAPEX . ' AS c');
        $this->db->join(DIVISION . ' AS d', 'c.district_id = d.id');
        $this->db->join(DIVISION . ' AS b', 'c.block_id = b.id');
        $this->db->join(CATEGORY . ' AS sc', 'c.category_id = sc.id');
        $this->db->join(CAPEX_WO . ' AS wo', 'wo.capex_id = c.id', 'left');
        $this->db->join(AC . ' AS ac', 'c.ac_id = ac.id');

        $this->db->group_by(array('wo.id', 'c.id', 'c.name', 'd.name', 'b.name', 'ac.name', 'c.agency', 'c.tender_number', 'c.tender_publication_date', 'c.tender_end_date', 'wo.wo_no', 'wo.wo_date', 'wo.contractor', 'wo.completion_date', 'wo.mobile', 'wo.document'));
        $this->db->order_by('d.name');
        $query = $this->db->get();
        // print_r($query);exit;
        return $query->result();
    }

    function get_wo_info($id)
    {
        $this->db->select('wo.id, c.id as capex_id, c.name, c.district_id, wo.wo_no, '
            . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
            . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
            . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
            . 'wo.dlp_submitted, wo.document, wo.assigned_engineer, wo.designation, wo.mobile');
        $this->db->where(array(
            'c.id' => $id
        ));
        $this->db->join(CAPEX_WO . ' AS wo', 'wo.capex_id=c.id and wo.isactive=1', 'left');
        $query = $this->db->get(CAPEX  . ' AS c');
        return $query->row();
    }


    function wo_save($data)
    {
        $this->db->trans_start();

        $capex_id = !empty($data['capex_id']) ? (int)$data['capex_id'] : 0;
        $id = 0;

        if ($capex_id > 0 && array_key_exists('wo_no', $data)) {

            $this->db->select('id, mobile');
            $this->db->where('capex_id', $capex_id);
            $query = $this->db->get(CAPEX_WO);

            if ($query->num_rows() > 0) {
                $id = $query->row()->id;
            }

            $input = array(
                'capex_id' => $capex_id,
                'wo_no' => $data['wo_no'],
                'wo_date' => !empty($data['wo_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))) : null,
                'contractor' => $data['contractor'],
                'pan_no' => $data['pan_no'],
                'rate' => $data['rate'],
                'awarded_cost' => $data['awarded_cost'],
                'completion_date' => !empty($data['completion_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['completion_date']))) : null,
                'barchart_given' => $data['barchart_given'],
                'ps_cost' => !empty($data['ps_cost']) ? $data['ps_cost'] : null,
                'lapse_date' => !empty($data['lapse_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['lapse_date']))) : null,
                'additional_ps_cost' => !empty($data['additional_ps_cost']) ? $data['additional_ps_cost'] : null,
                'dlp' => !empty($data['dlp']) ? $data['dlp'] : 0,
                'dlp_period' => $data['dlp_period'],
                'dlp_submitted' => $data['dlp_submitted'],
                'isactive' => 1,
                'islocked' => 1
            );

            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->update(CAPEX_WO, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(CAPEX_WO, $input);
                $id = $this->db->insert_id();
            }

            if (!empty($data['mobile'])) {
                $this->db->select('user_id');
                $this->db->where('username', $data['mobile']);
                $user = $this->db->get(LOGIN)->row();

                if ($user) {
                    $this->db->where('id', $capex_id);
                    $this->db->update('capex', ['pe_user_id' => $user->user_id]);
                }
            }
            $capex_update = array(
                'work_order' => $data['wo_no'],
                'wo_date' => !empty($data['wo_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))) : null,
                'wo_status' => 2
            );

            $this->db->where('id', $capex_id);
            $this->db->update(CAPEX, $capex_update);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return -1;
        }

        return $id;
    }

    function wo_remove($id)
    {
        $this->db->trans_start();
        $this->db->where('capex_id', $id);
        $this->db->update(CAPEX_WO, ['isactive' => -1]);

        $capex_update = array(
            'work_order' => null,
            'wo_date'    => null,
            'wo_status'  => 0
        );
        $this->db->where('id', $id);
        $this->db->update(CAPEX, $capex_update);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    function get_wp_list($status)
    {
        $session = $this->common->get_session();

        $this->db->select('c.id, d.name AS district, b.name AS block, c.name, c.wo_start_date, c.physical_progress, c.progress_remarks, c.pp_status, c.scheme_id AS ref_no');
        $this->db->from(CAPEX . ' AS c');
        $this->db->join(DIVISION . ' AS b', 'c.block_id = b.id');
        $this->db->join(DIVISION . ' AS d', 'c.district_id = d.id');

        $this->db->where(['c.isactive' => 1, 'c.wo_status' => 2]);

        if (!empty($session['district_id']) && $session['district_id'] > 0) {
            $this->db->where('r.district_id', $session['district_id']);
        }

        if (in_array($status, [0, 5])) {
            $this->db->where('c.pp_status', $status);
        } elseif (in_array($status, [1, 2, 3, 4])) {
            $this->db->where('c.pp_status >=', 1);
            $this->db->where('c.pp_status <=', 4);
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
            . 'FROM capex s JOIN division b ON s.block_id=b.id  ' . $where;
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
            'capex_id' => $data['id'],
            'physical_progress' => $progress,
            'progress_remarks' => $data['remarks'],
            'pp_status' => $pp_status
        );
        // print_r($input); exit;
        if (array_key_exists('wp_start_date', $data)) {
            $input['wo_start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_start_date'])));
        }
        $this->db->insert(CAPEX_PROGRESS, $input);
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
        $this->db->update(CAPEX, $input);
        return $id;
    }


    function get_wp_image($capex_id)
    {
        $this->db->select('c.name as name, cp.id, cp.capex_id, cp.physical_progress, cp.pp_status, cp.image1, cp.image2, cp.image3, cp.progress_remarks');
        $this->db->from(CAPEX_PROGRESS . ' as cp');
        $this->db->join(CAPEX . ' as c', 'cp.capex_id = c.id', 'left');
        $this->db->where('cp.capex_id', $capex_id);
        $this->db->order_by('cp.id', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }








    // ###################    Bridge     #############


    function get_bridge_listfff($district_id = null, $category_id = null, $type_id = null)
    {
        $session = $this->common->get_session();
        $agency = $this->agency_role_filter($session['role_id']);
        $this->db->select('c.id, c.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac,
        a.name as agency, c.category_id, sc.subcategory as funding, c.scheme_id, c.length,
        c.sanctioned_cost, c.admin_date, c.admin_no, c.road_type_name as work_type,
        c.length, c.isactive, c.note, c.survey_status, c.tender_status, c.wo_status');

        $this->db->from(CAPEX_BRIDGE . ' as c');
        $this->db->join(DIVISION . ' as d', 'd.id = c.district_id');
        $this->db->join(DIVISION . ' as b', 'b.id = c.block_id');
        $this->db->join(AC . ' as ac', 'c.ac_id = ac.id', 'left');
        $this->db->join(AGENCY . ' as a', 'a.id = c.agency_id');
        $this->db->join(CATEGORY . ' as sc', 'sc.id = c.category_id');
        $this->db->where('c.isactive', 1);
        $this->db->where('a.capex', 1);

        if (!empty($district_id)) {
            $this->db->where('c.district_id', $district_id);
        }
        if (!empty($category_id)) {
            $this->db->where('c.category_id', $category_id);
        }
        if (!empty($type_id)) {
            $this->db->where('c.work_type_id', $type_id);
        }

        if (!empty($agency)) {
            $this->db->where('c.agency', $agency);
        }
        $this->db->order_by('c.name');

        $query = $this->db->get();
        return $query->result();
    }

    function get_bridge_list($district_id = null, $category_id = null, $type_id = null)
    {
        $this->db->select('r.id, r.name, d.name as district, b.name as block, ac.id as ac_id, ac.name as ac, a.name as agency, r.category_id, sc.subcategory as funding, r.scheme_id, r.length, r.sanctioned_cost, r.admin_date, r.admin_no, r.road_type_name as work_type, r.isactive, r.note, r.survey_status, r.tender_status, r.wo_status');
        $this->db->from(CAPEX_BRIDGE . ' as r');
        $this->db->join('division d', 'd.id = r.district_id');
        $this->db->join('division b', 'b.id = r.block_id');
        $this->db->join('assembly_constituency ac', 'r.ac_id = ac.id', 'left');
        $this->db->join('scheme_agency a', 'a.id = r.agency_id');
        $this->db->join('scheme_category sc', 'sc.id = r.category_id');
        $this->db->where('r.isactive', 1);
        $this->db->where('a.capex', 1);

        if (!empty($district_id)) {
            $this->db->where('r.district_id', $district_id);
        }
        if (!empty($category_id)) {
            $this->db->where('r.category_id', $category_id);
        }
        if (!empty($type_id)) {
            $this->db->where('r.work_type_id', $type_id);
        }

        $this->db->order_by('r.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }



    function get_bridge_scheme_info($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get(CAPEX_BRIDGE);
        //   echo  $this->db->last_query($query); exit;
        return $query->row();
    }

    function bridge_save($data)
    {
        $this->db->trans_start();

        // Get Assembly Constituency name
        $this->db->select('name');
        $this->db->where('id', $data['ac_id']);
        $ac = $this->db->get('assembly_constituency')->row();
        $ac_name = $ac ? $ac->name : null;

        // Get Agency name
        $this->db->select('name');
        $this->db->where('id', $data['agency_id']);
        $agency = $this->db->get('scheme_agency')->row();
        $agency_name = $agency ? $agency->name : null;

        // Get Type name
        $this->db->select('name');
        $this->db->where('id', $data['type_id']);
        $type = $this->db->get('scheme_type')->row();
        $type_name = $type ? $type->name : null;

        // Prepare insert/update data
        $input = array(
            'scheme_id' => $data['scheme_id'],
            'name' => $data['name'],
            'sanctioned_cost' => $data['sanctioned_cost'],
            'category_id' => $data['category_id'],
            'type_id' => $data['type_id'],
            'road_type_id' => !empty($data['road_type_id']) ? $data['road_type_id'] : null,
            'road_type_name' => $type_name,
            'work_type_id' => !empty($data['type_id']) ? $data['type_id'] : null,
            'note' => !empty($data['note']) ? $data['note'] : null,
            'length' => $data['length'],
            'unit' => $data['unit'],
            'agency_id' => $data['agency_id'],
            'agency' => $agency_name,
            'admin_no' => $data['admin_no'],
            'admin_date' => !empty($data['admin_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))) : null,
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'ac_id' => $data['ac_id'],
            'ac' => $ac_name,
            'survey_status' => 6,
        );

        // Insert or Update
        if (!empty($data['id']) && (int)$data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('capex_bridge', $input);
            $id = $data['id'];
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert('capex_bridge', $input);
            $id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        // Check transaction status
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        }

        return $id;
    }

    function get_bridge_tender_list($district_id, $category_id, $type_id)
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
        $this->db->join('(SELECT * FROM capex_bridge_tender_log tl JOIN (SELECT capex_bridge_id as c_bridge_id, MAX(id) as max_id FROM capex_bridge_tender_log GROUP BY capex_bridge_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.capex_bridge_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id', 'left');
        $query = $this->db->get(CAPEX_BRIDGE . ' s');
        return $query->result();
    }

    function get_bridge_tender_info($id)
    {
        $this->db->select('d.name as district, b.name as block, r.name, r.agency, r.length,r.scheme_id, r.tender_number, r.tender_publication_date, r.tender_end_date, r.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where('r.id', $id);
        $this->db->join('(SELECT * FROM capex_bridge_tender_log tl JOIN (SELECT capex_bridge_id as c_bridge_id, MAX(id) as max_id FROM capex_bridge_tender_log GROUP BY capex_bridge_id) mtl ON tl.id = mtl.max_id)' . ' t', 'r.id=t.capex_bridge_id', 'left');
        $this->db->join(DIVISION . ' d', 'r.district_id=d.id');
        $this->db->join(DIVISION . ' b', 'r.block_id=b.id');
        $query = $this->db->get(CAPEX_BRIDGE . ' r');
        return $query->row();
    }

    function bridge_tender_save($data)
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
        $this->db->update('capex_bridge', $input);
        $input = array(
            'created' => date('Y-m-d'),
            'capex_bridge_id' => $data['id'],
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
        $this->db->insert(CAPEX_BRIDGE_TENDER_LOG, $input);

        $sql = "UPDATE capex_bridge_tender_log
            SET tender_status = CASE
                WHEN bid_matured_status = 1 THEN 2
                WHEN bid_matured_status = 0 THEN 3
                WHEN evaluation_status = 1 OR bid_opening_status = 1 THEN 1
                ELSE tender_status
            END";
        $this->db->query($sql);


        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

   function get_bridge_wo_list($district_id = 0, $category_id = 0, $type_id = 0)
    {
        $session = $this->common->get_session();
        $this->db->select('wo.id, d.name AS district, b.name AS block, ac.name as ac, r.name, r.agency, r.tender_number, DATE_FORMAT(r.tender_publication_date, "%d/%m/%Y") AS tender_publication_date, 
            DATE_FORMAT(r.tender_end_date, "%d/%m/%Y") AS tender_end_date, wo.id, r.id AS capex_bridge_id, wo.wo_no, DATE_FORMAT(wo.wo_date, "%d/%m/%Y") AS wo_date, wo.contractor, 
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
        $this->db->join(CAPEX_BRIDGE_WO . ' AS wo', 'wo.capex_bridge_id = r.id', 'left');
        $this->db->join(AC . ' ac', 'r.ac_id = ac.id', 'left');
        //$this->db->group_by(['wo.id', 's.id', 's.name', 'd.name', 'wo.wo_no', 'wo.wo_date', 'wo.contractor', 'wo.completion_date']);
        $this->db->order_by('d.name, b.name');
        $query = $this->db->get(CAPEX_BRIDGE . ' r');
        return $query->result();
    }

    function get_bridge_wo_info($id)
    {
        $this->db->select('wo.id, sr.id as capex_bridge_id, sr.name, sr.district_id, wo.wo_no, '
            . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
            . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
            . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
            . 'wo.dlp_submitted, wo.document');
        $this->db->where(array(
            'sr.id' => $id
        ));
        $this->db->join('capex_bridge_wo' . ' wo', 'wo.capex_bridge_id=sr.id and wo.isactive=1', 'left');
        $query = $this->db->get('capex_bridge' . ' sr');
        return $query->row();
    }
}
