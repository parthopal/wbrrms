<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('primary', 'secondary', 'success', 'warning', 'danger');
$count = json_decode($count);
$condition = json_decode($condition);
$total = sizeof($condition) > 0 ? $condition[0]->cnt + (sizeof($condition) > 1 ? $condition[1]->cnt : 0) + (sizeof($condition) > 2 ? $condition[2]->cnt : 0) : 0;
$entry = json_decode($entry);
$label = explode(",", $entry->label);
$label = "'" . implode("', '", $label) . "'";
$category = json_decode($category);
$ownership = json_decode($ownership);
$dc = json_decode($dc);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-6">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="card-title">Overall statistics</div>
                        <div class="card-category">Bridge information in system</div>
                        <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="total"></div>
                                <h6 class="fw-bold mt-3 mb-0">Total</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="drafted"></div>
                                <h6 class="fw-bold mt-3 mb-0">Drafted</h6>
                            </div>
                            <div class="px-2 pb-2 pb-md-0 text-center">
                                <div id="locked"></div>
                                <h6 class="fw-bold mt-3 mb-0">Locked</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if ($total > 0) {
                ?>
                <div class="col-md-6">
                    <div class="card full-height">
                        <div class="card-body">
                            <div class="card-title">Overall statistics</div>
                            <div class="card-category">Bridge condition in system</div>
                            <div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="good"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Good</h6>
                                </div>
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="poor"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Poor</h6>
                                </div>
                                <div class="px-2 pb-2 pb-md-0 text-center">
                                    <div id="hv"></div>
                                    <h6 class="fw-bold mt-3 mb-0">Highly Vulnerable</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Day wise total entry</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="entry"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bridge ownership in system</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="ownership"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bridge substructural material in system</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="material" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bridge type in system</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="category" style="width: 50%; height: 50%"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Bridge condition district wise in the system</div>
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dc"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/dashboard.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/chart.js/chart.min.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/chart-circle/circles.min.js') ?>"></script>
<script>
    Circles.create({
        id: 'total',
        radius: 45,
        value: <?= $count->district > 0 ? round($count->district / 22 * 100) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= $count->total > 0 ? $count->total : 0 ?>,
        colors: ['#f1f1f1', '#2BB930'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });
    Circles.create({
        id: 'drafted',
        radius: 45,
        value: <?= $count->drafted > 0 ? round(($count->district / 22 / $count->total) * 100 * $count->drafted) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= $count->drafted ?>,
        colors: ['#f1f1f1', '#FF9E27'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });
    Circles.create({
        id: 'locked',
        radius: 45,
        value: <?= $count->locked > 0 ? round(($count->district / 22 / $count->total) * 100 * $count->locked) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= $count->locked > 0 ? $count->locked : 0 ?>,
        colors: ['#f1f1f1', '#F25961'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });

    Circles.create({
        id: 'good',
        radius: 45,
        value: <?= sizeof($condition) > 0 ? round($condition[0]->cnt / $total * 100) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= $condition[0]->cnt ?>,
        colors: ['#f1f1f1', '#2BB930'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });
    Circles.create({
        id: 'poor',
        radius: 45,
        value: <?= sizeof($condition) > 1 ? round($condition[1]->cnt / $total * 100) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= sizeof($condition) > 1 ? $condition[1]->cnt : 0 ?>,
        colors: ['#f1f1f1', '#FF9E27'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });

    Circles.create({
        id: 'hv',
        radius: 45,
        value: <?= sizeof($condition) > 2 ? round($condition[2]->cnt / $total * 100) : 1 ?>,
        maxValue: 100,
        width: 7,
        text: <?= sizeof($condition) > 2 ? $condition[2]->cnt : 0 ?>,
        colors: ['#f1f1f1', '#F25961'],
        duration: 400,
        wrpClass: 'circles-wrp',
        textClass: 'circles-text',
        styleWrapper: true,
        styleText: true
    });
    var lineChart = document.getElementById('entry').getContext('2d'),
            barChart = document.getElementById('ownership').getContext('2d'),
            doughnutChart = document.getElementById('category').getContext('2d'),
            multipleBarChart = document.getElementById('dc').getContext('2d');
    var myLineChart = new Chart(lineChart, {
        type: 'line',
        data: {
            labels: [<?= $label ?>],
            datasets: [{
                    label: "total entry",
                    borderColor: "#1d7af3",
                    pointBorderColor: "#FFF",
                    pointBackgroundColor: "#1d7af3",
                    pointBorderWidth: 2,
                    pointHoverRadius: 4,
                    pointHoverBorderWidth: 1,
                    pointRadius: 4,
                    backgroundColor: 'transparent',
                    fill: true,
                    borderWidth: 2,
                    data: [<?= $entry->data ?>]
                }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom',
                labels: {padding: 10, fontColor: '#1d7af3'}
            },
            tooltips: {
                bodySpacing: 4,
                mode: "nearest",
                intersect: 0,
                position: "nearest",
                xPadding: 10,
                yPadding: 10,
                caretPadding: 10
            },
            layout: {
                padding: {left: 15, right: 15, top: 15, bottom: 15}
            }
        }
    });

    var myBarChart = new Chart(ownership, {
        type: 'bar',
        data: {
            labels: [<?= $ownership->name ?>],
            datasets: [{
                    label: "ownership",
                    backgroundColor: 'rgb(23, 125, 255)',
                    borderColor: 'rgb(23, 125, 255)',
                    data: [<?= $ownership->cnt ?>]
                }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        }
    });

    var myPieChart = new Chart(material, {
        type: 'pie',
        data: {
            datasets: [{
                    data: [<?= $material ?>],
                    backgroundColor: ["#1d7af3", "#fdaf4b", "#f3545d"],
                    borderWidth: 0
                }],
            labels: ['Concrete', 'Brickwork', 'Wooden/Bamboo']
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
                fontSize: 14
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

    var myDoughnutChart = new Chart(category, {
        type: 'doughnut',
        data: {
            datasets: [{
                    data: [<?= $category->cnt ?>],
                    backgroundColor: ['#f3545d', '#1d7af3', '#fdaf4b', '#2BB930']
                }],
            labels: [<?= $category->name ?>]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
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

    var myMultipleBarChart = new Chart(dc, {
        type: 'bar',
        data: {
            labels: [<?= $dc->name ?>],
            datasets: [{
                    label: "Good",
                    backgroundColor: '#177dff',
                    borderColor: '#177dff',
                    data: [<?= $dc->good ?>]
                }, {
                    label: "Poor",
                    backgroundColor: '#fdaf4b',
                    borderColor: '#fdaf4b',
                    data: [<?= $dc->poor ?>]
                }, {
                    label: "Highly Vulnerable",
                    backgroundColor: '#f3545d',
                    borderColor: '#f3545d',
                    data: [<?= $dc->hv ?>]
                }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            title: {
                display: true,
                text: 'Bridge Condition'
            },
            tooltips: {
                mode: 'index',
                intersect: false
            },
            responsive: true,
            scales: {
                xAxes: [{
                        stacked: true
                    }],
                yAxes: [{
                        stacked: true
                    }]
            }
        }
    });
</script>