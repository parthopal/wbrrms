<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <!-- <h1> hooo</h1> -->
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php echo form_open('log/reject'); ?>
        <div class="modal-content" style="width: 150%;height: 300px;">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Reject Log</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="heading" class="small"></p>
                <div class="row" id="data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required-label" for="reason">Reason</label>
                            <input type="text" id="reason" name="reason" class="form-control" placeholder="Reason..." value="" required>
                        </div>
                    </div>
                    <input id="id" type="hidden" class="form-control" name="id" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" >Submit</button>
            </div>
        </div>
    </div>
</div>