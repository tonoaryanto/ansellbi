<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

function addweek(){
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week + 1;
    var vweek = 7 * newweek;

    var htm = '';
    htm += '<label>Week '+newweek+'</label>';
    htm += '<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 6)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 6)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 5)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 5)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 4)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 4)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 3)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 3)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 2)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 2)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+(vweek - 1)+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+(vweek - 1)+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="form-group">';
    htm += '<label class="col-lg-12">Growday '+vweek+' </label>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Min</span>';
    htm += '<input name="week'+vweek+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
    htm += '<span class="input-group-addon">Max</span>';
    htm += '<input name="mxweek'+vweek+'" type="text" class="form-control" value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '</div>';

    $('<div>')
    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+newweek})
    .html(htm)
    .appendTo('#inputweek');
    $('#inputweek').attr('data-week',newweek);
}

function removeweek(){
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week - 1;
    if(count_week == 1){return;}
    $('#week'+count_week).remove();
    $('#inputweek').attr('data-week',newweek)
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
    loaddata();
  });
}

function loaddata(){
    $('#inputweek').html('');
    $('#inputweek').attr('data-week','0');
    var ling = '<?php echo base_url('setting/load_standard_val'); ?>';
    var isidata = {
        'nama_kandang' : $('[name="kandang"]').val(),
        'tpval' : $('[name="tpval"]').val()
    };
    var count_week = parseInt($('#inputweek').attr('data-week'));

    $.ajax({
        url : ling,
        type: "POST",
        data: isidata,
        dataType: "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if( data.status == true){
                var vnomordata = parseInt(data.dataSet['countweek']);
                    nomordata = vnomordata;
                    var dweek = 7;
                for (i = 0; i < nomordata; i++) {
                    var a = i + 1;
                    vweek = dweek * a;

                    var isinya1 = data.dataSet['dataweek'][vweek - 7];
                    if (isinya1 === undefined){isinya1 = '';}
                    var isinya2 = data.dataSet['dataweek'][vweek - 6];
                    if (isinya2 === undefined){isinya2 = '';}
                    var isinya3 = data.dataSet['dataweek'][vweek - 5];
                    if (isinya3 === undefined){isinya3 = '';}
                    var isinya4 = data.dataSet['dataweek'][vweek - 4];
                    if (isinya4 === undefined){isinya4 = '';}
                    var isinya5 = data.dataSet['dataweek'][vweek - 3];
                    if (isinya5 === undefined){isinya5 = '';}
                    var isinya6 = data.dataSet['dataweek'][vweek - 2];
                    if (isinya6 === undefined){isinya6 = '';}
                    var isinya7 = data.dataSet['dataweek'][vweek - 1];
                    if (isinya7 === undefined){isinya7 = '';}

                    var isinya21 = data.dataSet['dataweek2'][vweek - 7];
                    if (isinya21 === undefined){isinya21 = '';}
                    var isinya22 = data.dataSet['dataweek2'][vweek - 6];
                    if (isinya22 === undefined){isinya22 = '';}
                    var isinya23 = data.dataSet['dataweek2'][vweek - 5];
                    if (isinya23 === undefined){isinya23 = '';}
                    var isinya24 = data.dataSet['dataweek2'][vweek - 4];
                    if (isinya24 === undefined){isinya24 = '';}
                    var isinya25 = data.dataSet['dataweek2'][vweek - 3];
                    if (isinya25 === undefined){isinya25 = '';}
                    var isinya26 = data.dataSet['dataweek2'][vweek - 2];
                    if (isinya26 === undefined){isinya26 = '';}
                    var isinya27 = data.dataSet['dataweek2'][vweek - 1];
                    if (isinya27 === undefined){isinya27 = '';}

                    var htm = '';
                    htm += '<label>Week '+a+'</label>';
                    htm += '<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
                    htm += '<div class="form-group col-lg-12">';
                    htm += '<div class="col-lg-3" style="line-height: 34px;">';
                    htm += '<label>Input per week</label>';
                    htm += '</div>';
                    htm += '<div class="col-lg-3">';
                    htm += '<div class="input-group">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="weeka" onkeyup="weekinput(1,'+a+');" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-3">';
                    htm += '<div class="input-group">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweeka" onkeyup="weekinput(2,'+a+');" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-12">';
                    htm += '<hr style="margin-top:0px;">';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 6)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="'+isinya1+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 6)+'" type="text" class="form-control" value="'+isinya21+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 5)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="'+isinya2+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 5)+'" type="text" class="form-control" value="'+isinya22+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 4)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="'+isinya3+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 4)+'" type="text" class="form-control" value="'+isinya23+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 3)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="'+isinya4+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 3)+'" type="text" class="form-control" value="'+isinya24+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 2)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="'+isinya5+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 2)+'" type="text" class="form-control" value="'+isinya25+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 1)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="'+isinya6+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 1)+'" type="text" class="form-control" value="'+isinya26+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+vweek+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+vweek+'" type="text" class="form-control" value="'+isinya7+'">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+vweek+'" type="text" class="form-control" value="'+isinya27+'">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '</div>';

                    $('<div>')
                    .attr({'class' : 'form-group col-md-12', 'id' : 'week'+a})
                    .html(htm)
                    .appendTo('#inputweek');
                    $('#inputweek').attr('data-week',a);
                }
            }else{
                var dweek = 7;
                for (i = 0; i < 24; i++) {
                    var a = i + 1;
                    vweek = dweek * a;

                    var htm = '';
                    htm += '<label>Week '+a+'</label>';
                    htm += '<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
                    htm += '<div class="form-group col-lg-12">';
                    htm += '<div class="col-lg-3" style="line-height: 34px;">';
                    htm += '<label>Input per week</label>';
                    htm += '</div>';
                    htm += '<div class="col-lg-3">';
                    htm += '<div class="input-group">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="weeka" onkeyup="weekinput(1,'+a+');" placeholder="--Optional--" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-3">';
                    htm += '<div class="input-group">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweeka" onkeyup="weekinput(2,'+a+');" placeholder="--Optional--" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-12">';
                    htm += '<hr style="margin-top:0px;">';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 6)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 6)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 5)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 5)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 4)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 4)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 3)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 3)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 2)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 2)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+(vweek - 1)+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+(vweek - 1)+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="form-group col-lg-7">';
                    htm += '<label class="col-lg-12">Growday '+vweek+' </label>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Min</span>';
                    htm += '<input name="week'+vweek+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '<div class="input-group col-lg-12" style="margin-bottom:10px;">';
                    htm += '<span class="input-group-addon">Max</span>';
                    htm += '<input name="mxweek'+vweek+'" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '</div>';

                    $('<div>')
                    .attr({'class' : 'form-group col-md-12', 'id' : 'week'+a})
                    .html(htm)
                    .appendTo('#inputweek');
                    $('#inputweek').attr('data-week',a);
                }
            }
        }
    });
}

