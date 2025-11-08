<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$block = json_decode($block);
$approved = json_decode($approved);
$selected = json_decode($selected);
// print_r($approved);exit;
$style = ($role_id > 2 && $role_id != 8 && $role_id != 15) ? 'style="display: none;"' : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">
                        <?= $heading; ?>
                    </h2>
                </div>

                <div class="ml-md-auto py-2 py-md-0">
                    <button type="button" class="btn btn-success btn-round" onclick="add(0)" <?= $style ?>>
                        <i class="fa fa-plus"></i> &nbsp; Add New
                    </button>
                </div>

            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="0">--All District--</option>';
                                        foreach ($district as $row) {
                                            $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="button" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <?= $subheading ?>
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Work Name</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>GP</th>
                                        <th>Ref No</th>
                                        <th>Implementing Agency</th>
                                        <th>Road Length</th>
                                        <th>Type of Work</th>
                                        <th>Type of Road</th>
                                        <th>Vetted Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var role_id = <?php echo $role_id; ?>;

    function add(id) {
        window.location.href = baseURL + '/sfurgent/entry/' + id;
    }
</script>
<script src="<?= base_url('templates/js/sfurgent.js') ?>"></script>