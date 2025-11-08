<?php
//$key = $this->session->userdata('user');
//$k = json_decode($this->encryption->decrypt($key), true);
//
//$this->load->model('common/common_model');
?>
<div class="logo-header" data-background-color="blue">
    <a href="index.html" class="logo">
        <img src="<?= base_url('templates/img/top_logo.png'); ?>" alt="navbar brand" class="navbar-brand">
    </a>
    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon">
            <i class="fas fa-align-justify"></i>
        </span>
    </button>
    <button class="topbar-toggler more"><i class="fas fa-ellipsis-v"></i></button>
    <div class="nav-toggle">
        <button class="btn btn-toggle toggle-sidebar">
            <i class="fas fa-align-justify"></i>
        </button>
    </div>
</div>