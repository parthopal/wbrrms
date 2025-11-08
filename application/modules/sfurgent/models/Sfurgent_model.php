<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SFURGENT_Model extends CI_Model
{



    function get_approved_list($district_id)
    {
        $this->db->select("s.id, s.tag, d.name AS district, b.name AS block, GROUP_CONCAT(gp.name ORDER BY gp.name SEPARATOR ', ') AS gp, s.village, s.ref_no, s.name, s.agency, s.approved_length, s.work_type, s.road_type, s.survey_status,s.cost, s.approved_doc, s.isactive ", FALSE);

        $this->db->from(SFURGENT . ' s');
        $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
        $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
        $this->db->join(DIVISION . ' gp', 'FIND_IN_SET(gp.id, s.gp_id)', 'left');

        $this->db->where(array(
            's.district_id' => $district_id,
            's.isactive'    => 1
        ));

        $this->db->group_by('s.id');
        $this->db->order_by('b.name', 'ASC');

        $query = $this->db->get();
        return $query->result();
    }


    // function get_approved_list($district_id = null)
    // {
    //     $this->db->select("s.id, s.tag, d.name AS district, b.name AS block, 
    //                    GROUP_CONCAT(gp.name ORDER BY gp.name SEPARATOR ', ') AS gp, 
    //                    s.village, s.ref_no, s.name, s.agency, 
    //                    s.approved_length, s.work_type, s.road_type, 
    //                    s.survey_status, s.cost, s.approved_doc, s.isactive", FALSE);

    //     $this->db->from(SFURGENT . ' s');
    //     $this->db->join(DIVISION . ' d', 's.district_id = d.id', 'left');
    //     $this->db->join(DIVISION . ' b', 's.block_id = b.id', 'left');
    //     $this->db->join(DIVISION . ' gp', 'FIND_IN_SET(gp.id, s.gp_id)', 'left');

    //     // Only filter by district if $district_id is passed
    //     if (!empty($district_id)) {
    //         $this->db->where('s.district_id', $district_id);
    //     }

    //     // Always show active records
    //     $this->db->where('s.isactive', 1);

    //     $this->db->group_by('s.id');
    //     $this->db->order_by('b.name', 'ASC');

    //     $query = $this->db->get();
    //     return $query->result();
    // }

    function get_scheme_info($id)
    {
        $this->db->where(array('id' => $id));
        $query = $this->db->get(SFURGENT);
        return $query->row();
    }

    function save($data)
    {
        $this->db->trans_start();
        $id = isset($data['id']) ? $data['id'] : 0;
        $input = array(
            'name' => $data['name'],
            'tag' => $data['tag'],
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'gp_id' => !empty($data['gp_id']) ? implode(',', $data['gp_id']) : 0,
            'village' => $data['village'],
            'agency' => $data['agency'],
            'length' => $data['length'],
            'proposed_length' => $data['length'],
            'approved_length' => $data['length'],
            'work_type' => $data['work_type'],
            'road_type' => $data['road_type'],
            'cost' => $data['cost'],
            'admin_approval_date' => !empty($data['admin_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))) : null
        );

        if ($id > 0) {
            // Update
            $this->db->where('id', $id)->update(SFURGENT, $input);
        } else {
            // Insert
            $input['created'] = date('Y-m-d');
            $this->db->insert(SFURGENT, $input);
            $id = $this->db->insert_id();
        }

        // ---- Ref No generation ----
        $district_name = $this->db->select('name')
            ->where('id', $data['district_id'])
            ->get(DIVISION)
            ->row()
            ->name;

        do {
            $ref_no = str_pad($data['district_id'] . rand(0, 999), 5, '0', STR_PAD_LEFT)
                . '/' . $data['agency']
                . '/' . $district_name
                . '/STATEFUND_URGENT/2025';

            $exists = $this->db->where('ref_no', $ref_no)
                ->count_all_results(SFURGENT);
        } while ($exists > 0);

        $this->db->where('id', $id)->update(SFURGENT, ['ref_no' => $ref_no]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $id;
    }

    function remove($id)
    {
        $input = array(
            'isactive' => -1
        );
        $this->db->where('id', $id);
        $this->db->update(SFURGENT, $input);
        return true;
    }
}
