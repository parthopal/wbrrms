<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 11-Oct-2023]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class RIDF extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('ridf_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array(); //echo 'am here'; exit;
    }

    function overview() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 18);
        }
        $this->data['overview'] = json_encode($this->model->get_scheme_summary());
        // $this->data['tender_and_wo_count'] = json_encode($this->model->get_tender_and_wo_count());
        $this->data['title'] = 'RIDF SCHEME SUMMARY';
        $this->data['heading'] = 'Overview - RIDF';
        $this->data['subheading'] = 'OVERVIEW OF RIDF PROJECT';
        $this->data['content'] = $this->parser->parse('overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function index() {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0,
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
                $list = $this->model->get_scheme_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['district'] = json_encode($district_list);
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'RIDF Information';
        $this->data['subheading'] = 'View the project information';
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0) {
        $session = $this->common->get_session();
        $subheading = '';
        $block_list = array();
        $ac_list = array();
        $survey_list = array();
        $selected = array(
            'id' => 0,
            'district_id' => '',
            'ac_id' => '',
            'category_id' => '',
            'block_id' => '',
            'type_id' => '',
            'scheme_id' => '',
            'name' => '',
            'agency_id' => '',
            'road_type_id' => '',
            'length' => '',
            'work_type_id' => '',
            'sanctioned_cost' => '',
            'note' => '',
            'unit' => '',
            'admin_no' => '',
            'admin_date' => ''
        );
        if ($id > 0) {
            $row = $this->model->get_scheme_info($id);
            // print_r($row);exit;
            $selected = array(
                'id' => $row->id,
                'district_id' => $row->district_id,
                'ac_id' => $row->ac_id,
                'block_id' => $row->block_id,
                'category_id' => $row->category_id,
                'type_id' => $row->work_type_id,
                'scheme_id' => $row->scheme_id,
                'name' => $row->name,
                'agency_id' => $row->agency_id,
                'road_type_id' => $row->road_type_id,
                'length' => $row->length,
                'work_type' => $row->road_type_name,
                'work_type_id' => $row->work_type_id,
                'sanctioned_cost' => $row->sanctioned_cost,
                'note' => $row->note != '' ? $row->note : '',
                'unit' => $row->unit,
                'admin_no' => isset($row->admin_no) ? $row->admin_no : '',
                'admin_date' => isset($row->admin_date) ? date('d/m/Y', strtotime($row->admin_date)) : ''
            );
            $subheading = $row->name;
        }
        $district_list = $this->common->get_district_list();

        $this->data['district'] = sizeof($district_list) > 0 ? json_encode($district_list) : '';
        if ($selected['district_id'] > 0) {
            $ac_list = $this->model->get_ac_list($selected['district_id']);
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['assembly'] = json_encode($ac_list);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['agency'] = json_encode($this->model->get_agency_list());
        $this->data['road'] = json_encode($this->model->get_road_type_list());
        $this->data['work'] = json_encode($this->model->get_work_type_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['survey'] = json_encode($survey_list);
        $this->data['title'] = 'RIDF';
        $this->data['heading'] = 'Master Entry - RIDF';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);

        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save() {
        $this->data = $this->input->post();
        $id = $this->model->save($this->data);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data addded successfully'));
            redirect('ridf');
        }
    }

    function get_assembly_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_ac_list($data['district_id']));
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    // Tender
    function tender() {   //echo 'am here - tender()'; exit;
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'district_id' => '0',
            'category_id' => '0',
            'type_id' => '0'
        );
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['ac'] = json_encode($this->model->get_ac_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['approved'] = json_encode($this->model->get_tender_list($selected['district_id'], $selected['category_id'], $selected['type_id']));
        $this->data['title'] = 'Tender';
        $this->data['heading'] = 'Tender - RIDF';
        $this->data['subheading'] = 'Tender';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('tender', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_tender_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_tender_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function tender_entry($id) {
        // print_r($id);exit;
        // var_dump($id);exit;
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_tender_info($id);
            // print_r($row);exit;
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'scheme_id' => $row->scheme_id,
                'name' => $row->name,
                'length' => $row->length,
                'agency' => $row->agency,
                'tender_number' => $row->tender_number,
                'tender_publication_date' => $row->tender_publication_date,
                'tender_status' => $row->tender_status,
                'bid_opeaning_date' => $row->bid_opeaning_date,
                'bid_closing_date' => $row->bid_closing_date,
                'evaluation_status' => $row->evaluation_status,
                'bid_opening_status' => $row->bid_opening_status,
                'bid_matured_status' => $row->bid_matured_status
            );
            if ($row->tender_status == 3) {
                $selected = array(
                    'id' => $id,
                    'district' => $row->district,
                    'block' => $row->block,
                    'scheme_id' => $row->scheme_id,
                    'name' => $row->name,
                    'length' => $row->length,
                    'agency' => $row->agency,
                    'tender_number' => '',
                    'tender_publication_date' => null,
                    'tender_status' => 0,
                    'bid_opeaning_date' => null,
                    'bid_closing_date' => null,
                    'evaluation_status' => null,
                    'bid_opening_status' => null,
                    'bid_matured_status' => null
                );
            }
        }
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'Tender';
        $this->data['heading'] = 'Tender - RIDF';
        $this->data['subheading'] = 'Tender Entry';
        $this->data['content'] = $this->parser->parse('tender_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function tender_save() {
        $data = $this->input->post();
        // print_r($data);exit;
        if ($this->model->tender_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Tender Save successfully'));
            redirect('ridf/tender');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can\'t save now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function wo() {
        $session = $this->common->get_session();
        $block_list = array();
        $ac_list = array();
        $wo = array();
        $selected = array(
            'district_id' => '0',
            'category_id' => '0',
            'type_id' => '0'
        );
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $ac_list = $this->model->get_ac_list();
            $block_list = $this->common->get_block_list($selected['district_id']);
            $wo = $selected['district_id'] > 0 ? $this->model->get_wo_list($selected['district_id'], $selected['category_id'], $selected['type_id']) : array('hi');
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['ac'] = json_encode($ac_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['wo'] = json_encode($wo);
        // print_r($this->data['wo']);exit;
        $this->data['title'] = 'Work Order';
        $this->data['heading'] = 'Work Order - RIDF';
        $this->data['subheading'] = 'Work Order Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('wo_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wo_entry($id = 0) {
        $session = $this->common->get_session();
        if ($id > 0) {
            $wo = $this->model->get_wo_info($id);
            $selected = array(
                'id' => $wo->id,
                'ridf_id' => $wo->ridf_id,
                'district_id' => $wo->district_id,
                'name' => $wo->name,
                'wo_no' => $wo->wo_no,
                'wo_date' => $wo->wo_date != NULL ? $wo->wo_date : '',
                'contractor' => $wo->contractor,
                'pan_no' => $wo->pan_no,
                'rate' => $wo->rate,
                'awarded_cost' => $wo->awarded_cost,
                'completion_date' => $wo->completion_date != NULL ? $wo->completion_date : '',
                'barchart_given' => $wo->barchart_given,
                'ps_cost' => $wo->ps_cost,
                'lapse_date' => ($wo->lapse_date != NULL && $wo->lapse_date != '1970-01-01') ? $wo->lapse_date : '',
                'additional_ps_cost' => $wo->additional_ps_cost,
                'dlp' => $wo->dlp,
                'document' => $wo->document,
                'dlp_period' => $wo->dlp_period != NULL ? $wo->dlp_period : 0,
                'dlp_submitted' => $wo->dlp_submitted,
                    // 'assigned_engineer' => $wo->assigned_engineer,
                    // 'designation' => $wo->designation,
                    // 'mobile' => $wo->mobile
            );
            $this->data['role_id'] = $session['role_id'];
            $this->data['selected'] = json_encode($selected);
            $this->data['islocked'] = ($wo->id > 0 && $session['role_id'] > 3) ? 1 : 0;
            $this->data['title'] = 'Work Order';
            $this->data['heading'] = 'Work Order - RIDF';
            $this->data['subheading'] = 'Work Order Entry';
            $this->data['content'] = $this->parser->parse('wo_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function wo_save() {
        $input = $this->input->post();
        $id = $this->model->wo_save($input);
        $this->upload($id);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Wo information successfully saved'));
        }
        redirect(base_url('ridf/wo'));
    }

    function get_wo_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_wo_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function wo_remove() {
        $id = $this->input->get('id');
        $this->model->wo_remove($id);
        echo TRUE;
    }

    function upload($id) {
        $path = 'uploads/ridf/wo';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $pdf = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $pdf['upload_data']['file_name'];
            if (strlen($old_file) > 0) {
                $new_file = $path . '/' . $id . $pdf['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'document' => $new_file
                );
                $this->db->where('id', $id);
                $this->db->update(RIDF_WO, $input);
            }
        }
    }

    function wp($status = 0) {
        $session = $this->common->get_session();

        $wp_list = $this->model->get_wp_list($status);
        // print_r($wp_list);exit;
        $this->data['role_id'] = $session['role_id'];
        $this->data['wp'] = json_encode($wp_list);
        $this->data['status'] = $status;
        $this->data['heading'] = 'Work Progress List - RIDF';
        $this->data['subheading'] = $status == 0 ? 'Not-Started' : ($status == 5 ? 'Completed' : 'Work-In-Progress');
        $this->data['content'] = $this->parser->parse('wp_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wp_entry($id, $status) {
        $session = $this->common->get_session();
        $wp = $this->model->get_wp_info($id, $status);
        // print_r($wp);exit;
        $this->data['wp'] = json_encode($wp);
        $this->data['role_id'] = json_encode($session['role_id']);
        $this->data['status'] = $status;
        $this->data['heading'] = 'Work Progress Entry - RIDF';
        $this->data['subheading'] = $status == 0 ? 'Not-Started' : ($status == 5 ? 'Completed' : 'Work-In-Progress');
        $this->data['content'] = $this->parser->parse('wp_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wp_save() {
        $input = $this->input->post();
        $id = $this->model->wp_save($input);
        $this->wp_upload($id, $input['id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'WP information successfully saved'));
        }
        redirect(base_url('ridf/wp/' . $input['status']));
    }

    function wp_upload($id, $ridf_id) {
        $path = 'uploads/ridf/progress/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'jpg|jpeg|png';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image1')) {
            $error = array('error' => $this->upload->display_errors());
            echo 'image1';
            // var_dump($error);
            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            if (strlen($old_file) > 0) {
                $new_file = $path . $ridf_id . '_' . $id . '_' . 'image1' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image1' => $new_file
                );
                $this->db->where('id', $ridf_id);
                $this->db->update('ridf', $input);
                $this->db->where('id', $id);
                $this->db->update(RIDF_PROGRESS, $input);
            }
        }
        if (!$this->upload->do_upload('image2')) {
            $error = array('error' => $this->upload->display_errors());
            echo 'image2';
            var_dump($error);
            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            if (strlen($old_file) > 0) {
                $new_file = $path . '/' . $ridf_id . '_' . $id . '_' . 'image2' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image2' => $new_file
                );
                $this->db->where('id', $ridf_id);
                $this->db->update(RIDF, $input);
                $this->db->where('id', $id);
                $this->db->update(RIDF_PROGRESS, $input);
            }
        }
        if (!$this->upload->do_upload('image3')) {
            $error = array('error' => $this->upload->display_errors());
            echo 'image3';
            var_dump($error);
            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            if (strlen($old_file) > 0) {
                $new_file = $path . '/' . $ridf_id . '_' . $id . '_' . 'image3' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image3' => $new_file
                );
                $this->db->where('id', $ridf_id);
                $this->db->update(RIDF, $input);
                $this->db->where('id', $id);
                $this->db->update(RIDF_PROGRESS, $input);
            }
        }
    }

    // function wp_document($id){
    //     $wp_document = $this->model->get_wp_document($id);
    //     echo json_encode($wp_document);
    // }

    function wp_document($id) {
        // $this->load->model('Ridf_model');
        $wp_document = $this->model->get_wp_document($id);
        header('Content-Type: application/json');
        echo json_encode($wp_document);
    }

    ################################################################################
    ################################# REPORT #######################################

    function report() {
        $this->data['heading'] = 'Report Overview - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('report', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_state_summary() {
        $selected = array(
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'ac_id' => $_post['ac_id']
                );
            }
        }
        $this->data['ac'] = json_encode($this->model->get_assembly_list());
        $this->data['list'] = json_encode($this->model->get_rpt_state_summary($selected['ac_id']));
        $this->data['heading'] = 'State Summary Report - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_state_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_agency_progress() {
        $selected = array(
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'ac_id' => $_post['ac_id']
                );
            }
        }
        $this->data['ac'] = json_encode($this->model->get_assembly_list());
        $this->data['list'] = json_encode($this->model->get_rpt_agency_progress($selected['ac_id']));
        $this->data['heading'] = 'Agency wise Progress - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_agency_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_road_type_progress() {
        $selected = array(
            'road_type' => 'Bituminious Road',
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'road_type' => $_post['road_type'],
                    'ac_id' => $_post['ac_id']
                );
            }
        }
        $list = $this->model->get_rpt_road_type_progress($selected['road_type'], $selected['ac_id']);
        $this->data['ac'] = json_encode($this->model->get_rpt_assembly_list());
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Road Type wise Progress - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_road_type_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_work_type_progress() {
        $selected = array(
            'work_type' => 'Construction',
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'work_type' => $_post['work_type'],
                    'ac_id' => $_post['ac_id']
                );
            }
        }
        $list = $this->model->get_rpt_work_type_progress($selected['work_type'], $selected['ac_id']);
        $this->data['ac'] = json_encode($this->model->get_assembly_list());
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Work Type wise Progress - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_work_type_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_ps_work_status() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => '',
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'ac_id' => $_post['ac_id']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_rpt_ps_work_status($selected['district_id'], $selected['block_id'], $selected['ac_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['ac'] = json_encode($this->model->get_assembly_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'PS wise Work Status Report - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_ps_work_status', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_work_progress() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => '',
            'wp_id' => '6',
            'ac_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'ac_id' => $_post['ac_id'],
                    'wp_id' => $_post['wp_id']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_rpt_work_progress($selected['district_id'], $selected['block_id'], $selected['wp_id'], $selected['ac_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['ac'] = json_encode($this->model->get_assembly_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Work wise Progress Report - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_work_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_work_progress_details($ridf_id) {
        $list = $this->model->get_rpt_work_progress_details($ridf_id);
        $this->data['list'] = sizeof($list) > 0 ? json_encode($list) : '';
        $this->data['title'] = 'Work Progress Report';
        $this->data['heading'] = 'Work Progress Details Report - RIDF';
        $this->data['subheading'] = $list[0]->name;
        $this->data['content'] = $this->parser->parse('rpt_work_progress_details', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_qm_summary() {
        $selected = array(
            'start_date' => '01/' . date('m/Y'),
            'end_date' => date('d/m/Y')
        );
        // if ($this->input->post()) {
        //     $_post = $this->input->post();
        //     if (sizeof($_post) > 0) {
        //         $selected = array(
        //             'start_date' => $_post['start_date'],
        //             'end_date' => $_post['end_date']
        //         );
        //     }
        // }
        // $list = $this->model->get_rpt_qm_summary($selected['start_date'], $selected['end_date']);
        // $this->data['selected'] = json_encode($selected);
        // $this->data['list'] = json_encode($list);
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['heading'] = 'Quality Monitoring Report - RIDF';
        $this->data['subheading'] = 'RIDF schemes';
        $this->data['title'] = 'RIDF';
        $this->data['content'] = $this->parser->parse('rpt_qm_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_rpt_qm_list() {
        $data = $this->input->get();
        $start_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['start_date'])));
        $end_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['end_date'])));
        // var_dump($start_date); exit;
        echo json_encode($this->model->get_rpt_qm_summary($start_date, $end_date));
    }

    function add_tender($id) {
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_tender_list($id);
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'ref_no' => $row->ref_no,
                'name' => $row->name,
                'length' => $row->length,
                'agency' => $row->agency,
                'tender_number' => $row->tender_number,
                'tender_publication_date' => $row->tender_publication_date,
                'tender_status' => $row->tender_status,
                'bid_opeaning_date' => $row->bid_opeaning_date,
                'bid_closing_date' => $row->bid_closing_date,
                'evaluation_status' => $row->evaluation_status,
                'bid_opening_status' => $row->bid_opening_status,
                'bid_matured_status' => $row->bid_matured_status
            );
            if ($row->tender_status == 3) {
                $selected = array(
                    'id' => $id,
                    'district' => $row->district,
                    'block' => $row->block,
                    'ref_no' => $row->ref_no,
                    'name' => $row->name,
                    'length' => $row->length,
                    'agency' => $row->agency,
                    'tender_number' => '',
                    'tender_publication_date' => null,
                    'tender_status' => 0,
                    'bid_opeaning_date' => null,
                    'bid_closing_date' => null,
                    'evaluation_status' => null,
                    'bid_opening_status' => null,
                    'bid_matured_status' => null
                );
            }
        }
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'Tender';
        $this->data['heading'] = 'Tender - RIDF';
        $this->data['subheading'] = 'Tender Entry';
        $this->data['content'] = $this->parser->parse('tender_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    ######################### RIDF BRIDGE #############################

    function bridge_overview() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 18);
        }
        $this->data['overview_count'] = json_encode($this->model->get_bridge_overview_count());
        $this->data['title'] = 'RIDF-BRIDGE SCHEME SUMMARY';
        $this->data['heading'] = 'Overview - RIDF-BRIDGE';
        $this->data['subheading'] = 'OVERVIEW OF RIDF-BRIDGE PROJECT';
        $this->data['content'] = $this->parser->parse('bridge_overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function bridge_master() {
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'block_id' => 0,
            'category_id' => 0
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'category_id' => $_post['category_id']
                );
                $list = $this->model->get_bridge_list($selected['district_id'], $selected['block_id'], $selected['category_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['overview'] = json_encode($this->model->get_scheme_summary());
        $this->data['list'] = json_encode($list);
        $this->data['title'] = 'RIDF-BRIDGE SCHEME SUMMARY';
        $this->data['heading'] = 'Overview - RIDF-BRIDGE';
        $this->data['subheading'] = 'OVERVIEW OF RIDF-BRIDGE PROJECT';
        $this->data['content'] = $this->parser->parse('bridge_master', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_bridge_list() {
        $data = $this->input->get();
        // print_r($data); exit;
        echo json_encode($this->model->get_bridge_list($data['district_id'], $data['block_id'], $data['category_id']));
    }

    function bridge_entry($id = 0) {
        $session = $this->common->get_session();
        $subheading = '';
        $block_list = array();
        $selected = array(
            'id' => 0,
            'district_id' => '',
            'funding_id' => '',
            'block_id' => '',
            'name' => '',
            'agency' => '',
            'aot_date' => '',
            'wo_date' => '',
            'complete_date' => ''
        );
        if ($id > 0) {
            $row = $this->model->get_bridge_info($id);
            // print_r($row);exit;
            $selected = array(
                'id' => $row->id,
                'district_id' => $row->district_id,
                'block_id' => $row->block_id,
                'funding_id' => $row->category_id,
                'name' => $row->name,
                'agency' => $row->agency,
                'aot_date' => isset($row->aot_date) ? date('d/m/Y', strtotime($row->aot_date)) : '',
                'wo_date' => isset($row->wo_date) ? date('d/m/Y', strtotime($row->wo_date)) : '',
                'complete_date' => isset($row->complete_date) ? date('d/m/Y', strtotime($row->complete_date)) : ''
            );
            $subheading = $row->name;
        }
        $district_list = $this->common->get_district_list();

        $this->data['district'] = sizeof($district_list) > 0 ? json_encode($district_list) : '';
        if ($selected['district_id'] > 0) {
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['title'] = 'RIDF BRIDGE MASTER';
        $this->data['heading'] = 'Bridge Master Entry';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('bridge_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function bridge_save() {
        $this->data = $this->input->post();
        $id = $this->model->bridge_save($this->data);
        if ($this->data['block_id'] == 0) {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Select Block'));
            redirect('ridf/ridf_bridge_entry');
        }
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data addded successfully'));
            redirect('ridf/bridge_master');
        }
    }

    function bridge_remove() {
        $id = $this->input->post();
        $this->model->bridge_delete($id['id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'danger', 'message' => 'Bridge Deleted'));
            redirect('ridf/bridge_master');
        }
    }

    ################################ BRIDGE QM #################################

    function bridge_qm() {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'month' => date('m'),
            'year' => date('Y')
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'month' => $_post['month'],
                    'year' => $_post['year']
                );
                $list = $this->model->get_bridge_qm_list($selected['month'], $selected['year']);
            }
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Bridge Quality Monitoring Overview';
        $this->data['subheading'] = 'SQM Overall Satus';
        $this->data['title'] = 'Periodic Assigned SQM';
        $this->data['content'] = $this->parser->parse('bridge_qm_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function bridge_qm_entry() {
        $session = $this->common->get_session();
        if ($session['role_id'] == 4) {
            $scheme_list = array();
            $selected = array(
                'district_id' => '',
                'month' => date('m'),
                'year' => date('Y'),
                'sqm_id' => ''
            );
            if ($this->input->post()) {
                $_post = $this->input->post();
                if (sizeof($_post) > 0) {
                    $selected = array(
                        'district_id' => $_post['district_id'],
                        'month' => $_post['month'],
                        'year' => $_post['year'],
                        'sqm_id' => $_post['sqm_id'],
                    );
                    $scheme_list = $this->model->get_bridge_qm_scheme_list($selected['district_id'], $selected['sqm_id'], $selected['month'], $selected['year']);
                }
            }
            $district_list = $this->common->get_district_list();
            $sqm_list = $this->model->get_sqm_list();
            $this->data['sqm'] = json_encode($sqm_list);
            $this->data['selected'] = json_encode($selected);
            $this->data['district'] = json_encode($district_list);
            $this->data['scheme'] = json_encode($scheme_list);
            $this->data['heading'] = 'Bridge Quality Monitoring Entry';
            $this->data['subheading'] = 'Assigned SQM';
            $this->data['title'] = 'Assigned Bridge to SQM';
            $this->data['content'] = $this->parser->parse('bridge_qm_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        } else {
            redirect('ridf/bridge_qm');
        }
    }

    function bridge_qm_save() {
        $data = $this->input->post();
        $this->model->bridge_qm_save($data);
        redirect('ridf/bridge_qm');
    }

    function bridge_qm_start($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_qm_caption($id, $arr[3]);
            $this->data['qm_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['bridge_id'] = $caption[0]->bridge_id;
            $this->data['selected'] = json_encode($this->model->get_bridge_qm_image_list($id, $arr[3]));
            $this->data['total_img'] = json_encode($this->model->get_bridge_qm_total_image_list($id, $arr[3]));
            $this->data['progress'] = json_encode($this->model->get_bridge_qm_caption($id, $arr[3]));
            $this->data['heading'] = 'Bridge Quality Monitoring';
            $this->data['subheading'] = 'need to upload 10-15 images';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_qm_start', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function bridge_qm_save_start() {
        $data = $this->input->post();
        $qm_id = $data['qm_id'];
        $arr = explode('-', $qm_id);
        $qm_id = $arr[0];
        $id = $this->model->bridge_qm_save_start($qm_id, $data);
        if ($id > 0) {
            $path = 'uploads/ridf/bridge_qm/';
            if (!file_exists('./' . $path)) {
                mkdir('./' . $path, 0777, true);
            }
            $desc = $data['desc'];
            ;
            $input = array(
                'isactive' => -1
            );
            $this->db->where('inspection_id', $id);
            $this->db->where('seq_id > ' . sizeof($desc));
            $this->db->update(RIDF_BRIDGE_QM_IMAGE, $input);
            for ($i = 0; $i < sizeof($desc); $i++) {
                $image_id = $this->model->bridge_qm_save_image($i, $id, $desc);
                $new_file = '';
                $_FILES['file']['name'] = $_FILES['image']['name'][$i];
                $_FILES['file']['type'] = $_FILES['image']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['image']['error'][$i];
                $_FILES['file']['size'] = $_FILES['image']['size'][$i];

                $config['upload_path'] = './' . $path;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['overwrite'] = 1;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file')) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $image = array('upload_data' => $this->upload->data());
                    $old_file = $path . $image['upload_data']['file_name'];
                    $new_file = $path . $id . '_' . $image_id . $image['upload_data']['file_ext'];
                    rename($old_file, $new_file);
                    if (strlen($new_file) > 0) {
                        $input = array(
                            'image' => $new_file
                        );
                        $this->db->where('id', $image_id);
                        $this->db->update(RIDF_BRIDGE_QM_IMAGE, $input);
                    }
                }
            }
            $input = array(
                'status' => 1,
                'physical_progress' => $data['physical_progress'],
                'financial_progress' => $data['financial_progress'],
                'current_work_of_stage' => $data['stage_of_work']
            );
            $this->db->where('sqm_id', $qm_id);
            $this->db->where('bridge_id', $data['bridge_id']);
            $this->db->update(RIDF_BRIDGE_QM, $input);
        }
        $flag = TRUE;
        if ($flag) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'QM information successfully saved'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'QM information not saved'));
        }
        redirect(base_url('ridf/bridge_qm'));
    }

    function bridge_qm_submit($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_qm_caption($id, $arr[3]);
            $this->data['qm_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['bridge_id'] = $caption[0]->bridge_id;
            $this->data['heading'] = 'Overall Quality Grading Report';
            $this->data['subheading'] = 'Submit your final report';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_qm_submit', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function bridge_qm_upload_submit($id, $bridge_id) {
        $path = 'uploads/ridf/bridge_qm/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $config['overwrite'] = 1;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . $file['upload_data']['file_name'];
            $new_file = $path . $id . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'document' => $new_file
            );
            $this->db->where('sqm_id', $id);
            $this->db->where('bridge_id', $bridge_id);
            $this->db->update(RIDF_BRIDGE_QM, $input);
            return true;
        }
    }

    function bridge_qm_save_submit() {
        $data = $this->input->post();
        $id = $this->model->bridge_qm_save_submit($data);
        $input = array(
            'status' => 2,
            'remarks' => $data['remarks']
        );
        $this->db->where('sqm_id', $data['qm_id']);
        $this->db->where('bridge_id', $data['bridge_id']);
        $this->db->update(RIDF_BRIDGE_QM, $input);
        $this->bridge_qm_upload_submit($data['qm_id'], $data['bridge_id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Final report has been submitted successfully'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can not be saved right now'));
        }
        redirect(base_url('ridf/bridge_qm'));
    }

    function bridge_qm_report_view($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_qm_caption($id, $arr[3]);
            $this->data['caption'] = json_encode($this->model->get_bridge_qm_caption($id, $arr[3]));
            $this->data['agency'] = $caption[0]->agency;
            $this->data['selected'] = json_encode($this->model->get_bridge_qm_image_list($id, $arr[3]));
            $this->data['heading'] = 'Bridge QM Report View';
            $this->data['subheading'] = 'only view mode';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_qm_report_view', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    ################################################################################    
    ############################# BRIDGE INSPECTION ################################    

    function bridge_inspection() {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'month' => date('m'),
            'year' => date('Y')
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'month' => $_post['month'],
                    'year' => $_post['year']
                );
                $list = $this->model->get_bridge_inspection_list($selected['month'], $selected['year']);
            }
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Bridge Inspection Overview';
        $this->data['subheading'] = 'Inspection Overall Satus';
        $this->data['title'] = 'Periodic Assigned ';
        $this->data['content'] = $this->parser->parse('bridge_inspection_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function bridge_inspection_entry() {
        $session = $this->common->get_session();
        if ($session['role_id'] == 4) {
            $scheme_list = array();
            $selected = array(
                'district_id' => '',
                'month' => date('m'),
                'year' => date('Y'),
                'sqm_id' => ''
            );
            if ($this->input->post()) {
                $_post = $this->input->post();
                if (sizeof($_post) > 0) {
                    $selected = array(
                        'district_id' => $_post['district_id'],
                        'month' => $_post['month'],
                        'year' => $_post['year'],
                        'sqm_id' => $_post['sqm_id'],
                    );
                    $scheme_list = $this->model->get_bridge_inspection_scheme_list($selected['district_id'], $selected['sqm_id'], $selected['month'], $selected['year']);
                }
            }
            $district_list = $this->common->get_district_list();
            $sqm_list = $this->model->get_sqm_list();
            $this->data['sqm'] = json_encode($sqm_list);
            $this->data['selected'] = json_encode($selected);
            $this->data['district'] = json_encode($district_list);
            $this->data['scheme'] = json_encode($scheme_list);
            $this->data['heading'] = 'Bridge Inspection Entry';
            $this->data['subheading'] = 'Assigned SQM';
            $this->data['title'] = 'Assigned Bridge to SQM';
            $this->data['content'] = $this->parser->parse('bridge_inspection_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        } else {
            redirect('ridf/bridge_inspection');
        }
    }

    function bridge_inspection_save() {
        $data = $this->input->post();
        $this->model->bridge_inspection_save($data);
        redirect('ridf/bridge_inspection');
    }

    function bridge_inspection_start($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_inspection_caption($id, $arr[3]);
            $this->data['qm_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['bridge_id'] = $caption[0]->bridge_id;
            $this->data['selected'] = json_encode($this->model->get_bridge_inspection_image_list($id, $caption[0]->bridge_id));
            $this->data['total_img'] = json_encode($this->model->get_bridge_inspection_total_image_list($id, $arr[3]));
            $this->data['details'] = json_encode($this->model->get_bridge_inspection_caption($id, $arr[3]));
            $this->data['heading'] = 'BRIDGE Inspection Images';
            $this->data['subheading'] = 'Upload Images (atleast 4 images)';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_inspection_start', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function bridge_inspection_save_start() {
        $data = $this->input->post();
        $qm_id = $data['qm_id'];
        $arr = explode('-', $qm_id);
        $qm_id = $arr[0];
        $id = $this->model->bridge_inspection_save_start($qm_id, $data);
        if ($id > 0) {
            $path = 'uploads/ridf/bridge_inspection/';
            if (!file_exists('./' . $path)) {
                mkdir('./' . $path, 0777, true);
            }
            $desc = $data['desc'];
            //var_dump($desc);exit;
            $input = array(
                'isactive' => -1
            );
            $this->db->where('inspection_id', $id);
            $this->db->where('seq_id > ' . sizeof($desc));
            $this->db->update(RIDF_BRIDGE_INSPECTION_IMAGE, $input);
            for ($i = 0; $i < sizeof($desc); $i++) {
                $image_id = $this->model->bridge_inspection_save_image($i, $id, $desc);
                $new_file = '';
                $_FILES['file']['name'] = $_FILES['image']['name'][$i];
                $_FILES['file']['type'] = $_FILES['image']['type'][$i];
                $_FILES['file']['tmp_name'] = $_FILES['image']['tmp_name'][$i];
                $_FILES['file']['error'] = $_FILES['image']['error'][$i];
                $_FILES['file']['size'] = $_FILES['image']['size'][$i];

                $config['upload_path'] = './' . $path;
                $config['allowed_types'] = 'jpg|png|jpeg';
                $config['max_size'] = 2048;
                $config['overwrite'] = 1;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file')) {
                    $error = array('error' => $this->upload->display_errors());
                } else {
                    $image = array('upload_data' => $this->upload->data());
                    $old_file = $path . $image['upload_data']['file_name'];
                    $new_file = $path . $id . '_' . $image_id . $image['upload_data']['file_ext'];
                    rename($old_file, $new_file);
                    if (strlen($new_file) > 0) {
                        $input = array(
                            'image' => $new_file
                        );
                        $this->db->where('id', $image_id);
                        $this->db->update(RIDF_BRIDGE_INSPECTION_IMAGE, $input);
                    }
                }
            }
            $input = array(
                'status' => 1,
                'fundation' => $data['fundation'],
                'length' => $data['length'],
                'width' => $data['width'],
                'hfl' => $data['hfl'],
                'ofl' => $data['ofl'],
                'obstruction' => $data['obstruction'],
                'traffic_category' => $data['traffic_category'],
                'lbl' => $data['lbl'],
                'proposed_bridge' => $data['proposed_bridge'],
                'super_structure' => $data['super_structure'],
                'linear_waterway' => $data['linar_waterway'],
                'linear_water_provided' => $data['linear_water_provided']
            );
            $this->db->where('sqm_id', $qm_id);
            $this->db->where('bridge_id', $data['bridge_id']);
            $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
        }
        $flag = TRUE;
        if ($flag) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Inspection information successfully saved'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Inspection information not saved'));
        }
        redirect(base_url('ridf/bridge_inspection'));
    }

    function bridge_inspection_submit($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_inspection_caption($id, $arr[3]);
            $this->data['inspection_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['bridge_id'] = $caption[0]->bridge_id;
            $this->data['heading'] = 'Overall Inspection Grading Report';
            $this->data['subheading'] = 'Submit your final report';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_inspection_submit', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function bridge_inspection_upload_submit($id, $bridge_id) {
        $path = 'uploads/ridf/bridge_inspection/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $config['overwrite'] = 1;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . $file['upload_data']['file_name'];
            $new_file = $path . $id . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'document' => $new_file
            );
            $this->db->where('sqm_id', $id);
            $this->db->where('bridge_id', $bridge_id);
            $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
            return true;
        }
    }

    function bridge_inspection_save_submit() {
        $data = $this->input->post();
        $id = $this->model->bridge_inspection_save_submit($data);
        $input = array(
            'status' => 2
        );
        $this->db->where('sqm_id', $data['inspection_id']);
        $this->db->where('bridge_id', $data['bridge_id']);
        $this->db->update(RIDF_BRIDGE_INSPECTION, $input);
        $this->bridge_inspection_upload_submit($data['inspection_id'], $data['bridge_id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Final report has been submitted successfully'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can not be saved right now'));
        }
        redirect(base_url('ridf/bridge_inspection'));
    }

    function bridge_inspection_report_view($id) {
        if ($id > 0) {
            $arr = explode('-', $id);
            $id = $arr[0];
            $caption = $this->model->get_bridge_inspection_caption($id, $arr[3]);
            $this->data['caption'] = json_encode($this->model->get_bridge_inspection_caption($id, $arr[3]));
            $this->data['agency'] = $caption[0]->agency;
            $this->data['selected'] = json_encode($this->model->get_bridge_inspection_image_list($id, $caption[0]->bridge_id));
            $this->data['heading'] = 'BRIDGE Inspection Report View';
            $this->data['subheading'] = 'inspection report view';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('bridge_inspection_report_view', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    ############################################################################
    ############################# RIDF Requisition #############################

    function requisition() {
        $session = $this->common->get_session();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id']
                );
            }
        }
        $list = $this->model->get_curr_requisition_list($selected);
        $district_list = $this->common->get_district_list();
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['title'] = 'Requisition';
        $this->data['heading'] = 'Requisition - RIDF';
        $this->data['subheading'] = 'Requisition';
        $this->data['content'] = $this->parser->parse('requisition', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_requisition_list() {
        $data = $this->input->get();
        // print_r($data); exit;
        echo json_encode($this->model->get_requisition_list($data['district_id'], $data['block_id'], $data['category_id']));
    }

    function requisition_entry($ridf_id = 0) {
        $selected = array(
            'id' => 0,
            'agency_id' => '',
            'district_id' => '',
            'category_id' => '',
            'ridf_id' => $ridf_id
        );
        if ($ridf_id > 0) {
            $this->db->select('agency_id, district_id, category_id');
            $this->db->where('id', $ridf_id);
            $query = $this->db->get(RIDF);
            $row = $query->row();
            $selected['agency_id'] = $row->agency_id;
            $selected['district_id'] = $row->district_id;
            $selected['category_id'] = $row->category_id;
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $agency_list = $this->model->get_ridf_agency_list();
        if (sizeof($agency_list) == 1) {
            $selected['agency_id'] = $agency_list[0]->id;
        }
        $this->data['agency'] = json_encode($agency_list);
        $this->data['district'] = json_encode($district_list);
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['work'] = json_encode($this->model->get_work_list($selected));
        // print_r($this->data['work']);exit;
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'RIDF Requisition Process';
        $this->data['heading'] = 'Requisition - RIDF';
        $this->data['subheading'] = 'RIDF Requisition Process';
        $this->data['content'] = $this->parser->parse('requisition_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_work_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_work_list($data));
    }

    function get_work_info() {
        $data = $this->input->get();
        echo json_encode($this->model->get_work_info($data['ridf_id']));
    }

    function get_prev_requisition_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_prev_requisition_list($data['ridf_id']));
    }

    function get_sanction_cost() {
        $data = $this->input->get();
        echo json_encode($this->model->get_sanction_cost($data['work_id']));
    }

    function requisition_save() {
        $data = $this->input->post();
        // print_r($data);exit;
        $id = $this->model->requisition_save($data);
        if ($id > 0) {
            $this->upload_claim($id);
            if (!isset($data['iscurrent'])) {
                $this->upload_approval($id);
            }
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'danger', 'message' => 'Data can not be saved now, please try later or contact with support team.'));
        }
        if ($data['iscurrent'] > 0) {
            redirect('ridf/requisition');
        } else {
            redirect('ridf/requisition_entry/');
        }
    }

    function expenditure_save() {
        $data = $this->input->get();
        echo json_encode($this->model->expenditure_save($data));
    }

    function upload_claim($id) {
        $path = 'uploads/ridf/requisition/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('claimed_doc')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . $image['upload_data']['file_name'];
            $new_file = $path . $id . '_claim' . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'claimed_doc' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(RIDF_REQUISITION, $input);
        }
    }

    function upload_approval($id) {
        $path = 'uploads/ridf/requisition/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('approved_doc')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . $image['upload_data']['file_name'];
            $new_file = $path . $id . '_approval' . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'approved_doc' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(RIDF_REQUISITION, $input);
        }
    }

    function requisition_preview($ridf_id, $id) {
        $session = $this->common->get_session();
        $work_info = $this->model->get_work_info($ridf_id, $id);
        $total_info = $this->model->get_total_requisition_info($ridf_id);
        $prev_requisition_list = $this->model->get_prev_requisition_list($ridf_id, $id);
        $curr_requisition_info = $this->model->get_curr_requisition_info($id);
        $this->data['work'] = json_encode($work_info);
        $this->data['total'] = json_encode($total_info);
        $this->data['prev'] = json_encode($prev_requisition_list);
        $this->data['curr'] = json_encode($curr_requisition_info);
        $this->data['title'] = 'Requisition';
        $this->data['heading'] = 'Requisition - RIDF';
        $this->data['subheading'] = 'Requisition';
        $this->data['content'] = $this->parser->parse('requisition_preview', $this->data, true);
        $this->parser->parse('../../templates/preview.php', $this->data);
    }

    function requisition_claim($ridf_id, $id) {
        $work_info = $this->model->get_work_info($ridf_id);
        $curr_requisition_info = $this->model->get_curr_requisition_info($id);
        $this->data['id'] = $id;
        $this->data['work'] = json_encode($work_info);
        $this->data['curr'] = json_encode($curr_requisition_info);
        $this->data['title'] = 'Upload requisition claim';
        $this->data['heading'] = 'Requisition Claim - RIDF';
        $this->data['subheading'] = 'UPload requisition claim';
        $this->data['content'] = $this->parser->parse('requisition_claim', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function requisition_claim_save() {
        $data = $this->input->post();
        $id = $data['id'];
        if ($id > 0) {
            $this->upload_claim($id);
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'danger', 'message' => 'Data can not be saved now, please try later or contact with support team.'));
        }
        redirect('ridf/requisition');
    }

    function remove_requisition_list() {
        $id = $this->input->post();
        $this->model->requisition_delete($id['id']);

        echo TRUE;
    }

    // ########################## Maintence ##############################


    function postdlp_maintenance() {

        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0,
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
                $list = $this->model->get_postdlp_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['district'] = json_encode($district_list);
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);

        $this->data['title'] = 'Post DPL Maintence';
        $this->data['heading'] = 'Post DPL Maintence - RIDF';
        $this->data['subheading'] = 'Post DPL Maintence';
        $this->data['content'] = $this->parser->parse('postdlp_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function postdlp_entry($id) {

        $session = $this->common->get_session();

        $block_list = array();
        if ($id > 0) {
            $row = $this->model->get_postdlp_info($id);
            // print_r($row);exit;
            $selected = array(
                'id' => $id,
                'district_id' => $row->district_id,
                'block' => $row->block,
                'scheme_id' => $row->scheme_id,
                'agency' => $row->agency,
                'name' => $row->name,
                'carriage_type' => '',
                'existing_carriage_value' => '',
                'roadway_type' => '',
                'existing_width' => '',
                'estimation_file' => '',
                'existing_bt_length' => '',
                'existing_cc_length' => '',
                'existing_total_length' => '',
                'prop_bt_length' => '',
                'prop_cc_length' => '',
                'prop_total_length' => '',
                'existing_traffic' => '',
                'prop_traffic' => '',
                'initial_rehabilitation_cost' => '',
                'renewal_cost' => '',
                'routine_year1' => '',
                'routine_year2' => '',
                'routine_year3' => '',
                'routine_year4' => '',
                'routine_year5' => '',
                'routine_total' => '',
                'total_project_cost' => ''
            );
        }


        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }

        $this->data['role_id'] = $session['role_id'];
        $this->data['selected'] = json_encode($selected);
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);

        $this->data['title'] = 'Post DPL Maintence';
        $this->data['heading'] = 'Post DPL Maintence - RIDF';
        $this->data['subheading'] = 'Post DPL Maintence';
        $this->data['content'] = $this->parser->parse('postdlp_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function postdlp_save() {
        $this->data = $this->input->post();
        $id = $this->model->postdlp_save($this->data);
        $this->upload_postdlp($id);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data addded successfully'));
            redirect('ridf/postdlp_maintenance');
        }
    }

    function upload_postdlp($id) {
        // $ext = rand(1, 1000000);
        $path = 'uploads/ridf/maintenance/post_dlp';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }

        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'jpg|png|jpeg|pdf';
        $config['max_size'] = 2048;
        $config['file_name'] = 'estimation_' . $id;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('estimation_file')) {
            $error = array('error' => $this->upload->display_errors());
            // Log error or flash message if needed
        } else {
            $data = $this->upload->data();
            $filename = $path . '/' . $data['file_name'];

            $update_data = array('estimation_file' => $filename);
            $this->db->where('id', $id);
            $this->db->update('ridf_postdlp_maintenance', $update_data);

            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Estimation file uploaded successfully.'));
        }
    }

    function postdlp_view_maintenance_admin($id) {
        $session = $this->common->get_session();
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_postdlp_view($id);
            // print_r($row);exit;
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'scheme_id' => $row->scheme_id,
                'agency' => $row->agency,
                'name' => $row->name,
                'carriage_type' => $row->carriage_type,
                'existing_carriage_value' => $row->existing_carriage_value,
                'roadway_type' => $row->roadway_type,
                'existing_width' => $row->existing_width,
                'estimation_file' => $row->estimation_file,
                'existing_bt_length' => $row->existing_bt_length,
                'existing_cc_length' => $row->existing_cc_length,
                'existing_total_length' => $row->existing_total_length,
                'prop_bt_length' => $row->prop_bt_length,
                'prop_cc_length' => $row->prop_cc_length,
                'prop_total_length' => $row->prop_total_length,
                'existing_traffic' => $row->existing_traffic,
                'prop_traffic' => $row->prop_traffic,
                'initial_rehabilitation_cost' => $row->initial_rehabilitation_cost,
                'renewal_cost' => $row->renewal_cost,
                'routine_year1' => $row->routine_year1,
                'routine_year2' => $row->routine_year2,
                'routine_year3' => $row->routine_year3,
                'routine_year4' => $row->routine_year4,
                'routine_year5' => $row->routine_year5,
                'routine_total' => $row->routine_total,
                'total_project_cost' => $row->total_project_cost
            );
        }

        $this->data['role_id'] = $session['role_id'];
        $this->data['selected'] = json_encode($selected);

        $this->data['title'] = 'Post DPL Maintence';
        $this->data['heading'] = 'Post DPL Maintence - RIDF';
        $this->data['subheading'] = 'Post DPL Maintence';
        $this->data['content'] = $this->parser->parse('postdlp_view_maintenance_admin', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
}
