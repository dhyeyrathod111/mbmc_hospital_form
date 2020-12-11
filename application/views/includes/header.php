<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>MBMC | Dashboard</title>
      <!-- Tell the browser to be responsive to screen width -->
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <!-- Font Awesome -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/fontawesome-free/css/all.min.css">
      <!-- Ionicons -->
      <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
      <!-- Tempusdominus Bbootstrap 4 -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
      <!-- iCheck -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
      <!-- JQVMap -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/jqvmap/jqvmap.min.css">
      <!-- Theme style -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/adminlte.min.css">
      <!-- overlayScrollbars -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
      <!-- Daterange picker -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/daterangepicker/daterangepicker.css">
      <!-- summernote -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/summernote/summernote-bs4.css">
      <!-- bootstrap select -->
      <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
      <!-- Toastr -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/toastr/toastr.min.css">
      <!-- Google Font: Source Sans Pro -->
      <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
      <link rel="stylesheet" href="<?php echo base_url()?>assets/custom/css/custom.css">
      <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/custom/css/custom.css"> -->
      <link rel="stylesheet" type="text/css" href="https://unpkg.com/microtip/microtip.css">
      <!-- DataTables -->
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
      <link rel="stylesheet" href="<?php echo base_url()?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
      <link rel = "stylesheet" href = "https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css">
      <!-- new style -->
      <!-- <link rel = "stylesheet" href="style.css"> -->
      <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/dist/css/bootstrap-year-calendar.css"> -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel="stylesheet"/>
      <script type="text/javascript">  base_url = "<?php echo base_url(); ?>"; </script>
      <style type="text/css">
         .ui-datepicker {
         width: 29em !important;
         }
         .odd td {
         text-align: center;
         /* background: #f8f9fa; */
         }
         .odd:hover td{
         background: #c4e2ff;
         border-color: #c4e2ff
         }
         .even{
         text-align: center;
         }
         .even:hover td{
         background: #c4e2ff;
         border-color: #c4e2ff
         }
         .dt-buttons {
         float: left;
         padding-left: 21px;
         }
         tr th{
         background: #e9ecef;
         }
         .card {
         box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
         margin-bottom: 1rem;
         margin-top: 1rem;
         }
         .form-control {
         display: block;
         width: 100%;
         padding: .375rem .50rem !important;
         font-size: 1rem;
         font-weight: 400;
         line-height: 1.5;
         color: #495057;
         background-color: #fff;
         }
      </style>
   </head>
   <body  class="hold-transition sidebar-mini layout-fixed">
      <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
         <!-- Left navbar links -->
         <ul class="navbar-nav">
            <li class="nav-item">
               <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
               <?php
                  $app_name = $this->uri->segment(1);
                  $slug_name = $this->uri->segment(2);
                  ?>
               <a href="<?=base_url()?>" class="nav-link">
               <?php
                  if($app_name !='') {
                    if($slug_name == 'create') {
                        if ($app_name == 'garden') {
                            echo '<h3>Application form for tree cutting / Tree transplantation / Tree NOC / Tree trimming.</h3>';
                        } else if ($app_name == 'hospital') {
                            echo "<h3> FORM B (See rules 4 & 6) </h3>";
                        } else if ($app_name == 'clinic') {
                            echo "<h3> MIRA BHAINDER MUNCIPAL CORPORATION HEALTH DEPARTMENT</h3>";
                        } else if ($app_name == 'lab') {
                          echo "<h3> MIRA BHAINDER MUNCIPAL CORPORATION HEALTH DEPARTMENT</h3>";
                        }

                        else {
                            echo '<h3 style="margin: -3% 0% 0% -11%;">Add Details</h3>';
                        }   
                    } elseif($slug_name == 'edit') {
                        if ($app_name == 'garden') {
                            echo '<h3>Application form for tree cutting / Tree transplantation / Tree NOC / Tree trimming.</h3>';
                        } else if ($app_name == 'hospital') {
                            echo "<h3> FORM B (See rules 4 & 6) </h3>";
                        } else if($app_name == 'clinic'){
                            echo "<h3> MIRA BHAINDER MUNCIPAL CORPORATION HEALTH DEPARTMENT</h3>";
                        } else if ($app_name == 'lab') {
                          echo "<h3> MIRA BHAINDER MUNCIPAL CORPORATION HEALTH DEPARTMENT</h3>";
                        }  
                        else {
                            echo '<h3 style="margin: -3% 0% 0% -1%;">Edit Details</h3>';
                        }    
                    } else {
                      echo '<h3 class="custom-head" style="margin: -6% 0% 0% -16%;">'.ucwords($app_name).'</h3>'; 
                    }
                  } else {
                    echo '<h3 style="margin: -6% 0% 0% -16%;">Dashboard</h3>';
                  }
                  ?>
               </a>
            </li>
         </ul>
         <!-- SEARCH FORM -->
         <!-- <form class="form-inline ml-3">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            </form> -->
         <!-- Right navbar links -->
         <ul class="navbar-nav ml-auto">
            <!-- Messages Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
               <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="far fa-comments"></i>
                 <span class="badge badge-danger navbar-badge">3</span>
               </a>
               <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <a href="#" class="dropdown-item">
                   <!-- Message Start - >
                   <div class="media">
                     <img src="dist/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                     <div class="media-body">
                       <h3 class="dropdown-item-title">
                         Brad Diesel
                         <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                       </h3>
                       <p class="text-sm">Call me whenever you can...</p>
                       <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                     </div>
                   </div>
                   <!-- Message End - ->
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                   <!-- Message Start - ->
                   <div class="media">
                     <img src="<?php echo base_url()?>/assets/dist/img/user8-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                     <div class="media-body">
                       <h3 class="dropdown-item-title">
                         John Pierce
                         <span class="float-right text-sm text-muted"><i class="fas fa-star"></i></span>
                       </h3>
                       <p class="text-sm">I got your message bro</p>
                       <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                     </div>
                   </div>
                   <!-- Message End - ->
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                   <!-- Message Start - ->
                   <div class="media">
                     <img src="<?php echo base_url()?>/assets/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                     <div class="media-body">
                       <h3 class="dropdown-item-title">
                         Nora Silvester
                         <span class="float-right text-sm text-warning"><i class="fas fa-star"></i></span>
                       </h3>
                       <p class="text-sm">The subject goes here</p>
                       <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> 4 Hours Ago</p>
                     </div>
                   </div>
                   <!-- Message End - ->
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
               </div>
               </li> -->
            <!-- Notifications Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
               <a class="nav-link" data-toggle="dropdown" href="#">
                 <i class="far fa-bell"></i>
                 <span class="badge badge-warning navbar-badge">15</span>
               </a>
               <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                 <span class="dropdown-item dropdown-header">15 Notifications</span>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                   <i class="fas fa-envelope mr-2"></i> 4 new messages
                   <span class="float-right text-muted text-sm">3 mins</span>
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                   <i class="fas fa-users mr-2"></i> 8 friend requests
                   <span class="float-right text-muted text-sm">12 hours</span>
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item">
                   <i class="fas fa-file mr-2"></i> 3 new reports
                   <span class="float-right text-muted text-sm">2 days</span>
                 </a>
                 <div class="dropdown-divider"></div>
                 <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
               </div>
               </li> -->
            <li class="nav-item">
               <a aria-label="Edit" data-microtip-position="top" role="tooltip" title="logout" href="<?=base_url()?>logout" class="nav-link">
               <i class="fas fa-power-off"></i>
               </a>
            </li>
         </ul>
      </nav>
      <?php
         $session_userdata = $this->session->userdata('user_session');
         // echo'ssss<pre>';print_r($session_userdata);exit;
         ?>
      <!-- /.navbar -->
