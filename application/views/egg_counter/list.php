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
        $wrnomor = 0;
        foreach ($farm as $value) { 
          $data_farm = $this->umum_model->get("data_realtime",['kode_perusahaan' => $id_user,'kode_kandang' => $value->id])->row_array();
          $xmenit = (int)str_split(date_format(date_create($data_farm['date_create']), "i"))[1] - 5;
          if($xmenit < 0){
            $xmenit = 0;
          }else if($xmenit >= 0){
            $xmenit = 5;
          }
          $menit = str_split(date_format(date_create($data_farm['date_create']), "i"))[0].$xmenit;
        ?>
        <div class="col-sm-12">
          <div id="boxoption" class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title bold"><?php echo $value->nama_kandang; ?></h3>
              <div class="box-tools pull-right">
                <a href="<?php echo base_url('egg_counter/farm/').$value->id; ?>" class="btn btn-sm btn-primary" title="History Egg Counter"><i class="fa fa-home"></i><span class="hidden-xs">&nbsp;&nbsp;History</span></a>
              </div>
            </div>
            <div id="boxoption_body" class="box-body">
              <div class="row" style="padding-left: 10px;padding-right: 10px">
                <div class="col-sm-3">Flock : <span class="font16" id="shperiode<?php echo $nomor;?>"><?php if($data_farm['periode'] == ''){echo '0';}else{echo $data_farm['periode'];} ?></span></div>
                <div class="col-sm-3">Growday : <span class="font16" id="shgrow<?php echo $nomor;?>"><?php if($data_farm['growday'] == ''){echo '0';}else{echo $data_farm['growday'];} ?></span></div>
                <div class="col-sm-3">Date : <span class="font16" id="shtgl<?php echo $nomor;?>"><?php if($data_farm['date_create'] == ''){echo '-';}else{echo date_format(date_create($data_farm['date_create']), "d-m-Y");} ?></span></div>
                <div class="col-sm-3">Time : <span class="font16" id="shjam<?php echo $nomor;?>"><?php if($data_farm['date_create'] == ''){echo '-';}else{echo date_format(date_create($data_farm['date_create']), "H").":".$menit.":00";} ?></span></div>
              </div>
              <br>
              <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 1</span>
                      <span class="info-box-number"><span id="shegg1<?php echo $nomor;?>"><?php if($data_farm['eggcounter1'] != ''){echo $data_farm['eggcounter1'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 2</span>
                      <span class="info-box-number"><span id="shegg2<?php echo $nomor;?>"><?php if($data_farm['eggcounter2'] != ''){echo $data_farm['eggcounter2'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 3</span>
                      <span class="info-box-number"><span id="shegg3<?php echo $nomor;?>"><?php if($data_farm['eggcounter3'] != ''){echo $data_farm['eggcounter3'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 4</span>
                      <span class="info-box-number"><span id="shegg4<?php echo $nomor;?>"><?php if($data_farm['eggcounter4'] != ''){echo $data_farm['eggcounter4'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 5</span>
                      <span class="info-box-number"><span id="shegg5<?php echo $nomor;?>"><?php if($data_farm['eggcounter5'] != ''){echo $data_farm['eggcounter5'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 6</span>
                      <span class="info-box-number"><span id="shegg6<?php echo $nomor;?>"><?php if($data_farm['eggcounter6'] != ''){echo $data_farm['eggcounter6'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 7</span>
                      <span class="info-box-number"><span id="shegg7<?php echo $nomor;?>"><?php if($data_farm['eggcounter7'] != ''){echo $data_farm['eggcounter7'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                  <div class="info-box" style="box-shadow:none;">
                    <span class="info-box-icon" style="background:#0000;"><i class="ion ion-egg"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Egg Counter 8</span>
                      <span class="info-box-number"><span id="shegg8<?php echo $nomor;?>"><?php if($data_farm['eggcounter8'] != ''){echo $data_farm['eggcounter8'];}else{echo '0';} ?></span></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php 
        if($wrnomor == 5){$wrnomor = 0;}else{$wrnomor = $wrnomor + 1;}
        $nomor = $nomor + 1;
        }
        ?>
</div>
