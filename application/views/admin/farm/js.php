<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
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
        ajax: {"url": "<?php echo base_url('admin/farm/json'); ?>", "type": "POST"},
        columns: [
            {
                "data": "id",
                "orderable": false
            },
            {
                "data": "nama_farm"
            },
            {
                "data": "alamat_farm"
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
            var btnview = '';

            var btn = '<div class="btn-group dropup"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2px 7px;"><span class="caret"></span></button><ul class="dropdown-menu">' + btnview + btnedit + btnhapus +'</ul></div>';
            $('td:eq(0)', row).html(index + '&nbsp;' + btn);

            var bview = '<a class="btn btn-sm btn-default" href="' + '<?php echo base_url("admin/openfarm/data/");?>' + data.id + '">Open</a>';
            $('td:eq(3)', row).html(bview);
        }
    });

    jQuery('#btn-add-form').click(function(){
        $('#modal-form').modal('show');
        reset_form();
        $('#title-form').text('Tambah Data Farm');
        $('#form-aksi').attr('data-form','input');
    });
});

function reset_form(){
    $('#form-aksi')[0].reset();
    $('[name="alamat_farm"]').text('');
}

function reload_table(){
    $("#mytable").DataTable().ajax.reload();
}

function save(){
    var aksi = $('#form-aksi').attr('data-form');
    if(aksi == 'input'){var ling = '<?php echo base_url('admin/farm/simpan'); ?>';}
    if(aksi == 'edit'){var ling = '<?php echo base_url('admin/farm/update'); ?>';}
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
    }
}

function validasi(){
    var cek = 0;
    if($('[name="nama_farm"]').val() == ''){cek = 1;}
    return cek;
}

function _delete(id){
    swal.fire({
      title: "Apakah anda yakin akan menghapus data ini?",
      html: '<p style="font-size: 15px">Ini akan menghapus data kandang, histori house, user login, histori alarm, dan semua yang berkaitan dengan nama farm ini!</p>',
      type: "warning",
      confirmButtonColor: '#d33',
      showCancelButton: true,
      cancelButtonText: 'Batal',
      confirmButtonText: 'Ya, Hapus!'      
    }).then(result => {
        if (result.value) {
            $.ajax({
                url : '<?php echo base_url("admin/farm/delete");?>',
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
        url : '<?php echo base_url('admin/farm/edit/')?>' + id,
        type : "GET",
        dataType : "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if(data.status == true){
                var data = data.data;
                $('[name="id"]').val(data.id);
                $('[name="nama_farm"]').val(data.nama_farm);
                $('[name="alamat_farm"]').text(data.alamat_farm);
                $('#title-form').text('Edit Data Farm');
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
