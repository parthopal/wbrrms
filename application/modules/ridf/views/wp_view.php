<?php
defined('BASEPATH') or exit('No direct script access allowed');
$wp = json_decode($wp);
// $wp_document = json_decode($wp_document);
?>
<style>
    div.dataTables_filter {
        margin-bottom: 5px;
    }

    div.dataTables_filter input {
        margin-right: 5px;
    }
</style>

<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5>
                </div>
                <div class="ml-md-auto py-2 py-md-0"></div>
            </div>
        </div>
    </div>
    <div class="page-inner mt--5">
        <div class="row mt--2">
            <div class="col-md-12">
                <div class="card full-height">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="tbl" class="display table table-bordered table-striped table-hover ">
                                <thead>
                                    <tr class="thead-light">
                                        <th>#</th>
                                        <?php if ($role_id < 3): ?>
                                            <th>District</th>
                                        <?php endif; ?>
                                        <th>Block</th>
                                        <th>Scheme No</th>
                                        <th>Name</th>
                                        <th>Start Date</th>
                                        <th>Progress</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($wp != null) {
                                        $i = 1;
                                        foreach ($wp as $row) {
                                            $disabled = $row->pp_status == 5 ? 'disabled' : '';
                                            echo '<tr>';
                                            echo '<td>' . $i . '</td>';

                                            if ($role_id < 3) {
                                                echo '<td><small>' . (isset($row->district) ? $row->district : 'N/A') . '</small></td>';
                                            }

                                            // Block, Scheme No, and other fields
                                            echo '<td><small>' . $row->block . '</small></td>';
                                            echo '<td>' . $row->ref_no . '</td>';
                                            echo '<td><p class="truncate_text" data-toggle="tooltip" data-placement="bottom" title="' . $row->name . '">' . $row->name . '</p></td>';
                                            echo '<td>' . ($row->wo_start_date != null ? date('d/m/Y', strtotime($row->wo_start_date)) : '') . '</td>';
                                            echo '<td>' . $row->physical_progress . '</td>';
                                            echo '<td>' . $row->progress_remarks . '</td>';
                                            echo '<td class="text-center">
                                                <div style="display: flex; align-items: center; gap: 6px;">
                                                    
                                                    <button class="btn btn-sm btn-icon btn-round btn-primary" 
                                                        onclick="entry(' . $row->id . ',' . $status . ')" 
                                                        title="Progress Entry" ' . $disabled . '>
                                                        <i class="fas fa-plus pointer"></i>
                                                    </button>
                                                </div>
                                            </td>';


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
<script>
    function entry(id, status) {
        window.open(baseURL + '/ridf/wp_entry/' + id + '/' + status, '_self');
    }


    $(document).ready(function() {
        $('#tbl').DataTable({
            "paging": false,
            "searching": true,
            "ordering": false,
            "info": false,
            "autoWidth": false,
        });
    });
</script>

