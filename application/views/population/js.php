<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

var urlimage1;

$(document).ready(function(){
    $('.select2').select2();
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
    $('#boxteks').hide();
    $('#boxdate').show();
    $('#boxdate2').show();
    $('[id="boxperiod"]').show();
    $('[id="boxtabel"]').show();
    dtdate();
  });
}

function changetgl(dt){
  data_json = {
    'tgl' : $('[name="tgl'+dt+'"]').val(),
    'kandang' : $('#optionselect_kandang').val(),
    'periode' : $('[name="periode"]').val(),
  };

  $.ajax({
      type: "POST",
      url : "<?php echo base_url('population/changetgl'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){
          $('[name="tanggal_'+dt+'"]').val(isi.dataset);
        }
      }
  });
}

function changegrow(dt){
  data_json = {
    'grow' : $('[name="tanggal_'+dt+'"]').val(),
    'kandang' : $('#optionselect_kandang').val(),
    'periode' : $('[name="periode"]').val(),
  };

  $.ajax({
      type: "POST",
      url : "<?php echo base_url('population/changegrow'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){
          $('[name="tgl'+dt+'"]').val(isi.dataset);
        }
      }
  });
}

function dtdate(){
  data_json = {
    'kandang' : $('#optionselect_kandang').val(),
  };

  $.ajax({
    type: "POST",
    url : "<?php echo base_url('population/dtdate'); ?>",
    data : data_json,
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      var isi = data.dataset;
      $('[name="periode"]').val(isi.periode);
      $('[name="tgl1"]').val(isi.tgl1);
      $('[name="tgl2"]').val(isi.tgl2);
      $('[name="tanggal_1"]').val(isi.tanggal_dari);
      $('[name="tanggal_2"]').val(isi.tanggal_sampai);
      grafik();
    }
  });
}

function grafik(){
  if ($('#optionselect_kandang').val() == '') {return;}
  if ($('[name="tanggal_1"]').val() > $('[name="tanggal_2"]').val()) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Date is incorrect!</p>',
          type : "warning",
        });
        return;
      }

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

    var id = 1;
    var lebar = 12;
    var dtrow = 1;
    var datperiode = $('#inputperiode').val();
    var data_json = {};

      data_json = {
        'kandang' : $('#optionselect_kandang').val(),
        'periode' : $('[name="periode"]').val(),
        'growval' : $('[name="tanggal_1"]').val(),
        'growval2' : $('[name="tanggal_2"]').val(),
        'inidata' : {'0':'afterpopulation','1':'mortality','2':'selection'}
      };

      var tottinggi = 500;
      var totlebar = 800;

      $.ajax({
      type: "POST",
      url : "<?php echo base_url('population/grafik/'); ?>",
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
            .html('<div class="box box-success" id="gf'+1+'" style="padding: 10px;"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik'+id+'"><span style="color: #aaa;">-Set Options-</span></h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" onclick="swipegf('+id+')"><i class="fa fa-clone"></i></button></div></div><div class="box-body" style="overflow-x: auto;"><div id="inicanvas'+id+'" style="min-height: '+tottinggi+'px;min-width: '+totlebar+'px;"></div></div></div>')
            .appendTo('#inihtmlbfr'+dtrow);

            $('#inicanvas'+id).empty();
            $('<canvas>')
            .attr({'id' : 'chartcanvas'+id})
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

          var adddt1 = {
            label: isi.linelabel[1],
            borderColor: window.chartColors.orange,
            backgroundColor: window.chartColors.orange,
            fill: false,
            data: isi.data[1],
            spanGaps: false,
            };
          lineChartData['datasets'].push(adddt1);

          var adddt2 = {
            label: isi.linelabel[2],
            borderColor: window.chartColors.red,
            backgroundColor: window.chartColors.red,
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

          swal.close();
          $('#titlegrafik'+id).html(isi.glabel);
          loadtabel();
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

  data_json = {
      'kandang' : $('#optionselect_kandang').val(),
      'periode' : $('[name="periode"]').val(),
      'growval' : $('[name="tanggal_1"]').val(),
      'growval2' : $('[name="tanggal_2"]').val(),
      'inidata' : {'0':'afterpopulation','1':'mortality','2':'selection'}
  };
 
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('population/datatable'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){
          $('#mytable').DataTable( {
              dom: 'Bfrtip',
              buttons : [
                {
                      title : 'Data Mortality',
                      extend: 'pdfHtml5',
                      orientation: 'landscape',
                      pageSize: 'A4',
                      filename: 'Data Mortality',
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
                            doc.content[1].table.widths = [ '5%', '20%', '15%', '20%', '20%', '20%'];
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
                    title: "DATE",
                    orderable: false
                },
                {
                    title: "GROW DAY",
                    orderable: false
                },
                {
                    title: "POPULATION",
                    orderable: false
                },
                {
                    title: "MORTALITY (CUMULATIVE)",
                    orderable: false
                },
                {
                    title: "DEATH (CUMULATIVE)",
                    orderable: false
                },
                {
                    title: "CULLING (CUMULATIVE)",
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
  document.getElementById("btnpdf").click();
}
