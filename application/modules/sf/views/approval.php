<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$approval = json_decode($approval);
$selected = json_decode($selected);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div><h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2></div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" id="search_approval" name="search_approval" class="btn btn-primary mt-4">
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
                    <div class="card-header"><h2 class="card-title"><?= $subheading ?></h2></div>
                    <div class="card-body">
                        <form id="approval">
                            <div class="row">
                                <div class="col-md-8"></div>
                                <div class="col-md-4"><button type="submit" id="forward" name="forward" class="btn btn-primary mb-4"><span>Save for Batch/Lot No</span></button> &nbsp; <button type="button" id="backward" name="backward" class="btn btn-danger mb-4"><span>Return</span></button></div>
                            </div>
                            <div class="table-responsive">
                                <table id="tbl" class="display table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="chkall" id="chkall" value=""></th>
                                            <th>Lot No.</th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>GP</th>
                                            <th>Ref No.</th>
                                            <th>Work Name</th>
                                            <th>Proposed Length (KM)</th>
                                            <th>Length (KM)</th>
                                            <th>Road Type</th>
                                            <th>Work Type</th>
                                            <th>Agency</th>
                                            <th>Survey Status</th>
                                            <th>Vetted Amount</th>
                                            <!--<th>Average Amount</th>-->
                                            <th>Per Km Cost (Rs. in Lakh)</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($approval as $row) {
                                            $doc = strlen($row->lot_doc) > 0 ? '<a target="_blank" class="btn btn-sm btn-success" href="' . base_url($row->lot_doc) . '"><i class="fas fa-file-pdf"></i></a>' : '';
                                            echo '<tr>';
                                            echo '<td><input type="checkbox" name="chk[' . $row->id . ']" id="chk_' . $row->id . '" class="chk" value=""></td>';
                                            echo '<td>' . $row->lot_no . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->proposed_length . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . ($row->status == 0 ? 'Not Started' : ($row->status == 1 ? 'On Going' : 'Completed')) . '</td>';
                                            echo '<td>' . $row->cost . '</td>';
                                            // echo '<td>' . ($row->cost/$row->length) . '</td>';
                                            if ($row->cost != null && $row->cost != 0 && $row->length != null && $row->length != 0) {
                                                // echo '<td>' . ($row->cost/$row->length) . '</td>';
                                                echo '<td>' . round($row->cost / $row->length / 100000, 2) . '</td>';
                                            } else {
                                                echo '<td>&nbsp;</td>';
                                            }
                                            // echo '<td>' . number_format(($row->cost/$row->length), 2, '.', '') . '</td>';
                                            echo '<td>' . $doc . '</td>';
                                            echo '</tr>';
                                            $i++;
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
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
            buttons: ['excel'],
            aLengthMenu: [
                [10, 50, 100, 200, -1],
                [10, 50, 100, 200, "All"],
            ]
        });
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
<link rel="stylesheet" href="<?php echo base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
<script src="<?php echo base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/sf.js') ?>"></script>