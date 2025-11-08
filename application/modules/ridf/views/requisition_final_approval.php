<?php
defined('BASEPATH') or exit('No direct script access allowed');

$work = json_decode($work);
$curr = json_decode($curr);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12 ">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-auto">
                                <a href="<?= base_url('ridf/requisition_approved') ?>">
                                    <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col text-center">
                                <h2 class="mb-0"><?= $title ?></h2>
                            </div>
                        </div>
                        <?php echo form_open_multipart('ridf/requisition_final_approval_save'); ?>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="card card-dark bg-secondary-gradient">
                                    <div class="card-body skew-shadow">
                                        <span id="info_1"><?= $work->agency . ' | ' . $work->district . ' | ' . $work->block . ' | ' . $work->category . ' | ' . $work->scheme_id ?></span>
                                        <h2 id="info_2" class="py-4 mb-0"><?= $work->name . ' ( ' . $work->length . ' ' . $work->unit . ' )' ?></h2>
                                        <div class="row">
                                            <div class="col-6 pr-0">
                                                <h3 id="info_3" class="fw-bold mb-1"><?= $curr->ref_no ?></h3>
                                                <div class="text-small text-uppercase fw-bold op-8">Ref No</div>
                                            </div>
                                            <div class="col-6 pr-0">
                                                <h3 id="info_5" class="fw-bold mb-1"><?= $curr->memo_no ?></h3>
                                                <div class="text-small text-uppercase fw-bold op-8">Date: <?= $curr->memo_date ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6 mx-auto">
                                        <div class="form-group">
                                            <label>Approved Document<br>
                                                <span style="font-size: 10px; color: red;">(Max 2MB, Formats: pdf)</span>
                                            </label>
                                            <input type="file" name="approved_doc" class="form-control dropify" data-max-file-size="2M" accept="application/pdf" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>UPLOAD</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>