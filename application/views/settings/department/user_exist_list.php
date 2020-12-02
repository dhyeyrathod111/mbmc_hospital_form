<div id="alert_message">
	
</div>

<table id="user_list_table" class="table">
    <thead>
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Departments</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    	<?php foreach ($user_list as $key => $user) : ?>
	        <tr class="user-tr-<?php echo $user->user_id ?>">
	            <td><?php echo $user->user_name ?></td>
	            <td><?php echo $user->email_id ?></td>
	            <td>
	            	<select name="new_dept_selected" class="browser-default custom-select">
					  	<option value="">---Select departments---</option>
					  	<?php foreach ($dept_list as $dept) : ?>
					  		<?php if ($dept->dept_id !== $user->dept_id) : ?>
					  			<option value="<?php echo $dept->dept_id ?>"><?php echo $dept->dept_title ?></option>
					  		<?php endif ; ?>
					  	<?php endforeach ; ?>
					</select>
	            </td>
	            <td><button user_id="<?php echo $user->user_id ?>" class="btn btn-success btn_chnage_dept"> Submit </button></td>
	            <?php $url = base_url('/users/edit/').base64_encode($user->user_id) ; ?>
	        </tr>
    	<?php endforeach ; ?>
    </tbody>
</table>