<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var urlimage1;

var dataini = {
  0 : [{
    0 :'req_windspeed',
    1 :'windspeed'
  }],
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
  grafik(dataini[0],dataini[1][0],dataini[2][0],dataini[3][0],dataini[0].length,1);
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
        var totlebar = (lebargk + rangegd);
      }

    $.ajax({
      type: "POST",
      url : "<?php echo base_url('history_house/grafikwp/'); ?>",
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
            .attr({'id' : 'chartcanvas'+id})
            .appendTo('#inicanvas'+id);

          var data_color = [window.chartColors.orange, window.chartColors.green, window.chartColors.red, window.chartColors.purple];

          var lineChartData = {};
          lineChartData['labels'] = isi.labelgf;
          lineChartData['datasets'] = [{
                  label: isi.linelabel[0],
                  borderColor: data_color[1],
                  backgroundColor: data_color[1],
                  fill: false,
                  data: isi.data[0],
                  spanGaps: true,
                  }];

          var adddt = {
              label: isi.linelabel[1],
              borderColor: data_color[2],
              backgroundColor: data_color[2],
              fill: false,
              data: isi.data[1],
              spanGaps: false,
              };
          lineChartData['datasets'].push(adddt);

          var adddt2 = {
              label: isi.linelabel[2],
              borderColor: data_color[3],
              backgroundColor: data_color[3],
              fill: false,
              data: isi.data[2],
              spanGaps: false,
              };
          lineChartData['datasets'].push(adddt2);

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
                  gridLines: { color: '#888' },
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
          grafik(dataini[0],dataini[1][cr],dataini[2][cr],dataini[3][cr],dataini[0].length,ul);
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
      'kateg' : 'wind',
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
              dom: 'Bfrtip',
              buttons : [
                {
                      title : 'Data Wind Speed',
                      extend: 'pdfHtml5',
                      orientation: 'landscape',
                      pageSize: 'A4',
                      filename: 'Data Wind Speed',
                      attr:  {
                        id: 'btnpdf'
                      },
                      customize: function(doc) {
                            doc.styles.tableBodyEven.alignment = 'center';
                            doc.styles.tableBodyOdd.alignment = 'center';
                            doc.styles.tableHeader.alignment = 'center';
                            doc.styles.tableHeader.fillColor = '#fff';
                            doc.styles.tableHeader.color = '#000';
                            doc.styles.tableHeader.width = 200;
                            doc.styles.tableBodyEven.fillColor = '#fff';
                            doc.styles.tableBodyOdd.fillColor = '#ddeeff';
                            doc.content.push( 
                              {image: urlimage1,margin: [ 10, 20, 0, 12 ],alignment: 'center',width:780,height:385}
                              );
                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) { return 1; };
                            objLayout['vLineWidth'] = function(i) { return 1; };
                            objLayout['hLineColor'] = function(i) { return '#555'; };
                            objLayout['vLineColor'] = function(i) { return '#555'; };
                            objLayout['paddingLeft'] = function(i) { return 4; };
                            objLayout['paddingRight'] = function(i) { return 4; };
                            doc.content[1].layout = objLayout;
                            doc.content[1].table.widths = [ '4%', '11%', '11%', '11%', '21%', '21%', '21%'];
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
                    title: "REQUIRED WIND SPEED (ms)",
                    orderable: false
                },
                {
                    title: "WIND SPEED (ms)",
                    orderable: false
                },
                {
                    title: "STANDARD VALUE",
                    orderable: false
                }
              ]
          });
        }
      }
    });
}

function allprint(){
  urlimage1 = document.getElementById("chartcanvas4").toDataURL("image/png");
  document.getElementById("btnpdf").click();
}