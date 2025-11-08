<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Um_Model extends CI_Model {

    function get_user_list($role_id = 0) {
        $key = $this->session->userdata('user');
        $k = json_decode($this->encryption->decrypt($key), true);
        $this->db->select('u.id, u.name, r.name as role, d.name as district, u.mobile, u.email, u.photo, ul.username');
        $this->db->where(array(
            'u.id > ' => 2,
            'u.isactive' => 1,
            'ul.isactive' => 1
        ));
        if ($k['role_id'] > 3 && $k['district_id'] > 0) {
            $this->db->where('u.district_id', $k['district_id']);
        }
        if ($role_id > 0) {
            $this->db->where('u.role_id', $role_id);
        }
        $this->db->join(LOGIN . ' ul', 'ul.user_id=u.id');
        $this->db->join(ROLE . ' r', 'u.role_id=r.id');
        $this->db->join(DIVISION . ' d', 'u.district_id=d.id', 'left');
        $this->db->order_by('r.id, d.name');
        $query = $this->db->get(USER . ' u');
        return $query->result();
    }

    function get_user_info($user_id) {
        $this->db->select('u.id, u.name, u.mobile, u.email, u.role_id, u.district_id, u.block_id, u.photo, ul.username, ul.password');
        $this->db->where(array(
            'u.id' => $user_id,
            'ul.isactive' => 1
        ));
        $this->db->join(LOGIN . ' ul', 'ul.user_id=u.id');
        $query = $this->db->get(USER . ' u');
        return $query->row();
    }

    function get_menu_level_list($level_id, $parent_id, $role_id) {
        $this->db->select('m.id, m.level_id, m.name, m.has_child, IF(mr.id>0, 1, 0) as checked');
        $this->db->where(array(
            'm.level_id' => $level_id,
            'm.parent_id' => $parent_id,
            'm.isactive' => 1
        ));
        $this->db->order_by('m.sequence');
        $this->db->join(MENU_ROLE . ' mr', 'mr.menu_id=m.id AND mr.isactive=1 AND mr.role_id=' . $role_id, 'left');
        $query = $this->db->get(MENU . ' m');
        return $query->result();
    }

    function get_menu_list($role_id) {
        $level0 = $this->get_menu_level_list(0, 0, $role_id);
        if (sizeof($level0) > 0) {
            foreach ($level0 as $l0) {
                $arr = array(
                    'id' => $l0->id,
                    'level' => $l0->level_id,
                    'text' => $l0->name,
                    'checked' => $l0->checked > 0 ? true : false
                );
                if ($l0->has_child > 0) {
                    $level1 = $this->get_menu_level_list(1, $l0->id, $role_id);
                    if (sizeof($level1) > 0) {
                        $child1 = array();
                        foreach ($level1 as $l1) {
                            $arr1 = array(
                                'id' => $l1->id,
                                'level' => $l1->level_id,
                                'text' => $l1->name,
                                'checked' => $l1->checked > 0 ? true : false
                            );
                            if ($l1->has_child > 0) {
                                $level2 = $this->get_menu_level_list(2, $l1->id, $role_id);
                                if (sizeof($level2) > 0) {
                                    $child2 = array();
                                    foreach ($level2 as $l2) {
                                        $arr2 = array(
                                            'id' => $l2->id,
                                            'level' => $l2->level_id,
                                            'text' => $l2->name,
                                            'checked' => $l2->checked > 0 ? true : false,
//                                            'onlyView' => $l2->onlyview > 0 ? true : false
                                        );
                                        $child2[] = $arr2;
                                    }
                                    $arr1['children'] = $child2;
                                }
                            } else {
                                //$arr1['onlyView'] = $l1->onlyview > 0 ? true : false;
                            }
                            $child1[] = $arr1;
                        }
                        $arr['children'] = $child1;
                    }
                } else {
                    //$arr['onlyView'] = $l0->onlyview > 0 ? true : false;
                }
                $menu[] = $arr;
            }
        }
        return $menu;
    }

    function save_role($data) {
        $this->db->trans_start();
        $role_id = $data['role_id'];
        $input = array(
            'isactive' => -1
        );
        $this->db->where('role_id', $role_id);
        $this->db->update(MENU_ROLE, $input);
        $treeview = json_decode($data['treeview']);
        foreach ($treeview as $tv) {
            $input = array(
                'role_id' => $role_id,
                'menu_id' => $tv->id,
                'level_id' => $tv->level,
                'isview' => isset($tv->onlyview) ? $tv->onlyview : 0,
                'isactive' => 1
            );
            $this->db->where(array(
                'role_id' => $role_id,
                'menu_id' => $tv->id
            ));
            $query = $this->db->get(MENU_ROLE);
            $id = 0;
            if ($query->num_rows() > 0) {
                $id = $query->row()->id;
            }
            if ($id > 0) {
                $this->db->where('id', $id);
                $this->db->update(MENU_ROLE, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(MENU_ROLE, $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    function save_user($data) {
        $this->db->trans_start();
        $user_id = $data['user_id'];
        $input = array(
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'role_id' => $data['role_id'],
            'district_id' => array_key_exists('district_id', $data) ? implode(',', $data['district_id']) : '',
            'block_id' => array_key_exists('block_id', $data) ? implode(',', $data['block_id']) : '',
            'isactive' => 1
        );
        if ($user_id > 0) {
            $this->db->where('id', $user_id);
            $this->db->update(USER, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(USER, $input);
            $user_id = $this->db->insert_id();
        }
        if ($user_id > 0) {
            $this->db->where('user_id', $user_id);
            $query = $this->db->get(LOGIN);
            $user_login_id = 0;
            if ($query->num_rows() > 0) {
                $user_login_id = $query->row()->id;
            }
            $input = array(
                'user_id' => $user_id,
                'username' => $data['username'],
                'password' => $this->encryption->encrypt(DEFAULT_PWD),
                'isactive' => 1
            );
            if ($user_login_id > 0) {
                $this->db->where('id', $user_login_id);
                $this->db->update(LOGIN, $input);
            } else {
                $input['created'] = date('Y-m-d');
                $this->db->insert(LOGIN, $input);
            }
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return 0;
        } else {
            $this->db->trans_commit();
            return $user_id;
        }
    }

    function check_username($username) {
        $this->db->select('ifnull(id,0) as id');
        $this->db->where(array(
            'username' => $username
        ));
        $query = $this->db->get(LOGIN);
        //var_dump($this->db->last_query());exit;
        return $query->num_rows() > 0 ? $query->row()->id : 0;
    }

    function reset($id) {
        $input['password'] = $this->encryption->encrypt(DEFAULT_PWD);
        $this->db->where('user_id', $id);

        $this->db->update(LOGIN, $input);
    }

    function remove($id) {
        $input['isactive'] = -1;
        $this->db->where('user_id', $id);
        $this->db->update(LOGIN, $input);
        $input['isactive'] = -1;
        $this->db->where('id', $id);
        $this->db->update(USER, $input);
    }

}
