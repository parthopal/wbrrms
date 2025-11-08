<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
// var_dump($selected);exit;
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
                    <?php echo form_open('roads/tender_save'); ?>
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
                                                <div class="text-small text-uppercase fw-bold op-8">Reference No. : <?= $selected->ref_no ?></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fw-bold op-8">
                                    <?php
                                    if ($selected->tender_status == 0) {
                                        echo ' Tender Not Started ';
                                    }
                                    if ($selected->tender_status == 1) {
                                        echo ' Ongoing Tender ';
                                    }
                                    if ($selected->tender_status == 2) {
                                        echo ' Tender Completed ';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tender_number">NIT Number *</label>
                                    <div class="input-group">
                                        <input type='text' id="tender_number" name="tender_number" class="form-control" placeholder="Tender Number" value="<?= $selected->tender_number ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>NIT Publication Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="tender_publication_date" name="tender_publication_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->tender_publication_date == null ? '' : date_format(date_create($selected->tender_publication_date), "d/m/Y") ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>BID Closing Date </label>
                                    <div class="input-group">
                                        <input type="text" id="bid_closing_date" name="bid_closing_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->bid_closing_date == null ? '' : date_format(date_create($selected->bid_closing_date), "d/m/Y") ?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>BID Opening Date </label>
                                    <div class="input-group">
                                        <input type="text" id="bid_opeaning_date" name="bid_opeaning_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->bid_opeaning_date == null ? '' : date_format(date_create($selected->bid_opeaning_date), "d/m/Y") ?>" >
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3" id="tec_div" <?= !is_null($selected->bid_closing_date) ? '' : 'hidden' ?>>
                                <div class="form-group">
                                    <label>Technical Evaluation Completed </label>
                                    <select id="evaluation_status" name="evaluation_status" class="form-control dropdown" data-live-search="true" >
                                        <option value="">--Select Status--</option>
                                        <option value="0" <?= $selected->evaluation_status == '0' ? 'selected' : '' ?>>No</option>
                                        <option value="1" <?= $selected->evaluation_status == '1' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" id="fbo_div" <?= !is_null($selected->bid_closing_date) ? '' : 'hidden' ?>>
                                <div class="form-group">
                                    <label>Financial BID Opening Completed </label>
                                    <select id="bid_opening_status" name="bid_opening_status" class="form-control dropdown" data-live-search="true" <?= !is_null($selected->evaluation_status) && $selected->evaluation_status == 1 ? '' : 'disabled' ?> >
                                        <option value="">--Select Status--</option>
                                        <option value="0" <?= $selected->bid_opening_status == '0' ? 'selected' : '' ?>>No</option>
                                        <option value="1" <?= $selected->bid_opening_status == '1' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3" id="tm_div" <?= !is_null($selected->bid_closing_date) ? '' : 'hidden' ?>>
                                <div class="form-group">
                                    <label>Tender Matured </label>
                                    <select id="bid_matured_status" name="bid_matured_status" class="form-control dropdown" data-live-search="true" <?= !is_null($selected->bid_opening_status) && $selected->bid_opening_status == 1 ? '' : 'disabled' ?> >
                                        <option value="">--Select Status--</option>
                                        <option value="0" <?= $selected->bid_matured_status == '0' ? 'selected' : '' ?>>No</option>
                                        <option value="1" <?= $selected->bid_matured_status == '1' ? 'selected' : '' ?>>Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-action" id="retender">
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-warning ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>Retender</span>
                            </button>
                        </div>
                    </div>
                    <div class="card-action" id="save">
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $selected ? $selected->id : ''; ?>">
                    <input type="hidden" name="tender_status" value="<?php echo $selected ? $selected->tender_status : ''; ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#retender').hide();
        $('#bid_matured_status').change(function () {
            if ($(this).val() == 0) {
                $('#save').hide();
                $('#retender').show();
            } else {
                $('#save').show();
                $('#retender').hide();
            }
        });

    });
</script>

<script src="<?= base_url('templates/js/ssmtwo.js') ?>"></script>