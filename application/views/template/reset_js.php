$('#rstgo').on('click',function(){
  Swal.fire({
    title: 'Masukan Password untuk kamanan',
    input: 'password',
    inputAttributes: {
      autocapitalize: 'off'
    },
    showCancelButton: true,
    confirmButtonText: 'Mulai',
    showLoaderOnConfirm: true,
    preConfirm: (login) => {
      $.ajax({
        url : "<?php echo base_url('login/keamanan');?>",
        type: "POST",
        data: {'value' : btoa(login)},
        dataType:"JSON",
        success: function(data){
          $(this).attr('disabled','true');
          $('#tl').text('Sedang memproses . . .  ');
          cekreset(data.status);
        },
      });
    },
    allowOutsideClick: () => !Swal.isLoading()
  });

  function cekreset(result){
    if (result == true) {
      $.ajax({
        url : "<?php echo base_url('get_excel/reset_data');?>",
        type: "POST",
        data: {
          'value1' : btoa($('#resetoptionselect_kandang').val()),
          'value2' : btoa($('[name="namapenanggung"]').val()),
          'value3' : btoa($('[name="ketpenanggung"]').val()),
        },
        dataType:"JSON",
        success: function(data){
          if(data.status == true){
            swal.fire({
              title: "Sukses!",
              html : '<p style="font: 14px">Data telah direset.</p>',
              type : "success",
            });
          }else{
            swal.fire({
              title: "Gagal!",
              html : '<p style="font: 14px">Data tidak direset. Data mungkin telah kosong.</p>',
              type : "danger",
            });
          }
          $('#rstgo').removeAttr('disabled');
          $('#tl').text('');
          $('#modalreset').modal('hide');
        },
      });
    }else{
      swal.fire({
        title: "Gagal!",
        html : '<p style="font: 14px">Password yang anda masukan salah.</p>',
        type: "danger",
      });
      $('#rstgo').removeAttr('disabled');
      $('#tl').text('');
    }
  }
});

$('#resetbtn').on('click',function(){
  Swal.fire({
    title: 'Peringatan!',
    html : '<p style="font-size: 14px">Apakah anda yakin akan melakukan <b>reset data</b>?</p>',
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya',
    cancelButtonText: 'Batal'
  }).then(function(result){
    if(result.value == true){
      $('#modalreset').modal('show');
      rselectdata_kandang();
    }
  });
});

function rselectdata_kandang(){
  $.ajax({
    type: "POST",
    url : "<?php echo base_url('get_excel/data_select_kandang'); ?>",
    data : {
      'value1' : 'HOUR_1',
    },
    dataType: "JSON",
    success: function(data){
      get_sess(data.sess);
      risiselect_kandang(data);
    }
  });
}

function risiselect_kandang(inidata){
  $('#resetoptionselect_kandang').empty();
  $('#resetoptionselect_kandang')
  .val('')
  .select2({
    placeholder : '-Pilih Kandang-',
    allowClear : true,
    data : inidata,
  }).on("change", function () {
    var setdata = $('#resetoptionselect_kandang').val();
  });
}
