<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
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
                        <?php echo form_open('roads/rpt_work_progress'); ?>
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Work Progress</label>
                                    <select id="wp_id" name="wp_id" class="form-control dropdown">
                                        <option value="6" <?= $selected->wp_id == 6 ? 'selected' : '' ?>>--ALL--</option>
                                        <option value="0" <?= $selected->wp_id == 0 ? 'selected' : '' ?>>Not Started</option>
                                        <option value="1" <?= $selected->wp_id == 1 ? 'selected' : '' ?>>0-25%</option>
                                        <option value="2" <?= $selected->wp_id == 2 ? 'selected' : '' ?>>26-50%</option>
                                        <option value="3" <?= $selected->wp_id == 3 ? 'selected' : '' ?>>51-75%</option>
                                        <option value="4" <?= $selected->wp_id == 4 ? 'selected' : '' ?>>76-99%</option>
                                        <option value="5" <?= $selected->wp_id == 5 ? 'selected' : '' ?>>Completed</option>
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
                                        <th>Panchayat Samity</th>
                                        <th>Road Name</th>
                                        <th>Agency</th>
                                        <th>Length (Km.)</th>
                                        <th>Cost (Rs.)</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Work Start Date</th>
                                        <th>Progress</th>
                                        <th>Staring Point</th>
                                        <th>Middle Point</th>
                                        <th>Ending Point</th>
                                        <th>Total Inspection</th>
                                        <th>Last Inspection Date</th>
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
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            $name = $row->pp_status > 0 ? '<a href="' . base_url('roads/rpt_work_progress_details/' . $row->id) . '" target="_blank">' . $name . '</a>' : $name;
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->awarded_cost . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            $start_date = $row->wo_start_date != '' ? date('d/m/Y',strtotime($row->wo_start_date)) : '';
                                            echo '<td>' . $start_date . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            $image1 = strlen($row->image1) > 0 ? '<div class="avatar"><a href="' . base_url($row->image1) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                            echo '<td>' . $image1 . '</td>';
                                            $image2 = strlen($row->image2) > 0 ? '<div class="avatar"><a href="' . base_url($row->image2) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                            echo '<td>' . $image2 . '</td>';
                                            $image3 = strlen($row->image3) > 0 ? '<div class="avatar"><a href="' . base_url($row->image3) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                            echo '<td>' . $image3 . '</td>';
                                            $inspection_count = $row->inspection_count != 0 ? $row->inspection_count : '';
                                            echo '<td>'. $inspection_count . '</td>';
                                            $last_inspection = $row->last_work_date!= ''? date('d/m/Y',strtotime($row->last_work_date)) :'';
                                            echo '<td>' . $last_inspection . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                        <th></th>
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><?= array_sum(array_column($list, 'length')) ?></th>
                                        <th><?= array_sum(array_column($list, 'awarded_cost')) ?></th>
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
                left: 1,
                right: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'rural_roads(2025)_work_progress_report_' + $.now(),
                    title: 'Rural Roads(2025) WORK-WISE PROGRESS ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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

        $("#district_id").on("change", function (e) {
            e.preventDefault();
            get_block_list();
        });

        function get_block_list() {
            $.ajax({
                url: baseURL + "/roads/get_block_list",
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