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

    $('[name="stargrow"]').val('');
    $('[name="endgrow"]').val('');


    $.ajax({
        url : ling,
        type: "POST",
        data: isidata,
        dataType: "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if( data.status == true){
                $('[name="stargrow"]').val(data.dataset['stargrow']);
                $('[name="endgrow"]').val(data.dataset['endgrow']);
                $('[name="startime"]').val(data.dataset['startime']);
                $('[name="endtime"]').val(data.dataset['endtime']);
            }
        }
    });
}

function changedate_end(){
    var startgl = $('[name="startgl"]').val();
    var stargrow = $('[name="stargrow"]').val();
    var endtgl = $('[name="endtgl"]').val();

    var ling = '<?php echo base_url('setting/load_inputchangeend'); ?>';
    var isidata = {
        'nama_kandang' : $('[name="kandang"]').val(),
        'startgl' : startgl,
        'stargrow' : stargrow,
        'endtgl' : endtgl
    };

    $('[name="endgrow"]').val('');

    $.ajax({
        url : ling,
        type: "POST",
        data: isidata,
        dataType: "JSON",
        success : function(data)
        {
            get_sess(data.sess);
            if( data.status == true){
                $('[name="endgrow"]').val(data.dataset['endgrow']);
                $('[name="endtime"]').val(data.dataset['endtime']);
            }
        }
    });
}

function save(){
var ling = '<?php echo base_url('setting/save_growchange'); ?>';
var isidata = {
    'nama_kandang' : $('[name="kandang"]').val(),
    'startgl' : $('[name="startgl"]').val(),
    'stargrow' : $('[name="stargrow"]').val(),
    'endtgl' : $('[name="endtgl"]').val(),
    'endgrow' : $('[name="endgrow"]').val(),
    'flock' : $('[name="flock"]').val()
};
var cek = validasi();

if(cek != 1){
    const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-default',
            cancelButton: 'btn btn-primary'
        },
        buttonsStyling: false
    })

    swalWithBootstrapButtons.fire({
        title: 'Are you sure?',
        html : '<p style="font-size: 14px">You will not be able to restore the changed data!</p>',
        type: 'warning',
        showCancelButton: true,
        confirmButtonText: 'No, Return',
        cancelButtonText: 'Yes, Change it!',
        reverseButtons: false
    }).then((result) => {
        if (result.isConfirmed) {
            return;
        } else if (result.dismiss === Swal.DismissReason.cancel) {
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
                        allowOutsideClick: false,
                        type: "success",
                        }).then((result) => {
                            location.reload();
                        });
                    }else{
                        swal.fire({
                        title: "No data changes!",
                        html : data.message,
                        });
                    }
                }
            });
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
    if($('[name="startgl"]').val() == ''){cek = 1;}
    if($('[name="stargrow"]').val() == ''){cek = 1;}
    if($('[name="endtgl"]').val() == ''){cek = 1;}
    if($('[name="endgrow"]').val() == ''){cek = 1;}
    if($('[name="flock"]').val() == ''){cek = 1;}   
    return cek;
}