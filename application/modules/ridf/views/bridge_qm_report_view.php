<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$caption = $caption == '' ? '' : json_decode($caption);
// var_dump($caption);exit;
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
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-1">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-11">
                                <h3><?= strtoupper($title) ?></h3>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Overall Grade</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= $caption[0]->overall_grade != '' ? strtoupper($caption[0]->overall_grade) : 'Final submit not complete'?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Inspection Date</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= $caption[0]->inspection_date == '' ? 'Final submit not complete' : date('d/m/Y', strtotime($caption[0]->inspection_date)) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Physical Progress</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= strtoupper($caption[0]->physical_progress) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Financial Progress</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= strtoupper($caption[0]->financial_progress) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Work Stage</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= strtoupper($caption[0]->current_work_of_stage) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Remarks</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <span style="color: #00a399; font-size:18px;"><?= strtoupper($caption[0]->remarks) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <h4><span style="color: #387338; font-size:22px;">Document</span></h4>
                                    <div class="form-group" style="background-color: whitesmoke;">
                                        <?php echo $document = strlen($caption[0]->document) ? '<button class="btn btn-icon btn-sm btn-success" onclick="_document(\'' . base_url($caption[0]->document) . '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>' : '<i class="fa fa-times"></i>'; ?>
                                        &nbsp;&nbsp;<span style="color: #00a399; font-size:18px;">click here to view document</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <br><br>
                        <div id="div_image" class="row">
                            <?php
                            for ($i = 0; $i < sizeof($selected); $i++) {
                                echo '<div class="col-md-4"><div class="column">';
                                echo '<div class="form-group">';
                                echo '<div> ' . $selected[$i]->description . '</div>';
                                echo '<img src="' . base_url($selected[$i]->image) . '" width="150" height="100" name="img_' . $i . '" onmouseover="img_' . $i . '.width=300; img_' . $i . '.height=200;" onmouseout="img_' . $i . '.width=150;img_' . $i . '.height=100;"/>';
                                echo '</div>';
                                echo '</div></div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function _document(url) {
        window.open(url, '_blank');
    }

    function back() {
        window.location.href = baseURL + '/ridf/bridge_qm';
    }
</script>