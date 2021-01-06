<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

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
    var ling = '<?php echo base_url('setting/load_growchange'); ?>';
    var isidata = {
        'nama_kandang' : $('[name="kandang"]').val()
    };

    $('#last_date').text('');
    $('#last_growday').text('');
    $('#last_flock').text('');
    $('#change_date').text('');
    $('#change_growday').text('');
    $('#change_flock').text('');
    $('#real_date').text('');
    $('#real_growday').text('');
    $('#real_flock').text('');

    $.ajax({
        url : ling,
        type: "POST",
        data: isidata,
        dataType: "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if( data.status == true){
                $('#last_date').text(data.dataset['last_date']);
                $('#last_growday').text(data.dataset['last_growday']);
                $('#last_flock').text(data.dataset['last_flock']);
                $('#change_date').text(data.dataset['change_date']);
                $('#change_growday').text(data.dataset['change_growday']);
                $('#change_flock').text(data.dataset['change_flock']);
                $('#real_date').text(data.dataset['real_date']);
                $('#real_growday').text(data.dataset['real_growday']);
                $('#real_flock').text(data.dataset['real_flock']);
                $('[name="startgl"]').val(data.dataset['startgl']);
                $('[name="startime"]').val(data.dataset['startime']);
                $('[name="stargrow"]').val(data.dataset['stargrow']);
                $('[name="endtgl"]').val(data.dataset['endtgl']);
                $('[name="endtime"]').val(data.dataset['endtime']);
                $('[name="endgrow"]').val(data.dataset['endgrow']);
                $('[name="flock"]').val(data.dataset['real_flock']);
            }
        }
    });
}

function changedate(){
    var startgl = $('[name="startgl"]').val();
    var endtgl = $('[name="endtgl"]').val();

    var ling = '<?php echo base_url('setting/load_inputchange'); ?>';
    var isidata = {
        'nama_kandang' : $('[name="kandang"]').val(),
        'startgl' : startgl,
        'endtgl' : endtgl
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