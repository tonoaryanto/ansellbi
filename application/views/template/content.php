<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <span id="head1"><?php echo $txthead1?></span>
        <small><?php if(isset($txthead2)){echo $txthead2;}?></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
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
