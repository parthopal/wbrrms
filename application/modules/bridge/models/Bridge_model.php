<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bridge_Model extends CI_Model {

    function get_total_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('count(DISTINCT district_id) as district, count(id) as total, sum(if(isactive=0, 1, 0)) as drafted, sum(if(isactive=1, 1, 0)) as locked');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('isactive>-1');
        $query = $this->db->get(BRIDGE); //echo $this->db->last_query(); exit;
        return $query->row();
    }

    function get_condition_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('condition_id, count(id) as cnt');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('isactive>-1');
        $this->db->group_by('condition_id');
        $this->db->order_by('condition_id');
        $query = $this->db->get(BRIDGE);
        return $query->result();
    }

    function get_material_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('material_id, count(id) as cnt');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('isactive>-1');
        $this->db->group_by('material_id');
        $this->db->order_by('material_id');
        $query = $this->db->get(BRIDGE);
        $result = $query->result();
        $total = 0;
        foreach ($result as $row) {
            $total += $row->cnt;
        }
        $material = '';
        foreach ($result as $row) {
            $material .= (strlen($material) > 0 ? ',' : '') . round(($row->cnt / $total) * 100, 0);
        }
        return $material;
    }

    function get_category_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('c.code, count(b.id) as cnt');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('b.isactive>-1');
        $this->db->join(BRIDGE_CATEGORY . ' c', 'b.category_id=c.id');
        $this->db->group_by('c.code');
        $this->db->order_by('category_id');
        $query = $this->db->get(BRIDGE . ' b');
        $result = $query->result();
        $total = 0;
        $name = '';
        foreach ($result as $row) {
            $name .= (strlen($name) > 0 ? ',' : '') . '"' . $row->code . '"';
            $total += $row->cnt;
        }
        $count = '';
        foreach ($result as $row) {
            $count .= (strlen($count) > 0 ? ',' : '') . round(($row->cnt / $total) * 100, 0);
        }
        $arr = array(
            'name' => $name,
            'cnt' => $count
        );
        return $arr;
    }

    function get_ownership_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('o.name, count(b.id) as cnt');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('b.isactive>-1');
        $this->db->join(BRIDGE_OWNERSHIP . ' o', 'b.ownership_id=o.id');
        $this->db->group_by('o.name');
        $this->db->order_by('ownership_id');
        $query = $this->db->get(BRIDGE . ' b');
        $result = $query->result();
        $total = 0;
        $name = '';
        $count = '';
        foreach ($result as $row) {
            $name .= (strlen($name) > 0 ? ',' : '') . '"' . $row->name . '"';
            $count .= (strlen($count) > 0 ? ',' : '') . $row->cnt;
        }
        $arr = array(
            'name' => $name,
            'cnt' => $count
        );
        return $arr;
    }

    function get_districtwise_condition_count() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'and b.district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and b.block_id in (' . $session['block_id'] . ')' : '';
        $sql = 'SELECT d.name, ifnull(g.cnt,0) as good, ifnull(p.cnt,0) as poor, ifnull(hv.cnt,0) as hv FROM bridge b JOIN division d ON b.district_id=d.id '
                . 'LEFT JOIN (select district_id, count(id) as cnt from bridge where isactive>-1 and condition_id=1 group by district_id) g on g.district_id=b.district_id '
                . 'LEFT JOIN (select district_id, count(id) as cnt from bridge where isactive>-1 and condition_id=2 group by district_id) p on p.district_id=b.district_id '
                . 'LEFT JOIN (select district_id, count(id) as cnt from bridge where isactive>-1 and condition_id=3 group by district_id) hv on hv.district_id=b.district_id '
                . 'WHERE b.isactive > -1 ' . $where . ' GROUP BY d.name ORDER BY d.name;';
        $query = $this->db->query($sql);
        $result = $query->result();
        $total = 0;
        $name = '';
        $good = '';
        $poor = '';
        $hv = '';
        foreach ($result as $row) {
            $name .= (strlen($name) > 0 ? ',' : '') . '"' . $row->name . '"';
            $good .= (strlen($good) > 0 ? ',' : '') . $row->good;
            $poor .= (strlen($poor) > 0 ? ',' : '') . $row->poor;
            $hv .= (strlen($hv) > 0 ? ',' : '') . $row->hv;
        }
        $arr = array(
            'name' => $name,
            'good' => $good,
            'poor' => $poor,
            'hv' => $hv
        );
        return $arr;
    }

    function get_entry_data() {
        $session = $this->common->get_session();
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->select('group_concat(distinct date_format(created, "%d/%m") order by created desc) as date');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('isactive>-1');
        $this->db->order_by('created desc');
        $this->db->limit(12);
        $query = $this->db->get(BRIDGE);
        $label = $query->row()->date;
        $where = $session['district_id'] > 0 ? 'district_id in (' . $session['district_id'] . ')' : '';
        $where .= $session['block_id'] > 0 ? ' and block_id in (' . $session['block_id'] . ')' : '';
        $this->db->distinct();
        $this->db->select('created, count(id) as cnt');
        strlen($where) > 0 ? $this->db->where($where) : '';
        $this->db->where('isactive>-1');
        $this->db->group_by('created');
        $this->db->order_by('created desc');
        $this->db->limit(12);
        $query = $this->db->get(BRIDGE);
        $result = $query->result();
        $cnt = array();
        foreach ($result as $row) {
            $cnt[] = $row->cnt;
        }
        $arr = array(
            'label' => $label,
            'data' => implode(',', $cnt)
        );
        return $arr;
    }

    function get_bridge_district_list() {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            'd.level_id' => 2,
            'd.isactive' => 1,
            'b.isactive>' => -1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('d.id', explode(',', $session['district_id']));
        }
        $this->db->order_by('d.name');
        $this->db->join(DIVISION . ' d', 'b.district_id=d.id');
        $query = $this->db->get(BRIDGE . ' b');
        return $query->result();
    }

    function get_bridge_block_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('d.id, d.name');
        $this->db->where(array(
            'd.level_id' => 3,
            'd.parent_id' => $selected['district_id'],
            'd.isactive' => 1,
            'b.isactive>' => -1
        ));
        if ($session['block_id'] > 0) {
            $this->db->where_in('id', explode(',', $session['block_id']));
        }
        $this->db->order_by('d.name');
        $this->db->join(DIVISION . ' d', 'b.block_id=d.id');
        $query = $this->db->get(BRIDGE . ' b');
        return $query->result();
    }

    function get_bridge_scheme_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('s.id, s.name');
        $this->db->where(array(
            'b.isactive' => 1,
            's.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.scheme_id=s.id');
        $query = $this->db->get(BRIDGE_SCHEME . ' s');
        return $query->result();
    }

    function get_bridge_foundation_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('f.id, f.name');
        $this->db->where(array(
            'b.isactive' => 1,
            'f.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.foundation_id=f.id');
        $query = $this->db->get(BRIDGE_FOUNDATION . ' f');
        return $query->result();
    }

    function get_bridge_superstructure_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('s.id, s.name');
        $this->db->where(array(
            'b.isactive' => 1,
            's.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.superstructure_id=s.id');
        $query = $this->db->get(BRIDGE_SUPERSTRUCTURE . ' s');
        return $query->result();
    }

    function get_bridge_substructure_material_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('s.id, s.name');
        $this->db->where(array(
            'b.isactive' => 1,
            's.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.material_id=s.id');
        $query = $this->db->get(BRIDGE_SUBSTRUCTURE_MATERIAL . ' s');
        return $query->result();
    }

    function get_bridge_substructure_type_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('s.id, s.name');
        $this->db->where(array(
            'b.isactive' => 1,
            's.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.type_id=s.id');
        $query = $this->db->get(BRIDGE_SUBSTRUCTURE_TYPE . ' s');
        return $query->result();
    }

    function get_bridge_ownership_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('o.id, o.name');
        $this->db->where(array(
            'b.isactive' => 1,
            'o.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.ownership_id=o.id');
        $query = $this->db->get(BRIDGE_OWNERSHIP . ' o');
        return $query->result();
    }

    function get_bridge_condition_list($selected) {
        $session = $this->common->get_session();
        $this->db->distinct();
        $this->db->select('c.id, c.name');
        $this->db->where(array(
            'b.isactive' => 1,
            'c.isactive' => 1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        $this->db->join(BRIDGE . ' b', 'b.condition_id=c.id');
        $query = $this->db->get(BRIDGE_CONDITION . ' c');
        return $query->result();
    }

    function get_ownership_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_OWNERSHIP);
        return $query->result();
    }

    function get_condition_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_CONDITION);
        return $query->result();
    }

    function get_foundation_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_FOUNDATION);
        return $query->result();
    }

    function get_scheme_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_SCHEME);
        return $query->result();
    }

    function get_substructure_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_SUBSTRUCTURE_TYPE);
        return $query->result();
    }

    function get_substructure_material_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_SUBSTRUCTURE_MATERIAL);
        return $query->result();
    }

    function get_superstructure_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_SUPERSTRUCTURE);
        return $query->result();
    }

    function get_type_list() {
        $this->db->select('id, name');
        $this->db->where(array(
            'isactive' => 1
        ));
        $query = $this->db->get(BRIDGE_TYPE);
        return $query->result();
    }

    function get_bridge_list($selected) {
        $session = $this->common->get_session();
        $this->db->select('b.id, d.name as district, block.name as block, b.name, b.ref_no, s.name as scheme, b.length, b.width, b.chainage, c.name as condition, b.ownership, m.name as material, t.name as type, f.name as foundation, sup.name as superstructure, b.image_side, b.image_alignment, b.image_a1, b.image_a2, b.location, b.isactive');
        $this->db->where(array(
            'b.isactive>' => -1
        ));
        if ($session['district_id'] > 0) {
            $this->db->where_in('b.district_id', explode(',', $session['district_id']));
        } else if ($selected['district_id'] > 0) {
            $this->db->where('b.district_id', $selected['district_id']);
        }
        if ($session['block_id'] > 0) {
            $this->db->where_in('b.block_id', explode(',', $session['block_id']));
        } else if ($selected['block_id'] > 0) {
            $this->db->where('b.block_id', $selected['block_id']);
        }
        if ($selected['scheme_id'] > 0) {
            $this->db->where('b.scheme_id', $selected['scheme_id']);
        }
        if ($selected['condition_id'] > 0) {
            $this->db->where('b.condition_id', $selected['condition_id']);
        }
        if ($selected['ownership_id'] > 0) {
            $this->db->where('b.ownership_id', $selected['ownership_id']);
        }
        if ($selected['material_id'] > 0) {
            $this->db->where('b.material_id', $selected['material_id']);
        }
        if ($selected['type_id'] > 0) {
            $this->db->where('b.type_id', $selected['type_id']);
        }
        if ($selected['foundation_id'] > 0) {
            $this->db->where('b.foundation_id', $selected['foundation_id']);
        }
        if ($selected['superstructure_id'] > 0) {
            $this->db->where('b.superstructure_id', $selected['superstructure_id']);
        }
        $this->db->join(DIVISION . ' d', 'b.district_id=d.id');
        $this->db->join(DIVISION . ' block', 'b.block_id=block.id');
        $this->db->join(BRIDGE_SCHEME . ' s', 'b.scheme_id=s.id');
        $this->db->join(BRIDGE_CONDITION . ' c', 'b.condition_id=c.id');
        $this->db->join(BRIDGE_OWNERSHIP . ' o', 'b.ownership_id=o.id');
        $this->db->join(BRIDGE_SUBSTRUCTURE_MATERIAL . ' m', 'b.material_id=m.id');
        $this->db->join(BRIDGE_SUBSTRUCTURE_TYPE . ' t', 'b.type_id=t.id', 'left');
        $this->db->join(BRIDGE_FOUNDATION . ' f', 'b.foundation_id=f.id', 'left');
        $this->db->join(BRIDGE_SUPERSTRUCTURE . ' sup', 'b.superstructure_id=sup.id', 'left');
        $query = $this->db->get(BRIDGE . ' b');
        return $query->result();
    }

    function get_bridge_info($id) {
        $this->db->where('id', $id);
        $query = $this->db->get(BRIDGE);
        return $query->row();
    }

    function save($data) {
        $this->db->trans_start();
        $id = $data['id'];
        $ownership = $data['ownership'];
        $this->db->select('id');
        $this->db->where(array(
            'min_length<=' => $data['length'],
            'max_length>=' => $data['length']
        ));
        $query = $this->db->get(BRIDGE_CATEGORY);
        $category_id = $query->row()->id;
        if ($data['ownership_id'] < 50) {
            $this->db->select('name');
            $this->db->where('id', $data['ownership_id']);
            $query = $this->db->get(BRIDGE_OWNERSHIP);
            $ownership = $query->row()->name;
        }

        $input = array(
            'district_id' => $data['district_id'],
            'block_id' => $data['block_id'],
            'name' => $data['name'],
            'scheme_id' => $data['scheme_id'],
            'package_no' => $data['package_no'],
            'category_id' => $category_id,
            'length' => $data['length'],
            'width' => $data['width'],
            'chainage' => $data['chainage'],
            'location' => $data['latitude'] . ',' . $data['longitude'],
            'foundation_id' => $data['foundation_id'],
            'superstructure_id' => $data['material_id'] < 50 ? $data['superstructure_id'] : 0,
            'type_id' => $data['material_id'] < 50 ? $data['type_id'] : 0,
            'material_id' => $data['material_id'],
            'ownership_id' => $data['ownership_id'],
            'ownership' => $ownership,
            'condition_id' => $data['condition_id'],
            'isactive' => 0
        );
        if ($id > 0) {
            $this->db->where('id', $id);
            $this->db->update(BRIDGE, $input);
        } else {
            $input['created'] = date('Y-m-d');
            $this->db->insert(BRIDGE, $input);
            $id = $this->db->insert_id();
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $id;
    }

    function _get_ref_no($id, $block_id) {
        $this->db->select('max(bridge_count) as cnt');
        $this->db->where(array(
            'block_id' => $block_id
        ));
        $query = $this->db->get(BRIDGE);
        $cnt = $query->row()->cnt + 1;
        $this->db->select('o.name as ownership, c.code as category, d.code');
        $this->db->where(array(
            'b.id' => $id
        ));
        $this->db->join(BRIDGE_OWNERSHIP . ' o', 'b.ownership_id=o.id');
        $this->db->join(BRIDGE_CATEGORY . ' c', 'b.category_id=c.id');
        $this->db->join(DIVISION . ' d', 'b.block_id=d.id');
        $query = $this->db->get(BRIDGE . ' b');
        $row = $query->row();
        $ref_no = 'WB/PRD/' . $row->ownership . '/' . $row->category . '/' . $row->code . str_pad($cnt, 3, '0', STR_PAD_LEFT);
        $input = array(
            'bridge_count' => $cnt,
            'ref_no' => $ref_no
        );
        $this->db->where('id', $id);
        $this->db->update(BRIDGE, $input);
    }

    function status($data) {
        $id = $data['id'];
        $this->db->where('id', $id);
        $query = $this->db->get(BRIDGE);
        if ($query->row()->bridge_count == 0) {
            $this->_get_ref_no($id, $query->row()->block_id);
        }
        $input = array(
            'isactive' => $data['status']
        );
        $this->db->where('id', $data['id']);
        $this->db->update(BRIDGE, $input);
        return true;
    }

################################################################################    
################################################################################
################################ REPORT ########################################

    function filter_with_agency() {
        $session = $this->common->get_session();
        $filter = '';
        switch ($session['role_id']) {
            case 13:
                $filter = ' and agency = "ZP"';
                break;
            case 14:
                $filter = ' and agency = "BLOCK"';
                break;
            case 15:
                $filter = ' and agency = "SRDA"';
                break;
            case 16:
                $filter = ' and agency = "MBL"';
                break;
            case 17:
                $filter = ' and agency = "AGRO"';
                break;
            default:
                break;
        }
        return $filter;
    }
	
	function rpt_get_state_summary(){
        //$where = 'b.isactive > -1';
        //$where = $this->filter_with_agency();
        $sql='SELECT count(b.id) as total_bridge, d.name as district, sum(b.length) as length, sum(if(b.condition_id = 1, 1, 0)) as good, sum(if(b.condition_id = 2, 1, 0)) as poor , sum(if(b.condition_id = 3, 1, 0)) as highly,sum(if(b.condition_id = 1, b.length, 0)) as good_length, sum(if(b.condition_id = 2, b.length, 0)) as poor_length , sum(if(b.condition_id = 3, b.length, 0)) as highly_length , sum(if(b.isactive = 0, 1, 0)) as drafted, sum(if(b.isactive = 1, 1, 0)) as locked,sum(if(b.isactive = -1, 1, 0)) as picture from bridge as b join division as d on b.district_id = d.id join bridge_condition as bc on b.condition_id = bc.id WHERE b.isactive > -1 group by d.name order by district ';
        $query = $this->db->query($sql);
        // echo $this->db->last_query(); exit;
        return $query->result();
    }
}
