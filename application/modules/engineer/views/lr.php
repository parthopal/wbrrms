<?php
defined('BASEPATH') or exit('No direct script access allowed');

$lr = json_decode($lr);
// print_r($lr);exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Leave Records of <?= $lr[0]->name ?></h2>
                    <h5 class="text-white op-7 mb-2">Manage leave document of engineer</h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0"></div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-8">
                <div class="card full-height">
                    <div class="card-header">
                        <h4 class="card-title">Upload all the leave records documents</h4>
                    </div>
                    <div class="card-body">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="reqd">Upload upto (date)</label>
                                <div class="input-group">
                                    <input type="text" id="lr_date" name="lr_date" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= date('d/m/Y') ?>" required>
                                    <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                </div>
                            </div>
                        </div>
                        <form id="frmlr" action="<?= base_url('engineer/upload_lr') ?>" class="dropzone" enctype="multipart/form-data">
                            <div class="dz-message" data-dz-message>
                                <div class="icon">
                                    <i class="flaticon-file"></i>
                                </div>
                                <h4 class="message">Drag and Drop files here</h4>
                                <div class="note">(Upload the <strong>leave records</strong> - pdf copy only.)</div>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" multiple />
                            </div>
                            <input type="hidden" name="engineer_id" value="<?= $engineer_id ?>">
                            <input type="hidden" id="hdn_lr_date" name="hdn_lr_date" value="<?= date('d/m/Y') ?>">
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card full-height">
                    <div class="card-header">
                        <h4 class="card-title">Uploaded documents of leave records</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>#</th>
                                        <th>date</th>
                                        <th style="text-align: center;">Doc</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (strlen($lr[0]->lr_doc) > 0) {
                                        $i = 1;
                                        foreach ($lr as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . date('d/m/Y', strtotime($row->lr_date)) . '</td>';
                                            echo '<td align="center"><i class="fa fa-file-pdf fa-2x pointer" style="color: red;" onclick="_open(\'' . $row->lr_doc . '\')"></i></td>';
                                            echo '<td> <button data-toggle="tooltip" data-placement="bottom" title="Delete"
                                            class="btn btn-danger btn-icon btn-round btn-sm" onclick="_remove(' . $row->id . ')"> <i class="fas fa-trash"></i></button></td>';
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

    $('#frmlr').on('click', function(e) {
        e.preventDefault();
        $('#hdn_lr_date').val($('#lr_date').val());
    });

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

    function _remove(id) {
        if (confirm("Do you want to delete this?")) {
            $.ajax({
                url: baseURL + "/engineer/lr_remove",
                type: "POST",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(res) {
                    if (res.status) {
                        alert(res.message);
                        location.reload();
                    } else {
                        alert("Error: " + res.message);
                    }
                },
                error: function() {
                    alert("Server error! Please try again.");
                }
            });
        }
    }
</script>