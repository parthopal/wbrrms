<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('primary', 'secondary', 'success', 'warning', 'danger');
$overview_count = json_decode($overview_count);
// print_r($overview_count); e
// $tender_and_wo_count = json_decode($tender_and_wo_count);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Overview</h2>
                    <h5 class="text-white op-7 mb-2">Current Project Status</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-head-row">
                            <div class="card-title">RIDF-Bridge Overview</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="div-col-6">
                            <div class="card-body mt-3">
                                <div class="chart-container" style="position: relative; height:300px; width: 600px;">
                                    <p>
                                        <?php
                                        foreach ($overview_count as $row) {
                                            echo '<input type ="hidden" id ="total" value=' . $row->total . '>';
                                            echo '<input type ="hidden" id ="bridge_delete" value=' . $row->bridge_delete . '>';
                                            echo '<input type ="hidden" id ="bridge_approve" value=' . $row->approve . '>';
                                        } ?>
                                    </p>
                                    <canvas id="bridge_overview"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="div-col-6">
                            <div class="chart-container" style="position: relative; height:300px; width: 600px;">
                                <p>
                                    <?php
                                    foreach ($overview_count as $row) {
                                        echo '<input type ="hidden" id ="total" value=' . $row->total . '>';
                                        echo '<input type ="hidden" id ="bridge_delete" value=' . $row->bridge_delete . '>';
                                    } ?>
                                </p>
                                <canvas id="bridge_overview"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const overview = document.getElementById('bridge_overview');
    let total = document.getElementById('total').value;
    let total_delete = document.getElementById('bridge_delete').value;
    let total_approve = document.getElementById('approve').value;
    new Chart(overview, {
        type: 'pie',
        data: {
            labels: ['Total Entry', 'Delete Bridge', 'Approve Bridge'],
            datasets: [{
                data: [total, total_delete, approve],
            }]
        },
        options: {
            maintainAspectRatio: false,
            cutoutPercentage: 65,
        }
    });
</script>