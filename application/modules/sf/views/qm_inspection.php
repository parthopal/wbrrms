<?php
defined('BASEPATH') or exit('No direct script access allowed');
$sqm = json_decode($sqm);
$list = json_decode($list);
$selected = json_decode($selected);
$month = array(
    '01' => 'january',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'Auguest',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
);
$month = $role_id < 4 ? $month : array_intersect_key($month, array_flip(array($selected->month)));
$year = array(
    '2023' => '2023',
    '2024' => '2024'
);
$year = $role_id < 4 ? $year : array_intersect_key($year, array_flip(array($selected->year)));
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
                    <a href="<?= base_url('srrp/qm_inspection_report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('srrp/qm_inspection'); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Name of SQM</label>
                                    <select id="sqm_id" name="sqm_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="">All sqm</option>';
                                        foreach ($sqm as $row) {
                                            $_selected = ($selected->sqm_id > 0 && $selected->sqm_id == $row->id) || sizeof($sqm) == 1 ? 'selected' : '';
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
                                        <?php
                                        foreach ($month as $k => $v) {
                                            $_selected = ($selected->month > 0 && $selected->month == $k) || sizeof($month) == 1 ? 'selected' : '';
                                            echo '<option value="' . $k . '" ' . $_selected . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select name="year" id="year" class="form-control dropdown">
                                        <option value="0">--Select Year--</option>
                                        <?php
                                        foreach ($year as $k => $v) {
                                            $_selected = ($selected->year > 0 && $selected->year == $k) || sizeof($year) == 1 ? 'selected' : '';
                                            echo '<option value="' . $k . '" ' . $_selected . '>' . $v . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <!-- <select name="year" id="year" class="form-control dropdown">
                                        <option value="0">--Select year--</option>
                                        <option value="2023" <?= $selected->year == '2023' ? 'selected' : '' ?>>2023</option>
                                        <option value="2024" <?= $selected->year == '2024' ? 'selected' : '' ?>>2024</option>
                                    </select> -->
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
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Road name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Length</th>
                                        <th>Agency</th>
                                        <th>Progress</th>
                                        <th>Sqm Name</th>
                                        <th>Assigned Enginner</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->sqm . '<br><i>(' . $row->sqm_mobile . ')</i></td>';
                                            echo '<td>' . $row->pe . '<br><i>(' . $row->mobile . ')</i></td>';
                                            $disabled = $row->status == 0 ? 'disabled' : '';
                                            echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="start(\'' . $row->id . '\')"  title="start"><i class="fas fa-plus"></i></button>&nbsp;<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="submit(\'' . $row->id . '\')"  title="submit" ' . $disabled . '><i class="fas fa-pen"></i></button></p></td>';
                                            echo '</tr>';
                                        }
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
                    filename: 'qmview_' + $.now(),
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
                    title: 'QM-WISE REPORT',
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
    function start(id) {
        window.location.href = baseURL + "/srrp/qm_start/" + id;
    }
    function submit(id) {
        window.location.href = baseURL + "/srrp/qm_submit/" + id;
    } 
</script>