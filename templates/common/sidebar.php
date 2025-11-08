<?php
$this->load->model('common/common_model', 'common');
$arr = isset($k['name']) ? explode(' ', $k['name']) : $k['username'];

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
//$sql = 'SELECT id, parent_id, level_id FROM menu WHERE IF(INSTR("' . $path . '", "/srrp/rpt_") > 0, id=16, (INSTR("' . substr($path, 1) . '", link)>0))';

$row = '';
$sql = 'SELECT id, parent_id, level_id FROM menu WHERE link="' . ltrim($path, '/') . '"';
$query = $this->db->query($sql);
if ($query->num_rows() > 0) {
    $row = $query->row();
} else {
    $sql = 'SELECT id, parent_id, level_id FROM menu WHERE IF(INSTR("' . $path . '", "/srrp/rpt_") > 0, id=16, (INSTR(link, "' . substr($path, 1) . '")>0))';
    $query = $this->db->query($sql);
    if ($query->num_rows() > 0) {
        $row = $query->row();
    }
}
$level_0 = 0;
$level_1 = 0;
$level_2 = 0;
if ($row != '') {
    $level_id = $row->level_id;
    for ($i = $level_id; $i > -1; $i--) {
        switch ($row->level_id) {
            case 0:
                $level_0 = $row->id;
                break;
            case 1:
                $level_1 = $row->id;
                break;
            case 2:
                $level_2 = $row->id;
            default:
                break;
        }
        if ($row->parent_id > 0) {
            $sql = 'SELECT id, parent_id, level_id FROM menu WHERE id=' . $row->parent_id;
            $query = $this->db->query($sql);
            $row = $query->row();
        }
    }
}
?>
<style>
    .user {
        padding-left: 0px !important;
    }
    .avatar-custom {
        height: 2.5rem;
    }
