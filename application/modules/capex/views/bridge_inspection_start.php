<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$total_img = json_decode($total_img);
$details = $details == '' ? '' : json_decode($details);
$i = 0;
// print_r($total_img[0]->seq_id); exit;
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
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
                        <?php echo form_open_multipart('capex/bridge_inspection_save_start'); ?>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Length Of Bridge <span style="color: red; font-size: 20px">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="length" name="length" class="form-control number" value="<?= (isset($details[0]->length) ? $details[0]->length : '') ?>" step="1" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">meter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Width of Bridge <span style="color: red; font-size: 20px">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="width" name="width" class="form-control number" value="<?= (isset($details[0]->length) ? $details[0]->width : '') ?>" step="1" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">meter</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>HFL</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="hfl" name="hfl" class="form-control number" value="<?= $details[0]->hfl ?> " step="1">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>OFL</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="ofl" name="ofl" class="form-control number" value="<?= $details[0]->ofl ?> " step="1">

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type of Obstruction</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="obstruction" name="obstruction" class="form-control number" value="<?= $details[0]->obstruction ?> " step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Traffic Category</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="traffic_category" name="traffic_category" class="form-control number" value="<?= $details[0]->traffic_category ?> " step="1">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>LBL</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="lbl" name="lbl" class="form-control number" value="<?= $details[0]->lbl ?> " step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type of Foundation</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="fundation" name="fundation" class="form-control number" value="<?= $details[0]->fundation ?> " step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type of Proposed Bridge</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="proposed_bridge" name="proposed_bridge" class="form-control number" value="<?= $details[0]->proposed_bridge ?> " step="1">

                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type of Super Structure</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="super_structure" name="super_structure" class="form-control number" value="<?= $details[0]->super_structure ?> " step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Linear Waterway </label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="linar_waterway" name="linar_waterway" class="form-control number" value="<?= $details[0]->linear_waterway ?> " step="1">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Linear Waterway Provided</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="linear_water_provided" name="linear_water_provided" class="form-control number" value="<?= $details[0]->linear_water_provided ?> " step="1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div id="div_image" class="row">
                            <?php
                            $total = $total_img[0]->seq_id == 4 || $total_img[0]->seq_id == 0 ? '4' : '8';
                            for ($i = 0; $i < $total; $i++) {
                                echo '<div class="col-md-6"><div class="column">';
                                echo '<div class="form-group">';
                                echo '<label>Description <span style="color: red; font-size: 20px">*</span></label>';
                                echo '<textarea class="form-control" name="desc[' . $i . ']" id="desc_' . $i . '" rows="1" required>' . (isset($selected[$i]) ? $selected[$i]->description : '') . '</textarea>';
                                echo '</div>';
                                echo '<div class="form-group">';
                                echo '<label>Upload Image <span style="color: red; font-size: 20px">*</span> <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label>';
                                echo '<div class="multiselect_div">';
                                echo '<input type="file" name="image[' . $i . ']" data-default-file="' . (isset($selected[$i]) ? base_url($selected[$i]->image) : '') . '" class="dropify" data-max-file-size="2048K" accept="image/*"' . (isset($selected[$i]) ? '' : 'required') . '>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div></div>';
                            }
                            if ($total_img[0]->seq_id > 8) {
                                for ($i = 5; $i < $total_img[0]->seq_id; $i++) {
                                    echo '<div id="div_' . $i . '" class="col-md-6"><div class="column">';
                                    echo '<div class="form-group">';
                                    echo '<label>Description <span style="color: red; font-size: 20px">*</span></label>';
                                    echo '<textarea class="form-control" name="desc[' . $i . ']" id="desc_' . $i . '" rows="1" required>' . (isset($selected[$i]) ? $selected[$i]->description : '') . '</textarea>';
                                    echo '</div>';
                                    echo '<div class="form-group">';
                                    echo '<label>Upload Image <span style="color: red; font-size: 20px">*</span> <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label>';
                                    echo '<div class="multiselect_div">';
                                    echo '<input type="file" name="image[' . $i . ']" data-default-file="' . (isset($selected[$i]) ? base_url($selected[$i]->image) : '') . '" class="dropify" data-max-file-size="2048K" accept="image/*"' . (isset($selected[$i]) ? '' : 'required') . '>';
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
                    <input type="hidden" name="bridge_id" value="<?= $bridge_id ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var i = <?= $total_img[0]->seq_id > 4 ? $total_img[0]->seq_id : 4 ?>;

    function _back() {
        window.location.href = baseURL + '/capex/bridge_inspection';
    }
</script>
<script src="<?= base_url('templates/js/capex_qm.js') ?>"></script>