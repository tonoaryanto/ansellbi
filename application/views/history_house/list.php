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
        <div class="col-md-3">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header <?php echo $bg[$wrnomor]; ?>">
              <div class="widget-user-image">
                <span class="img-circle" style="padding: 10px 10px;position: absolute;"><a href="<?php echo base_url('history_house/farm/').$value->id; ?>" class="btn btn-block btn-success btn-lg" title="Open Farm"><i class="fa fa-university"></i></a></span>
              </div>
              <!-- /.widget-user-image -->
              <h3 class="widget-user-username"><?php echo $value->nama_kandang; ?></h3>
              <h5 class="widget-user-desc">
              <table border="0">
                <tr><td class="font14">FLock</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td style="font-weight: bold;" id="shperiode<?php echo $nomor;?>"><?php if($data_farm['periode'] == ''){echo '0';}else{echo $data_farm['periode'];} ?></td></tr>
                <tr><td class="font14">Growday</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td style="font-weight: bold;" id="shgrow<?php echo $nomor;?>"><?php if($data_farm['growday'] == ''){echo '0';}else{echo $data_farm['growday'];} ?></td></tr>
                <tr><td class="font14">Date</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td style="font-weight: bold;" id="shtgl<?php echo $nomor;?>"><?php if($data_farm['date_create'] == ''){echo '-';}else{echo date_format(date_create($data_farm['date_create']), "d-m-Y");} ?></td></tr>
                <tr><td class="font14">Time</td><td>&nbsp;&nbsp;:&nbsp;&nbsp;</td><td style="font-weight: bold;" id="shjam<?php echo $nomor;?>"><?php if($data_farm['date_create'] == ''){echo '-';}else{echo date_format(date_create($data_farm['date_create']), "H").":".$menit.":00";} ?></td></tr>
              </table>
            </div>
            <div class="box-footer no-padding">
              <ul class="nav nav-stacked">
                <li class="font16"><a href="javascript:void(0);">Required Temperature<span class="pull-right"><span id="shreqtemp<?php echo $nomor;?>"><?php if($data_farm['req_temp'] != ''){echo $data_farm['req_temp'];}else{echo '0';} ?></span> °C</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Average Temperature<span class="pull-right"><span id="shavgtemp<?php echo $nomor;?>"><?php if($data_farm['avg_temp'] != ''){echo $data_farm['avg_temp'];}else{echo '0';} ?></span> °C</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Humidity<span class="pull-right"><span id="shhum<?php echo $nomor;?>"><?php if($data_farm['humidity'] != ''){echo $data_farm['humidity'];}else{echo '0';} ?></span> %</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Wind Speed<span class="pull-right"><span id="shwind<?php echo $nomor;?>"><?php if($data_farm['windspeed'] != ''){echo $data_farm['windspeed'];}else{echo '0';} ?></span> M/s</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Feed Consumtion<span class="pull-right"><span id="shfeed<?php echo $nomor;?>"><?php if($data_farm['feed'] != ''){echo $data_farm['feed'];}else{echo '0';} ?></span> Kg</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Water Consumption <span class="pull-right"><span id="shwater<?php echo $nomor;?>"><?php if($data_farm['water'] != ''){echo $data_farm['water'];}else{echo '0';} ?></span> Liter</span></a></li>
                <li class="font16"><a href="javascript:void(0);">Static Pressure <span class="pull-right"><span id="shpress<?php echo $nomor;?>"><?php if($data_farm['static_pressure'] != ''){echo $data_farm['static_pressure'];}else{echo '0';} ?></span></span></a></li>
                <li class="font16"><a href="javascript:void(0);">Fan Speed <span class="pull-right"><span id="shfan<?php echo $nomor;?>"><?php if($data_farm['fan'] != ''){echo $data_farm['fan'];}else{echo '0';} ?></span> %</span></a></li>
              </ul>
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <?php 
        if($wrnomor == 5){$wrnomor = 0;}else{$wrnomor = $wrnomor + 1;}
        $nomor = $nomor + 1;
        }
        ?>
</div>
