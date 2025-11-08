<?php
defined('BASEPATH') or exit('No direct script access allowed');
$district = json_decode($district);
$block = json_decode($block);
$gp = json_decode($gp);
$selected = json_decode($selected);
//var_dump($selected);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0"></div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-10 text-center">
                                <h4><?= $title ?></h4>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open_multipart('proposal/save'); ?>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="form-group" style="background-color: whitesmoke">
                                    <label>Information Received From</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Refered By(Name & Designation)</label>
                                            <div class="input-group">
                                                <input type='text' id="name" name="name" class="form-control number" placeholder="Name & Designation" value="<?= $selected->name ?>" autocomplete="off">
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-md-7">
                                        <div class="form-group">
                                            <label>Contact No</label>
                                            <div class="input-group">
                                                <input type='text' id="contactno" name="contactno" class="form-control number" placeholder="Contactno" value="<?= $selected->contactno ?>" autocomplete="off">
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-5"> 
                                        <div class="form-group">
                                            <label>Received date </label>
                                            <div class="input-group">
                                                <input type="text" id="date" name="date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= $selected->date ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Received through </label>
                                            <select id="letter" name="letter" class="form-control dropdown" data-live-search="true" >
                                                <option value="">--Select Type--</option>
                                                <option value="letter" <?= $selected->letter == 'letter' ? 'selected' : '' ?>>Letter</option>
                                                <option value="email" <?= $selected->letter == 'email' ? 'selected' : '' ?>>Email</option>
                                                <option value="message" <?= $selected->letter == 'message' ? 'selected' : '' ?>>Message</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Agency</label>
                                            <select id="agency" name="agency" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Type--</option>
                                                <option value="zp" <?= $selected->agency == 'zp' ? 'selected' : '' ?>>ZP</option>
                                                <option value="agro" <?= $selected->agency == 'agro' ? 'selected' : '' ?>>AGRO</option>
                                                <option value="mbl" <?= $selected->agency == 'mbl' ? 'selected' : '' ?>>MBL</option>
                                                <option value="block" <?= $selected->agency == 'block' ? 'selected' : '' ?>>BLOCK</option>
                                                <option value="srda" <?= $selected->agency == 'srda' ? 'selected' : '' ?>>SRDA</option>
                                            </select>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Upload Image<br>
                                                <span style="font-size: 10px; color: red;">( Maximum 200kb, Uploaded Format - jpeg/jpg/png ) </span></label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="multiselect_div">
                                                <input type="file" name="userfile" data-default-file="../../<?php echo $selected ? base_url($selected->image) : ''; ?>" class="dropify" data-max-file-size="200K" accept="image/*">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Action Taken</label>
                                    <div class="input-group">
                                        <textarea rows="2" type="text" class="form-control" id="action_taken" name="action_taken"><?= $selected->action_taken ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <div class="form-group" style="background-color: whitesmoke">
                                    <label>Road/Bridge Information</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All District--</option>';
                                        foreach ($district as $row) {
                                            $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Block</label>
                                    <select id="block_id" name="block_id" class="form-control dropdown">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>GP</label>
                                    <select id="gp_id" name="gp_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All GP--</option>';
                                        foreach ($gp as $row) {
                                            $_selected = ($selected->gp_id > 0 && $selected->gp_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Road Name</label>
                                    <div class="input-group">
                                        <input type='text' id="road_name" name="road_name" class="form-control number" placeholder="Road name" value="<?= $selected->road_name ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Length</label>
                                    <div class="input-group">
                                        <input type='text' id="length" name="length" class="form-control number" placeholder="Length" value="<?= $selected->length ?>" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Unit</label>
                                    <select id="unit" name="unit" class="form-control dropdown" data-live-search="true">
                                        <option value="">--Select Unit--</option>
                                        <option value="km" <?= $selected->unit == 'km' ? 'selected' : '' ?>>KM</option>
                                        <option value="m" <?= $selected->unit == 'm' ? 'selected' : '' ?>>M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="row">                                    
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type of road </label>
                                            <select id="road_type" name="road_type" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Type--</option>
                                                <option value="earthenroad" <?= $selected->road_type == 'earthenroad' ? 'selected' : '' ?>>Earthen Road</option>
                                                <option value="concreteroad" <?= $selected->road_type == 'concreteroad' ? 'selected' : '' ?>>Concretere Road</option>
                                                <option value="bituminious(tar)road" <?= $selected->road_type == 'bituminious(tar)road' ? 'selected' : '' ?>>Bituminious(Tar) Road</option>
                                                <option value="kankarroad" <?= $selected->road_type == 'kankarroad' ? 'selected' : '' ?>>Kankar Road</option>
                                                <option value="brick" <?= $selected->road_type == 'brick' ? 'selected' : '' ?>>Brick</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Type of work</label>
                                            <select id="work_type" name="work_type" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Type--</option>
                                                <option value="construction" <?= $selected->work_type == 'construction' ? 'selected' : '' ?>>Construction</option>
                                                <option value="repair" <?= $selected->work_type == 'repair' ? 'selected' : '' ?>>Repair</option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Estimated Cost</label>
                                            <div class="input-group">
                                                <input type='text' id="cost" name="cost" class="form-control number" placeholder="Estimated Cost" value="<?= $selected->cost ?>" autocomplete="off">
                                            </div>
                                        </div> 
                                    </div> -->
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Approximate Cost</label>
                                            <div class="input-group">
                                                <input type='text' id="approximate_cost" name="approximate_cost" class="form-control number" placeholder="Approximate Cost" value="<?= $selected->approximate_cost ?>" autocomplete="off">
                                            </div>
                                        </div> 
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-group">
                                        <label for="remarks">Any Other Information </label>
                                        <textarea rows="5" type="text" class="form-control" id="information" name="information"><?= $selected->information ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right mb-2">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success">
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
</div>
<script src="<?= base_url('templates/js/proposal.js') ?>"></script>