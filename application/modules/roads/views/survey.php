<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$ac = json_decode($ac);
$block = json_decode($block);
$survey = $survey != '' ? json_decode($survey) : '';
$selected = json_decode($selected);
?>


<style>
    .scroll-message {
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        background: #ffefef;
        color: #b30000;
        font-weight: 600;
        padding: 10px 0;
        border: 1px solid #ffb3b3;
        font-family: Arial, sans-serif;
        position: relative;
    }

    .scroll-message span {
        display: inline-block;
        padding-left: 100%;
        animation: scrollText 20s linear infinite;
    }

    @keyframes scrollText {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-100%);
        }
    }
</style>
<div class="container">
    <div class="scroll-message">
        <span><b>
            👉 Please complete filling in all the master data fields (e.g., Proposed Surface Type) before proceeding.</b>
        </span>
    </div>

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
                                            <th>Work Name</th>
                                            <th>Assembly Constituency</th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>GP</th>
                                            <th>Ref No.</th>
                                            <th>Implementing Agency</th>
                                            <th>Road Length (km)</th> <!-- Proposed Length (km) -->
                                            <th>Actual Road Length <br>(after field verification)</th>
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
                                            <th>Return Cause</th>
                                            <th>Vetted Estimated </br> Document</th>
                                            <th class="not-export">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($survey as $row) {
                                            $vec = $row->status == 2 ? '<i class="fa fa-edit pointer text-primary" onclick="editVetted(' . $row->id . ')"></i>' : '';
                                            $est = $row->status == 2 ? '<i class="fa fa-edit pointer text-primary" onclick="editContigency(' . $row->id . ')"></i>' : '';
                                            $dpr = $row->status == 2 ? '<i class="fa fa-edit pointer" onclick="dpr(' . $row->id . ')"></i>' : '';
                                            $con = $row->status == 2 ? '<i class="fa fa-edit pointer" onclick="con(' . $row->id . ')"></i>' : '';
                                            // $est = $row->status == 2 ? '<i class="fa fa-edit pointer" onclick="est(' . $row->id . ')"></i>' : '';
                                            $disabled = ($row->cost > 0 && $row->status > 1) ? '' : 'disabled';
                                            $survey = $row->status == 2 ? 'disabled' : '';
                                            $style = strlen($row->return_cause) > 0 ? 'style="background-color: #FFCDD2;"' : '';
                                            echo '<tr>';
                                            echo '<td><input type="checkbox" name="chk[' . $row->id . ']" id="chk_' . $row->id . '" class="chk" value="" ' . $disabled . '></td>';
                                            echo '<td ' . $style . '><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . $row->ac . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
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
                                            // echo '<td class="fw-bold">' . number_format((($row->cost + $row->gst + $row->cess) / $row->length) / 100000, 2) . '</td>';
                                            echo '<td class="fw-bold">
                                                    <span id="contigency_' . $row->id . '">' . number_format($row->contigency_amt, 2) . '</span>
                                                </td>';

                                            echo '<td class="fw-bold">
                                                    <span id="estimated_' . $row->id . '">' . number_format($row->estimated_amt, 2) . '</span>
                                                </td>';


                                            echo '<td>' . $row->return_cause . '</td>';
                                            echo '<td>' . (!empty($row->survey_estimated_doc) ? '<button class="btn btn-icon btn-round btn-sm btn-primary" onclick="window.open(\'' . base_url($row->survey_estimated_doc) . '\', \'_blank\')" title="Document"><i class="fas fa-file-pdf"></i></button>' : '') . '</td>';

                                            // echo '<td><p style="margin:0px; width: 90px"><button data-toggle="tooltip" data-placement="bottom"  class="btn btn-icon btn-round btn-sm btn-primary" onclick="add_survey(' . $row->id . ')"  title="Edit" ' . $survey . '><i class="fas fa-plus pointer"></i></button> &nbsp;
                                            // <button data-toggle="tooltip" data-placement="bottom" title="Mark Scheme Not Implemented" class="btn btn-icon btn-round btn-sm btn-danger" onclick="mark_not_traceable(' . $row->id . ')" ><i class="fas fa-minus-circle"></i></button>
                                            // </p>
                                            // </td>';

                                            // $disabled = $row->status == 2 ? 'disabled' : '';
                                            echo '<td>
                                                <div style="margin:0px; width: 90px; display:inline-block; cursor:pointer;" ondblclick="enableEdit(' . $row->id . ')">
                                                    <button id="editBtn_' . $row->id . '" data-toggle="tooltip" data-placement="bottom"  
                                                        class="btn btn-icon btn-round btn-sm btn-primary" 
                                                        onclick="add_survey(' . $row->id . ')" ' . $survey . ' 
                                                        ' . ($row->status == 2 ? 'style="pointer-events:none;"' : '') . '>
                                                        <i class="fas fa-plus pointer"></i>
                                                    </button> &nbsp;
                                                    <button data-toggle="tooltip" data-placement="bottom" 
                                                        title="Mark Scheme Not Implemented" 
                                                        class="btn btn-icon btn-round btn-sm btn-danger" 
                                                        onclick="mark_not_traceable(' . $row->id . ')">
                                                        <i class="fas fa-minus-circle"></i>
                                                    </button>
                                                </div>
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
<script>
    // JS optional – sets data-text dynamically for reusability
    document.querySelector('.scroll-message').setAttribute(
        'data-text',
        document.querySelector('.scroll-message').textContent
    );

    // function enableEdit(id) {
    //     const btn = document.getElementById('editBtn_' + id);
    //     if (btn && btn.disabled) {
    //         btn.disabled = false; // enable button
    //         btn.style.pointerEvents = 'auto'; // restore click
    //         alert('Edit button enabled!'); // optional feedback
    //     }
    // }
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
                left: 2,
                right: 2
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'pathashree-IV_survey_' + $.now(),
                    title: 'PATHASHREE-IV SURVEY ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
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
                    title: 'PATHASHREE-IV MASTER',
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