</style>
<div class="sidebar sidebar-style-2">
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <div class="user">
                <div class="info">
                    <a data-toggle="collapse" href="#collapseExample" aria-expanded="true">
                        <span>
                            <b><?= $k['name'] ?></b>
                            <span class="user-level"><?= (isset($k['designation']) ? $k['designation'] : $k['username']) ?></span>
                            <span class="caret"></span>
                        </span>
                    </a>
                    <div class="clearfix"></div>
                    <div class="collapse in" id="collapseExample">
                        <ul class="nav">
                            <li>
                                <a href="<?= base_url('login/reset') ?>">
                                    <span class="link-collapse">Change Password</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <ul class="nav nav-primary">
                <!--                <li class="nav-item">
                                    <a href="<?= base_url('dashboard') ?>">
                                        <i class="fas fa-home"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>-->

                <?php
                if ($k['role_id'] > 3) {
                    echo '<h4 class="text-section"><hr></h4>';
                    $level0 = $this->common->get_menu_list(0, 0);
                    $active = '';
                    $show = '';
                    ?>
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard/rpt_general') ?>">
                            <i class="fas fa-file"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <?php
                    foreach ($level0 as $l0) {
                        $active = $level_0 == $l0->id ? 'active' : '';
                        $show = $level_0 == $l0->id ? 'show' : '';
                        if ($l0->has_child > 0) {
                            echo '<li class="nav-item ' . $active . '">';
                            echo '<a data-toggle="collapse" href="#div_' . $l0->id . '" class="collapsed" aria-expanded="false">
                            <i class="' . $l0->class . '"></i><p><b>' . $l0->name . '</b></p>
                            <span class="caret"></span></a>';
                            echo '<div class="collapse ' . $show . '" id="div_' . $l0->id . '"><ul class="nav nav-collapse">';
                            $level1 = $this->common->get_menu_list(1, $l0->id);
                            foreach ($level1 as $l1) {
                                $active = $level_1 == $l1->id ? 'active' : '';
                                $show = $level_1 == $l1->id ? 'show' : '';
                                if ($l1->has_child > 0) {
                                    echo '<li><a data-toggle="collapse" href="#div_' . $l1->id . '"><span class="sub-item">' . $l1->name . '</span><span class="caret"></span></a>
                                <div class="collapse ' . $show . '" id="div_' . $l1->id . '"><ul class="nav nav-collapse subnav">';
                                    $level2 = $this->common->get_menu_list(2, $l1->id);
                                    foreach ($level2 as $l2) {
                                        $active = $level_2 == $l2->id ? 'active' : '';
                                        echo '<li class="' . $active . '"><a href="' . base_url($l2->link) . '"><span class="sub-item">' . $l2->name . '</span></a></li>';
                                    }
                                    echo '</ul></div></li>';
                                } else {
                                    echo '<li class="' . $active . '"><a href="' . base_url($l1->link) . '"><span class="sub-item">' . $l1->name . '</span></a></li>';
                                }
                            }
                            echo '</ul></div>';
                            echo '</li>';
                        } else {
                            echo '<li class="nav-item ' . $active . '"><a href="' . base_url($l0->link) . '"><i class="' . $l0->class . '"></i><p><b>' . $l0->name . '</b></p></a></li>';
                        }
                    }
                    echo '<li class="nav-item"><a href="' . base_url('login/reset') . '"><i class="fas fa-unlock-alt"></i><p>Change Password</p></a></li>';
                    echo '<li class="nav-item"><a href="' . base_url('login/logout') . '"><i class="fas fa-sign-out-alt"></i><p>Logout</p></a></li>';
                } else {
                    ?>
                    <li class="nav-item">
                        <a href="<?= base_url('dashboard/menu') ?>">
                            <i class="fas fa-file"></i>
                            <p>Menu</p>
                        </a>
                    </li>
                    <?php
                    $menu_id = $this->session->userdata('menu');
                    if ($menu_id > 0) {
                        echo '<h4 class="text-section"><hr></h4>';
                        $this->db->where('id', $menu_id);
                        $query = $this->db->get(MENU);
                        $menu = $query->row();
                        echo '<li class="nav-item active">';
                        echo '<a data-toggle="collapse" href="#div_' . $menu->id . '" class="collapsed" aria-expanded="false">
                            <i class="' . $menu->class . '"></i><p><b>' . $menu->name . '</b></p>
                            <span class="caret"></span></a>';
                        echo '<div class="collapse show" id="div_' . $menu->id . '"><ul class="nav nav-collapse">';
                        $level1 = $this->common->get_menu_list(1, $menu->id);
                        foreach ($level1 as $l1) {
                            $active = $level_1 == $l1->id ? 'active' : '';
                            $show = $level_1 == $l1->id ? 'show' : '';
                            if ($l1->has_child > 0) {
                                echo '<li><a data-toggle="collapse" href="#div_' . $l1->id . '"><span class="sub-item">' . $l1->name . '</span><span class="caret"></span></a>
                                <div class="collapse ' . $show . '" id="div_' . $l1->id . '"><ul class="nav nav-collapse subnav">';
                                $level2 = $this->common->get_menu_list(2, $l1->id);
                                foreach ($level2 as $l2) {
                                    $active = $level_2 == $l2->id ? 'active' : '';
                                    echo '<li class="' . $active . '"><a href="' . base_url($l2->link) . '"><span class="sub-item">' . $l2->name . '</span></a></li>';
                                }
                                echo '</ul></div></li>';
                            } else {
                                echo '<li class="' . $active . '"><a href="' . base_url($l1->link) . '"><span class="sub-item">' . $l1->name . '</span></a></li>';
                            }
                        }
                        echo '</ul></div>';
                        echo '</li>';
                    }
//                    echo '<li class="nav-item"><a href="' . base_url('login/reset') . '"><i class="fas fa-unlock-alt"></i><p>Change Password</p></a></li>';
//                        echo '<li class="nav-item"><a href="' . base_url('login/logout') . '"><i class="fas fa-sign-out-alt"></i><p>Logout</p></a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
