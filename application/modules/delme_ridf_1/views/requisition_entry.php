<?php
defined('BASEPATH') or exit('No direct script access allowed');
$selected = json_decode($selected);
// print_r($selected);exit;
$district = json_decode($district);
$block = json_decode($block);
$category = json_decode($category);
$agency = json_decode($agency);
$road_list = json_decode($road_list);



$roll_ID = [15, 13, 16, 17];

$all_agency_names = array_map(function ($row) {
    return strtoupper($row->name);
}, $agency);

switch ($role_id) {
    case 15:
        $allowed_agency_names = ['WBSRDA'];
        break;
    case 13:
        $allowed_agency_names = ['ZP'];
        break;
    case 16:
        $allowed_agency_names = ['MBL'];
        break;
    case 17:
        $allowed_agency_names = ['AGRO'];
        break;
    default:
        $allowed_agency_names = $all_agency_names;
        break;
}

$disable = ($roll_ID == $role_id) ? 'readonly' : '';
$disabled = $role_id > 3 ? 'disabled style="background-color: white !important; color: #000000;"' : '';
// print_r($allowed_agency_names);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <!-- <div class="col-md-12"> -->
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-4">
                        <div class="col-auto">
                            <a href="<?= base_url('ridf/requisition') ?>">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </a>
                        </div>
                        <div class="col text-center">
                            <h2 class="mb-0"><?= $title ?></h2>
                        </div>
                    </div>

                    <?php echo form_open_multipart('ridf/requisition_save'); ?>



                    <div class="row">
                        <!-- Project Information Header -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group p-2 rounded shadow-sm" style="background: linear-gradient(to right, #f0f0f0, #bdd8ff);">
                                <label class="form-label mb-0">
                                    <i class="bi bi-info-circle me-2"></i>Project Information
                                </label>
                            </div>
                        </div>

                        <!-- Memo No -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="memo_no">Memo No <span class="text-danger">*</span></label>
                                <input type="text" id="memo_no" name="memo_no" class="form-control" required value="<?= $selected->memo_no ?>">
                            </div>
                        </div>

                        <!-- Date -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="date">Memo Date <span class="text-danger">*</span></label>
                                <?php $memo_date = !empty($selected->requisition_date) ? date('d/m/Y', strtotime($selected->requisition_date)) : ''; ?>
                                <div class="input-group">
                                    <input type="text" id="date" name="date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" required value="<?= $memo_date ?>">
                                    <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Agency -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="agency_id">Agency <span class="text-danger">*</span></label>
                                <select id="agency_id" name="agency_id" class="form-control dropdown" data-live-search="true" required>
                                    <?php
                                    $_selected = $selected->agency_id == '' ? 'selected' : '';
                                    if (!in_array($role_id, $roll_ID)) {
                                        echo "<option value='' $_selected>--Select Agency--</option>";
                                    }
                                    foreach ($agency as $row) {
                                        if (in_array(strtoupper($row->name), $allowed_agency_names)) {
                                            $_selected = $selected->agency_id == $row->id ? 'selected' : '';
                                            echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- District -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="district_id">District <span class="text-danger">*</span></label>
                                <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" required>
                                    <?php
                                    $_selected = $selected->district_id == '' ? 'selected' : '';
                                    echo "<option value='' $_selected>--Select District--</option>";
                                    foreach ($district as $row) {
                                        $_selected = $selected->district_id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Block -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="block_id">Block <span class="text-danger">*</span></label>
                                <select id="block_id" name="block_id" class="form-control dropdown" data-live-search="true" onchange="GetEmpty()" required>
                                    <option value="">--Select Block--</option>
                                    <?php
                                    foreach ($block as $row) {
                                        $_selected = $selected->block_id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Funding By -->
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="category_id">Funding By <span class="text-danger">*</span></label>
                                <select id="category_id" name="category_id" class="form-control dropdown" data-live-search="true" required>
                                    <?php
                                    $_selected = $selected->category_id == '' ? 'selected' : '';
                                    echo "<option value='' $_selected>--Select Funding--</option>";
                                    foreach ($category as $row) {
                                        $_selected = $selected->category_id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <!-- Project Name -->
                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <label for="work_id">Name of the Project <span class="text-danger">*</span></label>
                                <select id="work_id" name="work_id" class="form-control dropdown" data-live-search="true" required>
                                    <?php
                                    foreach ($road_list as $row) {
                                        $_selected = $selected->id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <?php ?>

                    <!-- Financial Details Header -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="form-group p-2 rounded shadow-sm" style="background: linear-gradient(to right, #f0f0f0, #bdd8ff);">
                                <label class="form-label mb-0">
                                    <i class="bi bi-info-circle me-2"></i>Financial Details
                                </label>
                            </div>
                        </div>

                        <!-- Sanctioned Cost -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="cost">Sanctioned Cost</label>
                                <input type="text" id="cost" name="cost" class="form-control" <?= $disabled ?> oninput="this.value = this.value.replace(/[^0-9.]/g, ''); calculateRIDFLoan();"
                                    value="<?= $selected->sanctioned_cost ?> ">
                            </div>
                        </div>


                        <!-- RIDF Loan -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="loan">RIDF Loan</label>
                                <input type="text" id="loan" name="loan" class="form-control" value="<?= $selected->ridf_loan ?>" <?= $disabled ?> oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                            </div>
                        </div>

                        <!-- Awareded cost Incuding Maintanance -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="awareded_cost">Awareded cost Incuding Maintanance <span class="text-danger">*</span></label>
                                <input type="text" id="awareded_cost" name="awareded_cost" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); calculateRIDFLoan();" required>
                            </div>
                        </div>



                        <!-- Physical Progress -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="progress">Physical Progress (%) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" id="progress" name="progress" class="form-control" min="1" max="100" required value="<?= $selected->physical_progress ?>">
                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                </div>
                            </div>
                        </div>
                        <!-- Previous Expenditure -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="previous_expenditure">Expenditure incurred upto previous claim <span class="text-danger">*</span></label>
                                <input type="text" id="previous_expenditure" name="previous_expenditure" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required value="<?= $selected->previous_claim_expenditure ?>">
                            </div>
                        </div>

                        <!-- Amounts Already Claimed -->
                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="already_claimed">Amount already claimed <span class="text-danger">*</span></label>
                                <input type="text" id="already_claimed" name="already_claimed" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required value="<?= $selected->amounts_already_claimed ?>">
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="work_value">Total Work Value</label>
                                <input type="text" id="work_value" name="work_value" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                            </div>
                        </div>

                        <div class="col-md-3 mb-3">
                            <div class="form-group">
                                <label for="present_expenditure">Expenditure incurred Present during claim <span class="text-danger">*</span></label>
                                <input type="text" id="present_expenditure" name="present_expenditure" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required value="<?= $selected->present_claim_expenditure ?>">
                            </div>
                        </div>


                        <div class="col-md-9">
                            <div class="row">


                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="dpr">DPR</label>
                                        <input type="text" id="dpr" name="dpr" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="contingency">Contingency</label>
                                        <input type="text" id="contingency" name="contingency" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                                    </div>
                                </div>





                                <!-- Present Claim Amount -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="present_claimed">Amount of Present claim <span class="text-danger">*</span></label>
                                        <input type="text" id="present_claimed" name="present_claimed" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required value="<?= $selected->present_claim_amount ?>">
                                    </div>
                                </div>

                                <!-- Ensuing Quarter -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="ensuing_quarter">Ensuing Quarter Drawal <span class="text-danger">*</span></label>
                                        <input type="text" id="ensuing_quarter" name="ensuing_quarter" class="form-control" oninput="this.value = this.value.replace(/[^0-9.]/g, '')" required value="<?= $selected->ensuing_quarter_drawal ?>">
                                    </div>
                                </div>

                                <!-- Period Ending Date -->
                                <div class="col-md-4 mb-3">
                                    <div class="form-group">
                                        <label for="ending_date">Period Ending Date <span class="text-danger">*</span></label>
                                        <?php
                                        $completion_date = !empty($selected->completion_date) ? date('d/m/Y', strtotime($selected->completion_date)) : '';
                                        ?>
                                        <div class="input-group">
                                            <input type="text" id="ending_date" name="ending_date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" required value="<?= $completion_date ?>">
                                            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Upload Image/Document<br>
                                    <span style="font-size: 10px; color: red;">(Max 200KB, Formats: jpeg, jpg, png, pdf)</span>
                                </label>
                                <input type="file" name="userfile" class="form-control dropify" data-max-file-size="200K" accept="image/jpeg, image/jpg, image/png, application/pdf" <?= isset($selected->document) && strlen($selected->document) > 0 ? 'data-default-file="../../' . $selected->document . '"' : ''; ?>>
                            </div>
                        </div>
                    </div>

                    <!-- Declaration Checkbox -->
                    <div class="checkbox-container mt-4">
                        <input type="checkbox" id="govtRules" name="govtRules" required>
                        <label for="govtRules">I agree to the following:</label>
                        <ol type="a">
                            <li>Items of work have been executed as per the financial rules of the Government of West Bengal after observing the prescribed tender formalities.</li>
                            <li>Expenditure reported has actually been incurred & audited and recorded in the books of accounts of the concerned divitions which in certificated by the conceptent authority of the line department.</li>
                            <li>The physical progress made is as per the CPM/PERT chart and is satisfactory. (in case of unsatifactory physical progress/reasons are given here under)</li>
                        </ol>
                    </div>

                    <hr>
                    <div class="row">
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?php echo isset($selected->id) ? $selected->id : ''; ?>">
                    <?php echo form_close(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script src="<?= base_url('templates/js/ridf.js') ?>"></script>



<script>
    // Input validation
    document.getElementById('progress').addEventListener('input', function() {
        const value = parseInt(this.value, 10);
        if (value < 1 || value > 100) {
            alert('Please enter a value between 1 and 100.');
            this.value = '';
            this.focus();
        }
    });

    function GetEmpty() {
        $("#work_id").empty();
        $("#cost").empty();
        $("#work_id").append(
            $("<option>", {
                value: "0",
                text: "--Name of the project --"
            })
        );

        $("#cost").val("Sanctioned Cost");
    }




    document.addEventListener("DOMContentLoaded", () => {
        const costInput = document.getElementById("cost");
        const loanInput = document.getElementById("loan");

        function getMaxLoan(cost) {
            return Math.round(cost * 0.8);
        }

        function calculateRIDFLoan() {
            const sanctionedCost = parseFloat(costInput.value) || 0;
            const maxLoan = getMaxLoan(sanctionedCost);

            // If the user hasn't manually edited the loan
            if (!loanInput.dataset.userEdited) {
                loanInput.value = maxLoan;
            }
        }

        function validateLoanInput() {
            const sanctionedCost = parseFloat(costInput.value) || 0;
            const maxLoan = getMaxLoan(sanctionedCost);
            let currentLoan = parseFloat(loanInput.value) || 0;

            if (currentLoan > maxLoan) {
                alert(`RIDF Loan cannot exceed 80% of Sanctioned Cost (₹${maxLoan}).`);
                loanInput.value = maxLoan;
            }

            // Mark as user-edited
            loanInput.dataset.userEdited = "true";
        }
        calculateRIDFLoan();

        costInput.addEventListener("input", () => {
            delete loanInput.dataset.userEdited;
            calculateRIDFLoan();
        });
        loanInput.addEventListener("input", validateLoanInput);
    });


    // ################################################


    document.addEventListener("DOMContentLoaded", function() {
        // === Element References ===
        const projectSelect = document.getElementById("work_id");
        const awardedCostInput = document.getElementById("awareded_cost");
        const progressInput = document.getElementById("progress");
        const workValueInput = document.getElementById("work_value");
        const alreadyClaimedInput = document.getElementById("already_claimed");
        const presentExpenditureInput = document.getElementById("present_expenditure");
        const loanInput = document.getElementById("loan");
        const dprInput = document.getElementById("dpr");
        const contingencyInput = document.getElementById("contingency");
        const presentClaimedInput = document.getElementById("present_claimed");
        const ensuingQuarterInput = document.getElementById("ensuing_quarter");


        // === Functions ===

        function calculateWorkValue() {
            const awardedCost = parseFloat(awardedCostInput.value) || 0;
            const progress = parseFloat(progressInput.value) || 0;
            const totalWorkValue = Math.round((awardedCost * progress) / 100);
            workValueInput.value = totalWorkValue;
            calculatePresentExpenditure();
        }

        function calculatePresentExpenditure() {
            const workValue = parseFloat(workValueInput.value) || 0;
            const alreadyClaimed = parseFloat(alreadyClaimedInput.value) || 0;
            const presentExpenditure = Math.max(workValue - alreadyClaimed, 0);
            presentExpenditureInput.value = presentExpenditure;
            calculatePresentClaim();
        }

        // DPR

        function validateDPR() {
            const loan = parseFloat(loanInput.value) || 0;
            const maxDpr = Math.round(loan * 0.005);
            let dprValue = parseFloat(dprInput.value) || 0;

            if (dprValue > maxDpr) {
                alert(`DPR Claim cannot exceed 0.5% of RIDF Loan (Upto: ${maxDpr})`);
                dprInput.value = "0";
                dprValue = 0;
            }

            calculatePresentClaim();
        }

        // Contingency

        function validateContingency() {
            const awardedCost = parseFloat(awardedCostInput.value) || 0;
            const maxContingency = Math.round(awardedCost * 0.03);
            let contingencyValue = parseFloat(contingencyInput.value) || 0;

            if (contingencyValue > maxContingency) {
                alert(`Contingency Claim cannot exceed 3% of Awarded Cost (Upto: ${maxContingency})`);
                contingencyInput.value = "0";
                contingencyValue = 0;
            }

            calculatePresentClaim();
        }

        function calculatePresentClaim() {
            const presentExpenditure = parseFloat(presentExpenditureInput.value) || 0;
            const dpr = parseFloat(dprInput.value) || 0;
            const contingency = parseFloat(contingencyInput.value) || 0;
            const presentClaim = presentExpenditure + dpr + contingency;

            presentClaimedInput.value = presentClaim;
            ensuingQuarterInput.value = presentClaim;
        }

        // === Event Listeners ===
        progressInput.addEventListener("input", calculateWorkValue);
        awardedCostInput.addEventListener("input", () => {
            calculateWorkValue();
            validateContingency();
        });

        alreadyClaimedInput.addEventListener("input", calculatePresentExpenditure);

        dprInput.addEventListener("input", validateDPR);
        contingencyInput.addEventListener("input", validateContingency);

        presentExpenditureInput.addEventListener("input", calculatePresentClaim);

        // === Initial Setup ===
        if (!dprInput.value) dprInput.value = "0";
        if (!contingencyInput.value) contingencyInput.value = "0";

        calculateWorkValue();
    });

    
</script>