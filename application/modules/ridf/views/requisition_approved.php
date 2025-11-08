<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$category = json_decode($category);
$selected = json_decode($selected);
$list = json_decode($list);


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
                            <div class="col-md-10">
                                <!-- <h2><?= $title ?></h2> -->
                            </div>

                        </div>
                        <hr>
                        <?php echo form_open('ridf/requisition_approved'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <option value="0" selected>--Select District--</option>
                                        <?php
                                        foreach ($district as $row) {
                                            $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                            echo "<option value='{$row->id}' {$_selected}>{$row->name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Tranche</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown">
                                        <option value="0">--Select Tranche--</option>
                                        <?php
                                        foreach ($category as $row) {
                                            $_selected = ($selected->category_id > 0 && $selected->category_id == $row->id) ? 'selected' : '';
                                            echo "<option value='{$row->id}' {$_selected}>{$row->name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 text-right">
                                <div class="form-group">
                                    <button type="submit" id="search_requisition" name="search_requisition" class="btn btn-primary">
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
                <div class="card full-height">
                    <div class="card-body">
                        <div class="table-responsive" style="overflow: auto;">
                            <table id="tbl_requisition" class="display nowrap table table-bordered table-striped table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Work Name</th>
                                        <th>RA</th>
                                        <th>District</th>
                                        <th>Tranche</th>
                                        <th>Memo No</th>
                                        <th>Memo Date</th>
                                        <th>Ref No</th>
                                        <th>Physical Progress</th>
                                        <th>Claimed Amount</th>
                                        <th>DPR Amount</th>
                                        <th>Contigency Amount</th>
                                        <th>Approved Date</th>
                                        <th>Approved Claimed Amount</th>
                                        <th>Approved DPR Amount</th>
                                        <th>Approved Contigency Amount</th>
                                        <th>Claimed Expenditure Amount</th>
                                        <th>DPR Expenditure Amount</th>
                                        <th>Contigency Expenditure Amount</th>
                                        <th>Claimed Doc</th>
                                        <th>Approved Doc</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->ra_id . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->category . '</td>';
                                        echo '<td>' . $row->memo_no . '</td>';
                                        echo '<td>' . $row->memo_date . '</td>';
                                        echo '<td>' . $row->ref_no . '</td>';
                                        echo '<td>' . $row->physical_progress . '</td>';
                                        echo '<td>' . $row->claimed_amt . '</td>';
                                        echo '<td>' . $row->dpr_amt . '</td>';
                                        echo '<td>' . $row->contigency_amt . '</td>';
                                        echo '<td>' . $row->approved_date . '</td>';
                                        echo '<td>' . $row->approved_claimed_amt . '</td>';
                                        echo '<td>' . $row->approved_dpr_amt . '</td>';
                                        echo '<td>' . $row->approved_contigency_amt . '</td>';
                                        echo '<td>' . $row->claimed_expenditure_amt . '</td>';
                                        echo '<td>' . $row->dpr_expenditure_amt . '</td>';
                                        echo '<td>' . $row->contigency_expenditure_amt . '</td>';

                                        echo '<td align="center">' . (strlen($row->claimed_doc) > 0 ? '<a href="' . base_url($row->claimed_doc) . '" target="_blank"><i class="fa fa-file-pdf fa-2x pointer" style="color: red;"></i></a>' : '') . '</td>';
                                        echo '<td align="center">' . (strlen($row->approved_doc) > 0 ? '<a href="' . base_url($row->approved_doc) . '" target="_blank"><i class="fa fa-file-pdf fa-2x pointer" style="color: red;"></i></a>' : '') . '</td>';

                                        $preview = strlen($row->ref_no) > 0 && strlen($row->approved_date) != NULL && empty($row->approved_doc) ? '<a href="' . base_url('ridf/requisition_approve_preview/' . $row->ridf_id . '/' . $row->id) . '" target="_blank" title="preview of requisition"><i class="fa fa-file-pdf fa-2x pointer" style="color: red;"></i></a>' : '';
                                        $final_submission = strlen($row->ref_no) > 0 && strlen($row->approved_date) != NULL && empty($row->approved_doc) ? '<a href="' . base_url('ridf/requisition_final_approval/' . $row->ridf_id . '/' . $row->id) . '" target="_self" title="upload signed copy of Approved document"><i class="fa fa-upload fa-2x pointer" style="color: grey;"></i>' : '';



                                        // echo '<td align="center"><p style="margin:0px; width: 80px">' . $preview . '&nbsp;&nbsp;' . $final_submission . '&nbsp;&nbsp;<a href="' . base_url('ridf/requisition_approve_entry/' . $row->ridf_id . '/' . $row->id) . '" target="_self"><button class="btn btn-icon btn-round btn-sm btn-success" title="Approved"><i class="fas fa-check-circle pointer"></i></button></a></p></td>';


                                        $approved = empty($row->approved_doc) ? '<a href="' . base_url('ridf/requisition_approve_entry/' . $row->ridf_id . '/' . $row->id) . '" target="_self">
                                            <button class="btn btn-icon btn-round btn-sm btn-success" title="Approved">
                                                <i class="fas fa-check-circle pointer"></i>
                                            </button>
                                        </a>' : '';

                                        echo '<td align="center">
                                                <p style="margin:0px; width: 80px">' . $preview . '&nbsp;&nbsp;' . $final_submission . '&nbsp;&nbsp;' . $approved . '</p>
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
    $(document).ready(function() {
        var currentdate = new Date();
        $('#tbl_requisition').DataTable({
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
                right: 3
            },
            buttons: [{
                extend: 'excel',
                text: 'Excel',
                filename: 'ridf_requisition_' + $.now(),
                title: 'RIDF - Requisition MASTER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
                    currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
                footer: true,
                exportOptions: {
                    columns: ':not(.not-export)'
                },
                customize: function(xlsx) {
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $('row c', sheet).attr('s', '25');
                }
            }]
        });
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });
    });
</script>