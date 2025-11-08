<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$category = json_decode($category);
$type = json_decode($type);
$selected = json_decode($selected);
$list = json_decode($list);
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
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('ridf/tender') ?>" class="btn btn-white btn-border btn-round mr-2">Tender</a>
                    <a href="<?= base_url('ridf/wo') ?>" class="btn btn-secondary btn-round">Work Order</a>
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
                                <!-- <h2><?= $title ?></h2> -->
                            </div>
                            <div class="col-md-2 text-right" <?= $visible ?>>
                                <a href="<?= base_url('ridf/entry') ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('ridf'); ?>
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
                                    <button type="submit" id="search_approved" name="search_approved" class="btn btn-primary">
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
                                        <th>Sl.</th>
                                        <th>Scheme Name</th>
                                        <th>Assembly Constituency</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Scheme ID</th>
                                        <th>Implementing Agency</th>
                                        <th>Type</th>
                                        <th>Sanction Length</th>
                                        <th>Sanction Cost<br><small>(in lakh)</small></small></th>
                                        <th>Admin Approval</th>
                                        <th>Admin Date</th>
                                        <!-- <th>Tender</th> -->
                                        <!-- <th>WO</th> -->
                                        <th>Status</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->ac_id . $row->ac . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->scheme_id . '</td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->work_type . '</td>';
                                        echo '<td>' . $row->length . '</td>';
                                        echo '<td>' . $row->sanctioned_cost . '</td>';
                                        echo '<td>' . $row->admin_no . '</td>';
                                        echo '<td>' . date('d/m/Y',strtotime($row->admin_date)) . '</td>';
                                        /*
                                        if($row->tender_status == 1){
                                            echo '<td> <span class="badge btn-sm btn-warning"> On Progress </td>';
                                        }elseif($row->tender_status == 2){
                                            echo '<td> <span class="badge btn-sm btn-success"> Complete </td>';
                                        }else{
                                            echo '<td> <span class="badge btn-sm btn-danger"> Not Started </td>';
                                        }

                                        if($row->wo_status == 1){
                                            echo '<td> <span class="badge btn-sm btn-warning"> On Progress </td>';
                                        }elseif($row->wo_status == 2){
                                            echo '<td> <span class="badge btn-sm btn-success"> Complete </td>';
                                        }else{
                                            echo '<td> <span class="badge btn-sm btn-danger"> Not Started </td>';
                                        }
                                        */
                                        if ($row->survey_status == -1) {
                                            echo '<td> <span class="badge btn-sm btn-danger"> Not Implemented </span> </td>';
                                        }
                                        if ($row->isactive == 1 && $row->survey_status == 1) {
                                            echo '<td> <span class="badge btn-sm btn-success"> Approved </span> </td>';
                                        }
                                        // $remove = $role_id < 4 && $row->agency == null ? '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove(' . $row->id . ')"  title="Remove"><i class="fas fa-times pointer"></i></button></p>' : '';
                                        // echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>' . $remove . '</td>';
                                        echo '<td><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="edit(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></td>';
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
    function edit(id) {
    window.location.href = baseURL + '/ridf/entry/' + id;
}
function add(id){
    window.location.href = baseURL + '/ridf/add_enter/' + id;
}
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
                left: 2,
                right: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'ridf_list_' + $.now(),
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
                    title:'RIDF LIST',
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
<!-- <script src="<?= base_url('templates/js/ridf.js') ?>"></script> -->