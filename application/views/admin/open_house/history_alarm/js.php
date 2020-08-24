$(document).ready(function(){
  data_alarm();
  $('.datepicker').datepicker({
    autoclose: true
  });
});

function data_alarm(){
  var datkandang = window.location.pathname.split('/')[5];
  var dari = $('[name="tanggal_dari"]').val();
  var sampai = $('[name="tanggal_sampai"]').val();
  var datperiode = $('#inputperiode').val();

  if (dari == '') {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data filter tanggal dari masih kosong</p>',
      type : "warning",
    });
    return;
  }
  if (datperiode == '' || datperiode < 1) {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data Periode Salah! Mohon set ulang</p>',
      type : "warning",
    });
    return;
  }
  if (sampai == '') {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data filter tanggal sampai masih kosong</p>',
      type : "warning",
    });
    return;
  }
  if (dari > sampai && dari == '' && sampai == '') {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data filter Salah! Mohon set ulang</p>',
      type : "warning",
    });
    return;
  }

  $.ajax({
    type: "POST",
    url : "<?php echo base_url('history_alarm/datajson'); ?>",
    data : {
      'value1' : datkandang,
      'value2' : dari,
      'value3' : sampai,
      'value4' : datperiode,
    },
    dataType: "HTML",
    success: function(data){
      $('#data_alarm').html('');
      $('#data_alarm').html(data);
    }
  });
}

