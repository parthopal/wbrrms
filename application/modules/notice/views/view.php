<?php
defined('BASEPATH') or exit('No direct script access allowed');

$notice = json_decode($notice);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                        <button type="button" class="btn btn-success btn-round" onclick="add(0)">
                            <i class="fa fa-plus"></i> &nbsp; Add New
                        </button>
                    </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                  
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Notice/Order</h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Brief Description</th>
                                        <th>Memo No</th>
                                        <th>Date</th>
                                        <th>Document</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($notice as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->name . '</td>';
                                        echo '<td>' . $row->memo_no . '</td>';
                                        echo '<td>' . $row->date . '</td>';
                                        $document = strlen($row->document) ? '<p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="_document(\'' . base_url($row->document) . '\')"  title="Document"><i class="fas fa-file-pdf"></i></button></p>' : '';    
                                         echo '<td>' . $document . '</td>';

                                        echo '<td><p style="margin:0px; width: 80px"><button class="btn btn-icon btn-round btn-sm btn-primary" onclick="add(' . $row->id . ')"  title="Edit"><i class="fas fa-pen pointer"></i></button>&nbsp;<button title="Remove" onclick="remove(' . $row->id . ')" class="btn btn-icon btn-round btn-sm btn-danger"><i class="fas fa-trash pointer" ></i></button></p>
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
<script src="<?= base_url('templates/js/notice.js') ?>"></script>