<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$gp = json_decode($gp);
$survey = json_decode($survey);
$selected = $selected != '' ? json_decode($selected) : '';
$style = 'style="display: none';
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
                            <div class="row col-md-10">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <span><h1 class="card-title ml-3">State Fund</h1></span>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group" style="width: 65px !important;">
                                    <input id="approved" name="aapproved" type="checkbox" data-toggle="toggle" data-onstyle="danger" data-style="btn-round" value="<?= $selected->isapproved ?>" <?= $selected->isapproved > 0 ? "checked" : "" ?>></div>
                            </div>
                        </div>
                        <?php echo form_open('sf/save'); ?>
                        <div class="card-body">
                            <?php
                            if ($role_id < 4) {
                                ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name*</label>
                                            <div class="input-group">
                                                <input type='text' id="name" name="name" class="form-control" placeholder="name" value="<?= $selected->name ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Category</label>
                                        <select id="tag" name="tag" class="form-control dropdown" data-live-search="true">
                                            <option value="">--Select Category--</option>
                                            <option value="SF" <?= $selected->tag == "SF" ? "selected" : "" ?>>SF</option>
                                            <option value="SF926" <?= $selected->tag == "SF926" ? "selected" : "" ?>>SF926</option>
                                            <option value="CSR17" <?= $selected->tag == "CSR17" ? "selected" : "" ?>>CSR17</option>
                                            <option value="CSR6.5" <?= $selected->tag == "CSR6.5" ? "selected" : "" ?>>CSR6.5</option>
                                            <option value="MP54" <?= $selected->tag == "MP54" ? "selected" : "" ?>>MP54</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>District *</label>
                                        <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select District--</option>
                                            <?php
                                            foreach ($district as $row) {
                                                $_selected = $row->id == $selected->district_id ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Block*</label>
                                        <select id="block_id" name="block_id" class="form-control dropdown" required>
                                            <?php
                                            echo '<option value="0">--All Block--</option>';
                                            foreach ($block as $row) {
                                                $_selected = ($selected->block_id > 0 && $selected->block_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group select2-input select2-warning">
                                        <label>GP*</label>
                                        <select id="gp_id" name="gp_id[]" multiple="multiple" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select GP--</option>
                                            <?php
                                            $gp_id = explode(",", $selected->gp_id);
                                            foreach ($gp as $row) {
                                                $_selected = '';
                                                if (in_array($row->id, $gp_id)) {
                                                    $_selected = 'selected';
                                                }
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="village">Village</label>
                                        <div class="input-group">
                                            <input type='text' id="village" name="village" class="form-control" placeholder="village" value="<?= $selected->village ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Implementing Agency</label>
                                        <select id="agency" name="agency" class="form-control dropdown" data-live-search="true">
                                            <option value="">--Select Agency--</option>
                                            <option value="ZP" <?= $selected->agency == 'ZP' ? 'selected' : '' ?>>ZP</option>
                                            <option value="BLOCK" <?= $selected->agency == 'BLOCK' ? 'selected' : '' ?>>BLOCK</option>
                                            <option value="SRDA" <?= $selected->agency == 'SRDA' ? 'selected' : '' ?>>SRDA</option>
                                            <option value="MBL" <?= $selected->agency == 'MBL' ? 'selected' : '' ?>>MBL</option>
                                            <option value="AGRO" <?= $selected->agency == 'AGRO' ? 'selected' : '' ?>>AGRO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?php
                                if ($role_id < 4) {
                                    ?>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="length">Actual Road Length</label>
                                            <div class="input-group">
                                                <input type='text' id="length" name="length" class="form-control" placeholder="length" value="<?= $selected->length ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Type of road</label>
                                            <select id="road_type" name="road_type" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Road Type--</option>
                                                <option value="Bituminious(Tar)Road" <?= $selected->road_type == 'Bituminious(Tar)Road' ? 'selected' : '' ?>>Bituminious(Tar)Road</option>
                                                <option value="Concrete Road" <?= $selected->road_type == 'Concrete Road' ? 'selected' : '' ?>>Concrete Road</option>
                                                <option value="Earthen Road" <?= $selected->road_type == 'Earthen Road' ? 'selected' : '' ?>>Earthen Road</option>
                                                <option value="Kankar Road" <?= $selected->road_type == 'Kankar Road' ? 'selected' : '' ?>>Kankar Road</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Type of work</label>
                                            <select id="work_type" name="work_type" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Work Type--</option>
                                                <option value="Construction" <?= $selected->work_type == 'Construction' ? 'selected' : '' ?>>Construction</option>
                                                <option value="Repair" <?= $selected->work_type == 'Repair' ? 'selected' : '' ?>>Repair</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 isapproved">
                                        <div class="form-group">
                                            <label>Vetted Amount<span class="lblreqd"></span></label>
                                            <div class="input-group">
                                                <input type='text' id="cost" name="cost" class="form-control reqd" placeholder="vetted amount" value="<?= $selected->cost ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 isapproved">
                                        <div class="form-group">
                                            <label>Admin No.<span class=""></span></label>
                                            <div class="input-group">
                                                <input type="text" id="admin_no" name="admin_no" class="form-control " placeholder="Admin No." value="<?= $selected->admin_no ?>" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 isapproved">
                                        <div class="form-group">
                                            <label>Admin Date<span class=""></span></label>
                                            <div class="input-group">
                                                <input type="text" id="admin_date" name="admin_date" class="form-control datepicker " placeholder="DD/MM/YYYY" value="<?= $selected->admin_date != '' ? $selected->admin_date : '' ?>" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                            <div class="card-action">
                                <div class="col-md-12 text-right">
                                    <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                        <i class="fas fa-save_survey"></i> &nbsp; <span>SAVE</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $selected ? $selected->id : ''; ?>">
                        <input type="hidden" id="isapproved" name="isapproved" value="<?= $selected->isapproved ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= base_url('templates/js/sf.js') ?>"></script>
