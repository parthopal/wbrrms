<?php
defined('BASEPATH') or exit('No direct script access allowed');

//$selected = json_decode($selected);
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
                            <h2 class="card-title ml-3"><?= $subheading; ?></h2>
                        </div>
                    </div>
                    <div class="col-md-12 text-center">
                        <div class="form-group" style="background-color: whitesmoke">
                            <h4><?= $title ?></h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('ridf/bridge_inspection_save_submit'); ?>
                        <hr>
                        <div class="row">
                            <div class="col">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Approach road obstruction <span style="color: red; font-size: 20px">*</span></label>
                                            <div class="input-group">
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label" for="s"><input class="form-check-input" type="radio" name="road_obstruction" id="1" value='1'>Clear Approach road</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <label class="form-check-label" for="u"><input class="form-check-input" type="radio" name="road_obstruction" id="2" value='2'>Obstructed Approach road</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Inspection Date <span style="color:red; font-size: 20px">*</span></label>
                                            <div class="input-group">
                                                <input type="text" id="inspection_date" name="inspection_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="surveyor_name">Remarks <span style="color: red; font-size: 20px">*</span></label>
                                            <div class="input-group">
                                                <textarea id="remarks" rows="5" name="remarks" class="form-control" required></textarea>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-------------------------------- 2nd DIV col ---------------------------------------------->
                            <div class="col">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Upload Document<br>
                                            <span style="font-size: 11px; color: red;">( Maximum 1MB, Uploaded Format - pdf ) </span></label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="userfile" data-default-file="" class="dropify" data-max-file-size="10000M" data-height="200" accept="application/pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        &nbsp;
                        <div class="card-action">
                            <div class="col-md-12 text-right">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="inspection_id" value="<?= $inspection_id ?>">
                        <input type="hidden" name="agency" value="<?= $agency ?>">
                        <input type="hidden" name="bridge_id" value="<?= $bridge_id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/ridf_qm.js') ?>"></script>