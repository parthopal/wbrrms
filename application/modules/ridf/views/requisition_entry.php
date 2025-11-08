<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$agency = json_decode($agency);
$category = json_decode($category);
$work = json_decode($work);
$selected = json_decode($selected);
$list = json_decode($list);

// $road = $selected->ridf_id

// print_r($work);exit;
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
                        <div class="col-md-12 mb-3 badge-info">
                            <div class="form-group mt-2">
                                <label class="form-label">
                                    <h2 style="color: white;">Work Information</h2>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="reqd">Agency</label>
                                <select id="agency_id" name="agency_id" class="form-control dropdown" required>
                                    <?php
                                    $_selected = $selected->agency_id == '' ? 'selected' : '';
                                    foreach ($agency as $row) {
                                        $_selected = $selected->agency_id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="reqd">District</label>
                                <select id="district_id" name="district_id" class="form-control dropdown" required>
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
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="reqd">Tranche</label>
                                <select id="category_id" name="category_id" class="form-control dropdown" required>
                                    <?php
                                    $_selected = $selected->category_id == '' ? 'selected' : '';
                                    echo "<option value='' $_selected>--Select Tranche--</option>";
                                    foreach ($category as $row) {
                                        $_selected = $selected->category_id == $row->id ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="reqd">Name of the Work</label>
                                <select id="ridf_id" name="ridf_id" class="form-control dropdown" required>
                                    <?php
                                    $_selected = $selected->ridf_id == '' ? 'selected' : '';
                                    echo "<option value='' $_selected>--Select Work--</option>";
                                    foreach ($work as $row) {
                                        $_selected = ($selected->ridf_id == $row->id) ? 'selected' : '';
                                        echo "<option value='{$row->id}' $_selected>{$row->name}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>



                    </div>
                    <div id="info" class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-dark bg-secondary-gradient">
                                <div class="card-body skew-shadow">
                                    <span id="info_1"></span>
                                    <h2 id="info_2" class="py-4 mb-0"></h2>
                                    <div class="row">
                                        <div class="col-2 pr-0">
                                            <h3 id="info_3" class="fw-bold mb-1"></h3>
                                            <div class="text-small text-uppercase fw-bold op-8">Sanctioned Cost</div>
                                        </div>
                                        <div class="col-1 pr-0">
                                            <h3 id="info_4" class="fw-bold mb-1"></h3>
                                            <div class="text-small text-uppercase fw-bold op-8">Awarded Cost</div>
                                        </div>
                                        <div class="col-1 pr-0">
                                            <h3 id="info_5" class="fw-bold mb-1"></h3>
                                            <div class="text-small text-uppercase fw-bold op-8">Approved Cost</div>
                                        </div>
                                        <div class="col-1 pr-0">
                                            <h3 id="info_6" class="fw-bold mb-1"></h3>
                                            <div class="text-small text-uppercase fw-bold op-8">Expenditure</div>
                                        </div>
                                        <div class="col-1 pr-0">
                                            <h3 id="info_7" class="fw-bold mb-1"></h3>
                                            <div class="text-small text-uppercase fw-bold op-8">In Hand</div>
                                        </div>
                                        <div class="col-3 pr-0"></div>
                                        <div class="col-1 pl-0 text-right">
                                            <small><span id="info_8" class="fw-bold mb-1"></span></small>
                                            <div class="text-small text-uppercase fw-bold op-8">RIDF Loan</div>
                                        </div>
                                        <div class="col-1 pl-0 text-right">
                                            <small><span id="info_9" class="fw-bold mb-1"></span></small>
                                            <div class="text-small text-uppercase fw-bold op-8">DPR</div>
                                        </div>
                                        <div class="col-1 pl-0 text-right">
                                            <small><span id="info_10" class="fw-bold mb-1"></span></small>
                                            <div class="text-small text-uppercase fw-bold op-8">Contigency</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div id="prev" class="row">
                        <div class="col-md-12 mb-3 mt-3 badge-info">
                            <div class="form-group">
                                <label class="form-label">
                                    <h2 style="color: white;">Previous Requisition Information</h2>
                                </label>
                            </div>
                        </div>
                        <div class="table-responsive" style="overflow: auto;">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>RA</th>
                                        <th>Memo No</th>
                                        <th>Memo Date</th>
                                        <th>Ref No</th>
                                        <th>Physical Progress</th>
                                        <th>Claimed Amount</th>
                                        <th>DPR Amount</th>
                                        <th>Contigency Amount</th>
                                        <th>Approved Date</th>
                                        <th>Approved Claimed Amount</th>
                                        <th>Approved DPR Amount</th>
                                        <th>Approved Contigency Amount</th>

                                        <th>Claimed Expenditure</th>
                                        <th>DPR Expenditure</th>
                                        <th>Contigency Expenditure</th>

                                        <!-- <th>Expenditure</th> -->
                                        <th>Claimed Doc</th>
                                        <th>Approved Doc</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-12 mb-3 mt-3 badge-info">
                            <div class="row">
                                <div class="col-md-9 mt-2">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <h2 style="color: white;">Fund Requisition</h2>
                                        </label>
                                        <span class="pull-right" style="color: gold;">
                                            <h2 id="ra_id"></h2>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group form-inline">
                                        <label class="col-md-8 col-form-label reqd">Current Requisition</label>
                                        <div class="col-md-4 input-group" style="width: 65px !important;">
                                            <input id="iscurrent" name="iscurrent" type="checkbox" data-toggle="toggle" data-onstyle="danger" data-style="btn-round" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="requisition" class="col-md-6">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="reqd">Memo Date</label>
                                        <div class="input-group">
                                            <input type="text" id="memo_date" name="memo_date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" required value="">
                                            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="reqd">Memo No</label>
                                        <input type="text" id="memo_no" name="memo_no" class="form-control" required value="">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="reqd">Physical Progress</label>
                                        <div class="input-group mb-3">
                                            <input type="text" id="physical_progress" name="physical_progress" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label class="form-label reqd mr-2">Requisition Amount</label>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" id="claimed" name="claimed" value="1" class="selectgroup-input" checked>
                                                <span class="selectgroup-button">Claimed Amount</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" id="dpr" name="dpr" value="0" class="selectgroup-input">
                                                <span class="selectgroup-button">DPR Amount</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" id="contigency" name="contigency" value="0" class="selectgroup-input">
                                                <span class="selectgroup-button">Contigency Amount</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 claimed">
                                    <div class="form-group">
                                        <label class="reqd">Claimed Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="claimed_amt" name="claimed_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-4 claimed">
                                    <div class="form-group">
                                        <label id="claimed_info" style="color: mediumvioletred !important;"></label>
                                    </div>
                                </div>
                                <div class="col-md-4 dpr">
                                    <div class="form-group">
                                        <label class="reqd">DPR Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="dpr_amt" name="dpr_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-4 dpr">
                                    <div class="form-group">
                                        <label id="dpr_info" style="color: mediumvioletred !important;"></label>
                                    </div>
                                </div>
                                <div class="col-md-4 contigency">
                                    <div class="form-group">
                                        <label class="reqd">Contigency Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="contigency_amt" name="contigency_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8 mt-4 contigency">
                                    <div class="form-group">
                                        <label id="contigency_info" style="color: mediumvioletred !important;"></label>
                                    </div>
                                </div>
                            </div>
                            <div class="row approved">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="reqd">Approved Date</label>
                                        <div class="input-group">
                                            <input type="text" id="approved_date" name="approved_date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="">
                                            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 claimed">
                                    <div class="form-group">
                                        <label class="reqd">Approved Claimed Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="app_claimed_amt" name="app_claimed_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 dpr">
                                    <div class="form-group">
                                        <label class="reqd">Approved DPR Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="app_dpr_amt" name="app_dpr_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 contigency">
                                    <div class="form-group">
                                        <label class="reqd">Approved Contigency Amount</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Rs</span>
                                            </div>
                                            <input type="text" id="app_contigency_amt" name="app_contigency_amt" class="form-control" value="0">
                                            <div class="input-group-append">
                                                <span class="input-group-text">.00</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-6 approved">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Claimed Document<br>
                                            <span style="font-size: 10px; color: red;">(Max 2MB, Formats: pdf)</span>
                                        </label>
                                        <input type="file" id="claimed_doc" name="claimed_doc" class="form-control dropify" data-max-file-size="2M" accept="application/pdf">
                                    </div>
                                </div>



                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Approved Document<br>
                                            <span style="font-size: 10px; color: red;">(Max 2MB, Formats: pdf)</span>
                                        </label>
                                        <input type="file" id="approved_doc" name="approved_doc" class="form-control dropify" data-max-file-size="2M" accept="application/pdf">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="checkbox-container mt-4 terms">
                        <input type="checkbox" id="terms" name="terms" value="0">
                        <label class="reqd">I agree to the following:</label>
                        <ol type="a">
                            <li>Items of work have been executed as per the financial rules of the Government of West Bengal after observing the prescribed tender formalities.</li>
                            <li>Expenditure reported has actually been incurred & audited and recorded in the books of accounts of the concerned divitions which in certificated by the conceptent authority of the line department.</li>
                            <li>The physical progress made is as per the CPM/PERT chart and is satisfactory. (in case of unsatifactory physical progress/reasons are given here under)</li>
                        </ol>
                    </div>
                    <hr>
                    

                    <?php
                    $showSaveButton = false;
                    // Check if the condition to show the button is met for the first block
                    if ($selected->ridf_id == 0 && $role_id > 3) {
                        $showSaveButton = true;
                    }
                    // Check the condition for the list iteration
                    foreach ($list as $row) {
                        if ($role_id < 3 || !empty($row->approved_doc)) {
                            $showSaveButton = true;
                            break;
                        }
                    }
                    ?>
                    <?php if ($showSaveButton): ?>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="submit" id="submit" name="submit" value="Submit" class="btn btn-success ml-2" disabled>
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                    <?php endif; ?>





                    <input type="hidden" id="hdn_ra_id" name="hdn_ra_id" value="1">
                    <input type="hidden" name="id" value="0">
                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('templates/js/ridf_requisition.js') ?>"></script>
<script>
    var role_id = <?php echo $role_id; ?>;
    $(document).ready(function() {
        $('#prev').hide();
        $('#info').hide();
        $('.dpr').hide();
        $('.contigency').hide();

        if ($('#ridf_id').val() > 0) {
            get_work_info();
            get_prev_requisition_list();
        }

        if ($('#iscurrent').val() === '0') {
            $('.terms').hide();
            $('#submit').prop('disabled', false);
        }
    });




    // Validate DPR Amount
    $('#dpr_amt').on('input', function() {
        let inputVal = parseInt($(this).val()) || 0;
        let dprInfoText = $('#dpr_info').text();
        let total = parseInt(dprInfoText.split('Total:')[1].split('<br>')[0]) || 0;
        let claimed = parseInt(dprInfoText.split('Claimed:')[1]) || 0;
        let remaining = total - claimed;

        if (inputVal > remaining) {
            alert('DPR Amount cannot exceed remaining DPR: ' + remaining);
            $(this).val(0).focus();
        }
    });

    // Validate Contingency Amount
    $('#contigency_amt').on('input', function() {
        let inputVal = parseInt($(this).val()) || 0;
        let contInfoText = $('#contigency_info').text();
        let total = parseInt(contInfoText.split('Total:')[1].split('<br>')[0]) || 0;
        let claimed = parseInt(contInfoText.split('Claimed:')[1]) || 0;
        let remaining = total - claimed;

        if (inputVal > remaining) {
            alert('Contingency Amount cannot exceed remaining Contingency: ' + remaining);
            $(this).val(0).focus();
        }
    });


    function validatePDF(inputId) {
        const fileInput = document.getElementById(inputId);
        const file = fileInput.files[0];

        if (file) {
            const isPDF = file.type === 'application/pdf';
            const isValidSize = file.size <= 2 * 1024 * 1024; // 2MB

            if (!isPDF || !isValidSize) {
                alert('Only PDF files up to 2MB are allowed.');
                fileInput.value = ''; // Clear the file
                return false;
            }
        }
        return true;
    }
    document.getElementById('claimed_doc').addEventListener('change', () => {
        const validClaimed = validatePDF('claimed_doc');
        const validApproved = validatePDF('approved_doc');
        document.getElementById('submit').disabled = !(validClaimed && validApproved);
    });

    document.getElementById('approved_doc').addEventListener('change', () => {
        const validClaimed = validatePDF('claimed_doc');
        const validApproved = validatePDF('approved_doc');
        document.getElementById('submit').disabled = !(validClaimed && validApproved);
    });
    document.getElementById('submit').disabled = false;
</script>