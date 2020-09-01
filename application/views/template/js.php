<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

	$('#menu-push').on('click',function () {
		var ini = $(this).attr('data-menu');
		if (ini == 'close') {
			$(this).attr('data-menu','open');
			$('body').attr('class','skin-blue sidebar-mini sidebar-open');
		}
		if(ini == 'open'){
			$(this).attr('data-menu','close');
			$('body').attr('class','skin-blue sidebar-mini');
		}
	});

	function rsize() {
		var width = $('body').width();
		var ini = $('#menu-push').attr('data-menu');
		if(width <= 768){
			if (ini == 'close') {$('body').attr('class','skin-blue sidebar-mini');}
			if(ini == 'open'){$('body').attr('class','skin-blue sidebar-mini sidebar-open');}
		}else{
			if (ini == 'close') {$('body').attr('class','skin-blue sidebar-collapse sidebar-mini');}
			if(ini == 'open'){$('body').attr('class','skin-blue sidebar-collapse sidebar-mini sidebar-open');}			
		}
	}

	function tanggal_indo(data){
		var ini = data.split('-');

		if(ini[0] != 0000 || ini[0] != 0){
			var _bulan = [
			'Januari',
			'Februari',
			'Maret',
			'April',
			'Mei',
			'Juni',
			'Juli',
			'Agustus',
			'September',
			'Oktober',
			'November',
			'Desember'
			];

			var bulan = _bulan[(Number(ini[1]) - 1)];

			return ini[2] + '&nbsp;' + bulan + '&nbsp;' + ini[0];
		}else{
			return data;
		}
	}

	function get_sess(sess){
		if(sess == 0){location.replace(base_url + 'login/keluar')}
	}

	function detectmob() { 
	 if( navigator.userAgent.match(/Android/i)
	 || navigator.userAgent.match(/webOS/i)
	 || navigator.userAgent.match(/iPhone/i)
	 || navigator.userAgent.match(/iPad/i)
	 || navigator.userAgent.match(/iPod/i)
	 || navigator.userAgent.match(/BlackBerry/i)
	 || navigator.userAgent.match(/Windows Phone/i)
	 ){
	    return true;
	  }
	 else {
	    return false;
	  }
	}

	$('#logout').on('click',function(){
	    Swal.fire({
	      title: 'Warning!',
	      html : '<p style="font-size: 14px">Are you sure you want to <b>quit</b>?</p>',
	      type: 'warning',
	      showCancelButton: true,
	      confirmButtonColor: '#3085d6',
	      cancelButtonColor: '#d33',
	      confirmButtonText: 'Ya',
	      cancelButtonText: 'Batal'
	    }).then(function(result){
	    	if(result.value == true){
		    	location.replace("<?php echo base_url('login/keluar');?>");
		    }
		});
	});

	$('.modal').on('hidden.bs.modal', function () {
	    $(body).css("padding-right","0px");
	});

	function myFunction(x) {
		if (x.matches) { // If media query matches
			var prevScrollpos = window.pageYOffset;
			window.onscroll = function() {
			var currentScrollPos = window.pageYOffset;
			if (prevScrollpos < currentScrollPos) {
				$('.navbar').attr('style','top:-50px;transition: top 0.5s;');
			} else {
				$('.navbar').attr('style','top:0;transition: top 0.5s;');
			}
			prevScrollpos = currentScrollPos;
			}
		}
	}

	var x = window.matchMedia("(max-width: 768px)")
	myFunction(x) // Call listener function at run time
	x.addListener(myFunction) // Attach listener function on state	