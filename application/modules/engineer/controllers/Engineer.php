<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 21-Jun-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Engineer extends MX_Controller
{

    var $data;

    function __construct()
    {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        $this->load->model('common/session_model');
        $this->load->model('engineer_model', 'model');
        checkAuthSession();
        $this->data = array();
    }

    function index()
    {
        $this->data['list'] = json_encode($this->model->get_engineer_list());
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0)
    {
        $this->data['district'] = json_encode($this->common->get_district_list());
        $this->data['info'] = json_encode($this->model->get_engineer_info($id));
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function sb($id)
    {
        $this->data['engineer_id'] = $id;
        $this->data['sb'] = json_encode($this->model->get_service_book($id));
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->data['content'] = $this->parser->parse('sb', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function lr($id)
    {
        $this->data['engineer_id'] = $id;
        $this->data['lr'] = json_encode($this->model->get_leave_records($id));
        $this->output->set_header("Cache-Control: no-store, no-cache, must-revalidate");
        $this->data['content'] = $this->parser->parse('lr', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function save()
    {
        $this->data = $this->input->post();
        $id = $this->model->save($this->data);
        if ($id > 0) {
            echo '<script>alert("Data saved successfully."); window.location.href = "' . base_url('engineer') . '";</script>';
            //redirect('um');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'Data can not be saved now'));
            //echo '<script>alert("Data can not be saved now");</script>';
        }
    }

    function upload_sb()
    {
        $data = $this->input->post();
        $path = 'uploads/engineer/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            var_dump($error);
            exit;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'engineer_id' => $data['engineer_id'],
                'sb_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['hdn_sb_date'])))
            );
            $this->db->insert(SERVICE_BOOK, $input);
            $id = $this->db->insert_id();
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $image['upload_data']['file_name'];
            $new_file = $path . $data['engineer_id'] . '_sb_' . $id . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'sb_doc' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(SERVICE_BOOK, $input);
            $input = array(
                'sb_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['hdn_sb_date'])))
            );
            $this->db->where('id', $data['engineer_id']);
            $this->db->update(ENGINEER, $input);
        }
    }

    function upload_lr()
    {
        $data = $this->input->post();
        $path = 'uploads/engineer/';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('file')) {
            $error = array('error' => $this->upload->display_errors());
            var_dump($error);
            exit;
        } else {
            $input = array(
                'created' => date('Y-m-d'),
                'engineer_id' => $data['engineer_id'],
                'lr_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['hdn_lr_date'])))
            );
            $this->db->insert(LEAVE_RECORDS, $input);
            $id = $this->db->insert_id();
            $image = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $image['upload_data']['file_name'];
            $new_file = $path . $data['engineer_id'] . '_lr_' . $id . $image['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'lr_doc' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(LEAVE_RECORDS, $input);
            $input = array(
                'lr_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['hdn_lr_date'])))
            );
            $this->db->where('id', $data['engineer_id']);
            $this->db->update(ENGINEER, $input);
        }
    }


    function sb_remove()
    {
        $id = $this->input->post('id');

        if (!empty($id)) {
            $data = ['isactive' => -1];

            $this->db->where('id', $id);
            $updated = $this->db->update(SERVICE_BOOK, $data);

            if ($updated) {
                echo json_encode(['status' => true, 'message' => 'Deleted successfully']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Delete failed']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
        }
    }


    function lr_remove()
    {
        $id = $this->input->post('id');

        if (!empty($id)) {
            $data = ['isactive' => -1];

            $this->db->where('id', $id);
            $updated = $this->db->update(LEAVE_RECORDS, $data);

            if ($updated) {
                echo json_encode(['status' => true, 'message' => 'Deleted successfully']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Delete failed']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Invalid ID']);
        }
    }
}
