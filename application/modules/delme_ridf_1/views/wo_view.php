<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
$block = json_decode($block);
$selected = $selected = '' ? '' : json_decode($selected);
$wo = json_decode($wo);
$category = json_decode($category);
$type = json_decode($type);
// print_r($type); exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
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
                                    <button type="submit" id="search_wo" name="search_wo" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><?= $subheading; ?></h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Assembly Constituency</th>
                                        <th>Block</th>
                                        <th>Project Name</th>
                                        <th>Implementing Agency</th>
                                        <th>Work Order No</th>
                                        <th>Work Order Date</th>
                                        <th>Contractor</th>
                                        <th>Completion Date</th>
                                        <th>Assigned Engineer</th>
                                        <th>pdf</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- <?php
                                    $i = 1;
                                    foreach ($wo as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="top" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->wo_no . '</td>';
                                        echo '<td>' . $row->wo_date . '</td>';
                                        echo '<td>' . $row->contractor . '</td>';
                                        echo '<td>' . $row->completion_date . '</td>';
                                        echo '<td>' . $row->assigned_engineer . '<br/>' . $row->mobile . '</td>';
                                        $document = strlen($row->document) ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' . base_url($row->document) . '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>' : '';
                                        echo '<td>' . $document . '</td>';
                                        echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' . $row->ridf_id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>
                                         </td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                    ?> -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- <script>
    var role_id = <?= $role_id ?>;
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
                right: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'RIDF_wo_' + $.now(),
                    title: 'RIDF WORK ORDER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'RIDF WORK ORDER',
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
    });
</script> -->
<script src="<?= base_url('templates/js/ridf.js') ?>"></script>