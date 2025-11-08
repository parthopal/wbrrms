<?php
defined('BASEPATH') or exit('No direct script access allowed');
$role = json_decode($role);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">Role Management</h2>
                    <h5 class="text-white op-7 mb-2">Manage role and menu</h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <a href="<?= base_url('um') ?>" class="btn btn-white btn-border btn-round mr-2">User</a>
                    <a href="<?= base_url('um/entry/0') ?>" class="btn btn-secondary btn-round">Add User</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <?php echo form_open('um/save_role'); ?>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select id="role_id" name="role_id" class="form-control dropdown">
                                        <?php
                                        echo '<option value="">Select role</option>';
                                        foreach ($role as $row) {
                                            echo '<option value="' . $row->id . '">' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 mt-4">
                                <div class="form-group">
                                    <label></label>
                                    <button type="submit" class="btn btn-primary btn-lg btn-block pull-right">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Menu</label>
                                <div class="body"><div id="menu" name="menu"></div></div>
                                <input type="hidden" id="treeview" name="treeview" value=""/>
                            </div>
                        </div>
                    </div>
                    <?php form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?= base_url('templates/assets/js/jquery.treeview.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('templates/assets/css/jquery.treeview.css') ?>">
<script src="<?= base_url('templates/js/um.js') ?>"></script>