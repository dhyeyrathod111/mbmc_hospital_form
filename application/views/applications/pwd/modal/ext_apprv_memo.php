<div id="alert_message"></div>
<?php if (!empty($previous_all_extention)) : ?>
    <div class="row">
        <div class="col-md-12 text-center">
            <h4 style="background: antiquewhite">Previous Extentions</h4>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">SR NO</th>
                    <th scope="col">Start date</th>
                    <th scope="col">Remark</th>
                    <th scope="col">End Date</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($previous_all_extention as $key => $oneExt) : ?>
                    <tr>
                        <td><?= $key+1 ?></td>
                        <td><?= $oneExt->date ?></td>
                        <td><?= $oneExt->description ?></td>
                        <td><?= $oneExt->to_date ?></td>
                        <td>
                            <?php 
                                switch ($oneExt->status) {
                                    case 1: echo "Reqeusted";break;
                                    case 2: echo "Approved";break;
                                    case 3: echo "Rejected";break;
                                }
                            ?>
                        </td>
                    </tr>
                <?php endforeach ; ?>
            </tbody>
        </table>
    </div>
<?php endif ; ?>

<div class="row">
    <div class="col-md-12 text-center">
        <h4 style="background: antiquewhite">New Extentions</h4>
    </div>
    <div class="col-md-4">
        <p> <b>Application ID:</b> MBMC-00000<?= $application->app_id ?> </p>
    </div>
    <div class="col-md-4">
        <p> <b> Applicant name:</b> <?= $application->applicant_name ?> </p>
    </div>
    <div class="col-md-4">
        <p> <b> Applicant Email:</b> <?= $application->applicant_email_id ?> </p>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <p><b>Work start date:</b> <?= $extention_data->date ?> </p>
    </div>
    <div class="col-md-4">
        <p><b>Work End date:</b> <?= $extention_data->to_date ?> </p>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <p><b>Remark:</b> <?= $extention_data->description ?> </p>
    </div>
</div>
<hr />
<div class="d-block">
    <input type="hidden" value="<?= $extention_data->id ?>" id="extention_id" name="extention_id">
    <input type="hidden" value="<?= $application->app_id ?>" id="pwd_app_id" name="pwd_app_id">
    <button type="button" class="btn btn-primary" id="extention_reject_btn">Reject</button>
    <button type="button" id="extention_approve_btn" class="btn btn-primary float-right">Approve</button>
</div>