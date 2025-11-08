<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$caption = $caption == '' ? '' : json_decode($caption);
// print_r($oqrc); exit;
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
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Road Obstruction</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= $caption[0]->road_obstruction != '' ? strtoupper($caption[0]->road_obstruction) : 'Final submit not complete' ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Inspectipn Date</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= $caption[0]->inspection_date == '' ? 'Final submit not complete' : date('d/m/Y', strtotime($caption[0]->inspection_date)) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Remarks</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= $caption[0]->remarks != '' ? strtoupper($caption[0]->remarks) : 'Final submit not complete' ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Document</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <?php echo $document = strlen($caption[0]->document) ? '<button class="btn btn-icon btn-sm btn-success" onclick="_document(\'' . base_url($caption[0]->document) . '\')"  title="Document"><i class="fas fa-file-pdf"></i></button>' : '<i class="fa fa-times"></i>'; ?>
                                        &nbsp;&nbsp;<span style="color: #191970; font-size: 19px;">click here to view document</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Length Of Bridge(in meter)</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->length) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Width of Bridge(in meter)</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->width) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">HFL</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->hfl) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">OFL</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->ofl) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Type of Obstruction</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->obstruction) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Traffic Category</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->traffic_category) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">LBL</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->lbl) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Type of Foundation</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->fundation) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Type of Proposed Bridge</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->proposed_bridge) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Type of Super Structure</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->super_structure) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Linear Waterway Disabled</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->linear_waterway) ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>
                                        <h4><span style="color: #387338; font-size:22px;">Linear Waterway Provided</span></h4>
                                    </label>
                                    <div class="form-group" style="background-color: #F5FFFA;">
                                        <span style="color: #191970; font-size:18px;"><?= strtoupper($caption[0]->linear_water_provided) ?></span>
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
<style>
    .group {
        margin-bottom: 20px;
    }

    .group label {
        font-size: 10px;
    }

    .group2 label {
        font-size: 8px;
    }
</style>
<script>
    function _document(url) {
        window.open(url, '_blank');
    }

    function back() {
        window.location.href = baseURL + '/capex/bridge_inspection';
    }
</script>