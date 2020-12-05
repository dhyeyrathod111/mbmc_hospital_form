<div id="alert_message"></div>

<?php if (empty($insection_form)) : ?> 
<div class="row">
    <div class="form-group col-12">
        <label for="exampleFormControlInput1">Bio-Medical waste disposal Certificate valid upto:</label>
        <input readonly type="text" id="bio_medical_valid_date" name="bio_medical_valid_date" class="form-control">
    </div>   
   <!-- <div class="form-group col-6">
        <label for="exampleFormControlInput1">MPCB Certificate valid upto:</label>
        <input readonly type="text" id="mpcb_certificate_valid_date" name="mpcb_certificate_valid_date" class="form-control">
    </div>  -->
</div>                    
<!--<div class="row">
    <div class="form-group col-6">
        <label for="exampleFormControlInput1">No of beds:</label>
        <input type="number" name="no_of_beds" class="form-control">
    </div>-->
    <!-- <div class="form-group col-6">
        <label for="exampleFormControlInput1">No of toilets:</label>
        <input type="number" name="no_of_toilets" class="form-control">
    </div> -->
</div>
<div class="form-check">
    <input class="form-check-input" name="doc_degree_certificate" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Doctor degree Certificate.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="doc_reg_mmc" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Doctor's Registration form MMC.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="agreement_copy" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Agreement Copy / Leave & license copy.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="tax_recipes" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Tax Receipt.
    </label>
</div>
<!-- <div class="form-check">
    <input class="form-check-input" name="nursing_certificate" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Nursing certificate from Maharashtra Nursing Council.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="noc_from_society" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from society for lab/nursing home.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="noc_from_town_planning_mbmc" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from town planing department, MBMC.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="noc_from_fire_dept" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from fire department every 6 month.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="general_observation" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Genral observations.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" name="labour_room_availability" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Labour Room if available is well equipped  with all instruments. O.T. Well equipment with saprate wash room & emergency drug.
    </label>
</div>
 --><hr />
<div class="row submit_button_inspection_form" style="display: block;">
    <input type="hidden" value="<?= $application->app_id ?>" name="app_id">
    <input type="hidden" value="1" name="sub_dept_id">
    <button data-dismiss="modal" class="btn btn-primary">cancel</button>
    <button type="submit" id="inspection_form_submitbtn" class="btn btn-success" style="float: right;">Submit</button>
</div>


<?php else : ?>



<div class="row">
    <div class="form-group col-12">
        <label for="exampleFormControlInput1">Bio-Medical wast valid upto:</label>
        <input readonly type="text" value="<?= $insection_form->bio_medical_valid_date ?>" id="bio_medical_valid_date" name="bio_medical_valid_date" class="form-control">
    </div>   
    <!-- <div class="form-group col-6">
        <label for="exampleFormControlInput1">MPCB Certificate valid upto:</label>
        <input readonly type="text" value="<?= $insection_form->mpcb_certificate_valid_date ?>" id="mpcb_certificate_valid_date" name="mpcb_certificate_valid_date" class="form-control">
    </div> --> 
</div>                    
<!-- <div class="row">
    <div class="form-group col-6">
        <label for="exampleFormControlInput1">No of beds:</label>
        <input type="number" value="<?= $insection_form->no_of_beds ?>" name="no_of_beds" class="form-control">
    </div>
    <div class="form-group col-6">
        <label for="exampleFormControlInput1">No of toilets:</label>
        <input type="number" name="no_of_toilets" value="<?= $insection_form->no_of_toilets ?>" class="form-control">
    </div>
</div> -->
<div class="form-check">
    <input class="form-check-input" <?= ($insection_form->doc_degree_certificate == 1) ? "checked" : '' ?> name="doc_degree_certificate" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Doctor degree Certificate.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" <?= ($insection_form->agreement_copy == 1) ? "checked" : '' ?> name="agreement_copy" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Doctor Ragistration form MMC.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" <?= ($insection_form->doc_reg_mmc == 1) ? "checked" : '' ?> name="doc_reg_mmc" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Ageement Copy / Leave & license copy.
    </label>
</div>
<div class="form-check">
    <input class="form-check-input" <?= ($insection_form->tax_recipes == 1) ? "checked" : '' ?> name="tax_recipes" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Tax Receipt.
    </label>
</div>
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->nursing_certificate == 1) ? "checked" : '' ?> name="nursing_certificate" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Nursing certificate from Maharashtra Nursing Council.
    </label>
</div> -->
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->noc_from_society == 1) ? "checked" : '' ?> name="noc_from_society" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from society for lab/nursing home.
    </label>
</div> -->
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->noc_from_town_planning_mbmc == 1) ? "checked" : '' ?> name="noc_from_town_planning_mbmc" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from town planing department, MBMC.
    </label>
</div> -->
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->noc_from_fire_dept == 1) ? "checked" : '' ?> name="noc_from_fire_dept" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        NOC from fire department every 6 month.
    </label>
</div> -->
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->general_observation == 1) ? "checked" : '' ?> name="general_observation" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Genral observations.
    </label>
</div> -->
<!-- <div class="form-check">
    <input class="form-check-input" <?= ($insection_form->labour_room_availability == 1) ? "checked" : '' ?> name="labour_room_availability" type="checkbox">
    <label class="form-check-label" for="defaultCheck1">
        Labour Room if available is well equipped  with all instruments. O.T. Well equipment with saprate wash room & emergency drug.
    </label>
</div> -->
<?php endif ; ?>