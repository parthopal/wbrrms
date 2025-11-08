<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
$ac = json_decode($ac);
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
                    <a href="<?= base_url('ssm/report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('ssm/rpt_state_summary'); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Assembly Constituency</label>
                                    <select id="ac_id" name="ac_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All AC--</option>';
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
                                        <th>Approved Work (No.)</th>
                                        <th>Approved Length (Km.)</th>
                                        <th>Approved Amount (Crore Rs.)</th>
                                        <th>Tender Invited (No.)</th>
                                        <th>Tender Matured (No.)</th>
                                        <th>WO Issued (No.)</th>
                                        <th>WO Length (Km.)</th>
                                        <th>WO Amount (Crore Rs.)</th>
                                        <th>0-25% Progress (No.)</th>
                                        <th>26-50% Progress (No.)</th>
                                        <th>51-75% Progress (No.)</th>
                                        <th>76-99% Progress (No.)</th>
                                        <th>Ongoing (No.)</th>
                                        <!-- <th>Ongoing Length (Km.)</th> -->
                                        <th>Ongoing Amount (Crore Rs.)</th>
                                        <th>Completed (No.)</th>
                                        <th>Completed Length (Km.)</th>
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
                                            echo '<td>' . $row->approved_scheme . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . round($row->approved_amount / 10000000, 2) . '</td>';
                                            echo '<td>' . $row->tender_invited . '</td>';
                                            echo '<td>' . $row->tender_matured . '</td>';
                                            echo '<td>' . $row->wo_issued . '</td>';
                                            echo '<td>' . $row->wo_length . '</td>';
                                            echo '<td>' . round($row->wo_amount / 10000000, 2) . '</td>';
                                            echo '<td>' . $row->progress_25 . '</td>';
                                            echo '<td>' . $row->progress_50 . '</td>';
                                            echo '<td>' . $row->progress_75 . '</td>';
                                            echo '<td>' . $row->progress_99 . '</td>';
                                            echo '<td>' . $row->ongoing . '</td>';
                                            // echo '<td>' . $row->ongoing_length . '</td>';
                                            echo '<td>' . round($row->ongoing_amount / 10000000, 2) . '</td>';
                                            echo '<td>' . $row->completed . '</td>';
                                            echo '<td>' . $row->completed_length . '</td>';
                                            echo '<td>' . round($row->completed_amount / 10000000, 2) . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                    <tr>
                                        <th></th>
                                        <th>Total</th>
                                        <th><?= array_sum(array_column($list, 'approved_scheme')) ?></th>
                                        <th><?= array_sum(array_column($list, 'approved_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'approved_amount')) / 10000000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_invited')) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_matured')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_issued')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'wo_amount')) / 10000000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_25')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_50')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_75')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_99')) ?></th>
                                        <th><?= array_sum(array_column($list, 'ongoing')) ?></th>
                                        <!-- <th><?= array_sum(array_column($list, 'ongoing_length')) ?></th> -->
                                        <th><?= round(array_sum(array_column($list, 'ongoing_amount')) / 10000000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'completed')) ?></th>
                                        <th><?= array_sum(array_column($list, 'completed_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'completed_amount')) / 10000000, 2) ?></th>
                                    </tr>
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
                left: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-III_state_summary_report_' + $.now(),
                    title: 'PATHASHREE-III STATE SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'PATHASHREE-III_STATE SUMMARY REPORT',
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