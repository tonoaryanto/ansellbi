<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

$(document).ready(function(){
    var cekdt = $('#ckgwd').attr('data-ini');
    var modal = $('#ckgwd');
    var body = $(window);
    // Get modal size
    var w = modal.width();
    var h = modal.height();
    // Get window size
    var bw = body.width();
    var bh = body.height();
  
    // Update the css and center the modal on screen
    modal.css({
    "background" : "#000c",
    "position": "absolute",
    })

    $('#ckgwd').modal({backdrop: 'static', keyboard: false})

    if(cekdt == 1){
        swal.close();
        $('#ckgwd').modal('show');
    }
});