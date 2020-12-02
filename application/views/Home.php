
  <?php $this->load->view('includes/header'); ?>


  <!-- Main Sidebar Container -->
  <?php $this->load->view('includes/sidenav'); ?>
   
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Dashboard</h1>
          </div><!-- /.col -->
          <!--<div class="col-sm-6">-->
          <!--  <ol class="breadcrumb float-sm-right">-->
          <!--    <li class="breadcrumb-item"><a href="#">Home</a></li>-->
          <!--    <li class="breadcrumb-item active">Dashboard</li>-->
          <!--  </ol>-->
          <!--</div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $data['dailyRequest']; ?></h3>

                <p>Daily Request</p>
              </div>
              <div class="icon">
                <i class="fa fa-paper-plane"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $data['approvalPending']; ?></h3>

                <p>Approval Pending</p>
              </div>
              <div class="icon">
                <i class="fa fa-clock"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= round($data['totalamountcollected'], 2); ?></h3>

                <p>Total Amount Collected Daily</p>
              </div>
              <div class="icon">
                <i class="fa fa-credit-card" aria-hidden="true"></i>
              </div>
              
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= round($data['totalamountpending'],2); ?></h3>

                <p>Total Amount Pending</p>
              </div>
              <div class="icon">
                <i class="fa fa-credit-card"></i>
              </div>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
        <!-- Main row -->
        <div class="row">
          <!-- Left col -->
          <section class="col-lg-6 connectedSortable">
            <!-- Custom tabs (Charts with tabs)-->
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fa fa-paper-plane"></i>
                  Monthly Request
                </h3>
                
              </div><!-- /.card-header -->
              <div class = "card-body">
                <canvas id="graph" width="400" height="200"></canvas>
              </div>
              
            </div>
            <!-- /.card -->

            <!-- solid sales graph -->
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fa fa-thumbs-up"></i>
                  Monthly Request Approved and Pending
                </h3>
              </div>
              <div class = "card-body">
                <center>
                  <canvas id="myChartRequest" width="400" height="200"></canvas>
                </center>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->

          </section>
          <!-- /.Left col -->
          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-6 connectedSortable">

            <!-- Map card -->
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fa fa-arrow-circle-left"></i>
                  Yearly Request
                </h3>
              </div>
              <div class = "card-body">
                <!-- <div id="example"></div> -->
                <canvas id="bar-chart" width="800" height="450"></canvas>
              </div>  
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fa fa-thumbs-up"></i>
                  Yearly Approval vs Rejected
                </h3>
              </div>
              <div class = "card-body">
                <center>
                  <canvas id="myChart" width="400" height="200"></canvas>
                </center>
              </div>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->

            
          </section>
          <!-- right col -->
        </div>
        <!-- /.row (main row) -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php $this->load->view('includes/footer');?>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>\
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<!-- <script src="<?php //echo base_url()?>/assets/plugins/jquery/jquery.min.js"></script> -->
<!-- jQuery UI 1.11.4 -->
<script src="<?php echo base_url()?>/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<!-- <script src="<?php //echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script> -->
<!-- ChartJS -->
<!-- <script src="<?php echo base_url()?>/assets/plugins/chart.js/Chart.min.js"></script> -->
<!-- Sparkline -->
<!-- <script src="<?php echo base_url()?>/assets/plugins/sparklines/sparkline.js"></script> -->

<!-- jQuery Knob Chart -->
<!-- <script src="<?php echo base_url()?>/assets/plugins/jquery-knob/jquery.knob.min.js"></script> -->
<!-- daterangepicker -->
<script src="<?php echo base_url()?>/assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo base_url()?>/assets/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="<?php echo base_url()?>/assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<!-- <script src="<?php echo base_url()?>/assets/plugins/summernote/summernote-bs4.min.js"></script> -->
<!-- overlayScrollbars -->
<!-- <script src="<?php echo base_url()?>/assets/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script> -->
<!-- AdminLTE App -->
<!-- <script src="<?php //echo base_url()?>/assets/dist/js/adminlte.js"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!-- <script src="<?php echo base_url()?>/assets/dist/js/pages/dashboard.js"></script> -->
<!-- AdminLTE for demo purposes -->
<script src="<?php echo base_url()?>/assets/dist/js/demo.js"></script>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<!-- Line Graph -->
<!-- <script src="<?= base_url(); ?>/assets/lineGraph/topup.js"></script>
<script>
var chartData = {
  node:"graph",
  dataset: [122, 65, 80, 84, 33, 55, 95, 15, 268, 47, 72, 69],
  labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
  pathcolor:"#288ed4",
  fillcolor:"#8e8e8e",
  xPadding: 0,
  yPadding: 0,
  ybreakperiod: 50
};

