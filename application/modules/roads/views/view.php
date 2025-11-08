<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$survey = $survey != '' ? json_decode($survey) : '';
$selected = json_decode($selected);
$style = $role_id > 2 ? 'style="display: none;"' : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <?php if ($role_id < 3): ?>
                    <div class="ml-md-auto py-2 py-md-0">
                        <button type="button" class="btn btn-success btn-round" onclick="add(0)">
                            <i class="fa fa-plus"></i> &nbsp; Add New
                        </button>
                    </div>
                <?php endif; ?>

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
                                <div class="form-group mt-4">
                                    <div class="selectgroup selectgroup-secondary selectgroup-pills">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="0" class="selectgroup-input" checked>
                                            <span class="selectgroup-button selectgroup-button-icon" selected title="Not approved"><i class="fa fa-times"></i></span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon" title="approved"><i class="fa fa-check"></i></span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="status" value="-1" class="selectgroup-input">
                                            <span class="selectgroup-button selectgroup-button-icon" title="Not implemented"><i class="fas fa-minus-circle"></i></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" id="search" name="search" class="btn btn-primary mt-4">
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
                                        <th>Work Name</th>
                                        <th>Assembly Constituency</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>GP</th>
                                        <th>Ref No</th>
                                        <th>Implementing Agency</th>
                                        <th>Road Length (km)</th> <!-- Proposed Length (km) -->
                                        <th>Actual Road Length <br>(After field verification)</th>
                                        <th>Type of Work</th>
                                        <th>Type of Road</th>
                                        <th>Executable BT Length (km)</th>
                                        <th>Executable CC Length (km)</th>
                                        <th>Executable Road Length (km)</th>
                                        <th>Name of New Technology</th>
                                        <th>New Technology Length (km)</th>
                                        <th>Cost for Road Works including <br> Protective work, CD work, etc. (Rs.)</th>
                                        <th>Applicable GST@18% (Rs.)</th>
                                        <th>Labour welfare cess @1% (Rs.)</th>
                                        <th>Total Estimated Cost <br> Excluding Contingency (Rs.)</th>
                                        <th>Per km Estimated Cost <br> excluding Contingency (Rs. in Lakh)</th>
                                        <th>Contingency/Agency Fee for <br> MBL & WBAICL @3% (Rs.)</th>
                                        <th>Vetted Estimated Cost </br> including contingency (Rs.)</th>
                                        <th>Vetted Estimated Document</th>
                                        <th>Survey Status</th>
                                        <th>DM Status</th>
                                        <th>SE Status</th>
                                        <th>State Admin Status</th>
                                        <th>Tender Status</th>
                                        <th>Work Order Status</th>
                                        <th>Work Progress</th>
                                        <th>Status</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($survey as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->gp . '</td>';
                                        echo '<td>' . $row->ref_no . '</td>';
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

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="contigency_' . $row->id . '">' . number_format($row->contigency_amt, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="estimated_' . $row->id . '">' . number_format($row->estimated_amt, 2) . '</span>
                                            </td>';




                                        echo '<td>';
                                        echo '<div style="display:inline-block; text-align:center; min-width:120px;">';
                                        // Show document link if available
                                        if (!empty($row->survey_estimated_doc)) {
                                            echo '<a href="' . $row->survey_estimated_doc . '" target="_blank" ' .
                                                'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                '<i class="fa fa-file-alt"></i> View Doc' .
                                                '</a>';
                                        }
                                        echo '</div>';
                                        echo '</td>';

                                        //  survey_lot_doc
                                        echo '<td>';
                                        echo '<div style="display:inline-block; text-align:center; min-width:120px;">';

                                        // Show Lot No or N/A
                                        $lotNo = !empty($row->survey_lot_no) ? $row->survey_lot_no : 'N/A';
                                        echo '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' . $lotNo . '</div>';

                                        // Show document link if available
                                        if (!empty($row->survey_lot_doc)) {
                                            echo '<a href="' . $row->survey_lot_doc . '" target="_blank" ' .
                                                'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                '<i class="fa fa-file-alt"></i> View Doc' .
                                                '</a>';
                                        }

                                        echo '</div>';
                                        echo '</td>';

                                        // 
                                        echo '<td>';
                                        echo '<div style="display:inline-block; text-align:center; min-width:120px;">';

                                        // Show Lot No or N/A
                                        $lotNo = !empty($row->dm_lot_no) ? $row->dm_lot_no : 'N/A';
                                        echo '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' . $lotNo . '</div>';

                                        // Show document link if role_id matches and document exists
                                        $allowed_roles = [1, 2, 3, 7, 12]; // roles allowed to see document
                                        if (in_array($role_id, $allowed_roles) && !empty($row->dm_lot_doc)) {
                                            echo '<a href="' . $row->dm_lot_doc . '" target="_blank" ' .
                                                'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                '<i class="fa fa-file-alt"></i> View Doc' .
                                                '</a>';
                                        }

                                        echo '</div>';
                                        echo '</td>';

                                        echo '<td>';
                                        echo '<div style="display:inline-block; text-align:center; min-width:120px;">';

                                        // Show Lot No or N/A
                                        $lotNo = !empty($row->se_lot_no) ? $row->se_lot_no : 'N/A';
                                        echo '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' . $lotNo . '</div>';

                                        $allowed_roles = [1, 2, 3, 7]; // roles allowed to see document
                                        if (in_array($role_id, $allowed_roles) && !empty($row->se_lot_doc)) {
                                            echo '<a href="' . $row->se_lot_doc . '" target="_blank" ' .
                                                'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                '<i class="fa fa-file-alt"></i> View Doc' .
                                                '</a>';
                                        }

                                        echo '</div>';
                                        echo '</td>';

                                        echo '<td>';
                                        echo '<div style="display:inline-block; text-align:center; min-width:120px;">';

                                        // Show Lot No or N/A
                                        $lotNo = !empty($row->sa_lot_no) ? $row->sa_lot_no : 'N/A';
                                        echo '<div style="font-size:14px; font-weight:600; color:#333;">Lot No: ' . $lotNo . '</div>';

                                        // Show document link if role_id matches and document exists
                                        $allowed_roles = [1, 2, 3]; // roles allowed to see document
                                        if (in_array($role_id, $allowed_roles) && !empty($row->admin_approval_doc)) {
                                            echo '<a href="' . $row->admin_approval_doc . '" target="_blank" ' .
                                                'style="display:inline-block; margin-top:5px; padding:5px 10px; font-size:12px; font-weight:500; color:#fff; background:#4a90e2; border-radius:6px; text-decoration:none; transition:0.3s;">' .
                                                '<i class="fa fa-file-alt"></i> View Doc' .
                                                '</a>';
                                        }

                                        echo '</div>';
                                        echo '</td>';

                                        echo '<td>';
                                        switch ($row->tender_status) {
                                            case '0':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#e74c3c; border-radius:6px;">Not Started</span>';
                                                break;
                                            case '1':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#f39c12; border-radius:6px;">On Progress</span>';
                                                break;
                                            case '2':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#27ae60; border-radius:6px;">Completed</span>';
                                                break;
                                            default:
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#7f8c8d; background:#ecf0f1; border-radius:6px;">N/A</span>';
                                        }
                                        echo '</td>';

                                        echo '<td>';
                                        switch ($row->wo_status) {
                                            case '0':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#e74c3c; border-radius:6px;">Not Started</span>';
                                                break;
                                            case '1':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#f39c12; border-radius:6px;">On Progress</span>';
                                                break;
                                            case '2':
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#fff; background:#27ae60; border-radius:6px;">Completed</span>';
                                                break;
                                            default:
                                                echo '<span style="padding:4px 8px; font-size:12px; font-weight:600; color:#7f8c8d; background:#ecf0f1; border-radius:6px;">N/A</span>';
                                        }
                                        echo '</td>';

                                        echo '<td class="text-center">';
                                        if ($row->pp_status > 0) {
                                            echo '<span onclick="wp_image_view(' . $row->id . ')" 
                                                    style="cursor:pointer; padding:6px 14px; font-size:12px; font-weight:600; 
                                                        color:#fff; background:linear-gradient(135deg,#4a90e2,#357ABD); 
                                                        border-radius:30px; display:inline-flex; align-items:center; gap:6px; 
                                                        box-shadow:0 3px 6px rgba(0,0,0,0.2); transition:all 0.3s ease;" 
                                                    onmouseover="this.style.transform=\'scale(1.05)\'" 
                                                    onmouseout="this.style.transform=\'scale(1)\'">
                                                    <i class="fa fa-image"></i> View Images
                                                </span>';
                                        } else {
                                            echo '<span style="padding:6px 14px; font-size:12px; font-weight:600; 
                                                                color:#fff; background:linear-gradient(135deg,#95a5a6,#7f8c8d); 
                                                                border-radius:30px; display:inline-flex; align-items:center; gap:6px; 
                                                                box-shadow:0 3px 6px rgba(0,0,0,0.15);">
                                                        <i class="fa fa-ban"></i> Not Started
                                                    </span>';
                                        }
                                        echo '</td>';

                                        echo '<td>';
                                        if ($row->isactive == -1) {
                                            echo '<span class="badge btn-danger">Not Implemented</span>';
                                        } elseif ($row->isactive == 1) {
                                            switch ($row->survey_status) {
                                                case 6:
                                                    echo '<span class="badge btn-success">Approved</span>';
                                                    break;
                                                case 5:
                                                    echo '<span class="badge btn-info">State Admin Level</span>';
                                                    break;
                                                case 4:
                                                    echo '<span class="badge btn-info">SE Level</span>';
                                                    break;
                                                case 3:
                                                    echo '<span class="badge btn-info">DM Level</span>';
                                                    break;
                                                case 2:
                                                    echo '<span class="badge btn-info">Survey Completed</span>';
                                                    break;
                                                case 1:
                                                    echo '<span class="badge btn-info">Ongoing Survey</span>';
                                                    break;
                                                case 0:
                                                    echo '<span class="badge btn-warning">Survey Not Started</span>';
                                                    break;
                                                default:
                                                    echo '<span class="badge btn-secondary">N/A</span>';
                                            }
                                        } else {
                                            echo '<span class="badge btn-secondary">N/A</span>';
                                        }
                                        echo '</td>';


                                        //                                        $remove = $role_id < 4 && $row->agency == null ? '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-danger" onclick="remove(' . $row->id . ')"  title="Remove"><i class="fas fa-times pointer"></i></button></p>' : '';
                                        //                                        echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>' . $remove . '</td>';
                                        // echo '<td><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></td>';
                                        // echo '</tr>';

                                        echo '<td>';
                                        if (!($row->survey_status > 2 && $role_id > 3)) {
                                            echo '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')" title="Edit"><i class="fas fa-pen pointer"></i></button>';
                                        }
                                        echo '</td>';
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
    $(document).ready(function() {
        var currentdate = new Date();
        $('#tbl').DataTable({
            om: 'lBfrtip',
            processing: true,
            scrollY: '450px',
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false, // Let CSS handle width
            paging: false,
            responsive: true,
            stateSave: true,
            colReorder: true,
            fixedColumns: {
                leftColumns: 2,
                rightColumns: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'rural_roads_master_' + $.now(),
                    title: 'Rural Roads(2025) MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'RURAL Roads(2025) MASTER',
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
            ],
            columnDefs: [{
                targets: '_all',
                className: 'text-center align-middle'
            }]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>
<script src="<?= base_url('templates/js/roads.js') ?>"></script>