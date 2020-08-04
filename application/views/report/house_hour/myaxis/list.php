<div class="row">
	<div class="col-sm-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			<li class=""><a href="<?php echo base_url('report/history_house_hour') ?>">Multi Data</a></li>
			<li class="active"><a href="#">Yaxis Ganda</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="box" style="border-style: none;">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-6" style="border-style: solid;border-radius:5px;border-width: thin;border-color: #ddd;padding-top:5px;">
									<label style="color: #3c8dbc;text-align: center;width: 100%;font-size: 15px;padding: 10px 5px;background: aliceblue;border-radius: 5px;">Data 1</label>
									<hr style="margin: 0px 0px 10px 0px">
									<div class="form-group">
									<label>Data Kandang</label>
									<select name="val_kandang1" class="form-control optionselect_kandang" id="optionselect_kandang1" style="width: 100%">
									<option disabled selected>-Pilih Data Kandang-</option>
									</select>
									</div>
									<div class="form-group">
										<label>Periode</label>
										<input name="val_periode1" class="form-control" id="inputperiode1" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Masukan periode-" onchange="selectdata(1);">
									</div>
									<div class="form-group">
										<label>Data House</label>
						                <select name="val_data1" class="form-control" id="optionselect1" style="width: 100%;border-radius: 3px;">
						                  <option disabled selected>-Pilih Kandang Dahulu-</option>
						                </select>
									</div>
									<div class="form-group">
										<label>Posisi Yaxis</label>
										<input type="text" class="form-control" name="posisiy1" value="left" readonly>
									</div>
									<div class="form-group" style="visibility: hidden;">
										<div class="checkbox">
						                    <label>
						                      <input type="checkbox">
						                      Tidak digunakan
						                    </label>
						                </div>
									</div>
								</div>
								<div class="col-sm-6" style="border-style: solid;border-radius:5px;border-width: thin;border-color: #ddd;padding-top:5px;">
									<label style="color: #3c8dbc;text-align: center;width: 100%;font-size: 15px;padding: 10px 5px;background: aliceblue;border-radius: 5px;">Data 2</label>
									<hr style="margin: 0px 0px 10px 0px">
									<div class="form-group">
									<label>Data Kandang</label>
									<select name="val_kandang2" class="form-control optionselect_kandang" id="optionselect_kandang2" style="width: 100%">
									<option disabled selected>-Pilih Data Kandang-</option>
									</select>
									</div>
									<div class="form-group">
										<label>Periode</label>
										<input name="val_periode2" class="form-control" id="inputperiode2" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Masukan periode-" onchange="selectdata(2);">
									</div>
									<div class="form-group">
										<label>Data House</label>
						                <select name="val_data2" class="form-control" id="optionselect2" style="width: 100%;border-radius: 3px;">
						                  <option disabled selected>-Pilih Kandang Dahulu-</option>
						                </select>
									</div>
									<div class="form-group">
										<label>Posisi Yaxis</label>
										<input type="text" class="form-control" name="posisiy2" value="right" readonly>
									</div>
									<div class="form-group" style="visibility: hidden;">
										<div class="checkbox">
						                    <label>
						                      <input type="checkbox">
						                      Tidak digunakan
						                    </label>
						                </div>
									</div>
								</div>
							</div>
							<div class="row">
							</div>
						</div>
						<div class="box-footer">
							<div class="form-group col-sm-12">
								<label>Nama Grafik</label>
								<input style="border-radius: 5px" type="text" class="form-control" name="namagrafik" placeholder="Nama Grafik">
							</div>
							<div class="form-group col-sm-4">
								<label>Star Grow Day</label>
								<div class="form-group">
									<input class="form-control" type="number" min="-1" name="hourdari1" value="-1" style="border-radius:3px;">
								</div>
							</div>
							<div class="form-group col-sm-4">
								<label>End Grow Day</label>
								<div class="form-group">
									<input class="form-control" type="number" min="-1" name="hourdari2" value="-1" style="border-radius:3px;">
								</div>
							</div>
							<div class="form-group col-sm-4">
								<label>Actions</label>
								<div>
									<button class="btn btn-default" onclick="grafik();">Apply</button>
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
