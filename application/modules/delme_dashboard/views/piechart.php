<?php
defined('BASEPATH') or exit('No direct script access allowed');

$status = json_decode($status);
$piechart = array();
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">District wise Overall Project Status</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <?php
            foreach ($status as $row) {
                $piechart[$row->id] = $row->data;
                ?>
                <div class="col-md-6">                
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title"><?= $row->district ?></div>
                        </div>
                        <!-- <a href="report/monitoring" target="_blank">  -->
                        <a href = <?= base_url('report/monitoring/') . $row->id ?> > 

                            <div class="card-body">
                                <div class="chart-container">
                                    <canvas id="piechart_<?= $row->id ?>" style="width: 50%; height: 50%"></canvas>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                <?php
            }
            ?>     
        </div>
    </div>
</div>
<script src="<?= base_url('templates/assets/js/plugin/chart.js/chart.min.js') ?>"></script>
<script>
    $(document).ready(function () {
        var piechart = <?= json_encode($piechart) ?>;
        Object.keys(piechart).forEach(key => {
            console.log(key, piechart[key]);
            var status = piechart[key];
            var statusPieChart = document.getElementById('piechart_' + key).getContext('2d');
            var myPieChart = new Chart(statusPieChart, {
                type: 'pie',
                data: {
                    datasets: [{
                            data: piechart[key],
                            backgroundColor: ["#f3545d", "#1d7af3", "#00FF00"],
                            borderWidth: 0
                        }],
                    labels: ['Under Tender - ' + status[0], 'In Progress - ' + status[1], 'Completed - ' + status[2]]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        labels: {
                            fontColor: 'rgb(154, 154, 154)',
                            fontSize: 11,
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    pieceLabel: {
                        render: 'percentage',
                        fontColor: 'white',
                        fontSize: 14,
                    },
                    tooltips: false,
                    layout: {
                        padding: {
                            left: 20,
                            right: 20,
                            top: 20,
                            bottom: 20
                        }
                    }
                }
            });
        });
    });
</script>