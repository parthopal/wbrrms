<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading; ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <h2><?= $title ?></h2>
                            </div>
                            <div class="col-md-2 text-right">
                                <a href="<?= base_url('fund/entry/' . $category . '/' . $activity_id) ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fund Type</th>
                                        <th>Order Date</th>
                                        <th>Order No</th>
                                        <th>Amount</th>
                                        <th>Document</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->expenditure . '</td>';
                                        echo '<td>' . date('d/m/Y', strtotime($row->order_date)) . '</td>';
                                        echo '<td>' . $row->order_no . '</td>';
                                        echo '<td>' . $row->amount . '</td>';
                                        echo '<td>' . $row->document . '</td>';
                                        echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="top" title="' . $row->remarks . '">' . $row->remarks . '</p></td>';
                                        echo '<td><a href="' . base_url('fund/entry/' . $category . '/' . $activity_id . '/' . $row->id) . '"><button class="btn btn-icon btn-round btn-sm btn-primary" title="Edit"><i class="fas fa-pen pointer"></i></button></a></td>';
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