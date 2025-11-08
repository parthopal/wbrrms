<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('card-primary', 'card-secondary', 'card-success', 'card-warning', 'card-danger');
$overview = json_decode($overview);
// var_dump($overview);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
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
                            <div class="card-title"><?= $title ?></div>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <?php
                        foreach ($overview as $row) {
                            ?>
                            <h4><?= $row->category ?></h4>
                            <hr>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="card card-stats card-primary card-round" style="background-color: #13a13a !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Total</p>
                                                        <h4 class="card-title fw-bold"><?= $row->total ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card card-stats card-secondary card-round" style="background-color: #205295 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Tender</p>
                                                        <h4 class="card-title fw-bold"><?= $row->tender ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card card-stats card-success card-round" style="background-color: #0194a0 !important;">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">Work Order</p>
                                                        <h4 class="card-title fw-bold"><?= $row->wo ?></h4>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/ridf.js') ?>"></script>