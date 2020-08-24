<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var dataini = {
  0 : ['3259'],
  1 : ['4'],
  2 : ['12'],
  3 : ['1']
};

$(document).ready(function(){
 reload_grafik();
  // selectdata_kandang();
});

function reload_grafik(){
  $('#inihtml').empty();
  Swal.fire({
    title: 'Memproses Data',
    html: '<p style="font-size: 14px">Mohon tunggu proses ini memerlukan waktu.</p>',
    allowOutsideClick: false,
    onBeforeOpen: () => {
      Swal.showLoading()
      Swal.getTimerLeft()
    },
  });
  grafik(dataini[0][0],dataini[1][0],dataini[2][0],dataini[3][0],dataini[0].length,1);
}

function grafik(inidata,id,lebar,dtrow,count,ul){
  if(ul <= count){
    var datperiode = $('#inputperiode').val();
    var data_json = {};
    if($('#optiongrow').is(':checked')) {
      if (datperiode == '' || datperiode < 0) {
        swal.fire({
          title: "Peringatan!",
          html : '<p style="font-size: 14px">Data Periode Salah! Mohon set ulang</p>',
          type : "warning",
        });
        return;
      }
      if (parseInt($('[name="growval"]').val()) > parseInt($('[name="growval2"]').val())) {
        swal.fire({
          title: "Peringatan!",
          html : '<p style="font-size: 14px">Data Grow Day Salah! Mohon set ulang</p>',
          type : "warning",
        });
        return;
      }
      data_json = {
        'radio' : 'grow',
        'growval' : $('[name="growval"]').val(),
        'growval2' : $('[name="growval2"]').val(),
        'periode' : $('#inputperiode').val(),
      };
    }
      data_json['inidata'] = inidata;

    $.ajax({
      type: "POST",
      url : "<?php echo base_url('admin/openhouse/grafik/'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){

          if($('#inihtmlbfr'+dtrow).attr('create') != 'true'){
            $('<div>')
            .attr({
              'class' : 'col-sm-12',
            })
            .html('<div class="row" id="inihtmlbfr'+dtrow+'" create="true"></div>')
            .appendTo('#inihtml');
          }

            $('<div>')
            .attr({
              'class' : 'col-sm-'+lebar
            })
            .html('<div class="box box-success"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik'+id+'"><span style="color: #aaa;">-Set Options Terlebih Dahulu-</span></h3></div><div class="box-body"><div id="inicanvas'+id+'"></div></div></div>')
            .appendTo('#inihtmlbfr'+dtrow);

            $('#inicanvas'+id).empty();
            $('<canvas>')
            .attr({'id' : 'chartcanvas'+id})
            .appendTo('#inicanvas'+id);

          var lineChartData = {};
          lineChartData['labels'] = data.labelgf;
          lineChartData['datasets'] = [{
                  label: data.linelabel[0],
                  borderColor: window.chartColors.blue,
                  backgroundColor: window.chartColors.blue,
                  fill: false,
                  data: data.data[0],
                  spanGaps: true,
                  }];

          var datascales = {};
          datascales['yAxes'] = {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  },
                  scaleLabel: {
                       display: true,
                       labelString: 'Moola',
                       fontSize: 20 
                    }
              }] 
          };

          var canvas = document.getElementById('chartcanvas'+id)
          var ctx = canvas.getContext('2d');
          ctx.clearRect(0,0,canvas.width,canvas.height);

          window.myLine = new Chart(ctx, {
            type: 'line',
            data: lineChartData,
            options: {
              responsive: true,
              hoverMode: 'index',
              stacked: true,
              title: {
                display: true,
                text: data.glabel
              },
            scales: datascales
            }
          });

          $('#titlegrafik'+id).html(data.glabel);
          var cr = ul;
          ul = ul + 1;
          grafik(dataini[0][cr],dataini[1][cr],dataini[2][cr],dataini[3][cr],dataini[0].length,ul);
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
  }else{
    swal.close();
    loadtabel();
  }
}

function loadtabel() {
  $('#tglresponse').empty();
  $('<table>')
  .attr({
    'class' : 'table table-striped- table-bordered table-hover table-checkable',
    'id' : 'mytable',
    'style' : 'min-width: 500px',
  })
  .appendTo('#tglresponse');

  if($('#optiongrow').is(':checked')) {
    data_json = {
      'radio' : 'grow',
      'kateg' : 'press',
      'growval' : $('[name="growval"]').val(),
      'growval2' : $('[name="growval2"]').val(),
      'periode' : $('#inputperiode').val(),
    };
  }
  data_json['inidata'] = dataini[0];
 
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('admin/openhouse/datatable'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){
          $('#mytable').DataTable( {
              data: data.dataSet,
              columns: [
                {
                    title: "NO",
                    seacrhable: false,
                    orderable: false
                },
                {
                    title: "GROW DAY",
                    orderable: false
                },
                {
                    title: "TANGGAL",
                    orderable: false
                },
                {
                    title: "JAM",
                    orderable: false
                },
                {
                    title: "STATIC PRESSURE",
                    orderable: false
                }
              ]
          });
        }
      }
    });
}
