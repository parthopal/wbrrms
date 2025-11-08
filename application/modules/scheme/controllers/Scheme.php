<?php

/**
 * P&RD Development
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright	Copyright (c) 2018, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 20-Jun-2023]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') or exit('No direct script access allowed');

class Scheme extends MX_Controller {

    var $data;

    function __construct() {
        parent::__construct();
        $this->load->model('scheme_model', 'model');
        $this->load->model('common/common_model', 'common');
        checkAuthSession();
        $this->data = array();
    }
    function rpt_state_summary($category) {
        $this->data['list'] = json_encode($this->model->get_rpt_state_summary($category));
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['heading'] = 'Workwise Summary Report';
       // $this->data['subheading'] = 'pathashree-rastashree schemes';
        $this->data['subheading'] = ($category == 'sf' ? 'STATE FUND' : strtoupper($category)) . ' Scheme';
        $this->data['title'] = 'PATHASHREE-RASTASHREE';
        $this->data['content'] = $this->parser->parse('rpt_state_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function rpt_workwise_progress($category) {
       // var_dump($category);exit;
        $list=array();
        $selected = array(
            'list_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'list_id' => $_post['list_id']
                );
               // var_dump($selected); exit;
                switch ($selected['list_id']) {
                    case 1: 
                        $list = $this->model->get_rpt_workwise_list_1($category, $selected);
                        break;
                        case 2: 
                         $list = $this->model->get_rpt_workwise_list_2($category, $selected);
                        break;
                        case 3: 
                         $list = $this->model->get_rpt_workwise_list_3($category,$selected);
                         break;
                        case 4: 
                        $list = $this->model->get_rpt_workwise_list_4($category,$selected);
                        break;
                         case 5: 
                        $list = $this->model->get_rpt_workwise_list_5($category,$selected);
                         break;
                         case 6: 
                         $list = $this->model->get_rpt_workwise_list_6($category,$selected);
                         break;
                         case 7: 
                         $list = $this->model->get_rpt_workwise_list_7($category,$selected);
                         break;
                         case 8: 
                         $list = $this->model->get_rpt_workwise_list_8($category,$selected);
                         break;
                         case 9: 
                        $list = $this->model->get_rpt_workwise_list_9($category,$selected);
                        break;
                        case 10: 
                        $list = $this->model->get_rpt_workwise_list_10($category,$selected);
                        break;
                        case 11: 
                        $list = $this->model->get_rpt_workwise_list_11($category,$selected);
                        break;
                        case 12: 
                       $list = $this->model->get_rpt_workwise_list_12($category,$selected);
                        break;
                        case 13: 
                        $list = $this->model->get_rpt_workwise_list_13($category,$selected);
                        break;
                        case 14: 
                        $list = $this->model->get_rpt_workwise_list_14($category,$selected);
                        break;
                        case 15: 
                        $list = $this->model->get_rpt_workwise_list_15($category,$selected);
                        break;
                        case 16: 
                        $list = $this->model->get_rpt_workwise_list_16($category,$selected);
                        break;
                        case 17: 
                        $list = $this->model->get_rpt_workwise_list_17($category,$selected);
                        break;                                                   
                        default:
                        break;
                }
            }
        }
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['sc'] = $category;
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Workwise  Progress Summary Report';
        $this->data['subheading'] = ($category == 'sf' ? 'STATE FUND' : strtoupper($category)) . ' Scheme';
        $this->data['title'] = 'PATHASHREE-RASTASHREE';
        $this->data['content'] = $this->parser->parse('rpt_workwise_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    //function rpt_agency_progress($category) 
    function rpt_agency_progress($category){
        //$session = $this->common->get_session();
        $list=array();
        $selected = array(
            'district_id' => '',
            'category_id' => '',
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
               
                $list = $this->model->get_rpt_agency_progress($selected);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        // $this->session->set_userdata('menu', $menu); 
        // $this->data['sc'] = $category;
        $this->data['district'] = json_encode($district_list);
        $this->data['sc'] = $category;
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
       $this->data['list'] = json_encode($list);
        // $this->data['category'] = json_encode($this->model->get_tranche_type_list());
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['heading'] = 'Agency wise Progress';
       // $this->data['subheading'] = 'pathashree-rastashree schemes';
        $this->data['subheading'] = ($category == 'sf' ? 'STATE FUND' : strtoupper($category)) . ' Scheme';
        $this->data['title'] = 'PATHASHREE-RASTASHREE';
        $this->data['content'] = $this->parser->parse('rpt_agency_progress', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
   
    function rpt_synoptic($category) {
        $list=array();
        $selected = array(
            'district_id' => '',
            'category_id' => '',
            'type_id' => '',
            'agency_id' => '',
            'synoptic_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id'],
                    'agency_id' => $_post['agency_id'],
                    'synoptic_id' => $_post['synoptic_id'],
                );
                switch ($selected['synoptic_id']) {
                    case 1: 
                        $list = $this->model->get_rpt_synoptic_1($category,$selected);
                        break;
                        case 2: 
                        $list = $this->model->get_rpt_synoptic_2($category,$selected);
                        break;
                        case 3: 
                      $list = $this->model->get_rpt_synoptic_3($category,$selected);
                       break;
                       case 4: 
                      $list = $this->model->get_rpt_synoptic_4($category,$selected);
                       break;
                       case 5: 
                      $list = $this->model->get_rpt_synoptic_5($category,$selected);
                        break;
                        case 6: 
                      $list = $this->model->get_rpt_synoptic_6($category,$selected);
                        break;
                         case 7: 
                        $list = $this->model->get_rpt_synoptic_7($category,$selected);
                        break;
                        case 8: 
                      $list = $this->model->get_rpt_synoptic_8($category,$selected);
                       break;
                        case 9: 
                        $list = $this->model->get_rpt_synoptic_9($category,$selected);
                        break;
                        case 10: 
                        $list = $this->model->get_rpt_synoptic_10($category,$selected);
                        break;
                         case 11: 
                         $list = $this->model->get_rpt_synoptic_11($category,$selected);
                        break;
                        case 12: 
                         $list = $this->model->get_rpt_synoptic_12($category,$selected);
                         break;
                         case 13: 
                        $list = $this->model->get_rpt_synoptic_13($category,$selected);
                        break;
                        case 14: 
                        $list = $this->model->get_rpt_synoptic_14($category,$selected);
                        break;
                        case 15: 
                        $list = $this->model->get_rpt_synoptic_15($category,$selected);
                        break;
                        case 16: 
                        $list = $this->model->get_rpt_synoptic_16($category,$selected);
                        break;
                        case 17: 
                         $list = $this->model->get_rpt_synoptic_17($category,$selected);
                        break;                                              
                    default:
                        break;
                }
                //$list = $this->model->get_rpt_synoptic($category, $selected);
               // var_dump($list);exit;
            }
        }
       
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['sc'] = $category;
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['agency'] = json_encode($this->model->get_syoptic_agency_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['heading'] = 'Synoptic Reports';
       // $this->data['subheading'] = 'pathashree-rastashree schemes';
       $this->data['subheading'] = ($category == 'sf' ? 'STATE FUND' : strtoupper($category)) . ' Scheme';
        $this->data['title'] = 'PATHASHREE-RASTASHREE';
        $this->data['content'] = $this->parser->parse('rpt_synoptic', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function report($category) {
        $this->data['sc'] = $category;
        $this->data['heading'] = 'Report Overview';
        $this->data['subheading'] = $category == 'sf' ? 'STATE FUND' : strtoupper($category);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
       // $this->data['title'] = $category == 'sf' ? 'STATE FUND' : strtoupper($category);
        $this->data['title'] = ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category)));
        $this->data['content'] = $this->parser->parse('report', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function rpt_districtwise_summary($category) {
        $list=array();
        $selected = array(
            'district_id' => '',
            'category_id' => '',
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
               
                $list = $this->model->get_rpt_districtwise_list($selected);
            }
            
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['district'] = json_encode($district_list);
        $this->data['sc'] = $category;
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        // $this->data['list'] = json_encode($this->model->get_rpt_state_summary());
        $this->data['list'] = json_encode($list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['heading'] = 'Districtwise Summary Report';
        $this->data['subheading'] = ($category == 'sf' ? 'STATE FUND' : strtoupper($category)) . ' Scheme';
       // $this->data['subheading'] = 'pathashree-rastashree schemes';
        $this->data['title'] = 'PATHASHREE-RASTASHREE';
        $this->data['content'] = $this->parser->parse('rpt_districtwise_summary', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }
    function overview($category) {
        $session = $this->common->get_session();
        if ($session['role_id'] < 10) {
            $menu = 0;
            switch ($category) {
                case 'ridf':
                    $menu = 18;
                    break;
                case 'pmgsy':
                    $menu = 17;
                    break;
                case 'sf':
                    $menu = 19;
                    break;
                case 'capex':
                    $menu = 20;
                    break;
                case 'sm':
                    $menu = 79;
                    break;
                default:
                    break;
            }
            $this->session->set_userdata('menu', $menu);
        }
        $this->data['sc'] = $category;
        $this->data['overview'] = json_encode($this->model->get_scheme_summary($category));
        $this->data['heading'] = 'Overview';
        $this->data['subheading'] = 'OVERVIEW OF '.($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category))) . ' PROJECT';

       // $this->data['subheading'] = 'Overview of ' . strtoupper($category) . ' Projects';
       $this->data['title'] = ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category)) .' SCHEME SUMMARY');

        //$this->data['title'] = strtoupper($category) . ' Scheme Summary';
        $this->data['content'] = $this->parser->parse('overview', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function view($category) {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0,
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
                $list = $this->model->get_scheme_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['sc'] = $category;
        $this->data['district'] = json_encode($district_list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Scheme Information';
        $this->data['subheading'] = 'View the project information';
        $this->data['title'] = ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category))) . ' PROJECT';
       // $this->data['title'] = strtoupper($category) . ' Projects';
        $this->data['content'] = $this->parser->parse('view', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function tender($category) {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0,
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
                $list = $this->model->get_tender_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['sc'] = $category;
        $this->data['district'] = json_encode($district_list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Tender Information';
        $this->data['subheading'] = 'View the tender information';
        $this->data['title'] = 'TENDER OF ' . ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category)) . ' PROJECT');
       // $this->data['title'] = 'Tender of ' . strtoupper($category) . ' Projects';
        $this->data['content'] = $this->parser->parse('tender', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function wo($category) {
        $session = $this->common->get_session();
        $list = array();
        $selected = array(
            'district_id' => 0,
            'category_id' => 0,
            'type_id' => ''
        );
        if ($this->input->post()) {
            $_post = $this->input->post();
            if (sizeof($_post) > 0) {
                $selected = array(
                    'district_id' => $_post['district_id'],
                    'category_id' => $_post['category_id'],
                    'type_id' => $_post['type_id']
                );
                $list = $this->model->get_wo_list($selected['district_id'], $selected['category_id'], $selected['type_id']);
            }
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['sc'] = $category;
        $this->data['district'] = json_encode($district_list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['selected'] = json_encode($selected);
        $this->data['list'] = json_encode($list);
        $this->data['heading'] = 'Work Order Information';
        $this->data['subheading'] = 'View the wo information';
        $this->data['title'] = ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category)));
        //$this->data['title'] = 'WO of ' . strtoupper($category) . ' Projects';
        $this->data['content'] = $this->parser->parse('wo', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function entry($category, $id = 0) {
        $session = $this->common->get_session();
        $block_list = array();
        $selected = array(
            'id' => 0,
            'scheme_id' => '',
            'name' => '',
            'sanctioned_cost' => '',
            'category_id' => '',
            'type_id' => '',
            'road_type_id' => '',
            'work_type_id' => '',
            'note' => '',
            'length' => '',
            'unit' => '',
            'agency_id' => '',
            'admin_no' => '',
            'admin_date' => '',
            'district_id' => 0,
            'block_id' => 0,
            'mp_id' => '',
            'mla_id' => ''
        );
        if ($id > 0) {
            $scheme = $this->model->get_scheme_info($id);
            $selected = array(
                'id' => $id,
                'scheme_id' => $scheme->scheme_id,
                'name' => $scheme->name,
                'sanctioned_cost' => $scheme->sanctioned_cost,
                'category_id' => $scheme->category_id,
                'type_id' => $scheme->type_id,
                'road_type_id' => $scheme->road_type_id,
                'work_type_id' => $scheme->work_type_id,
                'note' => $scheme->note != null ? $scheme->note : '',
                'length' => $scheme->length,
                'unit' => $scheme->unit,
                'agency_id' => $scheme->agency_id,
                'admin_no' => $scheme->admin_no,
                'admin_date' => $scheme->admin_date != null ? date('d/m/Y', strtotime($scheme->admin_date)) : '',
                'district_id' => $scheme->district_id,
                'block_id' => $scheme->block_id,
                'mp_id' => $scheme->mp_id,
                'mla_id' => $scheme->mla_id
            );
        }
        $district_list = $this->common->get_district_list();
        if (sizeof($district_list) == 1) {
            $selected['district_id'] = $district_list[0]->id;
        }
        if ($selected['district_id'] > 0) {
            $block_list = $this->common->get_block_list($selected['district_id']);
        }
        $this->data['role_id'] = $session['role_id'];
        $this->data['sc'] = $category;
        $this->data['district'] = json_encode($district_list);
        $this->data['block'] = json_encode($block_list);
        $this->data['category'] = json_encode($this->model->get_category_list($category));
        $this->data['type'] = json_encode($this->model->get_project_type_list());
        $this->data['agency'] = json_encode($this->model->get_agency_list($category));
        $this->data['road'] = json_encode($this->model->get_road_type_list());
        $this->data['work'] = json_encode($this->model->get_work_type_list());
        $this->data['mp'] = json_encode($this->model->get_constitution_list(1));
        $this->data['mla'] = json_encode($this->model->get_constitution_list(2));
        $this->data['selected'] = json_encode($selected);
        $this->data['heading'] = 'Project Information';
        $this->data['subheading'] = 'Enter the project information';
        $this->data['title'] = strtoupper($category) . ' Projects';
        $this->data['content'] = $this->parser->parse('entry', $this->data, true);
        $this->parser->parse('../../templates/template.php', $this->data);
    }

    function tender_entry($category, $scheme_id) {
        if ($scheme_id > 0) {
            $tender = $this->model->get_tender_info($scheme_id);
            $selected = array(
                'id' => 0,
                'scheme_id' => $scheme_id,
                'call_no' => 1,
                'nit_no' => '',
                'nit_date' => '',
                'bid_submission_date' => '',
                'bid_opening_date' => '',
                'technical_evaluation' => '',
                'tender_committee_date' => '',
                'financial_bid_opening_date' => '',
                'tender_matured' => '',
                'aot_issue_date' => '',
                'lop_issue_date' => ''
            );
            if ($tender != '') {
                $selected = array(
                    'id' => $tender->id,
                    'scheme_id' => $scheme_id,
                    'call_no' => $tender->call_no,
                    'nit_no' => $tender->nit_no,
                    'nit_date' => $tender->nit_date,
                    'bid_submission_date' => $tender->bid_submission_date,
                    'bid_opening_date' => $tender->bid_opening_date,
                    'technical_evaluation' => $tender->technical_evaluation,
                    'tender_committee_date' => $tender->tender_committee_date,
                    'financial_bid_opening_date' => $tender->financial_bid_opening_date,
                    'tender_matured' => $tender->tender_matured,
                    'aot_issue_date' => $tender->aot_issue_date,
                    'lop_issue_date' => $tender->lop_issue_date,
                    'islocked' => $tender->islocked
                );
            } else {
                $selected['call_no'] = $this->model->get_call_no($scheme_id);
            }
            $scheme = $this->model->get_scheme_info($scheme_id);
            $this->data['sc'] = $category;
            $this->data['selected'] = json_encode($selected);
            $this->data['heading'] = 'Tender Information';
            $this->data['subheading'] = 'Enter the tender information';
            $this->data['title'] = strtoupper($scheme->name);
            $this->data['content'] = $this->parser->parse('tender_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function wo_entry($category, $scheme_id) {
        if ($scheme_id > 0) {
            $wo = $this->model->get_wo_info($scheme_id);
            $selected = array(
                'id' => 0,
                'scheme_id' => $scheme_id,
                'wo_date' => '',
                'wo_no' => '',
                'wo_doc' => '',
                'completion_date' => '',
                'contractor_name' => '',
                'contractor_pan' => '',
                'contract_rate' => '',
                'awarded_cost' => '',
                'barchart_given' => 0,
                'barchart_doc' => '',
                'security_deposite_cost' => '',
                'additional_ps_cost' => '',
                'additional_ps_lapse_date' => '',
                'dlp' => '',
                'dlp_period' => '',
                'car_insurance' => 0
            );
            if ($wo != '') {
                $selected = array(
                    'id' => $wo->id,
                    'scheme_id' => $scheme_id,
                    'wo_date' => $wo->wo_date,
                    'wo_no' => $wo->wo_no,
                    'wo_doc' => $wo->wo_doc,
                    'completion_date' => $wo->completion_date,
                    'contractor_name' => $wo->contractor_name,
                    'contractor_pan' => $wo->contractor_pan,
                    'contract_rate' => $wo->contract_rate,
                    'awarded_cost' => $wo->awarded_cost,
                    'barchart_given' => $wo->barchart_given,
                    'barchart_doc' => $wo->barchart_doc,
                    'security_deposite_cost' => $wo->security_deposite_cost,
                    'additional_ps_cost' => $wo->additional_ps_cost,
                    'additional_ps_lapse_date' => $wo->additional_ps_lapse_date,
                    'dlp' => $wo->dlp,
                    'dlp_period' => $wo->dlp_period,
                    'car_insurance' => $wo->car_insurance
                );
            }
            $scheme = $this->model->get_scheme_info($scheme_id);
            $this->data['sc'] = $category;
            $this->data['selected'] = json_encode($selected);
            $this->data['heading'] = 'Work Order Information';
            $this->data['subheading'] = 'Enter the wo information';
            $this->data['title'] = ($category == 'sf' ? 'STATE FUND' : ($category == 'sm' ? 'SORASORI MUKHYAMANTRI' : strtoupper($category)));
           // $this->data['title'] = strtoupper($scheme->name);
            $this->data['content'] = $this->parser->parse('wo_entry', $this->data, true);
            $this->parser->parse('../../templates/template.php', $this->data);
        }
    }

    function save() {
        $data = $this->input->post();
        $id = $this->model->save($data);
        $this->db->select('category');
        $this->db->where('id', $data['category_id']);
        $query = $this->db->get(CATEGORY);
        $category = strtolower($query->row()->category);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
            redirect('scheme/view/' . $category);
        }
    }

    function tender_save() {
        $data = $this->input->post();
        $id = $this->model->tender_save($data);
        if ($id > 0) {
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
            redirect('scheme/tender/' . $data['sc']);
        }
    }

    function wo_save() {
        $data = $this->input->post();
        $id = $this->model->wo_save($data);
        if ($id > 0) {
            $file = $this->upload($id, 'uploads/scheme/wo/', 'wofile');
            if (strlen($file) > 0) {
                $input = array(
                    'wo_doc' => $file
                );
                $this->db->where('id', $id);
                $this->db->update(SCHEME_WO, $input);
            }
            $file = $this->upload($id, 'uploads/scheme/barchart/', 'barfile');
            if (strlen($file) > 0) {
                $input = array(
                    'barchart_doc' => $file
                );
                $this->db->where('id', $id);
                $this->db->update(SCHEME_WO, $input);
            }
            $this->session->set_flashdata('message', array('type' => 'success', 'message' => 'Data saved successfully'));
            redirect('scheme/wo/' . $data['sc']);
        }
    }

    function upload($id, $path, $userfile) {
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        $config['upload_path'] = './' . $path;
        $config['allowed_types'] = 'pdf';
        $config['max_size'] = 2048;
        $config['overwrite'] = 1;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if (!$this->upload->do_upload($userfile)) {
            $error = array('error' => $this->upload->display_errors());
            return '';
        } else {
            $file = array('upload_data' => $this->upload->data());
            $old_file = $path . $file['upload_data']['file_name'];
            $new_file = $path . $id . $file['upload_data']['file_ext'];
            rename($old_file, $new_file);
            return $new_file;
        }
    }

    function get_block_list() {
        $data = $this->input->get();
        echo json_encode($this->common->get_block_list($data['district_id']));
    }

    function next_call() {
        $data = $this->input->get();
        echo json_encode($this->model->save_next_call($data));
    }

    function retender() {
        $data = $this->input->get();
        echo json_encode($this->model->save_retender($data));
    }

}
