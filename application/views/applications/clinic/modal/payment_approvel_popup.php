<div id="alert_message"></div>
<div class="row">
    <div class="col-12">
        <h5><b>App ID:</b> &nbsp MBMC-00000<?= $application->app_id ?></h5>
    </div>
    <div class="col-6">
        <h5><b>Username:</b> &nbsp <?= $application->applicant_name ?></h5>
    </div>
    <div class="col-6">
        <h5><b>Email:</b> &nbsp <?= $application->applicant_email_id ?></h5>
    </div>
</div>
<div class="row">
    <div class="col-6">
        <h5><b>Amount:</b> &nbsp <?= $payment->amount ?></h5>
    </div>
    <div class="col-6">
        <h5><b>Document:</b> &nbsp <a target="_blank" href="<?= base_url('uploads/clinic/payment_docs') ?>/<?= $payment->document_path ?>"><?= $payment->document_path ?></a></h5>
    </div>
</div>
<hr />
<div class="d-block">
    <input type="hidden" value="<?= $application->app_id ?>" name="app_id">
    <button data-dismiss="modal" class="btn btn-primary">Close</button>
    <button type="submit" id="submit_btn_text" class="btn btn-success" style="float: right;">Approved</button>
</div>