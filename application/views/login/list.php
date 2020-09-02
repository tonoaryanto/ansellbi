<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $this->konfigurasi->set('nama_web');?> | Log in</title>
  <link rel="shortcut icon" href="<?php echo base_url();?>assets/img/icon.png">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?php echo base_url();?>assets/plugins/iCheck/square/blue.css">

  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/sweetalert/dist/sweetalert2.min.css">

  <link rel="stylesheet" href="<?php echo base_url('get_source/my_style/css/login-css.css');?>">
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background: #ded2d20d">
<div id="particles-js" style="position: absolute;z-index: -1;width: 100%;height: 100%"></div>
<div class="login-box" style="margin-top: 100px">
  <div class="login-logo">
    <a href="<?php echo $this->konfigurasi->set('link_footer'); ?>"><img src="<?php echo base_url();?>assets/img/icontext.png" style="width: 200px"></a>
  </div>
  <!-- /.login-logo -->
  <div id="cnotif"></div>
  <div class="login-box-body">
    <p class="login-box-msg" style="color:#505050;">Please fill in the username and password to enter the system.</p>

    <form id="form-login">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
    </form>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-12 text-center">
          <button type="submit" style="background:#860000;" class="btn btn-block btn-flat" onclick="masuk();">Enter</button>
        </div>
        <!-- /.col -->
      </div>
    <br>

  </div>
    <div class="social-auth-links text-center" style="color: #101010;margin-top: 25px">
      <strong>Copyright &copy; 2008-<?php echo date('Y');?> <a href="<?php echo $this->konfigurasi->set('link_footer'); ?>"><?php echo $this->konfigurasi->set('judul_footer'); ?></a>.</strong> All rights
      reserved.
    </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
  <!-- particles.js container --> 
  <!-- particles.js lib - https://github.com/VincentGarreau/particles.js -->
  <script src="assets/custom/js/particles.min.js"></script>
  <script type="text/javascript">
  particlesJS("particles-js", {"particles":{"number":{"value":80,"density":{"enable":true,"value_area":800}},"color":{"value":"#ef0000"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.5,"random":false,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":200,"color":"#ffbbbb","opacity":0.5,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"repulse"},"onclick":{"enable":true,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":false});
  </script>
  <!-- end::particles.js --> 
<!-- jQuery 3 -->
<script src="<?php echo base_url();?>assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url();?>assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="<?php echo base_url();?>assets/plugins/iCheck/icheck.min.js"></script>
<script src="<?php echo base_url(); ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url('get_source/my_style/js/login-js.js');?>"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>
</body>
</html>
