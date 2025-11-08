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

class Capex extends MX_Controller
{

    var $data;

    function __construct()
    {
        parent::__construct();
        $this->load->model('capex_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array(); //echo 'am here'; exit;
    }
    function get_assembly_list()
    {
        $data = $this->input->get();
        echo json_encode($this->model->get_ac_list($data['district_id']));
    }

    function get_block_list()
    {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function overview()
    {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 20);
        }
        $this->data['overview'] = json_encode($this->model->get_scheme_summary());
        // $this->data['tender_and_wo_count'] = json_encode($this->model->get_tender_and_wo_count());
        $this->data['title'] = 'Capex SCHEME SUMMARY';
        $this->data['heading'] = 'Overview - Capex';
        $this->data['subheading'] = 'OVERVIEW OF Capex PROJECT';
        $this->data['content'] = $this->parser->parse('overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function index()
    {
        $session = $this->common->get_session();

        $list = [];
        $selected = [
            'district_id' => 0,
            'category_id' => 0,
            'type_id'     => ''
        ];

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
        if (count($district_list) == 1 && $selected['district_id'] == 0) {
            $selected['district_id'] = $district_list[0]->id;
        }

        $this->data['role_id']     = $session['role_id'];
        $this->data['category']    = json_encode($this->model->get_category_list());
        $this->data['district']    = json_encode($district_list);
        $this->data['type']        = json_encode($this->model->get_project_type_list());
        $this->data['selected']    = json_encode($selected);
        $this->data['list']        = json_encode($list);
        $this->data['heading']     = 'Capex Information';
        $this->data['subheading']  = 'View the project information';

        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0)
    {
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
        // print_r($this->data['district']);exit;
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['agency'] = json_encode($this->model->get_agency_list());
        $this->data['road'] = json_encode($this->model->get_road_type_list());
        $this->data['work'] = json_encode($this->model->get_work_type_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['survey'] = json_encode($survey_list);
        $this->data['title'] = 'CAPEX';
        $this->data['heading'] = 'Master Entry - CAPEX';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);

        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }



    function save()
    {
        $data = $this->input->post();
        // print_r($data);exit;
        $id = $this->model->save($data);

        if ($id > 0) {
            $this->session->set_flashdata('message', array(
                'type' => 'success',
                'message' => 'Data added successfully'
            ));
        } else {
            $this->session->set_flashdata('message', array(
                'type' => 'danger',
                'message' => 'Failed to save data'
            ));
        }

        redirect(CAPEX);
    }

    function tender()
    {   //echo 'am here - tender()'; exit;
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
        $this->data['heading'] = 'Tender - CAPEX';
        $this->data['subheading'] = 'Tender';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('tender', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_tender_list()
    {
        $data = $this->input->get();
        echo json_encode($this->model->get_tender_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function tender_entry($id)
    {
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
        $this->data['heading'] = 'Tender - CAPEX';
        $this->data['subheading'] = 'Tender Entry';
        $this->data['content'] = $this->parser->parse('tender_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function tender_save()
    {
        $data = $this->input->post();
        // print_r($data);exit;
        if ($this->model->tender_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Tender Save successfully'));
            redirect('capex/tender');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can\'t save now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }
    function wo()
    {
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
        $this->data['heading'] = 'Work Order - CAPEX';
        $this->data['subheading'] = 'Work Order Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('wo_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function get_wo_list()
    {
        // print_r("Hiiii");exit;
        $data = $this->input->get();
        // print_r($data);exit;
        echo json_encode($this->model->get_wo_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function wo_entry($id = 0)
    {
        $session = $this->common->get_session();
        if ($id > 0) {
            $wo = $this->model->get_wo_info($id);
            $selected = array(
                'id' => $wo->id,
                'capex_id' => $wo->capex_id,
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

            );
            $this->data['role_id'] = $session['role_id'];
            $this->data['selected'] = json_encode($selected);
            $this->data['islocked'] = ($wo->id > 0 && $session['role_id'] > 3) ? 1 : 0;
            $this->data['title'] = 'Work Order';
            $this->data['heading'] = 'Work Order - CAPEX';
            $this->data['subheading'] = 'Work Order Entry';
            $this->data['content'] = $this->parser->parse('wo_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }
    function wo_save()
    {
        $input = $this->input->post();
        $id = $this->model->wo_save($input);
        $this->upload($id);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Wo information successfully saved'));
        }
        redirect(base_url('capex/wo'));
    }


    function upload($id)
    {
        $path = 'uploads/capex/wo';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $config['upload_path']   = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 2048;
        $config['encrypt_name']  = TRUE;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            log_message('error', 'Upload error: ' . $error['error']);
        } else {
            $uploadData = $this->upload->data();
            $new_filename = $id . $uploadData['file_ext'];
            $new_filepath = $path . '/' . $new_filename;

            if (file_exists($uploadData['full_path'])) {
                if (file_exists('./' . $new_filepath)) {
                    unlink('./' . $new_filepath);
                }
                rename($uploadData['full_path'], './' . $new_filepath);
                $this->db->where('id', $id);
                $this->db->update(CAPEX_WO, ['document' => $new_filepath]);
            }
        }
    }

    function wo_remove()
    {
        $id = $this->input->get('id');
        // print_r($id);exit;
        $this->model->wo_remove($id);
        echo TRUE;
    }


    function wp($status = 0)
    {
        $session = $this->common->get_session();

        $wp_list = $this->model->get_wp_list($status);
        // print_r($wp_list);exit;
        $this->data['role_id'] = $session['role_id'];
        $this->data['wp'] = json_encode($wp_list);
        $this->data['status'] = $status;
        $this->data['heading'] = 'Work Progress List - CAPEX';
        $this->data['subheading'] = $status == 0 ? 'Not-Started' : ($status == 5 ? 'Completed' : 'Work-In-Progress');
        $this->data['content'] = $this->parser->parse('wp_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wp_entry($id, $status)
    {
        $session = $this->common->get_session();
        $wp = $this->model->get_wp_info($id, $status);
        // print_r($wp);exit;
        $this->data['wp'] = json_encode($wp);
        $this->data['role_id'] = json_encode($session['role_id']);
        $this->data['status'] = $status;
        $this->data['heading'] = 'Work Progress Entry - CAPEX';
        $this->data['subheading'] = $status == 0 ? 'Not-Started' : ($status == 5 ? 'Completed' : 'Work-In-Progress');
        $this->data['content'] = $this->parser->parse('wp_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wp_save()
    {
        $input = $this->input->post();
        // print_r($input);exit;
        $id = $this->model->wp_save($input);
        $this->wp_upload($id, $input['id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'WP information successfully saved'));
        }
        redirect(base_url('capex/wp/' . $input['status']));
    }

    function wp_upload($id, $capex_id)
    {
        $path = 'uploads/capex/progress/';
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
                $new_file = $path . $capex_id . '_' . $id . '_' . 'image1' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image1' => $new_file
                );
                $this->db->where('id', $capex_id);
                $this->db->update(CAPEX, $input);
                $this->db->where('id', $id);
                $this->db->update(CAPEX_PROGRESS, $input);
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
                $new_file = $path . $capex_id . '_' . $id . '_' . 'image2' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image2' => $new_file
                );
                $this->db->where('id', $capex_id);
                $this->db->update(CAPEX, $input);
                $this->db->where('id', $id);
                $this->db->update(CAPEX_PROGRESS, $input);
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
                $new_file = $path . $capex_id . '_' . $id . '_' . 'image3' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image3' => $new_file
                );
                $this->db->where('id', $capex_id);
                $this->db->update(CAPEX, $input);
                $this->db->where('id', $id);
                $this->db->update(CAPEX_PROGRESS, $input);
            }
        }
    }

    function wp_image_view($id)
    {
        // print_r($id);exit;
        $session = $this->common->get_session();
        $wp_image = $this->model->get_wp_image($id);
        // echo "<pre>";
        // print_r($wp_image);exit;
        $this->data['wp_image'] = json_encode($wp_image);
        $this->data['role_id'] = json_encode($session['role_id']);
        $this->data['content'] = $this->parser->parse('wp_image_view', $this->data, true);
        $this->parser->parse('../../templates/preview.php', $this->data);
    }

    // ############   Capex Bridge ################

    function bridge_view()
    {
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
                $list = $this->model->get_bridge_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
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
        $this->data['title'] = 'CAPEX-BRIDGE SCHEME SUMMARY';
        $this->data['heading'] = 'Overview - CAPEX-BRIDGE';
        $this->data['subheading'] = 'OVERVIEW OF CAPEX-BRIDGE PROJECT';
        $this->data['content'] = $this->parser->parse('bridge_master', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }


    function bridge_entry($id = 0)
    {
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
            $row = $this->model->get_bridge_scheme_info($id);
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
        // print_r($this->data['district']);exit;
        $this->data['category'] = json_encode($this->model->get_category_list());
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['agency'] = json_encode($this->model->get_agency_list());
        $this->data['road'] = json_encode($this->model->get_road_type_list());
        $this->data['work'] = json_encode($this->model->get_work_type_list());
        $this->data['block'] = json_encode($block_list);
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['survey'] = json_encode($survey_list);
        $this->data['title'] = 'CAPEX BRIDGE';
        $this->data['heading'] = 'Master Entry - CAPEX BRIDGE';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);

        $this->data['content'] = $this->parser->parse('bridge_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }



    function bridge_save()
    {
        $data = $this->input->post(NULL, TRUE);

        // print_r($data);exit;

        if (!empty($data)) {
            $id = $this->model->bridge_save($data);
            if ($id) {
                $this->session->set_flashdata('message', [
                    'type'    => 'success',
                    'message' => 'Data added successfully'
                ]);
            } else {
                $this->session->set_flashdata('message', [
                    'type'    => 'danger',
                    'message' => 'Failed to save data'
                ]);
            }
        } else {
            $this->session->set_flashdata('message', [
                'type'    => 'warning',
                'message' => 'No data received'
            ]);
        }
        redirect('capex/bridge_view');
    }

    function bridge_tender()
    {   //echo 'am here - tender()'; exit;
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
        $this->data['approved'] = json_encode($this->model->get_bridge_tender_list($selected['district_id'], $selected['category_id'], $selected['type_id']));
        $this->data['title'] = 'Tender';
        $this->data['heading'] = 'Tender - Capex Bridge';
        $this->data['subheading'] = 'Tender';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('bridge_tender', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_bridge_tender_list()
    {
        $data = $this->input->get();
        echo json_encode($this->model->get_bridge_tender_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function bridge_tender_entry($id)
    {
        // print_r($id);exit;
        // var_dump($id);exit;
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_bridge_tender_info($id);
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
        $this->data['title'] = 'Bridge Tender';
        $this->data['heading'] = 'Bridge Tender - CAPEX';
        $this->data['subheading'] = 'Bridge Tender Entry';
        $this->data['content'] = $this->parser->parse('bridge_tender_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function bridge_tender_save()
    {
        $data = $this->input->post();
        // print_r($data);exit;
        if ($this->model->bridge_tender_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Tender Save successfully'));
            redirect('capex/bridge_tender');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can\'t save now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function bridge_wo()
    {
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
            $wo = $selected['district_id'] > 0 ? $this->model->get_bridge_wo_list($selected['district_id'], $selected['category_id'], $selected['type_id']) : array('hi');
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
        $this->data['content'] = $this->parser->parse('bridge_wo_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_bridge_wo_list(){
        $data = $this->input->get();
        echo json_encode($this->model->get_bridge_wo_list($data['district_id'], $data['category_id'], $data['type_id']));
    }

    function bridge_wo_entry($id = 0)
    {
        $session = $this->common->get_session();
        if ($id > 0) {
            $wo = $this->model->get_bridge_wo_info($id);
            $selected = array(
                'id' => $wo->id,
                'capex_bridge_id' => $wo->capex_bridge_id,
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
            $this->data['title'] = 'BRIDGE Work Order';
            $this->data['heading'] = 'Work Order - CAPEX BRIDGE';
            $this->data['subheading'] = 'BRIDGE Work Order Entry';
            $this->data['content'] = $this->parser->parse('bridge_wo_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function bridge_wo_save()
    {
        $input = $this->input->post();
        // print_r($input);exit;
        $id = $this->model->bridge_wo_save($input);
        $this->upload($id);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Wo information successfully saved'));
        }
        redirect(base_url('capex/bridge_wo'));
    }
}
