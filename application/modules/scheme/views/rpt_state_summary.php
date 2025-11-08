<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Sl.</th>
                                        <th>District</th>
                                        <th>Agency</th>
                                        <th>Project ID</th>
                                        <th>Project Name</th>
                                        <th>RIDF Tranche</th>
                                        <th>Project Type</th>
                                        <th>Road length</th>
                                        <th>Approved Amount</th>
                                        <th>Admin. Approval date</th>
                                        <th>Tender inviting date</th>
                                        <th>No. of calls for tender maturing </th>
                                        <th>Tender maturing date</th>
                                        <th>Work order issue date</th>
                                        <th>Work start date</th>
                                        <th>Physical progress (%)</th>
                                        <th>Financial Progress</th>
                                        <th>Expenditure made (Crore Rs.)</th>
                                        <th>Date of physical completion</th>
                                        <th>Date of financial completion</th>
                                        <th>Whether PCR submitted</th>
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
                                            echo '<td>' . $row->project_id . '</td>';
                                            echo '<td>' . $row->project_name . '</td>';
                                            echo '<td>' . $row->ridf_tranche . '</td>';
                                            echo '<td>' . $row->project_type . '</td>';
                                            echo '<td>' . $row->road_length . '</td>';
                                            echo '<td>' . $row->approved_amount . '</td>';
                                            echo '<td>' . $row->admin_approval_date . '</td>';
                                            echo '<td></td>';
                                            echo '<td>' . $row->tender_maturing . '</td>';
                                            echo '<td></td>';
                                            echo '<td>' . $row->workorder_issued_date . '</td>';
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
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
                left: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'state_summary_report_' + $.now(),
                    // title: 'STATE SUMMARY REPORT',
                    title: 'STATE SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'STATE SUMMARY REPORT',
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