<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 21-Aug-2024]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Bridge extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('bridge_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }

    function overview() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 119);
        }
        $this->data['count'] = json_encode($this->model->get_total_count());
        $this->data['condition'] = json_encode($this->model->get_condition_count());
        $this->data['material'] = $this->model->get_material_count();
        $this->data['category'] = json_encode($this->model->get_category_count());
        $this->data['ownership'] = json_encode($this->model->get_ownership_count());
        $this->data['dc'] = json_encode($this->model->get_districtwise_condition_count());
        $this->data['entry'] = json_encode($this->model->get_entry_data());
        $this->data['title'] = 'Bridge';
        $this->data['heading'] = 'Overview - Bridge';
        $this->data['subheading'] = 'overview';
        $this->data['content'] = $this->parser->parse('overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    
    function map() {
        $session = $this->common->get_session();
        $this->data['title'] = 'Bridge';
        $this->data['heading'] = 'Map - Bridge';
        $this->data['subheading'] = 'map view';
        $this->data['content'] = $this->parser->parse('map', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function index() {
        $session = $this->common->get_session();
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'block_id' => 0,
            'scheme_id' => 0,
            'foundation_id' => 0,
            'superstructure_id' => 0,
            'type_id' => 0,
            'material_id' => 0,
            'ownership_id' => 0,
            'condition_id' => 0
        );
        $district_list = $this->model->get_bridge_district_list();
        if ($this->input->post()) {
            $_post = $this->input->post();
//            var_dump($_post);
//            exit;
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'scheme_id' => $_post['scheme_id'],
                    'foundation_id' => $_post['foundation_id'],
                    'superstructure_id' => $_post['superstructure_id'],
                    'type_id' => $_post['type_id'],
                    'material_id' => $_post['material_id'],
                    'ownership_id' => $_post['ownership_id'],
                    'condition_id' => $_post['condition_id']
                );
                $list = $this->model->get_bridge_list($selected);
            }
        }
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->model->get_bridge_block_list($selected);
        }
        if (sizeof($block_list) == 1) {
            $selected['block_id'] = $block_list[0]->id;
        }
        $scheme_list = $this->model->get_bridge_scheme_list($selected);
        $foundation_list = $this->model->get_bridge_foundation_list($selected);
        $superstructure_list = $this->model->get_bridge_superstructure_list($selected);
        $substructure_type_list = $this->model->get_bridge_substructure_type_list($selected);
        $substructure_material_list = $this->model->get_bridge_substructure_material_list($selected);
        $ownership_list = $this->model->get_bridge_ownership_list($selected);
        $condition_list = $this->model->get_bridge_condition_list($selected);

        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['scheme'] = json_encode($scheme_list);
        $this->data['foundation'] = json_encode($foundation_list);
        $this->data['superstructure'] = json_encode($superstructure_list);
        $this->data['type'] = json_encode($substructure_type_list);
        $this->data['material'] = json_encode($substructure_material_list);
        $this->data['ownership'] = json_encode($ownership_list);
        $this->data['condition'] = json_encode($condition_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['title'] = 'Bridge';
        $this->data['heading'] = 'Master - Bridge';
        $this->data['subheading'] = 'Bridge';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function _entry($id = 0) {
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'id' => $id,
            'district_id' => 0,
            'block_id' => 0,
            'name' => '',
            'scheme_id' => 0,
            'package_no' => '',
            'length' => '',
            'width' => '',
            'chainage' => '',
            'latitude' => '',
            'longitude' => '',
            'foundation_id' => 0,
            'superstructure_id' => 0,
            'type_id' => 0,
            'material_id' => 0,
            'ownership_id' => 0,
            'ownership' => '',
            'condition_id' => 0
        );
        if ($id > 0) {
            $row = $this->model->get_bridge_info($id);
            $location = explode(',', $row->location);
            $selected = array(
                'id' => $id,
                'district_id' => $row->district_id,
                'block_id' => $row->block_id,
                'name' => $row->name,
                'scheme_id' => $row->scheme_id,
                'package_no' => $row->package_no,
                'length' => $row->length,
                'width' => $row->width,
                'chainage' => $row->chainage,
                'latitude' => $location[0],
                'longitude' => $location[1],
                'foundation_id' => $row->foundation_id,
                'superstructure_id' => $row->superstructure_id,
                'type_id' => $row->type_id,
                'material_id' => $row->material_id,
                'ownership_id' => $row->ownership_id,
                'ownership' => $row->ownership,
                'condition_id' => $row->condition_id
            );
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        if ($selected['district_id'] > 0) {
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        if (sizeof($block_list) == 1) {
            $selected['block_id'] = $block_list[0]->id;
        }
        $scheme_list = $this->model->get_scheme_list($selected);
        $foundation_list = $this->model->get_foundation_list($selected);
        $superstructure_list = $this->model->get_superstructure_list($selected);
        $substructure_type_list = $this->model->get_substructure_type_list($selected);
        $substructure_material_list = $this->model->get_substructure_material_list($selected);
        $ownership_list = $this->model->get_ownership_list($selected);
        $condition_list = $this->model->get_condition_list($selected);

        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['scheme'] = json_encode($scheme_list);
        $this->data['foundation'] = json_encode($foundation_list);
        $this->data['superstructure'] = json_encode($superstructure_list);
        $this->data['type'] = json_encode($substructure_type_list);
        $this->data['material'] = json_encode($substructure_material_list);
        $this->data['ownership'] = json_encode($ownership_list);
        $this->data['condition'] = json_encode($condition_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'Bridge';
        $this->data['heading'] = 'Master Entry - Bridge';
        $this->data['subheading'] = 'Bridge - Add/Edit Information';
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function image($id) {
        if ($id > 0) {
            $row = $this->model->get_bridge_info($id);
            $selected = array(
                'id' => $id,
                'name' => $row->name,
                'image_side' => $row->image_side,
                'image_alignment' => $row->image_alignment,
                'image_a1' => $row->image_a1,
                'image_a2' => $row->image_a2
            );
            $this->data['selected'] = json_encode($selected);
            $this->data['title'] = 'Bridge';
            $this->data['heading'] = 'Master Entry - Bridge';
            $this->data['subheading'] = 'Bridge - Add/Edit Images';
            $this->data['content'] = $this->parser->parse('image', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function upload() {
        $data = $this->input->post();
        $id = $data['id'];
        $input = array();
        $path = 'uploads/bridge';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = '*';
//        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image_side')) {
            $error = array('error1' => $this->upload->display_errors());
//            var_dump($error);
//            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $id . '_image_side' . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input['image_side'] = $new_file;
        }
        if (!$this->upload->do_upload('image_alignment')) {
            $error = array('error2' => $this->upload->display_errors());
//            var_dump($error);
//            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $id . '_image_alignment' . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input['image_alignment'] = $new_file;
        }
        if (!$this->upload->do_upload('image_a1')) {
            $error = array('error3' => $this->upload->display_errors());
//            var_dump($error);
//            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $id . '_image_a1' . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input['image_a1'] = $new_file;
        }
        if (!$this->upload->do_upload('image_a2')) {
            $error = array('error4' => $this->upload->display_errors());
//            var_dump($error);
//            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $id . '_image_a2' . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input['image_a2'] = $new_file;
        }
        if (sizeof($input) > 0) {
            $this->db->where('id', $id);
            $this->db->update(BRIDGE, $input);
        }
        redirect('bridge');
    }

    function save() {
        $this->data = $this->input->post();
        $id = $this->model->save($this->data);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data addded successfully'));
            redirect('bridge');
        }
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function status() {
        $data = $this->input->get();
        echo json_encode($this->model->status($data));
    }
	
	function report()
    {
        $this->data['heading'] = 'Report Overview';
        $this->data['subheading'] = 'BRIDGE REPORT';
        $this->data['title'] = 'BRIDGE';
        $this->data['content'] = $this->parser->parse('report', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }


    function rpt_state_summary(){
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 119);
        }
        $this->data['title'] = 'Bridge';
        $this->data['heading'] = 'State Summary Report - Bridge';
        $this->data['subheading'] = 'BRIDGE';
        $this->data['list'] = json_encode($this->model->rpt_get_state_summary());
        $this->data['content'] = $this->parser->parse('rpt_state_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
}
