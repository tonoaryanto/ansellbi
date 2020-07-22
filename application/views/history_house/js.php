<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
var kategrafik = 'DAY_1';

$(document).ready(function(){
  $('#btnprint').on('click',(function(e){

    e.preventDefault();

    if ($('[name="val_kandang" ]').val() == '') {
      swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon Input Nama Kandang Di Isi Terlebih Dahulu!</p>',
        type: "warning",
      });
      $('[name="name="val_kandang" "]').focus();
      return;
    }
    if ($('[name="val_data"]').val() == null) {
      swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon Input Data Di Isi Terlebih Dahulu!</p>',
        type: "warning",
      });
      $('[name="val_data"]').focus();
      return;
    }
    if ($('[name="val_periode"]').val() == null) {
      swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon Input Periode Di Isi Terlebih Dahulu!</p>',
        type: "warning",
      });
      $('[name="val_periode"]').focus();
      return;
    }
    if (parseInt($('[name="daydari"]').val()) > parseInt($('[name="daysampai"]').val())) {
      swal.fire({
        title: "Peringatan!",
        html : '<p style="font-size: 14px">Data filter Salah! Mohon set ulang</p>',
        type : "warning",
      });
      return;
    }

    $('#btnprint').attr('disabled','true');
    
    Swal.fire({
      title: 'Memproses Data',
      html: '<p style="font-size: 14px">Mohon tunggu proses ini memerlukan waktu.</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });

    $.ajax({
        url : "<?php echo base_url('export_excel/history_house_day');?>",
        type: "POST",
        data: {
        'value1' : $('[name="val_data"]').val(),
        'value2' : $('[name="val_kandang"]').val(),
        'value3' : $('[name="daydari"]').val(),
        'value4' : $('[name="daysampai"]').val(),
        'value5' : $('[name="val_periode"]').val(),
        },
        dataType:"JSON",
        success: function(data){
          get_sess(data.sess);
          if(data.status == true){
            swal.fire({
              title: "Save File",
              html : '<p style="font-size: 14px">Silahkan klik Save file</p>',
            });
            $('#btnprint').removeAttr('disabled');
            location.replace(data.url);
          }else{
            $('#tombol').removeAttr('disabled');
            swal.fire({
              title: "Gagal!",
              html : data.message,
              type: "error",
            });
            $('#btnprint').removeAttr('disabled');
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          $('#btnprint').removeAttr('disabled');
          swal.fire({
            title: "Gagal!",
            html : '<p style="font-size: 14px">Terjadi Kesalahan!</p>',
            type: "error",
          });
        }
    });
  }));

  selectdata_kandang();
});

function loadtabel() {
  $('#tglresponse').empty();
  $('<table>')
  .attr({
    'class' : 'table table-striped- table-bordered table-hover table-checkable',
    'id' : 'mytable',
    'style' : 'min-width: 500px',
  })
  .appendTo('#tglresponse');

  $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings){
      return {
          "iStart": oSettings._iDisplayStart,
          "iEnd": oSettings.fnDisplayEnd(),
          "iLength": oSettings._iDisplayLength,
          "iTotal": oSettings.fnRecordsTotal(),
          "iFilteredTotal": oSettings.fnRecordsDisplay(),
          "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
          "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
      };
  };
  $("#mytable").DataTable({
    initComplete: function() {
        var api = this.api();
        $('#mytable_filter input')
                .off('.DT')
                .on('keyup.DT', function(e) {
                    if (e.keyCode == 13) {
                        api.search(this.value).draw();
            }
        });
    },
    language: {
               "infoFiltered": "",
               "processing": "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sedang mengambil data..."
             },
    responsive:true,
    processing:true,
    serverSide:true,
    ajax:{
        url:"<?php echo base_url('history_house/datatabel');?>",
        type:"POST",
        data : {
        'value1' : kategrafik,
        'value2' : function(){ return $('#optionselect').val();},
        'value3' : function(){ return $('#optionselect_kandang').val();},
        'value4' : function(){ return $('[name="daydari"]').val();},
        'value5' : function(){ return $('[name="daysampai"]').val();},
        'value7' : function(){ return $('#inputperiode').val();}
      }
    },
    columns:[
        {
            title: "NO",
            data:"id",
            seacrhable: false,
            orderable: false
        },
        {
            title: "TANGGAL",
            data:"tanggal_value",
        },
        {
            title: "JAM",
            data:"jam_value",
        },
        {
            title: "GROW DAY",
            data:"grow_value",
        },
        {
            title: "DATA",
            data:"isi_value",
        },
    ],
    order: [[3, 'asc']],
    rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
    }
  });
}

