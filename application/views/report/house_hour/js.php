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
      html : '<p style="font-size: 14px">Data Period is empty</p>',
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
          html : '<p style="font-size: 14px">Data period '+i+' is empty!</p>',
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
      html : '<p style="font-size: 14px">Data Period is empty</p>',
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
          html : '<p style="font-size: 14px">Data period '+i+' is empty!</p>',
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

function loopgrafik(dataini,awal,loop) {
  if(awal <= loop){
    url = awal - 1;
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/datahh/'); ?>"+url,
      data : dataini,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){

      var rangegd =  parseInt($('[name="hourdari2"]').val()) - parseInt($('[name="hourdari1"]').val());
      var tinggigk = 500;
      var lebargk = 800;
      var tottinggi = (tinggigk + rangegd);
      if (parseInt($('[name="hourdari1"]').val()) == parseInt($('[name="hourdari2"]').val())) {
      var totlebar = (lebargk + rangegd);
      }else{
        var totlebar = (lebargk + rangegd)*2;
      }

          $('<div>')
          .attr('class','col-sm-12')
          .html('<div class="box box-success" style="padding: 10px;"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik'+awal+'"><span style="color: #aaa;">-Set Options Terlebih Dahulu-</span></h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div></div><div class="box-body" style="overflow-x: auto;"><div id="inicanvas'+awal+'" style="min-height: '+tottinggi+'px;min-width: '+totlebar+'px;"></div></div></div>')
          .appendTo('#inihtml');

          $('#inicanvas'+awal).empty();
          $('<canvas>')
          .attr({'id' : 'chartcanvas'+awal})
          .appendTo('#inicanvas'+awal);

          var lineChartData = {};
          lineChartData['labels'] = data.labelgf;
          lineChartData['datasets'] = [{
                  label: data.linelabel[0],
                  borderColor: window.chartColors.blue,
                  backgroundColor: window.chartColors.blue,
                  fill: false,
                  data: data.data,
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


          var data_color = [window.chartColors.orange, window.chartColors.green, window.chartColors.red, window.chartColors.purple];

          if(data.countsecond > 0){
            for (var i = 0; i < data.countsecond; i++) {
              var adddt = {
                  label: data.linelabel[i+1],
                  borderColor: data_color[i],
                  backgroundColor: data_color[i],
                  fill: false,
                  data: data.datasecond[i],
                  spanGaps: false,
                  };
              lineChartData['datasets'].push(adddt);
            }
          }

          var canvas = document.getElementById('chartcanvas'+awal)
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
          $('#titlegrafik'+awal).html(data.glabel);
          $('[name="hourdari1"]').val(data.hourdari1);
          $('[name="hourdari2"]').val(data.hourdari2);
          $('#tglresponse').removeAttr('style');
          awal = awal + 1;
          loopgrafik(dataini,awal,loop);
        }else{
          $('#inicanvas').html('-Data not found-');
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
    swal.fire({
      title: "Finish!",
      html : '<p style="font-size: 14px">Chart has been created</p>',
      type : "success",
    });
  }
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
  if(isibox == 4){return;}
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
    .html('<label>Data comparison '+isibox+'</label><div style="padding:10px;border-style: solid;border-width: thin;border-radius: 5px;border-color:#ccc;margin-bottom:10px;"><div class="row"><div class="form-group col-sm-4"><label>Data house</label><select name="val_pemkandang'+isibox+'" class="form-control" id="optionselect_kandang" style="width: 100%"><option disabled selected>-Select Data house-</option></select></div><div class="form-group col-sm-4"><label>Period</label><input name="val_pemperiode'+isibox+'" class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Input period-"></div></div></div>')
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
    url : "<?php echo base_url('history_house/data_select'); ?>",
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
