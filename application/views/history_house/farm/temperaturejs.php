<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var dataini = {
  0: function(ini=null){
    if(ini == null){
      if($('[name="order"]').val() == 1){return ['avg_temp','temp_1','temp_2','temp_3','temp_4','temp_out'];}
      if($('[name="order"]').val() == 2){return ['temp_1','temp_2','temp_3','temp_4','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 3){return ['temp_2','temp_1','temp_3','temp_4','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 4){return ['temp_3','temp_1','temp_2','temp_4','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 5){return ['temp_4','temp_1','temp_2','temp_3','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 6){return ['temp_out','temp_1','temp_2','temp_3','temp_4','avg_temp'];}
    }
    if(ini == 'table'){
      return ['avg_temp','temp_1','temp_2','temp_3','temp_4','temp_out'];
    }
  },
  1 : function(){
    if($('[name="order"]').val() == 1){return ['1','2','3','4','5','6'];}
    if($('[name="order"]').val() == 2){return ['2','3','4','5','1','6'];}
    if($('[name="order"]').val() == 3){return ['3','2','4','5','1','6'];}
    if($('[name="order"]').val() == 4){return ['4','2','3','5','1','6'];}
    if($('[name="order"]').val() == 5){return ['5','2','3','4','1','6'];}
    if($('[name="order"]').val() == 6){return ['6','2','3','4','5','1'];}
    },
  2 : ['12','6','6','6','6','6'],
  3 : ['1','2','2','2','2','2']
};

$(document).ready(function(){
 reload_grafik();
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
  grafik(dataini[0]()[0],dataini[1]()[0],dataini[2][0],dataini[3][0],dataini[0]().length,1);
}

function swipegf(ul){
  $('[name="order"]').val(ul);
  reload_grafik();
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

      var rangegd =  parseInt($('[name="growval2"]').val()) - parseInt($('[name="growval"]').val());
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
      url : "<?php echo base_url('history_house/grafik_temperature/'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){

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
            .attr({
              'id' : 'chartcanvas'+id
            })
            .appendTo('#inicanvas'+id);

          var lineChartData = {};
          lineChartData['labels'] = isi.labelgf;
          lineChartData['datasets'] = [{
                  label: isi.linelabel[0],
                  borderColor: window.chartColors.blue,
                  backgroundColor: window.chartColors.blue,
                  fill: false,
                  data: isi.data[0],
                  spanGaps: true,
                  }];

          var data_color = [window.chartColors.orange, window.chartColors.green, window.chartColors.red, window.chartColors.purple];

          var adddt = {
              label: isi.linelabel[1],
              borderColor: data_color[1],
              backgroundColor: data_color[1],
              fill: false,
              data: isi.data[1],
              spanGaps: false,
              };
          lineChartData['datasets'].push(adddt);

          var canvas = document.getElementById('chartcanvas'+id)
          var ctx = canvas.getContext('2d');
          ctx.clearRect(0,0,canvas.width,canvas.height);

          window.myLine = new Chart(ctx, {
            type: 'line',
            data: lineChartData,
            options: {
              responsive: true,
              maintainAspectRatio: false,
              title: {
                display: true,
                text: isi.glabel
              },
              tooltips: {
                position: 'nearest',
    						mode: 'index',
		    				intersect: false
              },
              scales: {
                xAxes: [{
                  display: true,
                  ticks: {
                    callback: function(dataLabel, index) {
                      switch (isi.difgrow) {
                        case 0:
                          return dataLabel;
                        case 1:
                          return index % 1 === 0 ? dataLabel : '';
                        case 2:
                          return index % 2 === 0 ? dataLabel : '';
                        case 3:
                          return index % 2 === 0 ? dataLabel : '';
                        case 4:
                          return index % 2 === 0 ? dataLabel : '';
                        case 5:
                          return index % 2 === 0 ? dataLabel : '';
                        case 6:
                          return index % 2 === 0 ? dataLabel : '';
                        default:
                          return index % 3 === 0 ? dataLabel : '';
                      }
                    }
                  }
                }],
                yAxes: [{
                  display: true,
                  gridLines: { color: '#888' },
                  ticks: {
                    min: 21,
                    max: 35,
                    stepSize: 1
                  }
                }]
              }
            }
          });

          $('#titlegrafik'+id).html(isi.glabel);
          var cr = ul;
          ul = ul + 1;
          grafik(dataini[0]()[cr],dataini[1]()[cr],dataini[2][cr],dataini[3][cr],dataini[0]().length,ul);
        }else{
          $('#inicanvas').html('-Data Not Found-');
          $('#tglresponse').empty();
          swal.fire({
            title: "Error!",
            html : isi.message,
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
      'kateg' : 'temp',
      'growval' : $('[name="growval"]').val(),
      'growval2' : $('[name="growval2"]').val(),
      'periode' : $('#inputperiode').val(),
    };
  }
  data_json['inidata'] = dataini[0]('table');
 
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
                    title: "DATE",
                    orderable: false
                },
                {
                    title: "TIME",
                    orderable: false
                },
                {
                    title: "REQ. TEMPERATURE",
                    orderable: false
                },
                {
                    title: "TEMPERATURE 1 (°C)",
                    orderable: false
                },
                {
                    title: "TEMPERATURE 2 (°C)",
                    orderable: false
                },
                {
                    title: "TEMPERATURE 3 (°C)",
                    orderable: false
                },
                {
                    title: "TEMPERATURE 4 (°C)",
                    orderable: false
                },
                {
                    title: "AVG. TEMPERATURE (°C)",
                    orderable: false
                },
                {
                    title: "OUT TEMPERATURE (°C)",
                    orderable: false
                }
              ]
          });
        }
      }
    });

}
