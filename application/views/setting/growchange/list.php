<div class="row">
  <div class="col-sm-12">
    <div id="boxoption" class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Form Change Growday</h3>
        <div class="box-tools pull-right">
        </div>
      </div>
      <div id="boxoption_body" class="box-body" style="">
        <div class="row" style="padding-left: 10px;padding-right: 10px;padding-bottom: 30px;">
          <div class="col-sm-6">
            <div class="row">
              <div class="form-group col-sm-6">
                <label>Data House : </label>
                <select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                  <option disabled selected>-Select Data-</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-4">
                <label>Star Date : </label>
                <input type="date" name="startgl" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-4">
                <label>Star Growday : </label>
                <input style="background:#0000;border-radius:5px;" type="text" readonly name="stargrow" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-4">
                <label>End Date : </label>
                <input type="date" name="endtgl" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-4">
                <label>End Growday : </label>
                <input style="background:#0000;border-radius:5px;" type="text" readonly name="endgrow" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-5">
                <label>Flock : </label>
                <input style="background:#0000;border-radius:5px;" type="text" readonly name="stargrow" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
              <button class="btn btn-primary" onclick="save()">Save</button>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="row">
              <div class="col-sm-10" id="boxteks" style="display: none;">
                <p style="padding: 10px;"></p>
                <p style="padding: 10px;color:#aaa;">Please fill in the house data before starting</p>
                </div>
            </div>
            <div class="row">
              <div class="col-sm-12">
                <label>Info House</label>
                <div style="border-style: solid;border-width: thin;border-radius:5px;border-color:#ccc;padding:10px">
                  <table border="0" style="width: 100%;">
                  <tr><td style="width:30%;"><u>Last Record Growday</u></td><td style="width:1%;"></td><td></td></tr>
                  <tr><td style="width:30%;">Date</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">&nbsp;</td><td style="width:1%;"></td><td></td></tr>
                  <tr><td style="width:30%;"><u>Change Growday Start</u></td><td style="width:1%;"></td><td></td></tr>
                  <tr><td style="width:30%;">Date</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">&nbsp;</td><td style="width:1%;"></td><td></td></tr>
                  <tr><td style="width:30%;"><u>Correction Data</u></td><td style="width:1%;"></td><td></td></tr>
                  <tr><td style="width:30%;">Date</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td><td></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td><td></td></tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
