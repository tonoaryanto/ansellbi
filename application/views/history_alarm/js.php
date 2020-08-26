$(document).ready(function(){
  selectdata_kandang();
  $('.datepicker').datepicker({
    autoclose: true
  });
});

function selectdata_kandang(){
  $.ajax({
    type: "GET",
    url : "<?php echo base_url('history_alarm/data_select_kandang'); ?>",
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      isiselect_kandang(data);
    }
  });
}

function isiselect_kandang(inidata){
  $('#optionselect_kandang').empty();
  $('#optionselect_kandang')
  .val('')
  .select2({
    placeholder : '-Select House-',
    allowClear : true,
    data : inidata,
  });
}

function data_alarm(){
  var datkandang = $('#optionselect_kandang').val();
  var dari = $('[name="tanggal_dari"]').val();
  var sampai = $('[name="tanggal_sampai"]').val();
  var datperiode = $('#inputperiode').val();

  if (datkandang == '' || datkandang == null || datkandang == undefined) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Incorrect house data</p>',
      type : "warning",
    });
    return;
  }
  if (dari == '') {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Incorrect date data</p>',
      type : "warning",
    });
    return;
  }
  if (datperiode == '' || datperiode < 1) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Incorrect period data</p>',
      type : "warning",
    });
    return;
  }
  if (sampai == '') {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Start date is empty</p>',
      type : "warning",
    });
    return;
  }
  if (dari > sampai && dari == '' && sampai == '') {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">End date is empty</p>',
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

