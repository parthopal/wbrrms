<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 08-Aug-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Um extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        $this->load->model('common/session_model');
        $this->load->model('um_model', 'model');
        checkAuthSession();
        $this->data = array();
    }

    function index() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 2);
        }
        $list = array();
        $selected = array(
            'role_id' => 0
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'role_id' => $_post['role_id']
                );
                $list = $this->model->get_user_list($selected['role_id']);
            }
        }
        $role_list = $this->common->get_role_list();
        $this->data['role_id'] = $session['role_id'];
        $this->data['role'] = json_encode($role_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['content'] = $this->parser->parse('user_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($user_id = 0) {
        $session = $this->common->get_session();
        $block_list = array();
        $arr = array(
            'user_id' => 0,
            'name' => '',
            'mobile' => '',
            'email' => '',
            'role_id' => 0,
            'district_id' => 0,
            'block_id' => 0,
            'photo' => '',
            'username' => '',
            'password' => ''
        );
        $info = $user_id > 0 ? $this->model->get_user_info($user_id) : NULL;
        if ($info != NULL) {
            $arr = array(
                'user_id' => $info->id,
                'name' => $info->name,
                'mobile' => $info->mobile,
                'email' => $info->email,
                'role_id' => $info->role_id,
                'district_id' => $info->district_id,
                'block_id' => $info->block_id,
                'photo' => $info->photo,
                'username' => $info->username,
                'password' => $info->password
            );
            $block_list = $this->common->get_block_list($arr['district_id']);
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $arr['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['selected'] = json_encode($arr);
        $this->data['role'] = json_encode($this->common->get_role_list());
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->data['content'] = $this->parser->parse('user_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save_user() {
        $this->data = $this->input->post();
        $user_id = $this->model->save_user($this->data);
        $this->upload($user_id);
        if ($user_id > 0) {
            // echo '<script>alert("Data saved successfully."); window.location.href = "' . base_url('um') . '";</script>';
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'User addded successfully'));
            redirect('um');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Data can not be saved now'));
            //echo '<script>alert("Data can not be saved now");</script>';
        }
    }

    function upload($user_id) {
        $ext = rand(1, 1000000);
        $path = 'uploads/user/';
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
            $old_file = $path . '/' . $image['upload_data']['file_name'];
            $new_file = $path . '/' . $user_id . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'photo' => $new_file
            );
            $this->db->where('id', $user_id);
            $this->db->update(USER, $input);
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Information Updated successfully'));
        }
    }

    function role() {
        $this->data['role'] = json_encode($this->common->get_role_list());
        $this->data['content'] = $this->parser->parse('user_role', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_user_list() {
        $this->input->get();
        echo json_encode($this->model->get_user_list());
    }

    function get_menu_list() {
        $role_id = $this->input->get('role_id');
        echo json_encode($this->model->get_menu_list($role_id));
    }

    function save_role() {
        $this->data = $this->input->post();
        if ($this->model->save_role($this->data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Information Updated successfully'));
            redirect('um/role');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Data can not be saved now'));
        }
    }

    function check_username() {
        $username = $this->input->get('username');
        if ($this->model->check_username($username) > 0) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    function get_checkuser() {
        $user_name = $this->input->get('user_name');
        echo json_encode($this->model->get_checkuser($user_name));
    }

    function reset() {
        $id = $this->input->get('id');
        $this->model->reset($id);
        echo 'true';
    }

    function remove() {
        $id = $this->input->get('id');
        $this->model->remove($id);
        echo 'true';
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

}
