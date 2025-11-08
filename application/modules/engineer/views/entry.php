<?php
defined('BASEPATH') or exit('No direct script access allowed');

$engineer = json_decode($info);
$district = json_decode($district);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Manage Engineer</h2>
                    <h5 class="text-white op-7 mb-2">Add/edit engineer (<i>Last updated on <?= $engineer->updated_on ?></i>)</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-header">
                        <div class="row ml-1">
                            <button type="button" class="btn btn btn-icon btn-round btn-primary" data-toggle="tooltip" data-placement="top" title="Back" onclick="_back()">
                                <i class="fas fa-arrow-left"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open('engineer/save'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="reqd">Name</label>
                                            <input id="name" type="text" class="form-control" name="name" value="<?= $engineer->name ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="reqd">Designation</label>
                                            <select id="designation" name="designation" class="form-control dropdown" required>
                                                <option value="">--Select Designation--</option>
                                                <option value="AE" <?= $engineer->designation == 'AE' ? 'selected' : '' ?>>Assistant Engineer</option>
                                                <option value="EE" <?= $engineer->designation == 'EE' ? 'selected' : '' ?>>Executive Engineer</option>
                                                <option value="SE" <?= $engineer->designation == 'SE' ? 'selected' : '' ?>>Superintendent Engineer</option>
                                                <option value="CE" <?= $engineer->designation == 'CE' ? 'selected' : '' ?>>Chief Engineer</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="reqd">Caste</label>
                                            <select id="caste" name="caste" class="form-control dropdown" required>
                                                <option value="">--Select Caste--</option>
                                                <option value="GEN" <?= $engineer->caste == 'GEN' ? 'selected' : '' ?>>General</option>
                                                <option value="OBC-A" <?= $engineer->caste == 'OBC-A' ? 'selected' : '' ?>>OBC-A</option>
                                                <option value="OBC-B" <?= $engineer->caste == 'OBC-B' ? 'selected' : '' ?>>OBC-B</option>
                                                <option value="SC" <?= $engineer->caste == 'SC' ? 'selected' : '' ?>>SC</option>
                                                <option value="ST" <?= $engineer->caste == 'ST' ? 'selected' : '' ?>>ST</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Home District</label>
                                            <select id="district_id" name="district_id" class="form-control dropdown">
                                                <?php
                                                $_selected = $engineer->district_id == '' ? 'selected' : '';
                                                echo '<option value="0" ' . $_selected . '>--Select Home District--</option>';
                                                foreach ($district as $row) {
                                                    $_selected = $engineer->district_id == $row->id ? 'selected' : '';
                                                    echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="reqd">DoB</label>
                                            <div class="input-group">
                                                <input type="text" id="dob" name="dob" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= date('d/m/Y', strtotime($engineer->dob)) ?>" required>
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label>Posting</label>
                                            <input type="text" id="posting" name="posting" class="form-control" value="<?= $engineer->posting ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>DoR</label>
                                            <div class="input-group">
                                                <input type="text" id="dor" name="dor" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->dor != null ? date('d/m/Y', strtotime($engineer->dor)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>SAE DoJ</label>
                                            <div class="input-group">
                                                <input type="text" id="doj_sae" name="doj_sae" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doj_sae != null ? date('d/m/Y', strtotime($engineer->doj_sae)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>SAE DoC</label>
                                            <div class="input-group">
                                                <input type="text" id="doc_sae" name="doc_sae" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doc_sae != null ? date('d/m/Y', strtotime($engineer->doc_sae)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>AE DoJ</label>
                                            <div class="input-group">
                                                <input type="text" id="doj_ae" name="doj_ae" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doj_ae != null ? date('d/m/Y', strtotime($engineer->doj_ae)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>AE DoC</label>
                                            <div class="input-group">
                                                <input type="text" id="doc_ae" name="doc_ae" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doc_ae != null ? date('d/m/Y', strtotime($engineer->doc_ae)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="col-md-2 ee">
                                        <div class="form-group">
                                            <label>EE DoJ</label>
                                            <div class="input-group">
                                                <input type="text" id="doj_ee" name="doj_ee" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doj_ee != null ? date('d/m/Y', strtotime($engineer->doj_ee)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 se">
                                        <div class="form-group">
                                            <label>SE DoJ</label>
                                            <div class="input-group">
                                                <input type="text" id="doj_se" name="doj_se" class="form-control datepicker" placeholder="DD/MM/YYYY" autocomplete="off" value="<?= ($engineer->doj_se != null ? date('d/m/Y', strtotime($engineer->doj_se)) : '') ?>">
                                                <div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 se">
                                        <div class="form-group">
                                            <label>Tag</label>
                                            <select id="doj_se_tag" name="doj_se_tag" class="form-control dropdown">
                                                <option value="">--Select Tag--</option>
                                                <option value="A/N" <?= $engineer->doj_se_tag == 'A/N' ? 'selected' : '' ?>>A/N</option>
                                                <option value="F/N" <?= $engineer->doj_se_tag == 'F/N' ? 'selected' : '' ?>>F/N</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 se">
                                        <div class="form-group">
                                            <label>SE Promotional Order</label>
                                            <input type="text" id="se_promotional_order" name="se_promotional_order" class="form-control" value="<?= $engineer->se_promotional_order ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Remarks</label>
                                            <textarea id="remarks" name="remarks" class="form-control" placeholder="Remarks" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-action">
                                    <div class="col-md-12 text-right">
                                        <button type="submit" name="submit" value="Submit" class="btn btn-success ml-2">
                                            <i class="fas fa-save"></i> &nbsp; <span>SAVE</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="<?= $engineer->id ?>">
                    <?php form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    if ($('#designation').val() === 'EE') {
        $('.ee').show();
        $('.se').hide();
    } else if ($('#designation').val() === 'SE') {
        $('.ee').show();
        $('.se').show();
    } else {
        $('.ee').hide();
        $('.se').hide();
    }
    $(document).ready(function () {
        $("#designation").on("change", function (e) {
            e.preventDefault();
            if ($('#designation').val() === 'AE') {
                $('.ee').hide();
                $('.se').hide();
            } else if ($('#designation').val() === 'EE') {
                $('.ee').show();
                $('.se').hide();
            } else if ($('#designation').val() === 'SE') {
                $('.ee').show();
                $('.se').show();
            }
        });
    });
</script>