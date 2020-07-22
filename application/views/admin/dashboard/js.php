<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
var kategrafik = 'DAY_1';

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
        ajax: {"url": "<?php echo base_url('admin/karyawan/json'); ?>", "type": "POST"},
        columns: [
            {
                "data": "id",
                "orderable": false
            },
            {
                "data": "nama_pegawai"
            },
            {
                "data": "nip_pegawai"
            },
            {
                "data": "tempat_lahir"
            },
            {
                "data": "tanggal_lahir"
            },
            {
                "data": "kontak"
            },
            {
                "data": "status_karyawan"
            }
        ],
        order: [[0, 'desc']],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            var index = page * length + (iDisplayIndex + 1);
            var statuskr = ['Training','Tetap','Resign','Tidak Lolos Training'];

            <?php if($this->konfigurasi->akses_fitur('pegawai_edit') == 'Y'){ ?>
            var btnedit = '<li><a href="#" onclick="edit_form(' + data.id + ');"><i class="fa fa-edit"></i> Edit</a></li>';
            <?php }else{ ?> var btnedit = ''; <?php } ?>

            <?php if($this->konfigurasi->akses_fitur('pegawai_delete') == 'Y'){ ?>
            var btnhapus = '<li><a href="#" id="btn-hapus-form" onclick="_delete(' + data.id + ');"><i class="fa fa-trash"></i> Hapus</a></li>';
            <?php }else{ ?> var btnhapus = ''; <?php } ?>

            var btn = '<div class="btn-group dropup"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2px 7px;"><span class="caret"></span></button><ul class="dropdown-menu"><li><a href="#" id="btn-view-form" onclick="view(' + data.id + ');"><i class="fa fa-sticky-note-o"></i> View</a></li>' + btnedit + btnhapus +'</ul></div>';
            if(data.kontak != null && data.kontak != ''){
                var ktk = '+' + data.kontak_negara + data.kontak;
                $('td:eq(5)', row).html(ktk);
            }
            $('td:eq(0)', row).html(index + '&nbsp;' + btn);

            $('td:eq(6)', row).html(statuskr[data.status_karyawan]);
        }
    });
});

