<?php

/**
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright           Copyright(c) 2021, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 02-Mar-2022]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('common/common_model', 'common');
        $this->load->model('dashboard_model', 'model');
        checkAuthSession();
        $this->data = array();
    }

    function index() {
        $session = $this->common->get_session();
        $session = $this->common->get_session();
        if (isset($session) && $session['role_id'] < 4) {
            redirect('dashboard/menu');
        } else {
            redirect('dashboard/rpt_general');
        }
    }

    function menu() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 4) {
            $this->session->set_userdata('menu', 0);
        }
        $menu = $session['role_id'] == 3 ? 'menu_sa' : 'menu';
        $this->data['content'] = $this->parser->parse($menu, $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function districtwise_piechart() {
        $session = $this->common->get_session();
        if ($session['role_id'] < 4) {
            $status = $this->model->districtwise_project_status();
            $this->data['status'] = json_encode($status);
            $this->data['heading'] = '';
            $this->data['content'] = $this->parser->parse('piechart', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function fetch_project_list() {
        $output = '';
        //$this->load->model('scroll_pagination_model');
        $data = $this->model->fetch_project_list($this->input->post('limit'), $this->input->post('start'));
        if ($data->num_rows() > 0) {
            foreach ($data->result() as $row) {
                $output .= '
                    <div class="d-flex">
                    <div class="avatar">
                        <span class="badge badge-green mt-3">' . $row->project_category . '</span>
                    </div>
                    <div class="flex-1 ml-3 pt-1">
                        <h6 class="text-uppercase fw-bold mb-1">' . $row->project_name . '</h6>
                        <div class="text-muted row">

                            <div class="col-md-5">
                                <strong>District :</strong> ' . $row->district . '
                            </div>

                            <div class="col-md-4">
                                <strong>Block :</strong> ' . $row->block . '
                            </div>

                            <div class="col-md-3">
                                Progress : <div class="progress progress-sm">
                                <div class="progress-bar bg-danger" style="width: ' . $row->phy_progress . 'px" role="progressbar" aria-valuenow="' . $row->phy_progress . '" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>

                                <div class="d-flex justify-content-between mt-2">
                                    <p class="text-muted mb-0">' . $row->phy_progress . '%</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="float-right pt-1">
                        <div id="circles-5"></div>
                    </div>
                   </div>
                   <div class="separator-dashed"></div>
                    ';
            }
        }
        echo $output;
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function rpt_general() {
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'scheme_id' => '',
            'district_id' => '',
            'block_id' => '0'
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'scheme_id' => $_post['scheme_id'],
                    'district_id' => $_post['district_id'],
                    'block_id' => $_post['block_id']
                );
                $block_list = $this->common->get_block_list($selected['district_id']);
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
        $list = $selected['scheme_id'] > 0 && $selected['district_id'] > 0 ? $this->model->get_rpt_general($selected['scheme_id'], $selected['district_id'], $selected['block_id']) : array();
        $this->data['selected'] = json_encode($selected);
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['list'] = json_encode($list);
//        $this->data['funding'] = json_encode($this->model->project_funding());
//        $this->data['status'] = json_encode($this->model->project_status());
        $this->data['heading'] = 'OVERVIEW REPORT';
        $this->data['subheading'] = 'Overview Report';
        $this->data['role_id'] = $session['role_id'];
        $this->data['content'] = $this->parser->parse('rpt_general', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function get_general_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_rpt_general($data['scheme_id'], $data['district_id'], $data['block_id']));
        // print_r($data); exit;
    }
}
