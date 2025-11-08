<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Engineer</h2>
                    <h5 class="text-white op-7 mb-2">Manage service document of engineer</h5>
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
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Designation</th>
                                        <th>Posting</th>
                                        <th>Caste</th>
                                        <th>DoB</th>
                                        <th>Home District</th>
                                        <th>Remarks</th>
                                        <th>Service Book</th>
                                        <th>Leave Records</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        $i = 1;
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->designation . '</td>';
                                            echo '<td>' . $row->posting . '</td>';
                                            echo '<td>' . $row->caste . '</td>';
                                            echo '<td>' . date('d/m/Y', strtotime($row->dob)) . '</td>';
                                            echo '<td>' . $row->home_district . '</td>';
                                            echo '<td>' . $row->remarks . '</td>';
                                            $sb = strlen($row->sb_date) > 0 ? '<i class="fa fa-list-ol fa-2x pointer" onclick="_sb(' . $row->id . ')"></i><br><b>' . date('d/m/Y', strtotime($row->sb_date)) . '</b>' : '<i class="fa fa-upload fa-2x pointer" style="color: grey;" onclick="_sb(' . $row->id . ')"></i>';
                                            echo '<td align="center">' . $sb . '</td>';
                                            $lr = strlen($row->lr_date) > 0 ? '<i class="fa fa-list-ol fa-2x pointer" onclick="_lr(' . $row->id . ')"></i><br><b>' . date('d/m/Y', strtotime($row->lr_date)) . '</b>' : '<i class="fa fa-upload fa-2x pointer" style="color: grey;" onclick="_lr(' . $row->id . ')"></i>';
                                            echo '<td align="center">' . $lr . '</td>';
                                            echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_edit(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button></p></td>';
                                            echo '</tr>';
                                            $i++;
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
    function _open(path) {
        window.open(baseURL + '/' + path, '_blank');
    }
    function _edit(id) {
        window.open(baseURL + '/engineer/entry/' + id, '_self');
    }
    function _sb(id) {
        window.open(baseURL + '/engineer/sb/' + id, '_blank');
    }
    function _lr(id) {
        window.open(baseURL + '/engineer/lr/' + id, '_blank');
    }
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
                right: 3
            },
            buttons: [{
                    extend: 'excel',
                    text: 'Excel',
                    filename: 'ridf_bridge_master_list_' + $.now(),
                    title: 'RIDF BRIDGE MASTER LIST ON ' + String(currentdate.getDate()).padStart(2, '0') + '/' + String((currentdate.getMonth() + 1)).padStart(2, '0') + '/' +
                            currentdate.getFullYear() + ' ' + String(currentdate.getHours()).padStart(2, '0') + ':' + String(currentdate.getMinutes()).padStart(2, '0'),
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
                    title: 'RIDF BRIDGE MASTER LIST ' + $.now(),
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