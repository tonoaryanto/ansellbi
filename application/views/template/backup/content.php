<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="content-wrapper">
    <div class="container">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>
          <?php echo $head1?>
          <small><?php if(isset($head2)){echo $head2;}?></small>
        </h1>
        <ol class="breadcrumb">
        <li><a href="<?php echo base_url('admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <?php if(isset($link1)){ ?><li><a href="<?php if($link1 == '#link'){echo '#';}else{echo base_url($link1);} ?>"><?php echo $head1;?></a></li><?php } ?>
        <?php if(isset($link2)){ ?><li><a href="<?php if($link2 == '#link'){echo '#';}else{echo base_url($link2);} ?>"><?php echo $head2;?></a></li><?php } ?>
        <?php if(isset($link3)){ ?><li><a href="<?php if($link3 == '#link'){echo '#';}else{echo base_url($link3);} ?>"><?php echo $head3;?></a></li><?php } ?>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <?php if($isi){$this->load->view($isi);}?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.container -->
  </div>