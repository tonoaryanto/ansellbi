<div class="row">
	<div class="col-sm-12">
		<div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			<li class="active"><a href="#">Multi Data</a></li>
			<li class=""><a href="<?php echo base_url('report/histori_house_day/myaxis') ?>">Yaxis Ganda</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="box" style="border-style: none;">
						<div class="box-body">
							<div class="row">
								<div class="form-group col-sm-2">
									<label>Data Kandang</label>
									<select name="val_kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
									<option disabled selected>-Pilih Data Kandang-</option>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>Periode</label>
									<input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Masukan periode-" onchange="selectdata();">
								</div>
								<div class="form-group col-sm-2">
									<label>Data House</label>
									<div class="input-group-btn" id="btndatapop">
					                  <button type="button" class="btn btn-default btn-block" style="border-radius: 5px;" onclick="btn_data()" id="thisbtndata" data-toggle="0" aria-expanded="false"> -Pilih Data-</button>
					                  <ul class="dropdown-menu" style="border: none;background: transparent;">
					                    <li>
									<select name="val_data[]" multiple="" class="form-control" id="optionselect" style="width: 100%;border-radius: 3px;max-height: 35px;">
									  <option disabled selected>- . . . -</option>
									</select>					                    	
					                    </li>
					                  </ul>
					                </div>
								</div>
								<div class="form-group col-sm-4">
									<label>Grow Day</label>
									<div class="row">
										<div class="form-group col-sm-6">
											<div class="input-group">
												<span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Dari</span>
												<input class="form-control" type="number" min="-1" name="daydari" value="-1" style="border-top-right-radius:4px;border-bottom-right-radius:4px;">
											</div>
										</div>
										<div class="form-group col-sm-6">
											<div class="input-group">
												<span class="input-group-addon" style="border-top-left-radius:4px;border-bottom-left-radius:4px;">Sampai</span>
												<input class="form-control" type="number" min="-1" name="daysampai" value="-1" style="border-top-right-radius:4px;border-bottom-right-radius:4px;">
											</div>
										</div>
									</div>
								</div>
								<div class="form-group col-sm-2">
									<label>Action</label>
									<div class="row">
										<div class="col-sm-12" id="actionpem">
											<button class="btn btn-success" onclick="addpembanding();" title="Tambah Pembanding"><i class="fa fa-plus"></i></button>
										</div>
									</div>
								</div>
							</div>
							<div class="row" id="boxpembanding" data-val="0">
							</div>
						</div>
						<div class="box-footer">
							<div class="form-group">
							<button class="btn btn-default" onclick="grafik();">Apply</button>
							<button class="btn btn-success" id="btnprint">Print</button>
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-sm-12">
		<div id="inihtml"></div>
	</div>
</div>
