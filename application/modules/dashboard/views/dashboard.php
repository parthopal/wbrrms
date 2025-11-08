<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('primary', 'secondary', 'success', 'warning', 'danger');
$funding = json_decode($funding);
$status = json_decode($status);
$ridf_count = json_decode($ridf_count);
$tender_and_wo_count = json_decode($tender_and_wo_count);
// var_dump($tender_and_wo_count);exit;
$status_piechart = array();
if($status != null && sizeof($status) > 0) {
    foreach ($status as $row) {
        $status_piechart[] = $row->cnt;
    }
}

$dashboard_count = json_decode($dashboard_count, true);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Dashboard</h2>
                    <h5 class="text-white op-7 mb-2">Current Project Status</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- <div class="page-inner mt--5">
        <div class="col-md-12">
            <div class="card full-height">
                <div class="card-header">
                    <div class="card-head-row"> -->
                        <!-- <div class="card-title">Head wise Summary</div>
                    </div>
                    <a href="project/index/" target="_blank">
                </div>  -->
                <!-- <div class="card-body"> -->
                    <!-- <div class="col-md-3">
                        <div class="card card-stats card-success card-round" style="background-color: #205295 !important;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-stats">
                                        <div class="numbers">
                                            <p class="card-category">Total User</p>
                                            <h4 class="card-title fw-bold">2656</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="row">
                        <?php
                        $i = 0;
                        foreach ($funding as $row) {
                            ?>
                            <div class="col-md-4">
                                <div class="card card-stats card-<?= $color[$i] ?> card-round">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-5">
                                                <div class="icon-big text-center">
                                                    <i class="fas fa-map-marked"></i>
                                                </div>
                                            </div>
                                            <div class="col-7 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category"><?= $row->category ?></p>
                                                    <h4 class="card-title fw-bold"><?= $row->cnt ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $i++;
                            $i = $i > 4 ? 0 : $i;
                        }
                        ?>
                    </div> -->
                <!-- </div>
            </div>
        </div>
    </div> -->
    <div class="page-inner mt--5">
        <?php
        if ($role_id == 1 || $role_id == 2 || $role_id == 12) {
            ?>
            <div class="row ">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-head-row">
                                <div class="card-title">Pathashree Scheme Summary</div>
                            </div>
                        </div>
                        <div class="card-body mt-3">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #13a13a !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-map-marked"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Approved Schemes</p>
                                                        <!-- <h4 class="card-title fw-bold">468</h4> -->
                                                        <h4 class="card-title fw-bold"><?= $dashboard_count['approved_scheme'] ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-secondary card-round" style="background-color: #205295 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-map-marked"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Approved Length (KM)</p>
                                                        <h4 class="card-title fw-bold"><?= $dashboard_count['length'] ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-success card-round" style="background-color: #0194a0 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-map-marked"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Sanctioned Amount (Cr.)</p>
                                                        <h4 class="card-title fw-bold"><?= round(($dashboard_count['sanctioned_amount'] / 10000000), 2) ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-warning card-round" style="background-color: #6861ce !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-5">
                                                    <div class="icon-big text-center">
                                                        <i class="fas fa-map-marked"></i>
                                                    </div>
                                                </div>
                                                <div class="col-7 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Tender Invited</p>
                                                        <h4 class="card-title fw-bold"><?= $dashboard_count['tender_invited'] ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #4834d4 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Tender Matured</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->tender_matured_count->tender_matured ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #34ace0 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Tender Matured Length</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->tender_matured_count->tender_matured_length ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #706fd3 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Tender Matured Amount</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->tender_matured_count->tender_amount ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #abbe2a !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Order</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_count->work_order ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-primary card-round" style="background-color: #cb4c7a !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Order Length</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_count->work_order_length ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-warning card-round" style="background-color: #36ba5c !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Order Amount</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_count->sanctioned_amount ?? 0.00 ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-warning card-round" style="background-color: #FFAD46 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Progress</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_progress_count->work_progress ?? 0 ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-warning card-round" style="background-color: #3aaab3 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Progress Length</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_progress_count->work_progress_length ?? 0.00 ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="card card-stats card-warning card-round" style="background-color: #4464b5 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Progress Amount</p>
                                                        <h4 class="card-title fw-bold"><?= $tender_and_wo_count->work_order_progress_count->sanctioned_amount ?? 0.00 ?></h4>
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
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">RIDF Scheme Summary</h4>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-pills nav-secondary nav-pills-no-bd" id="pills-tab-without-border" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd" role="tab" aria-controls="pills-home-nobd" aria-selected="true">RIDFXXVII</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd" role="tab" aria-controls="pills-profile-nobd" aria-selected="false">RIDFXXVIII</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-contact-tab-nobd" data-toggle="pill" href="#pills-contact-nobd" role="tab" aria-controls="pills-contact-nobd" aria-selected="false">PMGSY Post 5 Years</a>
                            </li>
                        </ul>
                        <div class="tab-content mt-5 mb-3" id="pills-without-border-tabContent">
                            <?php
                            $ccurrent_category_ids = array(1, 6, 3);
                            if($ridf_count != null) {
                                    foreach ($ridf_count as $row) {
                                        if (!in_array($row->category_id, $ccurrent_category_ids)) {
                                            continue;
                                        }
                                        if ($row->category_id == 1) {
                                            echo '<div class="tab-pane fade show active" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
                                            ';
                                        }
                                        if ($row->category_id == 6) {
                                            echo '<div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
                                            ';
                                        }
                                        if ($row->category_id == 3) {
                                            echo '<div class="tab-pane fade" id="pills-contact-nobd" role="tabpanel" aria-labelledby="pills-contact-tab-nobd">
                                            ';
                                        }
                                        ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="card card-stats card-success card-round" style="background-color: #13a13a !important;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 col-stats">
                                                                <div class="numbers">
                                                                    <p class="card-category">Approved Schemes</p>
                                                                    <h4 class="card-title fw-bold"><?= $row->scheme ?></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card card-stats card-success card-round" style="background-color: #205295 !important;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 col-stats">
                                                                <div class="numbers">
                                                                    <p class="card-category">Approved Length (KM)</p>
                                                                    <h4 class="card-title fw-bold"><?= $row->length ?></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="card card-stats card-success card-round" style="background-color: #0194a0 !important;">
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-12 col-stats">
                                                                <div class="numbers">
                                                                    <p class="card-category">Sanctioned Amount (Cr.)</p>
                                                                    <h4 class="card-title fw-bold"><?= round(($row->amount / 10000000), 2) ?></h4>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt--2">
        <div class="col-md-12">
            <div class="card full-height">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">Project Status</div>
                    </div>
                    <!-- <a href="dashboard/project_entry" target="_blank"> -->
                    <a href="project/index/" target="_blank">
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php
                        if($status != null) {
                            foreach ($status as $row) {
                                $tag = $row->iscompleted == 0 ? 'undertender' : ($row->iscompleted == 1 ? 'inprogress' : 'completed');
                                ?>

                                <div class="col-md-4">
                                    <a href="project/index/<?= $tag ?>">
                                        <div class="card card-stats card-<?= $color[$i] ?> card-round">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-5">
                                                        <div class="icon-big text-center">
                                                            <i class="fas fa-map-marked"></i>
                                                        </div>
                                                    </div>
                                                    <div class="col-7 col-stats">
                                                        <div class="numbers">
                                                            <p class="card-category"><?= ($row->iscompleted == 0 ? 'Under Tender' : ($row->iscompleted == 1 ? 'In Progress' : 'Completed')) ?></p>
                                                            <h4 class="card-title fw-bold"><?= $row->cnt ?></h4>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <?php
                                $i++;
                                $i = $i > 4 ? 0 : $i;
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt--2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Overall Project Status(%)</div>
                </div>
                <a href="dashboard/districtwise_piechart" target="_blank">
                    <!-- <a href="report/monitoring" target="_blank"> -->
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="status_piechart" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/dashboard.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/chart.js/chart.min.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/chart-circle/circles.min.js') ?>"></script>

<script>
    var statusPieChart = document.getElementById('status_piechart').getContext('2d');
    var myPieChart = new Chart(statusPieChart, {
        type: 'pie',
        data: {
            datasets: [{
                    data: <?= json_encode($status_piechart); ?>,
                    backgroundColor: ["#f3545d", "#1d7af3", "#00FF00"],
                    borderWidth: 0
                }],
            labels: ['Under Tender', 'In Progress', 'Completed']
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {
                    fontColor: 'rgb(154, 154, 154)',
                    fontSize: 11,
                    usePointStyle: true,
                    padding: 20
                }
            },
            pieceLabel: {
                render: 'percentage',
                fontColor: 'white',
                fontSize: 14,
            },
            tooltips: false,
            layout: {
                padding: {
                    left: 20,
                    right: 20,
                    top: 20,
                    bottom: 20
                }
            }
        }
    });
</script>