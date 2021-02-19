<?php defined('BASEPATH') OR exit('No direct script access allowed');
$listdata = $this->konfigurasi->listdata();
?>
var urlimage1;
var kolomkiri = 'LEFT Y-AXIS DATA';
var kolomkanan = 'RIGHT Y-AXIS DATA';
var titleprint;
var kategrafik = 'HOUR_1';

var listdata = <?php  echo json_encode($listdata);?>;

$(document).ready(function(){
  $('.select2').select2();
  selectdata(1);
  selectdata(2);
  loadtabel();
  $('#btnprint').on('click',(function(e){

    e.preventDefault();

    if (cekgrafik() == 1) {return;}

    $('#btnprint').attr('disabled','true');

    var inijson = {
      'data1kandang' : $('[name="val_kandang1"]').val(),
      'data1periode' : $('[name="val_periode1"]').val(),
      'data1data'    : $('[name="val_data1"]').val(),
      'data1posisi'  : $('[name="posisiy1"]').val(),
      'data2kandang' : $('[name="val_kandang1"]').val(),
      'data2periode' : $('[name="val_periode1"]').val(),
      'data2data'    : $('[name="val_data2"]').val(),
      'data2posisi'  : $('[name="posisiy2"]').val(),
      'value61'      : $('[name="tanggal_1"]').val(),
      'value62'      : $('[name="tanggal_2"]').val(),
      'namagrafik'   : $('[name="namagrafik"]').val()
    };

    Swal.fire({
      title: 'Processing data',
      html: '<p style="font-size: 14px">Please Wait . . .</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });

    $.ajax({
        url : "<?php echo base_url('export_excel/ydhistory_house_hour');?>",
        type: "POST",
        data: inijson,
        dataType:"JSON",
        success: function(data){
          get_sess(data.sess);
          if(data.status == true){
            swal.fire({
              title: "Save File",
              html : '<p style="font-size: 14px">Please click button Save file</p>',
            });
            $('#btnprint').removeAttr('disabled');
            location.replace(data.url);
          }else{
            $('#tombol').removeAttr('disabled');
            swal.fire({
              title: "Error!",
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
            title: "Error!",
            html : '<p style="font-size: 14px">Terjadi Kesalahan!</p>',
            type: "error",
          });
        }
    });
  }));

  selectdata_kandang();

  $('.select2posisi').select2({
  placeholder : 'Select Y axis position',
  allowClear : true
  });
});

function getflock(){
  if($('[name="kandang"]').val() == ''){return;}
  $('[name="periode"]').val('');

  $.ajax({
    url : '<?php echo base_url('report/getflock'); ?>',
    type: "POST",
    data: {
      'kandang' : $('[name="val_kandang1"]').val()
      },
    dataType: "JSON",
    success: function(data)
    {
      get_sess(data.sess);
      $('[name="val_periode1"]').val(data.periode);
      $('[name="tanggal_1"]').val('');
      $('[name="tanggal_2"]').val('');
      $('[name="tgl1"]').val('');
      $('[name="tgl2"]').val('');
      $('[name="time1"]').val('00:00');
      $('[name="time2"]').val('00:00');
      $('[id^=select2-time1]')
      .attr('title','00:00')
      .text('00:00');
      $('[id^=select2-time2]')
      .attr('title','00:00')
      .text('00:00');
    }
  });
}

function changetgl(dt){
  data_json = {
    'dt' : dt,
    'tgl' : $('[name="tgl'+dt+'"]').val(),
    'time' : $('[name="time'+dt+'"]').val(),
    'kandang' : $('[name="val_kandang1"]').val(),
    'periode' : $('[name="val_periode1"]').val()
  };

  $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/changetgl'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){
          $('[name="time'+dt+'"]').val(isi.timeset);
          $('[id^=select2-time'+dt+']')
          .attr('title',isi.timeset)
          .text(isi.timeset);
          $('[name="tanggal_'+dt+'"]').val(isi.dataset);
        }
      }
  });
}

