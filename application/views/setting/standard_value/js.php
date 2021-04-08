<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

function addweek(){
    if($('[name="kandang"]').val() == ''){return;}
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week + 1;
    var vweek = 7 * newweek;
    if(newweek > 1){$('#rmweek').show();}
    if(newweek != 0){$('#shweek').text(newweek);}

    var htm = '';
    htm +='<label>Week '+newweek+'</label>';
    htm +='<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
    htm += '<div class="form-group col-lg-12">';
    htm += '<div class="col-lg-2" style="line-height: 34px;">';
    htm += '<label>Input per week :</label>';
    htm += '</div>';
    htm += '<div class="col-lg-2">';
    htm +='<input name="weeka" onkeyup="weekinput(1,'+newweek+');" placeholder="--Optional--" type="text" class="form-control"  value="">';
    htm += '</div>';
    htm += '</div>';
    htm += '<div class="col-lg-12">';
    htm += '<hr style="margin-top:0px;">';
    htm += '</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 6)+' </label>';
    htm +='<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 5)+' </label>';
    htm +='<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 4)+' </label>';
    htm +='<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 3)+' </label>';
    htm +='<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 2)+' </label>';
    htm +='<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+(vweek - 1)+' </label>';
    htm +='<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='<div class="form-group col-lg-7">';
    htm +='<label>Growday '+vweek+' </label>';
    htm +='<input name="week'+vweek+'" type="text" class="form-control" value="">';
    htm +='</div>';
    htm +='</div>';

    $('<div>')
    .attr({'class' : 'form-group col-md-12', 'id' : 'week'+newweek})
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
    if(newweek <= 1){$('#rmweek').hide();}
    if(newweek != 0){$('#shweek').text(newweek);}
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
    var ling = '<?php echo base_url('setting/standard_value/load_val'); ?>';
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

                    if(nomordata > 1){$('#rmweek').show();}
                    if(nomordata <= 1){$('#rmweek').hide();}
                    if(nomordata != 0){$('#brshweek').show();$('#shweek').text(nomordata);}

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

                    var htm = '';
                    htm +='<label>Week '+a+'</label>';
                    htm +='<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
                    htm += '<div class="form-group col-lg-12">';
                    htm += '<div class="col-lg-2" style="line-height: 34px;">';
                    htm += '<label>Input per week :</label>';
                    htm += '</div>';
                    htm += '<div class="col-lg-2">';
                    htm +='<input name="weeka" onkeyup="weekinput(1,'+a+');" placeholder="--Optional--" type="text" class="form-control" value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-12">';
                    htm += '<hr style="margin-top:0px;">';
                    htm += '</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 6)+' </label>';
                    htm +='<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="'+isinya1+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 5)+' </label>';
                    htm +='<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="'+isinya2+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 4)+' </label>';
                    htm +='<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="'+isinya3+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 3)+' </label>';
                    htm +='<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="'+isinya4+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 2)+' </label>';
                    htm +='<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="'+isinya5+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 1)+' </label>';
                    htm +='<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="'+isinya6+'">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+vweek+' </label>';
                    htm +='<input name="week'+vweek+'" type="text" class="form-control" value="'+isinya7+'">';
                    htm +='</div>';
                    htm +='</div>';

                    $('<div>')
                    .attr({'class' : 'form-group col-md-12', 'id' : 'week'+a})
                    .html(htm)
                    .appendTo('#inputweek');
                    $('#inputweek').attr('data-week',a);
                }
            }else{
                var nomordata = 24;

                if(nomordata > 1){$('#rmweek').show();}
                if(nomordata <= 1){$('#rmweek').hide();}
                if(nomordata != 0){$('#brshweek').show();$('#shweek').text(nomordata);}

                var dweek = 7;
                for (i = 0; i < nomordata; i++) {
                    var a = i + 1;
                    vweek = dweek * a;

                    var htm = '';
                    htm +='<label>Week '+a+'</label>';
                    htm +='<div class="col-lg-12" style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;">';
                    htm += '<div class="form-group col-lg-12">';
                    htm += '<div class="col-lg-2" style="line-height: 34px;">';
                    htm += '<label>Input per week :</label>';
                    htm += '</div>';
                    htm += '<div class="col-lg-2">';
                    htm +='<input name="weeka" onkeyup="weekinput(1,'+a+');" placeholder="--Optional--" type="text" class="form-control"  value="">';
                    htm += '</div>';
                    htm += '</div>';
                    htm += '<div class="col-lg-12">';
                    htm += '<hr style="margin-top:0px;">';
                    htm += '</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 6)+' </label>';
                    htm +='<input name="week'+(vweek - 6)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 5)+' </label>';
                    htm +='<input name="week'+(vweek - 5)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 4)+' </label>';
                    htm +='<input name="week'+(vweek - 4)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 3)+' </label>';
                    htm +='<input name="week'+(vweek - 3)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 2)+' </label>';
                    htm +='<input name="week'+(vweek - 2)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+(vweek - 1)+' </label>';
                    htm +='<input name="week'+(vweek - 1)+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='<div class="form-group col-lg-7">';
                    htm +='<label>Growday '+vweek+' </label>';
                    htm +='<input name="week'+vweek+'" type="text" class="form-control" value="">';
                    htm +='</div>';
                    htm +='</div>';

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
    var ling = '<?php echo base_url('setting/standard_value/save_val'); ?>';

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

        for(a = 0; a < 7; a++) {
            var kur = 7 - a;
            var cdt = {};
            cdt[0] = dt[a];
            isiweek[(vweek - kur)] = cdt;
        }
    }

    isidata['week'] = isiweek;

    var cek = validasi();

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
    if($('[name="kandang"]').val() == ''){cek = 1;}
    if($('[name="week1"]').val() == ''){cek = 1;}
    if($('#inputweek').html() == ''){cek = 1;}
    
    return cek;
}