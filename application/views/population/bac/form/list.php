<div class="col-md-12" style="margin: auto;">
    <form id="form-aksi">
    <div class="col-lg-4">
          <div class="box">
            <div class="box-body" >
                <div class="form-group col-md-12">
                        <label>Date</label>
                        <div class="input-group date col-lg-5">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input name="tanggal" type="date" style="line-height: normal;" class="form-control pull-right" id="datepicker" onchange="getgrow()">
                        </div>
                </div>
                <div class="form-group col-lg-12">
                    <label>Data House</label>
                    <div class="input-group col-lg-8">
                        <select name="kandang" class="form-control" id="optionselect_kandang" style="width: 100%">
                            <option disabled selected>-Select data house-</option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-sm-12">
                        <label>Period</label>
                        <div class="input-group col-lg-5">
                            <input type="text" class="form-control pull-right" name="periode" disabled>
                        </div>
                </div>
                <div class="form-group col-sm-12">
                        <label>Growday</label>
                        <div class="input-group col-lg-5">
                            <input type="text" class="form-control pull-right" name="growday" disabled>
                        </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="box">
            <div class="box-body" >
                <div class="form-group">
                    <div class="col-sm-12">
                        <label>
                        <input type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                        Bird In
                        </label>
                        <div class="input-group col-lg-8" id="inputbird" style="display:;">
                            <input type="text" class="form-control pull-right" name="birdin">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="inputpopulation" style="display:;">
                    <div class="col-sm-12">
                        <label>Population</label>
                        <div class="input-group col-lg-8">
                            <input type="text" class="form-control pull-right" name="population">
                            <input type="hidden" class="form-control pull-right" name="stpopulation" value="false">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="inputmortality" style="display:;">
                    <div class="col-sm-12">
                        <label>Mortality</label>
                        <div class="input-group col-lg-8">
                            <input type="text" class="form-control pull-right" name="mortality">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="inputselection" style="display:;">
                    <div class="col-sm-12">
                        <label>Selection</label>
                        <div class="input-group col-lg-8">
                            <input type="text" class="form-control pull-right" name="selection">
                        </div>
                    </div>
                </div>
                <div class="form-group" id="endflock" style="display:;">
                    <label>
                        <input type="checkbox" class="minimal" style="position: absolute; opacity: 0;">
                        End Of Flock
                    </label>
                </div>
            </div>
        </div>
    </div>
    </form>
    <div class="col-lg-12">
        <div class="box">
            <!-- /.box-body -->
            <div class="box-footer">
            <a href="<?php echo base_url('population'); ?>" class="btn btn-default">Back</a>
            <button class="btn btn-primary" onclick="save();">Submit</button>
            </div>
        </div>
    </div>
</div>