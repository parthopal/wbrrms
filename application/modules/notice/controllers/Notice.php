<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 14-Oct-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Notice extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('notice_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }
    function index() {
        $this->data['notice'] = json_encode($this->model->get_notice_list());
        $this->data['heading'] = 'Notice';
        $this->data['subheading'] = 'Notice';
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function entry($id=0) {
        $selected = array(
            'id' => '0',
            'name' => '',
            'memo_no' => '',
            'date' => date('d/m/Y'),
            'document' => ''
        );
        if ($id > 0) {
            $notice = $this->model->get_notice_list($id);
            $selected = array(
                'id' => $notice->id,
                'name' => $notice->name,
                'memo_no' => $notice->memo_no,
                'date' => $notice->date != NULL ? date('d/m/Y', strtotime($notice->date)) : date('d/m/Y'),
                'document' => $notice->document
            );
        }
        $notice_list = $this->model->get_notice_list($id);
        $this->data['selected'] = json_encode($selected);
        $this->data['notice'] = json_encode($notice_list);
        //$this->data['notice'] = sizeof($notice_list) > 0 ? json_encode($notice_list) : '';
        $this->data['heading'] = 'Notice';
        $this->data['subheading'] = 'Notice';
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
        
    }
function upload($id) {
    $ext = rand(1, 1000000);
    $path = 'uploads/notice/';
    if (!file_exists('./' . $path)) {
        mkdir('./' . $path, 0777, true);
    }
    $config['upload_path'] = './' . $path;
    $config['allowed_types'] = 'pdf';
    $config['max_size'] = 2048;
    $this->load->library('upload', $config);
    if (!$this->upload->do_upload('userfile')) {
        $error = array('error' => $this->upload->display_errors());
        var_dump($error);exit;
    } else {
        $pdf = array('upload_data' => $this->upload->data());
        $old_file = $path . '/' . $pdf['upload_data']['file_name'];
        if(strlen($old_file) > 0 ) {
            $new_file = $path . $id . $pdf['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'document' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update('notice', $input);
        }
    }
}

    ##SAVE

    function save() {
        $input = $this->input->post();
        // var_dump($input);exit;
        $id = $this->model->save($input);
        $this->upload($id);
        if($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'notice information successfully saved'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'notice information not saved'));
        }
        redirect(base_url('notice'));
    }
    // function remove($id) {
    //     //$id = $_GET['id'];
    //     $this->model->_delete($id);
    //     $this->session->set_flashdata('message', array('type' => 'delete', 'message' => 'Data deleted successfully'));
    //     redirect('notice');
    // }
    function remove() {
        $id = $this->input->get('id');
        $this->model->remove($id);
        echo TRUE;
    }
}


?>