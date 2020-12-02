<?php $is_parent = TRUE ; ?>
<?php 
    foreach ($childApplication as $oneApplication) :
        if (!empty($childApplication)) {
            if ($oneApplication->app_id == $application->app_id) $is_parent = FALSE;
        } 
    endforeach ; 
?>
    <div id="alert_message"></div>
    <?php if ($is_parent && !empty($childApplication)) : ?>
        <div class="row">
            <div class="text-center text-danger col-12">
                <h5><?= "Unclosed child application" ?></h5>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">App ID</th>
                        <th scope="col">Applicant name</th>
                        <th scope="col">Applicant email</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($childApplication as $oneApplication) : ?>
                        <tr>
                            <td scope="row"><?= "MBMC-00000".$oneApplication->app_id ?></td>
                            <td><?= $oneApplication->applicant_name ?></td>
                            <td><?= $oneApplication->applicant_email_id ?></td>
                        </tr>
                    <?php endforeach ; ?>
                </tbody>
            </table>
        </div>
    <?php endif ; ?>

    <div class="form-group">
        <label>Refund Amount:</label>
        <input type="text" name="refundable_amount" required value="<?= $security_deposit->refundable_amount ?>" readonly class="form-control">
    </div>
    <div class="row">
        <div class="form-group col-6">
            <label>status:</label>
            <select class="form-control" id="payment_status" name="payment_status">
                <option value="">---Select payment status---</option>
                <option value="1">Paid</option>
                <option value="2">Unpaid</option>
            </select>
        </div>
        <div class="form-group col-6">
            <label>Payment type:</label>
            <select class="form-control" id="payment_type" name="payment_type">
                <option value="">---Select payment type---</option>
                <option disabled value="1">Online</option>
                <option value="2">Cash</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label>Remark note:</label>
        <textarea style="height: auto !important" rows="5" name="remark_note" class="form-control"></textarea>
    </div>
    <?php if ($is_parent && !empty($childApplication)) : ?>
        <div class="text-center text-danger">
            <p><?= "You can not close this application.Your have unclosed child application." ?></p>
        </div>
    <?php else : ?>
        <div>
            <input type="hidden" value="<?= $application->app_id ?>" name="app_id">
            <span class="btn btn-danger">Cancel</span>
            <button type="submit" id="submit_btn" class="btn btn-success float-right">Close application</button>
        </div>
    <?php endif ; ?>