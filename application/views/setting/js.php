<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
$(document).ready(function(){
    selectdata_kandang();
    $('.select2').select2();
});

function std_aktif(){
    var std_aktif = $('[name="stdaktif"]').val();
    if(std_aktif == 'y'){
        $('#inputweek').show();
    }else{
        $('#inputweek').hide();
    }
}

function addweek(){
    var count_week = parseInt($('#inputweek').attr('data-week'));
    var newweek = count_week + 1;

    $('<div>')
    .attr({'class' : 'form-group col-md-2', 'id' : 'week'+newweek})
    .html('<label>Week '+newweek+'</label><input name="week'+newweek+'" type="text" class="form-control" >')
    .appendTo('#inputweek');
    $('#inputweek').attr('data-week',newweek)
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
    //selectdata();
  });
}
function save(){
    
}