<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.2/css/fixedColumns.dataTables.min.css">
<style>
    th, td {
        white-space: nowrap;
    }
    div.dataTables_wrapper {
        width: 1000px;
        margin: 0 auto;
    }
</style>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0"></div>
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
                                        <th>Sl.</th>
                                        <th>Name</th>
                                        <th>Submitted On</th>
                                        <th>Work Start Date</th>
                                        <th>Progress</th>
                                        <th>Starting Point</th>
                                        <th>Middle Point</th>
                                        <th>Ending Point</th>
                                        <th>Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i++ . '</td>';
                                            echo '<td><small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small></td>';
                                            echo '<td>' . date('d/m/Y', strtotime($row->created)) . '</td>';
                                            echo '<td>' . date('d/m/Y', strtotime($row->wo_start_date)) . '</td>';
                                            echo '<td>' . ($row->physical_progress == null ? 0 : $row->physical_progress) . '</td>';
                                            $image1 = strlen($row->image1) > 0 ? '<div class="avatar"><a href="' . base_url($row->image1) . '" target="_blank"><img src="' . base_url($row->image1) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img1.jpg') . '" class="avatar-img rounded-circle"></div>';
                                            $loc1 = strlen($row->location1) > 0  ? '<br><small><i>(' . $row->location1. ')</i></small>' : '' ;
                                            echo '<td>' . $image1 .$loc1. '</td>' ;
                                            $image2 = strlen($row->image2) > 0 ? '<div class="avatar"><a href="' . base_url($row->image2) . '" target="_blank"><img src="' . base_url($row->image2) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                            $loc2 = strlen($row->location2) > 0  ? '<br><small><i>(' . $row->location2. ')</i></small>' : '' ;
                                            echo '<td>' . $image2 . $loc2 . '</td>';
                                            $image3 = strlen($row->image3) > 0 ? '<div class="avatar"><a href="' . base_url($row->image3) . '" target="_blank"><img src="' . base_url($row->image3) . '" class="avatar-img rounded-circle"></a></div>' : '<div class="avatar"><img src="' . base_url('templates/img/no_img.jpg') . '" class="avatar-img rounded-circle"></div>';
                                            $loc3 = strlen($row->location3) > 0  ? '<br><small><i>(' . $row->location3. ')</i></small>' : '' ;
                                            echo '<td>' . $image3 . $loc3 . '</td>';
                                            echo '<td>' . $row->progress_remarks . '</td>';
                                            echo '</tr>';
                                        }
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
                left: 2,
                right: 1
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'sfo_work_progress_details_report_' + $.now(),
                    title: 'STATE FUND & OTHERS WORK PROGRESS DETAILS REPORT',
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
                    title: 'STATE FUND & OTHERS WORK PROGRESS DETAILS REPORT',
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