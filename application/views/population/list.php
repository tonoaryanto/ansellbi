<div class="row">
  <div class="col-sm-12">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class=""><a href="<?php echo base_url('population/input_data');?>" class="btn btn-sm btn-success" title="Input Population"><i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Input Population</span></a></li>
        <li class="<?php if($this->uri->segment(2) == 'mortality'){ echo 'active';} ?>"><a href="<?php echo base_url('population/mortality') ?>">Mortality</a></li>
        <li class="<?php if($this->uri->segment(2) == 'death'){ echo 'active';} ?>"><a href="<?php echo base_url('population/death') ?>">Death</a></li>
        <li class="<?php if($this->uri->segment(2) == 'culling'){ echo 'active';} ?>"><a href="<?php echo base_url('population/culling') ?>">Culling</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active">
          <div id="boxoption_body" class="box-body">
            <div class="row" style="padding-left: 10px;padding-right: 10px">
              <div class="col-sm-2">
                <p><label>Data House : </label></p>
                <div class="form-group">
                  <select class="form-control" id="optionselect_kandang" style="width: 100%">
                    <option disabled selected>-Select Data-</option>
                  </select>
                </div>
              </div>
              <div class="col-sm-10" id="boxteks">
                <p style="padding: 10px;"></p>
                <p style="padding: 10px;color:#aaa;">Please fill in the house data before starting</p>
              </div>
              <div class="col-sm-2" id="boxperiod" style="display: none;">
                <p><label>Flock : </label></p>
                <div class="form-group">
                  <input type="number" class="form-control" name="periode" id="periode">
                </div>
              </div>
              <div class="col-sm-8" style="display:none;" id="boxdate"> 
                <p><label>Filter by Growday :</label></p>
                <div class="form-group col-sm-4">
                  <div class="input-group">
                    <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Start</span>
                    <input type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;" class="form-control pull-right datepicker" name="tanggal_dari" id="tanggal_dari">
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <div class="input-group">
                    <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">End</span>
                    <input type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;" class="form-control pull-right datepicker" name="tanggal_sampai" id="tanggal_sampai">
                  </div>
                </div>
                <div class="form-group col-sm-4">
                  <button class="btn btn-default" onclick="grafik()">Filter</button>
                  <button class="btn btn-danger" onclick="allprint()">Print</button>
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
    <div class="box" id="boxtabel" style="display: none;">
      <div class="box-body">
        <div id="tglresponse" class="table-responsive">
        </div>
      </div>
    </div>
  </div>
</div>
