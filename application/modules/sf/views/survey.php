<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$survey = $survey != '' ? json_decode($survey) : '';
$selected = json_decode($selected);
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
                                    <button type="button" id="search_survey" name="search_survey" class="btn btn-primary mt-4">
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
                        <h2 class="card-title"><?= $subheading ?></h2>
                    </div>
                    <div class="card-body">
                        <form id="survey">
                            <div class="row">
                                <div class="col-md-9"></div>
                                <div class="col-md-3"><button type="submit" id="forward" name="forward" class="btn btn-primary mb-4"><span>Save for Batch/Lot No</span></button></div>
                            </div>
                            <div class="table-responsive">
                                <table id="tbl" class="display table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" name="chkall" id="chkall" value=""> </th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>GP</th>
                                            <th>Ref No.</th>
                                            <th>Work Name</th>
                                            <th>Length (KM)</th>
                                            <th>Road Type</th>
                                            <th>Work Type</th>
                                            <th>Agency</th>
                                            <th>Survey Status</th>
                                            <th>Vetted Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($survey as $row) {
                                            $vec = $row->status == 2 ? '<i class="fa fa-edit pointer" onclick="vec(' . $row->id . ')"></i>' : '';
                                            $disabled = $row->cost > 0 ? '' : 'disabled';
                                            $survey = $row->status == 2 ? 'disabled' : '';
                                            echo '<tr>';
                                            echo '<td><input type="checkbox" name="chk[' . $row->id . ']" id="chk_' . $row->id . '" class="chk" value="" ' . $disabled . '></td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . ($row->status == 0 ? 'Not Started' : ($row->status == 1 ? 'On Going' : 'Completed')) . '</td>';
                                            echo '<td><span id="cost_' . $row->id . '">' . $row->cost . '</span>' . $vec . '</td>';
                                            echo '<td><p style="margin:0px; width: 90px"><button data-toggle="tooltip" data-placement="bottom"  class="btn btn-icon btn-round btn-sm btn-primary" onclick="add_survey(' . $row->id . ')"  title="Edit" ' . $survey . '><i class="fas fa-plus pointer"></i></button> &nbsp;

                                            <button data-toggle="tooltip" data-placement="bottom" title="Mark Scheme Not Implemented" class="btn btn-icon btn-round btn-sm btn-danger" onclick="mark_not_traceable(' . $row->id . ')" ><i class="fas fa-minus-circle"></i></button>
                                            
                                            </p>
                                            
                                            </td>';
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
<link rel="stylesheet" href="<?php echo base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
<script src="<?php echo base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/sf.js') ?>"></script>