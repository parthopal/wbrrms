<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
?>

<div class="container">
    <!-- Header -->
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

    <!-- Main Body -->
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <!-- Back Button -->
                        <div class="row mb-3">
                            <div class="col-md-1">
                                <a href="<?= base_url('ridf/postdlp_maintenance') ?>">
                                    <button type="button" class="btn btn-icon btn-round btn-primary" selected-toggle="tooltip" selected-placement="top" title="Back">
                                        <i class="fas fa-arrow-left"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-11 text-center">
                                <div class="col-md-12">
                                    <h2 class="mb-1"><?= $selected->name ?></h2>
                                    <p class="text-muted mb-0"><?= $selected->scheme_id ?> / <?= $selected->agency ?></p>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row align-items-center mb-4 p-3 shadow-sm bg-light">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <div><strong>District:</strong> <?= $selected->district ?? '-' ?></div>
                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">
                                <div><strong>Block:</strong> <?= $selected->block ?? '-' ?></div>
                            </div>

                            <div class="col-md-6" style="text-align: center">
                                <?php if (!empty($selected->estimation_file)) : ?>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill shadow d-inline-flex align-items-center"
                                        onclick="_document('<?= base_url($selected->estimation_file); ?>')"
                                        title="View Estimation Document">
                                        <i class="fas fa-file-pdf me-2"></i> &nbsp; View Estimation File
                                    </button>
                                <?php else: ?>
                                    <span class="text-muted fst-italic">No document uploaded</span>
                                <?php endif; ?>
                            </div>

                        </div>

                        <h5 class="text-secondary mb-3">Road Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <strong>Carriage Way Type:</strong>
                                <?php
                                $carriageTypes = [
                                    1 => 'Existing Carriage Way',
                                    2 => 'Proposed Carriage Way'
                                ];
                                echo isset($selected->carriage_type) && isset($carriageTypes[$selected->carriage_type])
                                    ? $carriageTypes[$selected->carriage_type]
                                    : '-';
                                ?>
                            </div>

                            <div class="col-md-3"><strong>Width (m):</strong> <?= $selected->existing_carriage_value ?? '-' ?></div>
                            <div class="col-md-3">
                                <strong>Road Width Type:</strong>
                                <?php
                                $roadwayTypes = [
                                    1 => 'Existing Road Way Width',
                                    2 => 'Proposed Road Way Width'
                                ];
                                echo isset($selected->roadway_type) && isset($roadwayTypes[$selected->roadway_type])
                                    ? $roadwayTypes[$selected->roadway_type]
                                    : '-';
                                ?>
                            </div>

                            <div class="col-md-3"><strong>Width (m):</strong> <?= $selected->existing_width ?? '-' ?></div>
                        </div>

                        <!-- Existing Road Length -->
                        <h6 class="text-secondary mb-3">Existing Road Length (km)</h6>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>BT Length:</strong> <?= $selected->existing_bt_length ?? '-' ?></div>
                            <div class="col-md-3"><strong>CC Length:</strong> <?= $selected->existing_cc_length ?? '-' ?></div>
                            <div class="col-md-3"><strong>Total Existing Length:</strong> <?= $selected->existing_total_length ?? '-' ?></div>
                        </div>

                        <!-- Proposed Maintenance -->
                        <h6 class="text-secondary mb-3">Proposed Maintenance Length (km)</h6>
                        <div class="row mb-3">
                            <div class="col-md-3"><strong>BT Length:</strong> <?= $selected->prop_bt_length ?? '-' ?></div>
                            <div class="col-md-3"><strong>CC Length:</strong> <?= $selected->prop_cc_length ?? '-' ?></div>
                            <div class="col-md-3"><strong>Total Proposed Length:</strong> <?= $selected->prop_total_length ?? '-' ?></div>
                        </div>

                        <!-- Traffic Category -->
                        <h6 class="text-secondary mb-3">Traffic Category</h6>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Existing:</strong> <?= $selected->existing_traffic ?? '-' ?></div>
                            <div class="col-md-4"><strong>Proposed:</strong> <?= $selected->prop_traffic ?? '-' ?></div>
                        </div>

                        <!-- Cost Details -->
                        <h6 class="text-secondary mb-3">Project Cost</h6>
                        <div class="row mb-3">
                            <div class="col-md-4"><strong>Initial Rehabilitation:</strong> ₹ <?= $selected->initial_rehabilitation_cost ?></div>

                            <div class="col-md-4"><strong>Renewal Cost:</strong> ₹ <?= $selected->renewal_cost  ?></div>
                        </div>

                        <!-- Routine Maintenance -->
                        <h6 class="text-secondary mb-3">Routine Maintenance Cost</h6>
                        <div class="row mb-3">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <div class="col-md-2 mb-2">
                                    <strong><?= $i ?><?= ($i == 1) ? 'st' : (($i == 2) ? 'nd' : (($i == 3) ? 'rd' : 'th')) ?> Year:</strong><br>
                                    ₹ <?= number_format($selected->{'routine_year' . $i} ?? 0, 2) ?>
                                </div>
                            <?php endfor; ?>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4"><strong>Total Routine Cost:</strong> ₹ <?= number_format($selected->routine_total ?? 0, 2) ?></div>
                            <div class="col-md-4"><strong>Total Project Cost:</strong> ₹ <?= number_format($selected->total_project_cost ?? 0, 2) ?></div>
                        </div>

                        <hr class="my-4">


                        <div class="text-center">
                            <button type="button" class="btn btn-success mr-2" data-toggle="modal" data-target="#approveModal">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>

                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal">
                                <i class="fas fa-times-circle"></i> Reject
                            </button>
                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ################################### Approve Modal ############################ -->
