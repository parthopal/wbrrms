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

class SF extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('sf_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }

    function overview() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10 || $session['role_id'] == 21) {
            $this->session->set_userdata('menu', 19);
        }
        $this->data['dashboard_count'] = json_encode($this->model->get_dashboard_count());
        $this->data['tender_and_wo_count'] = json_encode($this->model->get_tender_and_wo_count());
        $this->data['title'] = 'SF';
        $this->data['heading'] = 'STATE FUND & OTHERS Overview';
        $this->data['subheading'] = 'overview';
        $this->data['content'] = $this->parser->parse('overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function index() {
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
        $this->data['survey'] = json_encode($this->model->get_survey_list($selected['district_id']));
        $this->data['title'] = 'SF';
        $this->data['heading'] = 'STATE FUND & OTHERS Master';
        $this->data['subheading'] = 'Scheme Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($id = 0) {
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
            'isapproved' => 0,
            'admin_no' => '',
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
                'isapproved' => $row->survey_status < 6 ? 0 : 1,
                'admin_no' => isset($row->admin_no) ? $row->admin_no : '',
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
        $this->data['title'] = 'STATE FUND & OTHERS Project';
        $this->data['heading'] = 'Project Work';
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
            redirect('sf');
        }
    }

    function save_atr_review() {
        $this->data = $this->input->post();
        $id = $this->model->save_atr_review($this->data);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data addded successfully'));
            redirect('sf/qm_atr');
        }
    }

    function get_survey_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_survey_list($data['district_id'], $data['status']));
    }

    function survey() {
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
        $this->data['survey'] = json_encode($this->model->get_survey_pending_list($selected['district_id'], $selected['block_id']));
        $this->data['title'] = 'Survey';
        $this->data['heading'] = 'STATE FUND & OTHERS Scheme Inbox'; //'Pending Survey';
        $this->data['subheading'] = 'Survey Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('survey', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function survey_entry($id) {
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_project_info($id);
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'gp' => $row->gp,
                'name' => $row->name,
                'village' => $row->village,
                'length' => $row->length,
                'agency' => $row->agency,
                'road_type' => $row->road_type,
                'work_type' => $row->work_type,
                'status' => $row->status
            );
        }
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'Survey';
        $this->data['heading'] = 'STATE FUND & OTHERS Survey';
        $this->data['subheading'] = 'Survey Entry';
        $this->data['content'] = $this->parser->parse('survey_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function survey_save() {
        $data = $this->input->post();
        if ($this->model->survey_save($data)) {
            redirect('sf/survey');
        } else {
            echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function survey_vec_save() {
        $data = $this->input->get();
        echo json_encode($this->model->survey_vec_save($data));
    }

    function create_lot_no() {
        $session = $this->common->get_session();
        $data = $this->input->post();
        $lotno = rand(1, 100000);
        switch ($session['role_id']) {
            case 2:
            case 3:
                $lotno = 'SA' . $lotno;
                break;
            case 7:
                $lotno = 'SE' . $lotno;
                break;
            case 12:
                $lotno = 'DM' . $lotno;
                break;
            case 13:
                $lotno = 'ZP' . $lotno;
                break;
            case 14:
                $lotno = 'BDO' . $lotno;
                break;
            case 15:
                $lotno = 'SRDA' . $lotno;
                break;
            case 16:
                $lotno = 'MBL' . $lotno;
                break;
            case 17:
                $lotno = 'AGRO' . $lotno;
                break;
            default:
                break;
        }
        $this->model->create_lot_no($session['role_id'], $data['chk'], $lotno);
        echo json_encode($lotno);
    }

    function forwarded() {
        $session = $this->common->get_session();
        $data = $this->input->post();
        $lotno = $data['lotno'];
        $path = 'uploads/sf';
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')) {
            $error = array('error' => $this->upload->display_errors());
            //	    var_dump($error); exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $lotno . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $this->model->forwarded($session['role_id'], $lotno, $new_file);
            echo json_encode($lotno);
        }
    }

    function lot() {
        $session = $this->common->get_session();
        $lotno_list = array();
        $selected = array(
            'district_id' => '0',
            'lotno' => '0'
        );
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $lotno_list = $this->model->get_lotno_list($district_list[0]->id);
        }
        if (sizeof($lotno_list) == 1) {
            $selected['lotno'] = $lotno_list[0]->lotno;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['lotno'] = json_encode($lotno_list);
        $this->data['lot'] = json_encode($this->model->get_lot_list($selected['district_id'], $selected['lotno']));
        $this->data['title'] = 'Scheme';
        $this->data['heading'] = 'STATE FUND & OTHERS Batch/Lot Inbox'; // 'Scheme';
        $this->data['subheading'] = 'Scheme Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('lot', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function approval() {
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
        $this->data['approval'] = json_encode($this->model->get_approval_list($selected['district_id'], $selected['block_id']));
        $this->data['title'] = 'Survey';
        $this->data['heading'] = 'STATE FUND & OTHERS Vetted Scheme Inbox'; // 'Approval';
        $this->data['subheading'] = 'Scheme Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('approval', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_survey_pending_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_survey_pending_list($data['district_id'], $data['block_id']));
    }

    function get_lotno_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_lotno_list($data['district_id']));
    }

    function get_lot_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_lot_list($data['district_id'], $data['lotno']));
    }

    function get_approval_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_approval_list($data['district_id'], $data['block_id']));
    }

    function get_not_imp_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_scheme_not_implemented($data['district_id'], $data['block_id']));
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function get_gp_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_gp_list($data['block_id']));
    }

    function admin_approval() {
        $session = $this->common->get_session();
        $data = $this->input->post();
        $lotno = $data['lotno'] . 'AA';
        $path = 'uploads/sf';
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
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            $new_file = $path . '/' . $lotno . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'admin_approval_date' => date('Y-m-d', strtotime(str_replace('/', '-', $data['admin_date']))),
                'admin_approval_no' => $data['admin_no'],
                'admin_approval_doc' => $new_file,
                'survey_status' => 6
            );
            $this->db->where('sa_lot_no', $data['lotno']);
            $this->db->update(SF, $input);
            $this->db->select('max(scheme_no) as scheme_no');
            $query = $this->db->get(SF);
            $scheme_no = $query->row()->scheme_no;
            $this->db->select('s.id, s.agency, d.name as district');
            $this->db->where('sa_lot_no', $data['lotno']);
            $this->db->join(DIVISION . ' d', 's.district_id=d.id');
            $query = $this->db->get(SF . ' s');
            $result = $query->result();
            foreach ($result as $row) {
                $scheme_no++;
                $ref_no = str_pad($scheme_no, 5, '0', STR_PAD_LEFT) . '/' . $row->agency . '/' . $row->district . '/STATEFUND/2023';
                $input = array(
                    'scheme_no' => $scheme_no,
                    'ref_no' => $ref_no
                );
                $this->db->where('id', $row->id);
                $this->db->update(SF, $input);
            }
            echo json_encode(true);
        }
    }

    function approved() {
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
        $this->data['approved'] = json_encode($this->model->get_approved_list($selected['district_id'], $selected['block_id']));
        $this->data['title'] = 'Scheme';
        $this->data['heading'] = 'STATE FUND & OTHERS Approved Scheme Inbox';
        $this->data['subheading'] = 'Scheme Details';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('approved', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_approved_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_approved_list($data['district_id'], $data['block_id']));
    }

    function print_lot($lotno) {
        $this->data['title'] = 'Print';
        $this->data['heading'] = 'STATE FUND & OTHERS Print Lot';
        $this->data['subheading'] = 'Print';
        $this->data['lotno'] = $lotno;
        $this->data['print'] = json_encode($this->model->print_lot($lotno));
        $this->data['content'] = $this->parser->parse('print', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function return_to_prev() {
        $data = $this->input->post();
        $this->model->return_to_prev($data['arr']);
        echo json_encode($this->model->get_approved_list($data['district_id'], $data['block_id']));
    }

    function remove_survey_list() {
        $data = $this->input->get();
        $this->model->remove_survey_list($data['id']);
        echo json_encode($this->model->get_survey_list($data['district_id']));
    }

    ################################################################################

    function get_state_approval_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_state_approval_list($data['district_id'], $data['block_id']));
    }

    function not_traceable($id) {
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_project_info($id);
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'gp' => $row->gp,
                'name' => $row->name,
                'village' => $row->village,
                'length' => $row->length,
                'agency' => $row->agency,
                'road_type' => $row->road_type,
                'work_type' => $row->work_type,
                'status' => $row->status
            );
        }
        $this->data['selected'] = json_encode($selected);
        $this->data['title'] = 'Scheme Not Implemented';
        $this->data['heading'] = 'STATE FUND & OTHERS Scheme Not Implemented';
        $this->data['subheading'] = 'Scheme Not Implemented';
        $this->data['content'] = $this->parser->parse('not_traceable', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function not_traceable_save() {
        $data = $this->input->post();
        if ($this->model->not_traceable_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Scheme Updated successfully'));
            redirect('sf/survey');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'cannot be saved now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function scheme_not_implemented() {
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
        $this->data['schemes'] = json_encode($this->model->get_scheme_not_implemented($selected['district_id'], $selected['block_id']));
        $this->data['title'] = 'Scheme Not Implemented';
        $this->data['heading'] = 'STATE FUND & OTHERS Not Implemented List';
        $this->data['subheading'] = 'Scheme Not Implemented';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('not_traceable_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function delete_not_traceable($id) {
        $this->model->delete_not_traceable($id);
        redirect('sf/scheme_not_implemented');
    }

    // Tender
    function tender() {
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
        $this->data['approved'] = json_encode($this->model->get_tender_list($selected['district_id'], $selected['block_id']));
        $this->data['title'] = 'Tender';
        $this->data['heading'] = 'STATE FUND & OTHERS Tender';
        $this->data['subheading'] = 'Tender';
        $this->data['selected'] = json_encode($selected);
        $this->data['content'] = $this->parser->parse('tender', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_tender_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_tender_list($data['district_id'], $data['block_id']));
    }

    function tender_entry($id) {
        $selected = array();
        if ($id > 0) {
            $row = $this->model->get_tender_info($id);
            $selected = array(
                'id' => $id,
                'district' => $row->district,
                'block' => $row->block,
                'gp' => $row->gp,
                'village' => $row->village,
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
                    'gp' => $row->gp,
                    'village' => $row->village,
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
        $this->data['heading'] = 'STATE FUND & OTHERS Tender';
        $this->data['subheading'] = 'Tender Entry';
        $this->data['content'] = $this->parser->parse('tender_entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function tender_save() {
        $data = $this->input->post();
        if ($this->model->tender_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Tender Save successfully'));
            redirect('sf/tender');
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can\'t save now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function tender_benefitted_save() {
        $data = $this->input->post();
        if ($this->model->tender_benefitted_save($data)) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Tender Save successfully'));
            redirect('sf/tender_entry/' . $data['id']);
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can\'t save now, please try again later'));
            // echo '<script>alert("cannot be saved now, please try again later");</script>';
        }
    }

    function wo() {
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'district_id' => '0',
            'block_id' => '0'
        );
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        if (sizeof($block_list) == 1) {
            $selected['block_id'] = $block_list[0]->id;
        }
        $wo = $selected['district_id'] > 0 ? $this->model->get_wo_list($selected['district_id'], $selected['block_id']) : array();
        $this->data['role_id'] = $session['role_id'];
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['wo'] = json_encode($wo);
        $this->data['title'] = 'Work Order';
        $this->data['heading'] = 'STATE FUND & OTHERS Work Order';
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
                'sf_id' => $wo->sf_id,
                'district_id' => $wo->district_id,
                'name' => $wo->name,
                'wo_no' => $wo->wo_no,
                'wo_date' => $wo->wo_date != NULL ? $wo->wo_date : date('d/m/Y'),
                'contractor' => $wo->contractor,
                'pan_no' => $wo->pan_no,
                'rate' => $wo->rate,
                'awarded_cost' => $wo->awarded_cost,
                'completion_date' => $wo->completion_date != NULL ? $wo->completion_date : date('d/m/Y'),
                'barchart_given' => $wo->barchart_given,
                'ps_cost' => $wo->ps_cost,
                'lapse_date' => ($wo->lapse_date != NULL && $wo->lapse_date != '1970-01-01') ? $wo->lapse_date : '',
                'additional_ps_cost' => $wo->additional_ps_cost,
                'dlp' => $wo->dlp,
                'document' => $wo->document,
                'dlp_period' => $wo->dlp_period != NULL ? $wo->dlp_period : 0,
                'dlp_submitted' => $wo->dlp_submitted,
                'assigned_engineer' => $wo->assigned_engineer,
                'designation' => $wo->designation,
                'mobile' => $wo->mobile
            );
            $this->data['selected'] = json_encode($selected);
            $this->data['islocked'] = ($wo->id > 0 && $session['role_id'] > 3) ? 1 : 0;
            $this->data['title'] = 'Work Order';
            $this->data['heading'] = 'STATE FUND & OTHERS Work Order';
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
        redirect(base_url('sf/wo'));
    }

    function get_wo_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_wo_list($data['district_id'], $data['block_id']));
    }

    function wo_remove() {
        $id = $this->input->get('id');
        $this->model->wo_remove($id);
        echo TRUE;
    }

    function upload($id) {
        $path = 'uploads/sf/wo';
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
                $this->db->update(SF_WO, $input);
            }
        }
    }

    function wp($status = 0) {
        $session = $this->common->get_session();
        if ($session['role_id'] == 20) {
            $wp_list = $this->model->get_wp_list($session['user_id'], $status);
            $this->data['wp'] = json_encode($wp_list);
            $this->data['status'] = $status;
            $this->data['heading'] = 'STATE FUND & OTHERS Work Progress List';
            $this->data['subheading'] = $status == 0 ? 'Not-Started' : ($status == 5 ? 'Completed' : 'Work-In-Progress');
            $this->data['content'] = $this->parser->parse('wp_view', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        } else {
            redirect('dashboard');
        }
    }

    function wp_entry($id, $status) {
        $session = $this->common->get_session();
        $wp = $this->model->get_wp_info($session['user_id'], $id, $status);
        $this->data['wp'] = json_encode($wp);
        $this->data['status'] = $status;
        $this->data['heading'] = 'STATE FUND & OTHERS Work Progress Entry';
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
        redirect(base_url('sf/wp/' . $input['status']));
    }

    function wp_upload($id, $sf_id) {
        $path = 'uploads/sf/progress/';
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
            var_dump($error);
            exit;
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . '/' . $file['upload_data']['file_name'];
            if (strlen($old_file) > 0) {
                $new_file = $path . $sf_id . '_' . $id . '_' . 'image1' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image1' => $new_file
                );
                $this->db->where('id', $sf_id);
                $this->db->update(SF, $input);
                $this->db->where('id', $id);
                $this->db->update(SF_PROGRESS, $input);
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
                $new_file = $path . '/' . $sf_id . '_' . $id . '_' . 'image2' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image2' => $new_file
                );
                $this->db->where('id', $sf_id);
                $this->db->update(SF, $input);
                $this->db->where('id', $id);
                $this->db->update(SF_PROGRESS, $input);
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
                $new_file = $path . '/' . $sf_id . '_' . $id . '_' . 'image3' . $file['upload_data']['file_ext'];
                rename($old_file, $new_file);
                $input = array(
                    'image3' => $new_file
                );
                $this->db->where('id', $sf_id);
                $this->db->update(SF, $input);
                $this->db->where('id', $id);
                $this->db->update(SF_PROGRESS, $input);
            }
        }
    }

