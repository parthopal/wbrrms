<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
?>
<div class="container">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <a href="<?= base_url('scheme/tender/' . $sc) ?>">
                                    <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-7 text-center">
                                <h2><?= $title . ' <small><b>(CALL NO.-' . $selected->call_no . ')</b></small>' ?></h2>
                            </div>
                            <div class="col-md-4 text-right">
                                <button id="next_call" class="btn btn-warning btn-round mr-2">Next Call</button>
                                <button id="retender" class="btn btn-danger btn-round">Re-Tender</button>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('scheme/tender_save'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIT No.*</label>
                                    <div class="input-group">
                                        <input type='text' id="nit_no" name="nit_no" class="form-control" placeholder="NIT No." value="<?= $selected->nit_no ?>" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIT Publication Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="nit_date" name="nit_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->nit_date != '' ? date('d/m/Y', strtotime($selected->nit_date)) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Last Date of BID Submission *</label>
                                    <div class="input-group">
                                        <input type="text" id="bid_submission_date" name="bid_submission_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->bid_submission_date != '' ? date('d/m/Y', strtotime($selected->bid_submission_date)) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>BID Opening Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="bid_opening_date" name="bid_opening_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->bid_opening_date != '' ? date('d/m/Y', strtotime($selected->bid_opening_date)) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Technical Evaluation <span class="lblreqd"></span></label>
                                    <select id="technical_evaluation" name="technical_evaluation" class="form-control dropdown reqd" data-live-search="true">
                                        <option value="">--Select Option--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Dept. Tender Committee <span class="lblreqd"></span></label>
                                    <div class="input-group">
                                        <input type="text" id="tender_committee_date" name="tender_committee_date" class="form-control datepicker reqd" placeholder="DD/MM/YYYY" value="<?= $selected->tender_committee_date != '' ? date('d/m/Y', strtotime($selected->tender_committee_date)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Financial BID Opening Date <span class="lblreqd"></span></label>
                                    <div class="input-group">
                                        <input type="text" id="financial_bid_opening_date" name="financial_bid_opening_date" class="form-control datepicker reqd" placeholder="DD/MM/YYYY" value="<?= $selected->financial_bid_opening_date != '' ? date('d/m/Y', strtotime($selected->bid_opening_date)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Tender Matured <span class="lblreqd"></span></label>
                                    <select id="tender_matured" name="tender_matured" class="form-control dropdown reqd" data-live-search="true">
                                        <option value="">--Select Option--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>AOT Issue Date <span class="lblreqd"></span></label>
                                    <div class="input-group">
                                        <input type="text" id="aot_issue_date" name="aot_issue_date" class="form-control datepicker reqd" placeholder="DD/MM/YYYY" value="<?= $selected->aot_issue_date != '' ? date('d/m/Y', strtotime($selected->aot_issue_date)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>LOP Issue Date <span class="lblreqd"></span></label>
                                    <div class="input-group">
                                        <input type="text" id="lop_issue_date" name="lop_issue_date" class="form-control datepicker reqd" placeholder="DD/MM/YYYY" value="<?= $selected->lop_issue_date != '' ? date('d/m/Y', strtotime($selected->lop_issue_date)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Locked</label>
                                    <div class="input-group" style="width: 65px !important;">
                                        <input id="islocked" name="islocked" type="checkbox" data-toggle="toggle" data-onstyle="danger" data-style="btn-round" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="sc" name="sc" value="<?= $sc ?>">
                        <input type="hidden" id="call_no" name="call_no" value="<?= $selected->call_no ?>">
                        <input type="hidden" id="scheme_id" name="scheme_id" value="<?= $selected->scheme_id ?>">
                        <input type="hidden" id="id" name="id" value="<?= $selected->id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/scheme.js') ?>"></script>