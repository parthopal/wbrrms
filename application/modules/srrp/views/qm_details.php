<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
$caption = json_decode($caption);
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
                <div class="card">
                    <!--                    <div class="card-header">
                                            <div class="row ml-1">
                                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back_to_assignment()">
                                                    <i class="fas fa-arrow-left"></i>
                                                </button>
                                            </div>
                                        </div>-->
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><?= $caption[0]->sqm ?></label>
                                    </div>
                                </div>
                                <div class="col-md-4 text-right">
                                    <div class="form-group">
                                        <label><?= $caption[0]->month . ' / ' . $caption[0]->year ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                    <thead>
                                        <tr class="thead-light">
                                            <th>Sl.</th>
                                            <th>District</th>
                                            <th>Block</th>
                                            <th>Road name</th>
                                            <th>Length</th>
                                            <th>Agency</th>
                                            <th>Progress</th>
                                            <th>Amount</th>
                                            <th>image1</th>
                                            <th>Image2</th>
                                            <th>Image3</th>
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
                                                echo '<td>' . $row->block . '</td>';
                                                $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                                echo '<td>' . $name . '</td>';
                                                echo '<td>' . $row->length . '</td>';
                                                echo '<td>' . $row->agency . '</td>';
                                                echo '<td>' . $row->physical_progress . '</td>';
                                                echo '<td>' . round($row->awarded_cost / 100000, 2) . '</td>';
                                                $image1 = strlen($row->image1) > 0 ? '<div class="avatar"><a href="' . base_url($row->image1) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                                echo '<td>' . $image1 . '</td>';
                                                $image2 = strlen($row->image2) > 0 ? '<div class="avatar"><a href="' . base_url($row->image2) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                                echo '<td>' . $image2 . '</td>';
                                                $image3 = strlen($row->image3) > 0 ? '<div class="avatar"><a href="' . base_url($row->image3) . '" target="_blank"><img src="' . base_url('templates/img/blank_img.jpg') . '" class="avatar-img rounded-circle"></a></div>' : '';
                                                echo '<td>' . $image3 . '</td>';
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
                left: 3
            },
            buttons: [
                {
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'qmview_' + $.now(),
                    title: 'QM-WISE REPORT',
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
                    title: 'QM-WISE REPORT',
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
<script src="<?= base_url('templates/js/srrp_qm.js') ?>"></script>