function weekinput(set,dt){
    var vweek = 7 * dt;
    if(set == 1){
        var isi = $('[name="weeka"]').val();
        $('[name="week'+ (vweek - 6) +'"]').val(isi);
        $('[name="week'+ (vweek - 5) +'"]').val(isi);
        $('[name="week'+ (vweek - 4) +'"]').val(isi);
        $('[name="week'+ (vweek - 3) +'"]').val(isi);
        $('[name="week'+ (vweek - 2) +'"]').val(isi);
        $('[name="week'+ (vweek - 1) +'"]').val(isi);
        $('[name="week'+ vweek +'"]').val(isi);
    }
    if(set == 2){
        var isi = $('[name="mxweeka"]').val();
        $('[name="mxweek'+ (vweek - 6) +'"]').val(isi);
        $('[name="mxweek'+ (vweek - 5) +'"]').val(isi);
        $('[name="mxweek'+ (vweek - 4) +'"]').val(isi);
        $('[name="mxweek'+ (vweek - 3) +'"]').val(isi);
        $('[name="mxweek'+ (vweek - 2) +'"]').val(isi);
        $('[name="mxweek'+ (vweek - 1) +'"]').val(isi);
        $('[name="mxweek'+ vweek +'"]').val(isi);
    }
}

function save(){
    var ling = '<?php echo base_url('setting/save_standard_val'); ?>';
    var cek = validasi();

    var isidata = {
            'nama_kandang' : $('[name="kandang"]').val(),
            'tpval' : $('[name="tpval"]').val()
        };

    var count_week = parseInt($('#inputweek').attr('data-week'));
    var isiweek = {}
    var mp = 7;
    for (i = 0; i < count_week; i++) {
        var vweek = 7 * (i +1);

        var dt = {};
        var xdt = {};
        dt[0] = $('[name="week'+ (vweek - 6) +'"]').val();
        dt[1] = $('[name="week'+ (vweek - 5) +'"]').val();
        dt[2] = $('[name="week'+ (vweek - 4) +'"]').val();
        dt[3] = $('[name="week'+ (vweek - 3) +'"]').val();
        dt[4] = $('[name="week'+ (vweek - 2) +'"]').val();
        dt[5] = $('[name="week'+ (vweek - 1) +'"]').val();
        dt[6] = $('[name="week'+ vweek +'"]').val();
        xdt[0] = $('[name="mxweek'+ (vweek - 6) +'"]').val();
        xdt[1] = $('[name="mxweek'+ (vweek - 5) +'"]').val();
        xdt[2] = $('[name="mxweek'+ (vweek - 4) +'"]').val();
        xdt[3] = $('[name="mxweek'+ (vweek - 3) +'"]').val();
        xdt[4] = $('[name="mxweek'+ (vweek - 2) +'"]').val();
        xdt[5] = $('[name="mxweek'+ (vweek - 1) +'"]').val();
        xdt[6] = $('[name="mxweek'+ vweek +'"]').val();

        for(a = 0; a < 7; a++) {
            var kur = 7 - a;
            var cdt = {};
            cdt[0] = dt[a];
            cdt[1] = xdt[a];
            isiweek[(vweek - kur)] = cdt;
        }
    }

    isidata['week'] = isiweek;

    if(cek != 1){
        $.ajax({
            url : ling,
            type: "POST",
            data: isidata,
            dataType: "JSON",
            success : function(data)
            {
                get_sess(data.sess);
                if( data.status == true){
                    swal.fire({
                      title: "Successl!",
                      html : data.message,
                      type: "success",
                    });
                }else{
                    swal.fire({
                      title: "No data changes!",
                      html : data.message,
                    });
                }
            }
        });
    }else{
        swal.fire({
            title: "Warning!",
            html : '<p style="font-size: 14px">The input value is still empty or wrong!</p>',
            type: "warning",
        });
    }
}

