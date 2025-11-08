<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
$list = json_decode($list);
//var_dump($list);exit;
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
                    <div id="view" class="card-body">
                        <?php echo form_open('srrp/update'); ?>
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
                                    <label>Work Progress</label>
                                    <select id="wp_id" name="wp_id" class="form-control dropdown">
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
                                        <th>Road Name</th>
                                        <th>District</th>
                                        <th>Panchayat Samity</th>
                                        <th>Agency</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Approved length(km)</th>
                                        <th>Approved Cost (Rs.)</th>
                                        <th>Work Start Date</th>                                      
                                        <th>Length (Km.)</th>
                                        <th>Physical Progress</th>
                                        <th>Financial Progress</th>
                                        <th>Bill Paid</th>
                                        <th>Bill Due</th>
                                        <th>Updated</th>
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
                                            echo '<td id="ac_' . $row->id . '">' . $row->awarded_cost . '</td>';
                                            echo '<td>' . date('d/m/Y', strtotime($row->wo_start_date)) . '</td>';
                                           
                                            echo '<td id="len_' . $row->id . '">' . $row->length . '</td>';
                                            echo '<td id="pp_' . $row->id . '">' . $row->physical_progress . '</td>';
                                            echo '<td id="fp_' . $row->id . '">' . $row->financial_progress . '</td>';
                                            echo '<td id="bp_' . $row->id . '">' . $row->bill_paid . '</td>';
                                            echo '<td id="bd_' . $row->id . '">' . $row->bill_due . '</td>';
                                            $status = ($row->physical_progress == 100 && $row->financial_progress == 100) ? '<i class="fas fa-check" style="color: green"></i>' : '<i class="fas fa-times pointer" style="color: red" onclick="update(' . $row->id . ',\'' . $row->name . '\', \'' . $row->length . '\',\'' . $row->awarded_cost . '\', \'' . $row->physical_progress . '\', \'' . $row->financial_progress . '\', \'' . $row->bill_paid . '\', \'' . $row->bill_due . '\',\'' . $row->isupdated . '\')"></i>';
                                            echo '<td id="status_' . $row->id . '" style="text-align: center;">' . $status . '</td>';
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
                                        <th><?= array_sum(array_column($list, 'approved_length')) ?></th>                                        
                                        <th><?= array_sum(array_column($list, 'awarded_cost')) ?></th>
                                        <th></th>
                                        <th><?= array_sum(array_column($list, 'length')) ?></th>
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
                    <div id="entry" class="card-body">
                        <div class="col-md-12 text-center">
                            <strong id="name" class="text-primary"></strong>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Actual length taken up*</label>
                                    <div class="input-group">
                                        <input type='text' id="length" name="length" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">KM</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Physical Progress *</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="pp" name="pp" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Financial Progress *</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="fp" name="fp" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bill already paid *</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="bp" name="bp" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Bill due*</label>
                                    <div class="input-group mb-3">
                                        <input type='text' id="bd" name="bd" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rs</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-action">
                            <div class="col-md-12 text-right">
                                <button type="button" id="cancel" class="btn btn-danger ml-2">
                                    <i class="fas fa-times"></i> &nbsp; <span>Cancel</span>
                                </button>
                                <button type="button" id="submit" class="btn btn-success ml-2">
                                    <i class="fas fa-save"></i> &nbsp; <span>Update</span>
                                </button>
                            </div>
                        </div>
                        <input type="hidden" id="al" value="">
                        <input type="hidden" id="cost" value="">
                        <input type="hidden" id="isupdated" value="">
                        <input type="hidden" id="id" name="id" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
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
                    filename: 'work_progress_report_' + $.now(),
                    title: 'WORK-WISE PROGRESS',
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
                    title: 'WORK-WISE PROGRESS',
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
        // $("#cancel").on("click", function (e) {
        //     e.preventDefault();
        //     cancel();
        // });

        $("#cancel").on("click", function (e) {
            e.preventDefault();
            cancel();
        });

        $("#submit").on("click", function (e) {
            e.preventDefault();
            if(parseFloat(($('#ac').val)) > = sum(parseFloat('#bp').val + parsefloat('#bd'))){
                $('#ac').css('background-color', '#ffcccb');
            }else {
                $('#ac').css('background-color', '');
            }
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