drawlineChart(chartData);

$(document).find('#graph').css({'width': '90%'});
</script> -->
<!-- End Line Graph -->

<!-- Bar Graph -->
<!-- <link rel="stylesheet" href="<?= base_url(); ?>/assets/barGraph/dist/css/jquery.simple-bar-graph.min.css" />
<script src="<?= base_url(); ?>/assets/barGraph/dist/js/jquery.simple-bar-graph.min.js"></script>
<script>
  const myData = [
      { key:'2014', value: 91 },
      { key:'2015', value: 100 },
      { key:'2016', value: 95 },
      { key:'2017', value: 96 },
      { key:'2018', value: 44 },
      { key:'2019', value: 32 },
      { key:'2020', value: 130 },
  ];

  $('#example').simpleBarGraph({

    data: myData,
    barsColor: '#222',
    height:'400px',
    rowsCount: 5,
    rowCaptionsWidth:'16px',

  });

</script> -->
<!-- End Bar Graph -->

<!-- Pie Chart -->
<linl rel = "stylesheet" href = "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css">
<script src = "https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
<script>
  //Yearly Approved vs Rejected
  var ctx = document.getElementById("myChart").getContext("2d");
  var myPieChart = new Chart(ctx, {
    type: 'pie',
    data: {
    datasets: [{
        backgroundColor: ["#2ecc71","#F00"],
        data: [<?= json_encode($data['approvedForYear']) ?>, <?= $data['unapprovedForYear']; ?>]
      }],

    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
        'Approved',
        'Pending'
      ]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        title: {
          display: true,
          text: "Request Status For Year "+new Date().getFullYear()
        }
    }
  });
  $(document).find("#myChart").css({'height': '320px'});
  //EndYearly Approved vs Rejected

  var ctxLine = document.getElementById("graph").getContext("2d");
  var lineGraph = new Chart(ctxLine, {
    type: 'line',
    data: {
      labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      datasets: [{
          label: "Total Request",
          data: <?php echo json_encode($data['lineGraphArray']['totalRequest']); ?>,
          backgroundColor: "#212529",
          borderColor: "#212529",
          fill: false,
          lineTension: 0,
          radius: 5
        },
        {
          label: "Approved",
          data: <?php echo json_encode($data['lineGraphArray']['approvedArray']); ?>,
          backgroundColor: "#28a745",
          borderColor: "#28a745",
          fill: false,
          lineTension: 0,
          radius: 5
        },
        {
          label: "Pending",
          data: <?php echo json_encode($data['lineGraphArray']['unapprovedArray']); ?>,
          backgroundColor: "#007bff",
          borderColor: "#007bff",
          fill: false,
          lineTension: 0,
          radius: 5
        }
      ],
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        },
        title: {
          display: true,
          text: "Request For Year "+new Date().getFullYear()
        }
    }
  });
  $(document).find("#graph").css({'height': '320px'});

  new Chart(document.getElementById("myChartRequest"), {
    type: "bar",
    data: {
      labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
      datasets: [
        {
          label: "Approved",
          backgroundColor: "#3e95cd",
          data: <?= json_encode($data['ApprovedUnApproved']['approved']); ?>
        },
        {
          label: "Pending",
          backgroundColor: "#8e5ea2",
          data: <?= json_encode($data['ApprovedUnApproved']['unapproved']); ?>
        }
      ]
    },
    options: {
      title: {
        display: true,
        text: 'Monthly Request Status For Year '+new Date().getFullYear()
      }
    }
  });
  $(document).find("#myChartRequest").css({'height': '320px'});

  // Bar chart
  new Chart(document.getElementById("bar-chart"), {
    type: 'bar',
    data: {
      labels: <?php echo json_encode($data['yearlyRequest']['labels']); ?>,
      datasets: [
        {
          label: "Yearly Request Completed",
          backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850","#8e5ea2","#3e95cd"],
          data: <?php echo json_encode($data['yearlyRequest']['data']); ?>
        }
      ]
    },
    options: {
      legend: { display: false },
      title: {
        display: true,
        text: 'Request Completed Per Year'
      }
    }
});
  $(document).find("#bar-chart").css({'height': '320px'});
  //End Bar Chart

  // myChartRequest
</script>
<!-- End Pie Chart -->
</body>
</html>