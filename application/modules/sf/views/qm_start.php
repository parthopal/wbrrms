<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$i = 0;
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11">
                                <h4><?= $title ?></h4>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-3 text-right">
                                <button id="remove" class="btn btn-warning btn-round">Remove Last Image</button>
                            </div>
                            <div class="col-md-3">
                                <button id="add" class="btn btn-primary btn-round">Add More Images</button>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open_multipart('srrp/qm_save_start'); ?>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                        <div id="div_image" class="row">
                            <?php
                            for ($i = 0; $i < 5; $i++) {
                                echo '<div class="col-md-6"><div class="column">';
                                echo '<div class="form-group">';
                                echo '<label>Description *</label>';
                                echo '<textarea class="form-control" name="desc[' . $i . ']" id="desc_' . $i . '" rows="1" required>' . (isset($selected[$i]) ? $selected[$i]->description : '') . '</textarea>';
                                echo '</div>';
                                echo '<div class="form-group">';
                                echo '<label>Upload Image * <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label>';
                                echo '<div class="multiselect_div">';
                                echo '<input type="file" name="image[' . $i . ']" data-default-file="' . (isset($selected[$i]) ? base_url($selected[$i]->image) : '') . '" class="dropify" data-max-file-size="2048K" accept="image/*">';
                                echo '</div>';
                                echo '</div>';
                                echo '</div></div>';
                            }
                            if (sizeof($selected) > 5) {
                                for ($i = 5; $i < sizeof($selected); $i++) {
                                    echo '<div id="div_' . $i . '" class="col-md-6"><div class="column">';
                                    echo '<div class="form-group">';
                                    echo '<label>Description *</label>';
                                    echo '<textarea class="form-control" name="desc[' . $i . ']" id="desc_' . $i . '" rows="1" required>' . (isset($selected[$i]) ? $selected[$i]->description : '') . '</textarea>';
                                    echo '</div>';
                                    echo '<div class="form-group">';
                                    echo '<label>Upload Image * <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label>';
                                    echo '<div class="multiselect_div">';
                                    echo '<input type="file" name="image[' . $i . ']" data-default-file="' . (isset($selected[$i]) ? base_url($selected[$i]->image) : '') . '" class="dropify" data-max-file-size="2048K" accept="image/*">';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div></div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <input type="hidden" name="qm_id" value="<?= $qm_id ?>">
                    <input type="hidden" name="agency" value="<?= $agency ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var i = <?= sizeof($selected) > 5 ? sizeof($selected) : 5 ?>;
</script>
<script src="<?= base_url('templates/js/srrp_qm.js') ?>"></script>
