<?php
defined('BASEPATH') or exit('No direct script access allowed');

$work = json_decode($work);
$prev = json_decode($prev);
$curr = json_decode($curr);
$total = json_decode($total);
// print_r($total);exit;
?>
<style>
    .column-border {
        border-right: 1px solid black !important;
        border-collapse: collapse;
        padding-left: 10px !important;
    }

    @page {
        size: A4 portrait;
        margin: -5mm 5mm 5mm 5mm;

    }

    @media print {
        body {
            visibility: hidden;
        }

        #section-to-print {
            visibility: visible;
            position: relative;
            left: 0;
            top: 0;
        }
    }

    .card-invoice .card-header {
        padding: 15px 0px 0px !important;
    }

    .table-responsive>.table-bordered-black {
        border: 1px solid #000;
    }

    .tr-bordered-bottom-black {
        border-bottom: 1px solid #000;
    }

    .tr-bordered-top-black {
        border-top: 1px solid #000;
    }

    .page-inner {
        padding-right: 0rem;
        padding-left: 0rem;
    }

    .table-bordered th,
    .table-bordered td {
        border: 1px solid #000 !important;
    }

    #tbl {
        width: 100%;
        table-layout: fixed;
        flex-wrap: wrap
    }

    .no-scroll {
        overflow-x: hidden !important;
    }

</style>
<div class="container">
    <div class="page-inner">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-9">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="page-pretitle">
                            Requisition
                        </h6>
                    </div>
                    <div class="col-auto">
                        <button id="print" class="btn btn-primary ml-2">Print</button>
                    </div>
                </div>
                <div class="page-divider"></div>
                <div id="section-to-print" class="row">
                    <div class="col-md-12">
                        <div class="card card-invoice">
                            <div class="card-header" style="width: 95% !important;">
                                <div class="invoice-header">
                                    <div>
                                        <h3 class="invoice-title">Annexure-III</h3>
                                        <div class="pull-left">
                                            District: <?= $work->district ?><br>Block: <?= $work->block ?><br>Agency: <?= $work->agency ?><br>Tranche: <?= $work->category ?>
                                        </div>
                                    </div>
                                    <div class="invoice-logo">
                                        <img src="<?= base_url('templates/img/logo.png') ?>" alt="company logo">
                                    </div>
                                </div>
                                <div class="separator-solid mt--2"></div>
                            </div>
                            <div class="card-body mt-3" style="width: 95% !important;">
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center;">
                                        <h2><?= $work->name ?></h2>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Memo Date:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= $curr->memo_date ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Memo No:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= $curr->memo_no ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">RA:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= $curr->ra_id ?></td>

                                                    </tr>

                                                    <tr>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Physical Progress:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= $curr->physical_progress . '%' ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Sanction Cost:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($work->sanctioned_cost, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">RIDF Loan:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($work->sanctioned_cost * 0.8, 0) ?></td>

                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Awarded Cost:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($work->awarded_cost, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Claimed Allotted:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round ($total->total_approved_claimed_amt , 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Claimed Expenditure :</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($total->total_claimed_expenditure_amt, 0) ?></td>
                                                        
                                                    </tr>

                                                    <tr>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">DPR Amount:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($work->sanctioned_cost * 0.8 * 0.005, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">DPR Allotted:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($total->total_approved_dpr_amt, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">DPR Expenditure:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($total->total_dpr_expenditure_amt, 0) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Contigency Amount:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($work->awarded_cost * 0.03, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Contigency Allotted:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($total->total_approved_contigency_amt, 0) ?></td>
                                                        <th style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;">Contigency Expenditure:</th>
                                                        <td style="text-align: left; height: 30px; border-bottom: 0px; padding: 0 0px !important;"><?= round($total->total_contigency_expenditure_amt, 0) ?></td>
                                                    </tr>

                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="separator-solid"></div>
                                <div class="row">
                                    <div class="col-md-12" style="text-align: center;">
                                        <h4><b>Current Approve Fund Requisition</b></h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            <table id="tbl" class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Claim Amount</th>
                                                        <th>DPR Amount</th>
                                                        <th>Contingency Amount</th>
                                                        <th>Total Amount</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><?= $curr->approved_claimed_amt ?></td>
                                                        <td><?= $curr->approved_dpr_amt ?></td>
                                                        <td><?= $curr->approved_contigency_amt ?></td>
                                                        <td><?= ($curr->approved_claimed_amt + $curr->approved_dpr_amt + $curr->approved_contigency_amt) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="card-footer" style="width: 95% !important;">
                                <?php if (sizeof($prev) != 0) { ?>
                                    <div class="separator-solid"></div>
                                <?php } ?>
                                <?php if (sizeof($prev) > 0) { ?>
                                    <div class="row">
                                        <div class="col-md-12" style="text-align: center;">
                                            <h4><b>Previous Fund Requisition Allotted</b></h4>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table id="tbl" class="display table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>RA</th>
                                                            <th>Claim Date</th>
                                                            <th>Allotted Date</th>
                                                            <th>Claim Amount</th>
                                                            <th>DPR Amount</th>
                                                            <th>contigency <br>Amount</th>
                                                            <!-- <th>Allotted Amount</th> -->
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($prev as $row) {
                                                            echo '<tr>';
                                                            echo '<td>' . $row->ra_id . '</td>';
                                                            echo '<td>' . $row->memo_date . '</td>';
                                                            echo '<td>' . $row->approved_date . '</td>';
                                                            echo '<td>' . $row->approved_claimed_amt . '</td>';
                                                            echo '<td>' . $row->approved_dpr_amt . '</td>';
                                                            echo '<td>' . $row->approved_contigency_amt . '</td>';
                                                            // echo '<td>' . ($row->approved_claimed_amt + $row->approved_dpr_amt + $row->approved_contigency_amt) . '</td>';
                                                            echo '</tr>';
                                                        }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="separator-solid"></div>
                                <p class="text-muted mb-0 pull-right">
                                    *** This is computer generated claimed document
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#print').on('click', function() {
            window.print();
        });
    });
</script>