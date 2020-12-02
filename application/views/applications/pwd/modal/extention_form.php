<div class="modal-body">
    <div id="alert_message"></div>
    <div class="text-center text-danger">
        <p class="font-weight-bold"> Note : You can apply for extension after <?= $extStartDate ?></p>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="name_of_road">work start date<span class="red">*</span></label>
                <input type="datetime" readonly class="form-control"  name="ext_date" id="ext_date" placeholder="Enter Date" autocomplete="off">
            </div>
        </div>
         <div class="col-6">
            <div class="form-group">
                <label for="name_of_road">work to date<span class="red">*</span></label>
                <input type="datetime" readonly class="form-control"  name="ext_to_date" id="ext_to_date" placeholder="Enter Work End Date" autocomplete="off">
            </div>
        </div>
        <div class="col-12">
            <div class="form-group">
                <label>Description<span class="red">*</span></label>
                <textarea name="description" style="height: auto !important;" class="form-control" rows="5"></textarea>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer text-center d-block">
    <input type="hidden" value="<?= base64_encode($application->app_id) ?>" id="app_id" name="app_id">
    <?php if (!empty($extention_info) && $extention_info->approved_by == 0) : ?>
        <p>Your last extention dated <?= $extention_info->date ?> TO <?= $extention_info->to_date ?> has not been approved.You have to wait for the approvel.</p>
    <?php else : ?>
        <button type="submit" id="submit_btn_ext" class="btn btn-primary">Create Extention</button>
    <?php endif ; ?>
</div>


