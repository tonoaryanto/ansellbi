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
        url : '<?php echo base_url('egg_counter/rdata')?>',
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
                    $('#shegg1' + i).text(isidata.eggcounter1);
                    $('#shegg2' + i).text(isidata.eggcounter2);
                    $('#shegg3' + i).text(isidata.eggcounter3);
                    $('#shegg4' + i).text(isidata.eggcounter4);
                    $('#shegg5' + i).text(isidata.eggcounter5);
                    $('#shegg6' + i).text(isidata.eggcounter6);
                    $('#shegg7' + i).text(isidata.eggcounter7);
                    $('#shegg8' + i).text(isidata.eggcounter8);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(textStatus);
        }
    });
}
