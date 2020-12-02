<footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.1.0
    </div>
    <strong>Copyright &copy; 2020<a href="http://www.aaravsoftware.com/"> MBMC</a>.</strong> All rights
    reserved.
</footer>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
        crossorigin="anonymous"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>/assets/dist/js/adminlte.min.js"></script>

<script src="<?php echo base_url()?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.js"></script>
<script src="<?php echo base_url()?>/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
<!-- DataTables -->
<script src = "https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<!-- <script src="<?php echo base_url()?>assets/plugins/datatables/jquery.dataTables.min.js"></script> -->
<script src="<?php echo base_url()?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?php echo base_url()?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
	$('select').selectpicker();
  var year = (new Date).getFullYear();
  	$('#letter_date').datepicker({maxDate: '0',minDate: new Date(year, 0, 1)});
  	$('#application_date').datepicker();

    function sweet_alert(messg1,messg2,type) {
      swal(messg1,messg2,type);
    }

    $("#renewalDate").datepicker({ minDate: 0});
    $("#renewal_dateEdit").datepicker({ minDate: 0});
    //factLic
    $("#propertyDate").datepicker();
    $("#noObjDate").datepicker();
    $("#foodLicDate").datepicker();
    $("#propTaxDate").datepicker();
    $("#establishmentDate").datepicker();
    $("#assuranceDate").datepicker();
    $("#bio_waste_valid").datepicker();
    $("#jv_date").datepicker({ minDate: 0 , dateFormat: 'yy-mm-dd'});
    
    //garden
    $("#fromDate").datepicker({dateFormat: 'yy-mm-dd'});
    $("#toDate").datepicker({dateFormat: 'yy-mm-dd'});
    //end fact lic
    
    function status_response(title,url,data,ele){
      // console.log(data);
      swal({
        title: title,
        text: '',
        icon: 'warning',
        buttons: true,
        dangerMode: true,
      })
      .then((willactive) => {
        if (willactive) {
          $.ajax({
            type: 'POST',
            url: url,
            dataType: "Json",
            data: data,
            success: function(res) {
              // console.log(res);
              if(res.success =='1' || res.status == '1') {
                swal("Good Job!",res.messg,"success").then((willactive) => {
                  window.location.reload();
                });
                // ele.draw();
              } else if(res.success =='2' || res.status == '1'){
                swal("Warning!",res.messg,"warning");
                // ele.draw();
              } 
            },
          });
        } else {
          // sweet_alert("Warning!",'Oops! something went wrong.',"warning");
        }
      });
    }



    $("#renewalDate").datepicker();
    $("#renewal_dateEdit").datepicker();


    $(document).on('click','.doc_button',function(){
      var image_id = $(this).attr('data-image');
      // alert(app_id);
      if(image_id !='') {
        
        // $('#status').html('');
        $.ajax({
          type: 'POST',
          url: base_url +'image/imagedetails',
          dataType: "Json",
          data:{'image_id':image_id},
          success: function(res) {
             
              var status = res.status
              var image_data = res.image_data;
              var tbody ='';
              if(res.status =='1') {
                 // console.log(res);
                 i = 1;
                $.each(image_data , function(index, val) { 
                  console.log(val.image_path  );
                  i++;
                  tbody +='<tr><td>'+i+'</td><td>'+val.image_name+'</td><td class=""><a href="'+val.image_path+'"><i class="black fas fa fa-download right"></i></a></td></tr>';
                });
                
              } else if(res.status =='2'){
                tbody ="";
              } 
              console.log(tbody);
              $('#doc-body').html(tbody);
          },
      });
      }
    });
    //storage
    $("#startDate").datepicker();


    //storage
    $("#startDate").datepicker();

    //film
    $("#periodFrom").datepicker();
    $("#periodTo").datepicker();

    //advertisement
    $("#start_date").datepicker();
  //storage
  $("#startDate").datepicker();

  $("#applicationDate").datepicker();

  $("#date_from").datepicker({
    dateFormat: 'yy-mm-dd'
  });

  $("#date_till").datepicker({
    dateFormat: 'yy-mm-dd'
  });

  $('[data-toggle="tooltip"]').tooltip();


  function notify_success(message) {
      let html_str = '<div class="alert alert-info text-center"><strong>'+ message +'</strong></div>';
      $('#alert_message').fadeIn();
      $('#alert_message').html(html_str).fadeOut(4000);
  }
  function notify_error(message = '') {
      if (message === '') {
          message = "Sorry, we have to face some technical issues please try again later."
      } 
      let html_str = '<div class="alert alert-warning text-center"><strong>'+ message +'</strong></div>';
      $('#alert_message').fadeIn();
      $('#alert_message').html(html_str).fadeOut(4000);
  }
  
</script>

