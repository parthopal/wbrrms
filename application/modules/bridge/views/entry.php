<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$scheme = json_decode($scheme);
$foundation = json_decode($foundation);
$superstructure = json_decode($superstructure);
$type = json_decode($type);
$material = json_decode($material);
$ownership = json_decode($ownership);
$condition = json_decode($condition);
$selected = json_decode($selected);
$div_ownership = $selected->ownership_id < 50 ? 'style="display: none;"' : '';
$div_material = $selected->material_id < 50 && $selected->material_id > 0 ? '' : 'style="display: none;"';
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
                        <?php echo form_open('bridge/save'); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="reqd">Name</label>
                                        <div class="input-group">
                                            <input type='text' id="name" name="name" class="form-control" placeholder="name" value="<?= $selected->name ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2 mt-4 text-right">
                                    <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                        <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                    </button>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">District</label>
                                        <select id="district_id" name="district_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select District--</option>';
                                            foreach ($district as $row) {
                                                $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Block</label>
                                        <select id="block_id" name="block_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select Block--</option>';
                                            foreach ($block as $row) {
                                                $_selected = ($selected->block_id > 0 && $selected->block_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Scheme</label>
                                        <select id="scheme_id" name="scheme_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select Scheme--</option>';
                                            foreach ($scheme as $row) {
                                                $_selected = ($selected->scheme_id > 0 && $selected->scheme_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Package No</label>
                                        <div class="input-group">
                                            <input type='text' id="package_no" name="package_no" class="form-control" placeholder="package_no" value="<?= $selected->package_no ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Ownership</label>
                                        <select id="ownership_id" name="ownership_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select Ownership--</option>';
                                            foreach ($ownership as $row) {
                                                $_selected = ($selected->ownership_id > 0 && $selected->ownership_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="div_ownership" class="col-md-3" <?= $div_ownership ?>>
                                    <div class="form-group">
                                        <label>Ownership</label>
                                        <div class="input-group">
                                            <input type='text' id="ownership" name="ownership" class="form-control" placeholder="ownership" value="<?= $selected->ownership ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Condition</label>
                                        <select id="condition_id" name="condition_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select Condition--</option>';
                                            foreach ($condition as $row) {
                                                $_selected = ($selected->condition_id > 0 && $selected->condition_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="reqd">Length (in meter)</label>
                                        <div class="input-group">
                                            <input type='text' id="length" name="length" class="form-control" placeholder="length" value="<?= $selected->length ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="reqd">Carriageway Width (in meter)</label>
                                        <div class="input-group">
                                            <input type='text' id="width" name="width" class="form-control" placeholder="width" value="<?= $selected->width ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Chainage (in meter)</label>
                                        <div class="input-group">
                                            <input type='text' id="chainage" name="chainage" class="form-control" placeholder="chainage" value="<?= $selected->chainage ?>">
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Latitude</label>
                                        <div class="input-group">
                                            <input type='text' id="latitude" name="latitude" class="form-control" placeholder="latitude" value="<?= $selected->latitude ?>" required>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Longitude</label>
                                        <div class="input-group">
                                            <input type='text' id="longitude" name="longitude" class="form-control" placeholder="longitude" value="<?= $selected->longitude ?>" required>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Material</label>
                                        <select id="material_id" name="material_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="">--Select Material--</option>';
                                            foreach ($material as $row) {
                                                $_selected = ($selected->material_id > 0 && $selected->material_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="div_material" <?= $div_material ?>>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Substructure Type</label>
                                            <select id="type_id" name="type_id" class="form-control dropdown">
                                                <?php
                                                echo '<option value="0">--Select Type--</option>';
                                                foreach ($type as $row) {
                                                    $_selected = ($selected->type_id > 0 && $selected->type_id == $row->id) ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Foundation</label>
                                            <select id="foundation_id" name="foundation_id" class="form-control dropdown">
                                                <?php
                                                echo '<option value="0">--Select Foundation--</option>';
                                                foreach ($foundation as $row) {
                                                    $_selected = ($selected->foundation_id > 0 && $selected->foundation_id == $row->id) ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Super Structure</label>
                                            <select id="superstructure_id" name="superstructure_id" class="form-control dropdown">
                                                <?php
                                                echo '<option value="0">--Select Super Structure--</option>';
                                                foreach ($superstructure as $row) {
                                                    $_selected = ($selected->superstructure_id > 0 && $selected->superstructure_id == $row->id) ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
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
