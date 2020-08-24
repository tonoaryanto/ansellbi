<div class="modal fade" id="modal-rsetform">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="title-rsetform" style="text-transform: capitalize;">Default Modal</h4>
          </div>
          <form id="rsetform-aksi" action="javascript:void(0);">
          <div class="modal-body">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Data Reset</label>
                    <div>
                      <select type="text" name="select_datarset" class="form-control select2" style="width: 100%;" onchange="data_terakhir();">
                        <option disabled selected>--Pilih Data--</option>
                        <option value="house">History House</option>
                        <option value="alarm">History Alarm</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12" style="padding: 0px;" id="inputrset">
                  <div class="form-group col-sm-4">
                    <label>Periode</label>
                    <input placeholder="Masukan Periode" type="number" min="1" name="perioderset" size="20" class="form-control" width="100px" style="border-radius:5px;" onchange="data_terakhir();">
                  </div>
                  <div class="form-group col-sm-4">
                    <label>Star Growday</label>
                    <input placeholder="Star Growday" type="number" min="1" name="startgrowrset" size="20" class="form-control" width="100px" style="border-radius:5px;" onchange="data_terakhir();">
                  </div>
                  <div class="form-group col-sm-4">
                    <label>End Growday</label>
                    <input placeholder="End Growday" type="number" min="1" name="endgrowrset" size="20" class="form-control" width="100px" style="border-radius:5px;" onchange="data_terakhir();">
                  </div>
                </div>
                <div class="col-sm-12" style="padding: 0px;">
                <div class="form-group col-sm-4">
                    <span>
                        <input type="checkbox" name="rsetall" onclick="cekrset()" value="all">
                        Resel All data
                    </span>
                </div>
                </div>
                <input type="hidden" name="select_kandangrset" /> 
          </div>
          <div class="modal-footer">
            <button id="tombol" type="submit" class="btn btn-danger">Reset</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Kembali</button>
          </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
