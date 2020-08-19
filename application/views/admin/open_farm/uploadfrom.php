<div class="modal fade" id="modal-uploadform">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="title-uploadform">Default Modal</h4>
          </div>
          <form id="uploadform-aksi" action="javascript:void(0);">
          <div class="modal-body">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label>Data Upload</label>
                    <div>
                      <select type="text" name="select_data" class="form-control select2" style="width: 100%;" onchange="data_terakhir();">
                        <option disabled selected>--Pilih Data--</option>
                        <option value="house">History House</option>
                        <option value="alarm">History Alarm</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-sm-12" style="padding: 0px;">
                  <div class="form-group col-sm-6">
                    <label>Periode</label>
                    <input placeholder="Masukan Periode" type="number" min="1" name="periode" size="20" class="form-control" width="100px" style="border-radius:5px;" onchange="data_terakhir();">
                  </div>
                  <div class="col-sm-6">
                    <div class="form-group">
                      <label>Growday Terakhir</label>
                      <input type="text" name="last_growday" size="20" class="form-control" style="border-style:none;border-bottom-style:solid;background-color: transparent;" readonly="" placeholder="kosong">
                    </div>
                  </div>
                </div>
                <div class="col-sm-12" style="margin-bottom:15px;">
                <div class="col-sm-12" style="border:1px solid #ddd;padding: 10px;border-radius:5px;margin-bottom:15px;">
                    <label>Upload File Disini!</label>
                    <div class="form-group">
                      <input type="file" name="userfile" size="20" class="pfile" width="100px">
                      <p style="color: #888;padding: 2px" id="txtctt"></p>
                    </div>
                </div>
                <div>
                  <label>Logs :</label>
                  <textarea id="textlog" class="form-control" rows="6" style="resize:none;border-radius:5px;" readonly=""></textarea>
                </div>
                </div>
                <input type="hidden" name="select_kandang" /> 
          </div>
          <div class="modal-footer">
            <button id="tombol" type="submit" class="btn btn-success">Upload</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Kembali</button>
          </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
