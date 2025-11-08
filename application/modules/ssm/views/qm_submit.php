<?php
defined('BASEPATH') or exit('No direct script access allowed');

$oqrc = json_decode($oqrc);
//$selected = json_decode($selected);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11">
                                <h4><?= $title ?></h4>
                            </div>
                        </div>
                        <?php echo form_open_multipart('ssm/qm_save_submit'); ?>
                        <div class="table-responsive mt-3">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>S</th>
                                        <th>U</th>
                                        <th>NA</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($oqrc as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->name . '</td>';
                                        echo '<td><input type="radio" name="oqrc[' . $row->id . ']" onchange="calc_overall_grade()" value="' . $row->id . '_s' . '"> S</td>';
                                        echo '<td><input type="radio" name="oqrc[' . $row->id . ']" onchange="calc_overall_grade()" value="' . $row->id . '_u' . '"> U</td>';
                                        echo '<td><input type="radio" name="oqrc[' . $row->id . ']" onchange="calc_overall_grade()" value="' . $row->id . '_na' . '"> NA</td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                    echo '<tr style="background: yellow">';
                                    echo '<td></td>';
                                    echo '<td><h3>Overall Grade</h3></td>';
                                    echo '<td colspan="3" style="text-align: center"><h3><span id="span_grade"></span></h3></td>';
                                    echo '</tr>';
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>Upload Final Report Copy *<br>
                                        <span style="font-size: 10px; color: red;">( Maximum 2mb, Uploaded Format - .pdf ) </span></label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Inspection Date *</label>
                                    <div class="input-group">
                                        <input type="text" id="inspection_date" name="inspection_date" class="form-control datepicker" placeholder="DD/MM/YYYY" value="" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="multiselect_div">
                                        <input type="file" name="userfile" data-default-file="" class="dropify" data-max-file-size="10000M" accept="application/pdf" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                            </button>
                        </div>
                        <input type="hidden" name="qm_id" value="<?= $qm_id ?>">
                        <input type="hidden" name="agency" value="<?= $agency ?>">
                        <input type="hidden" id="overall_grade" name="overall_grade" value="">
                        <?php echo form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="<?= base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
    <script src="<?= base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
    <script src="<?= base_url('templates/js/ssm_qm.js') ?>"></script>
    <script type="text/javascript">
    $(function () {
        Date.prototype.ddmmyyyy = function () {
            var dd = this.getDate().toString();
            var mm = (this.getMonth() + 1).toString();
            var yyyy = this.getFullYear().toString();
            return (dd[1] ? dd : "0" + dd[0]) + "-" + (mm[1] ? mm : "0" + mm[0]) + "-" + yyyy;
        };
        $("#inspection_date").datepicker({ dateFormat: "dd-mm-yy" });
        $("#inspection_date").on('change', function () {
            var selectedDate = $(this).val();
            var todaysDate = new Date().ddmmyyyy();
            if (selectedDate < todaysDate) {
                alert('Selected date must be greater than today date');
                $(this).val('');
            }
        });
    });            
</script>