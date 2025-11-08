<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
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
                    <?php echo form_open('ssm/not_traceable_save'); ?>
                    <div class="card-body">

                        <div class="row">
                            <div id="div_tender" class="col-md-12">
                                <div class="card card-dark bg-secondary-gradient pointer">
                                    <div class="card-body skew-shadow">
                                        <p><span style="color: yellow;"><?= $selected->district . ' / ' . $selected->block . ' / ' . $selected->gp . ' / ' . strtoupper($selected->village) ?></span></p>
                                        <h4 id="tender_ref" class="py-4 mb-0"><?= $selected->name ?></h4>
                                        <i style="color: yellow;">Agency: <span><?= $selected->agency ?></span></i>
                                        <div class="row">
                                            <div class="col-10 pl-0 text-right">
                                                <h3 id="tender_start" class="fw-bold mb-1"></h3>
                                                <div class="text-small text-uppercase fw-bold op-8"><?= $selected->road_type ?></div>
                                            </div>
                                            <div class="col-2 pl-0 text-right">
                                                <h3 id="tender_end" class="fw-bold mb-1"></h3>
                                                <div class="text-small text-uppercase fw-bold op-8"><?= $selected->work_type ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Not Implemented Reason *</label>
                                    <select id="status" name="status" class="form-control dropdown" data-live-search="true" required>
                                        <option value="">--Select Not Implemented Reason--</option>
                                        <option value="0" <?= $selected->status == '0' ? 'selected' : '' ?>>Not Traceable</option>
                                        <option value="1" <?= $selected->status == '1' ? 'selected' : '' ?>>Taken up with Other Scheme/Fund </option>
                                        <option value="2" <?= $selected->status == '2' ? 'selected' : '' ?>>Others</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="surveyor_name">Remarks *</label>
                                    <div class="input-group">
                                        <textarea id="remarks" rows="8" name="remarks" class="form-control" required></textarea>

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
                    <input type="hidden" name="id" value="<?php echo $selected ? $selected->id : ''; ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/ssm.js') ?>"></script>
