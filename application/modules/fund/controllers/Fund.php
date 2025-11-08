<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 05-Jul-2023]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Fund extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('fund_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }

    function view($category, $activity_id) {
        $activity = $this->model->get_activity_info($activity_id);

        $this->data['activity_id'] = $activity_id;
        $this->data['category'] = $category;
        $this->data['list'] = json_encode($this->model->get_fund_list($category, $activity_id));

        $this->data['heading'] = strtoupper($category . ' Fund');
        $this->data['subheading'] = $activity_id == 1 ? 'Receipt' : ($activity_id < 6 ? 'Allocation' : 'Utilisation');
        $this->data['title'] = $activity->name;
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($category, $activity_id, $id = 0) {
        $activity = $this->model->get_activity_info($activity_id);
        $district_list = $this->common->get_district_list();
        $district_id = sizeof($district_list) == 1 ? $district_list[0]->id : 0;
        $selected = array(
            'id' => $id,
            'category' => $category,
            'activity_id' => $activity_id,
            'expenditure' => 'schematic',
            'order_no' => '',
            'order_date' => '',
            'from_agency_id' => '',
            'to_agency_id' => '',
            'district_id' => $district_id,
            'block_id' => 0,
            'against_id' => 0,
            'amount' => '',
            'against_order_no' => '',
            'against_order_date' => '',
            'against_order_amount' => '',
            'pending_amount' => '0.00',
            'remarks' => ''
        );
        if ($id > 0) {
            $fund = $this->model->get_fund_info($id);
            $against = $this->model->get_against_ref($fund->against_id);
            $selected = array(
                'id' => $id,
                'category' => $category,
                'activity_id' => $activity_id,
                'expenditure' => $fund->expenditure,
                'order_no' => $fund->order_no,
                'order_date' => $fund->order_date,
                'from_agency_id' => $fund->from_agency_id,
                'to_agency_id' => $fund->to_agency_id,
                'district_id' => $fund->district_id,
                'block_id' => $fund->block_id,
                'against_id' => $fund->against_id,
                'amount' => $fund->amount,
                'against_order_no' => $against->order_no,
                'against_order_date' => $against->order_no,
                'against_order_amount' => $against->amount,
                'pending_amount' => $this->model->get_pending_amount($fund->against_id),
                'remarks' => $fund->remarks
            );
        }
        $against_list = array();
        $block_list = $selected['district_id'] > 0 ? $this->common->get_block_list($selected['district_id']) : array();
        $selected['block_id'] = ($selected['district_id'] > 0 && $selected['block_id'] == 0 && sizeof($block_list) == 1) ? $block_list[0]->id : $selected['block_id'];
        if ($activity->parent_id != null) {
            switch ($activity_id) {
                case 2: case 3: case 4:
                    $against_list = $this->model->get_against_list($activity->id, $activity->parent_id, $selected['expenditure']);
                    break;
                case 5:
                    $against_list = $this->model->get_against_list($activity->id, $activity->parent_id, $selected['expenditure'], $selected['district_id']);
                    break;
                case 6:
                    $against_list = $this->model->get_against_list($activity->id, $activity->parent_id, $selected['expenditure'], $selected['district_id'], $selected['block_id']);
                    break;
                case 7:
                    $against_list = $this->model->get_against_list($activity->id, $activity->parent_id, $selected['expenditure'], $selected['district_id'], $selected['from_agency_id']);
                    break;
                default:
                    break;
            }
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['from_agency'] = json_encode($this->model->get_agency_list($activity->from_id));
        $this->data['to_agency'] = json_encode($this->model->get_agency_list($activity->to_id));
        $this->data['against'] = json_encode($against_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = strtoupper($category . ' Fund');
        $this->data['subheading'] = $activity_id == 1 ? 'Receipt' : ($activity_id < 6 ? 'Allocation' : 'Utilisation');
        $this->data['title'] = $activity->name;
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save() {
        $data = $this->input->post();
        $id = $this->model->save($data);
        $this->upload($id);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
            redirect('fund/view/' . $data['category'] . '/' . $data['activity_id']);
        }
    }

    function upload($id) {
        $path = 'uploads/fund/srrp/';
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
            $this->db->where('id', $id);
            $this->db->update(FUND, $input);
            return true;
        }
    }

    function get_against_list() {
        $data = $this->input->get();
        $activity = $this->model->get_activity_info($data['activity_id']);
        $against_list = array();
        switch ($activity->id) {
            case 2: case 3: case 4:
                $against_list = $this->model->get_against_list($activity->id, $activity->parent_id, $data['expenditure']);
                break;
            case 5:
                $against_list = $data['district_id'] > 0 ? $this->model->get_against_list($activity->id, $activity->parent_id, $data['expenditure'], $data['district_id']) : array();
                break;
            case 6:
                $against_list = $data['block_id'] > 0 ? $this->model->get_against_list($activity->id, $activity->parent_id, $data['expenditure'], $data['district_id'], $data['block_id']) : '';
                break;
            case 7:
                $against_list = $data['district_id'] > 0 ? $this->model->get_against_list($activity->id, $activity->parent_id, $data['expenditure'], $data['district_id'], $data['from_agency_id']) : array();
                break;
            default:
                break;
        }
        echo json_encode($against_list);
    }

    function get_against_ref() {
        $data = $this->input->get();
        echo json_encode($this->model->get_against_ref($data['against_id']));
    }

    function get_pending_amount() {
        $data = $this->input->get();
        echo json_encode($this->model->get_pending_amount($data['against_id']));
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

}
