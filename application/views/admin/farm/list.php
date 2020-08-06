<div class="row">
  <div class="col-sm-12">
    <div id="boxoption" class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Tabel Data Farm</h3>
        <button class="btn btn-success btn-sm" id="btn-add-form">
          <i class="fa fa-plus"></i> Tambah
        </button>
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table class="table table-bordered table-striped" id="mytable">
              <thead>
                  <tr>
                      <th class="teks-upper" width="50px">No</th>
                      <th class="teks-upper">Nama Farm</th>
                      <th class="teks-upper">Alamat Farm</th>
                  </tr>
              </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('admin/farm/addfrom'); ?>
