<?php

defined('BASEPATH') or exit('No direct script access allowed');

class SFURGENT extends MX_Controller
{

    var $data;

    function __construct()
    {
        parent::__construct();
        $this->load->model('sfurgent_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }
    // function overview()
    // {
    //     $session = $this->common->get_session();
    //     if ($session['role_id'] < 10 || $session['role_id'] == 21) {
    //         $this->session->set_userdata('menu', 144);
    //     }
    //     // $this->data['dashboard_count'] = json_encode($this->model->get_dashboard_count());
    //     // $this->data['tender_and_wo_count'] = json_encode($this->model->get_tender_and_wo_count());
    //     $this->data['title'] = 'SF';
    //     $this->data['heading'] = 'STATE FUND & OTHERS Overview';
    //     $this->data['subheading'] = 'overview';
    //     $this->data['content'] = $this->parser->parse('overview', $this->data, true);
    //     $this->parser->parse('../../templates/template.php', $this->data);
    // }

    function get_block_list()
    {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function get_gp_list()
    {
        $data = $this->input->get();
        echo json_encode($this->common->get_gp_list($data['block_id']));
    }
    function get_approved_list()
    {
        $data = $this->input->get();
        echo json_encode($this->model->get_approved_list($data['district_id']));
    }

    function index()
    {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 142);
        }
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'district_id' => '0',
            'block_id' => '0'
        );
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        if (sizeof($block_list) == 1) {
            $selected['block_id'] = $block_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['approved'] = json_encode($this->model->get_approved_list($selected['district_id']));
        $this->data['title'] = 'SF-URGENT';
        $this->data['heading'] = 'STATE FUND URGENT';
        $this->data['subheading'] = 'Scheme Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0)
    {
        $session = $this->common->get_session();
        $subheading = '';
        $block_list = array();
        $gp_list = array();
        $survey_list = array();
        $selected = array(
            'id' => 0,
            'tag' => '',
            'district_id' => '',
            'block_id' => '',
            'gp_id' => '',
            'village' => '',
            'name' => '',
            'agency' => '',
            'road_type' => '',
            'length' => '',
            'work_type' => '',
            'cost' => '',
            'survey_status' => 0,
            'admin_date' => ''
        );
        //var_dump($selected);exit;
        if ($id > 0) {
            $row = $this->model->get_scheme_info($id);
            $selected = array(
                'id' => $row->id,
                'tag' => $row->tag,
                'district_id' => $row->district_id,
                'block_id' => $row->block_id,
                'gp_id' => $row->gp_id,
                'village' => $row->village,
                'name' => $row->name,
                'agency' => $row->agency,
                'road_type' => $row->road_type,
                'length' => $row->length,
                'work_type' => $row->work_type,
                'cost' => $row->cost,
                'survey_status' => $row->survey_status,
                'admin_date' => $row->admin_approval_date != '' && isset($row->admin_approval_date) ? date('d/m/Y', strtotime($row->admin_approval_date)) : ''
            );
            $subheading = $row->name;
        }
        $district_list = $this->common->get_district_list();
        $this->data['district'] = sizeof($district_list) > 0 ? json_encode($district_list) : '';
        if ($selected['district_id'] > 0) {
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        if ($selected['block_id'] > 0) {
            $gp_list = $this->common->get_gp_list($selected['block_id']);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['block'] = json_encode($block_list);
        $this->data['gp'] = json_encode($gp_list);
        $this->data['survey'] = json_encode($survey_list);
        $this->data['title'] = 'STATE FUND URGENT';
        $this->data['heading'] = 'Project Work';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);

        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }


    function save()
    {
        $data = $this->input->post(); // no need to use $this->data unless required elsewhere
        // print_r($data);exit;
        if (!empty($data)) {
            $id = $this->model->save($data);

            if ($id > 0) {
                $this->upload_approval($id);
                $this->session->set_flashdata('message', [
                    'type'    => 'success',
                    'message' => 'Data added successfully'
                ]);
            } else {
                $this->session->set_flashdata('message', [
                    'type'    => 'error',
                    'message' => 'Failed to save data'
                ]);
            }
        } else {
            $this->session->set_flashdata('message', [
                'type'    => 'warning',
                'message' => 'No data submitted'
            ]);
        }

        redirect('sfurgent');
    }


    function remove()
    {
        $id = $this->input->post();
        $this->model->remove($id['id']);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'danger', 'message' => 'Bridge Deleted'));
            redirect('sfurgent');
        }
    }


    function upload_approval($id)
    {
        $path = 'uploads/sf_urgent/approved/';

        // Ensure directory exists
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }

        // File upload configuration
        $config['upload_path']   = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = 2048; // 2MB
        $config['file_name']     = $id . '_approval.pdf'; // final name

        $this->load->library('upload', $config);

        // Perform upload
        if (!$this->upload->do_upload('approved_doc')) {
            // Handle error (flash message or log)
            $error = $this->upload->display_errors();
            log_message('error', 'Upload failed: ' . $error);

            $this->session->set_flashdata('message', [
                'type'    => 'error',
                'message' => 'Approval document upload failed: ' . strip_tags($error)
            ]);
            return false;
        } else {
            // Upload success
            $upload_data = $this->upload->data();

            $input = [
                'approved_doc' => $path . $upload_data['file_name']
            ];

            $this->db->where('id', $id);
            $this->db->update(SFURGENT, $input);

            return true;
        }
    }
}
