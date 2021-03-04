<div class="col-md-12" style="margin: auto;">
          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Form input Population</h3>
              <div class="box-tools pull-right">
              </div>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body" >
                <form id="form-aksi">
                <div class="col-lg-12">
                    <div class="col-lg-4">
                        <div class="form-group col-lg-12">
                            <label>Data House</label>
                            <div class="input-group col-lg-8">
                                <select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                                    <option disabled selected>-Select data house-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                                <label>Flock</label>
                                <div class="input-group col-lg-5">
                                    <input readonly type="text" class="form-control pull-right" onchange="getgrow()" name="periode" disabled>
                                </div>
                        </div>
                        <div class="form-group col-md-12">
                            <label>Date</label>
                            <div class="input-group date col-lg-6">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input name="tanggal" type="date" style="line-height: normal;" class="form-control pull-right" id="datepicker" onchange="getgrow()">
                            </div>
                        </div>
                        <div class="form-group col-sm-12">
                            <label>Growday</label>
                            <div class="input-group col-lg-5">
                                <input type="text" class="form-control pull-right" name="growday" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group col-sm-12" id="birdinput" style="display:none;">
                            <label><input type="checkbox" class="minimal" style="position: absolute; opacity: 0;" name="cekbirdin">&nbsp;Bird In</label>
                            <div class="input-group col-lg-8" id="inputbird" style="display:none;">
                                <input type="text" class="form-control pull-right" name="birdin">
                            </div>
                        </div>
                        <div class="form-group col-sm-12" id="inputpopulation" style="display:none;">
                            <label>Population</label>
                            <div class="input-group col-lg-8">
                                <input type="text" class="form-control pull-right" name="population">
                                <input type="hidden" class="form-control pull-right" name="stpopulation" value="false">
                                <span class="input-group-addon" style="padding:0px;">
                                <input readonly name="perpopulation" type="date" class="form-control" style="min-width:90px;line-height: normal;border: none;height: 32px;">
                                </span>
                            </div>
                        </div>
                        <div class="form-group col-sm-12" id="inputmortality" style="display:none;">
                            <label>Death</label>
                            <div class="input-group col-lg-8">
                                <input type="text" class="form-control pull-right" name="mortality">
                            </div>
                        </div>
                        <div class="form-group col-sm-12" id="inputselection" style="display:none;">
                            <label>Culling</label>
                            <div class="input-group col-lg-8">
                                <input type="text" class="form-control pull-right" name="selection">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <a href="<?php echo base_url('population'); ?>" class="btn btn-default">Back</a>
            <button class="btn btn-primary" onclick="save();">Submit</button>
            </div>
        </div>
</div>