 <?php
defined('BASEPATH') or exit('No direct script access allowed');

// $selected = json_decode($selected);
$list = json_decode($list);
// print_r($list); exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Sl No.</th>
                                        <th>District</th>
                                        <th>Total Bridge (No.)</th>
                                        <th>Total Length (Meter)</th>
                                        <th>Good Condition (No.)</th>
                                        <th>Length (Meter)</th>
                                        <th>Poor Condition (No.)</th>
                                        <th>Length (Meter)</th>
                                        <th>Highly Vulnerable (No.)</th>
                                        <th>Length (Meter)</th>
                                        <th>Locked(No.)</th>
                                        <th>Drafted(No.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($list) && sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                           echo '<tr>';
                                            echo '<td>' . $i++ .'</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->total_bridge . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>'. $row->good .'</td>';
                                            echo '<td>'. $row->good_length.'</td>';
                                            echo '<td>'. $row->poor .'</td>';
                                            echo '<td>'. $row->poor_length.'</td>';
                                            echo '<td>'. $row->highly .'</td>';
                                            echo '<td>'. $row->highly_length.'</td>';
                                            echo '<td>'. $row->locked .'</td>';
                                            echo '<td>'. $row->drafted .'</td>';
                                            echo '</tr>';
                                        }
                                    }
                                    ?>
                                </tbody>
                                <tfoot align="right">
                                    <tr>
                                        <th></th>
                                        <th>Total</th>
                                        <th><?= array_sum(array_column($list, 'total_bridge')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'length')),3) ?></th>
                                        <th><?= array_sum(array_column($list, 'good')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'good_length')),3) ?></th>
                                        <th><?= array_sum(array_column($list, 'poor')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'poor_length')),3) ?></th>
                                        <th><?= array_sum(array_column($list, 'highly')) ?></th>
                                        <th><?= round(array_sum(array_column($list, 'highly_length')),3) ?></th>
                                        <th><?= array_sum(array_column($list, 'locked')) ?></th>
                                        <th><?= array_sum(array_column($list, 'drafted')) ?></th>
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
                left: 4,
				right: 2
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'brideg_state_summary_report_' + $.now(),
                    title: 'BRIDGE STATE SUMMARY REPORT ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/'
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
                    title: 'BRIDGE STATE SUMMARY REPORT',
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
    //setTimeout(function() {
    //        location.reload();
    //    }, 100000);
</script>