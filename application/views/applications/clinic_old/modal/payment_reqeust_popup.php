<div id="alert_message"></div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label for="name_of_road">Payment amount:<span class="red">*</span></label>
            <input type="text" name="amount" value="<?= $payment->total_amount ?>" readonly class="form-control" autocomplete="off">
        </div>
    </div>
    <div class="col-12">
        <div class="form-group">
            <label>Payment remark:<span class="red">*</span></label>
            <textarea name="description" style="height: auto !important;" class="form-control" rows="5"></textarea>
        </div>
    </div>
</div>
<div class="d-block">
    <input type="hidden" value="<?= $application->app_id ?>" name="app_id">
    <button data-dismiss="modal" class="btn btn-primary">cancel</button>
    <button type="submit" id="submit_btn_text" class="btn btn-success" style="float: right;">Send Payment Reqeust</button>
</div>