<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

function addweek(){
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week + 1;
    var vweek = 7 * newweek;

    $('<div>')
    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+newweek})
    .html('<label>Week '+newweek+'</label><div style="margin-bottom:15px;border-style:solid;padding:10px;border-width: thin;border-radius: 5px;border-color: #ccc;"><div class="form-group"><label>Day '+(vweek - 6)+' </label><input name="week'+(vweek - 6)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+(vweek - 5)+' </label><input name="week'+(vweek - 5)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+(vweek - 4)+' </label><input name="week'+(vweek - 4)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+(vweek - 3)+' </label><input name="week'+(vweek - 3)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+(vweek - 2)+' </label><input name="week'+(vweek - 2)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+(vweek - 1)+' </label><input name="week'+(vweek - 1)+'" type="text" class="form-control" value=""></div><div class="form-group"><label>Growday '+vweek+' </label><input name="week'+vweek+'" type="text" class="form-control" value=""></div></div>')
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
    url : "<?php echo base_url('setting/data_change_kandang'); ?>",
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
    var ling = '<?php echo base_url('setting/load_growchange'); ?>';
    var isidata = {
        'nama_kandang' : $('[name="kandang"]').val(),
    };

    $.ajax({
        url : ling,
        type: "POST",
        data: isidata,
        dataType: "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if( data.status == true){

            }else{

            }
        }
    });
}

function save(){
    var ling = '<?php echo base_url('setting/save_standard_val'); ?>';

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