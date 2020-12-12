<div class="col-md-12" style="margin: auto;">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Form input Egg Weight</h3>
              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body" >
                <form id="form-aksi">
                <div class="col-lg-12">
                    <div class="col-lg-6">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Date</label>
                            <div class="input-group date col-lg-3">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input onchange="getgrow()" name="tanggal" type="date" style="line-height: normal;" class="form-control pull-right" id="datepicker">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-12">Data House</label>
                        <div class="col-lg-5">
                            <select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                                <option disabled selected>-Select data house-</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Period</label>
                            <div class="input-group col-lg-3">
                                <input type="text" class="form-control pull-right" name="periode" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Growday</label>
                            <div class="input-group col-lg-3">
                                <input type="text" class="form-control pull-right" name="growday" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <label>Data Egg Weight</label>
                            <div class="input-group col-lg-5">
                                <input type="text" class="form-control pull-right" name="input1">
                                <div class="input-group-addon">Kg</div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <a href="<?php echo base_url('bodyweight'); ?>" class="btn btn-default">Back</a>
            <button class="btn btn-primary" onclick="save();">Submit</button>
            </div>
        </div>
</div>