<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    reload_data();
});

function reload_data(){
    var d = new Date();
    var waktu = 60 - d.getSeconds();
    $('#head1').after('&nbsp;<small span id="refwaktu" style="font-weight: thin;">(Refresh ' + waktu + ')</small>');
    setInterval(function() {
        d = new Date();
        waktu = 60 - parseInt(d.getSeconds());
        if(parseInt(waktu) < 2) {
            getdat();
        }
        $('#refwaktu').text("(Refresh " + waktu + ")");
    }, 1000);
}

function getdat(){
    $.ajax({
        url : '<?php echo base_url('history_house/rdata')?>',
        type : "GET",
        dataType : "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if(data.status == true){
                var i;
                var isi = data.isi;
                for (i = 0; i < data.countfarm; i++) {
                    var isidata = isi[i];
                    $('#shperiode' + i).text(isidata.periode);
                    $('#shgrow' + i).text(isidata.growday);
                    $('#shtgl' + i).text(isidata.tanggal);
                    $('#shjam' + i).text(isidata.jam);
                    $('#shreqtemp' + i).text(isidata.req_temp);
                    $('#shavgtemp' + i).text(isidata.avg_temp);
                    $('#shhum' + i).text(isidata.humidity);
                    $('#shwind' + i).text(isidata.wind);
                    $('#shfeed' + i).text(isidata.feed);
                    $('#shwater' + i).text(isidata.water);
                    $('#shpress' + i).text(isidata.static_pressure);
                    $('#shfan' + i).text(isidata.fan);
                }
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