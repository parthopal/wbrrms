<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Web_Model extends CI_Model {

    function get_district_list() {
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            'd.level_id' => 2,
            's.survey_status' => 6,
            's.isactive' => 1,
            'd.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->order_by('d.name');
        $query = $this->db->get(SRRP . ' s');
        return $query->result();
    }

    function get_block_list($district_id) {
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            's.district_id' => $district_id,
            'd.level_id' => 3,
            's.survey_status' => 6,
            's.isactive' => 1,
            'd.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.block_id=d.id');
        $this->db->order_by('d.name');
        $query = $this->db->get(SRRP . ' s');
        return $query->result();
    }

    function get_gp_list($district_id, $block_id) {
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.block_id' => $block_id,
            'd.level_id' => 4,
            's.survey_status' => 6,
            's.isactive' => 1,
            'd.isactive' => 1
        ));
        $this->db->join(DIVISION . ' d', 's.gp_id=d.id');
        $this->db->order_by('d.name');
	$query = $this->db->get(SRRP . ' s');
        return $query->result();
    }

    function get_road_list($district_id, $block_id, $gp_id = 0) {
        $this->db->select('d.name as district, b.name as block, gp.name as gp, s.name, s.length');
        $this->db->where(array(
            's.district_id' => $district_id,
            's.block_id' => $block_id,
            's.survey_status' => 6,
            's.isactive' => 1
        ));
        if ($gp_id > 0) {
            $this->db->where('s.gp_id', $gp_id);
        }
        $this->db->join(DIVISION . ' d', 's.district_id=d.id');
        $this->db->join(DIVISION . ' b', 's.block_id=b.id');
        $this->db->join(DIVISION . ' gp', 's.gp_id=gp.id');
        $this->db->order_by('gp.name, s.name');
        $query = $this->db->get(SRRP . ' s');
        return $query->result();
    }

}
