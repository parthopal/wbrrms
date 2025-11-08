<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$scheme = json_decode($scheme);
$foundation = json_decode($foundation);
$superstructure = json_decode($superstructure);
$type = json_decode($type);
$material = json_decode($material);
$ownership = json_decode($ownership);
$condition = json_decode($condition);
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
                <!--div class="ml-md-auto py-2 py-md-0">
                    <button type="button" class="btn btn-success btn-round" onclick="add(0)">
                        <i class="fa fa-plus"></i> &nbsp; Add New
                    </button>
                </div-->
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <?php echo form_open('bridge'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="vw_district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All District--</option>';
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
                                    <select id="vw_block_id" name="block_id" class="form-control dropdown">
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
                                    <label>Scheme</label>
                                    <select id="scheme_id" name="scheme_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Scheme--</option>';
                                        foreach ($scheme as $row) {
                                            $_selected = ($selected->scheme_id > 0 && $selected->scheme_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Material</label>
                                    <select id="material_id" name="material_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Material--</option>';
                                        foreach ($material as $row) {
                                            $_selected = ($selected->material_id > 0 && $selected->material_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Type</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Type--</option>';
                                        foreach ($type as $row) {
                                            $_selected = ($selected->type_id > 0 && $selected->type_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Foundation</label>
                                    <select id="foundation_id" name="foundation_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Foundation--</option>';
                                        foreach ($foundation as $row) {
                                            $_selected = ($selected->foundation_id > 0 && $selected->foundation_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Super Structure</label>
                                    <select id="superstructure_id" name="superstructure_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Super Structure--</option>';
                                        foreach ($superstructure as $row) {
                                            $_selected = ($selected->superstructure_id > 0 && $selected->superstructure_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Ownership</label>
                                    <select id="ownership_id" name="ownership_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Ownership--</option>';
                                        foreach ($ownership as $row) {
                                            $_selected = ($selected->ownership_id > 0 && $selected->ownership_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Condition</label>
                                    <select id="condition_id" name="condition_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All Condition--</option>';
                                        foreach ($condition as $row) {
                                            $_selected = ($selected->condition_id > 0 && $selected->condition_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary mt-4">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <?= $subheading ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Bridge Name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Scheme</th>
                                        <th>Length</th>
                                        <th>Width</th>
                                        <th>Chainage</th>
                                        <th>Sub-structure Material</th>
                                        <th>Sub-structure Type</th>
                                        <th>Foundation</th>
                                        <th>Super Structure</th>
                                        <th>Ownership</th>
                                        <th>Condition</th> 
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Side View</th>
                                        <th>Alignment</th>
                                        <th>A1</th>
                                        <th>A2</th>
                                        <th>Status</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        $status = '';
                                        switch ($row->isactive) {
                                            case 0:
                                                $status = '<span style="color: orange;">Drafted</span>';
                                                break;
                                            case 1:
                                                $status = '<span style="color: red;">Locked</span>';
                                                break;
                                            default:
                                                break;
                                        }
                                        $final_submit_style = '';
                                        $image1 = strlen($row->image_side) > 0 ? '<div class="avatar"><a href="' . base_url($row->image_side) . '" target="_blank"><img src="' . base_url($row->image_side) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                        $image2 = strlen($row->image_alignment) > 0 ? '<div class="avatar"><a href="' . base_url($row->image_alignment) . '" target="_blank"><img src="' . base_url($row->image_alignment) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                        $image3 = strlen($row->image_a1) > 0 ? '<div class="avatar"><a href="' . base_url($row->image_a1) . '" target="_blank"><img src="' . base_url($row->image_a1) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                        $image4 = strlen($row->image_a2) > 0 ? '<div class="avatar"><a href="' . base_url($row->image_a2) . '" target="_blank"><img src="' . base_url($row->image_a2) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                        $action_disabled = $row->isactive > 0 ? 'disabled' : '';
                                        $disabled = $row->isactive < 1 && strlen($row->image_side) > 0 && strlen($row->image_alignment) > 0 && strlen($row->image_a1) > 0 && strlen($row->image_a2) > 0 ? '' : 'disabled';
                                        $color = strlen($row->image_side) > 0 && strlen($row->image_alignment) > 0 && strlen($row->image_a1) > 0 && strlen($row->image_a2) > 0 ? 'purple' : 'lightblue';
                                        $arr = explode(',', $row->location);
                                        echo '<tr>';
                                        echo '<td><a href="https://maps.google.com/?q=' . $row->location . '" target="_blank"><i class="fas fa-map-marker-alt pointer fa-2x" style="color: red;"></i></a></td>';
                                        echo '<td><p class="truncate_text_small" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '" style="width: 225px !important;">' . $row->name . '<br><span style="color: purple;"><small><i>(' . $row->ref_no . ')</i></small></span></p></td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->scheme . '</td>';
                                        echo '<td>' . $row->length . '</td>';
                                        echo '<td>' . $row->width . '</td>';
                                        echo '<td>' . $row->chainage . '</td>';
                                        echo '<td>' . $row->material . '</td>';
                                        echo '<td>' . $row->type . '</td>';
                                        echo '<td>' . $row->foundation . '</td>';
                                        echo '<td>' . $row->superstructure . '</td>';
                                        echo '<td>' . $row->ownership . '</td>';
                                        echo '<td>' . $row->condition . '</td>';
                                        echo '<td>' . $arr[0] . '</td>';
                                        echo '<td>' . $arr[1] . '</td>';
                                        echo '<td>' . $image1 . '</td>';
                                        echo '<td>' . $image2 . '</td>';
                                        echo '<td>' . $image3 . '</td>';
                                        echo '<td>' . $image4 . '</td>';
                                        echo '<td>' . $status . '</td>';
                                        echo '<td><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')" title = "Edit" ' . $action_disabled . '><i class="fas fa-pen pointer"></i></button>'
                                        . '<button class="btn btn-icon btn-round btn-sm btn-secondary ml-2" onclick="image(' . $row->id . ')" title = "Edit" ' . $action_disabled . '><i class="fas fa-image pointer"></i></button>'
                                        . '<button class="btn btn-icon btn-round btn-sm btn-danger ml-2" onclick="status(' . $row->id . ', -1)" title = "Remove" ' . $action_disabled . '><i class="fas fa-trash pointer"></i></button>'
                                        . '<button class="btn btn-icon btn-round btn-sm ml-2" style="background-color: ' . $color . '; color: white;" onclick="status(' . $row->id . ', 1)" title="Final Submit" ' . (strlen($action_disabled) > 0 ? $action_disabled : $disabled) . '><i class="fas fa-save pointer"></i></button></td>';
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
                left: 2,
                right: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'bridge_master_' + $.now(),
                    title: 'BRIDGE MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'BRIDGE MASTER',
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
<script src="<?= base_url('templates/js/bridge.js') ?>"></script>