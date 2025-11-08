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
                    <a href="<?= base_url('roads/report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('roads/rpt_state_summary'); ?>
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
                                        <th>Cost for Road Works including <br> Protective work, CD work, etc. (Rs.)</th>
                                        <th>Applicable GST@18% (Rs.)</th>
                                        <th>Labour welfare cess @1% (Rs.)</th>
                                        <th>Total Estimated Cost <br> Excluding Contingency (Rs.)</th>
                                        <th>Per km Estimated Cost <br> excluding Contingency (Rs. in Lakh)</th>
                                        <th>Contingency/Agency Fee for <br> MBL & WBAICL @3% (Rs.)</th>
                                        <th>Vetted Estimated Cost </br> including contingency (Rs.)</th>
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
                                            echo '<td>' . round($row->approved_amount, 2) . '</td>';
                                            echo '<td>' . round($row->gst_18_percent, 2) . '</td>';
                                            echo '<td>' . round($row->cess_1_percent, 2) . '</td>';
                                            echo '<td>' . round($row->total_estimated_cost_excl_contingency, 2) . '</td>';
                                            echo '<td>' . round($row->per_km, 2) . '</td>';
                                            echo '<td>' . round($row->contingency_agency_fee_3_percent, 2) . '</td>';
                                            echo '<td>' . round($row->vetted_estimated_cost_incl_contingency, 2) . '</td>';
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
                                        <th><?= round(array_sum(array_column($list, 'approved_length')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'approved_amount')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'gst_18_percent')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'cess_1_percent')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'total_estimated_cost_excl_contingency')), 2) ?></th>
                                        <?php
                                        $total_cost = array_sum(array_column($list, 'total_estimated_cost_excl_contingency'));
                                        $total_length = array_sum(array_column($list, 'approved_length'));
                                        $per_km = $total_length > 0 ? ($total_cost / $total_length) : 0;
                                        ?>
                                        <th><?= round($per_km / 100000, 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'contingency_agency_fee_3_percent')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'vetted_estimated_cost_incl_contingency')), 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_invited')) ?></th>
                                        <th><?= array_sum(array_column($list, 'tender_matured')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_issued')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'wo_length')), 2) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'wo_amount')) / 10000000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_25')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_50')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_75')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_99')) ?></th>
                                        <th><?= array_sum(array_column($list, 'ongoing')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'ongoing_amount')) / 10000000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'completed')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'completed_length')), 2) ?></th>
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
    $(document).ready(function() {
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
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'Rural_roads(2025)_state_summary_report_' + $.now(),
                    title: 'RURAL ROADS (2025) STATE SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
                        currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function(xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                    }
                },
                {
                    extend: 'print',
                    text: 'Print',
                    title: '',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function(win) {
                        // Remove default title
                        $(win.document.body).find('h1').remove();

                        // Add header: logo + title in one line (left aligned)
                        $(win.document.body).prepend(
                            '<div style="display:flex; align-items:center; margin-bottom:15px;">' +
                            '<img src="' + baseURL + '/templates/img/pathashree.jpg" ' +
                            'style="height:45px; margin-right:12px;" />' +
                            '<h2 style="margin:0; font-size:13pt; font-weight:bold;">' +
                            'RURAL ROADS (2025) – STATE SUMMARY REPORT' +
                            '</h2>' +
                            '</div>'
                        );

                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', '10pt')
                            .css('border-collapse', 'collapse')
                            .css('width', '100%');
                        $(win.document.body).find('table thead tr th')
                            .css('background-color', '#f2f2f2')
                            .css('text-align', 'center')
                            .css('padding', '6px');

                        $(win.document.body).find('table tbody tfoot tr td')
                            .css('padding', '4px 6px')
                            .css('text-align', 'center');
                    }
                }

            ]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>