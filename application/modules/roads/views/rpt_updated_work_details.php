<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
$list = json_decode($list);
// print_r($list);
// exit;
?>
<!-- Somnath Chakraborty -->
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
                        <?php echo form_open('roads/rpt_updated_work_details'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="">--ALL District--</option>';
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Implementing Agency</label>
                                    <select id="agency" name="agency" class="form-control dropdown" data-live-search="true">
                                        <option value="">--ALL Agency--</option>
                                        <option value="ZP" <?= $selected->agency == 'ZP' ? 'selected' : '' ?>>ZP</option>
                                        <option value="BLOCK" <?= $selected->agency == 'BLOCK' ? 'selected' : '' ?>>BLOCK</option>
                                        <option value="SRDA" <?= $selected->agency == 'SRDA' ? 'selected' : '' ?>>SRDA</option>
                                        <option value="MBL" <?= $selected->agency == 'MBL' ? 'selected' : '' ?>>MBL</option>
                                        <option value="AGRO" <?= $selected->agency == 'AGRO' ? 'selected' : '' ?>>AGRO</option>
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
                                        <th>Road Name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Agency</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Approved <br>Lenght(KM) &nbsp;</th>
                                        <th>Approved<br> Cost(RS)</th>
                                        <th>Work Start Date</th>
                                        <th>Length actually <br> under implementation(KM)</th>
                                        <th>Cost involved for <br> actual length(Rs.)</th>
                                        <th>Physical<br>Progress(%)</th>
                                        <th>Financial<br> Progress(%)</th>
                                        <th>Bill Paid(Rs.)</th>
                                        <th>Bill presently due(Rs.)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . $row->cost . '</td>';
                                            $wo_start_date = $row->wo_start_date == NULL ? '' : date('d/m/Y', strtotime($row->wo_start_date));
                                            echo '<td>' . $wo_start_date . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->cost_involved . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->financial_progress . '</td>';
                                            echo '<td>' . $row->bill_paid . '</td>';
                                            echo '<td>' . $row->bill_due . '</td>';
                                            $status = $row->physical_progress == 100 && $row->financial_progress == 100 ? '<span class="badge btn-success">Complete</span>' : '<span class="badge btn-danger">Incomplete</span>';
                                            echo '<td>' . $status . '</td>';
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
                left: 2,
                right: 1
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'rural_roads(2025)_updated_work_details_report_' + $.now(),
                    title: 'RURAL ROADS(2025) REPORT ON UPDATED WORK DETAILS ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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

        $("#district_id").on("change", function(e) {
            e.preventDefault();
            get_block_list();
        });

        function get_block_list() {
            $.ajax({
                url: baseURL + "/ssm/get_block_list",
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