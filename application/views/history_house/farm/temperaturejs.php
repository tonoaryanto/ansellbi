<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var urlimage1, urlimage2, urlimage3, urlimage4, urlimage5, urlimage6, urlimage7;

var dataini = {
  0: function(ini=null){
    if(ini == null){
      if($('[name="order"]').val() == 1){return ['alltemp','temp_1','temp_2','temp_3','temp_4','temp_out','avg_temp'];}
      if($('[name="order"]').val() == 2){return ['temp_1','temp_2','temp_3','temp_4','alltemp','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 3){return ['temp_2','temp_1','temp_3','temp_4','alltemp','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 4){return ['temp_3','temp_1','temp_2','temp_4','alltemp','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 5){return ['temp_4','temp_1','temp_2','temp_3','alltemp','avg_temp','temp_out'];}
      if($('[name="order"]').val() == 6){return ['temp_out','temp_1','temp_2','temp_3','temp_4','alltemp','avg_temp'];}
      if($('[name="order"]').val() == 7){return ['avg_temp','temp_1','temp_2','temp_3','temp_4','temp_out','alltemp'];}
    }
    if(ini == 'table'){
      return ['avg_temp','temp_1','temp_2','temp_3','temp_4','temp_out'];
    }
  },
  1 : function(){
    if($('[name="order"]').val() == 1){return ['1','2','3','4','5','6','7'];}
    if($('[name="order"]').val() == 2){return ['2','3','4','5','1','7','6'];}
    if($('[name="order"]').val() == 3){return ['3','2','4','5','1','7','6'];}
    if($('[name="order"]').val() == 4){return ['4','2','3','5','1','7','6'];}
    if($('[name="order"]').val() == 5){return ['5','2','3','4','1','7','6'];}
    if($('[name="order"]').val() == 6){return ['6','2','3','4','5','1','7'];}
    if($('[name="order"]').val() == 7){return ['7','2','3','4','5','6','1'];}
    },
  2 : ['12','12','12','12','12','12','12'],
  3 : ['1','1','1','1','1','1','1']
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
          html : '<p style="font-size: 14px">Flock is incorrect!</p>',
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
        var totlebar = (lebargk + rangegd);
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
                  borderColor: '#ff0000',
                  backgroundColor: '#ff0000',
                  fill: false,
                  data: isi.data[0],
                  spanGaps: true,
                  }];

          var data_color = [window.chartColors.blue, window.chartColors.green, window.chartColors.purple,'#32a852',window.chartColors.orange];

          var minadddt = {
            label: isi.linelabel[1],
            borderColor: '#5B7BB4',
            backgroundColor: '#5B7BB4',
            fill: false,
            data: isi.data[1],
            spanGaps: false,
            };
          lineChartData['datasets'].push(minadddt);
          var maxadddt = {
            label: isi.linelabel[2],
            borderColor: '#800000',
            backgroundColor: '#800000',
            fill: false,
            data: isi.data[2],
            spanGaps: false,
            };
          lineChartData['datasets'].push(maxadddt);

            if(inidata == 'alltemp'){
            var adddt = {
              label: isi.linelabel[3],
              borderColor: data_color[1],
              backgroundColor: data_color[1],
              fill: false,
              data: isi.data[3],
              spanGaps: false,
              };
            lineChartData['datasets'].push(adddt);
            var adddt2 = {
              label: isi.linelabel[4],
              borderColor: data_color[2],
              backgroundColor: data_color[2],
              fill: false,
              data: isi.data[4],
              spanGaps: false,
              };
            lineChartData['datasets'].push(adddt2);
            var adddt3 = {
              label: isi.linelabel[5],
              borderColor: data_color[3],
              backgroundColor: data_color[3],
              fill: false,
              data: isi.data[5],
              spanGaps: false,
              };
            lineChartData['datasets'].push(adddt3);
            var adddt4 = {
              label: isi.linelabel[6],
              borderColor: data_color[4],
              backgroundColor: data_color[4],
              fill: false,
              data: isi.data[6],
              spanGaps: false,
              };
            lineChartData['datasets'].push(adddt4);
          }else{
            var adddt = {
              label: isi.linelabel[3],
              borderColor: data_color[1],
              backgroundColor: data_color[1],
              fill: false,
              data: isi.data[3],
              spanGaps: false,
              };
            lineChartData['datasets'].push(adddt);
          }

          var ticksy1 = isi.sizeyaxis1;
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
                    maxRotation: 120,
                    minRotation: 60,
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
                  gridLines: { color: '#333' },
                  ticks: {
                    autoSkip: false,
                    min: ticksy1[ticksy1.length - 1],
                    max: ticksy1[0]
                  },
                  afterBuildTicks: function(scale) {
                    scale.ticks = ticksy1;
                    return;
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
              dom: 'Bfrtip',
              buttons : [
                {
                      title : 'Data Temperature',
                      extend: 'pdfHtml5',
                      orientation: 'landscape',
                      pageSize: 'A4',
                      filename: 'Data Temperature',
                      attr:  {
                        id: 'btnpdf'
                      },
                      customize: function(doc) {
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';
                            doc.styles.tableHeader.fillColor = '#fff';
                            doc.styles.tableHeader.color = '#000';
                            doc.styles.tableBodyEven.fillColor = '#fff';
                            doc.styles.tableBodyOdd.fillColor = '#ddeeff';
                            doc.content.push( 
                              {image: urlimage1,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage2,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage3,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage4,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage5,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage6,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385},
                              {image: urlimage7,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385}
                              );
                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) { return 1; };
                            objLayout['vLineWidth'] = function(i) { return 1; };
                            objLayout['hLineColor'] = function(i) { return '#555'; };
                            objLayout['vLineColor'] = function(i) { return '#555'; };
                            objLayout['paddingLeft'] = function(i) { return 4; };
                            objLayout['paddingRight'] = function(i) { return 4; };
                            doc.content[1].layout = objLayout;
                      }
                }
              ],
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
                    title: "REQ TEMP",
                    orderable: false
                },
                {
                    title: "TEMP 1 (°C)",
                    orderable: false
                },
                {
                    title: "TEMP 2 (°C)",
                    orderable: false
                },
                {
                    title: "TEMP 3 (°C)",
                    orderable: false
                },
                {
                    title: "TEMP 4 (°C)",
                    orderable: false
                },
                {
                    title: "AVG TEMP (°C)",
                    orderable: false
                },
                {
                    title: "OUT TEMP (°C)",
                    orderable: false
                },
                {
                    title: "MIM STD VALUE",
                    orderable: false
                },
                {
                    title: "MAX STD VALUE",
                    orderable: false
                }
              ]
          });
        }
      }
    });
}

function allprint(){
  urlimage1 = document.getElementById("chartcanvas1").toDataURL("image/png");
  urlimage2 = document.getElementById("chartcanvas2").toDataURL("image/png");
  urlimage3 = document.getElementById("chartcanvas3").toDataURL("image/png");
  urlimage4 = document.getElementById("chartcanvas4").toDataURL("image/png");
  urlimage5 = document.getElementById("chartcanvas5").toDataURL("image/png");
  urlimage6 = document.getElementById("chartcanvas6").toDataURL("image/png");
  urlimage7 = document.getElementById("chartcanvas7").toDataURL("image/png");

  document.getElementById("btnpdf").click();
}