<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
$lotno = json_decode($lotno);
$lot = json_decode($lot);
$selected = json_decode($selected);
$formid = $role_id < 4 ? 'admin' : 'lot';
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
    <form id="<?= $formid ?>" enctype="multipart/form-data">
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
                                        <label>Lot No</label>
                                        <select id="lotno" name="lotno" class="form-control dropdown">
                                            <?php
                                            echo '<option value="0">--All Lot No--</option>';
                                            foreach ($lotno as $row) {
                                                $_selected = $selected->lotno == $row->lotno ? 'selected' : '';
                                                echo '<option value="' . $row->lotno . '" ' . $_selected . '>' . $row->lotno . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <button type="button" id="search_lotno" name="search_lotno" class="btn btn-primary mt-4">
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
                            <?php
                            if ($role_id < 4) {
                            ?>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Upload Signed Copy *<br>
                                                    <span style="font-size: 10px; color: red;">( Maximum 2 Mb, Uploaded Format - pdf ) </span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="multiselect_div">
                                                    <input type="file" name="userfile" class="dropify" data-max-file-size="2048K" accept="pdf/*" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="admin_date">Admin Date *</label>
                                                <div class="input-group">
                                                    <input type="text" id="admin_date" name="admin_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="admin_no">Admin No *</label>
                                                <div class="input-group">
                                                    <input type='text' id="admin_no" name="admin_no" class="form-control" placeholder="admin no" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"><button type="submit" id="forward" name="forward" class="btn btn-primary mb-4"><span>Final Submission</span></button></div>
                                            <div class="col-md-6"><button type="button" id="print" name="print" class="btn btn-primary mb-4" onclick="print_lot()"><i class="fas fa-download"></i>&nbsp;<span>Download/Print</span></button></div>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            } else {
                            ?>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Upload Signed Copy *<br>
                                                    <span style="font-size: 10px; color: red;">( Maximum 2 Mb, Uploaded Format - pdf ) </span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="multiselect_div">
                                                    <input type="file" name="userfile" class="dropify" data-max-file-size="2048K" accept="pdf/*" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2"><button type="submit" id="forward" name="forward" class="btn btn-primary mb-4"><span>Final Submission</span></button></div>
                                    <div class="col-md-2"><button type="button" id="print" name="print" class="btn btn-primary mb-4" onclick="print_lot()"><i class="fas fa-download"></i>&nbsp;<span>Download/Print</span></button></div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="table-responsive">
                                <table id="tbl" class="display table table-bordered table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>District</th>
                                            <th>Assembly Constituency</th>
                                            <th>Block</th>
                                            <th>GP</th>
                                            <th>Work Name</th>
                                            <th>Ref No</th>
                                            <th>Implementing Agency</th>
                                            <th>Lot No</th>
                                            <th>Road Length (km)</th> <!-- Proposed Length (km) -->
                                            <th>Actual Road Length <br>(After field verification)</th>
                                            <th>Type of Road</th>
                                            <th>Type of Work</th>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($lot as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->ac . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->lotno . '</td>';
                                            echo '<td>' . $row->proposed_length . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->bt_length . '</td>';
                                            echo '<td>' . $row->cc_length . '</td>';
                                            echo '<td>' . number_format($row->bt_length + $row->cc_length, 3) . '</td>';
                                            echo '<td>' . $row->new_road_type . '</td>';
                                            echo '<td>' . $row->new_length . '</td>';
                                            echo '<td>' . ($row->status == 0 ? 'Not Started' : ($row->status == 1 ? 'On Going' : 'Completed')) . '</td>';
                                            $len = isset($row->length) && $row->length > 0 ? (float)$row->length : 0;
                                            $per_unit = $len > 0 ? (($row->estimated_amt / $len) / 100000) : 0.00;

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
                                            $len = (isset($row->bt_length, $row->cc_length) && ($row->bt_length + $row->cc_length) > 0)
                                            ? (float)($row->bt_length + $row->cc_length)
                                            : 0;
                                            $per_unit = $len > 0 ? ((($row->cost + $row->gst + $row->cess) / $len) / 100000) : 0.00;
                                            echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                ' . number_format($per_unit, 3) . '
                                            </td>';

                                            // echo '<td class="fw-bold">' . number_format((($row->cost + $row->gst + $row->cess) / $row->length) / 100000, 2) . '</td>';

                                            echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="contigency_' . $row->id . '">' . number_format($row->contigency_amt, 2) . '</span>
                                            </td>';

                                            echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="estimated_' . $row->id . '">' . number_format($row->estimated_amt, 2) . '</span>
                                            </td>';

                                            // echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                            //     ' . number_format($per_unit, 2) . '
                                            // </td>';
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
    </form>
</div>
<script>
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
                right: 0
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'rural_roads(2025)_lot_' + $.now(),
                    title: 'LOT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'RURAL ROADS(2025) LOT',
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