function validasi(){
    var cek = 0;
    var count_week = parseInt($('#inputweek').attr('data-week'));

    if($('[name="kandang"]').val() == ''){cek = 1;}

    for (i = 0; i < count_week; i++) {
        var week = 7 * (i + 1);

        var dt1 = $('[name="week'+ (vweek - 6) +'"]').val();
        var dt2 = $('[name="week'+ (vweek - 5) +'"]').val();
        var dt3 = $('[name="week'+ (vweek - 4) +'"]').val();
        var dt4 = $('[name="week'+ (vweek - 3) +'"]').val();
        var dt5 = $('[name="week'+ (vweek - 2) +'"]').val();
        var dt6 = $('[name="week'+ (vweek - 1) +'"]').val();
        var dt7 = $('[name="week'+ vweek +'"]').val();
        var xdt1 = $('[name="mxweek'+ (vweek - 6) +'"]').val();
        var xdt2 = $('[name="mxweek'+ (vweek - 5) +'"]').val();
        var xdt3 = $('[name="mxweek'+ (vweek - 4) +'"]').val();
        var xdt4 = $('[name="mxweek'+ (vweek - 3) +'"]').val();
        var xdt5 = $('[name="mxweek'+ (vweek - 2) +'"]').val();
        var xdt6 = $('[name="mxweek'+ (vweek - 1) +'"]').val();
        var xdt7 = $('[name="mxweek'+ vweek +'"]').val();
 
        if(dt1 == '' && xdt1 != ''){cek = 1;}
        if(dt2 == '' && xdt2 != ''){cek = 1;}
        if(dt3 == '' && xdt3 != ''){cek = 1;}
        if(dt4 == '' && xdt4 != ''){cek = 1;}
        if(dt5 == '' && xdt5 != ''){cek = 1;}
        if(dt6 == '' && xdt6 != ''){cek = 1;}
        if(dt7 == '' && xdt7 != ''){cek = 1;}
        if(xdt1 == '' && dt1 != ''){cek = 1;}
        if(xdt2 == '' && dt2 != ''){cek = 1;}
        if(xdt3 == '' && dt3 != ''){cek = 1;}
        if(xdt4 == '' && dt4 != ''){cek = 1;}
        if(xdt5 == '' && dt5 != ''){cek = 1;}
        if(xdt6 == '' && dt6 != ''){cek = 1;}
        if(xdt7 == '' && dt7 != ''){cek = 1;}
 
    }
    if($('[name="week1"]').val() == ''){cek = 1;}
    if($('#inputweek').html() == ''){cek = 1;}
    
    return cek;
}