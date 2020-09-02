<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var dataini = {
  0 : ['3142'],
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
    title: 'Processing data',
    html: '<p style="font-size: 14px">Please Wait . . .</p>',
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
          title: "Warning!",
          html : '<p style="font-size: 14px">Periode is incorrect!</p>',
          type : "warning",
        });
        return;
      }
      if (parseInt($('[name="growval"]').val()) > parseInt($('[name="growval2"]').val())) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Growday is incorrect!</p>',
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

      var rangegd =  $('[name="growval2"]').val() - parseInt($('[name="growval"]').val());
      var tinggigk = 500;
      var lebargk = 800;
      var tottinggi = (tinggigk + rangegd);
      if (parseInt($('[name="growval"]').val()) == parseInt($('[name="growval2"]').val())) {
      var totlebar = (lebargk + rangegd);
      }else{
        var totlebar = (lebargk + rangegd)*2;
      }

    $.ajax({
      type: "POST",
      url : "<?php echo base_url('history_house/grafik/'); ?>",
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
            .html('<div class="box box-success" id="gf'+ul+'" style="padding: 10px;"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik'+id+'"><span style="color: #aaa;">-Set Options-</span></h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onclick="swipegf('+id+')"><i class="fa fa-clone"></i></button></div></div><div class="box-body" style="overflow-x: auto;"><div id="inicanvas'+id+'" style="min-height: '+tottinggi+'px;min-width: '+totlebar+'px;"></div></div></div>')
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
              maintainAspectRatio: false,
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
          $('#inicanvas').html('-Data Not Found-');
          $('#tglresponse').empty();
          swal.fire({
            title: "Error!",
            html : data.message,
            type : "warning",
          });
        }
      }
    });
  }else{
    loadtabel();
    swal.close();
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
      'kateg' : 'hum',
      'growval' : $('[name="growval"]').val(),
      'growval2' : $('[name="growval2"]').val(),
      'periode' : $('#inputperiode').val(),
    };
  }
  data_json['inidata'] = dataini[0];
 
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('history_house/datatable'); ?>",
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
                    title: "HUMIDITY (%)",
                    orderable: false
                }
              ]
          });
        }
      }
    });
}
