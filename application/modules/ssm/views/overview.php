<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('primary', 'secondary', 'success', 'warning', 'danger');
$dashboard_count = json_decode($dashboard_count, true);
// $tender_and_wo_count = json_decode($tender_and_wo_count);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Overview</h2>
                    <h5 class="text-white op-7 mb-2">Current Project Status</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">Pathashree-III Scheme Summary</div>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #006400 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Approved Schemes (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['approved_scheme'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-secondary card-round" style="background-color: #008000 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Approved Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-success card-round" style="background-color: #38b000 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Sanctioned Amount (Cr.)</p>
                                                    <!-- <h4 class="card-title fw-bold"><?= round(($dashboard_count['sanctioned_amount'] / 10000000), 2) ?></h4> -->
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['sanctioned_amount']/10000000, 2 ?? 0) ?></h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-warning card-round" style="background-color: #00a6fb  !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Tender Invited&nbsp;&nbsp; (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['tender_invited'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #0582ca !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Tender Matured (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['tender_matured'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #006494 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Matured Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['lender_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #003554 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Matured Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['tender_amount']/10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #bb3e03 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['wo_no'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #ca6702 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['wo_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #ee9b00 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['wo_amount'] / 10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #7b2cbf !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Progress (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['ongoing'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #5a189a !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['ongoing_length'] ?? 0.00 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #3c096c !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['ongoing_amount'] / 10000000,2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #023e7d !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['completed'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #0353a4 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['completed_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #0466c8 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['completed_amount'] / 10000000,2) ?? 0 ?></h4>
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
<script src="<?= base_url('templates/js/dashboard.js') ?>"></script>