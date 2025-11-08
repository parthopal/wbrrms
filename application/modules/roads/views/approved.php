<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
$block = json_decode($block);
$approved = json_decode($approved);
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
                                    <label>Assembly Constituency</label>
                                    <select id="ac_id" name="ac_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All AC--</option>';
                                        foreach ($ac as $row) {
                                            $_selected = ($selected->ac_id > 0 && $selected->ac_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <button type="button" id="search_approved" name="search_approved" class="btn btn-primary mt-4">
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
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Assembly Constituency</th>
                                        <th>Block</th>
                                        <th>GP</th>
                                        <th>Scheme ID</th>
                                        <th>Work Name</th>
                                        <th>Agency</th>
                                        <th>Proposed Length (km)</th>
                                        <th>Length (KM)</th>
                                        <th>Road Type</th>
                                        <th>Work Type</th>
                                        <th>Executable BT Length (km)</th>
                                        <th>Executable CC Length (km)</th>
                                        <th>Executable Road Length (km)</th>
                                        <th>Name of New Technology</th>
                                        <th>New Technology Length (km)</th>
                                        <th>Survey Status</th>
                                        <th>Cost for Road Works including <br> Protective work, CD work, etc. (Rs.)</th>
                                        <th>Applicable GST@18% (Rs.)</th>
                                        <th>Labour welfare cess @1% (Rs.)</th>
                                        <th>Total Estimated Cost <br> Excluding Contingency (Rs.)</th>
                                        <th>Per km Vetted Estimated Cost <br> (Rs. in Lakh)</th>
                                        <th>Contingency/Agency Fee for <br> MBL & WBAICL @3% (Rs.)</th>
                                        <th>Vetted Estimated Cost </br> including contingency</th>
                                        <th>Document</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($approved as $row) {
                                        $doc = strlen($row->lot_doc) > 0 ? '<a target="_blank" class="btn btn-sm btn-success" href="' . base_url($row->lot_doc) . '"><i class="fas fa-file-pdf"></i></a>' : '';
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->gp . '</td>';
                                        echo '<td>' . $row->ref_no . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->proposed_length . '</td>';
                                        echo '<td>' . $row->length . '</td>';
                                        echo '<td>' . $row->road_type . '</td>';
                                        echo '<td>' . $row->work_type . '</td>';
                                        echo '<td>' . $row->bt_length . '</td>';
                                        echo '<td>' . $row->cc_length . '</td>';
                                        echo '<td>' . ($row->cc_length + $row->bt_length) . '</td>';
                                        echo '<td>' . $row->new_road_type . '</td>';
                                        echo '<td>' . $row->new_length . '</td>';
                                        echo '<td>' . ($row->status == 0 ? 'Not Started' : ($row->status == 1 ? 'On Going' : 'Completed')) . '</td>';
                                        $len = isset($row->length) && $row->length > 0 ? (float)$row->length : 0;
                                        $per_unit = $len > 0 ? ((($row->cost + $row->gst + $row->cess) / $len) / 100000) : 0.00;

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="cost_' . $row->id . '">' . number_format($row->cost, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="gst_' . $row->id . '">' . number_format($row->gst, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="cess_' . $row->id . '">' . number_format($row->cess, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="total_' . $row->id . '">' . number_format(($row->cost + $row->gst + $row->cess), 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                ' . number_format($per_unit, 2) . '
                                            </td>';
                                            
                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="contigency_' . $row->id . '">' . number_format($row->contigency_amt, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="estimated_' . $row->id . '">' . number_format($row->estimated_amt, 2) . '</span>
                                            </td>';


                                        echo '<td>' . $doc . '</td>';
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
    $(document).ready(function() {
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
<script src="<?= base_url('templates/js/roads.js') ?>"></script>