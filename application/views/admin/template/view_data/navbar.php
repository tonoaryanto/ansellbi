<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar sidebar-mystyle">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Histori House'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-bar-chart"></i>
            <span>Histori House</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Histori House' AND isset($head2) AND $head2 == 'Day'){echo 'active';}?>">
              <a href="<?php echo base_url('histori_house/day')?>"><i class="fa fa-circle-o"></i> <span>DAY</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Histori House' AND isset($head2) AND $head2 == 'Hour'){echo 'active';}?>">
              <a href="<?php echo base_url('histori_house/hour')?>"><i class="fa fa-circle-o"></i> <span>HOUR</span></a>
            </li>
          </ul>
        </li>
        <li class="<?php if(isset($head1) and $head1 == 'Histori Alarm'){echo 'active';}?>">
          <a href="<?php echo base_url('histori_alarm')?>"><i class="fa fa-clock-o"></i> <span>Histori Alarm</span></a>
        </li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Report'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-file"></i>
            <span>Report</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Report' AND isset($head2) AND $head2 == 'Histori House (DAY)'){echo 'active';}?>">
              <a href="<?php echo base_url('report/histori_house_day');?>"><i class="fa fa-circle-o"></i> <span>Histori House (DAY)</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Report' AND isset($head2) AND $head2 == 'Histori House (HOUR)'){echo 'active';}?>">
              <a href="<?php echo base_url('report/histori_house_hour');?>"><i class="fa fa-circle-o"></i> <span>Histori House (HOUR)</span></a>
            </li>
          </ul>
        </li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Upload'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-upload"></i>
            <span>Upload</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Upload' AND isset($head2) AND $head2 == 'Upload Data House'){echo 'active';}?>">
              <a href="<?php echo base_url('get_excel');?>"><i class="fa fa-circle-o"></i> <span>Upload Data House</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Upload' AND isset($head2) AND $head2 == 'Upload Data Alarm'){echo 'active';}?>">
              <a href="<?php echo base_url('get_excel/alarm');?>"><i class="fa fa-circle-o"></i> <span>Upload Data Alarm</span></a>
            </li>
          </ul>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
