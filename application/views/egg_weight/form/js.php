<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
});

function selectdata_kandang(){
  var inidata = $.ajax({
    type: "GET",
    url : "<?php echo base_url('history_house/data_select_kandang'); ?>",
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      isiselect_kandang(data);

      dtkandang = data;
    }
  });
}

function isiselect_kandang(inidata){
  $('#optionselect_kandang').empty();
  $('#optionselect_kandang')
  .val('')
  .select2({
    placeholder : '-Select house-',
    allowClear : true,
    data : inidata,
  }).on("change", function () {
    getgrow();
  });
}

function save(){
    var ling = '<?php echo base_url('egg_weight/save_data'); ?>';
    var cek = validasi();
    if(cek != 1){
        $.ajax({
            url : ling,
            type: "POST",
            data: $('#form-aksi').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                get_sess(data.sess);
                if( data.status == true){
                    swal.fire({
                      title: "Berhasil!",
                      html : data.message,
                      type: "success",
                    });
                }
            }
        });
    }else{
        swal.fire({
            title: "Warning!",
            html : "Please fill in all the input",
            type: "warning",
        });
    }
}

function validasi(){
    var cek = 0;
    if($('[name="tanggal"]').val() == ''){cek = 1;}
    if($('[name="periode"]').val() == ''){cek = 1;}
    if($('[name="growday"]').val() == ''){cek = 1;}
    if($('[name="kandang"]').val() == ''){cek = 1;}
    if($('[name="input1"]').val() == ''){cek = 1;}
    return cek;
}

function getgrow(){
    if($('[name="kandang"]').val() == '' || $('[name="tanggal"]').val() == ''){return;}
    $.ajax({
      url : '<?php echo base_url('egg_weight/getgrow'); ?>',
      type: "POST",
      data: {
        'kandang' : $('#optionselect_kandang').val(),
        'tanggal' : $('[name="tanggal"]').val(),
      },
      dataType: "JSON",
      success: function(data)
      {
        get_sess(data.sess);
        $('[name="periode"]').val(data.periode);
        $('[name="growday"]').val(data.growday);
        $('[name="periode"]').removeAttr('disabled');
        $('[name="growday"]').removeAttr('disabled');
      }
    });
}