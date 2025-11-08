<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
$block = json_decode($block);
$approval = json_decode($approval);
// print_r($approval);exit;
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
                    <div class="card-header">
                        <h2 class="card-title"><?= $subheading ?></h2>
                    </div>
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
                                            <th>Assembly Constituency</th>
                                            <th>Block</th>
                                            <th>GP</th>
                                            <th>Ref No.</th>
                                            <th>Work Name</th>
                                            <th>Implementing Agency</th>
                                            <th>Road Length (km)</th>
                                            <th>Actual Road Length <br>(After field verification)</th>
                                            <th>Type of Work</th>
                                            <th>Type of Road</th>
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
                                            <th>Per km Estimated Cost <br> excluding Contingency (Rs. in Lakh)</th>
                                            <th>Contingency/Agency Fee for <br> MBL & WBAICL @3% (Rs.)</th>
                                            <th>Vetted Estimated Cost </br> including contingency (Rs.)</th>
                                            <th>Return Cause</th>
                                            <th>Vetted Estimated </br> Document</th>
                                            <th>Document</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($approval as $row) {
                                            $vec = $row->status > 2 ? '<i class="fa fa-edit pointer text-primary" onclick="editVetted(' . $row->id . ')"></i>' : '';
                                            $style = strlen($row->return_cause) > 0 ? 'style="background-color: #FFCDD2;"' : '';
                                            $doc = strlen($row->lot_doc) > 0 ? '<a target="_blank" class="btn btn-sm btn-success" href="' . base_url($row->lot_doc) . '"><i class="fas fa-file-pdf"></i></a>' : '';
                                            echo '<tr>';
                                            echo '<td><input type="checkbox" name="chk[' . $row->id . ']" id="chk_' . $row->id . '" class="chk" value=""></td>';
                                            echo '<td ' . $style . '>' . $row->lot_no . '</td>';
                                            echo '<td ' . $style . '>' . $row->district . '</td>';
                                            echo '<td>' . $row->ac . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->proposed_length . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->bt_length . '</td>';
                                            echo '<td>' . $row->cc_length . '</td>';
                                            echo '<td>' . number_format($row->bt_length + $row->cc_length, 3) . '</td>';
                                            echo '<td>' . $row->new_road_type . '</td>';
                                            echo '<td>' . $row->new_length . '</td>';
                                            echo '<td>' . ($row->status == 0 ? 'Not Started' : ($row->status == 1 ? 'On Going' : 'Completed')) . '</td>';
                                            echo '<td class="fw-bold">
                                                    <span id="cost_' . $row->id . '">' . number_format($row->cost, 2) . '</span>
                                                    &nbsp;&nbsp;&nbsp;' . $vec . '
                                                </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="gst_' . $row->id . '">' . number_format($row->gst, 2) . '</span>
                                                </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="cess_' . $row->id . '">' . number_format($row->cess, 2) . '</span>
                                                </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="total_' . $row->id . '">' . number_format(($row->cost + $row->gst + $row->cess), 2) . '</span>
                                                </td>';

                                            $len = (isset($row->bt_length, $row->cc_length) && ($row->bt_length + $row->cc_length) > 0)
                                                ? (float)($row->bt_length + $row->cc_length)
                                                : 0;
                                            $per_unit = $len > 0 ? ((($row->cost + $row->gst + $row->cess) / $len) / 100000) : 0.00;
                                            echo '<td class="fw-bold text-start" style="text-align:left !important;">
                                                ' . number_format($per_unit, 3) . '
                                            </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="contigency_' . $row->id . '">' . number_format($row->contigency_amt, 2) . '</span>
                                                </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="estimated_' . $row->id . '">' . number_format($row->estimated_amt, 2) . '</span>
                                                </td>';
                                            // echo '<td class="fw-bold">' . number_format(($row->estimated_amt / $row->length) / 100000, 2) . '</td>';
                                            echo '<td>' . $row->return_cause . '</td>';
                                            echo '<td>';
                                            echo '<div style="display:inline-block; text-align:center; min-width:120px;">';
                                            // Show document link if available
                                            if (!empty($row->survey_estimated_doc)) {
                                                echo '<a href="' . base_url($row->survey_estimated_doc) . '" target="_blank" ' .
                                                    'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                    '<i class="fa fa-file-alt"></i> View Doc' .
                                                    '</a>';
                                            }
                                            echo '</div>';
                                            echo '</td>';
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
                left: 3,
                right: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-IV_approval_' + $.now(),
                    title: 'APPROVAL ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'PATHASHREE-IV APROVAL',
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
</script>
<script src="<?= base_url('templates/js/roads.js') ?>"></script>