function changegrow(dt){
  data_json = {
    'dt' : dt,
    'grow' : $('[name="tanggal_'+dt+'"]').val(),
    'kandang' : $('[name="val_kandang1"]').val(),
    'periode' : $('[name="val_periode1"]').val()
  };

  $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/changegrow'); ?>",
      data : data_json,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){
          $('[name="tgl'+dt+'"]').val(isi.dataset);
          $('[name="time'+dt+'"]').val(isi.timeset);
          $('[id^=select2-time'+dt+']')
          .attr('title',isi.timeset)
          .text(isi.timeset);
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

  $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/datatabel_dyaxish');?>",
      data : {
        'data1kandang' : function () {return $('[name="val_kandang1"]').val();},
        'data1periode' : function () {return $('[name="val_periode1"]').val();},
        'data1data'    : function () {return $('[name="val_data1"]').val();},
        'data2kandang' : function () {return $('[name="val_kandang1"]').val();},
        'data2periode' : function () {return $('[name="val_periode1"]').val();},
        'data2data'    : function () {return $('[name="val_data2"]').val();},
        'tgl1'     : function () {return $('[name="tgl1"]').val();},
        'tgl2'     : function () {return $('[name="tgl2"]').val();},
        'time1'    : function () {return $('[name="time1"]').val();},
        'time2'    : function () {return $('[name="time2"]').val();},
        'value61'      : function () {return $('[name="tanggal_1"]').val();},
        'value62'      : function () {return $('[name="tanggal_2"]').val();},
      },
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){

          $("#mytable").DataTable({
            dom: 'Bfrtip',
            buttons : [
              {
                    title : 'Data ' + titleprint,
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    filename: 'Data ' + titleprint,
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
                            {image: urlimage1,margin: [ 0, 20, 0, 0 ],alignment: 'center',width:780,height:385}
                            );
                          var objLayout = {};
                          objLayout['hLineWidth'] = function(i) { return 1; };
                          objLayout['vLineWidth'] = function(i) { return 1; };
                          objLayout['hLineColor'] = function(i) { return '#555'; };
                          objLayout['vLineColor'] = function(i) { return '#555'; };
                          objLayout['paddingLeft'] = function(i) { return 4; };
                          objLayout['paddingRight'] = function(i) { return 4; };
                          doc.content[1].layout = objLayout;
                          doc.content[1].table.widths = [ '5%', '15%', '15%', '15%', '25%', '25%'];
                    }
              }
            ],
            data: data.dataSet,
            retrieve : true,
            processing:true,
            columns:[
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
                    title: kolomkiri,
                    orderable: false
                },
                {
                    title: kolomkanan,
                    orderable: false
                },
            ]
          });
        }
      }
    });
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
  $('.optionselect_kandang').empty();
  $('.optionselect_kandang')
  .val('')
  .select2({
    placeholder : '-Select Data House-',
    allowClear : true,
    data : inidata,
  }).on("change", function () {
    getflock();
  });
}

function cekgrafik() {
  var cek = 0;
  var title = $('[name="namagrafik"]').val();
  var data1kandang = $('[name="val_kandang1"]').val();
  var data1periode = $('[name="val_periode1"]').val();
  var data1data    = $('[name="val_data1"]').val();
  var data1posisi  = $('[name="posisiy1"]').val();
  var data2kandang = $('[name="val_kandang1"]').val();
  var data2periode = $('[name="val_periode1"]').val();
  var data2data    = $('[name="val_data2"]').val();
  var data2posisi  = $('[name="posisiy2"]').val();

  if (data2posisi == '' || data2posisi == null || data2posisi == undefined) {title = 'Data 2 input position is empty!';cek = 1;}
  if (data2data == '' || data2data == null || data2data == 'null' || data2data == undefined) {title = 'Data 2 input data parameter is empty!';cek = 1;}
  if (data2periode == '' || data2periode < 1) {title = 'Data 2 input flock is empty!';cek = 1;}
  if (data2kandang == '' || data2kandang == null || data2kandang == undefined) {title = 'Data 2 input house is empty!';cek = 1;}
  if (data1posisi == '' || data1posisi == null || data1posisi == undefined) {title = 'Data 1 input position is empty!';cek = 1;}
  if (data1data == '' || data1data == null || data1data == 'null' || data1data == undefined) {title = 'Data 1 input data parameter is empty!';cek = 1;}
  if (data1periode == '' || data1periode < 1) {title = 'Flock is empty!';cek = 1;}
  if (data1kandang == '' || data1kandang == null || data1kandang == undefined) {title = 'Data 1 input house is empty!';cek = 1;}
  if (parseInt($('[name="tanggal_1"]').val()) > parseInt($('[name="tanggal_2"]').val())) {title = 'Data Grow Day is worng!';cek = 1;}
  if (title == '') {title = 'Chart name is empty!';cek = 1;}

  if(cek == 1){
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">'+title+'</p>',
      type : "warning",
    });
    return 1;
  }else{return 0;}
}

