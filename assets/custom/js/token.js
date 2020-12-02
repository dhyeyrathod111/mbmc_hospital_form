

$(document).ready(function(){
  
  // validate jwt to verify access
  var token = getCookie('token');
  // document.cookie;
  
  // return false;
  if(typeof token !== 'undefined') {
    // alert(token);
    console.log('token: ' +token);
    $.ajax({
      type: 'POST',
      url: base_url +'admin/validate_token',
      dataType: "Json",
      data:{'token':token},
      success: function(res) {
          console.log(res.messg);
          if(res.status =='2') {
            window.location = base_url + 'login';
          } else if(res.status =='2'){
              Toast.fire({
                type: 'error',
                title: res.messg
              })
          } 
        },
    });

  } else {
    window.location = base_url + 'login';
  }
  
  function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      
      d.setTime(d.getTime() + (1*24*60*60*1000));
      var expires = "expires="+ d.toUTCString();
      
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }
 
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    // return "hello";
  }

});