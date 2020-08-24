<div class="modal fade" id="modal-form">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="title-form">Default Modal</h4>
          </div>
          <div class="modal-body">
            <form id="form-aksi" action="javascript:void(0);">
            <div class="form-group">
                      <label>Nama House</label>
                      <input type="text" class="form-control" name="nama_house" id="nama_house" placeholder="Nama Farm" onkeyup="enter('nama_house');" />
                  </div>
                  <div class="form-group">
                      <label>Nama House</label>
                      <input type="text" class="form-control" name="nama_house" id="nama_house" placeholder="Nama Farm" onkeyup="enter('nama_house');" />
                  </div>
                <input type="hidden" name="id"/> 
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="save();">Simpan</button>
            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Batal</button>
          </div>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>
