<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$survey = $survey != '' ? json_decode($survey) : '';
$selected = json_decode($selected);
$style = $role_id > 2 ? 'style="display: none;"' : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <button type="button" class="btn btn-success btn-round" onclick="add(0)">
                        <i class="fa fa-plus"></i> &nbsp; Add New
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
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
                                <div class="form-group mt-4">
                                    <div class="selectgroup selectgroup-secondary selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0" class="selectgroup-input" checked>
                                            <span class="selectgroup-button selectgroup-button-icon" selected title="Not approved"><i class="fa fa-times"></i></span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon" title="approved"><i class="fa fa-check"></i></span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="-1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon"  title="Not implemented"><i class="fas fa-minus-circle"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <?= $subheading ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Work Name</th>
                                        <th>Assembly Constituency</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>GP</th>
                                        <th>Ref No</th>
                                        <th>Implementing Agency</th>
                                        <th>Type of Work</th>
                                        <th>Type of Road</th>
                                        <th>Status</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($survey as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->gp . '</td>';
                                        echo '<td>' . $row->ref_no . '</td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->work_type . '</td>';
                                        echo '<td>' . $row->road_type . '</td>';
                                        if ($row->isactive == -1) {
                                            echo '<td> Not Implemented </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 6) {
                                            echo '<td> Approved </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 5) {
                                            echo '<td> State Admin Level </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 4) {
                                            echo '<td> SE Level </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 3) {
                                            echo '<td> DM Level </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 2) {
                                            echo '<td> Survey Completed </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 1) {
                                            echo '<td> Ongoing Survey </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 0) {
                                            echo '<td> Survey Not Started </td>';
                                        }
//                                        $remove = $role_id < 4 && $row->agency == null ? '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove(' . $row->id . ')"  title="Remove"><i class="fas fa-times pointer"></i></button></p>' : '';
//                                        echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>' . $remove . '</td>';
                                        echo '<td><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var role_id = <?= $role_id ?>;
    $(document).ready(function () {
        var currentdate = new Date();
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
                left: 2,
                right: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-III_master_' + $.now(),
                    title: 'PATHASHREE-III MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
                            + currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
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
                    title: 'PATHASHREE-III MASTER',
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
</script>
<script src="<?= base_url('templates/js/ssm.js') ?>"></script>