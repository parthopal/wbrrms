<?php
defined('BASEPATH') or exit('No direct script access allowed');

$sqm = json_decode($sqm);
$district = json_decode($district);
$scheme = json_decode($scheme);
$selected = json_decode($selected);
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
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="qm_back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-10 text-center">
                                <h4><?= $title ?></h4>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('capex/bridge_qm_entry'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Name of SQM</label>
                                    <select id="sqm_id" name="sqm_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="">Select sqm</option>';
                                        foreach ($sqm as $row) {
                                            $_selected = ($selected->sqm_id > 0 && $selected->sqm_id == $row->id) || sizeof($sqm) == 1 ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District<span style="color: red; font-size: 20px">*</span></label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" required>
                                        <?php
                                        echo '<option value="" ' . ($selected->district_id == '' ? 'selected' : '') . '>--Select District--</option>';
                                        foreach ($district as $row) {
                                            $_selected = $selected->district_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Month</label>
                                    <select name="month" id="month" class="form-control dropdown">
                                        <option value="0">--Select month--</option>
                                        <option value="1" <?= $selected->month == '1' ? 'selected' : '' ?>>January</option>
                                        <option value="2" <?= $selected->month == '2' ? 'selected' : '' ?>>February</option>
                                        <option value="3" <?= $selected->month == '3' ? 'selected' : '' ?>>March</option>
                                        <option value="4" <?= $selected->month == '4' ? 'selected' : '' ?>>April</option>
                                        <option value="5" <?= $selected->month == '5' ? 'selected' : '' ?>>May</option>
                                        <option value="6" <?= $selected->month == '6' ? 'selected' : '' ?>>June</option>
                                        <option value="7" <?= $selected->month == '7' ? 'selected' : '' ?>>July</option>
                                        <option value="8" <?= $selected->month == '8' ? 'selected' : '' ?>>August</option>
                                        <option value="9" <?= $selected->month == '9' ? 'selected' : '' ?>>September</option>
                                        <option value="10" <?= $selected->month == '10' ? 'selected' : '' ?>>October</option>
                                        <option value="11" <?= $selected->month == '11' ? 'selected' : '' ?>>November</option>
                                        <option value="12" <?= $selected->month == '12' ? 'selected' : '' ?>>December</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year" id="year" class="form-control dropdown">
                                        <option value="0">--Select year--</option>
                                        <!-- <option value="2023" <?= $selected->year == '2023' ? 'selected' : '' ?>>2023</option> -->
                                        <option value="<?= date('Y') ?>" <?= $selected->year == date('Y') ? 'selected' : '' ?>><?= date('Y') ?> </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('capex/bridge_qm_save'); ?>

                        <div class="row">
                            <div class="col-md-12 text-right mb-2">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>#</th>
                                        <th>Bridge Name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Agency</th>
                                        <th>Overall Grade</th>
                                        <th>Work Started</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    if (sizeof($scheme) > 0) {
                                        foreach ($scheme as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="top" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->overall_grade . '</td>';
                                            echo '<td>' . ($row->wo_start_date != null ? date('d/m/Y', strtotime($row->wo_start_date)) : '') . '</td>';
                                            $checked = $row->selected > 0 ? 'checked' : '';
                                            echo '<td><p style="margin:20px; width: 80px"><input type="checkbox" id="chk_' . $row->id . '" name="chk[]" class="chk"  title="checkbox" value="' . $row->id . '" ' . $checked . '></p>
                                            </td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="district_id" value="<?= $selected->district_id ?>">
                        <input type="hidden" name="sqm_id" value="<?= $selected->sqm_id ?>">
                        <input type="hidden" name="month" value="<?= $selected->month ?>">
                        <input type="hidden" name="year" value="<?= $selected->year ?>">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#tbl').DataTable({
            dom: 'lBfrtip',
            processing: true,
            scrollY: '450px',
            scrollX: true,
            scrollCollapse: true,
            paging: false,
            responsive: true,
            stateSave: true,
            colReorder: true,
            fixedColumns: {
                left: 1,
                right: 1
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'CAPEX Bridge_qm_entry_' + $.now(),
                    title: 'SQM',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: 'CAPEX BRIDGE SQM',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (win) {
                        $(win.document.body)
                                .find('h1').css('text-align', 'center')
                                .css('font-size', '10pt')
                                .prepend(
                                        '<img src="' + baseURL + '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                                        );
                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit')
                                .css('margin', '50px auto');
                    }
                }
            ]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

    });

    function qm_back() {
        window.location.href = baseURL + '/capex/bridge_qm/';
    }
</script>
<script src="<?= base_url('templates/js/capex_qm.js') ?>"></script>