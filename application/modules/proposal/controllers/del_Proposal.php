<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 01-Jun-2023]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Proposal extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        $this->load->model('proposal_model', 'model');
        checkAuthSession();
        $this->data = array();
    }

    // function index() {
    //     $this->data['heading'] = 'Scheme';
    //     $this->data['subheading'] = 'Scheme';
    //     $this->data['title'] = 'Scheme';
    //     $this->data['content'] = $this->parser->parse('view', $this->data, true);
    //     $this->parser->parse('../../templates/template.php', $this->data);
    // }
    function index() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_proposal_info($selected['district_id'], $selected['block_id']);
            }
        }
        $district_list = $this->common->get_district_list();

        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        if (sizeof($block_list) == 1) {
            $selected['block_id'] = $block_list[0]->id;
        }
        $this->data['list'] = json_encode($this->model->get_proposal_info());
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Proposal';
        $this->data['subheading'] = 'Proposal';
        $this->data['title'] = 'Proposal';
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0) {
        $session = $this->common->get_session();
        $block_list = array();
        $gp_list = array();
        $selected = array(
            'id' => '0',
            'district_id' => '0',
            'block_id' => '0',
            'gp_list' => '0',
            'name' => '',
            'road_name' => '',
            'length' => '',
            'contactno' => '',
            'letter' => '',
            'unit' => '',
            'approximate_cost' => '',
            'agency' => '',
            'work_type' => '',
            'road_type' => '',
            'date' => date('d/m/Y'),
            'agency' => '',
            'information' => '',
            'action_taken' => '',
            'image' => ''
        );
        if ($id > 0) {
            $proposal = $this->model->get_proposal_list($id);
            $selected = array(
                'id' => $proposal->id,
                'district_id' => $proposal->district_id,
                'block_id' => $proposal->block_id,
                'gp_id' => $proposal->gp_id,
                'name' => $proposal->name,
                'road_name' => $proposal->road_name,
                'length' => $proposal->length,
                'contactno' => $proposal->contactno,
                'letter' => $proposal->letter,
                'unit' => $proposal->unit,
                'approximate_cost' => $proposal->approximate_cost,
                'work_type' => $proposal->work_type,
                'road_type' => $proposal->road_type,
                'date' => $proposal->date != NULL ? date('d/m/Y', strtotime($proposal->date)) : date('d/m/Y'),
                'agency' => $proposal->agency,
                'information' => $proposal->information,
                'action_taken' => $proposal->action_taken,
                'image' => $proposal->image
            );
            $block_list = $this->common->get_block_list($selected['district_id']);
            $gp_list = $this->common->get_gp_list($selected['block_id']);
        }
        $district_list = $this->common->get_district_list();

        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['gp'] = json_encode($gp_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Proposal';
        $this->data['subheading'] = 'Proposal';
        $this->data['title'] = 'Proposal';
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function get_gp_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_gp_list($data['block_id']));
    }

    function upload($id) {
        $path = 'uploads/proposal/';
        // var_dump($path);exit;
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 1024;
        $config['overwrite'] = 1;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            //var_dump($error);exit;
        } else {
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . $image['upload_data']['file_name'];
            $new_file = $path . $id . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'image' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(PROPOSAL, $input);
            return true;
        }
    }

    function save() {
        $input = $this->input->post();
        //var_dump($input);exit;
        $id = $this->model->save($input);
        $id > 0 ? $this->upload($id) : '';
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'proposal information successfully saved'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'proposal information not saved'));
        }
        redirect(base_url('proposal'));
    }

    function remove() {
        $id = $this->input->get('id');
        $this->model->remove($id);
        echo TRUE;
    }

//     function upload($id) {
//         $path = 'uploads/qm/';
//         if (!file_exists('./' . $path)) {
//             mkdir('./' . $path, 0777, true);
//         }
//         $config['upload_path'] = './' . $path;
//         $config['allowed_types'] = 'jpg|png|jpeg';
//         $config['max_size'] = 1024;
//         $config['overwrite'] = 1;
//         $this->load->library('upload', $config);
//         $this->upload->initialize($config);
//         if (!$this->upload->do_upload('imagefile')) {
//             $error = array('error' => $this->upload->display_errors());
//             return false;
//         } else {
//             $image = array('upload_data' => $this->upload->data());
//             $old_file = $path . $image['upload_data']['file_name'];
//             $new_file = $path . $id . $image['upload_data']['file_ext'];
//             rename($old_file, $new_file);
//             $input = array(
//                 'image' => $new_file
//             );
//             $this->db->where('id', $id);
//             // $this->db->update(NEW, $input);
//             return true;
//         }
//     }
//     function save() {
//         $data = $this->input->post();
//         //var_dump($data);exit;
//         $this->model->save($data);
//         redirect('qm');
//     }
//     function save_submit() {
//         $input = $this->input->post();
//         // var_dump($input);exit;
//         $id = $this->model->save_submit($input);
//         $this->upload_pdf($id);
//         if ($id > 0) {
//             $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'submit pdf successfully saved'));
//         } else {
//             $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'submit pdf not saved'));
//         }
//         redirect(base_url('qm'));
//     }
}
