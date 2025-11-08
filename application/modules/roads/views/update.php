<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
$list = json_decode($list);
$role = $role_id;
// print_r($list[0]->id);
// echo '<br>';
// echo '<pre>';
// var_dump($list); 
// echo $id;
// exit;
?>

<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2" id='date_check'><?= $subheading ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div id="view" class="card-body">
                        <?php echo form_open('ssm/update'); ?>
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
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Agency</th>
                                        <th>Road Name</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Approved Length(km)</th>
                                        <th>Approved Amount (Rs.)</th>
                                        <th>Work Start Date</th>
                                        <th>Completion Date</th>
                                        <th>Actual length taken up (Km.)</th>
                                        <th>Cost involved for actual length (Rs)</th>
                                        <th>Physical Progress (%)</th>
                                        <th>Financial Progress (%)</th>
                                        <th>Bill Paid (Rs.)</th>
                                        <th>Bill Presently Due (Rs.)</th>
                                        <th>Updated</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . $row->awarded_cost . '</td>';
                                            $start_date = $row->wo_start_date == '' ? '<input type="text" id="wo_start_date_' . $row->id . '" class="form-control " placeholder="DD/MM/YYYY" onblur="update_wo_date(' . $row->id . ')">' : '<label id="start_date_' . $row->id . '">' . date('d/m/Y', strtotime($row->wo_start_date)) . '</label>';
                                            echo '<td>' . $start_date . '</td>';
                                            $complete_date = ($row->physical_progress != 100) ? '' : (($row->work_completion_date == '') ? '<input type="text" id="complete_date_' . $row->id . '" class="form-control " placeholder="DD/MM/YYYY" onblur="date_complete(' . $row->id . ')">' : '<label>' .  date('d/m/Y', strtotime($row->work_completion_date))) . '</label>';
                                            echo '<td>' . $complete_date  . '</td>';
                                            echo '<td id="len_' . $row->id . '">' . $row->length . '</td>';
                                            echo '<td id="cost_' . $row->id . '">' . (strlen($row->cost_involved) > 0 ? $row->cost_involved : 0) . '</td>';
                                            echo '<td id="pp_' . $row->id . '">' . $row->physical_progress . '</td>';
                                            echo '<td id="fp_' . $row->id . '">' . $row->financial_progress . '</td>';
                                            echo '<td id="bp_' . $row->id . '">' . $row->bill_paid . '</td>';
                                            echo '<td id="bd_' . $row->id . '">' . $row->bill_due . '</td>';
                                            $status = ($row->physical_progress == 100 && $row->financial_progress == 100) ? (($role < 3) ? '<i class="fas fa-check" style="color: green" onclick="update(' . $row->id . ',\'' . addslashes($row->name) . '\', \'' . $row->approved_length . '\', \'' . $row->length . '\',\'' . $row->awarded_cost . '\', \'' . $row->physical_progress . '\', \'' . $row->financial_progress . '\', \'' . $row->bill_paid . '\', \'' . $row->bill_due . '\',\'' . $row->cost_involved . '\',\'' . $row->isupdated . '\')"></i>' : '<i class="fas fa-check" style="color: green"></i>') : '<i class="fas fa-times pointer" style="color: red" onclick="update(' . $row->id . ',\'' . addslashes($row->name) . '\', \'' . $row->approved_length . '\', \'' . $row->length . '\',\'' . $row->awarded_cost . '\', \'' . $row->physical_progress . '\', \'' . $row->financial_progress . '\', \'' . $row->bill_paid . '\', \'' . $row->bill_due . '\',\'' . $row->cost_involved . '\',\'' . $row->isupdated . '\')"></i>';
                                            echo '<td id="status_' . $row->id . '" style="text-align: center;">' . $status . '</td>';
                                            echo '</tr>';
                                            $i++;
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
                                        <th></th>
                                        <th><?= array_sum(array_column($list, 'length')) ?></th>
                                        <th><?= array_sum(array_column($list, 'cost_involved')) ?></th>
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cost involved for actual length</label>
                                    <div class="input-group">
                                        <input type='text' id="cost_involved" name="cost_involved" class="form-control" value="" autocomplete="off" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rs.</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                        </div>
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Bill Presently due*</label>
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
                    <div class="row" id="date_update">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Start Date</label>
                                <div class="input-group mb-3">
                                    <input type='text' id="start_date" name="start_date" class="form-control" placeholder="DD/MM/YYYY" value="" onblur="update_complete_start_date()" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Complete Date</label>
                                <div class="input-group mb-3">
                                    <input type='text' id="complete_date" name="complete_date" class="form-control" placeholder="DD/MM/YYYY" value="" onblur="update_complete_start_date()" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#view').show();
        $('#entry').hide();
        $('#date_update').hide();
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
                    customize: function(xlsx) {
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
                    customize: function(win) {
                        $(win.document.body)
                            .find('h1').css('text-align', 'center')
                            .css('font-size', '10pt')
                            .prepend(
                                '<img ]c="' + baseURL + '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
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
                    $("#block_id").append($("<option>", {
                        value: "0",
                        text: "--All Block--"
                    }));
                    $.each(data, function(i, item) {
                        $("#block_id").append($("<option>", {
                            value: item.id,
                            text: item.name
                        }));
                    });
                } else if ($("#district_id").val() === 0) {
                    $("#block_id").append($("<option>", {
                        value: "0",
                        text: "--All Block--"
                    }));
                } else {
                    $("#block_id").append($("<option>", {
                        value: "",
                        text: "--Select Block--"
                    }));
                }
            });
        }

        $("#length").on("change", function(e) {
            var max_length = parseFloat($('#al').val()) + parseFloat($('#al').val() * 0.1);
            if (parseFloat($('#length').val()).toFixed(2) > parseFloat(max_length).toFixed(2)) {
                $('#length').css('background-color', '#ffcccb');
            } else {
                $('#length').css('background-color', '');
            }
        });

        $("#cost_involved").on("change", function(e) {
            if (parseFloat($('#cost_involved').val()) > parseFloat($('#cost').val())) {
                $('#cost_involved').css('background-color', '#ffcccb');
            } else {
                $('#cost_involved').css('background-color', '');
            }
        });

        $("#cancel").on("click", function(e) {
            e.preventDefault();
            cancel();
        });

        $("#submit").on("click", function(e) {
            e.preventDefault();
            var max_length = parseFloat($('#al').val()) + parseFloat($('#al').val() * 0.1);
            console.log('max_len: ' + parseFloat(max_length).toFixed(2));
			console.log('len: ' + parseFloat($('#length').val()).toFixed(2));
			console.log(Math.fround(parseFloat($('#length').val()).toFixed(2)) - Math.fround(parseFloat(max_length).toFixed(2)));
            if (Math.fround(parseFloat($('#length').val()).toFixed(2)) > Math.fround(parseFloat(max_length).toFixed(2))) {
                alert('The value of actual length taken up is greater than approved length');

            } else if (parseFloat($('#cost_involved').val()) > parseFloat($('#cost').val())) {
                alert('Cost involved for actual length can not be greater than approved amount');
            } else {
                save();
            }
        });
        $('#date_check').on('dblclick', function(e) {
            e.preventDefault();
            $('#date_update').show();
        });
    });

    function update(id, name, approved_length, length, cost, pp, fp, bp, bd, cost_involved, isupdated) {
        $('#view').hide();
        $('#entry').show();
        $('#name').html(name + '<i style="color: red;"> (Approved Length: ' + approved_length + ' km & Approved Cost: Rs. ' + cost + ')</i>');
        $('#id').val(id);
        $('#al').val(approved_length);
        var max_length = +(parseFloat(approved_length) + parseFloat(approved_length * 0.1)).toFixed(2);
        if (parseFloat(length) > 0 && parseFloat(length) <= max_length) {
            $('#length').val(length);
            if (isupdated > 0) {
                //                $('#length').attr('readonly', true);
            }
        } else {
            $('#length').val(0);
        }
        $('#cost').val(cost);
        if (cost_involved > 0 && cost_involved <= cost) {
            $('#cost_involved').val(cost_involved);
            if (isupdated > 0) {
                //                $('#cost_involved').attr('readonly', true);
            }
        } else {
            $('#cost_involved').val(0);
        }
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
            url: baseURL + '/ssm/update_save',
            type: 'get',
            data: {
                id: id,
                length: $('#length').val(),
                cost_involved: $('#cost_involved').val(),
                pp: $('#pp').val(),
                fp: $('#fp').val(),
                bp: $('#bp').val(),
                bd: $('#bd').val(),
                isupdated: $('#isupdated').val()
            },
            dataType: "json"
        }).done(function(data) {
            $('#view').show();
            $('#entry').hide();
            $('#len_' + id).text($('#length').val());
            $('#cost_approved_' + id).text($('#cost_approved').val());
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
        $('#cost_involved').val('');
        $('#length').val('');
        $('#pp').val('');
        $('#fp').val('');
        $('#bp').val('');
        $('#bd').val('');
        $('#isupdated').val('');
    }

    function date_complete(id) {
        var start_date = $('#start_date_' + id).text();
        var date = $('#complete_date_' + id).val();
        if (date != '') {
            $.ajax({
                    url: baseURL + "/ssm/update_completion_date",
                    type: "get",
                    data: {
                        id: id,
                        completion_date: date,
                        start_date: start_date
                    },
                    dataType: "json",
                    async: false,
                })
                .done(function(data) {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                });
        }
    }

    function update_wo_date(id) {
        var wo_date = $('#wo_start_date_' + id).val();
        // console.log(wo_date);
        if (date.split("/").reverse().join("-") > start_date.split("/").reverse().join("-") && date_format.test(wo_date)) {
            $.ajax({
                url: baseURL + "/ssm/update_wo_date",
                type: "get",
                data: {
                    id: id,
                    wo_start_date: wo_date
                },
                dataType: "json",
                async: false,
            }).done(function(data) {
                setTimeout(function() {
                    location.reload();
                }, 2500);
            });
        }
    }

    function update_complete_start_date() {
        var id = $('#id').val();
        var start_date = $('#start_date').val();
        var complete_date = $('#complete_date').val();
        // console.log(start_date + '/' + complete_date + '/' + id);
        $.ajax({
            url: baseURL + "/ssm/date_update",
            type: "post",
            data: {
                id: id,
                start_date: start_date,
                complete_date: complete_date
            },
            dataType: "json",
            async: false,
        }).done(function(data) {
            setTimeout(function() {
                location.reload();
            }, 2500);
        });
    }
</script>