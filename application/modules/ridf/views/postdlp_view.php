<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$category = json_decode($category);
$type = json_decode($type);
$selected = json_decode($selected);
$list = json_decode($list);
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
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?= form_open('ridf/postdlp_maintenance'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District *</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown" required>
                                        <option value="0">--Select District--</option>
                                        <?php foreach ($district as $row): ?>
                                            <option value="<?= $row->id ?>" <?= ($selected->district_id == $row->id) ? 'selected' : '' ?>>
                                                <?= $row->name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Funding By *</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown">
                                        <option value="">--Select Fund--</option>
                                        <?php foreach ($category as $row): ?>
                                            <option value="<?= $row->id ?>" <?= ($selected->category_id == $row->id) ? 'selected' : '' ?>>
                                                <?= $row->name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Project Type *</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown" data-live-search="true">
                                        <option value="">--Select Project Type--</option>
                                        <?php foreach ($type as $row): ?>
                                            <option value="<?= $row->id ?>" <?= ($selected->type_id == $row->id) ? 'selected' : '' ?>>
                                                <?= $row->name ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 mt-4 text-right">
                                <div class="form-group">
                                    <button type="submit" id="search_postdlp" name="search_maintence" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp; <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?= form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover w-100 nowrap">
                                <thead>
                                    <tr>
                                        <th>Sl.</th>
                                        <th>Scheme Name</th>
                                        <th>Assembly Constituency</th>
                                        <th>Funding By</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Scheme ID</th>
                                        <th>Implementing Agency</th>
                                        <th>Type</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($list as $row): ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td>
                                                <p class="truncate_text" data-toggle="tooltip" title="<?= $row->name ?>">
                                                    <?= $row->name ?>
                                                </p>
                                            </td>
                                            <td><?= $row->ac_id . ' - ' . $row->ac ?></td>
                                            <td><?= $row->funding ?></td>
                                            <td><?= $row->district ?></td>
                                            <td><?= $row->block ?></td>
                                            <td><?= $row->scheme_id ?></td>
                                            <td><?= $row->agency ?></td>
                                            <td><?= $row->work_type ?></td>

                                            <td>
                                                <?php if ($row->pdm == 1): ?>
                                                    <button class="btn btn-icon btn-round btn-sm btn-info" onclick="view(<?= $row->id ?>)" title="View">
                                                        <i class="fas fa-eye pointer"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <button class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit(<?= $row->id ?>)" title="Edit">
                                                        <i class="fas fa-pen pointer"></i>
                                                    </button>
                                                <?php endif; ?>
                                            </td>


                                        </tr>
                                    <?php endforeach; ?>
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
    function edit(id) {
        window.location.href = baseURL + '/ridf/postdlp_entry/' + id;
    }

    function view(id) {
        window.location.href = '/ridf/postdlp_view_maintenance_admin/' + id;
    }


    $(document).ready(function() {
        let table = $('#tbl').DataTable({
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
                    filename: 'ridf_list_' + $.now(),
                    title: 'SCHEME LIST',
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
                    title: 'RIDF LIST',
                    footer: true,
                    exportOptions: {
                        columns: ':not(.not-export)'
                    },
                    customize: function(win) {
                        $(win.document.body).css('font-size', '10pt').prepend(
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

        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function() {
            table.columns.adjust().draw();
        });
    });
</script>