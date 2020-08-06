<div class="modal fade" id="modal-form">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="title-form">Default Modal</h4>
          </div>
          <div class="modal-body">
            <form id="form-aksi">
                    <div class="form-group">
                        <label>Nama Farm</label>
                        <input type="text" class="form-control" name="nama_farm" placeholder="Nama Farm"/>
                    </div>
                    <div class="form-group">
                        <label>Alamat Farm</label>
                        <textarea class="form-control" name="alamat_farm" placeholder="Alamat Farm"></textarea>
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
