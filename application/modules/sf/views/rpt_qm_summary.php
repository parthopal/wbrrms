<?php
defined('BASEPATH') or exit('No direct script access allowed');

// $selected = json_decode($selected);
$district = json_decode($district);
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
                    <a href="<?= base_url('sf/report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Start Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="start_date" name="start_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>End Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="end_date" name="end_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" id="search_qm" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th rowspan="2">District</th>
                                        <th rowspan="2">Total</th>
                                        <th rowspan="2">S</th>
                                        <th rowspan="2">SRI</th>
                                        <th rowspan="2">U</th>
                                        <th colspan="4">ZP</th>
                                        <th colspan="4">SRDA</th>
                                        <th colspan="4">BLOCK</th>
                                        <th colspan="4">MBL</th>
                                        <th colspan="4">AGRO</th>
                                    </tr>
                                    <tr>
                                        <th>Total</th>
                                        <th>S</th>
                                        <th>SRI</th>
                                        <th>U</th>
                                        <th>Total</th>
                                        <th>S</th>
                                        <th>SRI</th>
                                        <th>U</th>
                                        <th>Total</th>
                                        <th>S</th>
                                        <th>SRI</th>
                                        <th>U</th>
                                        <th>Total</th>
                                        <th>S</th>
                                        <th>SRI</th>
                                        <th>U</th>
                                        <th>Total</th>
                                        <th>S</th>
                                        <th>SRI</th>
                                        <th>U</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($district) && sizeof($district) > 0) {
                                        $i = 1;
                                        foreach ($district as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $row->name . '</td>';

                                            echo '<td id="grand_total_' . $row->id . '"></td>';
                                            echo '<td id="s_total_' . $row->id . '"></td>';
                                            echo '<td id="sri_total_' . $row->id . '"></td>';
                                            echo '<td id="u_total_' . $row->id . '"></td>';

                                            echo '<td id="zp_total_' . $row->id . '"></td>';
                                            echo '<td id="zp_s_' . $row->id . '"></td>';
                                            echo '<td id="zp_sri_' . $row->id . '"></td>';
                                            echo '<td id="zp_u_' . $row->id . '"></td>';

                                            echo '<td id="srda_total_' . $row->id . '"></td>';
                                            echo '<td id="srda_s_' . $row->id . '"></td>';
                                            echo '<td id="srda_sri_' . $row->id . '"></td>';
                                            echo '<td id="srda_u_' . $row->id . '"></td>';

                                            echo '<td id="block_total_' . $row->id . '"></td>';
                                            echo '<td id="block_s_' . $row->id . '"></td>';
                                            echo '<td id="block_sri_' . $row->id . '"></td>';
                                            echo '<td id="block_u_' . $row->id . '"></td>';

                                            echo '<td id="mbl_total_' . $row->id . '"></td>';
                                            echo '<td id="mbl_s_' . $row->id . '"></td>';
                                            echo '<td id="mbl_sri_' . $row->id . '"></td>';
                                            echo '<td id="mbl_u_' . $row->id . '"></td>';

                                            echo '<td id="agro_total_' . $row->id . '"></td>';
                                            echo '<td id="agro_s_' . $row->id . '"></td>';
                                            echo '<td id="agro_sri_' . $row->id . '"></td>';
                                            echo '<td id="agro_u_' . $row->id . '"></td>';
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
                    filename: 'sfo_qm_summary_report_' + $.now(),
                    title: 'STATE FUND & OTHERS QUALITY MONITORING SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'STATE FUND & OTHERS QUALITY MONITORING SUMMARY REPORT',
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
<script src="<?= base_url('templates/js/srrp.js') ?>"></script>