function grafik(){
  if (cekgrafik() == 1) {return;}

  $('#inihtml').empty();
  $('#btnprintpdf').hide();
  
  Swal.fire({
    title: 'Processing data',
    html: '<p style="font-size: 14px">Please Wait . . .</p>',
    allowOutsideClick: false,
    onBeforeOpen: () => {
      Swal.showLoading()
      Swal.getTimerLeft()
    },
  });

  var inijson = {
    'data1kandang' : $('[name="val_kandang1"]').val(),
    'data1periode' : $('[name="val_periode1"]').val(),
    'data1data'    : $('[name="val_data1"]').val(),
    'data1posisi'  : $('[name="posisiy1"]').val(),
    'data2kandang' : $('[name="val_kandang1"]').val(),
    'data2periode' : $('[name="val_periode1"]').val(),
    'data2data'    : $('[name="val_data2"]').val(),
    'data2posisi'  : $('[name="posisiy2"]').val(),
    'tgl1'         : $('[name="tgl1"]').val(),
    'tgl2'         : $('[name="tgl2"]').val(),
    'time1'        : $('[name="time1"]').val(),
    'time2'        : $('[name="time2"]').val(),
    'value61'      : $('[name="tanggal_1"]').val(),
    'value62'      : $('[name="tanggal_2"]').val(),
    'namagrafik'   : $('[name="namagrafik"]').val()
  };

    $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/dataaxish'); ?>",
      data : inijson,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){

          var rangegd =  parseInt($('[name="tanggal_2"]').val()) - parseInt($('[name="tanggal_1"]').val());
          var tinggigk = 500;
          var lebargk = 800;
          var tottinggi = (tinggigk + rangegd);
          if (parseInt($('[name="tanggal_1"]').val()) == parseInt($('[name="tanggal_2"]').val())) {
          var totlebar = (lebargk + rangegd);
          }else{
            var totlebar = (lebargk + rangegd);
          }

          $('<div>')
          .html('<div class="box box-success" style="padding: 10px;"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik"><span style="color: #aaa;">-Set Options-</span></h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div></div><div class="box-body" style="overflow-x: auto;"><div id="inicanvas" style="min-height: '+tottinggi+'px;min-width: '+totlebar+'px;"></div></div></div>')
          .appendTo('#inihtml');

          $('#inicanvas').empty();
          $('<canvas>')
          .attr({'id' : 'chartcanvas'})
          .appendTo('#inicanvas');

          var data_color = [window.chartColors.orange, window.chartColors.green, window.chartColors.red, window.chartColors.purple];

          var lineChartData = {};
          lineChartData['labels'] = isi.labelgf;
          lineChartData['datasets'] = [
                  {
                    label: isi.linelabel[0],
                    borderColor: window.chartColors.blue,
                    backgroundColor: window.chartColors.blue,
                    fill: false,
                    data: isi.data[0],
                    spanGaps: true,
                    yAxisID: 'y1',
                  },
                  {
                    label: isi.linelabel[1],
                    borderColor: data_color[0],
                    backgroundColor: data_color[0],
                    fill: false,
                    data: isi.data[1],
                    spanGaps: true,
                    yAxisID: 'y2',
                  }
                ];
          var ticksy1 = isi.sizeyaxis1;
          var ticksy2 = isi.sizeyaxis2;
          var canvas = document.getElementById('chartcanvas')
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
                yAxes: [
                  {
                  position: "left",
                  id: "y1",
                  display: true,
                  gridLines: { color: '#36a2eb' },
                  ticks: {
                    autoSkip: false,
                    min: ticksy1[ticksy1.length - 1],
                    max: ticksy1[0]
                  },
                  afterBuildTicks: function(scale) {
                    scale.ticks = ticksy1;
                    return;
                  }
                  },
                  {
                  gridLines: { color: data_color[0] },
                  position: "right",
                  id: "y2",
                  display: true,
                  ticks: {
                    autoSkip: false,
                    min: ticksy2[ticksy2.length - 1],
                    max: ticksy2[0]
                  },
                  afterBuildTicks: function(scale) {
                    scale.ticks = ticksy2;
                    return;
                  }
                  }
                ]
              }
            }
          });

          swal.fire({
            title: "Finish!",
            html : '<p style="font-size: 14px">Chart has been created</p>',
            type : "success",
          });

          $('#titlegrafik').html(isi.glabel);
          $('#boxtabel').removeAttr('style');
          titleprint = $('[name="namagrafik"]').val();
          kolomkiri = listdata[$('[name="val_data1"]').val()];
          kolomkanan = listdata[$('[name="val_data2"]').val()];
          $('#myTable').DataTable().clear().destroy();
          loadtabel();
          $('#btnprintpdf').show();
         }else{
          $('#inicanvas').html('-Data not found-');
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

function selectdata(nomor){
  $('#optionselect'+nomor).empty();
  $('#optionselect'+nomor)
  .select2({
    allowClear : true,
    placeholder : 'Processing . . .',
  });
  var inidata = $.ajax({
    type: "POST",
    url : "<?php echo base_url('report/data_selectdy'); ?>",
    data : {
      'value1' : kategrafik,
      'value2' : $('#optionselect_kandang'+nomor).val(),
      'value3' : $('[name="val_periode'+nomor+'"]').val()
    },
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      isiselect(data,nomor);
    }
  });
}

function isiselect(inidata,nomor){
  $('#optionselect'+nomor).empty();
  $('#optionselect'+nomor)
  .val('')
  .select2({
    allowClear : true,
    placeholder : '-Select Data-',
    data : inidata,
  }).on("change", function () {
    $('[name="hourdari"]').val('-1');
  });
}

function btn_data() {
  var valbtn = $('#thisbtndata').attr('data-toggle');
  if(valbtn == '0'){
  $('#btndatapop').attr({'class' : 'input-group-btn open','aria-expanded' : 'true'});
  $('#thisbtndata').attr('data-toggle','1');
  }else{
  $('#thisbtndata').attr('data-toggle','0');
  $('#btndatapop').attr({'class' : 'input-group-btn', 'aria-expanded' : 'false'});
  }
}

function allprint(){
  urlimage1 = document.getElementById("chartcanvas").toDataURL("image/png");
  document.getElementById("btnpdf").click();
}