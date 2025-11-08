<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$selected = json_decode($selected);
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
                    <a href="<?= base_url('srrp/report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('srrp/rpt_agency_wise_updated'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Agency</label>
                                    <select id="agency" name="agency" class="form-control dropdown">
                                        <option value="" <?= $selected->agency == '' ? 'selected' : '' ?>>--All--</option>
                                        <option value="BLOCK" <?= $selected->agency == 'BLOCK' ? 'selected' : '' ?>>BLOCK</option>
                                        <option value="ZP" <?= $selected->agency == 'ZP' ? 'selected' : '' ?>>ZP</option>
                                        <option value="SRDA" <?= $selected->agency == 'SRDA' ? 'selected' : '' ?>>SRDA</option>
                                        <option value="MBL" <?= $selected->agency == 'MBL' ? 'selected' : '' ?>>MBL</option>
                                        <option value="AGRO" <?= $selected->agency == 'AGRO' ? 'selected' : '' ?>>AGRO</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--ALL DISTRICT--</option>';
                                        foreach ($district as $row) {
                                            $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select id="status_id" name="status_id" class="form-control dropdown">
                                        <option value="0" <?= $selected->status_id == 0 ? 'selected' : '' ?>>--All--</option>
                                        <option value="1" <?= $selected->status_id == 1 ? 'selected' : '' ?>>Incompleteed</option>
                                        <option value="2" <?= $selected->status_id == 2 ? 'selected' : '' ?>>Completed</option>
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
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Sl.</th>
                                        <th>District</th>
                                        <th>Agency</th>
                                        <th>Block</th>
                                        <th>Road Name</th>
                                        <th>Length (Km.)</th>
                                        <th>Approve Cost (in lakh)</th>
                                        <th>Awarded Cost (in lakh)</th>
                                        <th>Cost Invloved (in lakh)</th>
                                        <th>Physical Progress </th>
                                        <th>Finalcial Progress</th>
                                        <th>Completion Date</th>
                                        <th>Bill Paid (in lakh)</th>
                                        <th>Bill Due (in lakh)</th>
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
                                            echo '<td>' . $row->block . '</td>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . round($row->cost / 100000, 2) . '</td>';
                                            echo '<td>' . round($row->awarded_cost / 100000, 2) . '</td>';
                                            echo '<td>' . round($row->cost_involved / 100000, 2) . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->financial_progress . '</td>';
                                            $complete_date = $row->work_completion_date == NULL ? '' : date('d/m/Y', strtotime($row->work_completion_date));
                                            echo '<td>' . $complete_date . '</td>';
                                            echo '<td>' . round($row->bill_paid / 100000, 2) . '</td>';
                                            echo '<td>' . round($row->bill_due / 100000, 2) . '</td>';
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
                                        <th><?= array_sum(array_column($list, 'length')) ?></th>
                                        <th><?= array_sum(array_column($list, 'cost')) ?></th>
                                        <th><?= array_sum(array_column($list, 'awarded_cost')) ?></th>
                                        <th><?= array_sum(array_column($list, 'cost_involved')) ?></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><?= array_sum(array_column($list, 'bill_due')) ?></th>
                                        <th><?= array_sum(array_column($list, 'bill_paid')) ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- <input type="hidden" id="id" value="<?= $selected->id ?>" /> -->
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
                left: 3,
                right: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-II_agency_wise_updated_report_' + $.now(),
                    title: 'PATHASHREE-II AGENCY WISE UPDATED REPORT ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'PATHASHREE-II AGENCY WISE UPDATED REPORT',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function(win) {
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
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        $('#district_id').on('change', function(e) {
            e.preventDefault();
            get_block_list();
        });

        function get_block_list() {
            $.ajax({
                url: baseURL + "/srrp/get_block_list",
                type: "get",
                data: {
                    district_id: $("#district_id").val()
                },
                dataType: "json",
                async: false,
            }).done(function(data) {
                $("#block_id").empty();
                if (data.length > 0) {
                    $("#block_id").append(
                        $("<option>", {
                            value: "0",
                            text: "--All Block--"
                        })
                    );
                    $.each(data, function(i, item) {
                        $("#block_id").append(
                            $("<option>", {
                                value: item.id,
                                text: item.name
                            })
                        );
                    });
                } else if ($("#district_id").val() === 0) {
                    $("#block_id").append(
                        $("<option>", {
                            value: "0",
                            text: "--All Block--"
                        })
                    );
                } else {
                    $("#block_id").append(
                        $("<option>", {
                            value: "",
                            text: "--Select Block--"
                        })
                    );
                }
            });
        }
    });
</script>