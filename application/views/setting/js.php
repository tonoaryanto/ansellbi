<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

function addweek(){
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week + 1;

    $('<div>')
    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+newweek})
    .html('<label>Week '+newweek+'</label><input name="week'+newweek+'" type="text" class="form-control" >')
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
                if(vnomordata == 1){
                    nomordata = 24;
                }else{
                    nomordata = vnomordata;
                }
                for (i = 0; i < nomordata; i++) {
                    if(vnomordata == 1){
                        var isinya = '';
                    }else{
                        var isinya = data.dataSet['dataweek'][i];
                    }
                    var a = i + 1;
                    $('<div>')
                    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+a})
                    .html('<label>Week '+a+'</label><input name="week'+a+'" type="text" class="form-control" value="'+isinya+'">')
                    .appendTo('#inputweek');
                    $('#inputweek').attr('data-week',a);
                }
            }else{
                for (i = 0; i < 24; i++) {
                    var a = i + 1;
                    $('<div>')
                    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+a})
                    .html('<label>Week '+a+'</label><input name="week'+a+'" type="text" class="form-control">')
                    .appendTo('#inputweek');
                    $('#inputweek').attr('data-week',a);
                }
            }
        }
    });
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
    for (i = 0; i < count_week; i++) {
        isiweek[i] = $('[name="week'+(i+1)+'"]').val();
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
        if($('[name="week'+(count_week - i)+'"]').val() == ''){cek = 1;}
    }

    return cek;
}