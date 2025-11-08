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
                        <?php echo form_open('srrp/rpt_updated_work_summary'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
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
                                    <label>Implementing Agency</label>
                                    <select id="agency" name="agency" class="form-control dropdown" data-live-search="true">
                                        <option value="">--Select Agency--</option>
                                        <option value="ZP" <?= $selected->agency == 'ZP' ? 'selected' : '' ?>>ZP</option>
                                        <option value="BLOCK" <?= $selected->agency == 'BLOCK' ? 'selected' : '' ?>>BLOCK</option>
                                        <option value="SRDA" <?= $selected->agency == 'SRDA' ? 'selected' : '' ?>>SRDA</option>
                                        <option value="MBL" <?= $selected->agency == 'MBL' ? 'selected' : '' ?>>MBL</option>
                                        <option value="AGRO" <?= $selected->agency == 'AGRO' ? 'selected' : '' ?>>AGRO</option>
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
                                        <th>Approved Work(No.)</th>
                                        <th>Approved Lenght(KM)</th>
                                        <th>Approved Amount(Lakh Rs.)</th>
                                        <th>Actual length taken up (Km.)</th>
                                        <th>Cost involved for actual length (Rs.)</th>
                                        <th>Completed (No.)</th>
                                        <th>Completed Length (Km.)</th>
                                        <th>Completed Amount (Crore Rs.)</th>
                                        <th>Bill Paid(Rs.)</th>
                                        <th>Bill presently due(Rs.)</th>
                                        <th>No. of works not updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        $count = 0;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->total . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . round($row->cost / 100000, 2) . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->cost_involved . '</td>';
                                            echo '<td>' . $row->completed . '</td>';
                                            echo '<td>' . $row->completed_length . '</td>';
                                            echo '<td>' . round($row->completed_amount / 10000000, 2) . '</td>';
                                            echo '<td>' . $row->bill_paid . '</td>';
                                            echo '<td>' . $row->bill_due . '</td>';
                                            echo '<td>' . $row->not_updated . '</td>';
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
                left: 4
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-II_updated_works_summary_report_' + $.now(),
                    title: 'PATHASHREE-II REPORT ON UPDATED WORK SUMMARY ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'PATHASHREE-II UPDATE WORK SUMMARY',
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

        $("#district_id").on("change", function (e) {
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
            }).done(function (data) {
                $("#block_id").empty();
                if (data.length > 0) {
                    $("#block_id").append(
                            $("<option>", {
                                value: "0",
                                text: "--All Block--"
                            })
                            );
                    $.each(data, function (i, item) {
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