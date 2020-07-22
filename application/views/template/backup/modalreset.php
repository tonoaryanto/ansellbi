        <div class="modal fade" id="modalreset">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Reset Data</h4>
              </div>
              <div class="modal-body">
                <form id="form-reset">
                  <div class="form-group">
                    <label>Data Kandang</label>
                    <select id="resetoptionselect_kandang" class="form-control" style="width: 100%;">
                      <option>-pilih-</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Nama Penanggung Jawab</label>
                    <input class="form-control" type="text" name="namapenanggung" style="border-radius: 5px;">
                  </div>
                  <div class="form-group">
                    <label>Keterangan</label>
                    <textarea class="form-control" name="ketpenanggung" style="border-radius: 5px;"></textarea>
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <span id="tl"></span>
                <button type="button" class="btn btn-primary" id="rstgo">Reset</button>
              </div>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->