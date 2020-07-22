<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->konfigurasi->set('nama_web');?> <?php if(isset($menu_head)){echo '| '.$menu_head;} ?></title>
  <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/icon.png">
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
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/sweetalert/dist/sweetalert2.min.css">
  <script type="text/javascript">var base_url = "<?php echo base_url()?>"</script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <?php if(isset($cssadd)){$this->load->view($cssadd);}?>
  <?php if(isset($css)){ ?>
  <!-- MY Style -->
  <link rel="stylesheet" href="<?php echo base_url('get_source/my_style/'.$css);?>">
  <?php } ?>
  <link rel="stylesheet" href="<?php echo base_url('get_source/my_style/css/template-css.css');?>">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<!-- ADD THE CLASS sidebar-collapse TO HIDE THE SIDEBAR PRIOR TO LOADING THE SITE -->
<body onresize="rsize();" class="skin-blue sidebar-collapse sidebar-mini no-right">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a class="logo" href="<?php echo base_url();?>">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><span style="padding:7px 4px;background: #fff;border-radius: 7px;"><img src="<?php echo base_url();?>assets/img/icon.png" width="30px"></span></span>

      <span class="logo-lg"><b><?php echo $this->konfigurasi->set('nama_web');?></b></span>

    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
		<a href="#" class="hidden-sm hidden-md hidden-lg sidebar-toggle" id="menu-push" data-menu="close">
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
              <span class="hidden-sm hidden-md hidden-lg" style="font-size: 16px"><i class="fa fa-gear"></i>
          	  </span>
              <span class="hidden-xs">
              	<b><?php echo $this->session->userdata('nama_user');?></b> 
              	&nbsp;&nbsp;
              	<span style="font-size: 16px"><i class="fa fa-gear"></i></span>
              </span>
            </a>
            <ul class="dropdown-menu dropmenu" style="width: 100px;">
            	<li class="hidden-lg"><p style="text-align: center;padding:5px 10px;border-bottom: 1px solid #ddd;"><b><?php echo $this->session->userdata('nama_user');?></b> </p></li>
              <!--li><a href="#" id="resetbtn"><i class="fa fa-refresh"></i> Reset</a></li-->
              <li><a href="#" id="logout"><i class="fa fa-power-off"></i> Log out</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </nav>
  </header>
