<div class="col-md-12" style="margin: auto;">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Form Standard Value ( <?php echo $texttitle; ?> )</h3>
              <div class="box-tools pull-right">
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
                </div>
                <div class="col-md-12" id="inputweek" data-week="0">
                </div>
                </div>
                <input name="tpval" type="hidden" value="<?php echo $tpval ?>">
                <!-- /.box-body -->
              <div class="box-footer">
              <a href="<?php echo base_url('setting/standard_value'); ?>" class="btn btn-default">Back</a>
              <button class="btn btn-primary" onclick="save();">Submit</button>
              </div>
          </div>
</div>