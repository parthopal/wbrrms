<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
// print_r($selected);exit;
$disabled = $role_id > 3 ? 'disabled' : '';
$category = json_decode($category);
if ($selected->block_id == 0) {
    $required = 'required';
}
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
                                <a href="<?= base_url('ridf/bridge_master') ?>">
                                    <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-11 text-center">
                                <h2><?= $title ?></h2>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('ridf/bridge_save'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name of the Bridge<span style="color: red; font-size: 18px">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="bridge_name" name="bridge_name" class="form-control" placeholder="Bridge Entry" value="<?= $selected->name ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District <span style="color: red; font-size: 18px">*</span></label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Block <span style="color: red; font-size: 18px">*</span></label>
                                    <select id="block_id" name="block_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> <?= $required ?>>
                                        <option value="">--Select Block--</option>
                                        <?php
                                        $_selected = $selected->block_id == '' ? 'selected' : '';
                                        foreach ($block as $row) {
                                            $_selected = ($selected->block_id > 0 && $selected->block_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Funding By <span style="color: red; font-size: 18px">*</span></label>
                                    <select id="funding_id" name="funding_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
                                        <?php
                                        $_selected = $selected->category_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Funding--</option>';
                                        foreach ($category as $row) {
                                            $_selected = $selected->funding_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Agency <span style="color: red; font-size: 18px">*</span></label>
                                    <input type="text" id="agency" name="agency" class="form-control" placeholder="Name of Agency" value="<?= $selected->agency ?>" autocomplete="off" <?= $disabled ?> required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>AOT Date </label>
                                    <div class="input-group">
                                        <input type="text" id="aot_date" name="aot_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->aot_date != '' ? $selected->aot_date : '' ?>" autocomplete="off" <?= $disabled ?>>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>WO Date<span style="color: red; font-size: 18px">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="wo_date" name="wo_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->wo_date != '' ? $selected->wo_date : '' ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Complete Date <span style="color: red; font-size: 18px">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="complete_date" name="complete_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->complete_date != '' ? $selected->complete_date : '' ?>" autocomplete="off" <?= $disabled ?> required>
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
                        <input type="hidden" name="id" value="<?= $selected->id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/ridf.js') ?>"></script>