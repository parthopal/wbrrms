<?php
defined('BASEPATH') or exit('No direct script access allowed');
$category = json_decode($category);
$agency = json_decode($agency);
$type = json_decode($type);
$selected = json_decode($selected);
$district = json_decode($district);
$list = json_decode($list);
//var_dump($list);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('scheme/report/ridf') ?>" class="btn btn-secondary btn-round">Report</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div id="view" class="card-body">
                        <?php echo form_open('scheme/rpt_synoptic/' . $sc); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">-- All District--</option>';
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
                                    <label>Tranche*</label>
                                    <select id="category_id" name="category_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="0">--All Tranche Type--</option>
                                        <?php
                                        foreach ($category as $row) {
                                            $_selected = $row->id == $selected->category_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Project Type *</label>
                                    <select id="type_id" name="type_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="0">--All Project Type--</option>
                                        <?php
                                        foreach ($type as $row) {
                                            $_selected = $row->id == $selected->type_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                                    </div>
                                    <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Agency Type *</label>
                                    <select id="agency_id" name="agency_id" class="form-control dropdown" data-live-search="true" required>
                                        <option value="0">--All Agency Type--</option>
                                        <?php
                                        foreach ($agency as $row) {
                                           // var_dump($agency);exit;
                                            $_selected = $row->id == $selected->agency_id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Option</label>
                                    <select id="synoptic_id" name="synoptic_id" class="form-control dropdown">
                                        <option value="0">Select Option</option>
                                        <option value="1" <?= $selected->synoptic_id == '1' ? 'selected' : '' ?>>Projects approved but Tender not invited</option>
                                        <option value="2" <?= $selected->synoptic_id == '2' ? 'selected' : '' ?>>Projects for which tender invited but not matured</option>
                                        <option value="3" <?= $selected->synoptic_id == '3' ? 'selected' : '' ?>>Projects for which tender in First Call</option>
                                        <option value="4" <?= $selected->synoptic_id == '4' ? 'selected' : '' ?>>Projects for which tender in Second Call</option>
                                        <option value="5" <?= $selected->synoptic_id == '5' ? 'selected' : '' ?>>Projects for which tender in Third Call</option>
                                        <option value="6" <?= $selected->synoptic_id == '6' ? 'selected' : '' ?>>Projects for which tender in Fourth Call and above</option>
                                        <option value="7" <?= $selected->synoptic_id == '7' ? 'selected' : '' ?>>Projects for which tender matured but Projects order not issued</option>
                                        <option value="8" <?= $selected->synoptic_id == '8' ? 'selected' : '' ?>>Projects for which Projects order issued but Projects not started</option>
                                        <option value="9" <?= $selected->synoptic_id == '9' ? 'selected' : '' ?>>Projects in 0-10% progress category</option>
                                        <option value="10" <?= $selected->synoptic_id == '10' ? 'selected' : '' ?>>Projects in 10-30% progress category</option>
                                        <option value="11" <?= $selected->synoptic_id == '11' ? 'selected' : '' ?>>Projects in 30-60% progress category</option>
                                        <option value="12" <?= $selected->synoptic_id == '12' ? 'selected' : '' ?>>Projects in 60-99% progress category</option>
                                        <option value="13" <?= $selected->synoptic_id == '13' ? 'selected' : '' ?>>Ongoing Projects</option>
                                        <option value="14" <?= $selected->synoptic_id == '14' ? 'selected' : '' ?>>Projects only physically completed</option>
                                        <option value="15" <?= $selected->synoptic_id == '15' ? 'selected' : '' ?>>Completed Projects</option>
                                        <option value="16" <?= $selected->synoptic_id == '16' ? 'selected' : '' ?>>Not-started Projects</option>
                                        <option value="17" <?= $selected->synoptic_id == '17' ? 'selected' : '' ?>>Slow-moving Projects</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                    <th>Sl.</th>
                                        <th>District</th>
                                        <th>Ridf Tranche</th>
                                        <th>Project Type</th>
                                        <th>Agency</th>
                                         <th>No Of Projects</th> 
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($list) && sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                           // var_dump($list);exit;
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->category . '</td>';
                                            echo '<td>' . $row->type . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->no_of_projects . '</td>';
                                            echo '<td>' . $row->amount . '</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                    <tr>
                                    <th></th>
                                        <th>Total</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        var currentdate = new Date();
        $('#view').show();
        $('#entry').hide();
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
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'synoptic_report_' + $.now(),
                    title: 'SYNOPTIC REPORT OF PATHASHREE ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'SYNOPTIC PROGRESS',
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

        $("#district_id").on("change", function (e) {
            e.preventDefault();
        });
       
        // $("#synoptic_id").on("change", function (e) {
        //     e.preventDefault();
        // });
    });
</script>