<style>.bg-hot{background:#ff0000a3;}</style>
 
<div class="row">
<div class="col-sm-12">
    <div class="box" id="boxtabel">
      <div class="box-body">
      <style>
      .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th{padding:5px !important;}
      .table-striped > tbody > tr:nth-of-type(2n+1){background:#e1eef2 !important;}
      </style>
        <div id="tglresponse" class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
            <tr>
              <th>Name</th>
              <?php for ($i=0; $i < count($farm); $i++) { 
                $rskt = explode(" ",$farm[$i]->{'nama_kandang'});
                $skt = strtoupper(str_split($rskt[0])[0]);
                if(isset($rskt[1])){$skt .= strtoupper(str_split($rskt[1])[0]);}
                if(count($rskt) > 2){$skt .= strtoupper(str_split(end($rskt))[0]);}
                ?>
              <th><a href="<?php echo base_url('history_house/farm/').$farm[$i]->{'id'}; ?>" class="btn btn-success btn-xs" title="<?php echo $farm[$i]->{'nama_kandang'}; ?>"><span style="font-size:14px;"><?php echo $skt; ?></span><?php if(count($farm) < 11){ ?><span style="font-weight:bold;">&nbsp;<?php echo $farm[$i]->{'nama_kandang'}; ?></span> <?php } ?></a></th>
              <?php } ?>
            </tr>
            </thead>
            <tbody>
            <tr>
            <td style="width:15%;">Flock</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt0_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
            <td style="width:15%;">Growday</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt1_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
            <td style="width:15%;">Date</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt2_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
            <td style="width:15%;">Time</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt3_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
            <td style="width:15%;">Required Temp. (°C)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt4_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Current Temp. (°C)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt5_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Out Temp. (°C)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt6_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Humidity (%)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt7_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Fan Speed (%)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt8_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Required Speed (m/s)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt9_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Wind Speed (m/s)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt10_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Feed Cons. (Kg)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt11_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Water Cons. (Liter)</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt12_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Static Pressure</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt13_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Silo 1</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt14_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            <tr>
              <td>Silo 2</td>
              <?php for ($i=0; $i < count($farm); $i++) { ?>
              <td id="dtrt15_<?php echo $i; ?>"><a href="javascript:void(0);" title="Loading. . .">...</a></td>
              <?php } ?>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