<div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="approveModalLabel">Approval Details</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center align-items-center g-3">
                        <!-- IR Cost Input Field -->
                        <div class="col-md-10"><strong>Approvel Initial Rehabilitation Cost </strong></div>
                        <div class="form-group col-md-5">
                            <input type="text" class="form-control rounded-pill px-4 py-2 shadow-sm"
                                id="ir_cost" name="ir_cost" placeholder="Enter IR Cost"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateIRCost()">
                        </div>

                        <!-- Existing IR Cost Display -->
                        <div class="form-group col-md-5">
                            <div class="bg-white border-start border-4 border-primary rounded-pill px-4 py-2 shadow-sm text-dark d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Existing</span>
                                <strong>₹ <?= number_format($selected->initial_rehabilitation_cost ?? 0, 2) ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center align-items-center g-3 mb-3">
                        <div class="col-md-10">
                            <strong>Approval Renewal Cost</strong>
                        </div>
                        <div class="form-group col-md-5">
                            <input type="text"
                                class="form-control rounded-pill px-4 py-2 shadow-sm"
                                id="renewal_cost" name="renewal_cost" placeholder="Enter Renewal Cost"
                                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); validateRenewalCost()">
                        </div>
                        <div class="form-group col-md-5">
                            <div class="bg-white border-start border-4 border-primary rounded-pill px-4 py-2 shadow-sm text-dark d-flex justify-content-between align-items-center">
                                <span class="text-muted small">Existing</span>
                                <strong>₹ <?= number_format($selected->renewal_cost ?? 0, 2) ?></strong>
                            </div>
                        </div>
                    </div>


                    <?php
                    $years = [
                        1 => $selected->routine_year1 ?? 0,
                        2 => $selected->routine_year2 ?? 0,
                        3 => $selected->routine_year3 ?? 0,
                        4 => $selected->routine_year4 ?? 0,
                        5 => $selected->routine_year5 ?? 0,
                    ];
                    ?>
                    <div class=" row justify-content-center align-items-center g-3 mb-2">
                        <div class="col-md-10">
                            <strong>Approval Routine maintenance Cost</strong>
                        </div>
                    </div>

                    <?php foreach ($years as $year => $value): ?>
                        <div class="row justify-content-center align-items-center g-3 mb-2">

                            <div class="form-group col-md-5">
                                <input type="text" class="form-control rounded-pill px-4 py-2 shadow-sm"
                                    id="ry_cost<?= $year ?>" name="ry_cost<?= $year ?>" placeholder="<?= $year ?><?= ($year == 1) ? 'st' : (($year == 2) ? 'nd' : (($year == 3) ? 'rd' : 'th')) ?> Year"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '')">
                            </div>

                            <div class="form-group col-md-5">
                                <div class="bg-white border-start border-4 border-primary rounded-pill px-4 py-2 shadow-sm text-dark d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Existing</span>
                                    <strong>₹ <?= number_format($value, 2) ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="row justify-content-center align-items-center g-3 mb-2">
                        <!-- Total Maintenance Cost -->
                        <div class="form-group col-md-5">
                            <div class="bg-white border-start border-4 border-primary rounded-pill px-4 py-2 shadow-sm text-dark d-flex justify-content-between align-items-center">
                                <strong><span>Total Cost</span></strong>
                                <strong>₹ <span id="total_maintenance_cost">0.00</span></strong>
                            </div>
                        </div>

                        <div class="form-group col-md-5">
                            <div class="bg-white border-start border-4 border-primary rounded-pill px-4 py-2 shadow-sm text-dark d-flex justify-content-between align-items-center">
                                <strong><span>Total Project Cost</span></strong>
                                <strong>₹ <span id="total_project_cost">0.00</span></strong>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Submit Approval</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>

            <input type="hidden" id="existing_ir_cost" value="<?= $selected->initial_rehabilitation_cost ?? 0 ?>">
            <input type="hidden" id="existing_renewal_cost" value="<?= $selected->renewal_cost ?? 0 ?>">


        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form>
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Rejection Comments</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Comments</label>
                        <textarea class="form-control" rows="5" placeholder="Enter rejection comments"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Submit Rejection</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    function _document(url) {
        window.open(url, '_blank');
    }

    function parseFloatOrZero(val) {
        return parseFloat(val) || 0;
    }

    function calculateTotals() {
        let totalMaintenance = 0;
        for (let i = 1; i <= 5; i++) {
            let value = parseFloatOrZero(document.getElementById(`ry_cost${i}`)?.value);
            totalMaintenance += parseFloatOrZero(value);
        }
        document.getElementById('total_maintenance_cost').textContent = totalMaintenance.toFixed(2);
        let ir = parseFloatOrZero(document.getElementById('ir_cost')?.value);
        let renewal = parseFloatOrZero(document.getElementById('renewal_cost')?.value);

        let totalProject = ir + renewal + totalMaintenance;
        document.getElementById('total_project_cost').textContent = totalProject.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['ir_cost', 'renewal_cost', 'ry_cost1', 'ry_cost2', 'ry_cost3', 'ry_cost4', 'ry_cost5'];
        inputs.forEach(id => {
            let el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', calculateTotals);
            }
        });
    });

    function validateIRCost() {
        const input = document.getElementById('ir_cost');
        const existing = parseFloatOrZero(document.getElementById('existing_ir_cost').value);
        const current = parseFloatOrZero(input.value);

        if (current > existing) {
            input.classList.add('text-danger');
            input.style.border = '2px solid red';
        } else {
            input.classList.remove('text-danger');
            input.style.border = '';
        }
    }

    function validateRenewalCost() {
        const input = document.getElementById('renewal_cost');
        const existingValue = parseFloat(document.getElementById('existing_renewal_cost').value) || 0;
        const currentValue = parseFloat(input.value) || 0;

        if (currentValue > existingValue) {
            input.classList.add('text-danger');
            input.style.border = '2px solid red';
        } else {
            input.classList.remove('text-danger');
            input.style.border = '';
        }
    }


    document.addEventListener('DOMContentLoaded', function() {
        <?php foreach ($years as $year => $value): ?>
            const input<?= $year ?> = document.getElementById('ry_cost<?= $year ?>');
            const existing<?= $year ?> = <?= floatval($value) ?>;

            input<?= $year ?>.addEventListener('input', function() {
                const enteredValue = parseFloat(this.value) || 0;
                if (enteredValue > existing<?= $year ?>) {
                    input<?= $year ?>.classList.add('text-danger');
                    input<?= $year ?>.style.border = '2px solid red';
                } else {
                    input<?= $year ?>.classList.remove('text-danger');
                    input<?= $year ?>.style.border = '';
                }
            });
        <?php endforeach; ?>
    });



    $(document).ready(function() {
        $('#rejectModal').on('hidden.bs.modal', function() {
            $(this).find('textarea').val('');
            $(this).find('input').val('');
        });
    });

    $(document).ready(function() {
        $('#approveModal').on('hidden.bs.modal', function() {
            $(this).find('input[type="text"]').val('');
            $(this).find('textarea').val('');

            $('#total_maintenance_cost').text('0.00');
            $('#total_project_cost').text('0.00');
        });
    });
</script>