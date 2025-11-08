<?php
defined('BASEPATH') or exit('No direct script access allowed');

$district = json_decode($district);
$category = json_decode($category);
$block = json_decode($block);
$selected = json_decode($selected);
$list = json_decode($list);
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-10">
                                <!-- <h2><?= $title ?></h2> -->
                            </div>
                            <div class="col-md-2 text-right">
                                <a href="<?= base_url('ridf/requisition_entry') ?>" class="btn btn-success btn-round">Add</a>
                            </div>
                        </div>
                        <hr>
                        <?php echo form_open('ridf/requisition'); ?>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>District</label>
                                    <select id="district_id" name="district_id" class="form-control dropdown">
                                        <option value="0" selected>--Select District--</option>
                                        <?php
                                        foreach ($district as $row) {
                                            $_selected = ($selected->district_id > 0 && $selected->district_id == $row->id) ? 'selected' : '';
                                            echo "<option value='{$row->id}' {$_selected}>{$row->name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Block </label>
                                    <select id="block_id" name="block_id" class="form-control dropdown">
                                        <option value="0">--All Block--</option>
                                        <?php
                                        foreach ($block as $row) {
                                            $_selected = ($selected->block_id > 0 && $selected->block_id == $row->id) ? 'selected' : '';
                                            echo "<option value='{$row->id}' {$_selected}>{$row->name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Funding By</label>
                                    <select id="fund_id" name="fund_id" class="form-control dropdown">
                                        <option value="0">--Select Fund--</option>
                                        <?php
                                        foreach ($category as $row) {
                                            $_selected = ($selected->category_id > 0 && $selected->category_id == $row->id) ? 'selected' : '';
                                            echo "<option value='{$row->id}' {$_selected}>{$row->name}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4 text-right">
                                <div class="form-group">
                                    <button type="submit" id="search_requisition" name="search_requisition" class="btn btn-primary">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="table-responsive" style="overflow: auto;">
                            <table id="tbl_requisition" class="display nowrap table table-bordered table-striped table-hover" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th>SL. No</th>
                                        <th>memo_no</th>
                                        <th>Name of the project</th>
                                        <th>Funding by</th>
                                        <th>District</th>
                                        <th>Block</th>
                                        <th>Implementing Agency</th>
                                        <th>Sanctioned Cost</th>
                                        <th>RIDF Loan</th>
                                        <th>Physical Progress in %</th>
                                        <th>expenditure Incurrend upto Previous claim Rs.(in lakh)</th>
                                        <th>expenditure Incurrend during the Present claim Rs.(in lakh)</th>
                                        <th>Total value of work done Rs.(in lakh)</th>
                                        <th>Amount already clamied Rs.(in lakh)</th>
                                        <th>Amount present claim Rs.(in lakh)</th>
                                        <th>Likely during ensuing quarter Rs.(in lakh)</th>
                                        <th>Document</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($list as $row) {
                                        if ($row->isactive == 1) {
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';
                                            echo '<td>' . $row->memo_no . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . $row->funding . '</td>';
                                            echo '<td>' . $row->district . '</td>';
                                            echo '<td>' . $row->block . '</td>';
                                            echo '<td>' . $row->agency . '</td>';
                                            echo '<td>' . $row->sanctioned_cost . '</td>';
                                            echo '<td>' . $row->ridf_loan . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->previous_claim_expenditure . '</td>';
                                            echo '<td>' . $row->present_claim_expenditure . '</td>';
                                            echo '<td>' . $row->total_claim_expenditure . '</td>';
                                            echo '<td>' . $row->amounts_already_claimed . '</td>';
                                            echo '<td>' . $row->present_claim_amount . '</td>';
                                            echo '<td>' . $row->ensuing_quarter_drawal . '</td>';

                                            echo '<td>' . '</td>';
                                            echo '<td>' . '</td>';
                                            echo '</tr>';
                                            $i++;
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
<script src="<?= base_url('templates/js/ridf.js') ?>"></script>