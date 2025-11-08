<?php

/**
 * RRMS Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 27-Mar-2023]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Web extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('web_model', 'model');
        $this->data = array();
    }

    function index() {
        $this->data['district'] = json_encode($this->model->get_district_list());
        $this->data['title'] = 'DEPARTMENT OF WEST BENGAL PANCHAYAT AND RURAL DEVELOPMENT';
        $this->data['content'] = $this->parser->parse('home', $this->data, true);
        $this->parser->parse('../../web/template.php', $this->data);
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_block_list($data['district_id']));
    }

    function get_gp_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_gp_list($data['district_id'], $data['block_id']));
    }

    function get_road_list() {
        $data = $this->input->get();
        echo json_encode($this->model->get_road_list($data['district_id'], $data['block_id'], $data['gp_id']));
    }

}
