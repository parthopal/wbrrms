<?php
defined('BASEPATH') or exit('No direct script access allowed');

$color = array('primary', 'secondary', 'success', 'warning', 'danger');
$dashboard_count = json_decode($dashboard_count, true);
$districtData = json_decode($district_chart, true);

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
                            <div class="card-title">Rural Roads(2025) Scheme Summary</div>
                        </div>
                    </div>
                    <div class="card-body mt-3">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #006400 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Approved Schemes (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['approved_scheme'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-secondary card-round" style="background-color: #008000 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Approved Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-success card-round" style="background-color: #38b000 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Sanctioned Amount (Cr.)</p>
                                                    <!-- <h4 class="card-title fw-bold"><?= round(($dashboard_count['sanctioned_amount'] / 10000000), 2) ?></h4> -->
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['sanctioned_amount'] / 10000000, 2 ?? 0) ?></h4>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-warning card-round" style="background-color: #00a6fb  !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Tender Invited&nbsp;&nbsp; (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['tender_invited'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #0582ca !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Tender Matured (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['tender_matured'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #006494 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Matured Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['lender_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card card-stats card-primary card-round" style="background-color: #003554 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category h3">Matured Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['tender_amount'] / 10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #bb3e03 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['wo_no'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-primary card-round" style="background-color: #ca6702 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['wo_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #ee9b00 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Work Order Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['wo_amount'] / 10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #7b2cbf !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Progress (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['ongoing'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #5a189a !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['ongoing_length'] ?? 0.00 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #3c096c !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Ongoing Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['ongoing_amount'] / 10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #023e7d !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed (No.)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['completed'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #0353a4 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed Length (KM)</p>
                                                    <h4 class="card-title fw-bold"><?= $dashboard_count['completed_length'] ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card card-stats card-warning card-round" style="background-color: #0466c8 !important;">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-stats">
                                                <div class="numbers">
                                                    <p class="card-category">Completed Amount (Cr.)</p>
                                                    <h4 class="card-title fw-bold"><?= round($dashboard_count['completed_amount'] / 10000000, 2) ?? 0 ?></h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php
    if ($role_id < 3) {
    ?>
        <div class="page-inner mt--5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">District-wise Road Overview (2025)</h4>
                        </div>
                        <div style="height:550px; width:100%;">
                            <canvas id="districtBarChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0">District-wise Survey Completion (2025)</h4>
                        </div>
                        <div style="height:550px; width:100%;">
                            <canvas id="surveyBarChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php
    }
    ?>
</div>





<script src="<?= base_url('templates/assets/js/plugin/chart.js/chart.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="<?= base_url('templates/assets/js/plugin/chart-circle/circles.min.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') ?>"></script>

<script src="<?= base_url('templates/assets/js/plugin/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/jqvmap/maps/jquery.vmap.world.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/gmaps/gmaps.js') ?>"></script>
<script src="<?= base_url('templates/assets/js/plugin/dropzone/dropzone.min.js') ?>"></script>


<script>
    const districtData = <?= json_encode($districtData ?? []); ?>;

    if (districtData.length > 0) {
        // Sort districts descending by total roads
        districtData.sort((a, b) => (parseInt(b.total_roads) || 0) - (parseInt(a.total_roads) || 0));

        // Prepare labels (first 3 words + total count)
        const labels = districtData.map(item => {
            return `${item.district_name} (${item.total_roads})`;
        });


        // Values
        const values = districtData.map(item => parseInt(item.total_roads) || 0);

        const ctx = document.getElementById('districtBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Roads',
                    data: values,
                    backgroundColor: 'rgba(0, 123, 255, 0.6)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(0, 123, 255, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        bottom: 50
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 75,
                            minRotation: 55,
                            font: {
                                size: 12,
                                weight: 'bold' // ✅ Bold labels
                            },
                            color: '#000'
                        },
                        title: {
                            display: true,
                            text: 'Districts',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Total Roads (Nos.)',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#000'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'District-wise Road Overview (2025)',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Roads';
                            }
                        }
                    }
                }
            }
        });
    }

    // survey_complete

    if (districtData.length > 0) {
        // Sort districts descending by survey_completed
        districtData.sort((a, b) => (parseInt(b.survey_completed) || 0) - (parseInt(a.survey_completed) || 0));

        const labels = districtData.map(item => {
            return `${item.district_name} (${item.survey_completed} %)`;
        });

        // Values based on survey_completed
        const values = districtData.map(item => parseInt(item.survey_completed) || 0);

        const ctx = document.getElementById('surveyBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Survey Completed',
                    data: values,
                    backgroundColor: 'rgba(0, 123, 255, 0.6)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 1,
                    borderRadius: 6,
                    hoverBackgroundColor: 'rgba(0, 123, 255, 0.8)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        bottom: 50
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            autoSkip: false,
                            maxRotation: 75,
                            minRotation: 55,
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#000'
                        },
                        title: {
                            display: true,
                            text: 'Districts',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Survey Completed (Nos.)',
                            font: {
                                size: 13,
                                weight: 'bold'
                            }
                        },
                        ticks: {
                            font: {
                                size: 12,
                                weight: 'bold'
                            },
                            color: '#000'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'District-wise Survey Completion (2025)',
                        font: {
                            size: 16,
                            weight: 'bold'
                        },
                        color: '#333'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + '% Surveys Completed';
                            }
                        }
                    }
                }
            }
        });
    }
    // 
    else {
        document.getElementById('districtBarChart').outerHTML =
            '<p class="text-center text-muted mt-4">No district data available.</p>';
    }
</script>