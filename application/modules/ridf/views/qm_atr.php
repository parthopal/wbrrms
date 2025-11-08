<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
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
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('ssm/qm_atr'); ?>
                        <div class="row">
                            <div class="col-md-3">
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year" id="year" class="form-control dropdown">
                                        <option value="0">--Select year--</option>
                                        <option value="2023" <?= $selected->year == '2023' ? 'selected' : '' ?>>2023</option>
                                        <option value="2024" <?= $selected->year == '2024' ? 'selected' : '' ?>>2024</option>
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
                        <?php echo form_open('ssm/qm_save_atr_review'); ?>
                        <?php
                        if($role_id < 4) {
                        ?>
                        <div class="row" style="background-color: whitesmoke"id="div_hide">
                            <div class="col-md-4 mt-4 text-center">
                                <input type="radio" id="accept" name="atr" value="1">&nbsp;Accept
                                <input type="radio" id="reject" name="atr" value="-1">&nbsp;Reject
                                <input type="radio" id="modification" name="atr" value="0">&nbsp;Modification
                            </div>
                            <div class="col-md-6"> 
                                <div class="form-group form-inline"> 
                                    <label class="col-md-2" for="remarks">Remarks</label> 
                                    <div class="col-md-10">
                                        <textarea class="form-control" name="remarks" id="remarks" rows="2" style="width: 100%"></textarea>
                                    </div>
                                </div> 
                            </div> 
                            <div class="col-md-2 mt-3">
                                <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save_atr_review"></i> &nbsp; <span>SAVE</span>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <?php
                        }
                        ?>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <?php
                                        if($role_id < 4) {
                                            echo '<th></th>';
                                        }
                                        ?>
                                        <th>Road name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Length</th>
                                        <th>Agency</th>
                                        <th>Progress</th>
                                        <th>Assigned SQM</th>
                                        <th>SQM Report</th>
                                        <th>Overall Grade</th>
                                        <th>ATR</th>
                                        <?php
                                        if($role_id > 4) {
                                       echo  '<th>Action</th>';
                                    }
                                    ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            if($role_id < 4) {
                                            echo '<td><input type="radio" name="id" value="' . $row->id . '"></td>';
                                            }
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->sqm . '<br><i>(' . $row->mobile . ')</i></td>';
                                            $document = strlen($row->document) > 0 ? '<a href="' . base_url($row->document) . '" target="_blank"><button class="btn btn-icon btn-round btn-sm btn-primary" title="document"><i class="fas fa-file-pdf"></i></button></a>' : '';
                                            echo '<td>' . $document . '</td>';
                                            echo '<td>' . $row->overall_grade . '</td>';
                                            $atr = strlen($row->atr) > 0 ? '<a href="' . base_url($row->atr) . '" target="_blank"><button class="btn btn-icon btn-round btn-sm btn-primary" title="Document"><i class="fas fa-file-pdf"></i></button></a>' : '';
                                            echo '<td>' . $atr . '</td>';
                                            if($role_id > 4){
                                            echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="action(\'' . $row->id . '\')"  title="action"><i class="fas fa-plus"></i></button></p></td>';
                                            }
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
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
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-III_qmview_' + $.now(),
                    title: 'QM-WISE REPORT',
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
                    title: 'PATHASHREE-III QM-WISE REPORT',
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
    function action(id) {
        window.location.href = baseURL + "/ssm/qm_action/" + id;
    }
</script>
<!-- <script src="<?= base_url('templates/js/ssm_qm.js') ?>"></script> -->