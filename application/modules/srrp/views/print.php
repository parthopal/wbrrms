<?php

defined('BASEPATH') or exit('No direct script access allowed');

$print = json_decode($print);
//agency, gp, name, length, work_type, road_type, cost


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
                                 <input type="button" value="Print" class="btn btn-success" onclick="PrintPanel()" ?/>
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body" id="printContent">
                        <h2 class="text-center">RASTASHREE 2023</h2>
                        <h3 class="text-center">Lot No : <?php echo $lotno; ?></h3>
                        <div class="table-responsive">
                            <table class="display table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Sl. No.</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Executing Agency (Block/Zilla Parishad)</th>
                                        <th>GP Name</th>
                                        <th>Name of Road</th>
                                        <th>Length of Road(KM)</th>
                                        <th>Nature of Work (New Construction/ Repair/ Upgradation)</th>
                                        <th>Type of Surface (Bituminous/Concrete)</th>
                                        <th>Vetted Estimated Cost (Rs.)</th>
                                        <th>Per Km Cost (Rs. in Lakh)</th>
                                        <th>Remarks (If any)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    <?php
                                        $i = 1;
                                        $total = 0;
                                        foreach ($print as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->gp . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->length . '</td>';
                                            echo '<td>' . $row->work_type . '</td>';
                                            echo '<td>' . $row->road_type . '</td>';
                                            echo '<td>' . $row->cost . '</td>';
                                            echo '<td>' . round($row->cost/$row->length/100000, 2) . '</td>';
                                            echo '<td>&nbsp;</td>';
                                            echo '</tr>';
                                            $total += $row->cost;
                                            $i++;
                                        }
                                        ?>
                                    <tr>
                                        <td colspan="6" class="text-right"><strong>Total</strong> </td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><?= number_format((float) $total, 2, '.', '') ?></td>
                                        <td>&nbsp;</td>
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
    function PrintPanel() {
        var panel = document.getElementById("printContent");
        var printWindow = window.open('', '', '');
        panel.classList.remove('table-responsive');

        // Make sure the relative URL to the stylesheet works:
      //  printWindow.document.write('<base href="' + location.origin + location.pathname + '">');

        // Add the stylesheet link and inline styles to the new document:
        printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">');
        printWindow.document.write('<style> .table-custom.table thead th {text-align: center;}</style>')

        printWindow.document.write('</head><body >');
        printWindow.document.write(panel.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        setTimeout(function() {
            printWindow.print();
        }, 500);
        return false;
    }
</script>