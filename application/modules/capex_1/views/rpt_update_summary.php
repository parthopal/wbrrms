<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
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
                    <a href="<?= base_url('ridf/report') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div id="view" class="card-body">
                        <?php echo form_open('ridf/rpt_update_summary'); ?>
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
                                    <label>Status</label>
                                    <select id="status" name="status" class="form-control dropdown">
                                        <option value="0" <?= $selected->status == 0 ? 'selected' : '' ?>>Not Updated</option>
                                        <option value="1" <?= $selected->status == 1 ? 'selected' : '' ?>>Updated</option>
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
                                        <th>Road Name</th>
                                        <th>District</th>
                                        <th>Panchayat Samity</th>
                                        <th>Agency</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Work Start Date</th>
                                        <th>Approved length(km)</th>
                                        <th>Approved Cost (Rs.)</th>
                                        <th>Actual Length taken up (Km.)</th>
                                        <th>Physical Progress(%)</th>
                                        <th>Financial Progress(%)</th>
                                        <th>Bill Paid(Rs.)</th>
                                        <th>Bill Due(Rs.)</th>
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
                                            echo '<td>' . date('d/m/Y', strtotime($row->wo_start_date)) . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . $row->awarded_cost . '</td>';
                                            echo '<td id="len_' . $row->id . '">' . $row->length . '</td>';
                                            echo '<td id="pp_' . $row->id . '">' . $row->physical_progress . '</td>';
                                            echo '<td id="fp_' . $row->id . '">' . $row->financial_progress . '</td>';
                                            echo '<td id="bp_' . $row->id . '">' . $row->bill_paid . '</td>';
                                            echo '<td id="bd_' . $row->id . '">' . $row->bill_due . '</td>';
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
                                        <th><?= array_sum(array_column($list, 'approved_length')) ?></th>
                                        <th><?= array_sum(array_column($list, 'awarded_cost')) ?></th>
                                        <th><?= array_sum(array_column($list, 'length')) ?></th>
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
        $('#view').show();
        $('#entry').hide();
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
                    filename: 'RIDF_update_statu_report_' + $.now(),
                    title: 'RIDF UPDATE STATUS REPORT OF PATHASHREE ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'RIDF WORK-WISE PROGRESS',
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
                    $("#block_id").append($("<option>", {value: "0", text: "--All Block--"}));
                    $.each(data, function (i, item) {
                        $("#block_id").append($("<option>", {value: item.id, text: item.name}));
                    });
                } else if ($("#district_id").val() === 0) {
                    $("#block_id").append($("<option>", {value: "0", text: "--All Block--"}));
                } else {
                    $("#block_id").append($("<option>", {value: "", text: "--Select Block--"}));
                }
            });
        }

        $("#length").on("change", function (e) {
            if (parseFloat($('#length').val()) > parseFloat($('#al').val())) {
                $('#length').css('background-color', '#ffcccb');
            } else {
                $('#length').css('background-color', '');
            }
        });

        $("#cancel").on("click", function (e) {
            e.preventDefault();
            cancel();
        });

        $("#submit").on("click", function (e) {
            e.preventDefault();
            save();
        });
    });

    function update(id, name, length, cost, pp, fp, bp, bd, isupdated) {
        $('#view').hide();
        $('#entry').show();
        $('#name').html(name + '<i>(' + length + ' km)</i>');
        $('#id').val(id);
        $('#al').val(length);
        $('#cost').val(cost);
        $('#length').val(length);
        $('#pp').val(pp);
        $('#fp').val(fp);
        $('#bp').val(bp);
        $('#bd').val(bd);
        $('#isupdated').val(isupdated);
    }

    function cancel() {
        $('#view').show();
        $('#entry').hide();
        reset();
    }

    function save() {
        var id = $('#id').val();
        $.ajax({
            url: baseURL + '/srrp/update_save',
            type: 'get',
            data: {id: id, length: $('#length').val(), pp: $('#pp').val(), fp: $('#fp').val(), bp: $('#bp').val(), bd: $('#bd').val(), isupdated: $('#isupdated').val()},
            dataType: "json"
        }).done(function (data) {
            $('#view').show();
            $('#entry').hide();
            $('#len_' + id).text($('#length').val());
            $('#pp_' + id).text($('#pp').val());
            $('#fp_' + id).text($('#fp').val());
            $('#bp_' + id).text($('#bp').val());
            $('#bd_' + id).text($('#bd').val());
            $('#status_' + id).html('<i class="fas fa-check" style="color: green"></i>');
            reset();
        });
    }

    function reset() {
        $('#name').html('');
        $('#id').val('');
        $('#al').val('');
        $('#cost').val('');
        $('#length').val('');
        $('#pp').val('');
        $('#fp').val('');
        $('#bp').val('');
        $('#bd').val('');
        $('#isupdated').val('');
    }
</script>