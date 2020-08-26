function masuk(){
    var ling = '<?php echo base_url('login/masuk'); ?>';
    var cek = validasi();
    if(cek != 1){
        $.ajax({
            url : ling,
            type: "POST",
            data: $('#form-login').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                if( data.status == true){
                  notifikasi('Login Success!','Welcome back<b>'+data.nama_user+'</b>!','success');
                  location.replace(data.url);
                }else{
                  notifikasi('Warning!',data.message,'danger');                
              }
            }
        });
    }
}

function validasi(){
    if($('[name="username"]').val() == ''){
        notifikasi('Warning!','<b>Username</b> is empty.','warning');
        $('[name="username"]').focus();
        return 1;
    }
    if($('[name="password"]').val() == ''){
        notifikasi('Warning!','<b>Password</b> is empty.','warning');
        $('[name="password"]').focus();
        return 1;
    }
}

function notifikasi(texthead,textisi,notif=null) {
  $('#cnotif').html('');
  if (notif == 'danger') {
    var icon = '<i class="icon fa fa-ban"></i> ';
    var box = 'alert alert-danger alert-dismissible';
    var box_style = "display:none;border: none;border-radius: 10px;box-shadow: 5px 5px 10px #0000004d;background-color:#dd4b39cc !important;";
  }
  if(notif == 'warning'){
    var icon = '<i class="icon fa fa-warning"></i> ';
    var box = 'alert alert-warning alert-dismissible';
    var box_style = "display:none;border: none;border-radius: 10px;box-shadow: 5px 5px 10px #0000004d;background-color: #f39c12cc !important;";
  }
  if(notif == 'success'){
    var icon = '<i class="icon fa fa-check"></i> ';
    var box = 'alert alert-success alert-dismissible';
    var box_style = "display:none;border: none;border-radius: 10px;box-shadow: 5px 5px 10px #0000004d;background-color: #00a65acc !important";
  }

  var isi = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4>' + icon + texthead + '</h4>' + textisi;

  if (notif != null) {
    $('<div>')
    .attr({
        'id:' : 'cnotif-div',
        'class' : box,
        'style' : box_style
    })
    .html(isi)
    .appendTo('#cnotif').fadeIn( "slow" );
  }
}
