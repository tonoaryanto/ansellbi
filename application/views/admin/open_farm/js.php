<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    $('#uploadform-aksi').on('submit',(function(e){

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
    if ($('[name="select_data"]').val() == null) {
    swal.fire({
        title: "peringatan!",
        html : '<p style="font-size: 14px">Mohon pilih data yang akan di upload terlebih dahulu!</p>',
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

    if ($('[name="select_data"]').val() == 'house') {
    var daturl = "<?php echo base_url('get_excel/open_file');?>";
    }
    if ($('[name="select_data"]').val() == 'alarm') {
    var daturl = "<?php echo base_url('get_excel/open_file_alarm');?>";
    }

    $.ajax({
        url : daturl,
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
                title: "Data tidak disimpan!",
                html : data.message,
                type: "success",
            });
        var sebelumnya = $('#textlog').html();
        $('#textlog').html(sebelumnya + data.datamessage);
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

    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings)
    {
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

    var t = $("#mytable").dataTable({
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
        oLanguage: {
            sProcessing: "<center>loading...</center>"
        },
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {"url": "<?php echo base_url('admin/openfarm/json'); ?>", "type": "POST"},
        columns: [
            {
                "data": "id",
                "orderable": false
            },
            {
                "data": "nama_kandang"
            },
            {
                "data": "id",
                "orderable": false
            }
        ],
        order: [[0, 'desc']],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);

            var btnedit = '<li><a href="#" onclick="edit_form(' + data.id + ');"><i class="fa fa-edit"></i> Edit</a></li>';

            var btnhapus = '<li><a href="#" id="btn-hapus-form" onclick="_delete(' + data.id + ');"><i class="fa fa-trash"></i> Hapus</a></li>';
            var btnview = '<li><a href="#" onclick="upload_form(' + data.id + ');"><i class="fa fa-upload"></i> Upload</a></li>';

            var btn = '<div class="btn-group dropup"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2px 7px;"><span class="caret"></span></button><ul class="dropdown-menu">' + btnview + btnedit + btnhapus +'</ul></div>';
            $('td:eq(0)', row).html(index + '&nbsp;' + btn);

            var bhouse = '<a class="btn btn-sm btn-default" href="' + '<?php echo base_url("admin/openhouse/datahouse/");?>' + data.id + '">History House</a>';
            var balarm = '<a class="btn btn-sm btn-default" href="' + '<?php echo base_url("admin/openhouse/dataalarm/");?>' + data.id + '">History Alarm</a>';
            $('td:eq(2)', row).html(bhouse+' '+balarm);
        }
    });

    jQuery('#btn-add-form').click(function(){
        $('#modal-form').modal('show');
        reset_form();
        $('#title-form').text('Tambah Data House');
        $('#form-aksi').attr('data-form','input');
    });

    $(".select2").select2();
});

function reset_form(){
    $('#form-aksi')[0].reset();
    $('[name="alamat_farm"]').text('');
}

function reload_table(){
    $("#mytable").DataTable().ajax.reload();
}

function enter(ini){
    document.getElementById(ini).onkeypress = function(event){
        if (event.keyCode == 13 || event.which == 13){
            save();
        }
    };
}

function save(){
    var aksi = $('#form-aksi').attr('data-form');
    if(aksi == 'input'){var ling = '<?php echo base_url('admin/openfarm/simpan'); ?>';}
    if(aksi == 'edit'){var ling = '<?php echo base_url('admin/openfarm/update'); ?>';}
    var cek = validasi();
    if(aksi != '' && cek != 1){
        $.ajax({
            url : ling,
            type: "POST",
            data: $('#form-aksi').serialize(),
            dataType: "JSON",
            success: function(data)
            {
                get_sess(data.sess);
                if( data.status == true){
                    $('#modal-form').modal('hide');
                    reset_form();
                    swal.fire({
                      title: "Berhasil!",
                      html : data.message,
                      type: "success",
                    });
                    reload_table();
                }
            }
        });
    }else{
        swal.fire({
            title: "Peringatan!",
            html : '<p style="font-size: 14px">Input value masih kosong atau value salah!</p>',
            type: "warning",
        });
    }
}

function validasi(){
    var cek = 0;
    if($('[name="nama_house"]').val() == ''){cek = 1;}
    return cek;
}

function _delete(id){
    swal.fire({
      title: "Apakah anda yakin akan menghapus data ini?",
      html: '<p style="font-size: 15px">Ini akan menghapus data kandang, histori house, histori alarm!</p>',
      type: "warning",
      confirmButtonColor: '#d33',
      showCancelButton: true,
      cancelButtonText: 'Batal',
      confirmButtonText: 'Ya, Hapus!'      
    }).then(result => {
        if (result.value) {
            $.ajax({
                url : '<?php echo base_url("admin/openfarm/delete");?>',
                type: "POST",
                data: {'value' : id},
                dataType: "JSON",
                success: function(data)
                {
                    get_sess(data.sess);
                    //if success reload ajax table
                    swal.fire({
                      title: "Berhasil!",
                      html : '<p style="font-size: 14px">Data telah dihapus</p>',
                      type: "success",
                    });
                    reload_table();
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    swal.fire({
                      title: "Gagal!",
                      html : '<p style="font-size: 14px">Terjadi Kesalahan data tidak dihapus</p>',
                      type: "error",
                    });
                }

            });
        } else {
            swal.fire({
              title: "Dibatalkan",
              html : '<p style="font-size: 14px">Data tidak dihapus</p>',
              type: "info",
            });
        }
    })           
}

function edit_form(id){
    $.ajax({
        url : '<?php echo base_url('admin/openfarm/edit/')?>' + id,
        type : "GET",
        dataType : "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if(data.status == true){
                var data = data.data;
                $('[name="id"]').val(data.id);
                $('[name="nama_house"]').val(data.nama_house);
                $('#title-form').text('Edit Data House');
                $('#form-aksi').attr('data-form','edit');
                $('#modal-form').modal('show');
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            swal.fire({
              title: "Gagal!",
              html : '<p style="font-size: 14px">Terjadi Kesalahan saat mengambil data!</p>',
              type: "error",
            });
        }
    });
}

function upload_form(id){
                $('#title-uploadform').text('Upload Data');
                $('[name="select_kandang"]').val(id);
                $('#uploadform-aksi').attr('data-form','upload');
                $('#modal-uploadform').modal('show');
}

function data_terakhir() {
    if ($('[name="periode"]').val() == '') {
      return;
    }
    if ($('[name="select_data"]').val() == null) {
      return;
    }

    if ($('[name="select_data"]').val() == 'house') {
    $('#txtctt').html('Contoh : history_[nama farm]_[kode farm]_ENG.csv');
    }
    if ($('[name="select_data"]').val() == 'alarm') {
    $('#txtctt').html('Contoh : AlarmHistory_[nama farm]_[kode farm]_ENG.csv');
    }


    $('[name="last_periode"]').val('On Process. . .');
    $('[name="last_growday"]').val('On Process. . .');

    $.ajax({
        url : "<?php echo base_url('admin/openfarm/last_data');?>",
        type: "GET",
        data: {
          'value1' : $('[name="periode"]').val(),
          'value2' : $('[name="select_kandang"]').val(),
          'value3' : $('[name="select_data"]').val(),
        },
        cache: false,
        dataType:"JSON",
        success: function(data){
          $('[name="last_periode"]').val(data.periode);
          $('[name="last_growday"]').val(data.growday);
        }
    });
}
