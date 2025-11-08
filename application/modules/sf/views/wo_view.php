<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$selected = $selected = '' ? '' : json_decode($selected);
$wo = json_decode($wo);
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
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
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
                            <div class="col-md-4">
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
                                    <br />
                                    <button type="button" id="search_wo" name="search_wo" class="btn btn-primary">
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
                                    <?php
                                    $i = 1;
                                    foreach ($wo as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="top" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->wo_no . '</td>';
                                        echo '<td>' . date('d/m/Y',strtotime($row->wo_date)) . '</td>';
                                        echo '<td>' . $row->contractor . '</td>';
                                        echo '<td>' . $row->completion_date . '</td>';
                                        echo '<td>' . $row->assigned_engineer . '<br/>' . $row->mobile . '</td>';
                                        $document = strlen($row->document) ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' . base_url($row->document) . '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>' : '';
                                        echo '<td>' . $document . '</td>';
                                        echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="wo_add(' . $row->sf_id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>
                                         </td>';
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
    var role_id = <?= $role_id ?>;
    $(document).ready(function () {
        $('#tbl').DataTable({
            dom: "lBfrtip",
            buttons: ["excel"],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"],
            ],
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<script src="<?= base_url('templates/js/sf.js') ?>"></script>