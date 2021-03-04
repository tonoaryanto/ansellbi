<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

var urlimage1 = {};

$(document).ready(function(){
  $('#btnprint').on('click',(function(e){

    e.preventDefault();

  var datkandang = $('[name="val_kandang"]').val();
  var datperiode = $('[name="val_periode"]').val();
  var datgraf = $('#optionselect').val();

  if (datkandang == '' || datkandang == null || datkandang == undefined) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data house is empty!</p>',
      type : "warning",
    });
    return;
  }
  if (datgraf == '' || datgraf == null || datgraf == undefined) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data parameter  is empty!</p>',
      type : "warning",
    });
    return;
  }
  if (datperiode == '' || datperiode < 1) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data Flock is empty</p>',
      type : "warning",
    });
    return;
  }
  if (parseInt($('[name="hourdari1"]').val()) > parseInt($('[name="hourdari2"]').val())) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data Grow is incorrect</p>',
      type : "warning",
    });
    return;
  }

  var isiperbandingan = $('#boxpembanding').attr('data-val');
  var inijson = {
      'value1' : 'HOUR_1',
      'value2' : datgraf,
      'value3' : datkandang,
      'value61' : $('[name="hourdari1"]').val(),
      'value62' : $('[name="hourdari2"]').val(),
      'value7' : datperiode,
      'valnomor' : isiperbandingan
    };

  var isijson = {};
  if (isiperbandingan > 0) {
    for (var i = 1; i < (parseInt(isiperbandingan) + 1); i++) {
      var nomor = 7 + i;

      if ($('[name="val_pemkandang'+i+'"]').val() == '' || $('[name="val_pemkandang'+i+'"]').val() == null || $('[name="val_pemkandang'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Data House '+i+' is empty!</p>',
          type : "warning",
        });
        return;
      }
      if ($('[name="val_pemperiode'+i+'"]').val() == '' || $('[name="val_pemperiode'+i+'"]').val() == null || $('[name="val_pemperiode'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Data FLock '+i+' is empty!</p>',
          type : "warning",
        });
        return;
      }

      isijson['valkandang' + nomor] = $('[name="val_pemkandang'+i+'"]').val();
      isijson['valperiode' + nomor] = $('[name="val_pemperiode'+i+'"]').val();
    }
  }
  inijson['valpem'] = isijson;

  Swal.fire({
    title: 'Processing data',
    html: '<p style="font-size: 14px">Please Wait . . .</p>',
    allowOutsideClick: false,
    onBeforeOpen: () => {
      Swal.showLoading()
      Swal.getTimerLeft()
    },
  });

  $('#btnprint').attr('disabled','true');

    var dt = {};
    Swal.fire({
      title: 'Processing data',
      html: '<p style="font-size: 14px">Please wait . . .</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });
  loopprint(inijson,1,datgraf.length,dt);

  }));

  selectdata_kandang();
  selectdata();
});

function loopprint(dataini,awal,loop,dataurl) {
  if(awal <= loop){
    url = awal - 1;
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('export_excel/reportmtd_history_house_hour/');?>"+url,
      data : dataini,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){
          $('#btnprint').removeAttr('disabled');

          dataurl[url] = data.url;
          awal = awal + 1;
          loopprint(dataini,awal,loop,dataurl);
        }else{
          swal.fire({
            title: "Error!",
            html : data.message,
            type : "warning",
          });
        }
      }
    });
  }else{
    seturl = '' ;
    for (var i = 0; i < (awal - 1); i++) {
      seturl += '<br><div><a style="font-size: 14px" href="' + dataurl[i] + '">' + dataurl[i].split("/").pop() + '</a></div>';
    }
    swal.fire({
      title: "Save File",
      html : '<p style="font-size: 14px">Please click button file to download</p>'+seturl,
      type : "success",
      allowOutsideClick: false,
    });
  }
}

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
    //selectdata();
  });
}

