<?php
defined('BASEPATH') or exit('No direct script access allowed');

$isadmin = json_decode($isadmin);
$selected = $selected != '' ? json_decode($selected) : '';
?>
<div class="container">
    <div class="panel-header bg-primary-gradient">
        <div class="page-inner py-5">
            <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                <div>
                    <h2 class="text-white pb-2 fw-bold"><?= $heading ?></h2>
                    <!-- <h5 class="text-white op-7 mb-2"><?= $subheading ?></h5> -->
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
                            <div class="col-md-10">
                                <button type="button" onclick="_back()" class="btn btn-icon btn-round btn-primary mb-4 float-left mr-3">
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                                <span>
                                    <h1><?= $subheading ?></h1>
                                </span>
                            </div>
                            <div class="col-md-2">
                                <span>
                                    <h1>
                                        <?php
                                        if ($selected->status == 0) {
                                            echo '<p class="badge btn-warning" style="margin:0px; width: 100px" >Pending</p>';
                                        }
                                        ?>
                                    </h1>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row justify-content-center text-center">
                            <div class="col-md-12">
                                <h1>
                                    <b>
                                        <?php
                                        echo $selected->type;
                                        ?>
                                    </b>
                                </h1>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-md-12 text-center">
                                <?php
                                $block = $selected->block != '' ? $selected->block . ",&nbsp;" : '';
                                echo ' <span class="sub-text">' . $block . '</span>';
                                echo '<span class="sub-text">' . $selected->district . '</span>';
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <?php
                                echo ' <span class="text">From : </span>' . '<span class="sub-text">' . $selected->name . '</span>';
                                ?>
                            </div>
                            <div class="col-md-12 ">
                                <?php
                                echo ' <span class="text">Ref No. : </span>' . '<span class="sub-text">' . $selected->scheme_ref_no . '</span>';
                                ?>
                            </div>
                            <div class="col-md-12">
                                <?php
                                echo ' <span class="text">Ticket Number : </span>' . '<span class="sub-text">' . $selected->ref_no . '</span>';
                                ?>
                            </div>
                        </div>
                        <h3 class="mt-3"><b><u>Contact Details</u>&nbsp;:</b></h3>
                        <div class="row mt-2">
                            <div class="col-md-4 mt-2">
                                <?php
                                echo ' <span class="text">Name : </span>' . '<span class="sub-text">' . $selected->contact_person . '</span>';
                                ?>
                            </div>
                            <div class="col-md-4 mt-2">
                                <?php
                                echo ' <span class="text">Mobile No : </span>' . '<span class="sub-text">' . $selected->contact_no . '</span>';
                                ?>
                            </div>
                            <div class="col-md-4 mt-2">
                                <?php
                                echo ' <span class="text">Email ID : </span>' . '<span class="sub-text">' . $selected->contact_email . '</span>';
                                ?>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-10">
                                <h3 class=""><b><u>Description</u>&nbsp;:</b></h3>
                            </div>
                            <div class="col-md-2 ">
                                <?php
                                echo (isset($selected->document) && strlen($selected->document) > 0 ? '<a class="fas fa-image fa-2x " target="_blank" href="' . base_url($selected->document) . '"></a>' : '<p class="badge btn-secondary" style="margin:0px; width: 100px" >No Image</p>');
                                ?>
                            </div>
                            <div class="col-md-12 mt-2">
                                <?php
                                echo '<span class="sub-text text-wrap">' . $selected->remarks . '</span>';
                                ?>
                            </div>
                        </div>
                        <h3 class="mt-3"><b><u>Remarks</u>&nbsp;:</b></h3>
                        <div class="col-md-12">
                            <div class="form-group">
                                <textarea name="remarks" id="remarks" rows="3" class="form-control" placeholder="Please Fill Your Remarks ........"></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <?php
                                    if ($isadmin && $selected->status == 0) {
                                        echo '<p style="margin:5px; width: 550px">
                                        <button class="btn btn-sm btn-success" onclick="_resolve(' . $selected->id . ')" title="Edit">Solve</button>&nbsp;
                                        <button title="Remove" id="log_rejected" class="btn btn-sm btn-danger" onclick="reject(' . $selected->id . ')">Reject</button>
                                    </p>';
                                    } else {
                                        echo '&nbsp;';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="id" id="logId" value="<?= $selected->id ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include_once 'modal.php'; ?>
</div>
<style>
    .text {
        color: solid black;
        font: 18px;
        font-weight: bold;
    }

    .sub-text {
        color: solid black;
        font: 15px;

    }
</style>
<script>
    function _resolve(id) {
        var remarks = $('#remarks').val();

        var r = confirm("Are you sure, this ticket marked as resolved?");
        if (r === true)
            window.location.href = "<?php echo site_url('log/resolve/'); ?>?id=" + id + '&remarks=' + remarks;
        else
            return false;
    }
    function reject(id) {
        var remarks = $('#remarks').val();
        if (remarks === '') {
            alert('Please enter remarks before rejecting.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: '<?= site_url("log/reject/") ?>',
            data: {
                id: id,
                remarks: remarks
            },
            dataType: 'json', // Expect JSON response
            success: function (response) {
                if (response.success) {
                    window.location.href = response.redirect_url;
                }
            }
        });
    }
</script>