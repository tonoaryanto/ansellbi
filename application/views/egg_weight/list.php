<div class="row">
  <div class="col-sm-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
          <li class=""><a href="<?php echo base_url('egg_weight/input_data');?>" class="btn btn-sm btn-success" title="Input Egg Weight"><i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Input Egg Weight</span></a></li>
          <li class="<?php if($this->uri->segment(3) == ''){ echo 'active';} ?>"><a href="<?php echo base_url('egg_weight') ?>">History</a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active">
              <div id="boxoption_body" class="box-body">
                <div class="row" style="padding-left: 10px;padding-right: 10px">
                  <div class="col-sm-2">
                    <div class="form-group">
                      <label>Data House : </label>
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
                    <div class="form-group">
                      <label>Flock : </label>
                      <input type="number" class="form-control" name="periode" id="periode" style="border-radius:4px;">
                    </div>
                  </div>
                  <div class="col-sm-8" style="display:none;" id="boxdate"> 
                    <div class="col-sm-4">
                      <label>Star Growday :</label>
                      <div class="form-group">
                        <div class="input-group">
                          <input name="tgl1" onchange="changetgl(1);" class="form-control" value="" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
                          <span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
                            <input onchange="changegrow(1);" min="1" type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;min-width: 80px;border: none;height: 32px;" class="form-control pull-right" name="tanggal_1" id="tanggal_1">
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label>End Growday :</label>
                      <div class="form-group">
                        <div class="input-group">
                          <input name="tgl2" onchange="changetgl(2);" class="form-control" value="" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
                          <span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
                            <input onchange="changegrow(2);" min="1" type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;min-width: 80px;border: none;height: 32px;" class="form-control pull-right" name="tanggal_2" id="tanggal_2">
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <label>&nbsp;</label>
                      <div class="form-group">
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
