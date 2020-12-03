<div class="col-md-12" style="margin: auto;">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Form Standard Value ( <?php echo $texttitle; ?> )</h3>
              <div class="box-tools pull-right">
              <a href="#" class="btn btn-sm btn-danger" onclick="removeweek()" title="Remove Week"><i class="fa fa-minus"></i><span class="hidden-xs">&nbsp;&nbsp;Remove Week</span></a>
              <a href="#" class="btn btn-sm btn-success" onclick="addweek()" title="Add Week"><i class="fa fa-plus"></i><span class="hidden-xs">&nbsp;&nbsp;Add Week</span></a>
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
              <div class="box-body" >
                <div class="col-md-12">
								<div class="form-group col-sm-12">
									<label>Data House</label>
									<select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
									<option disabled selected>-Select data house-</option>
									</select>
								</div>
                <div class="form-group col-md-12">
                   <label>Set Activation</label>
                    <select name="stdaktif" class="form-control select2" onchange="std_aktif();">
                      <option disabled selected>- On / Off -</option>
                      <option value="y">On</option>
                      <option value="n">Off</option>
                    </select>
                  </div>
                </div>
                <?php $countweek = 12; ?>
                <div class="col-md-12" style="display: none;" id="inputweek" data-week="<?php echo $countweek; ?>">
                    <?php for ($i=0; $i < $countweek; $i++) { $a=$i+1;?>
                      <div class="form-group col-md-2" id="week<?php echo $a; ?>">
                        <label>Week <?php echo $a; ?></label>
                        <input name="week<?php echo $a; ?>" type="text" class="form-control" >
                      </div>
                    <?php } ?>
                  </div>
                </div>
              <!-- /.box-body -->
              <div class="box-footer">
              <a href="<?php echo base_url('setting/standard_value'); ?>" class="btn btn-default">Back</a>
              <button class="btn btn-primary" onclick="save();">Submit</button>
              </div>
          </div>
</div>