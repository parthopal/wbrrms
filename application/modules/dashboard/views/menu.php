<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<style>
    .center-align {
        float: none;
        margin: 0 auto;
        text-align: center;
    }
</style>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Menu</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-2">
                        <div class="row">
                            <div class="col-md-2">
                                <a href="<?= base_url('srrp/overview') ?>">
                                    <div class="card card-stats card-success card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">PATHASHREE-II</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('ridf/overview') ?>">
                                    <div class="card card-stats card-secondary card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">RIDF</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('scheme/overview/pmgsy') ?>">
                                    <div class="card card-stats card-warning card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">PMGSY</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('sf/overview') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #8E24AA !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">STATE FUND & OTHERS</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('capex/overview') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #34ace0 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">CAPEX</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('ssm/overview') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #E91E63 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">PATHASHREE-III</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('bridge/overview') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #8E24AA !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">BRIDGE</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            
                            <div class="col-md-3">
                                <a href="<?= base_url('sfurgent/') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #2460aa !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">STATE FUND - URGENT</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4">
                                <a href="<?= base_url('dashboard/rpt_general') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #004D40 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">REPORT OVERVIEW</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('roads/overview') ?>">
                                    <div class="card card-stats card-warning card-round" style="background: linear-gradient(135deg, #618effff, #1e8ae9ff) !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">RURAL ROADS(2025)</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-md-3">
                                <a href="<?= base_url('um') ?>">
                                    <div class="card card-stats card-danger card-round" style="background-color: #8E24AA !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">User Management</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-2">
                                <a href="<?= base_url('proposal') ?>">
                                    <div class="card card-stats card-warning card-round" style="background-color: #34ace0 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Scheme Bank</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-3">
                                <a href="<?= base_url('log/view') ?>">
                                    <div class="card card-stats card-secondary card-round">
                                        <div class="card-body">
                                            <div class="row" style="text-align: center;">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Call Log / Support</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row ml-1">
                            <div class="col-md-11">
                                <h4>Un-authorised persons are not allowed here !!!</h4>
                            </div>
                            <div id="private" class="col-md-1 pull-right pointer"><i class="fas fa-arrow-down fa-2x"></i></div>
                        </div>
                    </div>
                    <div id="show_hide" class="card-body mt-3">
                        <div class="row">
                            <div id="engineer" class="col-md-2 pointer">
                                <div class="card card-stats card-danger card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Engineer</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#show_hide').hide();
        $('#private').on('click', function(e) {
            if ($('#private').html() === '<i class="fas fa-arrow-down fa-2x"></i>') {
                $('#show_hide').show();
                $('#private').html('<i class="fas fa-arrow-up fa-2x"></i>');
            } else {
                $('#show_hide').hide();
                $('#private').html('<i class="fas fa-arrow-down fa-2x"></i>');
            }
        });
        $('#engineer').on('click', function(e) {
            e.preventDefault();
            let pwd = prompt('Enter your password to access this module');
            if (pwd.length > 0) {
                $.ajax({
                    url: baseURL + '/common/is_authorised',
                    type: 'post',
                    data: {
                        pwd: pwd
                    },
                    dataType: 'json',
                    async: false
                }).done(function(data) {
                    if (data > 0) {
                        window.open(baseURL + '/engineer', '_self');
                    } else {
                        alert('You are not the authorised person to access this module!!!');
                    }
                });
            }
        });
    });
</script>