################################## QM ##########################################

    function qm() {
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
                $list = $this->model->get_qm_list($selected['month'], $selected['year']);
            }
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'STATE FUND & OTHERS Quality Monitoring';
        $this->data['subheading'] = 'SQM Overall Satus';
        $this->data['title'] = 'Periodic Assigned SQM';
        $this->data['content'] = $this->parser->parse('qm_view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_details($id) {
        $arr = explode('-', $id);
        $caption = $this->model->get_qm_caption($arr[1], $arr[2], $arr[0]);
        $this->data['caption'] = json_encode($caption);
        $this->data['list'] = json_encode($this->model->get_qm_details($arr[1], $arr[2], $arr[0]));
        $this->data['heading'] = 'STATE FUND & OTHERS Assignment Details';
        $this->data['subheading'] = 'Assigned road details';
        $this->data['title'] = '';
        $this->data['content'] = $this->parser->parse('qm_details', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_entry() {
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
                    $scheme_list = $this->model->get_scheme_list($selected['district_id'], $selected['sqm_id'], $selected['month'], $selected['year']);
                }
            }
            $district_list = $this->common->get_district_list();
            $sqm_list = $this->model->get_sqm_list();
            $this->data['sqm'] = json_encode($sqm_list);
            $this->data['selected'] = json_encode($selected);
            $this->data['district'] = json_encode($district_list);
            $this->data['scheme'] = json_encode($scheme_list);
            $this->data['heading'] = 'STATE FUND & OTHERS Quality Monitoring';
            $this->data['subheading'] = 'Assigned SQM';
            $this->data['title'] = 'Assigned Road to SQM';
            $this->data['content'] = $this->parser->parse('qm_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        } else {
            redirect('sf/qm');
        }
    }

    function qm_inspection() {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'month' => date('m'),
            'year' => date('Y'),
            'sqm_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'month' => $_post['month'],
                    'year' => $_post['year'],
                    'sqm_id' => $_post['sqm_id']
                );
                $list = $this->model->get_inspection_list($selected['sqm_id'], $selected['month'], $selected['year']);
            }
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['list'] = json_encode($list);
        $sqm_list = $this->model->get_sqm_list();
        $this->data['sqm'] = json_encode($sqm_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'STATE FUND & OTHERS SQM Inspection';
        $this->data['subheading'] = 'SQM Inspection';
        $this->data['title'] = 'SQM Inspection';
        $this->data['content'] = $this->parser->parse('qm_inspection', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_start($id) {
        if ($id > 0) {
            $caption = $this->model->get_inspection_caption($id);
            $this->data['qm_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['selected'] = json_encode($this->model->get_inspection_image_list($id));
            $this->data['heading'] = 'STATE FUND & OTHERS Inspection Images';
            $this->data['subheading'] = 'Upload Images (atleast 5 images)';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('qm_start', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function qm_save_start() {
        $data = $this->input->post();
        // $flag = TRUE;
        //var_dump($data);exit;
        $qm_id = $data['qm_id'];
        $id = $this->model->qm_save_start($qm_id, $data['agency']);
        if ($id > 0) {
            $path = 'uploads/sf/qm/';
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
            $this->db->update(SF_QM_INSPECTION_IMAGE, $input);
            for ($i = 0; $i < sizeof($desc); $i++) {
                $image_id = $this->model->qm_save_inspection_image($i, $id, $desc);
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
                        $this->db->update(SF_QM_INSPECTION_IMAGE, $input);
                    }
                }
            }
            $input = array(
                'status' => 1
            );
            $this->db->where('id', $qm_id);
            $this->db->update(SF_QM, $input);
        }
        $flag = TRUE;
        if ($flag) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'QM information successfully saved'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'QM information not saved'));
        }
        redirect(base_url('sf/qm_inspection'));
    }

    function qm_submit($id) {
        if ($id > 0) {
            $caption = $this->model->get_inspection_caption($id);
            $this->data['qm_id'] = $id;
            $this->data['agency'] = $caption[0]->agency;
            $this->data['oqrc'] = json_encode($this->model->get_oqrc_list());
            $this->data['heading'] = 'Overall Quality Grading Report';
            $this->data['subheading'] = 'Submit your final report';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('qm_submit', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function qm_save_submit() {
        $data = $this->input->post();
        $id = $this->model->qm_save_submit($data);
        $this->qm_upload_submit($id);
        $input = array(
            'status' => 2
        );
        $this->db->where('id', $data['qm_id']);
        $this->db->update(SF_QM, $input);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Final report has been submitted successfully'));
        } else {
            $this->session->set_flashdata('message', array('type' => 'warning', 'message' => 'can not be saved right now'));
        }
        redirect(base_url('sf/qm_inspection'));
    }

    function qm_upload_submit($id) {
        $path = 'uploads/sf/qm/';
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
            $this->db->update(SF_QM_INSPECTION, $input);
            return true;
        }
    }

    function qm_save() {
        $data = $this->input->post();
        $this->model->qm_save($data);
        redirect('sf/qm');
    }

    function qm_atr() {
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
                $list = $this->model->get_atr_list($selected['month'], $selected['year']);
            }
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Action Taken Report';
        $this->data['subheading'] = 'ATR Summary';
        $this->data['title'] = '';
        $this->data['content'] = $this->parser->parse('qm_atr', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_action($id) {
        if ($id > 0) {
            $caption = $this->model->get_inspection_caption($id);
            $this->data['id'] = $id;
            $this->data['heading'] = 'Action Taken Report';
            $this->data['subheading'] = 'Submit your ATR';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('qm_action', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function qm_selection() {
        $caption = $this->model->get_selection();
        $this->data['heading'] = 'STATE FUND & OTHERS Action Taken Report';
        $this->data['subheading'] = 'Submit your ATR';
        $this->data['title'] = $caption[0]->name;
        $this->data['content'] = $this->parser->parse('qm_action', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_save_atr() {
        $data = $this->input->post();
        $this->qm_upload_atr($data['id']);
        $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'ATR has been submitted successfully'));
        redirect(base_url('sf/qm_atr'));
    }

    function qm_upload_atr($id) {
        $path = 'uploads/sf/qm/';
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
            $new_file = $path . $id . '_atr' . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            $input = array(
                'atr' => $new_file
            );
            $this->db->where('id', $id);
            $this->db->update(SF_QM_INSPECTION, $input);
            return true;
        }
    }

    function qm_save_atr_review() {
        $data = $this->input->post();
        $this->model->qm_save_atr_review($data);
        $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'ATR has been submitted successfully'));
        redirect(base_url('sf/qm_atr'));
    }

    function qm_inspection_report() {
        $list = array();
        $selected = array(
            'month' => date('m'),
            'year' => date('Y'),
            'sqm_id' => ''
        );
        $sqm_list = $this->model->get_sqm_list();
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'month' => $_post['month'],
                    'year' => $_post['year'],
                    'sqm_id' => $_post['sqm_id']
                );
                $list = $this->model->get_inspection_report_list($selected['sqm_id'], $selected['month'], $selected['year']);
            }
        }
        if (sizeof($sqm_list) == 1) {
            $selected['sqm_id'] = $sqm_list[0]->id;
            $list = $this->model->get_inspection_report_list($selected['sqm_id'], $selected['month'], $selected['year']);
        }
        $this->data['list'] = json_encode($list);

        $this->data['sqm'] = json_encode($sqm_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'STATE FUND & OTHERS Inspection Report';
        $this->data['subheading'] = 'Inspection Report';
        $this->data['title'] = 'Inspection Report';
        $this->data['content'] = $this->parser->parse('qm_inspection_report', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function qm_inspection_report_view($id) {
        if ($id > 0) {
            $caption = $this->model->get_inspection_caption($id);
            $this->data['oqrc'] = json_encode($this->model->get_qm_oqrc_list($id));
            $this->data['agency'] = $caption[0]->agency;
            $this->data['selected'] = json_encode($this->model->get_inspection_image_list($id));
            $this->data['grade'] = $this->model->get_overall_grade($id);
            $this->data['heading'] = 'STATE FUND & OTHERS Inspection Report View';
            $this->data['subheading'] = 'inspection report view';
            $this->data['title'] = $caption[0]->name;
            $this->data['content'] = $this->parser->parse('qm_inspection_report_view', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

################################################################################
################################# REPORT #######################################

    function report() {
        $this->data['heading'] = 'Report Overview';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('report', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_state_summary() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 19);
        }
        $selected = array(
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'tag' => $_post['tag']
                );
            }
        }
        $list = $this->model->get_rpt_state_summary($selected['tag']);
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS State Summary Report';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('rpt_state_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_agency_progress() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $this->session->set_userdata('menu', 6);
        }
        $selected = array(
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'tag' => $_post['tag']
                );
            }
        }
        $list = $this->model->get_rpt_agency_progress($selected['tag']);
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS Agency wise Progress';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('rpt_agency_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_road_type_progress() {
        $selected = array(
            'road_type' => 'Bituminious(Tar)Road',
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'road_type' => $_post['road_type'],
                    'tag' => $_post['tag']
                );
            }
        }
        $list = $this->model->get_rpt_road_type_progress($selected['road_type'], $selected['tag']);
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'STATE FUND & OTHERS Road Type wise Progress';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('rpt_road_type_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_work_type_progress() {
        $selected = array(
            'work_type' => 'Construction',
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'work_type' => $_post['work_type'],
                    'tag' => $_post['tag']
                );
            }
        }
        $list = $this->model->get_rpt_work_type_progress($selected['work_type'], $selected['tag']);
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['list'] = json_encode($list);
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'STATE FUND & OTHERS Work Type wise Progress';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('rpt_work_type_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_ps_work_status() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => '',
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'tag' => $_post['tag']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_rpt_ps_work_status($selected['district_id'], $selected['block_id'], $selected['tag']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS PS wise Work Status Report';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
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
            'tag' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'wp_id' => $_post['wp_id'],
                    'tag' => $_post['tag']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_rpt_work_progress($selected['district_id'], $selected['block_id'], $selected['wp_id'], $selected['tag']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $tag_list = $this->model->get_tag_list();
        $this->data['tag'] = json_encode($tag_list);
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS Work wise Progress Report';
        $this->data['subheading'] = 'state-fund & others schemes';
        $this->data['title'] = 'STATE FUND & OTHERS';
        $this->data['content'] = $this->parser->parse('rpt_work_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function rpt_work_progress_details($sf_id) {
        $list = $this->model->get_rpt_work_progress_details($sf_id);
        $this->data['list'] = sizeof($list) > 0 ? json_encode($list) : '';
        $this->data['title'] = 'Work Progress Report';
        $this->data['heading'] = 'STATE FUND & OTHERS Work Progress Details Report';
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
        $this->data['heading'] = 'STATE FUND & OTHERS Quality Monitoring Report';
        $this->data['subheading'] = 'state-fund schemes';
        $this->data['title'] = 'STATE FUND';
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

    function update() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => '',
            'wp_id' => '0'
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'wp_id' => $_post['wp_id']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_update_status($selected['district_id'], $selected['block_id'], $selected['wp_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS Update of Pathashree Schemes';
        $this->data['subheading'] = 'state-fund schemes';
        $this->data['title'] = 'STATE FUND';
        $this->data['content'] = $this->parser->parse('update', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function update_save() {
        $data = $this->input->get();
        echo json_encode($this->model->update_save($data));
    }

    function rpt_update_summary() {
        $block_list = array();
        $list = array();
        $selected = array(
            'district_id' => '',
            'block_id' => '',
            'status' => '0'
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id'],
                    'status' => $_post['status']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
                $list = $this->model->get_rpt_updated_status($selected['district_id'], $selected['block_id'], $selected['status']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
            $block_list = $this->common->get_block_list($district_list[0]->id);
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'STATE FUND & OTHERS Update Summary Report';
        $this->data['subheading'] = 'state-fund schemes';
        $this->data['title'] = 'STATE FUND';
        $this->data['content'] = $this->parser->parse('rpt_update_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

}
