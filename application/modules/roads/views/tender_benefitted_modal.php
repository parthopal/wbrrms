<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="modal fade" id="benefitted_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <?php echo form_open_multipart('roads/tender_benefitted_save'); ?>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"> Submit bellow details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="heading" class="small"></p>
                <div class="row" id="data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required-label" for="total_population">Total population benefitted</label>
                            <input type="text" id="total_population" name="total_population" class="form-control" placeholder="Total population benefitted..." value="" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required-label" for="total_households">Total Households benefitted</label>
                            <input type="text" id="total_households" name="total_households" class="form-control" placeholder="Total Households benefitted..." value="" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="required-label" for="no_of_village">No. of Village benefitted</label>
                            <input type="text" id="no_of_village" name="no_of_village" class="form-control" placeholder="No. of Village benefitted..." value="" required>
                        </div>
                    </div>
                    <input id="id" type="hidden" class="form-control" name="id" value="0">
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" id="submit_benefitted" class="btn btn-primary" onclick = "_benefittede_submit()">Submit</button> -->
                <button type="submit" class="btn btn-primary" >Submit</button>
            </div>
        </div>
        <?php form_close(); ?>

    </div>
</div>