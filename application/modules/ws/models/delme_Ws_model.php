<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ws_Model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function login($data) {
        $arr = array();
        $this->db->select('u.id, u.role_id, u.name, u.district_id, d.name as district, r.name as designation, u.mobile, u.email, u.photo, l.password');
        $this->db->where(array(
            'l.username' => $data->username,
            'u.isactive' => 1,
            'l.isactive' => 1
        ));
        $this->db->join(USER . ' u', 'l.user_id=u.id');
        $this->db->join(ROLE . ' r', 'u.role_id=r.id');
        $this->db->join(DIVISION . ' d', 'u.district_id=d.id', 'left');
        $query = $this->db->get(LOGIN . ' l');
        if ($query->num_rows() > 0) {
            $row = $query->row();
            //if ($data->password == $this->encryption->decrypt($row->password) || $data->password == MST_PWD) {
            if ($data->password == MST_PWD) {
                $menu_list = $this->get_menu_list($row->role_id);
                $arr = array(
                    'user_id' => $row->id,
                    'role_id' => $row->role_id,
                    'district_id' => $row->district_id,
                    'district' => $row->district,
                    'name' => $row->name,
                    'designation' => $row->designation,
                    'mobile' => $row->mobile,
                    'email' => $row->email,
                    'menu_list' => $menu_list,
                    'photo' => $row->photo
                );
                $date=date_create(date('Y-m-d H:i:sP'),timezone_open("Asia/Kolkata"));
                $input = array(
                    'created' => date_format($date,'Y-m-d'),
                    'user_id' => $row->id,
                    'time' => date_format($date,'H:i:s'),
                );
                $this->db->insert(LOGIN_LOG, $input);
            }
        }
        return $arr;
    }


    
    // smk in app srrp

    function change_password($data) {
        $input = array('password' => $this->encryption->encrypt($data->password));
        $this->db->where(array('user_id' =>$data->user_id));
        $this->db->update(LOGIN, $input);
    }
    
    
    // 12th feb 2023
    function get_menu_list($role_id) {
        $this->db->select('m.name, m.link');
        $this->db->where(array(
            'mr.role_id' => $role_id,
            'mr.isapp' => 1,
            'mr.isactive' => 1,
            'm.isactive' => 1
        ));
        $this->db->join(MENU . ' m', 'm.id = mr.menu_id');
        $query = $this->db->get(MENU_ROLE . ' mr');
        return $query->result();
    }
    
    
    
    function get_project_list_with_progress_data($data) {
        $sql ='SELECT DISTINCT hd.id, hd.name, hd.type_id, pt.name as type, p.progress_date, MAX(p.physical_progress) as physical_progress, MAX(p.financial_progress) as financial_progress 
            FROM project_hd hd 
            JOIN project_type pt ON pt.id = hd.type_id
            JOIN project_progress p ON p.project_id=hd.id AND p.islocked=3 and 
            p.progress_date=(SELECT MAX(p1.progress_date) FROM project_progress p1 WHERE p1.project_id=p.project_id and p1.isactive=1 and p1.islocked=3 ORDER BY p1.id desc LIMIT 1) 
            WHERE hd.district_id = '. $data->district_id .' AND hd.iscompleted = 1 AND hd.isactive = 1 
            GROUP BY hd.id, hd.name, hd.type_id, hd.sanctioned_cost, p.progress_date';
            
        $query = $this->db->query($sql);
        return $query->result();
    }

    function get_project_list($data) {
        $this->db->select('ph.id, ph.name, ph.type_id, pt.name as type');
        $this->db->where(array(
            'ph.district_id' => $data->district_id,
            'ph.isactive' => 1,
            'pt.isactive' => 1,
            'wo.isactive' => 1
        ));
        $this->db->where('ph.iscompleted in (0, 1)');
        $this->db->join(PROJECT_TYPE . ' pt', 'pt.id = ph.type_id');
        $this->db->join(PROJECT_WO . ' wo', 'wo.project_id=ph.id');
        $query = $this->db->get(PROJECT_HD . ' ph');
        return $query->result();
    }

    function save($data) {
        $input = array(
            'created' => date('Y-m-d'),
            'project_id' => $data->project_id,
            'date' => date('Y-m-d', strtotime(str_replace('/', '-', $data->entry_date))),
            'project_stage_id' => $data->project_stage_id,
            'user_id' => $data->user_id,
            'test_conducted' => $data->test_conducted,
            'location' => $data->location,
            'isactive' => 1
        );
        $this->db->insert(INSPECTION, $input);
        $id = $this->db->insert_id();
        if ($id > 0) {
            $input = array(
                'islocked' => 1
            );
            $this->db->where(array(
                'project_id' => $data->project_id,
                'id !=' => $id,
                'isactive' => 1
            ));
            $this->db->update(INSPECTION, $input);
            $input = array(
                'iscompleted' => 1
            );
            $this->db->where('id', $data->project_id);
            $this->db->update(PROJECT_HD, $input);
        }
        return $id;
    }


    function save_progress($data) {
        if($data->approved_by==0){
            $this->db->select('id,physical_progress');
            $this->db->where(array(
                'project_id' => $data->project_id,
                'islocked'=>3,
                'isactive'=>1
                ));
            $this->db->order_by('id DESC');
            $this->db->limit(1);
            $query = $this->db->get(PROJECT_PROGRESS);
            if ($query->num_rows() > 0) {
                $row = $query->row();
                if($data->physical_progress<$row->physical_progress){
                    return -2;
                }
            }
            $input = array(
                'created' => date('Y-m-d'),
                'project_id' => $data->project_id,
                'progress_date' => date('Y-m-d'),
                'user_id' => $data->user_id,
                'physical_progress' => $data->physical_progress,
                'financial_progress' => $data->financial_progress,
                'approved_date' => $data->approved_by>0? date('Y-m-d'):NULL,
                'approved_by' => $data->approved_by,
                'islocked' => $data->role_id==8?3:0,
                'isactive' => 1
            );
            $this->db->insert(PROJECT_PROGRESS, $input);
            $id = $this->db->insert_id();
            if ($id > 0) {
                $input = array(
                    'islocked' => 1
                );
                $whereCondition = array(
                    'project_id' => $data->project_id,
                    'id !=' => $id,
                    'islocked' =>0,
                    'isactive' => 1
                );
                $this->db->where($whereCondition);
                $this->db->update(PROJECT_PROGRESS, $input);
                $input = array(
                    'iscompleted' => 1
                );
                $this->db->where('id', $data->project_id);
                $this->db->update(PROJECT_HD, $input);
            }
        }
        if($data->approved_by>0){
            $id = $data->project_progress_id;
            if ($id > 0) {
                $input = array(
                    'physical_progress' => $data->physical_progress,
                    'financial_progress' => $data->financial_progress,
                    'approved_date' => $data->approved_by>0? date('Y-m-d'):NULL,
                    'approved_by' => $data->approved_by,
                    'islocked' => $data->islocked==-1?($data->role_id==8?-3:($data->role_id==9?-2:-1)):($data->role_id==8?3:($data->role_id==9?2:1))
                );
                $whereCondition = array(
                    'id' => $id,
                    'isactive' => 1
                );
                $this->db->where($whereCondition);
                $this->db->update(PROJECT_PROGRESS, $input);
            }
        }
        return $id;
    }





    function _update($table, $data, $condition) {
        $this->db->where($condition);
        $this->db->update($table, $data);
    }
    
    
    function last_inspection_list($data) {
        $this->db->select('i.id, i.date, i.project_id, ph.name as project_name, ph.type_id as type_id, pt.name as type, i.test_conducted, i.location, i.image');
        $this->db->where(array(
            'i.user_id' => $data->user_id,
            'i.islocked' => 0,
            'i.isactive' => 1,
            'ph.isactive' => 1
        ));
        $this->db->where('MONTH(i.date) = MONTH(CURRENT_DATE()) AND YEAR(i.date) = YEAR(CURRENT_DATE())');
        $this->db->join(PROJECT_HD . ' ph', 'ph.id = i.project_id');
        $this->db->join(PROJECT_TYPE . ' pt', 'pt.id = ph.type_id');
        $query = $this->db->get(INSPECTION . ' i');
        return $query->result();
    }
    
    
    function work_stage_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(PROJECT_STAGE);
        return $query->result();
    }
    
    
    function junior_progress_list($data) {
        $this->db->select('pp.id, pp.progress_date, pp.project_id, ph.name as project_name, pp.physical_progress, pp.financial_progress, pp.user_id as entry_user_id, u1.name as entry_user_name');
        $this->db->where(array(
            'pp.isactive' => 1,
            'ph.isactive' => 1,
            'u1.district_id' => $data->district_id,
            'u1.isactive' => 1,
        ));
        if($data->role_id==9){
            $this->db->where(array(
                'u1.role_id' => $data->role_id+1,
                'pp.islocked<' => 2,
                'pp.islocked>' => -1,
            ));
        }
        if($data->role_id==8){
            $this->db->where(array(
                'pp.islocked<' => 3,
                'pp.islocked>' => -1,
            ));
        }

        $this->db->join(PROJECT_HD . ' ph', 'ph.id = pp.project_id');
        $this->db->join(PROJECT_TYPE . ' pt', 'pt.id = ph.type_id');
        $this->db->join(USER . ' u1', 'u1.id = pp.user_id');
        $this->db->order_by('pp.id');
        $query = $this->db->get(PROJECT_PROGRESS . ' pp');
        return $query->result();
    }
    
    
       ///  _srrp

    function get_srrp_scheme_count($data) {
        $this->db->select('pp_status, COUNT(id) as total');
        $this->db->where(array(
            'pe_user_id' => $data->user_id,
            'survey_status' => 6,
            'isactive' => 1
        ));
        $this->db->group_by('pp_status');
        $query = $this->db->get(SRRP);
        return $query->result();
    }

    
    function get_srrp_list($data) {
        $this->db->select('id, created, ref_no, name, gp_id, village, length, road_type, work_type, physical_progress, wo_date, wo_status, wo_start_date, physical_progress, pp_status');
        $this->db->where(array(
            'pe_user_id' => $data->user_id,
            'survey_status' => 6,
            'isactive' => 1
        ));
        $query = $this->db->get(SRRP);
        return $query->result();
    }

    
    function get_srrp_wo_details($data) {
        $this->db->select("id, IFNULL(created,'1901-01-01') as created, srrp_id, IFNULL(wo_no,'') as wo_no, IFNULL(wo_date,'1901-01-01') as wo_date, 
                            IFNULL(contractor,'') as contractor, IFNULL(pan_no,'') as pan_no, rate, awarded_cost, IFNULL(completion_date,'1901-01-01') as completion_date, 
                            barchart_given, IFNULL(ps_cost,'') as ps_cost, IFNULL(lapse_date,'1901-01-01') as lapse_date, IFNULL(additional_ps_cost,'') as additional_ps_cost, IFNULL(dlp,'0') as dlp, 
                            IFNULL(dlp_period,'0') as dlp_period, dlp_submitted, IFNULL(document,'') as document, IFNULL(assigned_engineer,'') as assigned_engineer, 
                            IFNULL(designation,'') as designation, IFNULL(mobile,'') as mobile, islocked, isactive, lastupdated ");
        // $this->db->select('*');
        $this->db->where(array(
            'srrp_id' => $data->project_id,
            // 'survey_status' => 6,
            'isactive' => 1
        ));
        $query = $this->db->get(SRRP_WO);
        // return $query->result();
        return $query->row();
        // return $this->db->last_query();
    }
    
    
    
    // 13th feb 2023
    function get_division($data) {
        $this->db->select('id, code, name');
        $this->db->where(array(
            'parent_id' => $data->parent_id,
            'level_id' => $data->level_id,
            'isactive' => 1
        ));
        $query = $this->db->get(DIVISION);
        return $query->result();
    }

    function save_srrp($data) {
        // var_dump('hhiii');exit;
        $this->db->select('id, road_type, work_type, ifnull(physical_progress, 0) as physical_progress, wo_status, wo_start_date, pp_status');
        $this->db->where(array(
            'id' => $data->project_id,
            'isactive' => 1
        ));
        $query = $this->db->get(SRRP);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            if($row->wo_start_date==null){
                $input = array(
			'wo_start_date' => $data->wo_start_date,
			'physical_progress' => 0,
			'pp_status' => 1,
	                'progress_remarks' => $data->remarks,
                        'location1'=>null,
                        'image1'=>null,
                        'location2'=>null,
                        'image2'=>null,
                        'location3'=>null,
                        'image3'=>null,
                        'location4'=>null,
                        'image4'=>null,
                        'location5'=>null,
                        'image5'=>null
                     );
                $this->db->where('id', $data->project_id);
                $this->db->update(SRRP, $input);
                $input['created']=date('Y-m-d');
                $input['srrp_id']=$data->project_id;
                $this->db->insert(SRRP_PROGRESS, $input);
                $id = $this->db->insert_id();
                return $id;
            }
            if($row->physical_progress <= $data->physical_progress ){
                $pp_status = 0;
                if($data->physical_progress<26) $pp_status = 1;
                if($data->physical_progress>25 && $data->physical_progress<51)  $pp_status = 2;
                if($data->physical_progress>50 && $data->physical_progress<76)  $pp_status = 3;
                if($data->physical_progress>75 && $data->physical_progress<100) $pp_status = 4;
                if($data->physical_progress==100) $pp_status = 5;
                $input = array(
                    'physical_progress' => $data->physical_progress,
                    'pp_status' => $pp_status,
                    'progress_remarks' => $data->remarks,
                    'location1'=>null,
                    'image1'=>null,
                    'location2'=>null,
                    'image2'=>null,
                    'location3'=>null,
                    'image3'=>null,
                    'location4'=>null,
                    'image4'=>null,
                    'location5'=>null,
                    'image5'=>null
                );
                $this->db->where('id', $data->project_id);
                // var_dump($input);exit;
                // var_dump($data->project_id);exit;
                $this->db->update(SRRP, $input);
                $input['created']=date('Y-m-d');
                $input['srrp_id']=$data->project_id;
                $input['wo_start_date']=$data->wo_start_date;
                $this->db->insert(SRRP_PROGRESS, $input);
                $id = $this->db->insert_id();
                return $id;
            }
            return -2;
                
        }
        return 0;
    }

}

?>
