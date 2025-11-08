<?php

/**
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright           Copyright(c) 2021, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 01-Mar-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends MX_Controller {

    var $data, $user;

    function __construct() {
        parent::__construct();
        $this->load->model('common/session_model');
        $this->load->model('login_model', 'model');
        $this->load->library('session');
        $this->load->helper('captcha');
        $k = $this->session->userdata('user');
        $this->user = json_decode($this->encryption->decrypt($k), true);
        $this->data = array();
    }

    function index() {
        if (isset($this->user)) {
            if ($this->user['role_id'] < 4) {
                redirect('dashboard/menu');
            } else {
                redirect('dashboard');
            }
        }
        $this->data['captcha'] = $this->generate_captcha();
        $this->data['content'] = $this->parser->parse('login_view', $this->data, true);
        $this->parser->parse('../../templates/login.php', $this->data);
    }

    function refresh() {
        echo $this->generate_captcha();
    }

    function validate() {
        $data = $this->input->post();
        $username = $this->input->post('username');
        if (isset($data['captcha']) && $data['captcha'] != $this->session->userdata['captcha']) {
            $action = 'Invalid Captcha';
            session_logs($username, $action);
            echo json_encode('Wrong captcha, please try it again.');
        } else {
            if (!$this->model->validate($data)) {
                $action = 'Invalid Credential';
                session_logs($username, $action);
                echo json_encode('Invalid credentials.');
            } else {
                $action = 'Login Success';
                session_logs($username, $action);
                echo json_encode('');
            }
        }
    }

    function generate_captcha() {
        $vals = array(
            'word' => substr(sha1(mt_rand()),17,4), //mt_rand(1000, 9999),
            'img_path' => './captcha/',
            'img_url' => base_url('captcha'),
            'font_path' => base_url('system/fonts/ArialNarrow.ttf'),
            'img_width' => '180',
            'img_height' => '40',
            'expiration' => 7200,
            'word_length' => 4,
            'font_size' => 5,
            'img_id' => 'Imageid',
            'pool' => '0123456789',
            'colors' => array(
                'background' => array(255, 255, 255),
                'border' => array(255, 255, 255),
                'text' => array(0, 0, 0),
                'grid' => array(255, 40, 40)
            )
        );
        $cap = create_captcha($vals);
        $this->session->set_userdata('captcha', $cap['word']);
        return $cap['image'];
    }

    function error() {
        $this->output->set_status_header('404');
        $this->load->view('404_view');
    }

    function logout() {
        $this->session->set_userdata('user', '');
        redirect(base_url('login'));
    }

    function reset() {
        $key = $this->session->userdata('user');
        $k = json_decode($this->encryption->decrypt($key), true);
        $user_id = $k['user_id'];
        $this->data['user_id'] = $user_id;
        $this->data['content'] = $this->parser->parse('reset', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save() {
        $this->data = $this->input->post();
        if ($this->model->reset_password($this->data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Password Changed Successfully'));
            redirect('login/logout');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Please check the current password.'));
            redirect('login/reset');
        }
    }

}
