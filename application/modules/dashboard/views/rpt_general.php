<?php
defined('BASEPATH') or exit('No direct script access allowed');

$role = json_decode($role_id);
$district = json_decode($district);
$block = json_decode($block);
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
                        <?php echo form_open('dashboard/rpt_general'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Scheme Type</label>
                                    <select id="scheme_id" name="scheme_id" class="form-control dropdown" required>
                                        <option value="">Select Scheme Type</option>
                                        <option value="1" <?= $selected->scheme_id == 1 ? 'selected' : '' ?>>Pathashree-II</option>
                                        <option value="2" <?= $selected->scheme_id == 2 ? 'selected' : '' ?>>Pathashree-III</option>
                                        <option value="3" <?= $selected->scheme_id == 3 ? 'selected' : '' ?>>SF</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" required>
                                        <?php
                                        echo '<option value="">--Select District--</option>';
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
                                    <label>Block</label>
                                    <select id="block_id" name="block_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Block--</option>';
                                        foreach ($block as $row) {
                                            $_selected = ($selected->block_id > 0 && $selected->block_id == $row->id) ? 'selected' : '';
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
                                        <th>Block</th>
                                        <th>Agency</th>
                                        <th>Road Name</th>
                                        <th>Approved Length (Km.)</th>
                                        <th>Approved Amount (Lakh Rs.)</th>
                                        <th>Tender Invited</th>
                                        <th>Tender Matured</th>
                                        <th>WO Issued</th>
                                        <th>WO Length (Km.)</th>
                                        <th>WO Amount (Lakh Rs.)</th>
                                        <th>0-25% Progress (No.)</th>
                                        <th>26-50% Progress (No.)</th>
                                        <th>51-75% Progress (No.)</th>
                                        <th>76-99% Progress (No.)</th>
                                        <th>Ongoing (No.)</th>
                                        <th>Ongoing Amount (Lakh Rs.)</th>
                                        <th>Completed (No.)</th>
                                        <th>Completed Length (Km.)</th>
                                        <th>Completed Amount (Lakh Rs.)</th>
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
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . round($row->approved_amount / 100000, 2) . '</td>';
                                            $tender_invited = $row->tender_invited == 1 ? '<span> Y </span> ' : '<span> N </span>';
                                            echo '<td style="text-align: center;">' . $tender_invited . '</td>';
                                            $tender = $row->tender_matured == 1 ? '<span> Y </span> ' : '<span> N </span>';
                                            echo '<td style="text-align: center;">' . $tender . '</td>';
                                            $work_oder = $row->wo_issued == 1 ? '<span> Y </span> ' : '<span> N </span>';
                                            echo '<td style="text-align: center;">' . $work_oder . '</td>';
                                            echo '<td>' . $row->wo_length . '</td>';
                                            echo '<td>' . round($row->wo_amount / 100000, 2) . '</td>';
                                            echo '<td>' . $row->progress_25 . '</td>';
                                            echo '<td>' . $row->progress_50 . '</td>';
                                            echo '<td>' . $row->progress_75 . '</td>';
                                            echo '<td>' . $row->progress_99 . '</td>';
                                            echo '<td>' . $row->ongoing . '</td>';
                                            echo '<td>' . $row->ongoing_amount . '</td>';
                                            echo '<td>' . $row->completed . '</td>';
                                            echo '<td>' . $row->completed_length . '</td>';
                                            echo '<td>' . round($row->completed_amount / 100000, 2) . '</td>';
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
                                        <th><?= array_sum(array_column($list, 'approved_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'approved_amount')) / 100000, 2) ?></th>
                                        <th style="text-align: center;"><?= array_sum(array_column($list, 'tender_invited')) ?></th>
                                        <th style="text-align: center;"><?= array_sum(array_column($list, 'tender_matured')) ?></th>
                                        <th style="text-align: center;"><?= array_sum(array_column($list, 'wo_issued')) ?></th>
                                        <th><?= array_sum(array_column($list, 'wo_length')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'wo_amount')) / 100000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_25')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_50')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_75')) ?></th>
                                        <th><?= array_sum(array_column($list, 'progress_99')) ?></th>
                                        <th><?= array_sum(array_column($list, 'ongoing')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'ongoing_amount')) / 100000, 2) ?></th>
                                        <th><?= array_sum(array_column($list, 'completed')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'completed_length')), 2) ?></th>
                                        <th> <?= round(array_sum(array_column($list, 'completed_amount')) / 100000, 2) ?> </th>
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
                left: 5
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'overview_report_' + $.now(),
                    title: 'OVERVIEW REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
                            + currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                    }
                }
            ]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
        $('#district_id').on('change', function (e) {
            e.preventDefault();
            get_block_list();
        });
        $('#search_general').on('click', function (e) {
            e.preventDefault();
            rpt_general();
        });
    });
    function get_block_list() {
        $.ajax({
            url: baseURL + '/dashboard/get_block_list',
            type: 'get',
            data: {district_id: $('#district_id').val()},
            dataType: 'json',
            async: false
        }).done(function (data) {
            $('#block_id').empty();
            if (data.length > 0) {
                $('#block_id').append($('<option>', {value: '0', text: '--All Block--'}));
                $.each(data, function (i, item) {
                    $('#block_id').append($('<option>', {value: item.id, text: item.name}));
                });
            } else if ($('#district_id').val() === 0) {
                $('#block_id').append($('<option>', {value: '0', text: '--All Block--'}));
            } else {
                $('#block_id').append($('<option>', {value: '', text: '--Select Block--'}));
            }
        });
    }
</script>