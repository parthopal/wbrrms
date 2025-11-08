<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
// print_r($ac);exit;
$block = json_decode($block);
$gp = json_decode($gp);
$survey = json_decode($survey);
$selected = $selected != '' ? json_decode($selected) : '';
// print_r($selected);exit;
$style = 'style="display: none';
$disabled = $role_id > 4 ? 'disabled' : '';
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <h4 class="ml-3 mt-2"><?= $subheading; ?></h4>
                            </div>
                            <div class="col-md-2" style="display: none;">
                                <div class="input-group" style="width: 65px !important;">
                                    <input id="approved" name="aapproved" type="checkbox" data-toggle="toggle" data-onstyle="danger" data-style="btn-round" value="<?= $selected->isapproved ?>" <?= $selected->isapproved > 0 ? "checked" : "" ?>>
                                </div>
                            </div>
                        </div>
                        <?php echo form_open('roads/save'); ?>
                        <div class="card-body">
                            <?php
                            if ($role_id < 4) {
                            ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="name">Name*</label>
                                            <div class="input-group">
                                                <input type='text' id="name" name="name" class="form-control" placeholder="name" value="<?= $selected->name ?>" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Agency</label>
                                            <?php
                                            function sel($a, $b)
                                            {
                                                return trim($a) === trim($b) ? 'selected' : '';
                                            }
                                            ?>
                                            <select id="agency" name="agency" class="form-control dropdown" data-live-search="true">
                                                <option value="">--Select Agency--</option>
                                                <option value="ZP" <?= sel($selected->agency, 'ZP') ?>>ZP</option>
                                                <option value="BLOCK" <?= sel($selected->agency, 'BLOCK') ?>>BLOCK</option>
                                                <option value="SRDA" <?= sel($selected->agency, 'SRDA') ?>>SRDA</option>
                                                <option value="MBL" <?= sel($selected->agency, 'MBL') ?>>MBL</option>
                                                <option value="AGRO" <?= sel($selected->agency, 'AGRO') ?>>AGRO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>District *</label>
                                        <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" required <?= $disabled; ?>>
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
                                        <label>Assembly *</label>
                                        <select id="ac_id" name="ac_id" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select Assembly--</option>
                                            <?php
                                            foreach ($ac as $row) {
                                                $_selected = ($selected->ac_id > 0 && $selected->ac_id == $row->id) ? 'selected' : '';
                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Block*</label>
                                        <select id="block_id" name="block_id" class="form-control dropdown" required <?= $disabled; ?>>
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
                                <div class="col-md-6">
                                    <div class="form-group select2-input select2-warning">
                                        <label>GP*</label>
                                        <select id="gp_id" name="gp_id[]" multiple="multiple" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select GP--</option>
                                            <?php
                                            echo 'gp_id : ' . $selected->gp_id;
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="village">Village</label>
                                        <div class="input-group">
                                            <input type='text' id="village" name="village" class="form-control" placeholder="village" value="<?= $selected->village ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Proposed Surface Type</label>
                                        <select id="road_type" name="road_type" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select Road Type--</option>
                                            <option value="Bituminious(Tar)Road" <?= $selected->road_type == 'Bituminious(Tar)Road' ? 'selected' : '' ?>>Bituminious Road</option>
                                            <option value="Concrete Road" <?= $selected->road_type == 'Concrete Road' ? 'selected' : '' ?>>Concrete Road</option>
                                            <option value="Bituminious(Tar)/Concrete Road" <?= $selected->road_type == 'Bituminious(Tar)/Concrete Road' ? 'selected' : '' ?>>Bituminious & Concrete Road</option>
                                            <!-- <option value="Earthen Road" <?= $selected->road_type == 'Earthen Road' ? 'selected' : '' ?>>Earthen Road</option>
                                            <option value="Kankar Road" <?= $selected->road_type == 'Kankar Road' ? 'selected' : '' ?>>Kankar Road</option> -->
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Type of work</label>
                                        <select id="work_type" name="work_type" class="form-control dropdown" data-live-search="true" required>
                                            <option value="">--Select Work Type--</option>
                                            <option value="Construction" <?= $selected->work_type == 'Construction' ? 'selected' : '' ?>>Construction</option>
                                            <option value="Repair" <?= $selected->work_type == 'Repair' ? 'selected' : '' ?>>Repair</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3" id="bt_length_wrapper" style="display:none;">
                                    <div class="form-group">
                                        <label for="bt_length">Executable BT Length (km) <span class="text-danger">*</span></label>
                                        <input type="text" id="bt_length" name="bt_length" class="form-control" placeholder="length" value="<?= $selected->bt_length ?>">
                                    </div>
                                </div>

                                <div class="col-md-3" id="cc_length_wrapper" style="display:none;">
                                    <div class="form-group">
                                        <label for="cc_length">Executable CC Length (km) <span class="text-danger">*</span></label>
                                        <input type="text" id="cc_length" name="cc_length" class="form-control" placeholder="length" value="<?= $selected->cc_length ?>">
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="ex_length">Executable Road Length (km)</label>
                                        <div class="input-group">
                                            <input type='text' id="ex_length" name="ex_length" class="form-control" placeholder="length" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>New Technology Type of road </label>
                                        <select id="new_road_type" name="new_road_type" class="form-control dropdown" data-live-search="true">
                                            <option value="">--Select Road Type--</option>
                                            <option value="Waste Plastic Technology" <?= $selected->new_road_type == 'Waste Plastic Technology' ? 'selected' : '' ?>>Waste Plastic Technology</option>
                                            <option value="Jute Geotextile" <?= $selected->new_road_type == 'Jute Geotextile' ? 'selected' : '' ?>>Jute Geotextile</option>
                                            <option value="Panelled Cement Concrete" <?= $selected->new_road_type == 'Panelled Cement Concrete' ? 'selected' : '' ?>>Panelled Cement Concrete</option>
                                            <option value="Cell Filled Concrete" <?= $selected->new_road_type == 'Cell Filled Concrete' ? 'selected' : '' ?>>Cell Filled Concrete</option>
                                            <option value="Steel Slag" <?= $selected->new_road_type == 'Steel Slag' ? 'selected' : '' ?>>Steel Slag</option>
                                            <option value="Blast Furnace Slag" <?= $selected->new_road_type == 'Blast Furnace Slag' ? 'selected' : '' ?>>Blast Furnace Slag</option>
                                            <option value="Thin White Topping" <?= $selected->new_road_type == 'Thin White Topping' ? 'selected' : '' ?>>Thin White Topping</option>
                                            <option value="Cold Mix Technology" <?= $selected->new_road_type == 'Cold Mix Technology' ? 'selected' : '' ?>>Cold Mix Technology</option>
                                            <option value="ICBP technology" <?= $selected->new_road_type == 'ICBP technology' ? 'selected' : '' ?>>ICBP Technology</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3" id="new_length_wrapper" style="display: none;">
                                    <div class="form-group">
                                        <label for="length">New Technology Road Length (Km) <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type='text' id="new_length" name="new_length" class="form-control" placeholder="length" value="<?= $selected->new_length ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="length">Road Length (km)</label>
                                        <div class="input-group">
                                            <input type='text' id="length" name="length" class="form-control" placeholder="length" value="<?= $selected->length ?>" <?= $disabled; ?>>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($role_id < 4) {
                                ?>
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
                                        <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?php echo $selected ? $selected->id : ''; ?>">
                        <input type="hidden" id="isapproved" name="isapproved" value="<?= $selected->isapproved ?>">
                        <input type="hidden" name="proposed_length" value="<?= $selected->proposed_length ?>">

                        <?php if ($role_id > 4): ?>
                            <input type="hidden" name="proposed_length" value="<?= $selected->proposed_length ?>">
                            <input type="hidden" name="district_id" value="<?= $selected->district_id ?>">
                            <input type="hidden" name="agency" value="<?= $selected->agency ?>">
                            <input type="hidden" name="block_id" value="<?= $selected->block_id ?>">
                        <?php endif; ?>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {

        function toggleBtCcFields() {
            const roadType = $("#road_type").val();

            // Hide both initially
            $("#bt_length_wrapper, #cc_length_wrapper").hide();
            $("#bt_length, #cc_length").removeAttr("required");

            if (roadType === "Bituminious(Tar)Road") {
                $("#bt_length_wrapper").show();
                $("#bt_length").attr("required", true);
                $("#cc_length").val(0); // Auto set 0 for CC

            } else if (roadType === "Concrete Road") {
                $("#cc_length_wrapper").show();
                $("#cc_length").attr("required", true);
                $("#bt_length").val(0); // Auto set 0 for BT

            } else if (roadType === "Bituminious(Tar)/Concrete Road") {
                $("#bt_length_wrapper, #cc_length_wrapper").show();
                $("#bt_length, #cc_length").attr("required", true);
            } else {
                $("#bt_length").val(0);
                $("#cc_length").val(0);
            }

            updateExLength(); // Update total when toggling fields
        }

        function updateExLength() {
            const bt = parseFloat($("#bt_length").val()) || 0;
            const cc = parseFloat($("#cc_length").val()) || 0;
            $("#ex_length").val(bt + cc);
        }

        toggleBtCcFields();
        $("#road_type").on("change", toggleBtCcFields);

        // Update total whenever BT or CC length changes
        $("#bt_length, #cc_length").on("input", updateExLength);

        // Before form submit – make sure hidden field = 0
        $("form").on("submit", function() {
            if ($("#bt_length_wrapper").is(":hidden")) {
                $("#bt_length").val(0);
            }
            if ($("#cc_length_wrapper").is(":hidden")) {
                $("#cc_length").val(0);
            }
            updateExLength(); // Final update before submit
        });

        function toggleNewLengthField() {
            if ($("#new_road_type").val() !== "") {
                $("#new_length_wrapper").show();
                $("#new_length").attr("required", true);
            } else {
                $("#new_length_wrapper").hide();
                $("#new_length").removeAttr("required").val(""); // optional reset
            }
        }

        // Initial check on page load
        toggleNewLengthField();

        // Trigger when selection changes
        $("#new_road_type").on("change", toggleNewLengthField);

    });
</script>




<script src="<?= base_url('templates/js/roads.js') ?>"></script>