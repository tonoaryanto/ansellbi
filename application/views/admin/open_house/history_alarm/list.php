<div class="row">
  <div class="col-sm-12">
    <div id="boxoption" class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Options</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="collapse"><i id="ioption" class="fa fa-minus"></i></button>
        </div>
      </div>
      <div id="boxoption_body" class="box-body">
        <div class="row" style="padding-left: 10px;padding-right: 10px">
          <div class="col-sm-12">
            <div class="form-group col-sm-3">
              <input class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Masukan periode-" value="<?php if($data_farm != ''){echo $data_farm['periode'];}else{echo '1';} ?>">
            </div>
            <div class="form-group col-sm-3">
              <div class="input-group">
                <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Dari</span>
                <input type="text" class="form-control pull-right datepicker" name="tanggal_dari" id="tanggal_dari" placeholder="<?php echo date('m/d/Y'); ?>" value="<?php echo date('m/d/Y'); ?>">
              </div>
            </div>
            <div class="form-group col-sm-3">
              <div class="input-group">
                <span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Sampai</span>
                <input type="text" class="form-control pull-right datepicker" name="tanggal_sampai" id="tanggal_sampai" placeholder="<?php echo date('m/d/Y'); ?>" value="<?php echo date('m/d/Y'); ?>">
              </div>
            </div>
            <div class="form-group col-sm-3">
              <button class="btn btn-default" onclick="data_alarm()">Apply</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

  <div class="row">
    <div class="col-sm-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title" id="titlegrafik"><span>Histori Alarm</span></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <div id="data_alarm">
          </div>
        </div>
      </div>
    </div>
  </div>
