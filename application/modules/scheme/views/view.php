<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$category = json_decode($category);
$type = json_decode($type);
$selected = json_decode($selected);
$list = json_decode($list);
$disabled = $role_id > 3 ? 'disabled' : '';
$visible = $role_id > 3 ? 'style="display: none;"' : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading; ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('scheme/tender/' . $sc) ?>" class="btn btn-white btn-border btn-round mr-2">Tender</a>
                    <a href="<?= base_url('scheme/wo/' . $sc) ?>" class="btn btn-secondary btn-round">Work Order</a>
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
                                <h2><?= $title ?></h2>
                            </div>
                            <div class="col-md-2 text-right" <?= $visible ?>>
                                <a href="<?= base_url('scheme/entry/' . $sc) ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('scheme/view/' . $sc); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District *</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" required>
                                        <?php
                                        echo '<option value="0">--Select District--</option>';
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
                                    <label>Funding By *</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown" required>
                                        <?php
                                        echo '<option value="">--Select Fund--</option>';
                                        foreach ($category as $row) {
                                            $_selected = ($selected->category_id > 0 && $selected->category_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project Type *</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="">--Select Project Type--</option>
                                        <?php
                                        foreach ($type as $row) {
                                            $_selected = $row->id == $selected->type_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 text-right">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary">
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
                                <table id="tbl" class="display table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>District</th>
                                            <th>Funding By</th>
                                            <th>Scheme ID</th>
                                            <th>Scheme Name</th>
                                            <th>Type</th>
                                            <th>Agency</th>
                                            <th>Length/No.</th>
                                            <th>Sanction Cost<br><small>(in lakh)</small></th>
                                            <th>Admin Approval</th>
                                            <th>Tender</th>
                                            <th>WO</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->category . '</td>';
                                            echo '<td>' . $row->scheme_id . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="top" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->type . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->length . ' ' . $row->unit . '</td>';
                                            echo '<td>' . ROUND($row->sanctioned_cost / 100000, 2) . '</td>';
                                            echo '<td>No.: <b>' . $row->admin_no . '</b> on <b>' . date('d/m/Y', strtotime($row->admin_date)) . '</b></td>';
                                            echo '<td>' . ($row->istender > 0 ? '<i class="fas fa-check fa-2x" style="color: green"></i>' : '<i class="fas fa-times fa-2x" style="color: red"></i>') . '</td>';
                                            echo '<td>' . ($row->iswo > 0 ? '<i class="fas fa-check fa-2x" style="color: green"></i>' : '<i class="fas fa-times fa-2x" style="color: red"></i>') . '</td>';
                                            echo '<td><a href="' . base_url('scheme/entry/' . $sc . '/' . $row->id) . '"><button class="btn btn-icon btn-round btn-sm btn-primary" title="Edit"><i class="fas fa-pen pointer"></i></button></a>&nbsp;<button title="Remove" onclick="remove(' . $row->id . ')" class="btn btn-icon btn-round btn-sm btn-danger" ' . $disabled . '><i class="fas fa-trash pointer"></i></button></td>';
                                            echo '</tr>';
                                            $i++;
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
                right: 1
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'scheme_list_' + $.now(),
                    title: 'SCHEME LIST',
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
                    title: 'SCHEME LIST',
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