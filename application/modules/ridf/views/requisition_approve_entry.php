<?php
defined('BASEPATH') or exit('No direct script access allowed');
$work = json_decode($work);
$curr = json_decode($curr);
// echo "<pre>";
// print_r($work);exit;
?>


<div class="container">
    <!-- Header -->
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading; ?></h5>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="<?= base_url('ridf/requisition_approved') ?>">
                                    <button type="button" class="btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-11 text-center">
                                <div class="col-md-12">
                                    <h2 class="mb-1"><?= $title ?></h2>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <?php echo form_open_multipart('ridf/requisition_approved_save'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card card-dark bg-secondary-gradient">
                                    <div class="card-body skew-shadow">
                                        <span id="info_1"><?= $work->agency . ' | ' . $work->district . ' | ' . $work->block . ' | ' . $work->category . ' | ' . $work->scheme_id ?></span>
                                        <h2 id="info_2" class="py-4 mb-0"><?= $work->name . ' ( ' . $work->length . ' ' . $work->unit . ' )' ?></h2>
                                        <div class="row">
                                            <div class="col-6 pr-0">
                                                <h3 id="info_3" class="fw-bold mb-1"><?= $curr->ref_no ?></h3>
                                                <div class="text-small text-uppercase fw-bold op-8">Ref No</div>
                                            </div>
                                            <div class="col-6 pr-0">
                                                <h3 id="info_5" class="fw-bold mb-1"><?= $curr->memo_no ?></h3>
                                                <div class="text-small text-uppercase fw-bold op-8">Date: <?= $curr->memo_date ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 mx-auto">
                                <div class="card card-dark bg-secondary-gradient shadow-lg rounded-3" style="height: 390px;" >
                                    <div class="card-body skew-shadow">
                                        <h1 class="text-white mb-3">Project Financials</h1>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="fw-bold text-light">Sanctioned Cost:</span>
                                            <span class="text-white">₹ <?= number_format($work->sanctioned_cost, 2) ?></span>

                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="fw-bold text-light">Awarded Cost:</span>
                                            <span class="text-white">₹ <?= number_format($work->awarded_cost, 2) ?></span>
                                        </div>
                                        <hr class="bg-warning">
                                        
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="fw-bold text-light">Approved Claimed Amount:</span>
                                            <span class="text-white">₹ <?= number_format($work->total_approved_claimed_amt, 2) ?></span>

                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="fw-bold text-light">Approved DPR Amount:</span>
                                            <span class="text-white">₹ <?= number_format($work->total_approved_dpr_amt, 2) ?></span>
                                        </div>
                                        <div class="mb-2 d-flex justify-content-between">
                                            <span class="fw-bold text-light">Approved Contigency Amount:</span>
                                            <span class="text-white">₹ <?= number_format($work->total_approved_contigency_amt, 2) ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8 mx-auto">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="reqd">Approved Date</label>
                                            <div class="input-group">
                                                <input type="text" id="approved_date" name="approved_date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="" required>
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="reqd">Claimed Amount</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rs</span>
                                                </div>
                                                <input type="text" id="approved_claimed_amt" name="approved_claimed_amt" class="form-control" value="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="reqd">Current Claimed Amount</label>
                                            <button type="button" id="copy_claim_amt_btn" class="btn btn-outline-primary btn-block" title="Copy Current Claimed Amount">
                                                <i class="fas fa-copy"></i> Use Claimed Rs <b><?= number_format($curr->claimed_amt ?? 0) ?></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="reqd">DPR Amount</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rs</span>
                                                </div>
                                                <input type="text" id="approved_dpr_amt" name="approved_dpr_amt" class="form-control" value="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="reqd">Current DPR Amount</label>
                                            <button type="button" id="copy_dpr_amt_btn" class="btn btn-outline-primary btn-block" title="Copy Current DPR Amount">
                                                <i class="fas fa-copy"></i> Use DPR Rs <b><?= number_format($curr->dpr_amt ?? 0) ?></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="reqd">Contingency Amount</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rs</span>
                                                </div>
                                                <input type="text" id="approved_contigency_amt" name="approved_contigency_amt" class="form-control" value="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label class="reqd">Current Contingency Amount</label>
                                            <button type="button" id="copy_contigency_amt_btn" class="btn btn-outline-primary btn-block" title="Copy Current Contingency Amount">
                                                <i class="fas fa-copy"></i> Use Contingency Rs <b><?= number_format($curr->contigency_amt ?? 0) ?></b>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>Final Approve</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Values from PHP
        const claimedAmt = <?= isset($curr->claimed_amt) ? $curr->claimed_amt : 0 ?>;
        const dprAmt = <?= isset($curr->dpr_amt) ? $curr->dpr_amt : 0 ?>;
        const contigencyAmt = <?= isset($curr->contigency_amt) ? $curr->contigency_amt : 0 ?>;

        // Buttons
        document.getElementById("copy_claim_amt_btn").addEventListener("click", function() {
            document.getElementById("approved_claimed_amt").value = claimedAmt;
        });

        document.getElementById("copy_dpr_amt_btn").addEventListener("click", function() {
            document.getElementById("approved_dpr_amt").value = dprAmt;
        });

        document.getElementById("copy_contigency_amt_btn").addEventListener("click", function() {
            document.getElementById("approved_contigency_amt").value = contigencyAmt;
        });
    });
</script>