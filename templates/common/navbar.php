<?php
$this->load->model('common/common_model', 'common');
$key = $this->session->userdata('user');
$k = json_decode($this->encryption->decrypt($key), true);
$display = '';
if ($k['block_id'] > 0 || strlen($k['block_id']) > 0) {
    //$block = $this->common->get_table_data(DIVISION, array('id' => $k['block_id']));
    //$display .= $block[0]->name . ', ';
    $this->db->select('group_concat(name SEPARATOR ",") as name');
    $this->db->where_in('id', explode(',', $k['block_id']));
    $query = $this->db->get(DIVISION);
    $block = $query->result();
    $display .= $block[0]->name != null ? ($block[0]->name . ', ') : '';
}
if ($k['district_id'] > 0 || strlen($k['district_id']) > 0) {
//    $district = $this->common->get_table_data(DIVISION, array('id' => $k['district_id']));
    $this->db->select('group_concat(name SEPARATOR ",") as name');
    $this->db->where_in('id', explode(',', $k['district_id']));
    $query = $this->db->get(DIVISION);
    $district = $query->result();
    $display .= $district[0]->name;
}
?>
<nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">
    <div class="container-fluid">
        <p class="text-white fw-bold"><?= $display ?></p>
        <div class="collapse" id="search-nav">
            <!-- Navbar top here -->
        </div>
        <ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
            <li class="nav-item hidden-caret" style="position:relative">
                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-download"></i> 
                </a>
                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li>
                        <div class="dropdown-title">Download User Manual</div>
                    </li>
                    <li>
                        <div class="notif-scroll scrollbar-outer">
                            <div class="notif-center">
                                <a href="https://rrms.wtldssc.in/uploads/manual/RRMS_Web_User_Manual.pdf" target="_blank">
                                    <div class="notif-icon notif-primary"> <i class="fas fa-download"></i> </div>
                                    <div class="notif-content">
                                        <span class="block mt-2">
                                            Web Manual
                                        </span>
                                <!-- 	<span class="time">5 minutes ago</span>  -->
                                    </div>
                                </a>
                                <a href="https://rrms.wtldssc.in/uploads/manual/RRMS_App_Manual.pdf" target="_blank">
                                    <div class="notif-icon notif-primary"> <i class="fas fa-download"></i> </div>
                                    <div class="notif-content">
                                        <span class="block mt-2">
                                            App Manual
                                        </span>

                                    </div>
                                </a>

                            </div>
                        </div>
                    </li>

                </ul>
            </li>


            <li>


                <a class="btn btn-sm btn-white" href="<?= base_url('login/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
            <!-- <li class="nav-item">
                <a href="#" class="nav-link quick-sidebar-toggler">
                    <i class="fa fa-th"></i>
                </a>
            </li> -->
            <!-- <li class="nav-item dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="<?= base_url('templates/assets/img/profile.jpg') ?>" alt="..." class="avatar-img rounded-circle">
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                div class="avatar-lg"><img src="<?= base_url('templates/assets/img/profile.jpg') ?>" alt="image profile" class="avatar-img rounded"></div>
                                <div class="u-text">
                                    <h4><?= $k['name'] ?></h4>
                                    <p class="text-muted"><?= (isset($k['email']) && $k['email'] != NULL ? $k['email'] : '') ?></p>

                                </div>
                            </div>
                        </li>
                        <li>

                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('login/logout') ?>">Logout</a>
                        </li>
                    </div>
                </ul>
            </li> -->
        </ul>
    </div>
</nav>