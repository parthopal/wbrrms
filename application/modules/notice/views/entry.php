<?php
defined('BASEPATH') or exit('No direct script access allowed');
$selected = $selected = '' ? '' : json_decode($selected);
//var_dump($selected);exit;
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
                    <!-- <div class="card-header">
                        <div class="row ml-1">
                            <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h2 class="card-title ml-3"><?= $subheading ?></h2>
                        </div>
                    </div> -->
                    <div class="card-header">
                        <div class="row ml-1">
                            <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                            <h2 class="card-title ml-3">Add notice</h2>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('notice/save'); ?>
                        <!-- <div class="col-md-12 text-center">
                            <strong class="text-primary"><?= $selected->name ?></strong>
                        </div>
                        <hr> -->


                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Brief Description *</label>
                                    <input type='text' id="name" name="name" class="form-control" placeholder="Name " value="<?= $selected->name ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>memo no*</label>
                                    <input type='text' id="memo_no" name="memo_no" class="form-control" placeholder="memo no " value="<?= $selected->memo_no ?>" autocomplete="off" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="date" name="date" class="form-control datepicker" placeholder="Date" value="<?= $selected->date ?>" autocomplete="off" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Upload Pdf<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 1000mb, Uploaded Format - .pdf ) </span></label>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="multiselect_div">
                                            <input type="file" name="userfile" data-default-file="../../<?php echo $selected ? $selected->document : ''; ?>" class="dropify" data-max-file-size="2M" accept="application/pdf">
                                        </div>
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
                <input type="hidden" id="id" name="id" value="<?= $selected->id ?>">
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
<script src="<?php echo base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/notice.js') ?>"></script>