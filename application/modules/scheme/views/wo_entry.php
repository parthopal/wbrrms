<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$required = $selected->wo_doc != '' ? '' : 'required';
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11 text-center">
                                <h2><?= $title ?></h2>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open_multipart('scheme/wo_save'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Work Order Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="wo_date" name="wo_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->wo_date != '' ? date('d/m/Y', strtotime($selected->wo_date)) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Work Order No.*</label>
                                    <div class="input-group">
                                        <input type="text" id="wo_no" name="wo_no" class="form-control" placeholder="Work Order No." value="<?= $selected->wo_no ?>" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Completion Date (as per wo) *</label>
                                    <div class="input-group">
                                        <input type="text" id="completion_date" name="completion_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->completion_date != '' ? date('d/m/Y', strtotime($selected->completion_date)) : '' ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bar Chart Given</label>
                                    <select id="barchart_given" name="barchart_given" class="form-control dropdown" data-live-search="true">
                                        <option value="">--Select Option--</option>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label>Name of Contractor *</label>
                                    <input type="text" id="contractor_name" name="contractor_name" class="form-control" placeholder="Name of Contractor" value="<?= $selected->contractor_name ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>PAN No. *</label>
                                    <input type="text" id="contractor_pan" name="contractor_pan" class="form-control" placeholder="PAN of Contractor" value="<?= $selected->contractor_pan ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Contract Rate *</label>
                                    <div class="input-group">
                                        <input type="text" id="contract_rate" name="contract_rate" class="form-control number" placeholder="Contract Rate" value="<?= $selected->contract_rate ?>" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Awarded Cost *</label>
                                    <div class="input-group">
                                        <input type="text" id="awarded_cost" name="awarded_cost" class="form-control" placeholder="Awarded Cost" value="<?= $selected->awarded_cost ?>" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Security Deposit Cost (INR)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="security_deposite_cost" name="security_deposite_cost" class="form-control" placeholder="Secuirity deposit" value="<?= $selected->security_deposite_cost ?>" autocomplete="off">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Additional P.S. (INR)</label>
                                    <div class="input-group mb-3">
                                        <input type="text" id="additional_ps_cost" name="additional_ps_cost" class="form-control number" placeholder="Additional P.S." value="<?= $selected->additional_ps_cost ?>">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">INR</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Date of Lapse of Additional P.S.</label>
                                    <div class="input-group">
                                        <input type="text" id="additional_ps_lapse_date" name="additional_ps_lapse_date" class="form-control datepicker" placeholder="Lapse Date" value="<?= $selected->additional_ps_lapse_date != '' ? date('d/m/Y', strtotime($selected->additional_ps_lapse_date)) : '' ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Defect Liability Period (DLP)</label>
                                    <div class="input-group">
                                        <input type="text" id="dlp" name="dlp" class="form-control" autocomplete="off" value="<?= $selected->dlp ?>">
                                        <div class="input-group-append">
                                            <select id="dlp_period" name="dlp_period" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Period--</option>
                                                <option value="month">Month</option>
                                                <option value="year">Year</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>CAR Insurance for DLP Submitted</label>
                                    <div class="input-group">
                                        <select id="car_insurance" name="car_insurance" class="form-control dropdown" data-live-search="true">
                                            <option value="">--Select Option--</option>
                                            <option value="0">No</option>
                                            <option value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Locked WO</label>
                                    <div class="input-group" style="width: 75px !important;">
                                        <input id="islocked" name="islocked" type="checkbox" data-toggle="toggle" data-onstyle="danger" data-style="btn-round" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <div class="col-md-9">
                                        <label>Work Order *<br>
                                            <span style="font-size: 10px; color: red;">( Maximum 2MB, Uploaded Format - .pdf ) </span>
                                        </label>
                                    </div>
                                    <div class="col-md-3">
                                        <label>
                                            <?php
                                            if ($selected->wo_doc != '') {
                                                echo '<a href="' . base_url($selected->wo_doc) . '" target="_blank"><i class="fas fa-file-pdf fa-2x pointer"></i></a>';
                                            }
                                            ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="wofile" data-default-file="" class="dropify" data-max-file-size="2M" accept="application/pdf" <?= $required ?>>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="col-md-9">
                                    <label>Bar Chart<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2MB, Uploaded Format - .pdf ) </span>
                                    </label>
                                </div>
                                <div class="col-md-3">
                                    <label>
                                        <?php
                                        if ($selected->barchart_doc != '') {
                                            echo '<a href="' . base_url($selected->barchart_doc) . '" target="_blank"><i class="fas fa-file-pdf fa-2x pointer"></i></a>';
                                        }
                                        ?>

                                    </label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="barfile" data-default-file="" class="dropify" data-max-file-size="2M" accept="application/pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="sc" name="sc" value="<?= $sc ?>">
                    <input type="hidden" id="scheme_id" name="scheme_id" value="<?= $selected->scheme_id ?>">
                    <input type="hidden" id="id" name="id" value="<?= $selected->id ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/scheme.js') ?>"></script>
