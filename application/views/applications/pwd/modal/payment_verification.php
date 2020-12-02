<div class="col-md-12">
    <div id="alert_message"></div>
    <h6 class="font-weight-bold">Applicant name:</h6>
    <p><?= $application->applicant_name ?></p>
    <h6 class="font-weight-bold">Applicant Email-ID:</h6>
    <p><?= $application->applicant_email_id ?></p>
    <h6 class="font-weight-bold">Amount:</h6>
    <p><?= $payment->amount ?></p>
    <h6 class="font-weight-bold">Payment documnet:</h6>
    <p><a target="_blank" href="<?= base_url('uploads/pwd/payment_document') ?>/<?= $payment->document_path ?>"><?= $payment->document_path ?></a></p>
    <input type="hidden" value="<?= $application->id ?>" id="pwd_app_ai_id">
    <input type="hidden" value="<?= $payment->pay_id ?>" id="pay_id">
</div>