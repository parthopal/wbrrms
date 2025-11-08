<?php
defined('BASEPATH') or exit('No direct script access allowed');

$selected = json_decode($selected);
$oqrc = json_decode($oqrc);
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
                             <!-- <div class="col-md-1">
                                <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="back_qm()">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>  -->
                            <div class="col-md-11">
                                <h4><?= $title ?></h4>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive mt-3">
                            <table id="tbl" class="display table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th style="text-align: center">Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 1;
                                    foreach ($oqrc as $row) {
                                        echo '<tr>';
                                        echo '<td>' . $i . '</td>';
                                        echo '<td>' . $row->name . '</td>';
                                        echo '<td style="text-align: center">' . $row->value . '</td>';
                                        echo '</tr>';
                                        $i++;
                                    }
                                    echo '<tr style="background: yellow">';
                                    echo '<td></td>';
                                    echo '<td><h3>Overall Grade</h3></td>';
                                    echo '<td colspan="3" style="text-align: center"><h3><span id="span_grade">' . $grade . '</span></h3></td>';
                                    echo '</tr>';
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <hr>
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

