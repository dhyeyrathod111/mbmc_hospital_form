<div id="alert_message"></div>
<div class="form-group">
    <label>Status<span class="red">*</span></label>
    <select required class="form-control" name="status">
        <option value="">---Select Status---</option>
        <?php foreach ($approvel_status as $status) : ?>
            <option value="<?= $status['status_id'] ?>"><?= $status['status_title'] ?></option>
        <?php endforeach ; ?>
    </select>
</div>
<div class="form-group">
    <label>Remarks<span class="red">*</span></label>
    <textarea rows="5" style="height: auto !important;" name="remarks" class="form-control" required></textarea>
</div>
<div>
    <input type="hidden" value="<?= $postdata['dept_id'] ?>" id="dept_id" name="dept_id">
    <input type="hidden" value="<?= $postdata['app_id'] ?>" id="app_id" name="app_id">
    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" id="remark_submit" style="float: right;" class="btn btn-primary">Save changes</button>
</div>