function grafik(){
  var datkandang = $('[name="val_kandang"]').val();
  var datperiode = $('[name="val_periode"]').val();
  var datgraf = $('#optionselect').val();

  $('#inihtml').empty();
  $('#btnprintpdf').hide();

  if (datkandang == '' || datkandang == null || datkandang == undefined) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data House is empty!</p>',
      type : "warning",
    });
    return;
  }
  if (datgraf == '' || datgraf == null || datgraf == undefined) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data parameter is empty!</p>',
      type : "warning",
    });
    return;
  }
  if (datperiode == '' || datperiode < 1) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data Flock is empty</p>',
      type : "warning",
    });
    return;
  }
  if (parseInt($('[name="hourdari1"]').val()) > parseInt($('[name="hourdari2"]').val())) {
    swal.fire({
      title: "Warning!",
      html : '<p style="font-size: 14px">Data Grow day is wrong</p>',
      type : "warning",
    });
    return;
  }

  var isiperbandingan = $('#boxpembanding').attr('data-val');
  var inijson = {
      'value1' : 'HOUR_1',
      'value2' : datgraf,
      'value3' : datkandang,
      'value61' : $('[name="hourdari1"]').val(),
      'value62' : $('[name="hourdari2"]').val(),
      'value7' : datperiode,
      'valnomor' : isiperbandingan
    };

  var isijson = {};
  if (isiperbandingan > 0) {
    for (var i = 1; i < (parseInt(isiperbandingan) + 1); i++) {
      var nomor = 7 + i;

      if ($('[name="val_pemkandang'+i+'"]').val() == '' || $('[name="val_pemkandang'+i+'"]').val() == null || $('[name="val_pemkandang'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Data house '+i+' is empty!</p>',
          type : "warning",
        });
        return;
      }
      if ($('[name="val_pemperiode'+i+'"]').val() == '' || $('[name="val_pemperiode'+i+'"]').val() == null || $('[name="val_pemperiode'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Warning!",
          html : '<p style="font-size: 14px">Data Flock '+i+' is empty!</p>',
          type : "warning",
        });
        return;
      }

      isijson['valkandang' + nomor] = $('[name="val_pemkandang'+i+'"]').val();
      isijson['valperiode' + nomor] = $('[name="val_pemperiode'+i+'"]').val();
    }
  }
  inijson['valpem'] = isijson;

  Swal.fire({
    title: 'Processing data',
    html: '<p style="font-size: 14px">Please Wait . . .</p>',
    allowOutsideClick: false,
    onBeforeOpen: () => {
      Swal.showLoading()
      Swal.getTimerLeft()
    },
  });

  loopgrafik(inijson,1,datgraf.length);
}

//register custome positioner
Chart.Tooltip.positioners.custom = function(elements, position) {
    if (!elements.length) {
      return false;
    }
    var offset = 0;
    //adjust the offset left or right depending on the event position
    if (elements[0]._chart.width / 2 > position.x) {
      offset = 20;
    } else {
      offset = -20;
    }
    return {
      x: position.x,
      y: 70
    }
  }
  //Individual chart config

