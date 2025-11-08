<?php
defined('BASEPATH') or exit('No direct script access allowed');

$category = json_decode($category);
$type = json_decode($type);
$agency = json_decode($agency);
$road = json_decode($road);
$work = json_decode($work);
$district = json_decode($district);
$block = json_decode($block);
$mp = json_decode($mp);
$mla = json_decode($mla);
$selected = json_decode($selected);
$block_id = $selected->block_id != null ? explode(',', $selected->block_id) : '';
$mp_id = $selected->mp_id != null ? explode(',', $selected->mp_id) : '';
$mla_id = $selected->mla_id != null ? explode(',', $selected->mla_id) : '';
$disabled = $role_id > 3 ? 'disabled' : '';
$road_display = $selected->type_id == 1 ? 'style="display: block;"' : 'style="display: none;"';
$note_display = $selected->type_id > 1 ? 'style="display: block;"' : 'style="display: none;"';
$note_required = $selected->type_id > 1 ? 'required' : '';
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
                                <a href="<?= base_url('scheme/view/' . $sc) ?>">
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
                        <?php echo form_open('scheme/save'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Funding By *</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
                                        <?php
                                        $_selected = $selected->category_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Funding--</option>';
                                        foreach ($category as $row) {
                                            $_selected = $selected->category_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Agency *</label>
                                    <select id="agency_id" name="agency_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
                                        <?php
                                        $_selected = $selected->agency_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Agency--</option>';
                                        foreach ($agency as $row) {
                                            $_selected = $selected->agency_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Scheme ID (PIC) *</label>
                                    <div class="input-group">
                                        <input type="text" id="scheme_id" name="scheme_id" class="form-control" placeholder="Scheme ID" value="<?= $selected->scheme_id ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Name of the Work *</label>
                                    <div class="input-group">
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Work Name" value="<?= $selected->name ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project Type *</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
                                        <?php
                                        $_selected = $selected->type_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Project Type--</option>';
                                        foreach ($type as $row) {
                                            $_selected = $selected->type_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rd" <?= $road_display ?>>
                                <div class="form-group">
                                    <label>Road Type</label>
                                    <select id="road_type_id" name="road_type_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?>>
                                        <?php
                                        $_selected = $selected->road_type_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Road Type--</option>';
                                        foreach ($road as $row) {
                                            $_selected = $selected->road_type_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 rd" <?= $road_display ?>>
                                <div class="form-group">
                                    <label>Work Type</label>
                                    <select id="work_type_id" name="work_type_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?>>
                                        <?php
                                        $_selected = $selected->work_type_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select Work Type--</option>';
                                        foreach ($work as $row) {
                                            $_selected = $selected->work_type_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9 nd" <?= $note_display ?>>
                                <div class="form-group">
                                    <label>Note *</label>
                                    <div class="input-group">
                                        <input type="text" id="note" name="note" class="form-control" placeholder="Note" value="<?= $selected->note ?>" autocomplete="off" <?= $disabled ?> <?= $note_required ?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Proposed Length / Span *</label>
                                    <div class="input-group">
                                        <input type="text" id="length" name="length" class="form-control" value="<?= $selected->length ?>" autocomplete="off" <?= $disabled ?> required>
                                        <div class="input-group-append">
                                            <select id="unit" name="unit" class="form-control dropdown" data-live-search="true" required>
                                                <option value="">--Select Unit--</option>
                                                <option value="km" <?= $selected->unit == 'km' ? 'selected' : '' ?>>KM</option>
                                                <option value="m" <?= $selected->unit == 'm' ? 'selected' : '' ?>>Meter</option>
                                                <option value="sqft" <?= $selected->unit == 'sqft' ? 'selected' : '' ?>>Square Feet</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Sanctioned Cost *</label>
                                    <div class="input-group">
                                        <input type="text" id="sanctioned_cost" name="sanctioned_cost" class="form-control" placeholder="Sanctioned Cost" value="<?= $selected->sanctioned_cost ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Admin No.*</label>
                                    <div class="input-group">
                                        <input type="text" id="admin_no" name="admin_no" class="form-control number" placeholder="Admin No." value="<?= $selected->admin_no ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Admin Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="admin_date" name="admin_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->admin_date != '' ? $selected->admin_date : '' ?>" autocomplete="off" <?= $disabled ?> required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District *</label>
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
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>Block *</label>
                                    <select id="block_id" name="block_id[]" class="form-control dropdown" data-live-search="true" multiple="multiple" <?= $disabled ?> required>
                                        <?php
                                        foreach ($block as $row) {
                                            $_selected = in_array($row->id, $block_id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Lok Sabha Constituency</label>
                                    <select id="mp_id" name="mp_id[]" class="form-control dropdown" data-live-search="true" multiple="multiple">
                                        <?php
                                        foreach ($mp as $row) {
                                            $_selected = in_array($row->id, $mp_id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>Assembly Constituency</label>
                                    <select id="mla_id" name="mla_id[]" class="form-control dropdown" data-live-search="true" multiple="multiple">
                                        <?php
                                        foreach ($mla as $row) {
                                            $_selected = in_array($row->id, $mla_id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
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
<script src="<?= base_url('templates/js/scheme.js') ?>"></script>