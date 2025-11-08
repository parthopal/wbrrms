<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$reqd1 = strlen($selected->image_side) > 0 ? '' : 'required';
$reqd2 = strlen($selected->image_alignment) > 0 ? '' : 'required';
$reqd3 = strlen($selected->image_a1) > 0 ? '' : 'required';
$reqd4 = strlen($selected->image_a2) > 0 ? '' : 'required';
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
                            <div class="row col-md-12">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <h4 class="ml-3 mt-2"><?= $subheading; ?></h4>
                            </div>
                        </div>
                        <?php echo form_open_multipart('bridge/upload'); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 style="color: darkblue; text-align: center;"><b><?= strtoupper($selected->name) ?></b></h3>
                                </div>
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                        <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="reqd">Side View<br>
                                            <span style="font-size: 10px; color: red;">( All span should be viewed ) </span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" id="image_side" name="image_side" data-default-file="<?php echo $selected ? base_url($selected->image_side) : ''; ?>" class="dropify" data-max-file-size="10M" accept="image/png, image/gif, image/jpeg" <?= $reqd1 ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="reqd">Along with Alignment<br>
                                            <span style="font-size: 10px; color: red;">( Along the alignment of bridge from approach ) </span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" id="image_alignment" name="image_alignment" data-default-file="<?php echo $selected ? base_url($selected->image_alignment) : ''; ?>" class="dropify" data-max-file-size="10M" accept="image/png, image/gif, image/jpeg"  <?= $reqd2 ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="reqd">A1 Side View<br>
                                            <span style="font-size: 10px; color: red;">( Approach road view on A1 side ) </span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" name="image_a1" data-default-file="<?php echo $selected ? base_url($selected->image_a1) : ''; ?>" class="dropify" data-max-file-size="10M" accept="image/png, image/gif, image/jpeg"  <?= $reqd3 ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="reqd">A2 Side View<br>
                                            <span style="font-size: 10px; color: red;">( Approach road view on A2 side ) </span></label>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" name="image_a2" data-default-file="<?php echo $selected ? base_url($selected->image_a2) : ''; ?>" class="dropify" data-max-file-size="10M" accept="image/png, image/gif, image/jpeg"  <?= $reqd4 ?>>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $selected ? $selected->id : ''; ?>">
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/bridge.js') ?>"></script>