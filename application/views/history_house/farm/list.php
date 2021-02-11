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
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
      <li class="<?php if($this->uri->segment(4) == 'temperature'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/temperature' ?>">Temperature</a></li>
      <li class="<?php if($this->uri->segment(4) == 'humidity'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/humidity' ?>">Humidity</a></li>
      <li class="<?php if($this->uri->segment(4) == 'wind'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/wind' ?>">Wind Speed</a></li>
      <li class="<?php if($this->uri->segment(4) == 'feed'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/feed' ?>">Feed</a></li>
      <li class="<?php if($this->uri->segment(4) == 'water'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/water' ?>">Water</a></li>
      <li class="<?php if($this->uri->segment(4) == 'pressure'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/pressure' ?>">Static Pressure</a></li>
      <li class="<?php if($this->uri->segment(4) == 'fan'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/fan' ?>">Fan Speed</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active">
          <div id="boxoption_body" class="box-body">
            <div class="row" style="padding-left: 10px;padding-right: 10px">
                <div class="col-sm-12">
                  <p>
                    <div class="radio">
                      <label style="visibility: hidden;">
                        <input type="radio" name="optiongrow" id="optiongrow" value="option1" checked="">
                        Filter by Grow Day :
                      </label>
                    </div>
                  </p>
                  <div class="form-group col-sm-2">
                    <label>Flock</label>
                    <div class="input-group">
                      <input name="val_periode" class="form-control" id="inputperiode" style="border-radius:4px;" type="number" min="1" placeholder="-Masukan periode-" value="<?php echo $iniperiode; ?>">
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                  <label>Start Date & Growday</label>
                    <div class="input-group">
                      <input name="tgl1" onchange="changetgl(1);" class="form-control" value="<?php echo date_format(date_create($initgl1),"Y-m-d"); ?>" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
                      <span class="input-group-addon" style="padding:0px;">
                        <?php $t1 = date_format(date_create($initgl1),"H:i");?>
                        <select onchange="changetgl(1);" name="time1" class="form-control select2" style="min-width: 90px;height:34px;border-radius:5px;">
                          <option <?php if($t1 == "00:00"){echo "selected";} ?> value="00:00">00:00</option>
                          <option <?php if($t1 == "01:00"){echo "selected";} ?> value="01:00">01:00</option>
                          <option <?php if($t1 == "02:00"){echo "selected";} ?> value="02:00">02:00</option>
                          <option <?php if($t1 == "03:00"){echo "selected";} ?> value="03:00">03:00</option>
                          <option <?php if($t1 == "04:00"){echo "selected";} ?> value="04:00">04:00</option>
                          <option <?php if($t1 == "05:00"){echo "selected";} ?> value="05:00">05:00</option>
                          <option <?php if($t1 == "06:00"){echo "selected";} ?> value="06:00">06:00</option>
                          <option <?php if($t1 == "07:00"){echo "selected";} ?> value="07:00">07:00</option>
                          <option <?php if($t1 == "08:00"){echo "selected";} ?> value="08:00">08:00</option>
                          <option <?php if($t1 == "09:00"){echo "selected";} ?> value="09:00">09:00</option>
                          <option <?php if($t1 == "10:00"){echo "selected";} ?> value="10:00">10:00</option>
                          <option <?php if($t1 == "11:00"){echo "selected";} ?> value="11:00">11:00</option>
                          <option <?php if($t1 == "12:00"){echo "selected";} ?> value="12:00">12:00</option>
                          <option <?php if($t1 == "13:00"){echo "selected";} ?> value="13:00">13:00</option>
                          <option <?php if($t1 == "14:00"){echo "selected";} ?> value="14:00">14:00</option>
                          <option <?php if($t1 == "15:00"){echo "selected";} ?> value="15:00">15:00</option>
                          <option <?php if($t1 == "16:00"){echo "selected";} ?> value="16:00">16:00</option>
                          <option <?php if($t1 == "17:00"){echo "selected";} ?> value="17:00">17:00</option>
                          <option <?php if($t1 == "18:00"){echo "selected";} ?> value="18:00">18:00</option>
                          <option <?php if($t1 == "19:00"){echo "selected";} ?> value="19:00">19:00</option>
                          <option <?php if($t1 == "20:00"){echo "selected";} ?> value="20:00">20:00</option>
                          <option <?php if($t1 == "21:00"){echo "selected";} ?> value="21:00">21:00</option>
                          <option <?php if($t1 == "22:00"){echo "selected";} ?> value="22:00">22:00</option>
                          <option <?php if($t1 == "23:00"){echo "selected";} ?> value="23:00">23:00</option>
                        </select>
                      </span>
                      <span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
                      <input onchange="changegrow(1);" min="-1" name="growval1" value="<?php echo $inigrow; ?>" type="number" class="form-control" style="border-style:none;height: 32px;border-radius: 4px;min-width: 90px;">
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                    <label>End Date & Growday</label>
                    <div class="input-group">
                      <input name="tgl2" onchange="changetgl(2);" class="form-control" value="<?php echo date_format(date_create($initgl2),"Y-m-d"); ?>" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
                      <span class="input-group-addon" style="padding:0px;">
                        <?php $t2 = date_format(date_create($initgl2),"H:i");?>
                        <select onchange="changetgl(2);" name="time2" class="form-control select2" style="min-width: 90px;height:34px;border-radius:5px;">
                          <option <?php if($t2 == "00:00"){echo "selected";} ?> value="00:00">00:00</option>
                          <option <?php if($t2 == "01:00"){echo "selected";} ?> value="01:00">01:00</option>
                          <option <?php if($t2 == "02:00"){echo "selected";} ?> value="02:00">02:00</option>
                          <option <?php if($t2 == "03:00"){echo "selected";} ?> value="03:00">03:00</option>
                          <option <?php if($t2 == "04:00"){echo "selected";} ?> value="04:00">04:00</option>
                          <option <?php if($t2 == "05:00"){echo "selected";} ?> value="05:00">05:00</option>
                          <option <?php if($t2 == "06:00"){echo "selected";} ?> value="06:00">06:00</option>
                          <option <?php if($t2 == "07:00"){echo "selected";} ?> value="07:00">07:00</option>
                          <option <?php if($t2 == "08:00"){echo "selected";} ?> value="08:00">08:00</option>
                          <option <?php if($t2 == "09:00"){echo "selected";} ?> value="09:00">09:00</option>
                          <option <?php if($t2 == "10:00"){echo "selected";} ?> value="10:00">10:00</option>
                          <option <?php if($t2 == "11:00"){echo "selected";} ?> value="11:00">11:00</option>
                          <option <?php if($t2 == "12:00"){echo "selected";} ?> value="12:00">12:00</option>
                          <option <?php if($t2 == "13:00"){echo "selected";} ?> value="13:00">13:00</option>
                          <option <?php if($t2 == "14:00"){echo "selected";} ?> value="14:00">14:00</option>
                          <option <?php if($t2 == "15:00"){echo "selected";} ?> value="15:00">15:00</option>
                          <option <?php if($t2 == "16:00"){echo "selected";} ?> value="16:00">16:00</option>
                          <option <?php if($t2 == "17:00"){echo "selected";} ?> value="17:00">17:00</option>
                          <option <?php if($t2 == "18:00"){echo "selected";} ?> value="18:00">18:00</option>
                          <option <?php if($t2 == "19:00"){echo "selected";} ?> value="19:00">19:00</option>
                          <option <?php if($t2 == "20:00"){echo "selected";} ?> value="20:00">20:00</option>
                          <option <?php if($t2 == "21:00"){echo "selected";} ?> value="21:00">21:00</option>
                          <option <?php if($t2 == "22:00"){echo "selected";} ?> value="22:00">22:00</option>
                          <option <?php if($t2 == "23:00"){echo "selected";} ?> value="23:00">23:00</option>
                        </select>
                      </span>
                      <span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
                      <input onchange="changegrow(2);" min="-1" name="growval2" value="<?php echo $inigrow; ?>" type="number" class="form-control" style="border-style:none;height: 32px;border-radius: 4px;min-width: 90px;">
                      </span>
                    </div>
                  </div>
                  <div class="form-group col-sm-2">
                    <label>&nbsp;</label>
                    <div class="input-group">
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