<!-- <?php
defined('BASEPATH') or exit('No direct script access allowed');
?> -->
<!-- <style>
    body {
        background: #f7f9fc; /* light clean background */
        font-family: "Poppins", sans-serif;
    }

    /* Header */
    .dashboard-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(90deg, #74ebd5, #9face6); /* soft gradient */
        border-radius: 15px;
        padding: 15px 25px;
        margin: 20px 0;
        color: #fff;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }

    .dashboard-header img {
        height: 50px;
    }

    .dashboard-header h2 {
        font-weight: 700;
        font-size: 24px;
        margin: 0;
    }

    /* Menu Container Background */
    .menu-wrapper {
        background: linear-gradient(135deg, #e0f7fa, #e3f2fd);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
    }

    .menu-title {
        font-weight: 600;
        font-size: 20px;
        margin-bottom: 20px;
        color: #333;
    }

    .menu-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 25px;
    }

    /* Cards */
    .menu-card {
        background: rgba(255, 255, 255, 0.65);
        backdrop-filter: blur(12px);
        border-radius: 18px;
        padding: 30px 20px;
        text-align: center;
        color: #333;
        transition: all 0.3s ease;
        position: relative;
        cursor: pointer;
    }

    .menu-card i {
        font-size: 38px;
        margin-bottom: 12px;
        display: block;
        color: #1976d2;
    }

    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .menu-card p {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
    }

    /* Restricted Section */
    .restricted {
        margin-top: 20px;
        background: #fff;
        border: 1px solid #ffcdd2;
        border-radius: 18px;
        padding: 20px;
        color: #d32f2f;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    }

    .restricted h4 {
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    #private {
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    #private:hover {
        transform: rotate(180deg);
    }
</style> -->

<!-- <div class="container"> -->

    <!-- Header with Logo -->
    <!-- <div class="dashboard-header">
        <div class="d-flex align-items-center">
            <img src="<?= base_url('assets/logo.png') ?>" alt="Logo">
            <h2 class="ml-3">Smart Road Dashboard</h2>
        </div>
        <span class="text-white-50">Welcome, <?= $this->session->userdata('username'); ?></span>
    </div> -->

    <!-- Main Menu -->
    <!-- <div class="menu-wrapper">
        <div class="menu-title">Main Menu</div>
        <div class="menu-container">

            <a href="<?= base_url('srrp/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-road"></i>
                    <p>PATHASHREE-II</p>
                </div>
            </a>

            <a href="<?= base_url('ridf/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-university"></i>
                    <p>RIDF</p>
                </div>
            </a>

            <a href="<?= base_url('scheme/overview/pmgsy') ?>">
                <div class="menu-card">
                    <i class="fas fa-project-diagram"></i>
                    <p>PMGSY</p>
                </div>
            </a>

            <a href="<?= base_url('sf/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-landmark"></i>
                    <p>STATE FUND & OTHERS</p>
                </div>
            </a>

            <a href="<?= base_url('capex/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-coins"></i>
                    <p>CAPEX</p>
                </div>
            </a>

            <a href="<?= base_url('ssm/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-road"></i>
                    <p>PATHASHREE-III</p>
                </div>
            </a>

            <a href="<?= base_url('bridge/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-archway"></i>
                    <p>BRIDGE</p>
                </div>
            </a>

            <a href="<?= base_url('sfurgent/') ?>">
                <div class="menu-card">
                    <i class="fas fa-bolt"></i>
                    <p>STATE FUND - URGENT</p>
                </div>
            </a>

            <a href="<?= base_url('dashboard/rpt_general') ?>">
                <div class="menu-card">
                    <i class="fas fa-chart-pie"></i>
                    <p>REPORT OVERVIEW</p>
                </div>
            </a>

            <a href="<?= base_url('roads/overview') ?>">
                <div class="menu-card">
                    <i class="fas fa-road"></i>
                    <p>PATHASHREE-IV</p>
                </div>
            </a>

            <a href="<?= base_url('um') ?>">
                <div class="menu-card">
                    <i class="fas fa-users-cog"></i>
                    <p>User Management</p>
                </div>
            </a>

            <a href="<?= base_url('proposal') ?>">
                <div class="menu-card">
                    <i class="fas fa-folder-open"></i>
                    <p>Scheme Bank</p>
                </div>
            </a>

            <a href="<?= base_url('log/view') ?>">
                <div class="menu-card">
                    <i class="fas fa-headset"></i>
                    <p>Call Log / Support</p>
                </div>
            </a>
        </div>
    </div> -->

    <!-- Restricted Area -->
    <!-- <div class="restricted mt-4">
        <h4>Restricted Area <span id="private"><i class="fas fa-chevron-down"></i></span></h4>
        <div id="show_hide" style="display:none;" class="mt-3">
            <div class="menu-container">
                <div id="engineer" class="menu-card">
                    <i class="fas fa-user-secret"></i>
                    <p>Engineer</p>
                </div>
            </div>
        </div>
    </div>
</div>  -->

<!-- <script>
    $(document).ready(function() {
        $('#private').on('click', function() {
            $('#show_hide').slideToggle(300);
            $(this).find('i').toggleClass('fa-chevron-down fa-chevron-up');
        });

        $('#engineer').on('click', function(e) {
            e.preventDefault();
            let pwd = prompt('Enter your password to access this module');
            if (pwd && pwd.length > 0) {
                $.ajax({
                    url: baseURL + '/common/is_authorised',
                    type: 'post',
                    data: { pwd: pwd },
                    dataType: 'json',
                    async: false
                }).done(function(data) {
                    if (data > 0) {
                        window.open(baseURL + '/engineer', '_self');
                    } else {
                        alert('You are not the authorised person to access this module!!!');
                    }
                });
            }
        });
    });
</script> -->
