<?php

/**
 * P&RD Development
 *
 * @package		wbrrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 08-Aug-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Log extends MX_Controller
{

    var $data;

    function __construct()
    {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        $this->load->model('log_model', 'model');
        $this->data = array();
    }

    function view($status = 0)
    {
        $session = $this->common->get_session();
        $this->session->set_userdata('menu', 26);
        $this->data['logs'] = json_encode($this->model->get_logs_call_list($session, $status));
        $this->data['isadmin'] = json_encode($session['user_id'] <= 3 && $session['role_id'] <= 3);
        $this->data['heading'] = 'Support Call Log';
        $this->data['subheading'] = ($status == 0 ? 'Pending' : ($status > 0 ? 'Resolved' : 'Rejected')) . ' List';
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0)
    {
        $arr = array(
            'id' => $id,
            'type_id' => 0,
            'ref_no' => '',
            'scheme_ref_no' => '',
            'contact_person' => '',
            'contact_no' => '',
            'contact_email' => '',
            'remarks' => '',
            'document' => ''
        );
        $info = $id > 0 ? $this->model->get_logs_call_info($id) : NULL;
        if ($info != NULL) {
            $arr = array(
                'id' => $info->id,
                'type_id' => $info->type_id,
                'ref_no' => $info->ref_no,
                'scheme_ref_no' => $info->scheme_ref_no,
                'contact_person' => $info->contact_person,
                'contact_no' => $info->contact_no,
                'contact_email' => $info->contact_email,
                'remarks' => $info->remarks,
                'document' => $info->document
            );
        }
        $this->data['selected'] = json_encode($arr);
        $this->data['type'] = json_encode($this->model->get_logs_type_list());
        $this->data['heading'] = 'Log a Support Call';
        $this->data['subheading'] = 'Add/Edit Support Call';
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save()
    {
        $session = $this->common->get_session();
        $this->data = $this->input->post();
        $id = $this->model->save($session['user_id'], $this->data);
        $this->upload($id);
        $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
        redirect('log/view');
    }

    function upload($id)
    {
        $ext = rand(1, 1000000);
        $path = 'uploads/logs/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['max_size'] = 1024;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . $image['upload_data']['file_name'];
            $new_file = $path . $id . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'document' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(LOGS_CALL, $input);
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Information Updated successfully'));
        }
    }




    // call log view 

    function log_info($status = 0)
    {
        $session = $this->common->get_session();
        $ref_no = $this->input->get('ref_no');
        // echo $ref_no;
        // die();
        $this->session->set_userdata('menu', 26);
        $row = $this->model->get_logs_calls_info($ref_no, $status);
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
        // die();

        $selected = array(
            'id' => $row->id,
            'type' => $row->type,
            'ref_no' => $row->ref_no,
            'name' => $row->name,
            'district' => $row->district,
            'block' => $row->block,
            'contact_person' => $row->contact_person,
            'contact_no' => $row->contact_no,
            'contact_email' => $row->contact_email,
            'remarks' => $row->remarks,
            'document' => $row->document,
            'status' => $row->status,
            'reason' => $row->reason,
            'scheme_ref_no' => $row->scheme_ref_no

        );
        $subheading = $row->type;

        $this->data['isadmin'] = json_encode($session['user_id'] <=3 && $session['role_id'] <= 3);
        $this->data['heading'] = 'Call Log View';
        $this->data['subheading'] = $subheading;
        $this->data['selected'] = json_encode($selected);


        $this->data['content'] = $this->parser->parse('log/log_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    // function resolve() {
    //     $id = $this->input->get('id');
    //     $this->model->resolve($id);
    //     $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Information Updated successfully'));
    //     redirect('log/view');
    // }
    function resolve()
    {
        $id = $this->input->get('id');
        $reason = $this->input->get('remarks');
        $this->model->resolve($id, $reason);
        $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Information Updated successfully'));
        redirect('log/view');
    }

    // function reject() {
    //     $this->data = $this->input->post();
    //     // echo "<pre>"."fjfjehj";
    //     // print_r($this->data);
    //     // echo "<pre>";
    //     // exit();
    //     $id = $this->data['id'];
    //     $reason = $this->data['remarks'];
    //     $this->model->reject($id, $reason);
    //     $this->session->set_flashdata('message', array('type' => 'delete', 'message' => 'Information Updated successfully'));
    //     redirect('log/view');
    // }
    // suvendu 
    function reject()
    {
        $this->data = $this->input->post();
        $id = $this->data['id'];
        $reason = $this->data['remarks'];
        $this->model->reject($id, $reason);
        $this->session->set_flashdata('message', array('type' => 'delete', 'message' => 'Information Updated successfully'));
        $this->output->set_output(json_encode(array('success' => true, 'redirect_url' => site_url('log/view'))));
    }
}
