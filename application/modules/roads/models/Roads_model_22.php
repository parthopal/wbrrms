<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Roads_Model extends CI_Model
{

    // function get_dashboard_count()
    // {
    //     $session = $this->common->get_session();
    //     $this->db->where('s.survey_status', 6);
    //     $this->db->where('s.isactive', 1);
    //     if (!empty($session['district_id']) && $session['district_id'] > 0) {
    //         if (is_string($session['district_id']) && strpos($session['district_id'], ',') !== false) {
    //             $districts = array_map('trim', explode(',', $session['district_id']));
    //             $this->db->where_in('s.district_id', $districts);
    //         } else {
    //             $this->db->where('s.district_id', (int)$session['district_id']);
    //         }
    //     }

    //     if (!empty($session['block_id']) && $session['block_id'] > 0) {
    //         if (is_string($session['block_id']) && strpos($session['block_id'], ',') !== false) {
    //             $blocks = array_map('trim', explode(',', $session['block_id']));
    //             $this->db->where_in('s.block_id', $blocks);
    //         } else {
    //             $this->db->where('s.block_id', (int)$session['block_id']);
    //         }
    //     }

    //     $agencyFilter = $this->filter_with_agency();
    //     if (!empty($agencyFilter)) {
    //         if (is_string($agencyFilter)) {
    //             $this->db->where($agencyFilter);
    //         } elseif (is_array($agencyFilter)) {
    //             $this->db->where($agencyFilter);
    //         }
    //     }
    //     $progress_subquery = "SELECT 
    //         s.district_id,
    //         SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
    //         SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
    //         SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,
    //         SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
    //         SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
    //         SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,
    //         SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
    //         SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
    //         SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,
    //         SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
    //         ROUND(SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_99_length,
    //         IFNULL(SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END),0) AS progress_99_amount,
    //         SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
    //         ROUND(SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_100_length,
    //         IFNULL(SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END),0) AS progress_100_amount
    //     FROM roads s
    //     LEFT JOIN roads_wo wo ON wo.roads_id = s.id
    //     WHERE s.survey_status = 6 AND s.isactive = 1
    //     GROUP BY s.district_id ";

    //     // Main select
    //     $this->db->select("
    //     SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
    //     SUM(s.bt_length + s.cc_length) AS approved_length,
    //     SUM(s.cost) AS approved_amount,
    //     (SUM(s.cost) * 0.18) AS gst_18_percent,
    //     ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
    //     ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,
    //     (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,
    //     ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,
    //     ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,
    //     SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
    //     SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
    //     SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
    //     SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
    //     SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

    //     IFNULL(p.progress_25,0) AS progress_25,
    //     IFNULL(p.progress_25_length,0) AS progress_25_length,
    //     IFNULL(p.progress_25_amount,0) AS progress_25_amount,
    //     IFNULL(p.progress_50,0) AS progress_50,
    //     IFNULL(p.progress_50_length,0) AS progress_50_length,
    //     IFNULL(p.progress_50_amount,0) AS progress_50_amount,
    //     IFNULL(p.progress_75,0) AS progress_75,
    //     IFNULL(p.progress_75_length,0) AS progress_75_length,
    //     IFNULL(p.progress_75_amount,0) AS progress_75_amount,
    //     IFNULL(p.progress_99, 0) AS progress_99,
    //     IFNULL(p.progress_99_length, 0) AS progress_99_length,
    //     IFNULL(p.progress_99_amount, 0) AS progress_99_amount,
    //     IFNULL(p.progress_100,0) AS progress_100,
    //     IFNULL(p.progress_100_length,0) AS progress_100_length,
    //     IFNULL(p.progress_100_amount,0) AS progress_100_amount,

    //     ( IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0) ) AS ongoing,
    //     ( IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0) ) AS ongoing_length,
    //     ( IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0) ) AS ongoing_amount,

    //     IFNULL(p.progress_100,0) AS completed,
    //     IFNULL(p.progress_100_length,0) AS completed_length,
    //     IFNULL(p.progress_100_amount,0) AS completed_amount ");

    //     $this->db->from('roads s');
    //     $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
    //     $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
    //     $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

    //     // Join the progress subquery safely
    //     $this->db->join("($progress_subquery) p", 's.district_id = p.district_id', 'left');

    //     // // Group by district to match aggregations
    //     // $this->db->group_by('s.district_id');

    //     $query = $this->db->get();
    //     return $query->row();
    // }


    function get_dashboard_count()
    {
        $session = $this->common->get_session();
        $this->db->where('s.survey_status', 6);
        $this->db->where('s.isactive', 1);

        // Apply district filters
        if (!empty($session['district_id']) && $session['district_id'] > 0) {
            if (is_string($session['district_id']) && strpos($session['district_id'], ',') !== false) {
                $districts = array_map('trim', explode(',', $session['district_id']));
                $this->db->where_in('s.district_id', $districts);
            } else {
                $this->db->where('s.district_id', (int)$session['district_id']);
            }
        }

        // Apply block filters
        if (!empty($session['block_id']) && $session['block_id'] > 0) {
            if (is_string($session['block_id']) && strpos($session['block_id'], ',') !== false) {
                $blocks = array_map('trim', explode(',', $session['block_id']));
                $this->db->where_in('s.block_id', $blocks);
            } else {
                $this->db->where('s.block_id', (int)$session['block_id']);
            }
        }

        if ($session['role_id'] == 12) {
            // Apply fixed filter for role_id = 12
            $this->db->where_in('s.agency', ['ZP', 'BLOCK']);
        } else {
            // Apply dynamic filter for other roles
            $agencyFilter = $this->filter_with_agency(); // use only ONE function
            if (!empty($agencyFilter)) {
                if (is_string($agencyFilter)) {
                    $this->db->where($agencyFilter);
                } elseif (is_array($agencyFilter)) {
                    $this->db->where($agencyFilter);
                }
            }
        }


        // Progress subquery
        $progress_subquery = "
        SELECT 
            s.district_id,
            SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
            SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
            SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,
            SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
            SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
            SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,
            SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
            SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
            SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,
            SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
            ROUND(SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_99_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END),0) AS progress_99_amount,
            SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
            ROUND(SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_100_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END),0) AS progress_100_amount
        FROM roads s
        LEFT JOIN roads_wo wo ON wo.roads_id = s.id
        WHERE s.survey_status = 6 AND s.isactive = 1
        GROUP BY s.district_id
    ";

        // New: Tender matured subquery
        $tender_matured_subquery = "
        SELECT 
            s.district_id,
            COUNT(DISTINCT s.id) AS tender_matured_count,
            IFNULL(SUM(s.bt_length + s.cc_length), 0) AS tender_matured_length,
            IFNULL(SUM(s.cost), 0) AS tender_matured_cost
        FROM roads s
        JOIN roads_tender_log tl ON tl.roads_id = s.id
        WHERE s.survey_status = 6 
          AND s.isactive = 1
          AND s.tender_number IS NOT NULL 
          AND tl.bid_matured_status = 1
        GROUP BY s.district_id
    ";

        // Main select
        $this->db->select("
        SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
        SUM(s.bt_length + s.cc_length) AS approved_length,
        SUM(s.cost) AS approved_amount,
        (SUM(s.cost) * 0.18) AS gst_18_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,
        (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,

        SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
        SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
        SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
        SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
        SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

        IFNULL(p.progress_25,0) AS progress_25,
        IFNULL(p.progress_25_length,0) AS progress_25_length,
        IFNULL(p.progress_25_amount,0) AS progress_25_amount,
        IFNULL(p.progress_50,0) AS progress_50,
        IFNULL(p.progress_50_length,0) AS progress_50_length,
        IFNULL(p.progress_50_amount,0) AS progress_50_amount,
        IFNULL(p.progress_75,0) AS progress_75,
        IFNULL(p.progress_75_length,0) AS progress_75_length,
        IFNULL(p.progress_75_amount,0) AS progress_75_amount,
        IFNULL(p.progress_99, 0) AS progress_99,
        IFNULL(p.progress_99_length, 0) AS progress_99_length,
        IFNULL(p.progress_99_amount, 0) AS progress_99_amount,
        IFNULL(p.progress_100,0) AS progress_100,
        IFNULL(p.progress_100_length,0) AS progress_100_length,
        IFNULL(p.progress_100_amount,0) AS progress_100_amount,

        ( IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0) ) AS ongoing,
        ( IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0) ) AS ongoing_length,
        ( IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0) ) AS ongoing_amount,

        IFNULL(p.progress_100,0) AS completed,
        IFNULL(p.progress_100_length,0) AS completed_length,
        IFNULL(p.progress_100_amount,0) AS completed_amount,

        IFNULL(tm.tender_matured_count,0) AS tender_matured_count,
        IFNULL(tm.tender_matured_length,0) AS tender_matured_length,
        IFNULL(tm.tender_matured_cost,0) AS tender_matured_cost
    ");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id', 'left');
        $this->db->join("($tender_matured_subquery) tm", 's.district_id = tm.district_id', 'left');

        $query = $this->db->get();
        return $query->row();
    }






    function get_district_wise_count()
    {
        $query = $this->db
            ->select('
            d.name AS district_name,
            COUNT(s.id) AS total_roads,
            SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS survey_completed_roads,
            ROUND((SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) / COUNT(s.id)) * 100, 2) survey_completed
        ')
            ->from('roads s')
            ->join(DIVISION . ' d', 's.district_id = d.id', 'left')
            ->where('s.isactive', 1)
            ->group_by('s.district_id')
            ->order_by('d.name', 'ASC')
            ->get();

        return $query->result_array();
    }


    function get_survey_complete()
    {
        $query = $this->db->select('
                d.name,
                COUNT(s.id) AS total_roads
            ')
            ->from('roads s')
            ->join(DIVISION . ' d', 's.district_id = d.id', 'left')
            ->join(DIVISION . ' b', 's.block_id = b.id', 'left')
            ->join(DIVISION . ' gp', 'FIND_IN_SET(gp.id, s.gp_id)', 'left')
            ->join(AC . ' ac', 's.ac_id = ac.id', 'left')
            ->where('s.isactive = 1 and survey_status = 6')
            ->group_by('s.district_id')
            ->order_by('d.name', 'ASC')
            ->get();

        return $query->result_array();
    }


    function get_tender_and_wo_count()
    {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'and district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $where .= $this->filter_with_agency();
        $sql = "SELECT COUNT(id) as tender_matured, ifnull(SUM(length),0) as tender_matured_length, SUM(cost) as tender_amount FROM roads "
            . "WHERE isactive = 1 AND tender_status = 2 " . $where;
        $query = $this->db->query($sql);
        $tender_matured_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_order, ifnull(SUM(s.length),0) as work_order_length, SUM(wo.awarded_cost) as sanctioned_amount FROM roads s JOIN roads_wo wo ON wo.roads_id = s.id "
            . "WHERE s.isactive = 1 AND s.wo_status = 2 " . $where;
        $query = $this->db->query($sql);
        $work_order_count = $query->row();

        $sql = "SELECT COUNT(s.id) as work_progress, ifnull(SUM(s.length),0) as work_progress_length, SUM(wo.awarded_cost) as sanctioned_amount "
            . "FROM roads s JOIN roads_wo wo ON wo.roads_id = s.id WHERE s.isactive = 1 AND s.wo_status = 2 AND s.pp_status = 5 " . $where;
        $query = $this->db->query($sql);
        $work_order_progress_count = $query->row();
        return array(
            'tender_matured_count' => $tender_matured_count,
            'work_order_count' => $work_order_count,
            'work_order_progress_count' => $work_order_progress_count
        );
    }





    function get_survey_list($district_id, $status = 0)
    {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, '
            . 'concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved = ""), "", concat("(", ac.reserved, ")"))) as ac, '
            . 'b.name as block, GROUP_CONCAT(gp.name) as gp, s.village, s.ref_no, s.name, s.agency, s.work_type, s.road_type, s.new_road_type, s.new_length, s.bt_length, s.cc_length, s.approved_length, s.proposed_length, s.length, s.survey_status, s.tender_status, s.wo_status, s.pp_status, s.cost, s.gst, s.cess, s.contigency_amt, s.estimated_amt, '
            . 's.survey_estimated_doc, s.survey_lot_no, s.survey_lot_doc, s.dm_lot_no, s.dm_lot_doc, s.se_lot_no, s.se_lot_doc, s.sa_lot_no, s.admin_approval_doc, s.isactive, ni.remarks');
        $this->db->where(array(
            's.district_id' => $district_id
        ));
        if (!empty($block_id) && (int)$block_id > 0) {
            $this->db->where('s.block_id', (int)$block_id);
        } elseif (!empty($session['block_id'])) {
            $block_ids = array_filter(array_map('trim', explode(',', $session['block_id'])), 'is_numeric');
            if (!empty($block_ids) && $block_ids[0] != 0) {
                $this->db->where_in('s.block_id', $block_ids);
            }
        } else {
        }

        if ($session['role_id'] == 12) {
            $this->db->where_in('s.agency', ['ZP', 'BLOCK']);
        } else {
            $agencyFilter = $this->filter_with_agencys();
            if (!empty($agencyFilter)) {
                $this->db->where($agencyFilter);
            }
        }
        switch ($status) {
            case 0:
                $this->db->where('s.survey_status < 6');
                $this->db->where('s.isactive', 1);
                break;
            case 1:
                $this->db->where('s.survey_status', 6);
                $this->db->where('s.isactive', 1);
                break;
            case -1:
                $this->db->where('s.isactive', -1);
                break;
            default:
                break;
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id', 'left');
        // $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id', 'left');
        $this->db->join(DIVISION . ' gp', 'FIND_IN_SET(gp.id, s.gp_id)', 'left');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id', 'left');
        $this->db->join(ROADS_NOT_IMPLEMENTED_LOG . ' ni', 'ni.roads_id=s.id', 'left');
        $this->db->group_by('s.id');
        $this->db->order_by('b.name');
        $query = $this->db->get(ROADS . ' s'); //echo $this->db->last_query(); exit;
        return $query->result();
    }



    function get_ac_list($district_id = 0)
    {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('a.id, CONCAT(a.no, " ", a.name, " ", IF((a.reserved IS NULL OR a.reserved = ""), "", CONCAT("(", a.reserved, ")"))) AS name');
        $this->db->join(ROADS . ' s', 's.ac_id = a.id', 'left');
        $this->db->where('a.isactive', 1); // filter on ac table itself

        if ($session['district_id'] > 0) {
            $this->db->where('a.district_id', $session['district_id']);
        } else if ($district_id > 0) {
            $this->db->where('a.district_id', $district_id);
        }

        $this->db->order_by('a.id');
        $query = $this->db->get(AC . ' a');
        return $query->result();
    }


    // function get_road_type(){
    //     $session = $this->common->get_session();

    // }

    function get_scheme_info($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get(ROADS);
        return $query->row();
    }


    function get_survey_pending_list($district_id, $block_id, $ac_id)
    {
        $session = $this->common->get_session();

        // Select fields
        $this->db->select('s.id, 
        d.name as district, 
        CONCAT(ac.no, " ", ac.name, " ", IF((ac.reserved IS NULL OR ac.reserved=""), "", CONCAT("(", ac.reserved, ")"))) as ac, 
        b.name as block, 
        gp.name as gp, 
        s.ref_no, s.name, s.new_road_type, s.new_length, s.bt_length, s.cc_length, s.length, s.proposed_length, s.road_type, s.work_type, s.agency, 
        s.survey_status as status, s.cost, s.gst, s.cess, s.dpr_amt, s.contigency_amt, s.estimated_amt, s.return_cause, s.survey_estimated_doc');

        // Base where conditions
        $this->db->where([
            's.district_id'   => $district_id,
            's.gp_id >'       => 0,
            's.survey_status <' => 3,
            's.isactive'      => 1
        ]);

        // Filter by block
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if (!empty($session['block_id'])) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        }

        // Filter by assembly
        if ($ac_id > 0) {
            $this->db->where('s.ac_id', $ac_id);
        }

        // Robust agency filter
        $roleAgencyMap = [
            13 => 'ZP',
            14 => 'BLOCK',
            15 => 'SRDA',
            16 => 'MBL',
            17 => 'AGRO',
        ];

        if (isset($roleAgencyMap[$session['role_id']])) {
            $agency = $roleAgencyMap[$session['role_id']];
            $this->db->where("TRIM(UPPER(s.agency)) =", strtoupper($agency));
        }

        // Survey lot not assigned
        $this->db->where('s.survey_lot_no IS NULL', null, false);

        // Joins
        $this->db->join(DIVISION . ' d', 's.district_id = d.id');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id = gp.id');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id');

        // Execute query
        $query = $this->db->get(ROADS . ' s');

        // Optional: debug SQL
        // log_message('debug', $this->db->last_query());

        return $query->result();
    }


    function get_project_info($id)
    {
        $this->db->select('d.name as district, b.name as block, gp.name as gp, s.name, s.village, s.agency, s.length, s.road_type, s.work_type, s.survey_status as status, s.survey_estimated_doc');
        $this->db->where('s.id', $id);
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get(ROADS . ' s');
        return $query->row();
    }


    function update_proposed_length($id, $value)
    {
        $this->db->where('id', $id);
        return $this->db->update(ROADS, ['proposed_length' => $value]);
    }


    function save($data)
    {
        $session = $this->common->get_session();
        $this->db->trans_start();

        $id = $data['id'];

        // Prepare input
        $input = array(
            'district_id'   => $data['district_id'],
            'ac_id'         => $data['ac_id'],
            'block_id'      => $data['block_id'],
            'gp_id'         => isset($data['gp_id']) ? implode(',', $data['gp_id']) : 0,
            'village'       => $data['village'],
            'work_type'     => $data['work_type'],
            'road_type'     => $data['road_type'],
            'new_road_type' => ($data['new_road_type'] === '' ? null : $data['new_road_type']),
            'new_length'    => ($data['new_length'] === '' ? null : $data['new_length']),
            'bt_length'     => ($data['bt_length'] === '' ? null : $data['bt_length']),
            'cc_length'     => ($data['cc_length'] === '' ? null : $data['cc_length']),
        );

        if ($session['role_id'] < 4) {
            $input['name']   = $data['name'];
            $input['agency'] = $data['agency'];
            $input['length'] = $data['length'];

            // // proposed_length logic
            // if (empty($data['proposed_length']) || $data['proposed_length'] == 0) {
            //     $input['proposed_length'] = $data['length'];
            // }


            $input['approved_length'] = $data['length'];

            if ($data['isapproved'] > 0) {
                $input['cost']               = $data['cost'];
                $input['admin_approval_no']  = $data['admin_no'];
                $input['admin_approval_date'] = $data['admin_date'] != ''
                    ? date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date'])))
                    : null;
            }
        }
        // If approved, set survey status
        if ($data['isapproved'] > 0) {
            $input['survey_status'] = 6;
        }

        // Insert or Update
        if ($id > 0) {
            // Update
            $this->db->where('id', $id);
            $this->db->update(ROADS, $input);

            // Check existing ref_no
            $this->db->select('ref_no');
            $this->db->where('id', $id);
            $refRow = $this->db->get(ROADS)->row();

            if (empty($refRow->ref_no)) {
                $this->_generate_ref_no($id, $data);
            }
        } else {
            // Insert
            $input['created'] = date('Y-m-d');
            $this->db->insert(ROADS, $input);
            $id = $this->db->insert_id();

            $this->_generate_ref_no($id, $data);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }

        return $id;
    }

    /**
     * Generate ref_no and update the record
     */
    function _generate_ref_no($id, $data)
    {
        // Get district name
        $this->db->select('name');
        $this->db->where('id', $data['district_id']);
        $query = $this->db->get(DIVISION);
        $district_name = $query->row()->name;

        // Create ref_no
        if ($data['isapproved'] > 0) {
            $ref_no = str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $data['agency'] . '/' . $district_name . '/RURALROADS/2025';

            // Ensure uniqueness
            $this->db->where('ref_no', $ref_no);
            $query = $this->db->get(ROADS);
            if ($query->num_rows() > 0) {
                $ref_no = str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $data['agency'] . '/' . $district_name . '/RURALROADS/2025';
            }
        } else {
            $ref_no = 'TMP/' . str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT) . '/' . $data['agency'] . '/' . $district_name . '/ROADS/2025';
        }

        // Update ref_no
        $this->db->where('id', $id);
        $this->db->update(ROADS, array('ref_no' => $ref_no));
    }


    function survey_save($data)
    {
        // print_r($data);exit;
        $this->db->trans_start();
        $input = array(
            'survey_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['survey_date']))),
            'length' => $data['length'],
            'survey_status' => $data['status']
        );
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'roads_id' => $data['id'],
            'survey_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['survey_date']))),
            'length' => $data['length'],
            'surveyor_name' => $data['surveyor_name'],
            'surveyor_designation' => $data['surveyor_designation'],
            'surveyor_mobile' => $data['surveyor_mobile'],
            'status' => $data['status']
        );
        $this->db->insert(ROADS_SURVEY_LOG, $input);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }



    public function survey_vec_save($data)
    {
        // --- 1. Convert safely ---
        $vetted = isset($data['cost']) ? $data['cost'] : 0;

        // --- 2. Auto-calculations ---
        $gst = $vetted * 0.18;                  // 18% GST
        $cess = ($vetted + $gst) * 0.01;        // 1% Cess
        $total_estimated = $vetted + $gst + $cess;  // Total = cost + gst + cess
        $contigency_amt = ($vetted + $gst) * 0.03;  // 3% contingency
        $estimated_amt = $total_estimated + $contigency_amt; // Final estimated

        // --- 3. Prepare formatted input (2 decimal precision) ---
        $input = [
            'cost'            => round($vetted, 2),
            'gst'             => round($gst, 2),
            'cess'            => round($cess, 2),
            'total_estimated' => round($total_estimated, 2),
            'contigency_amt'  => round($contigency_amt, 2),
            'estimated_amt'   => round($estimated_amt, 2),
        ];

        // --- 4. Update record ---
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        return $this->db->affected_rows() > 0;
    }




    function survey_dpr_save($data)
    {
        $input = ['dpr_amt' => $data['dpr_amt']];
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        return true;
    }

    function survey_con_save($data)
    {
        $input = ['contigency_amt' => $data['contigency_amt']];
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        return true;
    }

    function survey_est_save($data)
    {
        $input = ['estimated_amt' => $data['estimated_amt']];
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        return true;
    }


    function create_lot_no($role_id, $data, $lotno)
    {
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
            $this->db->update(ROADS, $input);
        }
        return true;
    }

    function forwarded($role_id, $lotno, $path)
    {
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
        $this->db->update(ROADS, $input);
        return true;
    }

    function get_district_list()
    {
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
        $query = $this->db->get(ROADS . ' s');

        return $query->result();
    }

    function get_lotno_list($district_id)
    {
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
        $query = $this->db->get(ROADS);
        return $query->result();
    }

    function get_lot_list($district_id, $lotno)
    {
        $session = $this->common->get_session();
        $role_id = $session['role_id'];
        $lot_ref = ($role_id == 2 || $role_id == 3) ? 's.sa_lot_no' : ($role_id == 7 ? 's.se_lot_no' : ($role_id == 12 ? 's.dm_lot_no' : 's.survey_lot_no'));
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, gp.name as gp, s.ref_no, ' . $lot_ref . ' as lotno, s.name, s.length, s.road_type, s.work_type, s.agency, s.survey_status as status, s.cost, s.gst, s.cess, s.dpr_amt, s.	contigency_amt, s.estimated_amt, s.proposed_length, s.bt_length, s.cc_length, s.new_road_type, s.new_length');
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
        $query = $this->db->get(ROADS . ' s');
        //        echo $this->db->last_query();
        //        exit;
        return $query->result();
    }

    function get_approval_list($district_id, $block_id)
    {
        $session = $this->common->get_session();
        $this->db->select(
            's.id, d.name as district, '
                . 'concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, '
                . 'b.name as block, gp.name as gp, s.ref_no, s.name, s.proposed_length, s.length, s.road_type, s.work_type, s.agency, '
                . 's.survey_status as status, s.survey_estimated_doc, s.cost, s.gst, s.cess, s.contigency_amt, s.dpr_amt, s.estimated_amt, s.return_cause, s.bt_length, s.cc_length, s.new_road_type, s.new_length '
        );
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
        $query = $this->db->get(ROADS . ' s');
        //    echo $this->db->last_query();
        //    exit;
        return $query->result();
    }

    function return_to_prev($data, $msg)
    {
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
                    $sql = 'SELECT agency FROM roads WHERE id=' . $d;
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
            $this->db->update(ROADS, $input);
        }
        return true;
    }

    function remove_survey_list($id)
    {
        $input = array(
            'isactive' => -2
        );
        $this->db->where('id', $id);
        $this->db->update(ROADS, $input);
    }

    function get_scheme_not_implemented($district_id, $block_id)
    {
        $session = $this->common->get_session();
        $this->db->select('nil.ssm_id as id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.road_type, s.work_type, s.agency, s.proposed_length, nil.status_id as status, nil.remarks, nil.created');
        $this->db->where(
            array(
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
        $this->db->join('(SELECT * FROM ssm_not_implemented tnil JOIN (SELECT ssm_id as c_ssm_id, MAX(id) as max_id FROM ssm_not_implemented GROUP BY ssm_id) mtnil ON tnil.id = mtnil.max_id)' . ' nil', 's.id=nil.ssm_id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->order_by('s.id', 'DESC');
        $query = $this->db->get(SSM . ' s');
        return $query->result();
    }

    function delete_not_traceable($id)
    {

        $input['isactive'] = -2;
        $this->db->where('id', $id);
        $this->db->update(SSM, $input);
        return TRUE;
    }

    function get_approved_list($district_id, $block_id, $ac_id = 0)
    {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, gp.name as gp, s.ref_no, s.name, s.proposed_length, s.length, s.bt_length, s.cc_length, s.new_road_type, new_length, '
            . 's.road_type, s.work_type, s.agency, s.survey_status as status, s.cost, s.gst, s.cess, s.ref_no, s.contigency_amt, s.estimated_amt, '
            . 's.admin_approval_date, s.admin_approval_no, s.admin_approval_doc as lot_doc');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.survey_status' => 6,
            's.isactive' => 1
        ));
        if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        } else if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        }
        if ($session['role_id'] == 12) {
            $this->db->where_in('s.agency', ['ZP', 'BLOCK']);
        } else {
            $agencyFilter = $this->filter_with_agencys();
            if (!empty($agencyFilter)) {
                $this->db->where($agencyFilter);
            }
        }
        if ($ac_id > 0) {
            $this->db->where('s.ac_id', $ac_id);
        }

        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get(ROADS . ' s');
        return $query->result();
    }

    function print_lot($district_id, $lotno)
    {
        $this->db->select('d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.ref_no, s.agency, gp.name as gp, s.name, s.proposed_length, s.length, s.work_type, s.road_type,s.new_road_type,new_length, s.cost, s.gst, s.cess, s.estimated_amt, s.contigency_amt, s.dpr_amt, s.bt_length, s.cc_length');
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
        $query = $this->db->get(ROADS . ' s');
        //        echo $this->db->last_query();
        //        exit;
        return $query->result();
    }

    ################################################################################

    function get_state_approval_list($district_id, $block_id)
    {
        $this->db->select('s.id, d.name as district, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.road_type, s.agency, s.survey_status as status');
        $this->db->where(
            array(
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
        $query = $this->db->get(SSM . ' s');
        return $query->result();
    }

    function back_to_inbox($id)
    {
        $input = array(
            'survey_lot_no' => null,
            'survey_lot_doc' => null
        );
        $this->db->where(array(
            'id' => $id,
            'survey_status' => 0
        ));
        $this->db->update(SSM, $input);
    }

    function not_traceable_save($data)
    {
        $this->db->trans_start();
        $input = array(
            'isactive' => '-1'
        );
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'roads_id' => $data['id'],
            'status_id' => $data['status'],
            'remarks' => $data['remarks']
        );
        $this->db->insert(ROADS_NOT_IMPLEMENTED_LOG, $input);
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
    function get_tender_list($district_id, $block_id, $ac_id)
    {
        $session = $this->common->get_session();
        $this->db->select('s.id, d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, gp.name as gp, s.ref_no, s.name, s.length, s.agency, s.survey_status as status, s.cost, s.tender_number, s.tender_publication_date, s.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.survey_status' => 6,
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
        $this->db->join('(SELECT * FROM roads_tender_log tl JOIN (SELECT roads_id as c_roads_id, MAX(id) as max_id FROM roads_tender_log GROUP BY roads_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.roads_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $query = $this->db->get(ROADS . ' s');
        return $query->result();
    }

    function tender_save($data)
    {
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
        $this->db->update(ROADS, $input);
        $input = array(
            'created' => date('Y-m-d'),
            'roads_id' => $data['id'],
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
        $this->db->insert(ROADS_TENDER_LOG, $input);

        //manage for now - sujay
        /* $sql = 'UPDATE ssm_tender_log SET tender_status=2 WHERE bid_matured_status=1';
          $this->db->query($sql);

          $sql = 'UPDATE ssm SET tender_status=2 WHERE id in (SELECT DISTINCT ssm_id FROM ssm_tender_log WHERE bid_matured_status = 1)';
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

    function get_tender_info($id)
    {
        $this->db->select('d.name as district, b.name as block, gp.name as gp,  s.village, s.ref_no, s.name, s.agency, s.length, s.tender_number, s.tender_publication_date, s.tender_end_date, s.tender_status, t.bid_opeaning_date, t.bid_closing_date, t.evaluation_status, t.bid_opening_status, t.bid_matured_status');
        $this->db->where('s.id', $id);
        $this->db->join('(SELECT * FROM roads_tender_log tl JOIN (SELECT roads_id as c_roads_id, MAX(id) as max_id FROM roads_tender_log GROUP BY roads_id) mtl ON tl.id = mtl.max_id)' . ' t', 's.id=t.roads_id', 'left');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $query = $this->db->get(ROADS . ' s');
        return $query->row();
    }

    function tender_benefitted_save($data)
    {
        // var_dump($data);exit;
        $input = array(
            'total_population' => $data['total_population'],
            'total_households' => $data['total_households'],
            'no_of_village' => $data['no_of_village'],
            'tender_status' => 1
        );
        // var_dump($input);exit;
        $this->db->where('id', $data['id']);
        $this->db->update(ROADS, $input);
        return TRUE;
    }

    function get_wo_info($id)
    {
        $this->db->select('wo.id, sr.id as roads_id, sr.name, sr.district_id, wo.wo_no, '
            . 'date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, wo.pan_no, wo.rate, '
            . 'wo.awarded_cost, date_format(wo.completion_date, "%d/%m/%Y") as completion_date, '
            . 'wo.barchart_given, wo.ps_cost, wo.lapse_date, wo.additional_ps_cost, wo.dlp, wo.dlp_period, '
            . 'wo.dlp_submitted, wo.document, wo.assigned_engineer, wo.designation, wo.mobile');
        $this->db->where(array(
            'sr.id' => $id
        ));
        $this->db->join(ROADS_WO . ' wo', 'wo.roads_id=sr.id and wo.isactive=1', 'left');
        $query = $this->db->get(ROADS . ' sr');
        return $query->row();
    }

    function get_wo_list($district_id = 0, $block_id = 0, $ac_id = 0)
    {
        $session = $this->common->get_session();
        $this->db->select('d.name as district, concat(ac.no, " ", ac.name, " ", IF((ac.reserved is null or ac.reserved=""), "", concat("(", ac.reserved, ")"))) as ac, b.name as block, s.name,s.agency, s.tender_number, '
            . 'date_format(s.tender_publication_date, "%d/%m/%Y") as tender_publication_date, date_format(s.tender_end_date, "%d/%m/%Y") as tender_end_date, '
            . 'wo.id, s.id as roads_id, wo.wo_no, date_format(wo.wo_date, "%d/%m/%Y") as wo_date, wo.contractor, '
            . 'date_format(wo.completion_date, "%d/%m/%Y") as completion_date, wo.assigned_engineer, wo.mobile, wo.document');
        $this->db->where(array(
            's.survey_status' => 6,
            's.tender_status' => 2,
            's.isactive' => 1
        ));
        if ($district_id > 0) {
            $this->db->where('s.district_id', $district_id);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('s.block_id', explode(',', $session['block_id']));
        } else if ($block_id > 0) {
            $this->db->where('s.block_id', $block_id);
        }
        if ($ac_id > 0) {
            $this->db->where('s.ac_id', $ac_id);
        }
        $this->db->join(AC . ' ac', 's.ac_id=ac.id');
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(ROADS_WO . ' wo', 'wo.roads_id=s.id', 'left');
        $this->db->group_by('wo.id, s.id, s.name, d.name, wo.wo_no, wo.wo_date, wo.contractor, wo.completion_date');
        $this->db->order_by('d.name');
        $query = $this->db->get(ROADS . ' s');
        return $query->result();
    }

    function wo_save($data)
    {
        $this->db->trans_start();
        $roads_id = isset($data['roads_id']) ? $data['roads_id'] : 0;
        $this->db->select('id, mobile');
        $this->db->where('roads_id', $roads_id);
        $query = $this->db->get(ROADS_WO);
        $id = $query->num_rows() > 0 ? $query->row()->id : 0;
        if ($roads_id > 0) {
            if (array_key_exists('wo_no', $data)) {
                $input = array(
                    'roads_id' => $roads_id,
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
                $this->db->update(ROADS_WO, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(ROADS_WO, $input);
                $id = $this->db->insert_id();
            }
            $this->db->select('user_id');
            $this->db->where('username', $data['mobile']);
            $query = $this->db->get(LOGIN);
            if ($query->num_rows() > 0) {
                $user_id = $query->row()->user_id;
                $sql = 'UPDATE roads SET pe_user_id=' . $user_id . ' WHERE id=' . $roads_id;
                $this->db->query($sql);
            } else {
                $this->db->select('district_id');
                $this->db->where('id', $roads_id);
                $query = $this->db->get(ROADS);
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
                $sql = 'UPDATE roads SET pe_user_id=' . $user_id . ' WHERE id=' . $roads_id;
                $this->db->query($sql);
            }
            $input = array(
                'work_order' => array_key_exists('wo_no', $data) ? $data['wo_no'] : '',
                'wo_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_date']))),
                'wo_status' => 2
            );
            $this->db->where('id', $roads_id);
            $this->db->update(ROADS, $input);
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
        $this->db->where('roads_id', $id);
        $this->db->update(ROADS_WO, $input);
        $input = array(
            'work_order' => null,
            'wo_date' => null,
            'wo_status' => 0
        );
        $this->db->where('id', $id);
        $this->db->update(ROADS, $input);
        return TRUE;
    }

    // function get_wp_list($user_id, $status)
    // {
    //     $where = 'WHERE s.survey_status=6 AND s.isactive=1 AND s.pe_user_id=' . $user_id;
    //     switch ($status) {
    //         case 0:
    //         case 5:
    //             $where .= ' AND s.pp_status=' . $status;
    //             break;
    //         case 1:
    //         case 2:
    //         case 3:
    //         case 4:
    //             $where .= ' AND s.pp_status > 0 and pp_status < 5';
    //             break;
    //         default:
    //             break;
    //     }
    //     $sql = 'SELECT s.id, b.name as block, g.name as gp, s.ref_no, s.name, s.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status '
    //         . 'FROM roads s JOIN division b ON s.block_id=b.id JOIN division g ON s.gp_id=g.id ' . $where;
    //     $query = $this->db->query($sql);
    //     // echo $this->db->last_query($query);exit;
    //     return $query->result();
    // }


    function get_wp_list($user_id, $status)
    {
        $this->db->select('s.id, b.name as block, g.name as gp, s.ref_no, s.name, 
                       s.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status');
        $this->db->from('roads s');
        $this->db->join('division b', 's.block_id = b.id');
        $this->db->join('division g', 's.gp_id = g.id');
        $this->db->where([
            's.survey_status' => 6,
            's.isactive'      => 1,
            's.pe_user_id'    => $user_id
        ]);

        switch ($status) {
            case 0:
            case 5:
                $this->db->where('s.pp_status', $status);
                break;
            case 1:
            case 2:
            case 3:
            case 4:
                $this->db->where('s.pp_status >', 0);
                $this->db->where('s.pp_status <', 5);
                break;
            default:
                // no extra condition
                break;
        }

        $query = $this->db->get();
        // echo $this->db->last_query(); exit; // for debugging
        return $query->result();
    }


    function get_wp_info($user_id, $id, $status)
    {
        $where = 'WHERE s.id=' . $id . ' AND s.survey_status=6 AND s.isactive=1 AND s.pe_user_id=' . $user_id;
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
        $sql = 'SELECT s.id, b.name as block, g.name as gp, s.ref_no, s.name, s.length, s.road_type, s.work_type, '
            . 's.wo_start_date, s.physical_progress, s.progress_remarks, s.pp_status '
            . 'FROM roads s JOIN division b ON s.block_id=b.id JOIN division g ON s.gp_id=g.id ' . $where;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function wp_save($data)
    {
        $progress = $data['wp_progress'];
        // print_r($progress); exit;
        $pp_status = $progress < 26 ? 1 : (($progress > 25 && $progress < 51) ? 2 : (($progress > 50 && $progress < 76) ? 3 : (($progress > 75 && $progress < 100) ? 4 : ($progress == 100 ? 5 : 0))));
        echo '<pre>';
        // // print_r($progress);
        $input = array(
            'created' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_date']))),
            'roads_id' => $data['id'],
            'physical_progress' => $progress,
            'progress_remarks' => $data['remarks'],
            'pp_status' => $pp_status
        );
        // print_r($input); exit;
        if (array_key_exists('wp_start_date', $data)) {
            $input['wo_start_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['wp_start_date'])));
        }
        $this->db->insert(ROADS_PROGRESS, $input);
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
        $this->db->update(ROADS, $input);

        return $id;
    }


    function get_wp_image($roads_id)
    {
        $this->db->select('r.name as name, rp.id, rp.roads_id, rp.physical_progress, rp.pp_status, rp.image1, rp.image2, rp.image3, rp.progress_remarks');
        $this->db->from(ROADS_PROGRESS . ' as rp');
        $this->db->join(ROADS . ' as r', 'rp.roads_id = r.id', 'left');
        $this->db->where('rp.roads_id', $roads_id);
        $this->db->order_by('rp.id', 'ASC');
        $query = $this->db->get();

        return $query->result_array();
    }
    ################################### QM #########################################

    function get_qm_list($month, $year)
    {
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
        GROUP_CONCAT(DISTINCT d.name) as district, COUNT(ssm_id) as total
        FROM ssm_qm as qm JOIN division d ON qm.district_id=d.id JOIN ssm s ON s.id=qm.ssm_id JOIN user u ON u.id=qm.sqm_id
        WHERE qm.isactive=1 and qm.month=' . $month . ' and qm.year=' . $year . $where . ' GROUP BY u.name, u.mobile ORDER BY u.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_qm_caption($month, $year, $sqm_id)
    {
        $sql = 'SELECT distinct u.name as sqm, m.name as month, qm.year FROM ssm_qm as qm JOIN user u ON u.id=qm.sqm_id JOIN month m ON qm.month=m.id WHERE qm.isactive=1 AND qm.month=' . $month . ' AND qm.year=' . $year . ' AND qm.sqm_id=' . $sqm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_qm_details($month, $year, $sqm_id)
    {
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
        FROM ssm_qm as qm JOIN ssm s ON s.id=qm.ssm_id JOIN ssm_wo wo ON wo.ssm_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
        WHERE qm.isactive=1 AND qm.month=' . $month . ' AND qm.year=' . $year . ' AND qm.sqm_id=' . $sqm_id . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

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

    function get_scheme_list($district_id, $sqm_id = 0, $month = 0, $year = 0)
    {
        $sqm_id = $sqm_id == '' ? 0 : $sqm_id;
        $sql = 'SELECT s.id, d.name as district, b.name as block, s.name, s.agency, s.length, s.wo_start_date, s.physical_progress, qm.inspection_date, qm.overall_grade, ifnull(qm.atr_action, 2) as atr_action, total.cnt, ifnull(q.id, 0) as selected
        FROM ssm s JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
        LEFT JOIN ssm_qm q ON s.id=q.ssm_id and q.sqm_id=' . $sqm_id . ' and q.month=' . $month . ' and q.year=' . $year . ' and q.isactive=1
        LEFT JOIN (SELECT sq.ssm_id, sqi.inspection_date, sqi.overall_grade, sqi.atr_action FROM ssm_qm sq JOIN ssm_qm_inspection sqi ON sqi.qm_id=sq.id WHERE sqi.inspection_date=(SELECT max(inspection_date) from ssm_qm_inspection as sqi1 join ssm_qm sq1 on sqi1.qm_id=sq1.id WHERE sq1.ssm_id=sq.ssm_id)) as qm ON qm.ssm_id=s.id
        LEFT JOIN (SELECT DISTINCT ssm_id, count(ssm_id) as cnt FROM ssm_qm GROUP BY ssm_id) as total ON total.ssm_id=s.id
        WHERE s.survey_status=6 AND s.isactive = 1 AND s.district_id=' . $district_id . ' ORDER BY d.name ASC, b.name ASC, agency ASC, s.physical_progress DESC';
        $query = $this->db->query($sql);
        //        echo $this->db->last_query();
        //        exit;
        return $query->result();
    }

    function get_inspection_list($sqm_id, $month, $year)
    {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, ur.name as sqm, ur.mobile as sqm_mobile, u.mobile, qm.status
        FROM ssm_qm as qm JOIN ssm s ON s.id=qm.ssm_id JOIN ssm_wo wo ON wo.ssm_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
        WHERE qm.isactive=1 AND qm.status<2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_report_list($sqm_id, $month, $year)
    {
        $where = $sqm_id != '' ? ' AND qm.sqm_id=' . $sqm_id : '';
        $sql = 'SELECT qm.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as pe, u.mobile, ur.name as sqm, ur.mobile as sqm_mobile, qm.status, sqi.inspection_date, sqi.overall_grade, sqi.document
        FROM ssm_qm as qm JOIN ssm_qm_inspection sqi ON sqi.qm_id=qm.id JOIN ssm s ON s.id=qm.ssm_id JOIN ssm_wo wo ON wo.ssm_id=s.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON s.pe_user_id=u.id JOIN user ur ON qm.sqm_id=ur.id
        WHERE qm.isactive=1 AND qm.status=2 AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_caption($qm_id)
    {
        $sql = 'SELECT s.name, s.agency FROM ssm_qm as qm JOIN ssm s ON qm.ssm_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_selection($qm_id)
    {
        $sql = 'SELECT s.name, s.agency FROM ssm_qm as qm JOIN ssm s ON qm.ssm_id=s.id WHERE qm.id=' . $qm_id;
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_inspection_image_list($id)
    {
        $this->db->select('i.description, i.image');
        $this->db->where(array(
            'hd.qm_id' => $id,
            'i.isactive' => 1
        ));
        $this->db->join('SSM_QM_INSPECTION_IMAGE' . ' i', 'i.inspection_id=hd.id');
        $this->db->order_by('i.seq_id');
        $query = $this->db->get('SSM_QM_INSPECTION' . ' hd');
        return $query->result();
    }

    function qm_save($data)
    {
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
        $this->db->update('SSM_QM', $input);
        foreach ($data['chk'] as $row) {
            $input = array(
                'district_id' => $data['district_id'],
                'ssm_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month'],
                'isactive' => 1
            );
            $this->db->where(array(
                'ssm_id' => $row,
                'sqm_id' => $data['sqm_id'],
                'year' => $data['year'],
                'month' => $data['month']
            ));
            // var_dump($input);exit;
            $query = $this->db->get('SSM_QM');
            if ($query->num_rows() > 0) {
                $this->db->where('id', $query->row()->id);
                $this->db->update('SSM_QM', $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert('SSM_QM', $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
    }

    function qm_save_start($qm_id, $agency)
    {
        $this->db->trans_start();
        $id = 0;
        $this->db->where('qm_id', $qm_id);
        $query = $this->db->get('SSM_QM_INSPECTION');
        if ($query->num_rows() > 0) {
            $id = $query->row()->id;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'qm_id' => $qm_id,
                'agency' => $agency
            );
            $this->db->insert('SSM_QM_INSPECTION', $input);
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

    function qm_save_inspection_image($i, $id, $desc)
    {
        $this->db->trans_start();
        $this->db->where(array(
            'inspection_id' => $id,
            'seq_id' => $i + 1
        ));
        $query = $this->db->get('SSM_QM_INSPECTION_IMAGE');

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
            $this->db->update('SSM_QM_INSPECTION_IMAGE', $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert('SSM_QM_INSPECTION_IMAGE', $input);
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

    function get_oqrc_list()
    {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get('SSM_OQRC');
        return $query->result();
    }

    function get_qm_oqrc_list($qm_id)
    {
        $this->db->select('o.id, o.name, r.value,i.inspection_date,i.overall_grade,i.atr_action');
        $this->db->where(array(
            'i.qm_id' => $qm_id,
            'r.isactive' => 1
        ));
        $this->db->join('SSM_QM_INSPECTION' . ' i', 'i.qm_id=q.id');
        $this->db->join('SSM_QM_INSPECTION_REPORT' . ' r', 'r.inspection_id=i.id');
        $this->db->join('SSM_OQRC' . ' o', 'r.oqrc_id=o.id');
        $query = $this->db->get('SSM_QM' . ' q');
        return $query->result();
    }

    function get_overall_grade($id)
    {
        $this->db->select('overall_grade');
        $this->db->where('qm_id', $id);
        $query = $this->db->get('SSM_QM_INSPECTION');
        //echo $this->db->last_query(); exit;
        return $query->num_rows() > 0 ? $query->row()->overall_grade : '';
    }

    function qm_save_submit($data)
    {
        $this->db->trans_start();
        $this->db->select('id');
        $this->db->where('qm_id', $data['qm_id']);
        $query = $this->db->get('SSM_QM_INSPECTION');
        $inspection_id = $query->row()->id;
        if ($inspection_id > 0) {
            $input = array(
                'isactive' => -1
            );
            $this->db->where('inspection_id', $inspection_id);
            $this->db->update('SSM_QM_INSPECTION_REPORT', $input);
            foreach ($data['oqrc'] as $row) {
                $arr = explode('_', $row);
                $this->db->select('id');
                $this->db->where(array(
                    'inspection_id' => $inspection_id,
                    'oqrc_id' => $arr[0]
                ));
                $query = $this->db->get('SSM_QM_INSPECTION_REPORT');
                $input = array(
                    'inspection_id' => $inspection_id,
                    'oqrc_id' => $arr[0],
                    'value' => ucwords($arr[1]),
                    'isactive' => 1
                );
                if ($query->num_rows() > 0) {
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('SSM_QM_INSPECTION_REPORT', $input);
                } else {
                    $input['created'] = date('Y-m-d');
                    $this->db->insert('SSM_QM_INSPECTION_REPORT', $input);
                }
            }
            $input = array(
                'inspection_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['inspection_date']))),
                'overall_grade' => $data['overall_grade'],
                'agency' => $data['agency']
            );
            $this->db->where('id', $inspection_id);
            $this->db->update('SSM_QM_INSPECTION', $input);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $inspection_id;
    }

    function get_atr_list($month, $year)
    {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? ' and qm.district_id=' . $session['district_id'] : '';
        // $sql = 'SELECT ins.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as sqm, u.mobile, qm.status, ins.document, ins.overall_grade, ins.atr
        //     FROM ssm_qm as qm JOIN ssm s ON s.id=qm.ssm_id JOIN ssm_wo wo ON wo.ssm_id=s.id JOIN ssm_qm_inspection ins ON ins.qm_id=qm.id
        //     JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON qm.sqm_id=u.id
        //     WHERE qm.isactive=1 AND qm.status=2 AND ins.overall_grade<>"S" AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';

        $sql = 'SELECT ins.id, d.name as district, b.name as block, s.name, s.physical_progress, s.length, wo.awarded_cost, s.agency, u.name as sqm, u.mobile, qm.status, ins.document, ins.overall_grade, ins.atr FROM ssm_qm as qm JOIN ssm s ON s.id=qm.ssm_id JOIN ssm_wo wo ON wo.ssm_id=s.id
    JOIN ssm_qm_inspection ins ON ins.qm_id=qm.id JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id JOIN user u ON qm.sqm_id=u.id WHERE qm.isactive=1 AND qm.status=2 AND ins.overall_grade<>"S" AND ins.inspection_date=(SELECT max(sqi.inspection_date) FROM ssm_qm_inspection as sqi JOIN ssm_qm as sq ON sqi.qm_id=sq.id WHERE sq.ssm_id=qm.ssm_id) AND qm.month=' . $month . ' AND qm.year=' . $year . $where . ' ORDER BY d.name, b.name';
        $query = $this->db->query($sql);
        //echo $this->db->last_query(); exit;
        return $query->result();
    }

    function qm_save_atr_review($data)
    {
        // var_dump($data);exit;
        $this->db->trans_start();
        $input = array(
            'atr_action' => $data['atr'],
            'atr_comments' => $data['remarks']
        );
        if ($data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('SSM_QM_INSPECTION', $input);
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

    // function filter_with_agencys()
    // {
    //     $session = $this->common->get_session();
    //     $filter = [];

    //     switch ($session['role_id']) {
    //         case 13:
    //             $filter = ['s.agency' => 'ZP'];
    //             break;
    //         case 14:
    //             $filter = ['s.agency' => 'BLOCK'];
    //             break;
    //         case 15:
    //             $filter = ['s.agency' => 'SRDA'];
    //             break;
    //         case 16:
    //             $filter = ['s.agency' => 'MBL'];
    //             break;
    //         case 17:
    //             $filter = ['s.agency' => 'AGRO'];
    //             break;
    //     }

    //     return $filter;
    // }

    function filter_with_agencys()
    {
        $session = $this->common->get_session();
        $filter = [];

        switch ($session['role_id']) {
            case 13:
                $this->db->where("TRIM(UPPER(s.agency)) =", "ZP");
                break;
            case 14:
                $this->db->where("TRIM(UPPER(s.agency)) =", "BLOCK");
                break;
            case 15:
                $this->db->where("TRIM(UPPER(s.agency)) =", "SRDA");
                break;
            case 16:
                $this->db->where("TRIM(UPPER(s.agency)) =", "MBL");
                break;
            case 17:
                $this->db->where("TRIM(UPPER(s.agency)) =", "AGRO");
                break;
        }

        // nothing returned, because we applied directly in query
        return [];
    }






    function get_rpt_state_summary($ac_id = 0)
    {
        $session = $this->common->get_session();

        // Base WHERE conditions
        $where = "s.survey_status = 6 AND s.isactive = 1";
        if ($session['district_id'] > 0) {
            $where .= " AND s.district_id IN (" . $session['district_id'] . ")";
        }
        if ($session['block_id'] > 0) {
            $where .= " AND s.block_id IN (" . $session['block_id'] . ")";
        }
        if ($ac_id > 0) {
            $where .= " AND s.ac_id = " . (int)$ac_id;
        }

        $progress_subquery = "SELECT 
            s.district_id,
            SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
            SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
            SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,
            SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
            SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
            SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,
            SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
            SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
            SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,
            SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
            ROUND(SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_99_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END),0) AS progress_99_amount,
            SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
            ROUND(SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_100_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END),0) AS progress_100_amount
        FROM roads s
        LEFT JOIN roads_wo wo ON wo.roads_id = s.id
        WHERE s.survey_status = 6 AND s.isactive = 1
        GROUP BY s.district_id
        ";

        // Main query
        $this->db->select("
        d.name AS district,
        s.district_id,
        SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
        SUM(s.bt_length + s.cc_length) AS approved_length,
        SUM(s.cost) AS approved_amount,
        (SUM(s.cost) * 0.18) AS gst_18_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,
        (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,
        SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
        SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
        SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
        SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
        SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

        IFNULL(p.progress_25,0) AS progress_25,
        IFNULL(p.progress_25_length,0) AS progress_25_length,
        IFNULL(p.progress_25_amount,0) AS progress_25_amount,
        IFNULL(p.progress_50,0) AS progress_50,
        IFNULL(p.progress_50_length,0) AS progress_50_length,
        IFNULL(p.progress_50_amount,0) AS progress_50_amount,
        IFNULL(p.progress_75,0) AS progress_75,
        IFNULL(p.progress_75_length,0) AS progress_75_length,
        IFNULL(p.progress_75_amount,0) AS progress_75_amount,
        IFNULL(p.progress_99, 0) AS progress_99,
        IFNULL(p.progress_99_length, 0) AS progress_99_length,
        IFNULL(p.progress_99_amount, 0) AS progress_99_amount,
        IFNULL(p.progress_100,0) AS progress_100,
        IFNULL(p.progress_100_length,0) AS progress_100_length,
        IFNULL(p.progress_100_amount,0) AS progress_100_amount,

        ( IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0)
        ) AS ongoing,
        ( IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0)
        ) AS ongoing_length,
        ( IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0)
        ) AS ongoing_amount,

        (IFNULL(p.progress_100,0)) AS completed,
        (IFNULL(p.progress_100_length,0)) AS completed_length,
        (IFNULL(p.progress_100_amount,0)) AS completed_amount");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

        // Join progress aggregated subquery
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id ', 'left');

        $this->db->where($where);
        $this->db->group_by('s.district_id');
        $this->db->order_by('d.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }


    function get_rpt_agency_progress($ac_id = 0)
    {
        $session = $this->common->get_session();

        // Base WHERE conditions
        $where = "s.survey_status = 6 AND s.isactive = 1";
        if ($session['district_id'] > 0) {
            $where .= " AND s.district_id IN (" . $session['district_id'] . ")";
        }
        if ($session['block_id'] > 0) {
            $where .= " AND s.block_id IN (" . $session['block_id'] . ")";
        }
        if ($ac_id > 0) {
            $where .= " AND s.ac_id = " . $ac_id;
        }

        // Progress subquery — grouped by district and agency
        $progress_subquery = "SELECT 
            s.district_id,
            s.agency,
            SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
            SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
            SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,
            
            SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
            SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
            SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,
            
            SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
            SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
            SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,
            
            SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
            ROUND(SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_99_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END),0) AS progress_99_amount,
            
            SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
            ROUND(SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_100_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END),0) AS progress_100_amount
            FROM roads s
            LEFT JOIN roads_wo wo ON wo.roads_id = s.id
            WHERE s.survey_status = 6 AND s.isactive = 1
            GROUP BY s.district_id, s.agency
        ";

        // Main query
        $this->db->select("
            d.name AS district,
            s.district_id,
            s.agency,

            SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
            SUM(s.bt_length + s.cc_length) AS approved_length,
            SUM(s.cost) AS approved_amount,

            (SUM(s.cost) * 0.18) AS gst_18_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,

            (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + 
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,

            SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
            SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
            SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
            SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
            SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

            IFNULL(p.progress_25,0) AS progress_25,
            IFNULL(p.progress_25_length,0) AS progress_25_length,
            IFNULL(p.progress_25_amount,0) AS progress_25_amount,
            IFNULL(p.progress_50,0) AS progress_50,
            IFNULL(p.progress_50_length,0) AS progress_50_length,
            IFNULL(p.progress_50_amount,0) AS progress_50_amount,
            IFNULL(p.progress_75,0) AS progress_75,
            IFNULL(p.progress_75_length,0) AS progress_75_length,
            IFNULL(p.progress_75_amount,0) AS progress_75_amount,
            IFNULL(p.progress_99,0) AS progress_99,
            IFNULL(p.progress_99_length,0) AS progress_99_length,
            IFNULL(p.progress_99_amount,0) AS progress_99_amount,
            IFNULL(p.progress_100,0) AS progress_100,
            IFNULL(p.progress_100_length,0) AS progress_100_length,
            IFNULL(p.progress_100_amount,0) AS progress_100_amount,

            (IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0)) AS ongoing,
            (IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0)) AS ongoing_length,
            (IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0)) AS ongoing_amount,

            IFNULL(p.progress_100,0) AS completed,
            IFNULL(p.progress_100_length,0) AS completed_length,
            IFNULL(p.progress_100_amount,0) AS completed_amount
        ");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

        // Join aggregated progress subquery (by district + agency)
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id AND s.agency = p.agency', 'left');

        // Apply conditions
        $this->db->where($where);

        // Group by district + agency
        $this->db->group_by(['s.district_id', 's.agency']);
        $this->db->order_by('d.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }




    function get_rpt_road_type_progress($road_type = '', $ac_id = 0)
    {
        $session = $this->common->get_session();

        // Base WHERE conditions
        $where = "s.survey_status = 6 AND s.isactive = 1";
        if ($session['district_id'] > 0) {
            $where .= " AND s.district_id IN (" . $session['district_id'] . ")";
        }
        if ($session['block_id'] > 0) {
            $where .= " AND s.block_id IN (" . $session['block_id'] . ")";
        }
        if ($ac_id > 0) {
            $where .= " AND s.ac_id = " . (int)$ac_id;
        }
        if ($road_type != '') {
            $where .= " AND s.road_type = " . $this->db->escape($road_type);
        }

        // Progress subquery — grouped by district, agency, and road_type
        $progress_subquery = "SELECT 
            s.district_id,
            s.agency,
            s.road_type,
            SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
            SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
            SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,

            SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
            SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
            SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,

            SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
            SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
            SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,

            SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
            ROUND(SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_99_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END),0) AS progress_99_amount,

            SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
            ROUND(SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END),2) AS progress_100_length,
            IFNULL(SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END),0) AS progress_100_amount
            FROM roads s
            LEFT JOIN roads_wo wo ON wo.roads_id = s.id
            WHERE s.survey_status = 6 AND s.isactive = 1
            GROUP BY s.district_id, s.agency, s.road_type
        ";

        // Main query (CI Query Builder)
        $this->db->select("
            d.name AS district,
            s.district_id,
            s.agency,
            s.road_type,

            SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
            SUM(s.bt_length + s.cc_length) AS approved_length,
            SUM(s.cost) AS approved_amount,

            (SUM(s.cost) * 0.18) AS gst_18_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,

            (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + 
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,

            SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
            SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
            SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
            SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
            SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

            IFNULL(p.progress_25,0) AS progress_25,
            IFNULL(p.progress_25_length,0) AS progress_25_length,
            IFNULL(p.progress_25_amount,0) AS progress_25_amount,
            IFNULL(p.progress_50,0) AS progress_50,
            IFNULL(p.progress_50_length,0) AS progress_50_length,
            IFNULL(p.progress_50_amount,0) AS progress_50_amount,
            IFNULL(p.progress_75,0) AS progress_75,
            IFNULL(p.progress_75_length,0) AS progress_75_length,
            IFNULL(p.progress_75_amount,0) AS progress_75_amount,
            IFNULL(p.progress_99,0) AS progress_99,
            IFNULL(p.progress_99_length,0) AS progress_99_length,
            IFNULL(p.progress_99_amount,0) AS progress_99_amount,
            IFNULL(p.progress_100,0) AS progress_100,
            IFNULL(p.progress_100_length,0) AS progress_100_length,
            IFNULL(p.progress_100_amount,0) AS progress_100_amount,

            (IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0)) AS ongoing,
            (IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0)) AS ongoing_length,
            (IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0)) AS ongoing_amount,

            IFNULL(p.progress_100,0) AS completed,
            IFNULL(p.progress_100_length,0) AS completed_length,
            IFNULL(p.progress_100_amount,0) AS completed_amount
        ");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

        // Join progress subquery grouped by district + agency + road_type
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id AND s.agency = p.agency AND s.road_type = p.road_type', 'left');

        // Apply WHERE
        $this->db->where($where);

        // Group by district, agency, and road_type
        $this->db->group_by(['s.district_id', 's.agency', 's.road_type']);
        $this->db->order_by('d.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }


    function get_rpt_work_type_progress($work_type = '', $ac_id = 0)
    {
        $session = $this->common->get_session();

        // Base WHERE conditions
        $where = "s.survey_status = 6 AND s.isactive = 1";
        if (!empty($session['district_id']) && $session['district_id'] > 0) {
            $where .= " AND s.district_id IN (" . $session['district_id'] . ")";
        }
        if (!empty($session['block_id']) && $session['block_id'] > 0) {
            $where .= " AND s.block_id IN (" . $session['block_id'] . ")";
        }
        if ($ac_id > 0) {
            $where .= " AND s.ac_id = " . (int)$ac_id;
        }
        if ($work_type !== '') {
            $where .= " AND s.work_type = " . $this->db->escape($work_type);
        }

        // Progress subquery (grouped by district, agency, work_type)
        $progress_subquery = "SELECT 
        s.district_id,
        s.agency,
        s.work_type,
        SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
        SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
        SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,

        SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
        SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
        SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,

        SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
        SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
        SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,

        SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
        SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_99_length,
        SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END) AS progress_99_amount,

        SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
        SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_100_length,
        SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END) AS progress_100_amount
    FROM roads s
    LEFT JOIN roads_wo wo ON wo.roads_id = s.id
    WHERE s.survey_status = 6 AND s.isactive = 1
    GROUP BY s.district_id, s.agency, s.work_type
    ";

        // Main query
        $this->db->select("
        d.name AS district,
        s.district_id,
        s.agency,
        s.work_type,
        SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
            SUM(s.bt_length + s.cc_length) AS approved_length,
            SUM(s.cost) AS approved_amount,

            (SUM(s.cost) * 0.18) AS gst_18_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,

            (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,

            ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + 
            ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,

            SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
            SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
            SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
            SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
            SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,

            IFNULL(p.progress_25,0) AS progress_25,
            IFNULL(p.progress_25_length,0) AS progress_25_length,
            IFNULL(p.progress_25_amount,0) AS progress_25_amount,
            IFNULL(p.progress_50,0) AS progress_50,
            IFNULL(p.progress_50_length,0) AS progress_50_length,
            IFNULL(p.progress_50_amount,0) AS progress_50_amount,
            IFNULL(p.progress_75,0) AS progress_75,
            IFNULL(p.progress_75_length,0) AS progress_75_length,
            IFNULL(p.progress_75_amount,0) AS progress_75_amount,
            IFNULL(p.progress_99,0) AS progress_99,
            IFNULL(p.progress_99_length,0) AS progress_99_length,
            IFNULL(p.progress_99_amount,0) AS progress_99_amount,
            IFNULL(p.progress_100,0) AS progress_100,
            IFNULL(p.progress_100_length,0) AS progress_100_length,
            IFNULL(p.progress_100_amount,0) AS progress_100_amount,

            (IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0)) AS ongoing,
            (IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0)) AS ongoing_length,
            (IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0)) AS ongoing_amount,

            IFNULL(p.progress_100,0) AS completed,
            IFNULL(p.progress_100_length,0) AS completed_length,
            IFNULL(p.progress_100_amount,0) AS completed_amount
    ");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

        // Join progress subquery
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id AND s.agency = p.agency AND s.work_type = p.work_type', 'left');

        // Apply WHERE
        $this->db->where($where);

        // Group and order
        $this->db->group_by(['s.district_id', 's.agency', 's.work_type']);
        $this->db->order_by('d.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }


    function get_rpt_ps_work_status($district_id = 0, $block_id = 0, $ac_id = 0)
    {
        $session = $this->common->get_session();

        // Base WHERE conditions
        $where = "s.survey_status = 6 AND s.isactive = 1";

        // Apply district filter
        if ($district_id > 0) {
            $where .= " AND s.district_id IN (" . $district_id . ")";
        } elseif (!empty($session['district_id']) && $session['district_id'] > 0) {
            $where .= " AND s.district_id IN (" . $session['district_id'] . ")";
        }

        // Apply block filter
        if ($block_id > 0) {
            $where .= " AND s.block_id IN (" . $block_id . ")";
        } elseif (!empty($session['block_id']) && $session['block_id'] > 0) {
            $where .= " AND s.block_id IN (" . $session['block_id'] . ")";
        }

        // Apply AC filter
        if ($ac_id > 0) {
            $where .= " AND s.ac_id = " . (int)$ac_id;
        }

        // Apply agency filter
        $where .= $this->filter_with_agency();

        // Progress subquery (grouped by district, agency, work_type)
        $progress_subquery = "SELECT 
        s.district_id,
        s.agency,
        s.work_type,
        SUM(CASE WHEN s.pp_status = 1 THEN 1 ELSE 0 END) AS progress_25,
        SUM(CASE WHEN s.pp_status = 1 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_25_length,
        SUM(CASE WHEN s.pp_status = 1 THEN wo.awarded_cost ELSE 0 END) AS progress_25_amount,
        SUM(CASE WHEN s.pp_status = 2 THEN 1 ELSE 0 END) AS progress_50,
        SUM(CASE WHEN s.pp_status = 2 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_50_length,
        SUM(CASE WHEN s.pp_status = 2 THEN wo.awarded_cost ELSE 0 END) AS progress_50_amount,
        SUM(CASE WHEN s.pp_status = 3 THEN 1 ELSE 0 END) AS progress_75,
        SUM(CASE WHEN s.pp_status = 3 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_75_length,
        SUM(CASE WHEN s.pp_status = 3 THEN wo.awarded_cost ELSE 0 END) AS progress_75_amount,
        SUM(CASE WHEN s.pp_status = 4 THEN 1 ELSE 0 END) AS progress_99,
        SUM(CASE WHEN s.pp_status = 4 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_99_length,
        SUM(CASE WHEN s.pp_status = 4 THEN wo.awarded_cost ELSE 0 END) AS progress_99_amount,
        SUM(CASE WHEN s.pp_status = 5 THEN 1 ELSE 0 END) AS progress_100,
        SUM(CASE WHEN s.pp_status = 5 THEN s.length * s.physical_progress / 100 ELSE 0 END) AS progress_100_length,
        SUM(CASE WHEN s.pp_status = 5 THEN wo.awarded_cost ELSE 0 END) AS progress_100_amount
    FROM roads s
    LEFT JOIN roads_wo wo ON wo.roads_id = s.id
    WHERE s.survey_status = 6 AND s.isactive = 1
    GROUP BY s.district_id, s.agency, s.work_type";

        // Main query
        $this->db->select("
        d.name AS district,
        s.district_id,
        b.name AS block,
        s.agency,
        s.work_type,
        SUM(CASE WHEN s.survey_status = 6 THEN 1 ELSE 0 END) AS approved_scheme,
        SUM(s.bt_length + s.cc_length) AS approved_length,
        SUM(s.cost) AS approved_amount,
        (SUM(s.cost) * 0.18) AS gst_18_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01) AS cess_1_percent,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03) AS contingency_agency_fee_3_percent,
        (SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) AS total_estimated_cost_excl_contingency,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) / NULLIF(SUM(s.bt_length + s.cc_length),0)) / 100000 AS per_km,
        ((SUM(s.cost) + (SUM(s.cost) * 0.18) + ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.01)) + 
        ((SUM(s.cost) + (SUM(s.cost) * 0.18)) * 0.03)) AS vetted_estimated_cost_incl_contingency,
        SUM(CASE WHEN s.tender_status >= 0 THEN 1 ELSE 0 END) AS tender_invited,
        SUM(CASE WHEN s.tender_status = 2 THEN 1 ELSE 0 END) AS tender_matured,
        SUM(CASE WHEN s.wo_status >= 1 THEN 1 ELSE 0 END) AS wo_issued,
        SUM(CASE WHEN s.wo_status >= 2 THEN (s.bt_length + s.cc_length) ELSE 0 END) AS wo_length,
        SUM(CASE WHEN s.wo_status >= 2 THEN s.cost ELSE 0 END) AS wo_amount,
        IFNULL(p.progress_25,0) AS progress_25,
        IFNULL(p.progress_25_length,0) AS progress_25_length,
        IFNULL(p.progress_25_amount,0) AS progress_25_amount,
        IFNULL(p.progress_50,0) AS progress_50,
        IFNULL(p.progress_50_length,0) AS progress_50_length,
        IFNULL(p.progress_50_amount,0) AS progress_50_amount,
        IFNULL(p.progress_75,0) AS progress_75,
        IFNULL(p.progress_75_length,0) AS progress_75_length,
        IFNULL(p.progress_75_amount,0) AS progress_75_amount,
        IFNULL(p.progress_99,0) AS progress_99,
        IFNULL(p.progress_99_length,0) AS progress_99_length,
        IFNULL(p.progress_99_amount,0) AS progress_99_amount,
        IFNULL(p.progress_100,0) AS progress_100,
        IFNULL(p.progress_100_length,0) AS progress_100_length,
        IFNULL(p.progress_100_amount,0) AS progress_100_amount,
        (IFNULL(p.progress_25,0) + IFNULL(p.progress_50,0) + IFNULL(p.progress_75,0) + IFNULL(p.progress_99,0)) AS ongoing,
        (IFNULL(p.progress_25_length,0) + IFNULL(p.progress_50_length,0) + IFNULL(p.progress_75_length,0) + IFNULL(p.progress_99_length,0)) AS ongoing_length,
        (IFNULL(p.progress_25_amount,0) + IFNULL(p.progress_50_amount,0) + IFNULL(p.progress_75_amount,0) + IFNULL(p.progress_99_amount,0)) AS ongoing_amount,
        IFNULL(p.progress_100,0) AS completed,
        IFNULL(p.progress_100_length,0) AS completed_length,
        IFNULL(p.progress_100_amount,0) AS completed_amount
    ");

        $this->db->from('roads s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(AC . ' ac', 's.ac_id = ac.id', 'left');

        // Join progress subquery
        $this->db->join("($progress_subquery) p", 's.district_id = p.district_id AND s.agency = p.agency AND s.work_type = p.work_type', 'left');

        // Apply WHERE
        $this->db->where($where);

        // Group and order
        $this->db->group_by(['s.district_id', 's.agency', 's.work_type']);
        $this->db->order_by('d.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }


    function get_rpt_work_progress($district_id = 0, $block_id = 0, $wp_id = 0, $ac_id = 0)
    {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '');
        $where .= $ac_id > 0 ? ' and ac_id=' . $ac_id : '';
        $where .= $wp_id == 6 ? ' and s.pp_status >=0 and s.pp_status <=5' : ' and s.pp_status =' . $wp_id;
        // $where .= '';
        $where .= $this->filter_with_agency();
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length, wo.awarded_cost, s.road_type, s.work_type, '
            . 's.wo_start_date, s.pp_status, s.physical_progress,s.image1,s.image2,s.image3,  wp.wp_date as last_work_date, wp.date_count as inspection_count from roads s '
            . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join roads_wo wo on wo.roads_id=s.id '
            . 'LEFT JOIN (SELECT roads_id, max(created) As wp_date,wo_start_date,count(created) as date_count FROM roads_progress GROUP BY roads_id) wp ON s.id = wp.roads_id AND s.wo_start_date = wp.wo_start_date '
            . 'where s.survey_status=6 and s.isactive=1 ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function get_rpt_work_progress_details($roads_id)
    {
        //        $db = $this->load->database('rpt', TRUE);
        $sql = 'SELECT s.name, sp.created, sp.wo_start_date, sp.physical_progress, sp.location1, sp.image1, sp.location2, sp.image2, sp.location3,
        sp.image3, sp.progress_remarks FROM roads_progress sp join roads s on sp.roads_id=s.id WHERE sp.roads_id=' . $roads_id . ' ORDER BY sp.created';
        $query = $this->db->query($sql);
        return $query->result();
    }


    function get_rpt_qm_summary($start_date, $end_date)
    {
        $sql = 'SELECT d.id, d.name as district, sqm.agency, sqm.overall_grade, COUNT(sqm.overall_grade) as cnt
        FROM ssm_qm as qm
        JOIN ssm_qm_inspection sqm ON sqm.qm_id=qm.id
        JOIN division d ON qm.district_id=d.id
        WHERE sqm.isactive=1 AND sqm.inspection_date>="' . date('Y-m-d', strtotime(str_replace('/', '-', $start_date))) . '" AND sqm.inspection_date<="' . date('Y-m-d', strtotime(str_replace('/', '-', $end_date))) . '"
        GROUP BY d.name, sqm.agency, sqm.overall_grade ORDER BY d.name, sqm.agency';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
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
        $sql = 'select s.id, d.name as district, b.name as block, s.name, s.agency, s.length,s.approved_length, wo.awarded_cost, s.road_type, s.work_type, s.cost_involved, s.work_completion_date, '
            . 's.wo_start_date, s.pp_status, s.physical_progress, s.financial_progress, s.bill_paid, s.bill_due, isupdated '
            . 'from ssm s join division d on s.district_id=d.id '
            . 'join division b on s.block_id=b.id join ssm_wo wo on wo.ssm_id=s.id '
            . 'where s.survey_status=6 and s.isactive=1 and s.pp_status=' . $wp_id . ' ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }

    function update_save($data)
    {
        // print_r($data); exit;
        $pp = $data['pp'];
        $pp_status = $pp < 25 ? 1 : (($pp > 24 && $pp < 50) ? 2 : (($pp > 49 && $pp < 75) ? 3 : (($pp > 74 && $pp < 100) ? 4 : ($pp == 100 ? 5 : 0))));
        $data['isupdated'] = $data['isupdated'] == '' ? 1 : ($data['isupdated'] + 1);
        $input = array(
            'length' => $data['length'],
            'physical_progress' => $pp,
            'pp_status' => $pp_status,
            'cost_involved' => $data['cost_involved'],
            'progress_remarks' => 'updated manually on ' . date('d/m/Y'),
            'financial_progress' => $data['fp'],
            'bill_paid' => $data['bp'],
            'bill_due' => $data['bd'],
            'isupdated' => $data['isupdated']
        );
        $this->db->where('id', $data['id']);
        $this->db->update(SSM, $input);
        $this->db->select('wo_start_date');
        $this->db->where('id', $data['id']);
        $query = $this->db->get(SSM);
        $wo_start_date = $query->row()->wo_start_date;
        $input = array(
            'created' => date('Y-m-d'),
            'ssm_id' => $data['id'],
            'wo_start_date' => $wo_start_date,
            'physical_progress' => $pp,
            'pp_status' => $pp_status,
            'progress_remarks' => 'updated manually on ' . date('d/m/Y')
        );
        $this->db->insert(SSM_PROGRESS, $input);
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
            . 'from ssm s join division d on s.district_id=d.id '
            . 'join division b on s.block_id=b.id join ssm_wo wo on wo.ssm_id=s.id '
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
            . 'FROM roads s JOIN division d ON s.district_id=d.id ' . $where . ' '
            . 'GROUP BY d.name, s.agency ORDER BY d.name, s.agency';
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_rpt_temp_ssm()
    {
        $sql = 'SELECT s.name,d.name as district, b.name as block, wp.loc1 as location1, wp.loc2 as location2, wp.loc3 as location3 FROM ssm s '
            . 'JOIN (SELECT ssm_id, wo_start_date, max(location1) as loc1, (location2) as loc2, (location3) as loc3, pp_status FROM ssm_progress GROUP BY ssm_id) wp ON s.id = wp.ssm_id AND s.wo_start_date = wp.wo_start_date '
            . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join ssm_wo wo on wo.ssm_id=s.id '
            . 'WHERE s.isactive = 1 and wp.loc1 IS NOT NULL and wp.loc2 IS NOT NULL and wp.loc3 IS NOT NULL '
            . 'order by d.name,b.name';
        $query = $this->db->query($sql);
        //    echo $this->db->last_query($query); exit;
        return $query->result();
    }

    function get_rpt_temp_srrp()
    {
        $sql = 'SELECT s.name,d.name as district, b.name as block, wp.loc1 as location1, wp.loc2 as location2, wp.loc3 as location3 FROM srrp s '
            . 'JOIN (SELECT srrp_id, wo_start_date, (location1) as loc1, (location2) as loc2, (location3) as loc3, pp_status FROM srrp_progress GROUP BY srrp_id) wp ON s.id = wp.srrp_id AND s.wo_start_date = wp.wo_start_date '
            . 'join division d on s.district_id=d.id join division b on s.block_id=b.id join srrp_wo wo on wo.srrp_id=s.id '
            . 'WHERE s.isactive = 1 and wp.loc1 IS NOT NULL and wp.loc2 IS NOT NULL and wp.loc3 IS NOT NULL '
            . 'order by d.name,b.name';
        $query = $this->db->query($sql);
        //    echo $this->db->last_query($query); exit;
        return $query->result();
    }

    function update_completion_date($data)
    {
        $id = $data['id'];
        $complete_date = new DateTime(date('Y-m-d', strtotime(str_replace('/', '-', $data['completion_date']))));
        $start_date = new DateTime(date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date']))));
        if ($complete_date > $start_date) {
            $input = array(
                'work_completion_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['completion_date'])))
            );
            $this->db->where('id', $id);
            $this->db->update(SSM, $input);
            // echo $this->db->last_query($input);
        } else {
            return false;
        }
    }
    function update_wo_date($data)
    {
        $wo_start_date = new DateTime(date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_start_date']))));
        if ($wo_start_date != '') {
            $input = array(
                'wo_start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['wo_start_date'])))
            );
            $this->db->where('id', $data['id']);
            $this->db->update(SSM, $input);
        }
    }
    function rpt_agency_wise_updated($district_id, $agency, $status_id)
    {
        $order_by = '';
        if ($agency == '' && $district_id == 0) {
            $order_by = 'district, s.agency,';
        } else if ($agency != '' && $district_id == 0) {
            $order_by = 'district,';
        } elseif ($agency == '' && $district_id != 0) {
            $order_by = 's.agency,';
        }
        $session = $this->common->get_session();
        $status = $status_id == 0 ? '' : ($status_id == 2 ? ' and s.physical_progress = 100 and s.financial_progress = 100 ' : ' and s.physical_progress >= 0 and s.physical_progress <= 99 and s.financial_progress >= 0 and s.financial_progress <= 99 ');
        $agency = $agency == '' ? '' : ' AND s.agency = "' . $agency . '"';
        $where = $district_id > 0 ? ' and s.district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and s.district_id in (' . $session['district_id'] . ')' : '');
        $sql = 'SELECT s.id, s.name, d.name as district, b.name as block, s.agency, s.cost, s.length, s.wo_start_date, s.work_completion_date, s.physical_progress, s.financial_progress, s.cost_involved, s.bill_paid, s.bill_due, wo.awarded_cost FROM ssm as s 
                JOIN division d ON s.district_id=d.id JOIN division b ON s.block_id=b.id
                JOIN ssm_wo wo ON s.id = wo.ssm_id 
                WHERE s.isactive = 1 ' . $where . $status . $agency . ' ORDER BY ' . $order_by . ' s.physical_progress, s.financial_progress ';
        $query = $this->db->query($sql);
        // echo $this->db->last_query($query); exit;
        return $query->result();
    }
    function get_rpt_updated_work_details($district_id = 0, $block_id = 0, $agency, $status_id)
    {
        $session = $this->common->get_session();
        $status = $status_id == 0 ? '' : ($status_id == 2 ? ' and s.physical_progress = 100 and s.financial_progress = 100 ' : ' and s.physical_progress >= 0 and s.physical_progress <= 99 and s.financial_progress >= 0 and s.financial_progress <= 99 ');
        $where = $district_id > 0 ? ' and s.district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and s.district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and s.block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and s.block_id in (' . $session['block_id'] . ')' : '');
        $where .= $agency == '' ? '' : ' AND s.agency = "' . $agency . '"';
        $where .= $this->filter_with_agency();
        $sql = 'select d.name as district, b.name as block, s.agency, s.name, s.road_type, s.work_type, s.approved_length, s.cost, s.wo_start_date, s.length, s.cost_involved, s.physical_progress, '
            . 's.financial_progress, s.bill_paid, s.bill_due '
            . 'from roads s join division d ON s.district_id=d.id '
            . 'JOIN division b ON s.block_id=b.id '
            . 'where s.survey_status=6 AND s.isactive=1 ' . $where . $status . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        //  echo $this->db->last_query(); exit;
        return $query->result();
    }
    function get_rpt_updated_work_summary($district_id = 0, $block_id = 0, $agency)
    {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and s.district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and s.district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and s.block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and s.block_id in (' . $session['block_id'] . ')' : '');
        $where .= $agency == '' ? '' : ' AND s.agency = "' . $agency . '"';
        $where .= $this->filter_with_agency();
        $sql = 'select d.name as district, b.name as block, s.agency, count(s.id) as total, sum(s.approved_length) as approved_length, '
            . 'sum(s.cost) as cost, sum(s.length) as length, sum(s.cost_involved) as cost_involved, sum(s.bill_paid) as bill_paid, '
            . 'sum(s.bill_due) as bill_due,  sum(if(s.pp_status>4, 1, 0)) as completed, sum(if(s.pp_status>4, length, 0)) as completed_length, '
            . 'sum(if(s.pp_status > 4, wo.awarded_cost, 0)) as completed_amount, sum(IF(s.isupdated > 0, 0, 1)) as not_updated '
            . 'from roads s join division d ON s.district_id=d.id '
            . 'JOIN division b ON s.block_id=b.id JOIN roads_wo wo ON wo.roads_id=s.id '
            . 'where s.survey_status=6 AND s.isactive=1 ' . $where . ' group by d.name, b.name, s.agency order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }
    function date_update($data)
    {
        if ($data['start_date'] != '') {
            $input = array(
                'wo_start_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])))
            );
            $this->db->where('id', $data['id']);
            $this->db->update(ROADS, $input);
            $this->db->where('id', $data['id']);
            $this->db->update(ROADS_PROGRESS, $input);
        }
        if ($data['complete_date'] != '') {
            $input = array(
                'work_completion_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['complete_date'])))
            );
            $this->db->where('id', $data['id']);
            $this->db->update(ROADS, $input);
        }
    }

    function get_rpt_all_documents($district_id = 0, $block_id = 0, $scheme_id = 0)
    {
        $session = $this->common->get_session();
        $where = $district_id > 0 ? ' and s.district_id in (' . $district_id . ')' : ($session['district_id'] > 0 ? ' and s.district_id in (' . $session['district_id'] . ')' : '');
        $where .= $block_id > 0 ? ' and s.block_id in (' . $block_id . ')' : ($session['block_id'] > 0 ? ' and s.block_id in (' . $session['block_id'] . ')' : '');
        $table = $scheme_id == 1 ? 'srrp' : ($scheme_id == 2 ? 'ssm' : '');
        $where .= $this->filter_with_agency();
        $sql = 'select d.name as district, b.name as block, s.agency, s.name, s.survey_lot_no, s.survey_lot_doc, s.dm_lot_no, s.dm_lot_doc, s.se_lot_no, s.se_lot_doc, s.sa_lot_no, s.sa_lot_doc, s.survey_status, s.isactive '
            . 'from ' . $table . ' s join division d ON s.district_id=d.id '
            . 'JOIN division b ON s.block_id=b.id '
            . 'where s.isactive= 1 and s.survey_status > 1 ' . $where . ' order by d.name, b.name';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }
}
