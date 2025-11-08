<?php

/**
 * P&RD Department
 *
 * @package		rrms
 * @author		EMDEE
 * @copyright           Copyright(c) 2021, Emdee Digitronics Pvt. Ltd.
 * @license		Emdee Digitronics Pvt. Ltd.
 * @author		Sujay Bandyopadhyay (sujay.bandyopadhyay@gmail.com)
 * @since		Version 1.0,[Created: 05-Oct-2021]
 */
// ------------------------------------------------------------------------
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('ws_model');
    }

    function api_post() {
        date_default_timezone_set('Asia/Kolkata');
        $ws = file_get_contents('php://input');
        $ws = json_decode($ws); 
        $code = $ws->WS_CODE;
        $data = $ws->WS_DATA;
        switch ($code) {
            case WS_LOGIN:
                $this->_login($data);
                break;
            case WS_CHANGE_PASSWORD:
                $this->_change_password($data);
                break;
            case WS_PROJECT:
                $this->_project($data);
                break;
            case WS_SAVE:
                $this->_save($data);
                break;
            case WS_LAST_INSPECTION_LIST:
                $this->last_inspection_list($data);
                break;
            case WS_WORK_STAGE_LIST:
                $this->work_stage_list($data);
                break;
            case WS_PROGRESS_SAVE:
                $this->_save_progress($data);
                break;
            case WS_PROGRESS_APPROVAL_LIST:
                $this->junior_progress_list($data);
                break;
            case WS_SRRP_SCHEME_COUNT:
                $this->_scheme_count($data);
                break;
            case WS_SRRP_LIST:
                $this->_scheme_list($data);
                break;
            case WS_SRRP_WO_DETAILS:
                $this->_scheme_wo_details($data);
                break;
            case WS_DIVISION:
                $this->_division($data);
                break;
            case WS_SAVE_SRRP:
                $this->_save_scheme($data);
                break;
            default:
                break;
        }
    }

    function _login($data) {
        $data = $data[0];
        $arr = $this->ws_model->login($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }

    // srrp
    function _change_password($data) {
        $data = $data[0];
        $id = $this->ws_model->change_password($data);
        $message = 'Data saved successfully ...';
        $this->response([
            'status' => TRUE,
            'message' => $message,
            'data' => $id
                ], REST_Controller::HTTP_OK);
    }

    function _project($data) {
        $data = $data[0];
        $arr = $this->ws_model->get_project_list($data);
        if($data->with_progress??false){
            $arr = $this->ws_model->get_project_list_with_progress_data($data);
        }
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }

    function _save($data) {
        $data = $data[0];
        $id = $this->ws_model->save($data);
        $this->response([
            'status' => TRUE,
            'message' => 'Data saved successfully ...',
            'data' => $id
                ], REST_Controller::HTTP_OK);
        $this->response([
            'status' => FALSE,
            'message' => 'Data cannot be saved right now. Please again later or contact with administrator ...',
            'data' => ''
                ], REST_Controller::HTTP_OK);
    }
   
    
     function last_inspection_list($data) {
        $data = $data[0];
        $arr = $this->ws_model->last_inspection_list($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }


    function work_stage_list($data) {
        $arr = $this->ws_model->work_stage_list();
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }
    

    function _save_progress($data) {
        $data = $data[0];
        $id = $this->ws_model->save_progress($data);
        $this->response([
            'status' => TRUE,
            'message' => $id==-2?'Physical progress is less than previous input. ':'Data saved successfully ...',
            'data' => $id
                ], REST_Controller::HTTP_OK);
        $this->response([
            'status' => FALSE,
            'message' => 'Data cannot be saved right now. Please again later or contact with administrator ...',
            'data' => ''
                ], REST_Controller::HTTP_OK);
    }
    
    
    function junior_progress_list($data) {
       $data = $data[0];
       $arr = $this->ws_model->junior_progress_list($data);
       $this->response([
           'status' => TRUE,
           'message' => 'success',
           'data' => $arr
               ], REST_Controller::HTTP_OK);
   }
   
   
   
   
   ///  SRRP 12th feb 2023
   
   
   function _scheme_count($data) {
        $data = $data[0];
        $arr = $this->ws_model->get_scheme_count($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }
   
   function _scheme_list($data) {
        $data = $data[0];
        $arr = $this->ws_model->get_scheme_list($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }
   
   function _scheme_wo_details($data) {
        $data = $data[0];
        $arr = $this->ws_model->get_scheme_wo_details($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }
    
    
   function _division($data) {
        $data = $data[0];
        $arr = $this->ws_model->get_division($data);
        $this->response([
            'status' => TRUE,
            'message' => 'success',
            'data' => $arr
                ], REST_Controller::HTTP_OK);
    }
    

    function _save_scheme($data) {
        $data = $data[0];
        $id = $this->ws_model->save_scheme($data);
        $message = 'Data saved successfully ...';
        if($id==-2){
            $message = 'Physical progress is less than previous input. ';
        }
        if($id==0){
            $message = 'Something went wrong. Please wait.';
        }
        $this->response([
            'status' => TRUE,
            'message' => $message,
            'data' => $id
                ], REST_Controller::HTTP_OK);
    }
 
   
    //DOCUMENT UPLOAD
    function api_upload_post() {
        $ws = $this->input->post();
        $ws = $ws['data'];
        $ws = json_decode($ws);
        $code = $ws->WS_CODE;
        $data = $ws->WS_DATA;
        $data = $data[0];
        $id = $data->id;
        $srrp_progress_id = 0;
        $file_name = $data->id;
        $image_no = isset($data->image_no) ? $data->image_no : 0;
        $location = isset($data->location) ? $data->location : null;
        $isscheme = isset($data->issrrp) && $data->issrrp ? true : false;
        $path = $isscheme ? 'uploads/ssm/progress':'uploads/inspection';
        // var_dump($isbefore); exit;
        if (!file_exists('./' . $path)) {
            mkdir('./' . $path, 0777, true);
        }
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            //var_dump($_FILES); exit;
            if (isset($_FILES['file']['name'])) {
                $config = array(
                    'upload_path' => $path,
                    'allowed_types' => "*",
                    'overwrite' => TRUE,
                );
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('file')) {
                    $data = array('upload_data' => $this->upload->data());
                    $old_file = $path . '/' . $data['upload_data']['file_name'];
                    $extension = '.jpg';
                    if ($code == WS_UPLOAD_PDF) {
                        $extension = '.pdf';
                    }
                    $new_file = $path . '/' . $file_name . $extension;
                    if($isscheme){
                        $this->db->select('id, physical_progress, pp_status');
                        $this->db->where(array(
                            'ssm_id' => $id
                        ));
                        $this->db->order_by('id', 'DESC');
                        $this->db->limit(1);
                        $query = $this->db->get(SSM_PROGRESS);
                        if ($query->num_rows() > 0) {
                            $row = $query->row();
                            $new_file = $path . '/' . $file_name. '_' . $row->id . "_image" . $image_no . $extension;
                            $progress_id = $row->id;
                        }
                    }
                    rename($old_file, $new_file);
                    if($isscheme){
                        $input = array('image'.$image_no => $new_file, 'location'.$image_no => $location);   
                        if ($progress_id > 0) {
                            $this->db->where('id', $row->id);
                            $this->db->update(SSM_PROGRESS, $input);
                            
                            // and srrp table update
                            $this->db->where('id', $id);
                            $this->db->update(SSM, $input);
                        }
                        
                    }else{
                        $input = array('image' => $new_file);
                        if ($code == WS_UPLOAD_PDF) {
                            $input = array('document' => $new_file);
                        }
                        $this->db->where('id', $id);
                        var_dump($input); exit;
                        $this->db->update(INSPECTION, $input);
                    }
                    $this->response([
                        'status' => TRUE,
                        'message' => 'success',
                        'data' => 'File uploaded successfully.'
                            ], REST_Controller::HTTP_OK);
                } else {
                    $error = array('error' => $this->upload->display_errors());
                    $this->response([
                        'status' => FALSE,
                        'message' => 'failure',
                        'data' => $error
                            ], REST_Controller::HTTP_OK);
                }
            }
            $this->response([
                'status' => FALSE,
                'message' => 'failure',
                'data' => 'file not found'
                    ], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    // function _update($code, $file, $assignment_id) {
    //     switch ($code) {
    //         case WS_UPLOAD_PDF:
    //             $input = array('pdf_doc' => $file);
    //             $condition = 'assignment_id=' . $assignment_id;
    //             $this->ws_model->_update(REPORT_MONITORING, $input, $condition);
    //             break;
    //         case WS_UPLOAD_IMAGE:
    //             $input = array('image' => $file);
    //             $condition = 'assignment_id=' . $assignment_id;
    //             $this->ws_model->_update(REPORT_PROGRESS, $input, $condition);
    //             break;
    //         default:
    //             break;
    //     }
    // }

}

?>
