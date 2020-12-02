<div class="form-group">
    <label>Status<span class="red">*</span></label>
    <select class="form-control" name="status">
        <option value="">---Select Status---</option>
        <?php foreach ($approvel_status as $status) : ?>
            <option value="<?= $status['status_id'] ?>"><?= $status['status_title'] ?></option>
        <?php endforeach ; ?>
    </select>
</div>
<?php if (!empty($health_officers)) : ?>
    <div class="form-group">
        <label>Health officers<span class="red">*</span></label>
        <select required class="form-control" name="health_officers">
            <option value="">---Select Helth officer---</option>
            <?php foreach ($health_officers as $officer) : ?>
                <option value="<?= $officer->ward_id ?>"><?= $officer->ward_title ?></option>
            <?php endforeach ; ?>
        </select>
    </div>
<?php endif ; ?>
<div class="form-group">
    <label>Remarks<span class="red">*</span></label>
    <textarea rows="5" style="height: auto !important;" name="remarks" class="form-control"></textarea>
</div>
<div>
    <input type="hidden" value="<?= $postdata['lab_id'] ?>" id="lab_id" name="lab_id">
    <input type="hidden" value="<?= $postdata['dept_id'] ?>" id="dept_id" name="dept_id">
    <input type="hidden" value="<?= 1 ?>" id="sub_dept_id" name="sub_dept_id">
    <input type="hidden" value="<?= $postdata['app_id'] ?>" id="app_id" name="app_id">
    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
    <button type="submit" style="float: right;" class="btn btn-primary">Save changes</button>
</div>