var kategrafik = 'HOUR_1';

$(document).ready(function(){
  loadtabel();
  $('#btnprint').on('click',(function(e){

    e.preventDefault();

    swal.fire({
      title: "Underconstruction !",
      html : '<p style="font-size: 14px">Sedang Dalam Pengembangan . . .</p>',
      type : "warning",
    });

    return;


  var datkandang = $('[name="val_kandang"]').val();
  var datperiode = $('[name="val_periode"]').val();
  var datgraf = $('#optionselect').val();

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

  $('#btnprint').attr('disabled','true');

  var isiperbandingan = $('#boxpembanding').attr('data-val');
  var inijson = {
      'value1' : 'DAY_1',
      'value2' : datgraf,
      'value3' : datkandang,
      'value4' : $('[name="daydari"]').val(),
      'value5' : $('[name="daysampai"]').val(),
      'value7' : datperiode,
      'valnomor' : isiperbandingan
    };

  var isijson = {};
  if (isiperbandingan > 0) {
    for (var i = 1; i < (parseInt(isiperbandingan) + 1); i++) {
      var nomor = 7 + i;

      if ($('[name="val_pemkandang'+i+'"]').val() == '' || $('[name="val_pemkandang'+i+'"]').val() == null || $('[name="val_pemkandang'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Peringatan!",
          html : '<p style="font-size: 14px">Data Kandang pada pembanding '+i+' Masih Kosong!</p>',
          type : "warning",
        });
        return;
      }
      if ($('[name="val_pemperiode'+i+'"]').val() == '' || $('[name="val_pemperiode'+i+'"]').val() == null || $('[name="val_pemperiode'+i+'"]').val() == undefined) {
        swal.fire({
          title: "Peringatan!",
          html : '<p style="font-size: 14px">Data periode pada pembanding '+i+' Masih Kosong!</p>',
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
      title: 'Memproses Data',
      html: '<p style="font-size: 14px">Mohon tunggu proses ini memerlukan waktu.</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });

    var dt = {};
  loopprint(inijson,1,datgraf.length,dt);

  }));

  selectdata_kandang();

  $('.select2posisi').select2({
  placeholder : 'Pilih Posisi Y axis',
  allowClear : true
  });
});

function loopprint(dataini,awal,loop,dataurl) {
  if(awal <= loop){
    Swal.fire({
      title: 'Memproses Data',
      html: '<p style="font-size: 14px">Membuat file unduh '+awal+' dari '+loop+'</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });
    url = awal - 1;
    $.ajax({
      type: "POST",
      url : "<?php echo base_url('export_excel/reportmtd_history_house_day/');?>"+url,
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
            title: "Gagal!",
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
      html : '<p style="font-size: 14px">Silahkan klik file dibawah ini</p>'+seturl,
      type : "success",
      allowOutsideClick: false,
    });
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
        url:"<?php echo base_url('report/datatabel_dyaxish');?>",
        type:"POST",
        data : {
        'data1kandang' : function () {return $('[name="val_kandang1"]').val();},
        'data1periode' : function () {return $('[name="val_periode1"]').val();},
        'data1data'    : function () {return $('[name="val_data1"]').val();},
        'data2kandang' : function () {return $('[name="val_kandang2"]').val();},
        'data2periode' : function () {return $('[name="val_periode2"]').val();},
        'data2data'    : function () {return $('[name="val_data2"]').val();},
        'value61'      : function () {return $('[name="hourdari1"]').val();},
        'value62'      : function () {return $('[name="hourdari2"]').val();},
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
            data:"ttanggal_value",
            orderable: false
        },
        {
            title: "JAM",
            data:"jjam_value",
            orderable: false
        },
        {
            title: "GROW DAY",
            data:"grow_value",
            orderable: false
        },
        {
            title: "DATA SUMBU Y KIRI",
            data:"isi_value1",
            orderable: false
        },
        {
            title: "DATA SUMBU Y KANAN",
            data:"isi_value3",
            orderable: false
        },
    ],
    order: [[1, 'asc'],[2, 'asc']],
    rowCallback: function(row, data, iDisplayIndex) {
        var info = this.fnPagingInfo();
        var page = info.iPage;
        var length = info.iLength;
        var index = page * length + (iDisplayIndex + 1);
        $('td:eq(0)', row).html(index);
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

      dtkandang = data;
    }
  });
}

function isiselect_kandang(inidata){
  $('.optionselect_kandang').empty();
  $('.optionselect_kandang')
  .val('')
  .select2({
    placeholder : '-Pilih Kandang-',
    allowClear : true,
    data : inidata,
  });
}

function cekgrafik() {
  var cek = 0;
  var title = '';
  var data1kandang = $('[name="val_kandang1"]').val();
  var data1periode = $('[name="val_periode1"]').val();
  var data1data    = $('[name="val_data1"]').val();
  var data1posisi  = $('[name="posisiy1"]').val();
  var data2kandang = $('[name="val_kandang2"]').val();
  var data2periode = $('[name="val_periode2"]').val();
  var data2data    = $('[name="val_data2"]').val();
  var data2posisi  = $('[name="posisiy2"]').val();
  
  if (data2posisi == '' || data2posisi == null || data2posisi == undefined) {title = 'Data 2 input posisi masih kosong!';cek = 1;}
  if (data2data == '' || data2data == null || data2data == undefined) {title = 'Data 2 input data house masih kosong!';cek = 1;}
  if (data2periode == '' || data2periode < 1) {title = 'Data 2 input periode masih kosong!';cek = 1;}
  if (data2kandang == '' || data2kandang == null || data2kandang == undefined) {title = 'Data 2 input kandang masih kosong!';cek = 1;}
  if (data1posisi == '' || data1posisi == null || data1posisi == undefined) {title = 'Data 1 input posisi masih kosong!';cek = 1;}
  if (data1data == '' || data1data == null || data1data == undefined) {title = 'Data 1 input data house masih kosong!';cek = 1;}
  if (data1periode == '' || data1periode < 1) {title = 'Data 1 input periode masih kosong!';cek = 1;}
  if (data1kandang == '' || data1kandang == null || data1kandang == undefined) {title = 'Data 1 input kandang masih kosong!';cek = 1;}
  if (parseInt($('[name="hourdari1"]').val()) > parseInt($('[name="hourdari2"]').val())) {title = 'Data Grow Day Salah!';cek = 1;}

  if(cek == 1){
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">'+title+'</p>',
      type : "warning",
    });
    return 1;
  }else{return 0;}
}

function grafik(){
  $('#inihtml').empty();

  if (cekgrafik() == 1) {return;}

  Swal.fire({
    title: 'Memproses Data',
    html: '<p style="font-size: 14px">Mohon tunggu proses ini memerlukan waktu.</p>',
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
    'data2kandang' : $('[name="val_kandang2"]').val(),
    'data2periode' : $('[name="val_periode2"]').val(),
    'data2data'    : $('[name="val_data2"]').val(),
    'data2posisi'  : $('[name="posisiy2"]').val(),
    'value61'      : $('[name="hourdari1"]').val(),
    'value62'      : $('[name="hourdari2"]').val(),
    'namagrafik'   : $('[name="namagrafik"]').val()
  };

    $.ajax({
      type: "POST",
      url : "<?php echo base_url('report/dataaxish'); ?>",
      data : inijson,
      dataType : "JSON",
      success : function(data){
        get_sess(data.sess);
        if(data.status == true){

          $('<div>')
          .html('<div class="box box-success"><div class="box-header with-border"><h3 class="box-title" id="titlegrafik"><span style="color: #aaa;">-Set Options Terlebih Dahulu-</span></h3><div class="box-tools pull-right"><button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button></div></div><div class="box-body"><div id="inicanvas"></div></div></div>')
          .appendTo('#inihtml');

          $('#inicanvas').empty();
          $('<canvas>')
          .attr({'id' : 'chartcanvas'})
          .appendTo('#inicanvas');

          var data_color = [window.chartColors.orange, window.chartColors.green, window.chartColors.red, window.chartColors.purple];

          var lineChartData = {};
          lineChartData['labels'] = data.labelgf;
          lineChartData['datasets'] = [
                  {
                    label: data.linelabel[0],
                    borderColor: window.chartColors.blue,
                    backgroundColor: window.chartColors.blue,
                    fill: false,
                    data: data.data[0],
                    spanGaps: true,
                    yAxisID: 'y1',
                  },
                  {
                    label: data.linelabel[1],
                    borderColor: data_color[0],
                    backgroundColor: data_color[0],
                    fill: false,
                    data: data.data[1],
                    spanGaps: true,
                    yAxisID: 'y2',
                  }
                ];

          var datascales = {
              y1: {
                  ticks: {
                      beginAtZero:true
                  },
                  scaleLabel: {
                       display: true,
                       fontSize: 20 
                    },
                  position: 'left',
              },
              y2: {
                  ticks: {
                      beginAtZero:true
                  },
                  scaleLabel: {
                       display: true,
                       fontSize: 20 
                    },
                  position: 'right',
              },
            };

          var canvas = document.getElementById('chartcanvas')
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
            scales: datascales,
            }
          });

          swal.fire({
            title: "Selesai!",
            html : '<p style="font-size: 14px">Grafik telah dibuat</p>',
            type : "success",
          });

          $('#titlegrafik').html(data.glabel);
          $('[name="hourdari1"]').val(data.hourdari1);
          $('[name="hourdari2"]').val(data.hourdari2);
          $('#boxtabel').removeAttr('style');
          $('#mytable').DataTable().ajax.url("<?php echo base_url('report/datatabel_dyaxish');?>").load();
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

function selectdata(nomor){
  $('#optionselect'+nomor).empty();
  $('#optionselect'+nomor)
  .select2({
    allowClear : true,
    placeholder : 'Sedang memproses . . .',
  });
  var inidata = $.ajax({
    type: "POST",
    url : "<?php echo base_url('history_house/data_select'); ?>",
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
    placeholder : '-Pilih Data-',
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
