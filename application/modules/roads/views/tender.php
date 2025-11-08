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
                                    <button type="button" id="search_tender" name="search_tender" class="btn btn-primary mt-4">
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
                                        <th>Length (KM)</th>
                                        <th>Agency</th>
                                        <th>Vetted Estimated Cost </br> including contingency (Rs.)</th>
                                        <th>Tender Number</th>
                                        <th>Tender Publication Date</th>
                                        <th>Tender Status</th>
                                        <th>BID Closing Date</th>
                                        <th>BID Opening date</th>
                                        <th>Evaluation Status</th>
                                        <th>BID Opening Status</th>
                                        <th>BID Matured Status</th>
                                        <?php if ($role_id != 12) { ?>
                                            <th>Action</th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($approved as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->gp . '</td>';
                                        echo '<td>' . $row->ref_no . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                        echo '<td>' . $row->length . '</td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        echo '<td>' . $row->estimated_amt . '</td>';
                                        echo '<td>' . $row->tender_number . '</td>';
                                        echo '<td>' . $row->tender_publication_date . '</td>';
                                        echo '<td>' . ($row->tender_status == 0 ? 'Not Started' : ($row->tender_status == 1 ? 'On Progress' : ($row->tender_status == 2 ? 'Completed' : 'Retendering'))) . '</td>';
                                        echo '<td>' . $row->bid_closing_date . '</td>';
                                        echo '<td>' . $row->bid_opeaning_date . '</td>';
                                        echo '<td>' . ($row->evaluation_status == 0 ? 'No' : ($row->evaluation_status == 1 ? 'Yes' : '')) . '</td>';
                                        echo '<td>' . ($row->bid_opening_status == 0 ? 'No' : ($row->bid_opening_status == 1 ? 'Yes' : '')) . '</td>';
                                        echo '<td>' . ($row->bid_matured_status == 0 ? 'No' : ($row->bid_matured_status == 1 ? 'Yes' : '')) . '</td>';
                                        if ($role_id != 12) {
                                            echo '<td><p style="margin:0px; width: 90px"><button data-toggle="tooltip" data-placement="bottom"  class="btn btn-icon btn-round btn-sm btn-primary" onclick="_edit_tender(' . $row->id . ',' . $row->tender_status . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p>
                                                </td>';
                                        }
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
    <?php include_once 'tender_benefitted_modal.php'; ?>
</div>
<script>

    function _edit_tender(id, tender_status) {
        if (tender_status == 0) {
            $('#id').val(id);
            $('#total_population').val('');
            $('#total_households').val('');
            $('#no_of_village').val('');
            $('#benefitted_modal').modal('show');
        } else {
            tender_entry(id)
        }
    }
</script>
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
                right: 1
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'RuralRoads_tender_' + $.now(),
                    title: 'RURAL ROADS(2025) TENDER ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'RURAL ROADS(2025) TENDER',
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
<script src="<?= base_url('templates/js/roads.js') ?>"></script>
