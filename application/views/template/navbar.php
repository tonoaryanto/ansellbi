<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar sidebar-mystyle">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <!--li class="<?php if(isset($head1) and $head1 == 'Dashboard'){echo 'active menu-open';}?>">
          <a href="<?php echo base_url('dashboard')?>">
            <i class="fa fa-dashboard"></i>
            <span>Dashboard</span>
          </a>
        </li-->
        <li class="<?php if(isset($head1) and $head1 == 'History House'){echo 'active menu-open';}?>">
          <a href="<?php echo base_url('history_house')?>">
            <i class="fa fa-bank"></i>
            <span>History House</span>
          </a>
        </li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Population'){echo 'active menu-open';}?>">
          <a href="#">
          <i class="fa fa-bar-chart"></i>
            <span>Population</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Population' AND isset($head2) AND $head2 == 'History Population'){echo 'active';}?>">
              <a href="<?php echo base_url('population');?>"><i class="fa fa-circle-o"></i> <span>History Population</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Population' AND isset($head2) AND $head2 == 'Input Population'){echo 'active';}?>">
              <a href="<?php echo base_url('population/input_data');?>"><i class="fa fa-circle-o"></i> <span>Input Population</span></a>
            </li>
          </ul>
        </li>
        <li class="<?php if(isset($head1) and $head1 == 'Egg Counter'){echo 'active menu-open';}?>">
          <a href="<?php echo base_url('egg_counter');?>">
            <i class="ion ion-egg"></i>
            <span>Egg Counter</span>
          </a>
        </li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Egg Weight'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-cubes"></i>
            <span>Egg Weight</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Egg Weight' AND isset($head2) AND $head2 == 'History Egg Weight'){echo 'active';}?>">
              <a href="<?php echo base_url('egg_weight');?>"><i class="fa fa-circle-o"></i> <span>History Egg Weight</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Egg Weight' AND isset($head2) AND $head2 == 'Input Egg Weight'){echo 'active';}?>">
              <a href="<?php echo base_url('egg_weight/input_data');?>"><i class="fa fa-circle-o"></i> <span>Input Egg Weight</span></a>
            </li>
          </ul>
        </li>
        <li class="treeview <?php if(isset($head1) and $head1 == 'Body Weight'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-balance-scale"></i>
            <span>Body Weight</span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php if(isset($head1) AND $head1 == 'Body Weight' AND isset($head2) AND $head2 == 'History Body Weight'){echo 'active';}?>">
              <a href="<?php echo base_url('body_weight');?>"><i class="fa fa-circle-o"></i> <span>History Body Weight</span></a>
            </li>
            <li class="<?php if(isset($head1) AND $head1 == 'Body Weight' AND isset($head2) AND $head2 == 'Input Body Weight'){echo 'active';}?>">
              <a href="<?php echo base_url('body_weight/input_data');?>"><i class="fa fa-circle-o"></i> <span>Input Egg weight</span></a>
            </li>
          </ul>
        </li>
        <!--li class="<?php if(isset($head1) and $head1 == 'History Alarm'){echo 'active';}?>">
          <a href="<?php echo base_url('history_alarm')?>"><i class="fa fa-clock-o"></i> <span>History Alarm</span></a>
        </li-->
        <li class="treeview <?php if(isset($head1) and $head1 == 'Report'){echo 'active menu-open';}?>">
          <a href="#">
            <i class="fa fa-file"></i>
            <span>Report</span>
          </a>
          <ul class="treeview-menu">
            <!--li class="<?php if(isset($head1) AND $head1 == 'Report' AND isset($head2) AND $head2 == 'History House (DAY)'){echo 'active';}?>">
              <a href="<?php echo base_url('report/history_house_day');?>"><i class="fa fa-circle-o"></i> <span>History House (DAY)</span></a>
            </li-->
            <li class="<?php if(isset($head1) AND $head1 == 'Report' AND isset($head2) AND $head2 == 'History House'){echo 'active';}?>">
              <a href="<?php echo base_url('report/history_house_hour');?>"><i class="fa fa-circle-o"></i> <span>History House</span></a>
            </li>
          </ul>
        </li>
        <!--li class="treeview <?php if(isset($head1) and $head1 == 'Upload'){echo 'active menu-open';}?>">
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
        </li-->
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
