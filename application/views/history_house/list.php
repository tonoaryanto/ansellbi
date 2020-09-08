<div class="row">
        <?php 
        $bg = [
          '0' => 'bg-orange',
          '1' => 'bg-blue',
          '2' => 'bg-purple',
          '3' => 'bg-red',
          '4' => 'bg-green',
          '5' => 'bg-aqua',
        ];
        $id_user = $this->session->userdata('id_user');
        $nomor = 0;
        foreach ($farm as $value) { 
          $data_farm = $this->umum_model->get("(SELECT * FROM (SELECT periode, grow_value,tanggal_value as settgl,jam_value as settime,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value FROM image2 WHERE kategori = 'HOUR_1' AND kode_kandang = '".$value->id."' AND kode_perusahaan = '".$id_user."' ORDER BY periode DESC, grow_value DESC) as data GROUP BY settgl DESC, LPAD(SUBSTRING_INDEX(settime, '-', 1), 2, '0') DESC LIMIT 1) as data2")->row_array();

          $beginesql = "(SELECT isi_value FROM image2 WHERE kategori = 'HOUR_1' AND nama_data = '";
          $endesql = "' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$value->id."' AND tanggal_value = '".$data_farm['settgl']."' AND jam_value = '".$data_farm['settime']."' LIMIT 1) as data";

          $data_farm1 = $this->umum_model->get($beginesql."4096".$endesql)->row_array();
          $data_farm2 = $this->umum_model->get($beginesql.'7218'.$endesql)->row_array();
          $data_farm3 = $this->umum_model->get($beginesql.'3142'.$endesql)->row_array();
          $data_farm4 = $this->umum_model->get($beginesql.'64760'.$endesql)->row_array();
          $data_farm5 = $this->umum_model->get($beginesql.'1301'.$endesql)->row_array();
          $data_farm6 = $this->umum_model->get($beginesql.'1302'.$endesql)->row_array();
          $data_farm7 = $this->umum_model->get($beginesql.'3259'.$endesql)->row_array();
          $data_farm8 = $this->umum_model->get($beginesql.'3190'.$endesql)->row_array();
        ?>
        <div class="col-md-3">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header <?php echo $bg[$nomor]; ?>">
              <div class="widget-user-image">
                <span class="img-circle" style="padding: 10px 10px;position: absolute;"><a href="<?php echo base_url('history_house/farm/').$value->id; ?>" class="btn btn-block btn-success btn-lg" title="Open Farm"><i class="fa fa-university"></i></a></span>
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username"><?php echo $value->nama_kandang; ?></h3>
              <h5 class="widget-user-desc">
              <table border="0">
                <tr><td>Periode</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td><b><?php if($data_farm['periode'] == ''){echo '0';}else{echo $data_farm['periode'];} ?></b></td></tr>
                <tr><td>Growday</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td><b><?php if($data_farm['grow_value'] == ''){echo '0';}else{echo $data_farm['grow_value'];} ?></b></td></tr>
                <tr><td>Date</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td><b><?php if($data_farm['tanggal_value'] == ''){echo '0';}else{echo $data_farm['tanggal_value'];} ?></b></td></tr>
                <tr><td>Time</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td><b><?php if($data_farm['jam_value'] == ''){echo '0';}else{echo $data_farm['jam_value'];} ?></b></td></tr>
              </table>  
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li><a href="javascript:void(0);">Required Temperature<span class="pull-right"><?php if($data_farm1['isi_value'] != ''){echo $data_farm1['isi_value'];}else{echo '0';} ?> °C</span></a></li>
                <li><a href="javascript:void(0);">Average Temperature<span class="pull-right"><?php if($data_farm2['isi_value'] != ''){echo $data_farm2['isi_value'];}else{echo '0';} ?> °C</span></a></li>
                <li><a href="javascript:void(0);">Humidity<span class="pull-right"><?php if($data_farm3['isi_value'] != ''){echo $data_farm3['isi_value'];}else{echo '0';} ?> %</span></a></li>
                <li><a href="javascript:void(0);">Wind Speed<span class="pull-right"><?php if($data_farm4['isi_value'] != ''){echo $data_farm4['isi_value'];}else{echo '0';} ?> M/s</span></a></li>
                <li><a href="javascript:void(0);">Feed Consumtion<span class="pull-right"><?php if($data_farm5['isi_value'] != ''){echo $data_farm5['isi_value'];}else{echo '0';} ?> Kg</span></a></li>
                <li><a href="javascript:void(0);">Water Consumption <span class="pull-right"><?php if($data_farm6['isi_value'] != ''){echo $data_farm6['isi_value'];}else{echo '0';} ?> Liter</span></a></li>
                <li><a href="javascript:void(0);">Static Pressure <span class="pull-right"><?php if($data_farm7['isi_value'] != ''){echo $data_farm7['isi_value'];}else{echo '0';} ?></span></a></li>
                <li><a href="javascript:void(0);">Fan Speed <span class="pull-right"><?php if($data_farm8['isi_value'] != ''){echo $data_farm8['isi_value'];}else{echo '0';} ?> %</span></a></li>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <?php 
        if($nomor == 5){$nomor = 0;}else{$nomor = $nomor + 1;}
        }
        ?>
</div>
