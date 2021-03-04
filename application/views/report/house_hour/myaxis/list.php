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
			<li class=""><a href="<?php echo base_url('report/history_house_hour') ?>">Multiple Data</a></li>
			<li class="active"><a href="#">Double Y-axis</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="box" style="border-style: none;">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-12" style="padding: 0px 15px 0px;">
									<div class="form-group col-md-6">
										<label>Data House</label>
										<select name="val_kandang1" class="form-control optionselect_kandang" id="optionselect_kandang1" style="width: 100%">
										<option disabled selected>-Select Data House-</option>
										</select>
									</div>
									<div class="form-group col-md-6">
										<label>Flock</label>
										<input name="val_periode1" class="form-control" id="inputperiode1" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Input Flock-">
									</div>
								</div>
								<div class="col-sm-12" style="padding: 0px 30px 0px;">
								<div class="col-sm-6" style="border-style: solid;border-radius:5px;border-width: thin;border-color: #ddd;padding-top:5px;">
									<label style="color: #3c8dbc;text-align: center;width: 100%;font-size: 15px;padding: 10px 5px;background: aliceblue;border-radius: 5px;">Data 1</label>
									<hr style="margin: 0px 0px 10px 0px">
									<div class="form-group">
										<label>Data Parameter</label>
						                <select name="val_data1" class="form-control" id="optionselect1" style="width: 100%;border-radius: 3px;">
						                  <option disabled selected>-</option>
						                </select>
									</div>
									<div class="form-group">
										<label>Yaxis Position</label>
										<input type="text" class="form-control" name="posisiy1" value="left" readonly>
									</div>
									<div class="form-group" style="visibility: hidden;">
										<div class="checkbox">
						                    <label>
						                      <input type="checkbox">
						                      Not Used
						                    </label>
						                </div>
									</div>
								</div>
								<div class="col-sm-6" style="border-style: solid;border-radius:5px;border-width: thin;border-color: #ddd;padding-top:5px;">
									<label style="color: #3c8dbc;text-align: center;width: 100%;font-size: 15px;padding: 10px 5px;background: aliceblue;border-radius: 5px;">Data 2</label>
									<hr style="margin: 0px 0px 10px 0px">
									<div class="form-group">
										<label>Data Parameter</label>
						                <select name="val_data2" class="form-control" id="optionselect2" style="width: 100%;border-radius: 3px;">
						                  <option disabled selected>-</option>
						                </select>
									</div>
									<div class="form-group">
										<label>Yaxis Position</label>
										<input type="text" class="form-control" name="posisiy2" value="right" readonly>
									</div>
									<div class="form-group" style="visibility: hidden;">
										<div class="checkbox">
						                    <label>
						                      <input type="checkbox">
						                      Not Used
						                    </label>
						                </div>
									</div>
								</div>
							</div>
							</div>
							<div class="row">
							</div>
						</div>
						<div class="box-footer">
							<div class="form-group col-sm-12">
								<label>Chart name</label>
								<input style="border-radius: 5px" type="text" class="form-control" name="namagrafik" placeholder="Input chart name">
							</div>
							<div class="form-group col-sm-4">
								<label>Star Growday :</label>
								<div class="form-group">
									<div class="input-group">
									<input name="tgl1" onchange="changetgl(1);" class="form-control" value="" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
									<span class="input-group-addon" style="padding:0px;">
										<select onchange="changetgl(1);" name="time1" class="form-control select2" style="min-width: 90px;height:34px;border-radius:5px;">
										<option value="00:00">00:00</option>
										<option value="01:00">01:00</option>
										<option value="02:00">02:00</option>
										<option value="03:00">03:00</option>
										<option value="04:00">04:00</option>
										<option value="05:00">05:00</option>
										<option value="06:00">06:00</option>
										<option value="07:00">07:00</option>
										<option value="08:00">08:00</option>
										<option value="09:00">09:00</option>
										<option value="10:00">10:00</option>
										<option value="11:00">11:00</option>
										<option value="12:00">12:00</option>
										<option value="13:00">13:00</option>
										<option value="14:00">14:00</option>
										<option value="15:00">15:00</option>
										<option value="16:00">16:00</option>
										<option value="17:00">17:00</option>
										<option value="18:00">18:00</option>
										<option value="19:00">19:00</option>
										<option value="20:00">20:00</option>
										<option value="21:00">21:00</option>
										<option value="22:00">22:00</option>
										<option value="23:00">23:00</option>
										</select>
									</span>
									<span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
										<input onchange="changegrow(1);" min="1" type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;min-width: 80px;border: none;height: 32px;" class="form-control pull-right" name="tanggal_1" id="tanggal_1">
									</span>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-4">
							<label>End Growday :</label>
								<div class="form-group">
									<div class="input-group">
									<input name="tgl2" onchange="changetgl(2);" class="form-control" value="" type="date" style="border-top-left-radius:4px;border-bottom-left-radius:4px;line-height:unset;">
									<span class="input-group-addon" style="padding:0px;">
										<select onchange="changetgl(2);" name="time2" class="form-control select2" style="min-width: 90px;height:34px;border-radius:5px;">
										<option value="00:00">00:00</option>
										<option value="01:00">01:00</option>
										<option value="02:00">02:00</option>
										<option value="03:00">03:00</option>
										<option value="04:00">04:00</option>
										<option value="05:00">05:00</option>
										<option value="06:00">06:00</option>
										<option value="07:00">07:00</option>
										<option value="08:00">08:00</option>
										<option value="09:00">09:00</option>
										<option value="10:00">10:00</option>
										<option value="11:00">11:00</option>
										<option value="12:00">12:00</option>
										<option value="13:00">13:00</option>
										<option value="14:00">14:00</option>
										<option value="15:00">15:00</option>
										<option value="16:00">16:00</option>
										<option value="17:00">17:00</option>
										<option value="18:00">18:00</option>
										<option value="19:00">19:00</option>
										<option value="20:00">20:00</option>
										<option value="21:00">21:00</option>
										<option value="22:00">22:00</option>
										<option value="23:00">23:00</option>
										</select>
									</span>
									<span class="input-group-addon" style="border-top-right-radius:4px;border-bottom-right-radius:4px;padding:0px;">
										<input onchange="changegrow(2);" min="1" type="number" style="line-height: normal;border-top-right-radius:4px;border-bottom-right-radius:4px;min-width: 80px;border: none;height: 32px;" class="form-control pull-right" name="tanggal_2" id="tanggal_2">
									</span>
									</div>
								</div>
							</div>
							<div class="form-group col-sm-4">
							<label>Actions</label>
								<div>
									<button class="btn btn-default" onclick="grafik();">Apply</button>
									<button style="display: none;" id="btnprintpdf" class="btn btn-danger" onclick="allprint()">Print PDF</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div id="inihtml"></div>
	      <div class="box" id="boxtabel" style="visibility: hidden;">
	        <div class="box-body">
	          <div id="tglresponse" class="table-responsive">
	          </div>
	        </div>
	      </div>
	</div>
</div>
