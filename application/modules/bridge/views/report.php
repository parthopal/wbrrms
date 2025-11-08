<?php
defined('BASEPATH') or exit('No direct script access allowed');
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $title ?></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <a href="<?= base_url('bridge/rpt_state_summary') ?>">
                                    <div class="card card-stats card-success card-round">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12 col-stats">
                                                    <div class="numbers">
                                                        <p class="card-category">State Summary Report</p>
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
</div>