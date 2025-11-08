<?php
defined('BASEPATH') or exit('No direct script access allowed');

$role = json_decode($role);
$list = json_decode($list);
$selected = json_decode($selected);
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold">User Management</h2>
                    <h5 class="text-white op-7 mb-2">Manage role & user</h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0">
                    <?php
                    if ($role_id < 4) {
                        echo '<a href="' . base_url('um/role') . '" class="btn btn-white btn-border btn-round mr-2">Role</a>';
                    }
                    ?>
                    <a href="<?= base_url('um/entry/0') ?>" class="btn btn-secondary btn-round">Add User</a>
                </div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <?php echo form_open('um'); ?>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Role</label>
                                    <select id="role_id" name="role_id" class="form-control dropdown">
                                        <option value="">--Select Role--</option>
                                        <?php
                                        foreach ($role as $row) {
                                            $_selected = $selected->role_id == $row->id ? 'selected' : '';
                                            echo '<option value="' . $row->id . '" ' . $_selected . '>' . $row->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <button type="submit" id="search" name="search" class="btn btn-primary mt-4">
                                        <i class="fa fa-search"></i> &nbsp;
                                        <span>SEARCH</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <hr>
                        <div class="table-responsive">
                            <table id="tbl" class="stripe row-border order-column" style="width:100%">
                                <thead>
                                    <tr class="thead-light">
                                        <th>Role</th>
                                        <th>Name</th>
                                        <th>District</th>
                                        <th>Mobile/Email</th>
                                        <th>Username</th>
                                        <th class="not-export">Photo</th>
                                        <th class="not-export">Reset</th>
                                        <th class="not-export">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (sizeof($list) > 0) {
                                        foreach ($list as $row) {
                                            echo '<tr>';
                                            echo '<td>' . $row->role . '</td>';
                                            echo '<td>' . $row->name . '</td>';
                                            echo '<td>' . ($row->district != NULL ? $row->district : 'ALL') . '</td>';
                                            echo '<td>' . $row->mobile . '<br>' . $row->email . '</td>';
                                            echo '<td>' . $row->username . '</td>';
                                            $image = strlen($row->photo) > 0 ? '<div class="avatar"><img src="' . $row->photo . '" class="avatar-img rounded-circle"></div>' : '';
                                            echo '<td>' . $image . '</td>';
                                            echo '<td><button class="btn btn-sm btn-icon btn-round btn-warning" onclick="reset(' . $row->id . ')" title="Reset password"><i class="fas fa-user-cog pointer"></i></button></td>';
                                            echo '<td><button class="btn btn-sm btn-icon btn-round btn-secondary" onclick="edit(' . $row->id . ')" title="Edit"><i class="fas fa-pen pointer"></i></button>&nbsp;<button onclick="remove(' . $row->id . ')" title="Remove" class="btn btn-sm btn-icon btn-round btn-danger"><i class="fas fa-trash pointer"></i></button></td>';
                                            echo '</tr>';
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
<script src="<?= base_url('templates/js/um.js') ?>"></script>