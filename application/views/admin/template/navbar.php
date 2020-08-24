<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar sidebar-mystyle">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="<?php if(isset($head1) and $head1 == 'Data Farm'){echo 'active menu-open';}?>">
          <a href="<?php echo base_url('admin/farm');?>">
            <i class="fa fa-file"></i>
            <span>Data Farm</span>
          </a>
        </li>
        <li class="<?php if(isset($head1) and $head1 == 'User Login'){echo 'active menu-open';}?>">
          <a href="<?php echo base_url('admin/userlogin');?>">
            <i class="fa fa-user"></i>
            <span>User Login</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
