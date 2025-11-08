<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$progress = $progress == '' ? '' : json_decode($progress);
$total_img = json_decode($total_img);
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="qm_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11">
                                <h4><?= $title ?></h4>
                            </div>
                            <div class="col-md-6"></div>
                            <div class="col-md-3 text-right">
                                <button id="qm_remove" class="btn btn-warning btn-round">Remove Last Image</button>
                            </div>
                            <div class="col-md-3">
                                <button id="qm_add" class="btn btn-primary btn-round">Add More Images</button>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open_multipart('capex/bridge_qm_save_start'); ?>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Physical Progress <span style="color: red; font-size: 20px">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type='number' id="physical_progress" name="physical_progress" class="form-control number" placeholder="progress percentage" value="<?= $progress[0]->physical_progress ?>" min="<?= $progress[0]->physical_progress ?>" max="100" step="1" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Financial Progress <span style="color: red; font-size: 20px">*</span></label>
                                    <div class="input-group mb-3">
                                        <input type='number' id="financial_progress" name="financial_progress" class="form-control number" placeholder="progress percentage" value="<?= $progress[0]->financial_progress ?>" min="<?= $progress[0]->financial_progress ?>" max="100" step="1" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Stage of Work <span style="color: red; font-size: 20px">*</span></label><br>
                                    <label class="btn btn-outline-primary" for="ongoing"><input type="radio" class="btn-check" name="stage_of_work" id="ongoing" value="ongoing" autocomplete="off" <?= $check = $progress[0]->current_work_of_stage == 'ongoing' ? 'checked' : '' ?> required>&nbsp;<span style="color: #00008B; font-size: 18px">Ongoing</span></label>
                                    <label class="btn btn-outline-info" for="maintenance"><input type="radio" class="btn-check" name="stage_of_work" id="maintenance" value="maintenance" autocomplete="off" <?= $progress[0]->current_work_of_stage == 'maintenance' ? 'checked' : '' ?>>&nbsp;<span style="color: #00008B; font-size: 18px">Maintenance</span></label>
                                    <label class="btn btn-outline-success" for="complete"><input type="radio" class="btn-check" name="stage_of_work" id="complete" value="complete" autocomplete="off" <?= $progress[0]->current_work_of_stage == 'complete' ? 'checked' : '' ?>>&nbsp;<span style="color: #00008B; font-size: 18px">Complete</span></label>
                                </div>
                            </div>
                        </div>
                        <div id="div_image" class="row">
                            <?php
                            $total = $total_img[0]->seq_id == 10 || $total_img[0]->seq_id == 0 ? '10' : '15';
                            for ($i = 0; $i < $total; $i++) {
                                echo '<div class="col-md-6"><div class="column">';
                                echo '<div class="form-group">';
                                echo '<label>Description <span style="color: red; font-size: 20px">*</span></label>';
                                echo '<textarea class="form-control" name="desc[' . $i . ']" id="desc_' . $i . '" rows="1" required>' . (isset($selected[$i]) ? $selected[$i]->description : '') . '</textarea>';
                                echo '</div>';
                                echo '<div class="form-group">';
                                echo '<label>Upload Image <span style="color: red; font-size: 20px">*</span> <br><span style="font-size: 10px; color: red;">( Maximum 2Mb, Uploaded Format - jpeg/jpg/png ) </span></label>';
                                echo '<div class="multiselect_div">';
                                echo '<input type="file" id= "image[' . $i . ']" name="image[' . $i . ']" data-default-file="' . (isset($selected[$i]) ? base_url($selected[$i]->image) : '') . '" class="dropify" data-max-file-size="2048K" accept="image/*"' . (isset($selected[$i]) ? '' : 'required') . '>';
                                echo '</div>';
                                echo '</div>';
                                echo '</div></div>';
                            }
                            if ($total_img[0]->seq_id > 15) {
                                for ($i = 10; $i < $total_img[0]->seq_id; $i++) {
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
                    <input type="hidden" id="qm_id" name="qm_id" value="<?= $qm_id ?>">
                    <input type="hidden" name="agency" value="<?= $agency ?>">
                    <input type="hidden" id="bridge_id" name="bridge_id" value="<?= $bridge_id ?>">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var i = <?= $total_img[0]->seq_id > 10 ? $total_img[0]->seq_id : 10 ?>;
    $(document).ready(function () {
        $("#physical_progress").on('keyup', function () {
            var progress_status = $("#physical_progress").val();
            if (progress_status > 100) {
                $.notify(
                        "<span style=background-color:#FFFFFF;color:#FFA500;font-size:145% > Physical Progress Greater-than 100% </span>"
                        );
                progress_status = 100;
            } else if (progress_status.charAt(0) == 0) {
                progress_status = parseInt(progress_status);
            } else {
                progress_status = progress_status;
            }
            $('#physical_progress').val(progress_status);
        });
        $("#financial_progress").on('keyup', function () {
            var progress_status = $("#financial_progress").val();
            if (progress_status > 100) {
                $.notify(
                        "<span style=background-color:#FFFFFF;color:#FFA500;font-size:145% > Financial Progress Greater-than 100% </span>"
                        );
                progress_status = 100;
            } else if (progress_status.charAt(0) == 0) {
                progress_status = parseInt(progress_status);
            } else {
                progress_status = progress_status;
            }
            $('#financial_progress').val(progress_status);
        });
    });
</script>
<script src="<?= base_url('templates/js/capex_qm.js') ?>"></script>