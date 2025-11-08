<?php
defined('BASEPATH') or exit('No direct script access allowed');
$list = json_decode($list);
$selected = json_decode($selected);
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
                    <div class="card-body">
                    <?php echo form_open('scheme/rpt_workwise_progress/' . $sc); ?>
                      
                                    <div class=row>
                            <div class="col-md-8">
                                <div class="form-group">
                                
                                    <label>Options</label>
            
                                        
                                    <select id="list_id" name="list_id" class="form-control dropdown">
                                    <option value="0">Select Option</option>
                                        <option value="1" <?= $selected->list_id == '1' ? 'selected' : '' ?>>Projects approved but Tender not invited</option>
                                        <option value="2" <?= $selected->list_id == '2' ? 'selected' : '' ?>>Projects for which tender invited but not matured</option>
                                        <option value="3" <?= $selected->list_id == '3' ? 'selected' : '' ?>>Projects for which tender in First Call</option>
                                        <option value="4" <?= $selected->list_id == '4' ? 'selected' : '' ?>>Projects for which tender in Second Call</option>
                                        <option value="5" <?= $selected->list_id == '5' ? 'selected' : '' ?>>Projects for which tender in Third Call</option>
                                        <option value="6" <?= $selected->list_id == '6' ? 'selected' : '' ?>>Projects for which tender in Fourth Call and above</option>
                                        <option value="7" <?= $selected->list_id == '7' ? 'selected' : '' ?>>Projects for which tender matured but Projects order not issued</option>
                                        <option value="8" <?= $selected->list_id == '8' ? 'selected' : '' ?>>Projects for which Projects order issued but Projects not started</option>
                                        <option value="9" <?= $selected->list_id == '9' ? 'selected' : '' ?>>Projects in 0-10% progress category</option>
                                        <option value="10" <?= $selected->list_id == '10' ? 'selected' : '' ?>>Projects in 10-30% progress category</option>
                                        <option value="11" <?= $selected->list_id == '11' ? 'selected' : '' ?>>Projects in 30-60% progress category</option>
                                        <option value="12" <?= $selected->list_id == '12' ? 'selected' : '' ?>>Projects in 60-99% progress category</option>
                                        <option value="13" <?= $selected->list_id == '13' ? 'selected' : '' ?>>Ongoing Projects</option>
                                        <option value="14" <?= $selected->list_id == '14' ? 'selected' : '' ?>>Projects only physically completed</option>
                                        <option value="15" <?= $selected->list_id == '15' ? 'selected' : '' ?>>Completed Projects</option>
                                        <option value="16" <?= $selected->list_id == '16' ? 'selected' : '' ?>>Not-started Projects</option>
                                        <option value="17" <?= $selected->list_id == '17' ? 'selected' : '' ?>>Slow-moving Projects</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
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
                                        <th>Agency</th>
                                        <th>Project ID</th>
                                        <th>Project Name</th>
                                        <th>RIDF Tranche</th>
                                        <th>Project Type</th>
                                        <th>Road length</th>
                                        <th>Approved Amount</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->scheme_id . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->category . '</td>';
                                            echo '<td>' . $row->type . '</td>';
                                            echo '<td>' . $row->length . '</td>';
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
     var currentdate = new Date();
    $(document).ready(function () {
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
                left: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'state_summary_report_' + $.now(),
                    // title: 'STATE SUMMARY REPORT',
                    title: 'STATE SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'STATE SUMMARY REPORT',
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