<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php if($cekgrowchange == 1){ ?>
<div class="modal fade" id="ckgwd" data-ini="<?php echo $cekgrowchange?>">
  <div class="modal-dialog">
    <div class="modal-content" style="background:#0000;">
      <div class="modal-header">
        <a class="close btn btn-default btn-sm" aria-label="Close" href="../..">Back</a>
        <h4 class="modal-title" style="color:#fff;">Warning!!</h4>
      </div>    
      <div class="modal-body" style="color:#fff;">
        <h4>
        There is a growday difference between the ABI system and the controller. Please adjust it first before starting this process. please press button <span class="btn btn-default btn-xs">Back</span> to return. After that go to the Growday change menu
        <br>
        <br>
        <br>
        <center>
        <img style="opacity:80%;" src="<?php echo base_url(); ?>assets/img/growchange.png">
        </center>
        </h4>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="row">
  <div class="col-sm-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Options</h3>
        <div class="box-tools pull-right">
        </div>
      </div>
      <div id="boxoption_body" class="box-body">
        <div class="row" style="padding-left: 10px;padding-right: 10px">
            <div class="col-sm-12">
              <p>
                  <label style="visibility: hidden;">
                    <input type="radio" name="optiongrow" id="optiongrow" value="option1" checked="">
                    Filter by Grow Day :
                  </label>
              </p>

              <?php if($inigrow < 30){
                $inigrow2 = $inigrow;
                $inigrow = 1;
              }else{
                $inigrow2 = $inigrow;
                $inigrow = $inigrow - 30;
              }
              ?>

              <div class="form-group col-sm-3">
                <div class="input-group">
                  <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Periode</span>
                  <input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-top-right-radius:4px;border-bottom-right-radius:4px;" type="number" min="1" placeholder="-Masukan periode-" value="<?php echo $iniperiode; ?>">
                </div>
              </div>
              <div class="form-group col-sm-3">
                <div class="input-group">
                  <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Start Grow Day</span>
                  <input class="form-control" type="number" min="-1" name="growval" value="<?php echo $inigrow; ?>" style="border-top-right-radius:4px;border-bottom-right-radius:4px;">
                </div>
              </div>
              <div class="form-group col-sm-3">
                <div class="input-group">
                  <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">End Grow Day</span>
                  <input class="form-control" type="number" min="-1" name="growval2" value="<?php echo $inigrow2; ?>" style="border-top-right-radius:4px;border-bottom-right-radius:4px;">
                </div>
              </div>
              <div class="form-group col-sm-3">
              <button class="btn btn-default" onclick="reload_grafik();">Reload</button>
              <button class="btn btn-danger" onclick="allprint()">Print</button>
                <input type="hidden" name="order" value="1" min="1">
              </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div id="inihtml"></div>
</div>

<div class="row">
  <div class="col-sm-12">
    <div class="box" id="boxtabel">
      <div class="box-body">
        <div id="tglresponse" class="table-responsive">
        </div>
      </div>
    </div>
  </div>
</div>
