<div class="row">
  <div class="col-sm-12">
    <div id="boxoption" class="box">
      <div class="box-header with-border">
        <h3 class="box-title">Setup Change Growday</h3>
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
                <input onchange="changedate()" type="date" name="startgl" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-3">
                <label>&nbsp;</label>
                <input readonly type="time" name="startime" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-4">
                <label>Star Growday : </label>
                <input onchange="changedate_end()" style="background:#0000;border-radius:5px;" type="text" name="stargrow" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-4">
                <label>End Date : </label>
                <input onchange="changedate_end()" type="date" name="endtgl" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-3">
                <label>&nbsp;</label>
                <input readonly type="time" name="endtime" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
              <div class="form-group col-sm-4">
                <label>End Growday : </label>
                <input style="border-radius:5px;" type="text" readonly name="endgrow" class="form-control" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-5">
                <label>Flock : </label>
                <input style="background:#0000;border-radius:5px;" type="text" name="flock" class="form-control" id="optionselect_kandang" style="width: 100%;line-height:unset;border-radius:5px;">
              </div>
            </div>
            <div class="row">
              <div class="form-group col-sm-12">
              <a href=".." class="btn btn-default" >Back</a>
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
                  <tr><td style="width:30%;"><u>Last Record Growday</u></td><td style="width:1%;"></td>
                  <td id=""></td></tr>
                  <tr><td style="width:30%;">Date Time</td><td style="width:1%;">:</td>
                  <td id="last_date"></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td>
                  <td id="last_growday"></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td>
                  <td id="last_flock"></td></tr>
                  <tr><td style="width:30%;">&nbsp;</td><td style="width:1%;"></td>
                  <td id=""></td></tr>
                  <tr><td style="width:30%;"><u>Change Growday Start</u></td><td style="width:1%;"></td>
                  <td id=""></td></tr>
                  <tr><td style="width:30%;">Date Time</td><td style="width:1%;">:</td>
                  <td id="change_date"></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td>
                  <td id="change_growday"></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td>
                  <td id="change_flock"></td></tr>
                  <tr><td style="width:30%;">&nbsp;</td><td style="width:1%;"></td>
                  <td id=""></td></tr>
                  <tr><td style="width:30%;"><u>Correction Data</u> (Optional)</td><td style="width:1%;"></td>
                  <td id=""></td></tr>
                  <tr><td style="width:30%;">Date Time</td><td style="width:1%;">:</td>
                  <td id="real_date"></td></tr>
                  <tr><td style="width:30%;">Growday</td><td style="width:1%;">:</td>
                  <td id="real_growday"></td></tr>
                  <tr><td style="width:30%;">Flock</td><td style="width:1%;">:</td>
                  <td id="real_flock"></td></tr>
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
