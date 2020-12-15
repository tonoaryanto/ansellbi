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
                    <div class="col-lg-4">
                        <div class="form-group">
                            <div class="col-md-12">
                                <label>Date</label>
                                <div class="input-group date col-lg-5">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input name="tanggal" type="date" style="line-height: normal;" class="form-control pull-right" id="datepicker" onchange="getgrow()">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-12">Data House</label>
                            <div class="col-lg-8">
                                <select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                                    <option disabled selected>-Select data house-</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Period</label>
                                <div class="input-group col-lg-5">
                                    <input type="text" class="form-control pull-right" name="periode" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Growday</label>
                                <div class="input-group col-lg-5">
                                    <input type="text" class="form-control pull-right" name="growday" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group" id="inputbird" style="display:none;">
                            <div class="col-sm-12">
                                <label>Bird In</label>
                                <div class="input-group col-lg-8">
                                    <input type="text" class="form-control pull-right" name="birdin">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="inputpopulation" style="display:none;">
                            <div class="col-sm-12">
                                <label>Population</label>
                                <div class="input-group col-lg-8">
                                    <input type="text" class="form-control pull-right" name="population">
                                    <input type="hidden" class="form-control pull-right" name="stpopulation" value="false">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="inputmortality" style="display:none;">
                            <div class="col-sm-12">
                                <label>Mortality</label>
                                <div class="input-group col-lg-8">
                                    <input type="text" class="form-control pull-right" name="mortality">
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="inputselection" style="display:none;">
                            <div class="col-sm-12">
                                <label>Selection</label>
                                <div class="input-group col-lg-8">
                                    <input type="text" class="form-control pull-right" name="selection">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
            <a href="<?php echo base_url('egg_weight'); ?>" class="btn btn-default">Back</a>
            <button class="btn btn-primary" onclick="save();">Submit</button>
            </div>
        </div>
</div>