<?php $first_segment = !empty($this->uri->segment(1)) ? $this->uri->segment(1) : 'Dashboard' ;  ?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="<?=base_url()?>" class="brand-link">
    <img src="<?php echo base_url()?>assets/images/mbmc_offical/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
        style="opacity: .8">
    <span class="brand-text font-weight-light">MBMC </span>
    </a>
    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <?php
                $session_data = $this->session->userdata('user_session');
                $dept_id = $session_data[0]['dept_id'];
                $role_id = $session_data[0]['role_id'];
            ?>
            <div class="image">
                <img src="<?php echo base_url()?>/assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                <?= $session_data[0]['user_name'];?>
                </a>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <?php if($session_data[0]['is_user'] == '0') {?>
                <li class="nav-item has-treeview menu-open">
                    <a href="<?= base_url() ?>" class="nav-link <?php echo ($first_segment === 'Dashboard') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
				
                <?php if($this->authorised_user['is_superadmin'] == 1  && $this->authorised_user['is_user'] == '0') : ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url()?>departments" class="nav-link <?php echo ($first_segment === 'departments') ? 'active' : '' ?>">
                            <i class="nav-icon far fa-building"></i>
                            <p>
                                Departments
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview">
                        <a href="<?php echo base_url()?>users" class="nav-link <?php echo ($first_segment === 'users') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                    </li>
                <?php endif ;  ?>
                
                <li class="nav-header">MISCELLANEOUS</li>
                
            <?php }else{
                echo "<li class='nav-header'>DEPARTMENTS</li>";
            }//end user condition ?>
            
			<?php 
				$dept_name = $this->db->query("SELECT * FROM department_table WHERE dept_title = '$first_segment'")->row(); 
			?>

                <li class="nav-item <?php echo ((!empty($dept_name->dept_title) && strtolower($dept_name->dept_title) === $first_segment) || ($first_segment == 'lab' ||  $first_segment == 'clinic' || $first_segment == 'hospital')) ? 'menu-open' : '' ; ?>">
                    <a href="<?php echo base_url()?>applications" class="nav-link">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Applications </p>
                        <i class="fas fa-angle-right right"></i>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php
                            if($this->authorised_user['is_superadmin'] != 1 && $this->authorised_user['is_user'] == '0'){
                                $dept = $this->db->query("SELECT * FROM department_table WHERE status ='1' AND is_deleted='0' AND dept_id = ".$this->authorised_user['dept_id'])->result_array();
                            } else {
                                $dept = $this->db->query("SELECT td.* FROM (SELECT dt.*, (SELECT GROUP_CONCAT(role_id) FROM permission_access WHERE dept_id = dt.dept_id AND status = '1') roles FROM `department_table` dt WHERE dt.status = '1' AND dt.is_deleted = '0') td WHERE (FIND_IN_SET('1',td.roles) = 0 OR td.roles IS NULL)")->result_array();
                            }
                            foreach ($dept as $key => $val) {  ?> 
                        <li class="nav-item">
                            <?php 
                                if($val['dept_id'] != '5') { 
                                    
                                    if($this->authorised_user['is_user'] == '0'){
                            ?>
                                        <a href="<?=base_url().strtolower($val['dept_title']);?>" class="nav-link <?php echo ($first_segment === strtolower($val['dept_title'])) ? 'active' : '' ; ?>">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p><?= ($val['dept_title']  !='Medical') ?  $val['dept_title'] :  ''; ?></p>
                                        </a>
                                        
                            <?php 
                                    } else {
                                        
                                    $getAppRoutes = $this->db->query("SELECT dept_id, slug, controller, method, grp_index,sub_slug FROM `app_routes` WHERE slug LIKE 'create%' AND status = '1' AND dept_id = '".$val['dept_id']."' AND grp_index != '0' ORDER BY dept_id asc limit 1")->result_array();  
                                     if(!empty($getAppRoutes)){
                            ?>
                                        <a href="<?=base_url().$getAppRoutes[0]['sub_slug'].'/'.$getAppRoutes[0]['slug'];?>" class="nav-link <?php echo ($first_segment === strtolower($val['dept_title'])) ? 'active' : '' ; ?>">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p><?= ($val['dept_title']  !='Medical') ?  $val['dept_title'] :  ''; ?></p>
                                        </a>
                            <?php
                                     }
                                    }
                                } else { 
                                    if($this->authorised_user['is_user'] == '0'){
                            ?>
                            <a href="<?php echo base_url()?>hospital" class="nav-link <?php echo ($first_segment === 'hospital') ? 'active' : '' ; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hospital</p>
                            </a>
                            <a href="<?php echo base_url()?>clinic" class="nav-link <?php echo ($first_segment === 'clinic') ? 'active' : '' ; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Clinic</p>
                            </a>
                            <a href="<?php echo base_url()?>lab" class="nav-link <?php echo ($first_segment === 'lab') ? 'active' : '' ; ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Labs</p>
                            </a>
                            <?php  
                                    }else{
                                        $getAppRoutes = $this->db->query("SELECT dept_id, slug, controller, method, grp_index,sub_slug FROM `app_routes` WHERE slug LIKE 'create%' AND status = '1' AND dept_id = '".$val['dept_id']."' AND grp_index != '0' ORDER BY dept_id asc")->result_array();
                            ?>
                                        <a href="<?php echo base_url().$getAppRoutes[0]['sub_slug'].'/'.$getAppRoutes[0]['slug']; ?>" class="nav-link <?php echo ($first_segment === 'hospital') ? 'active' : '' ; ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Hospital</p>
                                        </a>
                                        <a href="<?php echo base_url().$getAppRoutes[1]['sub_slug'].'/'.$getAppRoutes[1]['slug']; ?>" class="nav-link <?php echo ($first_segment === 'clinic') ? 'active' : '' ; ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Clinic</p>
                                        </a>
                                        <a href="<?php echo base_url().$getAppRoutes[2]['sub_slug'].'/'.$getAppRoutes[2]['slug']; ?>" class="nav-link <?php echo ($first_segment === 'lab') ? 'active' : '' ; ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>Labs</p>
                                        </a>
                            <?php
                                    }
                                }
                            ?>
                        </li>
                        <?php  }  ?>
                    </ul>
                </li>
                
                <?php if($session_data[0]['is_user'] == '0') {?>
                <!-- <li class="nav-item">
                    <a href="<?php echo base_url() ?>reports" class="nav-link <?php echo ($first_segment === 'reports') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>Reports</p>
                        <i class="fas fa-angle-right right"></i>
                    </a>
                </li> -->
                <li class="nav-header">Settings</li>
                <?php if($this->authorised_user['is_superadmin'] == 1) : ?>
                    <li class="nav-item">
                        <a href="<?php echo base_url()?>roles" class="nav-link <?php echo ($first_segment === 'roles') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Roles
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo base_url()?>permissions" class="nav-link <?php echo ($first_segment === 'permissions') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-user-check"></i>
                            <p>
                                Permissions
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                    </li>
                <?php endif ; if($this->authorised_user['role_id'] == 1 || $this->authorised_user['is_superadmin'] == 1) : ?>
                    <li class="nav-item has-treeview">
                        <a href="<?php echo base_url('rolestatus') ?>" class="nav-link <?php echo ($first_segment === 'rolestatus') ? 'active' : '' ?>"> 
                            <i class="nav-icon far fa-check-circle"></i>
                            <p>
                                Role Status
                                <i class="fas fa-angle-right right"></i>
                            </p>
                        </a>
                    </li>
                <?php endif ; ?>

                <!-- <li class="nav-item has-treeview">
                    <a href="<?php echo base_url()?>mail" class="nav-link <?php echo ($first_segment === 'mail') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-envelope"></i>
                        <p>
                            Mail
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li> -->
                <?php if($this->authorised_user['is_superadmin'] == 1) : ?>
                <li class="nav-header">Masters</li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>road" class="nav-link <?php echo ($first_segment === 'road') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-road"></i>
                        <p>
                            Road Type
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>status" class="nav-link <?php echo ($first_segment === 'status') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            Application Status
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>sku" class="nav-link <?php echo ($first_segment === 'sku') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-barcode"></i>
                        <p>
                            Sku
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>unit" class="nav-link <?php echo ($first_segment === 'unit') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-cube"></i>
                        <p>
                            Unit
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>price" class="nav-link <?php echo ($first_segment === 'price') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-barcode"></i>
                        <p>
                            Sku Price
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>garden/addTree" class="nav-link <?php echo ($first_segment === 'garden' && $this->uri->segment(2) === 'addTree') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-tree"></i>
                        <p>
                            Add Tree
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>garden/addProcess" class="nav-link <?php echo ($first_segment === 'garden' && $this->uri->segment(2) === 'addProcess') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-plus-circle"></i>
                        <p>
                            Add Process
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>templic/addLicType" class="nav-link <?php echo ($first_segment === 'templic') ? 'active' : '' ?>">
                        <i class="nav-icon far fa-id-badge"></i>
                        <p>
                            Add License Type
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>hall-service" class="nav-link <?php echo ($first_segment === 'hall-service') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-hotel"></i>
                        <p>
                            Hall Services
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>designation" class="nav-link <?php echo ($first_segment === 'designation') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-id-card-alt"></i>
                        <p>
                            Designation
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>qualification" class="nav-link <?php echo ($first_segment === 'qualification') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Qualification
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>advertisement/adv_index" class="nav-link <?php echo ($this->uri->segment(2) === 'adv_index') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-ad"></i>
                        <p>
                            Advertisement Type
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url()?>advertisement/illuminate_index" class="nav-link <?php echo ($this->uri->segment(2) === 'illuminate_index') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-low-vision"></i>
                        <p>
                            Illuminate
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('ward') ?>" class="nav-link <?php echo ($first_segment === 'word') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-low-vision"></i>
                        <p>Ward<i class="fas fa-angle-right right"></i></p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('depositinspection') ?>" class="nav-link <?php echo ($first_segment === 'depositinspection') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-low-vision"></i>
                        <p>
                            Deposit Inspection Fees
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('garden_permission') ?>" class="nav-link <?php echo ($first_segment === 'garden_permission') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-low-vision"></i>
                        <p>
                            Garden Permission
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a href="<?php echo base_url('defect_liab') ?>" class="nav-link <?php echo ($first_segment === 'defect_liab') ? 'active' : '' ?>">
                        <i class="fas fa-business-time"></i>
                        <p>
                            Defect Laiblity Period
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
				<li class="nav-item">
                    <a href="<?php echo base_url('company_details') ?>" class="nav-link <?php echo ($first_segment === 'company_details') ? 'active' : '' ?>">
                        <i class="fas fa-building"></i>
                        <p>
                            Company Details
                            <i class="fas fa-angle-right right"></i>
                        </p>
                    </a>
                </li>
            <?php endif ; ?>
            <?php } ?>
            </ul>
        </nav>
    </div>
</aside>
