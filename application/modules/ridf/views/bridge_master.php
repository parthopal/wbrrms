<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
$list = json_decode($list);
$category = json_decode($category);
$disabled = $role_id > 3 ? 'disabled' : '';
$visible = $role_id > 3 ? 'style="display: none;"' : '';
// echo '<pre>';
// print_r($list);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading; ?></h5>
                </div>
                <!-- <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('ridf/tender') ?>" class="btn btn-white btn-border btn-round mr-2">Tender</a>
                    <a href="<?= base_url('ridf/wo') ?>" class="btn btn-secondary btn-round">Work Order</a>
                </div> -->
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
                                <!-- <h2><?= $title ?></h2> -->
                            </div>
                            <div class="col-md-2 text-right" <?= $visible ?>>
                                <a href="<?= base_url('ridf/bridge_entry/0') ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('ridf/bridge_master'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District <span style="color: red; font-size: 18px">*</span></label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" data-live-search="true" <?= $disabled ?> required>
                                        <?php
                                        $_selected = $selected->district_id == '' ? 'selected' : '';
                                        echo '<option value="" ' . $_selected . '>--Select District--</option>';
                                        foreach ($district as $row) {
                                            $_selected = $selected->district_id == $row->id ? 'selected' : '';
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
                                    <label>Funding By </label>
                                    <select id="category_id" name="category_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Fund--</option>';
                                        foreach ($category as $row) {
                                            $_selected = ($selected->category_id > 0 && $selected->category_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 text-right">
                                <div class="form-group">
                                    <button type="submit" id="search_bridge" name="search_bridge" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Sl.</th>
                                            <th>Funding By</th>
                                            <th>Name of the Bridge</th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>Agency</th>
                                            <th>AOT Date</th>
                                            <th>Work Order Date</th>
                                            <th>Completion Date</th>
                                            <th class="not-export">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($list as $row) {
                                            if ($row->isactive == 1) {
                                                echo '<tr>';
                                                echo '<td>' . $i . '</td>';
                                                echo '<td>' . $row->funding . '</td>';
                                                echo '<td>' . $row->name . '</td>';
                                                echo '<td>' . $row->district . '</td>';
                                                echo '<td>' . $row->block . '</td>';
                                                echo '<td>' . $row->agency . '</td>';
                                                echo '<td>' . date('d/m/Y', strtotime($row->aot_date)) . '</td>';
                                                echo '<td>' . date('d/m/Y', strtotime($row->wo_date)) . '</td>';
                                                echo '<td>' . date('d/m/Y', strtotime($row->complete_date)) . '</td>';
                                                echo '<td><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="bridge_edit(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></td>';
                                                echo '</tr>';
                                                $i++;
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
                right: 1
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'ridf_bridge_master_list_' + $.now(),
                    title: 'RIDF BRIDGE MASTER LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
                            currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
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
                    title: 'RIDF BRIDGE MASTER LIST ' + $.now(),
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
    });
</script>
<script src="<?= base_url('templates/js/ridf.js') ?>"></script>