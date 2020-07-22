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
<body onresize="rsize();" class="hold-transition skin-blue layout-top-nav">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="<?php echo base_url();?>" class="navbar-brand"><b><?php echo $this->konfigurasi->set('nama_web');?></b></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Upload <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li class="<?php if($head1 == 'Upload Riwayat House'){echo 'active';} ?>"><a href="<?php echo base_url('get_excel');?>">Histori House</a></li>
                <li class="<?php if($head1 == 'Upload Riwayat Alarm'){echo 'active';} ?>"><a href="<?php echo base_url('get_excel/alarm');?>">Histori Alarm</a></li>
              </ul>
            </li>
          </ul>
        </div>

        <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <span class="hidden-sm hidden-md hidden-lg" style="font-size: 16px"><i class="fa fa-user"></i>
              </span>
              <span class="hidden-xs">
                <b><?php echo $this->session->userdata('nama_user');?></b> 
                &nbsp;&nbsp;
                <span style="font-size: 16px"><i class="fa fa-user"></i></span>
              </span>
            </a>
            <ul class="dropdown-menu dropmenu" style="width: 100px;">
              <li class="hidden-lg"><p style="text-align: center;padding:5px 10px;border-bottom: 1px solid #ddd;"><b><?php echo $this->session->userdata('nama_user');?></b> </p></li>
              <?php if (isset($navbar)) {$this->load->view($navbar);} ?>
              <li><a href="#" id="resetbtn"><i class="fa fa-refresh"></i> Reset</a></li>
              <li><a href="#" id="logout"><i class="fa fa-power-off"></i> Log out</a></li>
            </ul>
          </li>
        </ul>
      </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>