function grafik(){
  var datgraf = $('#optionselect').val();
  var datkandang = $('#optionselect_kandang').val();
  var datperiode = $('#inputperiode').val();

  $('#inicanvas').empty();
  $('#inicanvas').html('Sedang Memproses . . .');

  if (datkandang == '' || datkandang == null || datkandang == undefined) {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data Kandang Masih Kosong!</p>',
      type : "warning",
    });
    return;
  }
  if (datgraf == '' || datgraf == null || datgraf == undefined) {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data Grafik Masih Kosong!</p>',
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
  if ($('[name="daydari"]').val() > $('[name="daysampai"]').val()) {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Data filter Salah! Mohon set ulang</p>',
      type : "warning",
    });
    return;
  }

  $.ajax({
    type: "POST",
    url : "<?php echo base_url('history_house/datajson/'); ?>",
    data : {
      'value1' : kategrafik,
      'value2' : datgraf,
      'value3' : datkandang,
      'value4' : $('[name="daydari"]').val(),
      'value5' : $('[name="daysampai"]').val(),
      'value7' : datperiode,
    },
    dataType : "JSON",
    success : function(data){
      get_sess(data.sess);
      if(data.status == true){

        $('#inicanvas').empty();
        $('<canvas>')
        .attr({'id' : 'chartcanvas'})
        .appendTo('#inicanvas');

        var canvas = document.getElementById('chartcanvas')
        var ctx = canvas.getContext('2d');
        ctx.clearRect(0,0,canvas.width,canvas.height);

        window.myLine = new Chart(ctx, {
          type: 'line',
          data: {
            labels: data.labelgf,
            datasets: [{
                label: data.label,
                borderColor: window.chartColors.blue,
                backgroundColor: window.chartColors.blue,
                fill: false,
                data: data.data,
                yAxisID: 'y'
                }]
            },
          options: {
            responsive: true,
            hoverMode: 'index',
            stacked: true,
            title: {
              display: true,
              text: data.glabel
            },
          }
        });

        $('#titlegrafik').html(data.glabel);
        $('[name="daydari"]').val(data.daydari);
        $('[name="daysampai"]').val(data.daysampai);
        $('#tglresponse').removeAttr('style');
        $('#mytable').DataTable().ajax.url("<?php echo base_url('history_house/datatabel');?>").load();
      }else{
        $('#inicanvas').html('-Data Tidak Ditemukan-');
        $('#tglresponse').empty();
        swal.fire({
          title: "Gagal!",
          html : data.message,
          type : "warning",
        });
      }
    }
  });
}

function filter(){
  grafik();
}

function selectdata_kandang(){
  var inidata = $.ajax({
    type: "GET",
    url : "<?php echo base_url('history_house/data_select_kandang'); ?>",
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
    placeholder : '-Pilih Kandang-',
    allowClear : true,
    data : inidata,
  }).on("change", function () {
    selectdata();
  });
}

function selectdata(){
  if($('[name="val_periode"]').val() == '' || $('[name="val_periode"]').val() < 0){
    return;
  }

  var setdata = $('#optionselect_kandang').val();
  $('#optionselect').empty();
  $('<option>')
  .attr({'selected' : 'true','disabled' : 'true'})
  .text('Sedang memproses . . .')
  .appendTo('#optionselect');

  var inidata = $.ajax({
    type: "POST",
    url : "<?php echo base_url('history_house/data_select'); ?>",
    data : {
      'value1' : kategrafik,
      'value2' : $('#optionselect_kandang').val(),
      'value3' : $('[name="val_periode"]').val()
    },
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      isiselect(data);
    }
  });
}

function isiselect(inidata){
  $('#optionselect').empty();
  $('#optionselect')
  .val('')
  .select2({
    allowClear : true,
    placeholder : '-Pilih Data-',
    data : inidata,
  }).on("change", function () {
    $('[name="daydari"]').val('-1');
    $('[name="daysampai"]').val('-1');
  });
}

