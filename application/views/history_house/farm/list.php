<div class="row">
  <div class="col-sm-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
      <li class="<?php if($this->uri->segment(4) == 'temperature'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/temperature' ?>">Temperature</a></li>
      <li class="<?php if($this->uri->segment(4) == 'humidity'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/humidity' ?>">Humidity</a></li>
      <li class="<?php if($this->uri->segment(4) == 'wind'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/wind' ?>">Wind Speed</a></li>
      <li class="<?php if($this->uri->segment(4) == 'feed'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/feed' ?>">Feed Sensor</a></li>
      <li class="<?php if($this->uri->segment(4) == 'water'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/water' ?>">Water Sensor</a></li>
      <li class="<?php if($this->uri->segment(4) == 'pressure'){ echo 'active';} ?>"><a href="<?php echo base_url('history_house/farm/').$idfarm.'/pressure' ?>">Static Pressure</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active">
          <div id="boxoption_body" class="box-body">
            <div class="row" style="padding-left: 10px;padding-right: 10px">
                <div class="col-sm-12">
                  <p>
                    <div class="radio">
                      <label>
                        <input type="radio" name="optiongrow" id="optiongrow" value="option1" checked="">
                        Filter by Grow Day :
                      </label>
                    </div>
                  </p>
                  <div class="form-group col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Periode</span>
                      <input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-top-right-radius:4px;border-bottom-right-radius:4px;" type="number" min="1" placeholder="-Masukan periode-" onchange="reload_grafik()" value="<?php echo $iniperiode; ?>">
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                    <div class="input-group">
                      <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Grow Day</span>
                      <input class="form-control" type="number" min="-1" name="growval" value="<?php echo $inigrow; ?>" style="border-top-right-radius:4px;border-bottom-right-radius:4px;" onchange="reload_grafik()">
                    </div>
                  </div>
                  <div class="form-group col-sm-4">
                    <button class="btn btn-default" onclick="reload_grafik();">Reload</button>
                  </div>
                </div>
                <!--div class="col-sm-6">
                  <p>
                    <div class="radio">
                      <label>
                        <input type="radio" name="optiongrow" id="optiongrow" value="option1">
                        Filter by Date:
                      </label>
                    </div>
                  </p>
                  <div class="form-group col-sm-6">
                    <div class="input-group">
                      <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Start Date</span>
                      <input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-top-right-radius:4px;border-bottom-right-radius:4px;padding: 0px 5px;" type="date" min="1" placeholder="-Masukan periode-" onchange="" value="<?php echo $iniperiode; ?>">
                    </div>
                  </div>
                </div-->
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