function loopgrafik(dataini,awal,loop) {
  if(awal <= loop){
    url = awal - 1;
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/datahh/'); ?>"+url,
      data : dataini,
      dataType : "JSON",
      success : function(isi){
        get_sess(isi.sess);
        if(isi.status == true){

      var rangegd =  parseInt($('[name="hourdari2"]').val()) - parseInt($('[name="hourdari1"]').val());
      var tinggigk = 500;
      var lebargk = 800;
      var tottinggi = (tinggigk + rangegd);
      if (parseInt($('[name="hourdari1"]').val()) == parseInt($('[name="hourdari2"]').val())) {
      var totlebar = (lebargk + rangegd);
      }else{
        var totlebar = (lebargk + rangegd);
      }

      var htm1 = "";

      htm1 += '<div class="nav-tabs-custom">';
      htm1 +=   '<ul class="nav nav-tabs pull-right">';
      htm1 +=     '<li id="tabtable'+awal+'" class=""><a href="#data-table'+awal+'" data-toggle="tab">Table</a></li>';
      htm1 +=     '<li id="tabchart'+awal+'" class="active"><a href="#data-chart'+awal+'" data-toggle="tab">Chart</a></li>';
      htm1 +=     '<li class="pull-left header"><i class="fa fa-inbox"></i> <span id="titlegrafik'+awal+'"></span></li>';            
      htm1 +=   '</ul>';
      htm1 +=   '<div class="tab-content no-padding">';
			htm1 +=     '<div class="tab-pane active" id="data-chart'+awal+'">';
      htm1 +=       '<div class="box-body" style="overflow-x: auto;">';
      htm1 +=         '<div id="inicanvas'+awal+'" style="min-height: '+tottinggi+'px;min-width: '+totlebar+'px;">';
      htm1 +=         '</div>';
      htm1 +=       '</div>';
      htm1 +=     '</div>';
      htm1 +=     '<div class="tab-pane" id="data-table'+awal+'">';
      htm1 +=       '<div class="row" style="padding: 15px;">';
      htm1 +=         '<div class="col-sm-12">';
      htm1 +=           '<div id="tglresponse'+awal+'" class="table-responsive"></div>';
      htm1 +=         '</div>';
      htm1 +=       '</div>';
      htm1 +=     '</div>';
      htm1 +=   '</div>';
      htm1 += '</div>';

          $('<div>')
          .attr('class','col-sm-12')
          .html(htm1)
          .appendTo('#inihtml');

          $('#inicanvas'+awal).empty();
          $('<canvas>')
          .attr({'id' : 'chartcanvas'+awal})
          .appendTo('#inicanvas'+awal);

          var lineChartData = {};
          lineChartData['labels'] = isi.labelgf;
          lineChartData['datasets'] = [{
                  label: isi.linelabel[0],
                  borderColor: window.chartColors.blue,
                  backgroundColor: window.chartColors.blue,
                  fill: false,
                  data: isi.data,
                  spanGaps: true,
                  }];

          var data_color = [
            window.chartColors.orange,
            window.chartColors.green,
            window.chartColors.red,
            '#603ec7',
            '#32a852',
            '#6e1800',
            '#9e7e2e',
            '#4454bd',
            '#c73eb2',
            '#6e2d3e',
            '#6e8bc4',
            '#0048BA',
            '#7CB9E8',
            '#C0E8D5',
            '#B284BE',
            '#72A0C1',
            '#FFBF00',
            '#FF9966',
            '#C46210',
            '#DA1884',
            '#3D2B1F',
            '#0D98BA',
            '#DFFF00',
            '#58427C',
            '#F56FA1',
            '#666699',
            '#DE5285',
            '#A67B5B',
            '#014421',
            '#801818',
            '#6C3082',
            '#1034A6',
            '#4A646C',
            '#C95A49',
            '#5F9EA0',
            ];

          if(isi.countsecond > 0){
            for (var i = 0; i < isi.countsecond; i++) {
              var adddt = {
                  label: isi.linelabel[i+1],
                  borderColor: data_color[i],
                  backgroundColor: data_color[i],
                  fill: false,
                  data: isi.datasecond[i],
                  spanGaps: false,
                  };
              lineChartData['datasets'].push(adddt);
            }
          }
          
          var ticksy1 = isi.sizeyaxis1;
          var canvas = document.getElementById('chartcanvas'+awal)
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
                position: 'custom',
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
          $('#titlegrafik'+awal).html(isi.glabel);
          $('[name="hourdari1"]').val(isi.hourdari1);
          $('[name="hourdari2"]').val(isi.hourdari2);
          $('#tglresponse').removeAttr('style');
          $('#btnprintpdf').show();
          tabel(awal,isi.linelabel,isi.tdtbl,isi.glabel);
          awal = awal + 1;
          loopgrafik(dataini,awal,loop);
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
  }else{
    swal.fire({
      title: "Finish!",
      html : '<p style="font-size: 14px">Chart has been created</p>',
      type : "success",
    });
  }
}

function tabel(awal,nama,data,title){
  var titleprint = title;
  var thm = '';

  thm +=  '<thead>';
  thm +=  '<tr>';
  thm +=    '<th>';
  thm += 'No';
  thm += '</th><th>';
  thm += 'Grow day';
  thm += '</th><th>';
  thm += 'Time';

  for (var i = 0; i < nama.length; i++) {
  thm += '</th><th>';
  thm += nama[i];
  }

  thm += '</th>';
  thm += '</tr>';
  thm += '</thead>';
  thm += '<tbody>';

  for (var i = 0; i < data.length; i++) {
  thm += '<tr>';
  thm += '<td>';
  thm += (i+1);
  thm += '</td><td>';
  thm += data[i].growday;
  thm += '</td><td>';
  thm += data[i].time;

  for (var k = 0; k < nama.length; k++) {
  thm += '</td><td>';
  thm += data[i].data[k];
  }

  thm += '</td>';
  thm += '</tr>';
  }

  thm += '</tbody>';

  $("<table>")
    .attr({
      'class' : 'table table-striped- table-bordered table-hover table-checkable',
      'id' : 'mytable'+awal,
    })
    .html(thm)
    .appendTo('#tglresponse'+awal);

  $("#mytable"+awal).DataTable({
    dom: 'Bfrtip',
    buttons : [
      {
            title : 'Multiple Data ' + titleprint,
            extend: 'pdfHtml5',
            orientation: 'landscape',
            pageSize: 'A4',
            filename: 'Multiple Data ' + titleprint,
            attr:  {
              id: 'btnpdf'+awal
            },
            customize: function(doc) {
                  var dataimage = [];
                  dataimage[0] = {image: urlimage1[(awal - 1)],margin: [ 0, 20, 0, 0 ],alignment: 'center',width:780,height:385};

                  doc.styles.tableBodyEven.alignment = 'center';
                  doc.styles.tableBodyOdd.alignment = 'center';
                  doc.styles.tableHeader.alignment = 'center';
                  doc.styles.tableHeader.fillColor = '#fff';
                  doc.styles.tableHeader.color = '#000';
                  doc.styles.tableHeader.width = 200;
                  doc.styles.tableBodyEven.fillColor = '#fff';
                  doc.styles.tableBodyOdd.fillColor = '#ddeeff';
                  doc.content.push(dataimage);
                  var objLayout = {};
                  objLayout['hLineWidth'] = function(i) { return 1; };
                  objLayout['vLineWidth'] = function(i) { return 1; };
                  objLayout['hLineColor'] = function(i) { return '#555'; };
                  objLayout['vLineColor'] = function(i) { return '#555'; };
                  objLayout['paddingLeft'] = function(i) { return 4; };
                  objLayout['paddingRight'] = function(i) { return 4; };
                  doc.content[1].layout = objLayout;
                  var widthtb = [];

                  if(nama.length <= 2){
                  widthtb[0] = '5%';
                  widthtb[1] = '15%';
                  widthtb[2] = '20%';
                  var wdl = 60;
                  }else{
                  widthtb[0] = '5%';
                  widthtb[1] = '7%';
                  widthtb[2] = '8%';
                  var wdl = 80;
                  }

                  var wd = wdl / nama.length;
                  for (var i = 0; i < nama.length; i++) {
                  widthtb[(3+i)] = wd+'%';
                  }

                  doc.content[1].table.widths = widthtb;
            }
      }
    ]
  });
}

function hapuspembanding() {
var isibox = $('#boxpembanding').attr('data-val');
$('#bodypem'+isibox).remove();
isibox = parseInt(isibox) - 1;
if (isibox == 0) {
$('#btnhapuspem').remove();
}
$('#boxpembanding').attr('data-val',isibox);
}

function addpembanding() {
  var isibox = $('#boxpembanding').attr('data-val');
  if(isibox == 16){return;}
  isibox = parseInt(isibox) + 1;
  $('#boxpembanding').attr('data-val',isibox);

  if(isibox == 1){
    $('<button>')
    .attr({'class' : 'btn btn-danger', 'onclick' : 'hapuspembanding();' , 'id' : 'btnhapuspem', 'title' : 'Delete comparison'})
    .html('<i class="fa fa-minus"></i>')
    .appendTo('#actionpem');
  }
  $('<div>')
    .attr({'class' : 'col-sm-6','id' : 'bodypem'+ isibox})
    .html('<label>Data comparison '+isibox+'</label><div style="padding:10px;border-style: solid;border-width: thin;border-radius: 5px;border-color:#ccc;margin-bottom:10px;"><div class="row"><div class="form-group col-sm-4"><label>Data house</label><select name="val_pemkandang'+isibox+'" class="form-control" id="optionselect_kandang" style="width: 100%"><option disabled selected>-Select Data house-</option></select></div><div class="form-group col-sm-4"><label>Flock</label><input name="val_pemperiode'+isibox+'" class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Input Flock-"></div></div></div>')
    .appendTo('#boxpembanding');

  $('[name="val_pemkandang'+isibox+'"]').empty();
  $('[name="val_pemkandang'+isibox+'"]')
  .val('')
  .select2({
    placeholder : '-Select House-',
    allowClear : true,
    data : dtkandang,
  });
}

function selectdata(){
  $('[id="thisbtndata"]').html('Processing . . .');
  $('#optionselect').empty();
  $('<option>')
  .attr({'selected' : 'true','disabled' : 'true'})
  .text('Processing . . .')
  .appendTo('#optionselect');

  var inidata = $.ajax({
    type: "POST",
    url : "<?php echo base_url('report/data_select'); ?>",
    data : {
      'value1' : 'HOUR_1',
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
  $('[id="thisbtndata"]').html('-Select Data-');
  $('#optionselect').empty();
  $('#optionselect')
  .val('')
  .select2({
    allowClear : true,
    placeholder : '-Select Data-',
    data : inidata,
  }).on("change", function () {
    $('[name="hourdari1"]').val('-1');
    $('[name="hourdari2"]').val('-1');
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
  var datgraf = $('#optionselect').val();

  for (var i = 0; i < datgraf.length; i++) {
    urlimage1[i] = document.getElementById("chartcanvas"+(i+1)).toDataURL("image/png");
  }

  for (var i = 0; i < datgraf.length; i++) {
  document.getElementById("btnpdf"+(i+1)).click();
  }
}
