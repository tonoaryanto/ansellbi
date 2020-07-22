<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->konfigurasi->set('nama_web');?> | Collapsed Sidebar Layout</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- MY Style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/custom/css/my_style.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body onresize="rsize();" class="skin-blue sidebar-collapse sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a class="logo" href="<?php echo base_url();?>assets/index2.html">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>MPS</b></span>

      <span class="logo-lg"><b><?php echo $this->konfigurasi->set('nama_web');?></b></span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
		<a href="#" class="hidden-lg sidebar-toggle" id="menu-push" data-menu="close">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		</a>

		<span class="hidden-xs logo" style="min-width: 200px;text-align:left;background: none;">
		<a href="#" style="color: #fff;font-size: 16px;"><b><?php echo $this->konfigurasi->set('nama_web');?></b></a>    		
		</span>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-lg" style="font-size: 16px"><i class="fa fa-gear"></i>
          	  </span>
              <span class="hidden-xs">
              	<b>Alexander Pierce</b>!
              	&nbsp;&nbsp;
              	<span style="font-size: 16px"><i class="fa fa-gear"></i></span>
              </span>
            </a>
            <style type="text/css" media="only screen and (max-width: 768px)">.dropmenu{right: 0px !important;}.dropmenu > li > a {color: #555 !important;}.dropmenu > li > a:hover {color: #fff !important;}</style>
            <ul class="dropdown-menu dropmenu" style="width: 100px;">
            	<li class="hidden-lg"><p style="text-align: center;padding:5px 10px;border-bottom: 1px solid #ddd;"><b>Alexander Pierce</b>!</p></li>
				<li><a href="#"><i class="fa fa-users"></i> Profil</a></li>
				<li><a href="#"><i class="fa fa-power-off"></i> Log out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar sidebar-mystyle">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview active">
          <a href="#">
            <i class="fa fa-files-o"></i>
            <span>Layout Options</span>
          </a>
          <ul class="treeview-menu">
            <li><a href="top-nav.html"><i class="fa fa-circle-o"></i> Top Navigation</a></li>
            <li><a href="boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
            <li><a href="fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
            <li class="active"><a href="collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a>
            </li>
          </ul>
        </li>
        <li>
          <a href="../mailbox/mailbox.html">
            <i class="fa fa-envelope"></i> <span>Mailbox</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-yellow">12</small>
              <small class="label pull-right bg-green">16</small>
              <small class="label pull-right bg-red">5</small>
            </span>
          </a>
        </li>
        <li class="header">LABELS</li>
        <li><a href="#"><i class="fa fa-circle-o text-red"></i> <span>Important</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-yellow"></i> <span>Warning</span></a></li>
        <li><a href="#"><i class="fa fa-circle-o text-aqua"></i> <span>Information</span></a></li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Sidebar Collapsed
        <small>Layout with collapsed sidebar on load</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Layout</a></li>
        <li class="active">Collapsed Sidebar</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box">
        <div class="box-body">
          <div class="row">
            <form id="form-aksi">
              <div class="col-sm-3 hidden-xs"></div>
              <div class="col-sm-3 col-xs-6">
                <div style="margin-top: 15px" class="form-group">
                  <input type="file" name="userfile" size="20" class="pfile" style="">
                </div>
              </div>
              <div class="col-sm-3 col-xs-6" style="text-align: center;">
                <div style="margin-top:10px" class="form-group">
                  <div class="col-sm-12 controls">
                    <button href="javascript:void(0);" type="submit" class="btn btn-success">Upload</button>
                  </div>
                </div>
              </div>
              <div class="col-sm-3 hidden-xs"></div>
            </form>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
              <p>Status : <span id="textprogres"></span></p>
              <div class="progress">
                <div id="barprogres" class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                </div>
              </div>
            </div>
            <div class="col-sm-2"></div>
          </div>
        </div>
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer" style="background: #ecf0f5;border: none;">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.4.0
    </div>
    <strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    reserved.
  </footer>

</div>
<!-- ./wrapper -->

<!-- jQuery 3 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="<?php echo base_url();?>assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="<?php echo base_url();?>assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url();?>assets/dist/js/adminlte.min.js"></script>

<script type="text/javascript">

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

</script>
</html>
