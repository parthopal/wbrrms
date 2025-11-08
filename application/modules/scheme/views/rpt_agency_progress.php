<?php
defined('BASEPATH') or exit('No direct script access allowed');
$category = json_decode($category);
$type = json_decode($type);
$selected = json_decode($selected);
$district = json_decode($district);
$list = json_decode($list);
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
                    <a href="<?= base_url('scheme/report/ridf') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                    <?php echo form_open('scheme/rpt_agency_progress/' . $sc); ?>
                    <div class="row">
                        <div class="col-md-3">
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
                            <div class="col-md-3">
                            <div class="form-group">
                                    <label>Tranche*</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="0">--All Tranche--</option>
                                        <?php
                                        foreach ($category as $row) {
                                            $_selected = $row->id == $selected->category_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                            <div class="form-group">
                                    <label>Project Type *</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="0">--All Project--</option>
                                        <?php
                                        foreach ($type as $row) {
                                            $_selected = $row->id == $selected->type_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Sl.</th>
                                        <th>District</th>
                                        <th>Agency</th>
                                        <th>Approved Project</th>
                                        <th>Approved Amount </th>
                                        <th>Tender Invited (No.)</th>
                                        <th>Tender Matured (No.)</th>
                                        <th>WO Issued (No.)</th>
                                        <th>WO Amount (Crore Rs.)</th>
                                        <th>0-10% Progress</th>
                                        <th>10-30% Progress</th>
                                        <th>30-60% Progress (No.)</th>
                                        <th>60-99% Progress (No.)</th>
                                        <th>Ongoing(No)</th>
                                        <th>Ongoing Amount (Crore Rs.)</th>
                                        <th>Completed (No.)</th>
                                        <th>Completed Amount (Crore Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->approved_projects . '</td>';
                                            echo '<td>' . $row->approved_amount . '</td>';
                                            echo '<td>' . $row->tender_invited . '</td>';
                                            echo '<td>' . $row->tender_matured . '</td>';
                                            echo '<td>' . $row->wo_issued . '</td>';
                                            echo '<td>' . $row->wo_amount . '</td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '<td></td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                    <tr>
                                    <th></th>
                                        <th>Total</th>
                                        <th></th>
                                        <th><?= array_sum(array_column($list, 'approved_projects')) ?></th>
                                        <th><?= array_sum(array_column($list, 'approved_amount')) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_invited')) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_matured')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_issued')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_amount')) ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
     var currentdate = new Date();
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
                left: 3
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'agency_progress_report_' + $.now(),
                    // title: 'AGENCY-WISE PROGRESS REPORT',
                    title: 'AGENCY-WISE PROGRESS REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'AGENCY-WISE PROGRESS REPORT',
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