<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    reload_data();
    getdat();

    $('#example1').DataTable({
        "lengthMenu": [[-1], ["All"]],
        "paging":   false,
        "searching": false,
        "ordering": false,
        fixedColumns: {
            leftColumns: 1
        },
        scrollX: true,
        "info":     false
    });
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
                setdt(data.countfarm,0,isi);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(textStatus);
        }
    });
}

function setdt(count,i,data){
    if(i < count){
        var isidata = data;
        $('#dtrt0_' + i).text(isidata.periode[i]);
        $('#dtrt1_' + i).text(isidata.growday[i]);
        $('#dtrt2_' + i).html(isidata.tanggal[i]);
        $('#dtrt3_' + i).text(isidata.jam[i]);
        $('#dtrt4_' + i).text(isidata.req_temp[i]);
        $('#dtrt5_' + i).attr('style','background:'+isidata.avg_bg[i]);
        $('#dtrt5_' + i).text(isidata.avg_temp[i]);
        $('#dtrt6_' + i).text(isidata.out_temp[i]);
        $('#dtrt7_' + i).text(isidata.humidity[i]);
        $('#dtrt8_' + i).text(isidata.fan[i]);
        $('#dtrt9_' + i).text(isidata.reqspeed[i]);
        $('#dtrt10_' + i).text(isidata.windspeed[i]);
        $('#dtrt11_' + i).text(isidata.feed[i]);
        $('#dtrt12_' + i).text(isidata.water[i]);
        $('#dtrt13_' + i).text(isidata.static_pressure[i]);
        $('#dtrt14_' + i).text(isidata.silo1[i]);
        $('#dtrt15_' + i).text(isidata.silo2[i]);
        i = i + 1;
        setdt(count,i,data);
    }
}