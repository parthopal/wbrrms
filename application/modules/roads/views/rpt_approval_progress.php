<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
$ac = json_decode($ac);
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
                        <?php echo form_open('roads/rpt_approval_progress'); ?>
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
                                        <th>Agency</th>
                                        <th>Total Work (No.)</th>
                                        <th>Total Length (Km.)</th>
                                        <th>Total Amount (Lakh Rs.)</th>
                                        <th>Not Implemented (No.)</th>
                                        <th>Yet to Start (No.)</th>
                                        <th>On-going Survey (No.)</th>
                                        <th>Survey Completed (No.)</th>
                                        <th>DM Level (No.)</th>
                                        <th>SE Level (No.)</th>
                                        <th>State Level (No.)</th>
                                        <th>Approved (No.)</th>
                                        <th>Approved Length (Km.)</th>
                                        <th>Approved Amount (Lakh Rs.)</th>
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
                                            echo '<td>' . $row->total_work . '</td>';
                                            echo '<td>' . $row->total_length . '</td>';
                                            echo '<td>' . $row->total_amount . '</td>';
                                            echo '<td>' . $row->not_implemented . '</td>';
                                            echo '<td>' . $row->yet_to_start . '</td>';
                                            echo '<td>' . $row->on_going_survey . '</td>';
                                            echo '<td>' . $row->survey_completed . '</td>';
                                            echo '<td>' . $row->dm_level . '</td>';
                                            echo '<td>' . $row->se_level . '</td>';
                                            echo '<td>' . $row->state_level . '</td>';
                                            echo '<td>' . $row->approved . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . $row->approved_amount . '</td>';
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
                                        <th><?= array_sum(array_column($list, 'total_work')) ?></th>
                                        <th><?= array_sum(array_column($list, 'total_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'total_amount')) / 100, 2) ?> Cr.</th>
                                        <th><?= array_sum(array_column($list, 'not_implemented')) ?></th>
                                        <th><?= array_sum(array_column($list, 'yet_to_start')) ?></th>
                                        <th><?= array_sum(array_column($list, 'on_going_survey')) ?></th>
                                        <th><?= array_sum(array_column($list, 'survey_completed')) ?></th>
                                        <th><?= array_sum(array_column($list, 'dm_level')) ?></th>
                                        <th><?= array_sum(array_column($list, 'se_level')) ?></th>
                                        <th><?= array_sum(array_column($list, 'state_level')) ?></th>
                                        <th><?= array_sum(array_column($list, 'approved')) ?></th>
                                        <th><?= array_sum(array_column($list, 'approved_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'approved_amount')) / 100, 2) ?> Cr.</th>
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
                left: 3
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'rural_roads(2025)_agency_progress_report_' + $.now(),
                    // title: 'AGENCY-WISE PROGRESS REPORT',
                    title: 'RURAL Roads(2025) AGENCY-WISE PROGRESS REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
