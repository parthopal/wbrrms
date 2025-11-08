<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back_to_atr()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11">
                                <h4><?= $title ?></h4>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open_multipart('ssm/qm_save_atr'); ?>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Upload ATR Copy *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2mb, Uploaded Format - .pdf ) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="userfile" data-default-file="" class="dropify" data-max-file-size="10000M" accept="application/pdf">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="<?= base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
    <script src="<?= base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
    <script src="<?= base_url('templates/js/ssm_qm.js') ?>"></script>