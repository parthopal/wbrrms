<?php
defined('BASEPATH') or exit('No direct script access allowed');

$list = json_decode($list);
// echo '<pre>';
// print_r($list); exit;
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <!-- <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2> -->
                    <!-- <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5> -->
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('srrp/report') ?>" class="btn btn-secondary btn-round">Report</a>
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
                                        <th>Sl.</th>
                                        <th>District</th>
                                        <th>Block</th>
										<th>Agency</th>
                                        <th>Road Name</th>
                                        <th>Approval Date</th>
										<th>Tender Date</th>
                                        <th>WO Start Date</th>
                                        <th>WO Completion Date</th>
                                        <th>Approved Length (Km.)</th>
                                        <th>Approved Amount (Lakh Rs.)</th>
                                        <th>Physical Progress</th>
                                        <th>Financial Progress</th>
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
											echo '<td>' . $row->agency . '</td>';
                                            $name = '<small><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></small>';
                                            echo '<td>' . $name . '</td>';
                                            echo '<td>' . date('d/m/Y',strtotime($row->admin_approval_date)). '</td>';
											echo '<td>' . ($row->tender_date== "" ? "" : date('d/m/Y', strtotime($row->tender_date))) . '</td>';
                                            echo '<td>' . ($row->wo_date== "" ? "" : date('d/m/Y', strtotime($row->wo_date))) . '</td>';
                                            echo '<td>' . ($row->completion_date== "" ? "" : date('d/m/Y', strtotime($row->completion_date))) . '</td>';
                                            echo '<td>' . $row->approved_length . '</td>';
                                            echo '<td>' . round($row->cost/100000,2) . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->financial_progress . '</td>';
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
                left: 3
            }
        });
    });
    </script>
