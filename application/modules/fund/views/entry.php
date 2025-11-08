<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$from_agency = json_decode($from_agency);
$to_agency = json_decode($to_agency);
$against = json_decode($against);
$selected = json_decode($selected);
$visible = $selected->activity_id == 1 ? 'style="display: none"' : 'style="display: block"';
$block_visible = $selected->activity_id > 4 && $selected->activity_id < 7 ? 'style="display: block"' : 'style="display: none"';
$from_visible = $selected->activity_id > 4 && $selected->activity_id < 7 ? 'style="display: none"' : 'style="display: block"';
$to_visible = $selected->activity_id > 4 ? 'style="display: none"' : 'style="display: block"';
$required = $selected->activity_id == 1 ? '' : 'required';
$block_required = $selected->activity_id > 4 && $selected->activity_id < 7 ? 'required' : '';
$from_required = $selected->activity_id > 4 && $selected->activity_id < 7 ? '' : 'required';
$to_required = $selected->activity_id > 4 ? '' : 'required';
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
                        <?php echo form_open_multipart('fund/save'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Fund Type *</label>
                                            <div class="selectgroup selectgroup-pills">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="expenditure" value="schematic" class="selectgroup-input" <?= ($selected->expenditure == 'schematic' ? 'checked' : '') ?>>
                                                    <span class="selectgroup-button">Schematic</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="expenditure" value="contingency" class="selectgroup-input" <?= ($selected->expenditure == 'contingency' ? 'checked' : '') ?>>
                                                    <span class="selectgroup-button">Contingency</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Order No.*</label>
                                            <div class="input-group">
                                                <input type="text" id="order_no" name="order_no" class="form-control" placeholder="Order No." value="<?= $selected->order_no ?>" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Order Date *</label>
                                            <div class="input-group">
                                                <input type="text" id="order_date" name="order_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->order_date != '' ? date('d/m/Y', strtotime($selected->order_date)) : '' ?>" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6" <?= $from_visible ?>>
                                        <div class="form-group">
                                            <label>From *</label>
                                            <select id="from_agency_id" name="from_agency_id" class="form-control dropdown" data-live-search="true" <?= $from_required ?>>
                                                <?php
                                                $_selected = $selected->from_agency_id == '' ? 'selected' : '';
                                                echo '<option value="" ' . $_selected . '>--Select Agency--</option>';
                                                foreach ($from_agency as $row) {
                                                    $_selected = $selected->from_agency_id == $row->id || sizeof($from_agency) == 1 ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" <?= $to_visible ?>>
                                        <div class="form-group">
                                            <label>To *</label>
                                            <select id="to_agency_id" name="to_agency_id" class="form-control dropdown" data-live-search="true" <?= $to_required ?>>
                                                <?php
                                                $_selected = $selected->to_agency_id == '' ? 'selected' : '';
                                                echo '<option value="" ' . $_selected . '>--Select Agency--</option>';
                                                foreach ($to_agency as $row) {
                                                    $_selected = $selected->to_agency_id == $row->id || sizeof($to_agency) == 1 ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" <?= $visible ?>>
                                        <div class="form-group">
                                            <label>District *</label>
                                            <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" <?= $required ?>>
                                                <?php
                                                $_selected = $selected->district_id == '' ? 'selected' : '';
                                                echo '<option value="" ' . $_selected . '>--Select District--</option>';
                                                foreach ($district as $row) {
                                                    $_selected = $selected->district_id == $row->id ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6" <?= $block_visible ?>>
                                        <div class="form-group">
                                            <label>Block *</label>
                                            <select id="block_id" name="block_id" class="form-control dropdown" data-live-search="true" <?= $block_required ?>>
                                                <?php
                                                $_selected = $selected->block_id == '' ? 'selected' : '';
                                                echo '<option value="" ' . $_selected . '>--Select Block--</option>';
                                                foreach ($block as $row) {
                                                    $_selected = $selected->block_id == $row->id ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6" <?= $visible ?>>
                                        <div class="form-group">
                                            <label>Against Ref *</label>
                                            <select id="against_id" name="against_id" class="form-control dropdown" data-live-search="true"  <?= $required ?>>
                                                <?php
                                                $_selected = $selected->against_id == '' ? 'selected' : '';
                                                echo '<option value="0" ' . $_selected . '>--Select Against Ref--</option>';
                                                foreach ($against as $row) {
                                                    $_selected = $selected->against_id == $row->id ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->order_no . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Amount*</label>
                                            <div class="input-group">
                                                <input type="text" id="amount" name="amount" class="form-control" placeholder="Amount" value="<?= $selected->amount ?>" autocomplete="off" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="vhr"></div>
                            <div class="col-md-6 ml-2">
                                <div class="col-md-12" <?= $visible ?>>
                                    <div class="row">
                                        <div class="col-md-6" style="display: none">
                                            <div class="card card-stats card-secondary card-round">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-stats">
                                                            <div class="numbers">
                                                                <p class="card-category"><span id="against_order_no"><?= $selected->pending_amount ?></span></p>
                                                                <h4 class="card-title fw-bold"><span id="against_order_amount"><?= $selected->against_order_amount ?></span></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="card card-stats card-danger card-round">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-12 col-stats">
                                                            <div class="numbers">
                                                                <p class="card-category">Pending Amount</p>
                                                                <h4 class="card-title fw-bold"><span id="pending_amount"><?= $selected->pending_amount ?></span></h4>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Document<br>
                                            <span style="font-size: 10px; color: red;">( Maximum 2MB, Uploaded Format - .pdf ) </span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" name="userfile" data-default-file="" class="dropify" data-max-file-size="2M" accept="application/pdf">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remarks</label>
                                    <div class="input-group">
                                        <textarea id="remarks" name="remarks" class="form-control" placeholder="Remarks" autocomplete="off"><?= $selected->remarks ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $selected->id ?>">
                        <input type="hidden" name="category" value="<?= $selected->category ?>">
                        <input type="hidden" id="activity_id" name="activity_id" value="<?= $selected->activity_id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/fund.js') ?>"></script>