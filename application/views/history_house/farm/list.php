<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
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
                      <input class="form-control" type="number" min="-1" name="growval2" value="<?php echo $inigrow; ?>" style="border-top-right-radius:4px;border-bottom-right-radius:4px;">
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
