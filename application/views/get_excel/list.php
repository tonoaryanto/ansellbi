
  <div class="row">
    <div class="col-sm-12">
      <div class="box">
        <div class="box-body">
          <div class="row">
            <form id="form-aksi">
              <div class="col-sm-12">
                <div class="row">
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label>Periode</label>
                      <input placeholder="Masukan Periode" type="number" min="1" name="periode" size="20" class="form-control" width="100px" style="border-radius:5px;" onchange="data_terakhir();">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label>Nama Kandang</label>
                        <select type="text" name="select_kandang" class="form-control select2 test"></select>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label>Periode Terakhir</label>
                      <input type="text" name="last_periode" size="20" class="form-control" style="border-style:none;border-bottom-style:solid;background-color: transparent;" readonly="" placeholder="kosong">
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="form-group">
                      <label>Growday Terakhir</label>
                      <input type="text" name="last_growday" size="20" class="form-control" style="border-style:none;border-bottom-style:solid;background-color: transparent;" readonly="" placeholder="kosong">
                    </div>
                  </div>
                </div>
                  <div class="col-sm-12" style="border:1px solid #ddd;padding: 10px;border-radius:5px;">
                    <label>Upload File Disini!</label>
                    <div class="form-group">
                      <input type="file" name="userfile" size="20" class="pfile" width="100px">
                      <p style="color: #888;padding: 2px">Contoh : history_[nama farm]_[kode farm]_ENG.csv</p>
                    </div>
                    <div class="form-group">
                      <div class="col-sm-12 controls" style="text-align: right;" id="framebtn">
                        <button id="tombol" href="javascript:void(0);" type="submit" class="btn btn-success">Upload</button>
                      </div>
                    </div>
                </div>
              </div>
            </form>
          </div>
          <br>
          <div class="row">
            <div class="col-sm-12">
              <label>Logs :</label>
              <textarea id="textlog" class="form-control" rows="6" style="resize:none;border-radius:5px;" readonly=""></textarea>
            </div>
          </div>
        </div>
      </div>      
    </div>
  </div>