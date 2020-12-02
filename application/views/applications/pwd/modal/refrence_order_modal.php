<div class="alert_message"></div>
<div class="form-group">
    <label>Refrence number:</label>
    <input type="number" name="refrence_number" readonly value="<?= $application->reference_no ?>" class="form-control">
</div>
<div class="form-group">
    <label for="exampleFormControlTextarea1">Remark:</label>
    <textarea class="form-control" name="joint_visit_remark" placeholder="" rows="5" style="height: auto !important;"></textarea>
</div>
<div>
    <input type="hidden" value="<?= $application->app_id ?>" name="app_id">
    <input type="hidden" id="is_close" name="is_close">
    <button type="submit" data-dismiss="modal" class="btn btn-danger">Close</button>
    <button type="submit" is_close="0" class="btn btn-success float-right submit_btn_joint_ref">Send refrence number</button>
</div>