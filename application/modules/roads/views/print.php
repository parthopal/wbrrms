<?php
defined('BASEPATH') or exit('No direct script access allowed');

$print = json_decode($print);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading; ?></h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-10">
                                <h2 class="card-title"><?= $subheading ?></h2>
                            </div>
                            <div class="col-md-2 text-right">
                                <input type="button" value="🖨️ Print" class="btn btn-success" onclick="PrintPanel('<?= $lotno ?>')" />

                                <!-- <button type="button" class="btn btn-success" onclick="PrintPanel('<?= $lotno ?>')">
                                    <i class="fas fa-print"></i> Print
                                </button> -->

                            </div>
                        </div>
                    </div>
                    <div class="card-body" id="printContent">
                        <div style="display:flex; justify-content:space-between; align-items:center;">
                            <div style="flex:1; text-align:center;">
                                <h2 class="text-center">Rural Roads - 2025</h2>
                                <h3 class="text-center">Lot No : <?= $lotno; ?></h3>
                            </div>
                            <div>
                                <img src="/templates/img/logo.png" alt="logo" style="height:80px;">
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="display table ">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>District</th>
                                        <th>AC</th>
                                        <th>Block</th>
                                        <th>GP Name</th>
                                        <th>Name of Road</th>
                                        <th>Implementing Agency</th>
                                        <!-- <th>Ref No</th> -->
                                        <th>Length of Road(KM)</th>
                                        <th>Type of Work</th>
                                        <th>Type of Road</th>
                                        <th>Executable BT <br> Length (km)</th>
                                        <th>Executable CC <br> Length (km)</th>
                                        <th>Total Executable <br> Road Length (km)</th>
                                        <th>Name of New Technology</th>
                                        <th>New Technology Length (km)</th>
                                        <th>Cost for Road Works including <br> Protective work, CD work, etc. (Rs.)</th>
                                        <th>Applicable GST@18% (Rs.)</th>
                                        <th>Labour welfare cess @1% (Rs.)</th>
                                        <th>Total Estimated Cost <br> Excluding Contingency (Rs.)</th>
                                        <th>Per km Estimated Cost <br> excluding Contingency (Rs. in Lakh)</th>
                                        <th>Contingency/Agency Fee for <br> MBL & WBAICL @3% (Rs.)</th>
                                        <th>Vetted Estimated Cost </br> including contingency(Rs.)</th>
                                        <th>Remarks (If any)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    $total_length = 0;
                                    $total_cost = 0;
                                    $total_gst = 0;
                                    $total_cess = 0;
                                    $total_estimated = 0;
                                    $total_est = 0;
                                    $total_con = 0;
                                    $total_per_unit = 0;
                                    $total_bt = 0;
                                    $total_cc = 0;
                                    $total_bt_cc = 0;
                                    $total_new = 0;
                                    foreach ($print as $row) {
                                        $per_unit_lakh = $row->length > 0 ? $row->estimated_amt / $row->length : 0;
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->district . '</td>';
                                        echo '<td>' . $row->ac . '</td>';
                                        echo '<td>' . $row->block . '</td>';
                                        echo '<td>' . $row->gp . '</td>';
                                        echo '<td>' . $row->name . '</td>';
                                        echo '<td>' . $row->agency . '</td>';
                                        // echo '<td>' . $row->ref_no . '</td>';
                                        echo '<td>' . number_format(($row->proposed_length ?? 0), 3) . '</td>';
                                        echo '<td>' . $row->work_type . '</td>';
                                        echo '<td>' . $row->road_type . '</td>';
                                        // echo '<td>' . $row->bt_length . '</td>';
                                        // echo '<td>' . $row->cc_length . '</td>';
                                        // echo '<td>' . number_format($row->bt_length + $row->cc_length, 3) . '</td>';
                                        $bt = $row->bt_length ?? 0;
                                        $cc = $row->cc_length ?? 0;

                                        echo '<td>' . ($bt ?: 0) . '</td>';
                                        echo '<td>' . ($cc ?: 0) . '</td>';
                                        echo '<td>' . number_format(($bt + $cc), 3) . '</td>';

                                        echo '<td>' . $row->new_road_type . '</td>';
                                        echo '<td>' . $row->new_length . '</td>';
                                        // $len = (isset($row->bt_length, $row->cc_length) && ($row->bt_length + $row->cc_length) > 0)
                                        //     ? (float)($row->bt_length + $row->cc_length)
                                        //     : 0;

                                        $len = ($bt + $cc) > 0 ? ($bt + $cc) : 0;
                                        $per_unit = $len > 0 ? ((($row->cost + $row->gst + $row->cess) / $len) / 100000) : 0.00;

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="cost_">' . number_format($row->cost, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="gst_">' . number_format($row->gst, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="cess_">' . number_format($row->cess, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="total_">' . number_format(($row->cost + $row->gst + $row->cess), 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                ' . number_format($per_unit, 3) . '
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="contigency_">' . number_format($row->contigency_amt, 2) . '</span>
                                            </td>';

                                        echo '<td class="fw-bold text-start" style="text-align:right !important;">
                                                <span id="estimated_">' . number_format($row->estimated_amt, 2) . '</span>
                                            </td>';


                                        // echo '<td>' . number_format($per_unit_lakh / 100000, 2) . '</td>';
                                        echo '<td>&nbsp;</td>';
                                        echo '</tr>';
                                        $total_length += $row->proposed_length;
                                        $total_bt += $row->bt_length;
                                        $total_cc += $row->cc_length;
                                        $total_bt_cc = $total_bt + $total_cc;
                                        $total_new += $row->new_length;
                                        $total_cost += $row->cost;
                                        $total_gst += $row->gst;
                                        $total_cess += $row->cess;
                                        $total_estimated = $total_cost + $total_gst + $total_cess;
                                        $total_con += $row->contigency_amt;
                                        $total_est += $row->estimated_amt;
                                        $total_per_unit += $per_unit;
                                        // $avg_total_per_unit = $total_estimated / $total_bt_cc;
                                        $avg_total_per_unit = ($total_bt_cc > 0) ? ($total_estimated / $total_bt_cc) : 0;
                                        $i++;
                                    }
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Total</strong> </td>
                                        <td>&nbsp;</td>
                                        <td><strong> <?= $total_length ?> </strong></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><strong><?= number_format($total_bt, 3) ?></strong></td>
                                        <td><strong><?= number_format($total_cc, 3) ?></strong></td>
                                        <td><strong><?= number_format($total_bt_cc, 3) ?></strong></td>
                                        <td>&nbsp;</td>
                                        <td><strong><?= $total_new ?></strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_cost, 2) ?> </strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_gst, 2) ?> </strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_cess, 2) ?> </strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_estimated, 2) ?> </strong> </td>
                                        <!-- <td style="text-align: right;"><strong><?= number_format($avg_total_per_unit / 100000, 3) ?> L</strong></td> -->
                                        <td style="text-align: right;">
                                            <strong><?= ($avg_total_per_unit > 0) ? number_format($avg_total_per_unit / 100000, 3) . ' L' : '0.000 L' ?></strong>
                                        </td>
                                        <td style="text-align: right;"><strong><?= number_format($total_con, 2) ?> </strong></td>
                                        <td style="text-align: right;"><strong><?= number_format($total_est, 2) ?> </strong></td>
                                        <td>&nbsp;</td>
                                    </tr>
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
    function PrintPanel(lotno) {
        var panel = document.getElementById("printContent");
        var printWindow = window.open('', '', 'width=1200,height=800');
        panel.classList.remove('table-responsive');

        // Add custom styles
        printWindow.document.write('<html><head><title>' + lotno + '</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: landscape; margin: 10mm; }');
        printWindow.document.write('table { font-size: 12px; border-collapse: collapse; width: 100%; }');
        printWindow.document.write('th, td { border: 1px solid #000; padding: 5px; text-align: center; }');
        printWindow.document.write('h2, h3 { text-align: center; }');
        printWindow.document.write('</style></head><body>');

        // Write content
        printWindow.document.write(panel.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Auto print and auto-download as PDF/lotno name (only works in Chrome/Edge)
        printWindow.onload = function() {
            printWindow.document.title = lotno; // file name suggestion
            printWindow.print();
        };

        return false;
    }
</script>