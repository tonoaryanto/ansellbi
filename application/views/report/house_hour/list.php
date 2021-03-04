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
			<li class="active"><a href="#" data-toggle="tab" aria-expanded="true">Multiple Data</a></li>
			<li class=""><a href="<?php echo base_url('report/history_house_hour/myaxis') ?>">Double Y-axis</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active">
					<div class="box" style="border-style: none;">
						<div class="box-body">
							<div class="row">
								<div class="form-group col-sm-2">
									<label>Data House</label>
									<select name="val_kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
									<option disabled selected>-Select data house-</option>
									</select>
								</div>
								<div class="form-group col-sm-2">
									<label>Flock</label>
									<input name="val_periode" class="form-control" id="inputperiode" style="width: 100%;border-radius: 3px;" type="number" min="1" placeholder="-Input FLock-" onchange="">
								</div>
								<div class="form-group col-sm-2">
									<label>Data parameter</label>
									<div class="input-group-btn" id="btndatapop">
					                  <button type="button" class="btn btn-default btn-block" style="border-radius: 5px;" onclick="btn_data()" id="thisbtndata" data-toggle="0" aria-expanded="false"> -Select Data-</button>
					                  <ul class="dropdown-menu" style="border: none;background: transparent;">
					                    <li>
									<select name="val_data[]" multiple="" class="form-control" id="optionselect" style="width: 100%;border-radius: 3px;max-height: 35px;">
									  <option disabled selected>- . . . -</option>
									</select>					                    	
					                    </li>
					                  </ul>
					                </div>
								</div>
								<div class="form-group col-sm-2">
									<label> Star Growday</label>

						            <div class="form-group">
						                <input class="form-control" type="number" min="-1" name="hourdari1" value="-1" style="border-radius:3px;">
						            </div>
								</div>
								<div class="form-group col-sm-2">
									<label>End Growday</label>

						            <div class="form-group">
						                <input class="form-control" type="number" min="-1" name="hourdari2" value="-1" style="border-radius:3px;">
						            </div>
								</div>
								<div class="form-group col-sm-2">
									<label>Actions</label>
									<div class="row">
										<div class="col-sm-12" id="actionpem">
											<button class="btn btn-success" onclick="addpembanding();" title="Added the comparator"><i class="fa fa-plus"></i></button>
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
							<!--button class="btn btn-success" id="btnprint">Print</button-->
							<button style="display: none;" id="btnprintpdf" class="btn btn-danger" onclick="allprint()">Print PDF</button>
							</div>		
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="inihtml"></div>
</div>
