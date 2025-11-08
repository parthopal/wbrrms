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
             
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                    <div class="row">
                            <div class="col-md-10">
                                <h4><?= $title ?></h4>
                            </div>
                            <div class="col-md-2 text-right">
                                <a href="<?= base_url('proposal/entry') ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <?php echo form_open('proposal'); ?>
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
                                        <th>Proposed received from</th>
                                        <th>Received Through</th>
                                        <th>Date of receiving proposal</th>
                                        <th>District Name</th>
                                        <th>Block Name</th>
                                        <th>GP Name</th>
                                       
                                        <th>Name Of Road</th>
                                        <th>Contact No</th>
                                       
            
                                        <th>Length(km)</th>
                                        <th>Work Type</th>
                                        <th>Road Type</th>
                                        <th>Approximate Cost</th>
                                        <th>Proposed Exicutive Agency</th>
                                        <th>Any Other Information</th>
                                        <th>Uploaded document</th>
                                        <th>Action Taken</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->letter . '</td>';
                                            echo '<td>' . $row->date . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                           
                                            echo '<td>' . $row->road_name . '</td>';
                                            echo '<td>' . $row->contactno . '</td>';
                                            
                                           
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . ROUND($row->approximate_cost / 100000, 2) . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->information . '</td>';
                                            $image = strlen($row->image) > 0 ? '<div class="avatar"><a href="' . base_url($row->image) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                            echo '<td>' . $image . '</td>';
                                            echo '<td>' . $row->action_taken . '</td>';
                                            echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>&nbsp;<button title="Remove" onclick="remove(' . $row->id . ')" class="btn btn-icon btn-round btn-sm btn-danger"><i class="fas fa-trash pointer" ></i></button></p>
                                            </td>';

                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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
                left: 4,
                right: 1
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'Proposal_' + $.now(),
                    title: 'Proposal REPORT',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function (xlsx) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
                        $('row c', sheet).attr('s', '25');
                    }
                },
                // {
                //     extend: 'print',
                //     text: 'Print',
                //     title: 'PS WISE WORK STATUS REPORT',
                //     footer: true,
                //     exportOptions: {
                //         columns: ':not(.not-export)'
                //     },
                //     customize: function (win) {
                //         $(win.document.body)
                //                 .find('h1').css('text-align', 'center')
                //                 .css('font-size', '10pt')
                //                 .prepend(
                //                         '<img src="' + baseURL + '/templates/img/pathashree.jpg" style="position:absolute; top:0; left:0;" />'
                //                         );
                //         $(win.document.body).find('table')
                //                 .addClass('compact')
                //                 .css('font-size', 'inherit')
                //                 .css('margin', '50px auto');
                //     }
                // }
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
                url: baseURL + "/proposal/get_block_list",
                type: "get",
                data: {district_id: $("#district_id").val()},
                dataType: "json",
                async: false,
            }).done(function (data) {
                $("#block_id").empty();
                if (data.length > 0) {
                    $("#block_id").append(
                            $("<option>", {value: "0", text: "--All Block--"})
                            );
                    $.each(data, function (i, item) {
                        $("#block_id").append(
                                $("<option>", {value: item.id, text: item.name})
                                );
                    });
                } else if ($("#district_id").val() === 0) {
                    $("#block_id").append(
                            $("<option>", {value: "0", text: "--All Block--"})
                            );
                } else {
                    $("#block_id").append(
                            $("<option>", {value: "", text: "--Select Block--"})
                            );
                }
            });
        }
    });
</script>
<script src="<?= base_url('templates/js/proposal.js') ?>"></script>