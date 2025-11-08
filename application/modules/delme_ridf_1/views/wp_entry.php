<?php
defined('BASEPATH') or exit('No direct script access allowed');

$wp = $wp == '' ? '' : json_decode($wp);
$role_id = json_decode($role_id, true);
// echo '<pre>';
// print_r($wp);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0"></div>
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
                            <h2 class="card-title ml-3"><?= $wp->name ?></h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('ridf/wp_save'); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Visiting Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="wp_date" name="wp_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= date('d/m/Y'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4" style="display : <?= $wp->wo_start_date != null ? 'none' : 'block' ?>">
                                <div class="form-group">
                                    <label>Work Start Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="wp_start_date" name="wp_start_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $wp->wo_start_date != null ? date('d/m/Y', strtotime($wp->wo_start_date)) : date('d/m/Y'); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Progress *</label>
                                    <div class="input-group mb-3">
                                       <?php $wp_min = $role_id == 21 ? '' : $wp->physical_progress ; ?>
                                        <input type='number' id="wp_progress" name="wp_progress" class="form-control number" placeholder="progress percentage" value="<?= $wp->physical_progress ?>" min="<?= $wp->physical_progress ?>" step="1" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Remarks *</label>
                                    <textarea id="remarks" name="remarks" class="form-control" placeholder="Remarks" rows="5" required></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Starting Point *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2mb, Uploaded Format - jpg, jpeg, png) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="image1" data-default-file="" class="dropify" data-max-file-size="2M" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Middle Point *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2mb, Uploaded Format - jpg, jpeg, png ) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="image2" data-default-file="" class="dropify" data-max-file-size="2M" accept="image/*" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Point *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2mb, Uploaded Format - jpg, jpeg, png ) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="image3" data-default-file="" class="dropify" data-max-file-size="2M" accept="image/*" required>
                                        </div>
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
                    <input type="hidden" id="id" name="id" value="<?= $wp->id ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>