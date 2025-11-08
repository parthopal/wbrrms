<?php
defined('BASEPATH') or exit('No direct script access allowed');
$district = json_decode($district);
// print_r($district);exit;
$selected = json_decode($selected);
$block = json_decode($block);
?>

<div class="container">
    <!-- Header -->
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading; ?></h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Body -->
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Title Row -->
                        <div class="row mb-3">
                            <div class="col-md-1">
                                <a href="<?= base_url('ridf/postdlp_maintenance') ?>">
                                    <button type="button" class="btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-11 text-center">
                                <div class="col-md-12">
                                    <h2 class="mb-1"><?= $selected->name ?></h2>
                                    <p class="text-muted mb-0"><?= $selected->scheme_id ?> / <?= $selected->agency ?></p>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Form Start -->
                        <?= form_open_multipart('ridf/postdlp_save'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>District <span class="text-danger">*</span></label>
                                            <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true">
                                                <?php
                                                foreach ($district as $row) {
                                                    if ($selected->district_id == $row->id) {
                                                        echo '<option value="' . $row->id . '" selected>' . $row->name . '</option>';
                                                        break;
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                    </div>

                                    <!-- Block -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Block *</label>
                                            <select id="block_id" name="block" class="form-control dropdown" data-live-search="true">
                                                <option value=""><?= $selected->block ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Carriage Way Type -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="carriage_type">Carriage Way Type <span class="text-danger">*</span></label>
                                            <select id="carriage_type" name="carriage_type" class="form-control dropdown" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1">Existing Carriage Way</option>
                                                <option value="2">Proposed Carriage Way</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Select Width (m) -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="existing_carriage_value">Select Width (m) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select id="existing_carriage_value" name="existing_carriage_value" class="form-control dropdown" style="width: 100%;" required>
                                                    <option value="">select</option>
                                                    <option value="3.0">3.0</option>
                                                    <option value="3.75">3.75</option>
                                                    <option value="5.5">5.5</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Road Way width Type -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="roadway_type">Road Way Width Type <span class="text-danger">*</span></label>
                                            <select id="roadway_type" name="roadway_type" class="form-control dropdown" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="1">Existing Road Way Width</option>
                                                <option value="2">Proposed Road Way Width</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Existing Width Options -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="existing_width">Select Existing Width (m) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <select id="existing_width" name="existing_width" class="form-control dropdown" style="width: 100%;" required>
                                                    <option value="">Select</option>
                                                    <option value="6.0">6.0</option>
                                                    <option value="7.5">7.5</option>
                                                    <option value="9.0">9.0</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Estimation File *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2 MB, Uploaded Format - .pdf )</span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="multiselect_div">
                                        <input type="file" name="estimation_file" class="dropify"
                                            accept="application/pdf" data-max-file-size="2M"
                                            data-allowed-file-extensions="pdf" required />
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="row ">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Existing Road Length (km)</label>
                                </div>
                            </div>
                            <!-- BT Input -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="existing_bt_length">BT Length (km) </label>
                                    <input type="number" id="existing_bt_length" name="existing_bt_length" class="form-control" oninput="calculateTotalRoad()" step="0.01" min="0">
                                </div>
                            </div>

                            <!-- CC Input -->

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="existing_cc_length">Existing CC Length (km)</label>
                                    <input type="number" id="existing_cc_length" name="existing_cc_length" class="form-control" oninput="calculateTotalRoad()" step="0.01" min="0">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="existing_total_length">Total Length (km)</label>
                                    <input type="number" id="existing_total_length" name="existing_total_length" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Proposed Maintenance Length</label>

                                </div>
                            </div>
                            <!-- Proposed BT Input -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prop_bt_length">BT Length (km)</label>
                                    <input type="number" id="prop_bt_length" name="prop_bt_length" class="form-control" oninput="calculateProposedTotal()" step="0.01" min="0">
                                </div>
                            </div>

                            <!-- Proposed CC Input -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prop_cc_length">CC Length (km)</label>
                                    <input type="number" id="prop_cc_length" name="prop_cc_length" class="form-control" oninput="calculateProposedTotal()" step="0.01" min="0">
                                </div>
                            </div>

                            <!-- Proposed Total Length (Readonly) -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="prop_total_length">Total Proposed Length (km)</label>
                                    <input type="number" id="prop_total_length" name="prop_total_length" class="form-control" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="existing_traffic">Existing Traffic Category <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="existing_traffic" name="existing_traffic"
                                            class="form-control" placeholder="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="prop_traffic">Proposed Traffic Category <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="prop_traffic" name="prop_traffic"
                                            class="form-control" placeholder="" required>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="initial_rehabilitation_cost">Initial Rehabilitation Cost <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="initial_rehabilitation_cost" name="initial_rehabilitation_cost" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="renewal_cost">Renewal Cost <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" id="renewal_cost" name="renewal_cost"
                                            class="form-control" placeholder="Enter amount"
                                            required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="font-weight-bold">Routine Maintenance</label>
                                </div>
                            </div>

                            <!-- Yearly Inputs -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_year1">1st Year</label>
                                    <input type="text" id="routine_year1" name="routine_year1" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_year2">2nd Year</label>
                                    <input type="text" id="routine_year2" name="routine_year2" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_year3">3rd Year</label>
                                    <input type="text" id="routine_year3" name="routine_year3" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_year4">4th Year</label>
                                    <input type="text" id="routine_year4" name="routine_year4" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_year5">5th Year</label>
                                    <input type="text" id="routine_year5" name="routine_year5" class="form-control" placeholder="Enter amount" required oninput="calculateTotal(); this.value = this.value.replace(/[^0-9.]/g, '')">
                                </div>
                            </div>

                            <!-- Total Cost Display -->
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="routine_total">Total Routine Cost</label>
                                    <input type="number" id="routine_total" name="routine_total" class="form-control" oninput="calculateTotal();" placeholder="₹" readonly>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total_project_cost" class="font-weight-bold">Total Project Cost</label>
                                    <input type="number" id="total_project_cost" name="total_project_cost" class="form-control" placeholder="₹" readonly>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-12 text-right ">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                    </div>



                </div>


                <input type="hidden" name="id" value="<?= $selected->id ?>">
                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<script>
    function calculateTotalRoad() {
        const bt = parseFloat(document.getElementById('existing_bt_length').value) || 0;
        const cc = parseFloat(document.getElementById('existing_cc_length').value) || 0;
        const total = bt + cc;
        document.getElementById('existing_total_length').value = total.toFixed(2);
    }

    function calculateProposedTotal() {
        const bt = parseFloat(document.getElementById('prop_bt_length').value) || 0;
        const cc = parseFloat(document.getElementById('prop_cc_length').value) || 0;
        const total = bt + cc;
        document.getElementById('prop_total_length').value = total.toFixed(2);
    }


    // function calculateRoutineTotal() {
    //     let total = 0;
    //     for (let i = 1; i <= 5; i++) {
    //         let val = parseFloat(document.getElementById(`routine_year${i}`).value) || 0;
    //         total += val;
    //     }
    //     document.getElementById('routine_total').value = total.toFixed(2);
    // }

    function calculateTotal() {
        const val = id => parseFloat(document.getElementById(id).value) || 0;

        // Total routine cost
        let routineTotal = val("routine_year1") + val("routine_year2") + val("routine_year3") + val("routine_year4") + val("routine_year5");
        document.getElementById("routine_total").value = routineTotal.toFixed(2);

        // Total project cost
        let total = val("initial_rehabilitation_cost") + val("renewal_cost") + routineTotal;
        document.getElementById("total_project_cost").value = total.toFixed(2);
    }
</script>

<script src="<?= base_url('templates/js/ridf.js') ?>"></script>