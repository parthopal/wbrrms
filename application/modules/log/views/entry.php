<?php
defined('BASEPATH') or exit('No direct script access allowed');

$type = json_decode($type);
$selected = json_decode($selected);
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
                <div class="card full-height">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" onclick="_back()" class="btn btn-icon btn-round btn-primary mb-4 float-left mr-3">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <span><h1>Support Call</h1></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php echo form_open_multipart('log/save'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label>Logs Type</label>
                                                    <select id="type_id" name="type_id" class="form-control dropdown" required>
                                                        <?php
                                                        echo '<option value="">--Select Logs Type--</option>';
                                                        foreach ($type as $row) {
                                                            $_selected = ($selected->type_id > 0 && $selected->type_id == $row->id) ? 'selected' : '';
                                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label>Scheme Ref No *</label>
                                                    <input type="text" id="scheme_ref_no" name="scheme_ref_no" class="form-control" value="<?= $selected->scheme_ref_no ?> " required>
                                                    <div class="invalid-feedback">Please provide scheme reference number</div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="contact_person">Contact Person *</label>
                                                    <input id="contact_person" type="text" class="form-control" name="contact_person" value="<?= $selected->contact_person ?>" required>
                                                    <div class="invalid-feedback">Please provide the contact person</div>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label for="contact_no">Contact No *</label>
                                                    <input id="contact_no" type="text" class="form-control" name="contact_no" value="<?= $selected->contact_no ?>" required>
                                                    <div class="invalid-feedback">Please provide the contact number</div>
                                                </div>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="form-group">
                                                    <label for="contact_email">Contact Email</label>
                                                    <input id="contact_email" type="text" class="form-control" name="contact_email" value="<?= $selected->contact_email ?>" tabindex="4">
                                                    <div class="invalid-feedback">Please provide the name</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Upload Image<br>
                                                        <span style="font-size: 10px; color: red;">( Maximum 200kb, Uploaded Format - jpeg/jpg/png ) </span></label>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="multiselect_div">
                                                        <input type="file" name="userfile" data-default-file="../../<?php echo $selected ? $selected->document : ''; ?>" class="dropify" data-max-file-size="200K" accept="image/*">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-group">
                                                <label for="remarks">Note *</label>
                                                <textarea rows="5" type="text" class="form-control" id="remarks" name="remarks"><?= isset($selected->remarks) ? $selected->remarks : '' ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-10"></div>
                                    <div class="col-md-2 col-sm-12">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block pull-right">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" value="<?= $selected->id ?>">
                        <?php form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('templates/js/logs.js') ?>"></script>