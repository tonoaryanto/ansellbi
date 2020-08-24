<div class="row">
  <div class="col-sm-12">
    <div id="boxoption" class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Tabel Data House</h3>
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
                      <th class="teks-upper">Nama Kandang</th>
                      <th class="teks-upper">Data House</th>
                  </tr>
              </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php $this->load->view('admin/open_farm/addform'); ?>
<?php $this->load->view('admin/open_farm/uploadform'); ?>
<?php $this->load->view('admin/open_farm/resetform'); ?>
