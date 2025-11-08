<?php
defined('BASEPATH') or exit('No direct script access allowed');
$role = json_decode($role);
$district = json_decode($district);
$block = json_decode($block);
$selected = json_decode($selected);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Manage User</h2>
                    <h5 class="text-white op-7 mb-2">Add/edit user</h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <?php
                    if ($role_id < 4) {
                        echo '<a href="' . base_url('um/role') . '" class="btn btn-white btn-border btn-round mr-2">Role</a>';
                    }
                    ?>
                    <a href="<?= base_url('um') ?>" class="btn btn-secondary btn-round">User</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open_multipart('um/save_user'); ?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Name</label>
                                            <input id="name" type="text" class="form-control" name="name" value="<?= $selected->name ?>" tabindex="1" required autofocus>
                                            <div class="invalid-feedback">Please provide the credential name</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="column">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label>Role</label>
                                                        <select id="role_id" name="role_id" class="form-control dropdown">
                                                            <?php
                                                            echo '<option value="">Select role</option>';
                                                            foreach ($role as $row) {
                                                                $_selected = ($selected->role_id > 0 && $selected->role_id == $row->id) ? 'selected' : '';
                                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label>District</label>
                                                        <select id="district_id" name="district_id[]" multiple="multiple" class="form-control dropdown">
                                                            <?php
                                                            $district_id = explode(',', $selected->district_id);
                                                            echo '<option value="0">--All District--</option>';
                                                            foreach ($district as $row) {
                                                                $_selected = in_array($row->id, $district_id) ? 'selected' : '';
                                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label>Block</label>
                                                        <select id="block_id" name="block_id[]" multiple="multiple" class="form-control dropdown">
                                                            <?php
                                                            $block_id = explode(',', $selected->block_id);
                                                            echo '<option value="0">--All Block--</option>';
                                                            foreach ($block as $row) {
                                                                $_selected = in_array($row->id, $block_id) ? 'selected' : '';
                                                                echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="form-group">
                                                        <label for="mobile">Mobile</label>
                                                        <div class="input-icon">
                                                            <span class="input-icon-addon">
                                                                <i class="fa fa-mobile-alt"></i>
                                                            </span>
                                                            <input id="mobile" type="text" class="form-control" name="mobile" value="<?= $selected->mobile ?>" tabindex="2">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <label for="email">Email</label>
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text">@</span>
                                                            </div>
                                                            <input id="email" type="text" class="form-control" name="email" value="<?= $selected->email ?>" tabindex="3">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Upload Image<br>
                                                    <span style="font-size: 10px; color: red;">( Maximum 200kb, Uploaded Format - jpeg/jpg/png ) </span></label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="multiselect_div">
                                                    <input type="file" name="userfile" data-default-file="../../<?php echo $selected ? $selected->photo : ''; ?>" class="dropify" data-max-file-size="200K" accept="image/*">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <div class="input-icon">
                                                <span class="input-icon-addon">
                                                    <i class="fa fa-user"></i>
                                                </span>
                                                <input id="username" type="text" class="form-control" name="username" value="<?= $selected->username ?>" tabindex="5" autocomplete="nope" required autofocus>
                                                <!--<label id="chk"></label>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mt-5">
                                            <label id="chk"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg btn-block pull-right">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user_id" value="<?= $selected->user_id ?>">
                        <?php form_close(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url('templates/assets/dropify/css/dropify.min.css'); ?>">
<script src="<?php echo base_url('templates/assets/dropify/js/dropify.js'); ?>"></script>
<script src="<?= base_url('templates/js/dropify.js') ?>"></script>
<script src="<?= base_url('templates/js/um.js') ?>"></script>