<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- /.content-wrapper -->
<?php $this->load->view('template/modalreset'); ?>
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
<script src="<?php echo base_url(); ?>assets/sweetalert/dist/sweetalert2.min.js"></script>
<script src="<?php echo base_url('get_source/my_style/js/template-js.js');?>"></script>
<script src="<?php echo base_url('get_source/my_style/js/template-reset_js.js');?>"></script>
<?php if(isset($jsadd)){ $this->load->view($jsadd); } ?>
</html>
