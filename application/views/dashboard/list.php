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
          <div class="col-sm-7">
            <p><label>Data Grafik : </label></p>
              <div class="form-group col-sm-3">
                <select name="val_kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                  <option disabled selected>-Pilih Data-</option>
                </select>
              </div>
              <div class="form-group col-sm-3">
                <input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Masukan periode-" onchange="selectdata()">
              </div>
              <div class="form-group col-sm-3">
                <select name="val_data" class="form-control" id="optionselect" style="width: 100%;border-radius: 3px;">
                  <option disabled selected>-Pilih Kandang Dahulu-</option>
                </select>
              </div>
            <div class="form-group col-sm-3">
              <button class="btn btn-default" onclick="grafik();loadtabel();">Apply</button>
            </div>
          </div>
          <div class="col-sm-5">
            <p><label>Filter Berdasarkan Grow Day :</label></p>
            <div class="form-group col-sm-6">
                <input class="form-control" type="number" min="-1" name="hourdari" value="-1" style="border-radius:3px;">
            </div>
            <div class="form-group col-sm-6">
              <button class="btn btn-default" onclick="filter();">Filter</button>
              <button class="btn btn-success" id="btnprint">Print</button>
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
          <h3 class="box-title" id="titlegrafik"><span style="color: #aaa;">-Set Options Terlebih Dahulu-</span></h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="box-body">
          <div id="inicanvas">-Set Options Terlebih Dahulu-</div>
          <br><br>
          <div id="tglresponse" class="table-responsive" style="visibility: hidden;">
          </div>
        </div>
      </div>
    </div>
  </div>
