$(document).ready(function(){
  $('#form-aksi').on('submit',(function(e){

    e.preventDefault();

    if ($('[name="periode"]').val() == '') {
      swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon Input Periode Di Isi Terlebih Dahulu!</p>',
        type: "warning",
      });
      $('[name="periode"]').focus();
      return;
    }
    if ($('[name="select_kandang"]').val() == null) {
      swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon Input Nama Kandang Di Isi Terlebih Dahulu!</p>',
        type: "warning",
      });
      $('[name="select_kandang"]').focus();
      return;
    }

    $('#tombol').attr('disabled','true');

    $('#textlog').text('');
    var datatext = "Memproses data . . .";
    $('#textprogres').text(datatext);
    $('#textlog').html('- '+datatext);

    var senddata = new FormData(this);

    Swal.fire({
      title: 'Memproses Data',
      html: '<p style="font-size: 14px">Mohon tunggu proses ini memerlukan waktu.</p>',
      allowOutsideClick: false,
      onBeforeOpen: () => {
        Swal.showLoading()
        Swal.getTimerLeft()
      },
    });

    $.ajax({
        url : "<?php echo base_url('get_excel/open_file_alarm');?>",
        type: "POST",
        data: senddata,
        contentType: false,
        cache: false,
        processData: false,
        dataType:"JSON",
        success: function(data){
          get_sess(data.sess);
          if(data.status == true){
              swal.fire({
                title: "Berhasil!",
                html : '<p style="font-size: 14px">Data berhasil diupload!</p>',
                type: "success",
              });
              var sebelumnya = $('#textlog').html();
              $('#textlog').html(sebelumnya + data.datamessage);
            $('#tombol').removeAttr('disabled');
          }else{
            $('#tombol').removeAttr('disabled');
            swal.fire({
              title: "Gagal!",
              html : data.message,
              type: "error",
            });
            var datatext = $('#textlog').html() + "&#10;- Error . . .";
            $('#textlog').html(datatext);
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          $('#btnstop').remove();
          $('#tombol').removeAttr('disabled');
          swal.fire({
            title: "Gagal!",
            html : '<p style="font-size: 14px">Terjadi Kesalahan!</p>',
            type: "error",
          });
          var datatext = $('#textlog').html() + "&#10;- Error . . .";
          $('#textlog').html(datatext);
        }
    });
  }));

    $(".select2").select2();
    select_kandang();
});

function select_kandang() {
    $('[name="select_kandang"]').empty();
    $('[name="select_kandang"]')
    .select2({
      allowClear: true,
      placeholder: '- Pilih Kandang -',
      ajax: {
        dataType: 'json',
        url:  "<?php echo base_url('get_excel/dtkandang');?>",
        type: "GET",
        data: function(params) {return {
            search: params.term
        }},
        processResults: function (data, page) {
          get_sess(data.sess);
          return {results: data};
        },
      }
    }).on("change", function () {
      data_terakhir();
    });;
}

function add_new_kandang() {
  $('#list_select_kandang').hide();
  $('#new_select_kandang').show();
  $('[name="tambah_kandang"]').val('');
}

function batal_new_kandang() {
  $('#new_select_kandang').hide();
  $('#list_select_kandang').show();
}

function data_terakhir() {
    if ($('[name="periode"]').val() == '') {
      return;
    }
    if ($('[name="select_kandang"]').val() == null) {
      return;
    }

    $('[name="last_periode"]').val('On Process. . .');
    $('[name="last_growday"]').val('On Process. . .');

    $.ajax({
        url : "<?php echo base_url('get_excel/last_data_alarm');?>",
        type: "GET",
        data: {
          'value1' : $('[name="periode"]').val(),
          'value2' : $('[name="select_kandang"]').val(),
        },
        cache: false,
        dataType:"JSON",
        success: function(data){
          $('[name="last_periode"]').val(data.periode);
          $('[name="last_growday"]').val(data.growday);          
        }
    });
}

function save_new_kandang() {
  var cek = $('[name="tambah_kandang"]').val();
  if (cek == '') {
    swal.fire({
      title: "Peringatan!",
      html : '<p style="font-size: 14px">Mohon Isi Input Nama Kandang!</p>',
      type: "warning",
    });
  }else{
    $.ajax({
        url : "<?php echo base_url('get_excel/add_new_kandang');?>",
        type: "POST",
        data: {'value1' : $('[name="tambah_kandang"]').val()},
        cache: false,
        dataType:"JSON",
        success: function(data){
          get_sess(data.sess);
          if(data.status == true){
              swal.fire({
                title: "Berhasil!",
                html : '<p style="font-size: 14px">Nama Kandang Telah Ditambahkan!</p>',
                type: "success",
              });
              batal_new_kandang();
              select_kandang();
          }else{
            swal.fire({
              title: "Gagal!",
              html : data.message,
              type: "error",
            });
          }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          swal.fire({
            title: "Gagal!",
            html : '<p style="font-size: 14px">Terjadi Kesalahan!</p>',
            type: "error",
          });
        }
    });
  }
}