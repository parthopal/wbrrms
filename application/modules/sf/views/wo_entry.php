<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = $selected = '' ? '' : json_decode($selected);
$disabled = $islocked > 0 ? 'disabled' : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div class="py-2 py-md-0">
                    <h2 class="text-white pb-2 fw-bold ml-3"><?= $heading; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row ml-1">
                            <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h2 class="card-title ml-3"><?= $subheading ?></h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('sf/wo_save'); ?>
                        <div class="col-md-12 text-center">
                            <strong class="text-primary"><?= $selected->name ?></strong>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Work Order No.*</label>
                                    <div class="input-group">
                                        <input type='text' id="wo_no" name="wo_no" class="form-control" placeholder="Work Order No. " value="<?= $selected->wo_no ?>" autocomplete="off" required <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>WO Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="wo_date" name="wo_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?php echo $selected ? $selected->wo_date : ''; ?>" <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Completion Date (as per wo) *</label>
                                    <div class="input-group">
                                        <input type="text" id="completion_date" name="completion_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->completion_date ?>" <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name of Contractor *</label>
                                    <input type='text' id="contractor" name="contractor" class="form-control" placeholder="Name of Contractor" value="<?= $selected->contractor ?>" autocomplete="off" required <?= $disabled ?>>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PAN no of Contractor *</label>
                                    <input type='text' id="pan_no" name="pan_no" class="form-control" placeholder="PAN no of Contractor" value="<?= $selected->pan_no ?>" autocomplete="off" required <?= $disabled ?>>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contract Rate *</label>
                                    <div class="input-group mb-3">
                                        <input type='number' id="rate" name="rate" class="form-control number" placeholder="Contract Rate" value="<?= $selected->rate ?>" autocomplete="off" step="0.01" required <?= $disabled ?>>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Awarded Cost *</label>
                                    <div class="input-group mb-3">
                                        <input type='number' id="awarded_cost" name="awarded_cost" class="form-control number" placeholder="Awarded Cost" value="<?= $selected->awarded_cost ?>" autocomplete="off" step="0.01" required <?= $disabled ?>>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>BAR CHART Given</label>
                                    <select id="barchart_given" name="barchart_given" class="form-control dropdown" data-live-search="true"  <?= $disabled ?>>
                                        <option value="">--Select Barchart Given--</option>
                                        <option value="0" <?= $selected->barchart_given == 0 ? 'selected' : '' ?>>No</option>
                                        <option value="1" <?= $selected->barchart_given == 1 ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Secuirity Deposit(S.D)</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="ps_cost" name="ps_cost" class="form-control" placeholder="Secuirity deposit" step="0.01" value="<?= $selected->ps_cost ?>" <?= $disabled ?>>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Additional P.S. (INR)</label>
                                    <div class="input-group mb-3">
                                        <input type='number' id="additional_ps_cost" name="additional_ps_cost" class="form-control number" placeholder="Additional P.S." step="0.01" value="<?= $selected->additional_ps_cost ?>" <?= $disabled ?>>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date of Lapse of Additional P.S.</label>
                                    <div class="input-group">
                                        <input type='text' id="lapse_date" name="lapse_date" class="form-control datepicker" placeholder="Lapse Date" value="<?= $selected->lapse_date ?>" <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Defect Liability Period(DLP)</label>
                                    <div class="input-group">
                                        <input type='text' id="dlp" name="dlp" class="form-control" placeholder="Defect Laibility Period" value="<?= $selected->dlp ?>" <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Period</label>
                                    <select id="dlp_period" name="dlp_period" class="form-control dropdown" data-live-search="true" <?= $disabled ?>>
                                        <option value="0">--Select Period--</option>
                                        <option value="1" <?= $selected->dlp_period == 1 ? 'selected' : '' ?>>Month</option>
                                        <option value="2" <?= $selected->dlp_period == 2 ? 'selected' : '' ?>>Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>CAR Insurance for DLP Submitted</label>
                                    <select id="dlp_submitted" name="dlp_submitted" class="form-control dropdown" data-live-search="true" <?= $disabled ?>>
                                        <option value="">--Select Car Insurance--</option>
                                        <option value="0" <?= $selected->dlp_submitted == 0 ? 'selected' : '' ?>>No</option>
                                        <option value="1" <?= $selected->dlp_submitted == 1 ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Upload Work Order Pdf *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 1000mb, Uploaded Format - .pdf ) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="userfile" data-default-file="../../<?php echo $selected ? $selected->document : ''; ?>" class="dropify" data-max-file-size="10000M" accept="application/pdf" <?= strlen($selected->document) > 0 ? '' : 'required'; ?> <?= $disabled ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label class="required-label">Assign Engineer Name *</label>
                                        <input type="text" placeholder="Assign Engineer Name" name="assigned_engineer" value="<?= $selected->assigned_engineer ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="required-label">Designation *</label>
                                        <input type="text" placeholder="Designation" name="designation" value="<?= $selected->designation ?>" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="required-label">Mobile Number *</label>
                                        <input type="text" placeholder="Mobile Number" name="mobile" value="<?= $selected->mobile ?>" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action">
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="<?= $selected->id ?>">
                    <input type="hidden" id="sf_id" name="sf_id" value="<?= $selected->sf_id ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<link rel="stylesheet" href="<?php echo base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
<script src="<?php echo base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/sf.js') ?>"></script>
