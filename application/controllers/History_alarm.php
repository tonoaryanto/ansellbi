<?php defined('BASEPATH') OR exit('No direct script access allowed');

class History_alarm extends CI_Controller {

    function __construct(){
        parent::__construct();
        $this->load->library('datatables');
    }

    public function index(){
        $this->konfigurasi->cek_url();
        $data = [
            'txthead1'     => 'History Alarm',
            'head1'     => 'History Alarm',
            'link1'     => '#',
            'isi'       => 'history_alarm/list',
            'cssadd'    => 'history_alarm/cssadd',
            'jsadd'     => 'history_alarm/jsadd',
        ];
        $this->load->view('template/wrapper',$data);
    }

    public function datajson(){
        $id_user = $this->session->userdata('id_user');
        $data_select = $this->input->post('value1');
        $dari = date_format(date_create($this->input->post('value2')), 'Y-m-d');
        $sampai = date_format(date_create($this->input->post('value3')), 'Y-m-d');
        $data_periode = $this->input->post('value4');

        $where  = "id_user = '".$id_user."' ";
        $where .= "AND kode_kandang = '".$data_select."' ";

        $where1  = $where;
        $where1 .= "AND tanggal >= '".$dari."' ";
        $where1 .= "AND tanggal <= '".$sampai."' ";
        $where1 .= "AND periode = '".$data_periode."' ";

        $raw_group = $this->db->query("SELECT tanggal FROM history_alarm WHERE ".$where1." GROUP BY tanggal");

        if ($raw_group->num_rows() >= 1) {
        ?>
        <ul class="timeline">
        <?php
        $no1 = 0; 
        foreach ($raw_group->result() as $value1) {
            $where2  = $where;
            $where2 .= "AND tanggal = '".$value1->tanggal."' ";
            $raw_alarm = $this->umum_model->get('history_alarm',$where2)->result();

            ?>
            <!-- timeline time label -->
            <li class="time-label">
                <span class="<?php echo $this->getbg($this->umum_model->acak())?>">
                  <?php echo tgl_indo_hari($value1->tanggal);?>
                </span>
            </li>
            <!-- /.timeline-label -->
            <?php
            $no2 = 0;
            foreach ($raw_alarm as $value2) {
            ?>
            <!-- timeline item -->
            <li>
            <i class="fa <?php echo $this->type_icon($value2->type); ?>"></i>

            <div class="timeline-item">
              <span class="time"><b><i class="fa fa-clock-o"></i> <?php echo $value2->jam?></b></span>

              <h3 class="timeline-header"><a href="javascript:void(0);" data-toggle="collapse" data-target="#colap<?php echo $no1.$no2?>"><?php echo $this->type($value2->type); ?></a> <?php echo $this->param($value2->alarm_param,$value2->data5); ?></h3>

              <div class="timeline-body">
                <div id="colap<?php echo $no1.$no2?>" class="collapse">
                    <p>
                        <style type="text/css">tr:nth-child(odd){background: #f7f7f759} tr:nth-child(even){background: #bdd0fb36;} td{vertical-align: top;padding-bottom: 5px;}</style>
                        <table border="0" style="width: 100%">
                            <tr>
                                <td style="width: 130px">Grow Day</td>
                                <td style="width: 1px">&nbsp;:&nbsp;</td>
                                <td><?php echo $value2->growday; ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 1</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm1 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm1);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm1($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 1</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable1 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable1);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm1($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 2</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm2 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm2);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm2($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 2</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable2 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable2);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm2($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Alarm 3</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->alarm3 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->alarm3);
                                    for ($i=0; $i < count($data); $i++) { 
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm3($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Disable 3</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->disable3 == '-9999'){echo 'Tidak digunakan';}else{
                                    $data = str_split($value2->disable3);
                                    for ($i=0; $i < count($data); $i++) {
                                        if ($data[$i] != 0) {
                                        echo '- ('.$data[$i].') '.$this->alarm3($data[$i]).'<br>';
                                        }
                                    }
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Require Temperature</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->req_temp == '-999.9'){echo 'Tidak digunakan';
                                }else if($value2->req_temp == '777.7'){
                                    echo 'Tidak Terhubung';
                                }else{
                                    echo $value2->req_temp.' <span>&#8451;</span>';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>In Temperature</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->in_temp == '-999.9'){echo 'Tidak digunakan';
                                }else if($value2->req_temp == '777.7'){
                                    echo 'Tidak Terhubung';
                                }else{
                                    echo $value2->in_temp.' <span>&#8451;</span>';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Humidity</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->humidity == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->humidity;
                                } ?></td>
                            </tr>
                            <tr>
                                <td>Weight</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->weight == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->weight.' gr';
                                } ?></td>
                            </tr>
                            <tr>
                                <td>State</td>
                                <td>&nbsp;:&nbsp;</td>
                                <td><?php if($value2->state == '-9999'){echo 'Tidak digunakan';}else{
                                    echo $value2->state;
                                } ?></td>
                            </tr>
                        </table>
                    </p>
                </div>
              </div>
            </div>
            </li>
            <!-- END timeline item -->
            <?php
            $no2++;
            }
        $no1++;
        }
            ?>
              <li>
                <i class="fa fa-clock-o bg-gray"></i>
              </li>
            </ul>
            <?php
        }else{
            ?>
            <p style="text-align: center;color:#888;">- Data tidak tersedia -</p>
            <?php 
        }

    }

    private function alarm1($value){
        $data = [
            '1' => 'Cold',
            '2' => 'Hot',
            '3' => 'Memori',
            '4' => 'All Sensors',
            '5' => 'One Sensor',
            '7' => 'Silo 1 Empty',
            '8' => 'Silo 2 Empty',
            '9' => 'Silo Error',
        ];

        return $data[$value];
    }

    private function alarm2($value){
        $data = [
            '1' => 'Water Overflow',
            '2' => 'Water Stoppage',
            '3' => 'AC Power Alarm',
            '4' => 'Alarm Test',
            '5' => 'Trolleys At Start Input',
            '6' => 'Trolleys Fill Alarm',
            '7' => 'Trolleys Move Alarm',
            '8' => 'Low Humidity Alarm',
            '9' => 'Hight Humidity Alarm',
        ];

        return $data[$value];
    }

    private function alarm3($value){
        $data = [
            '1' => 'Egg Floor',
            '2' => 'Low Wind Speed',
            '3' => 'Height Wind Speed',
            '4' => 'Panel Alarm',
        ];

        return $data[$value];
    }

    private function getbg($value){
        if($value == 5){$value = 0;}
        if($value == 6){$value = 1;}
        if($value == 7){$value = 2;}
        if($value == 8){$value = 3;}
        if($value == 9){$value = 4;}
        $data = [
            '0' => 'bg-red',
            '1' => 'bg-green',
            '2' => 'bg-blue',
            '3' => 'bg-purple',
            '4' => 'bg-orange',
        ];

        return $data[$value];
    }

    private function type($value){
        $data = [
            '1' => 'Alarm On',
            '2' => 'Alarm Off',
            '3' => 'Start Up',
            '4' => 'Shut Down',
            '5' => 'Params',
        ];

        return $data[$value];
    }

    private function type_icon($value){
        $data = [
            '1' => 'fa-dot-circle-o bg-green',
            '2' => 'fa-circle-o bg-red',
            '3' => 'fa-plug bg-blue',
            '4' => 'fa-power-off bg-black',
            '5' => 'fa-th-list bg-purple',
        ];

        return $data[$value];
    }

    private function param($value,$value2){
        $data = [
            '0'  => '',
            '4096'  => '(Require Temp.)',
            '3'     => '(Al Disable)',
            '8'     => '(Al. Dis. T2)',
            '12432' => '(Weight)',
            '800'   => '(Grow day)',
            '1829'  => '(Panel Al. Dis.)',
            '1828'  => '(Panel)',
            '3154'  => '(Type 1)',
            '3170'  => '(Type 2)',
            '7218'  => '(Temp Avg)',
            '3142'  => '(Humidity Mes.)',
            '3147'  => '(System state)',
            '3708'  => '(Type 3)',
            '1186'  => '(Al. Dis. T3)',
        ];

        if($value2 == 0){$value2 = '';}
        $hasil = $value2.$data[$value];
        return $hasil;
    }

    public function data_select_kandang(){
        $cek_sess = $this->konfigurasi->cek_js();
        if ($cek_sess == 0) {
            echo json_encode(['sess' => $cek_sess]);
        }else{
            $id_user = $this->session->userdata('id_user');

            $this->db->select('id,nama_kandang AS text');
            $this->db->from('data_kandang');
            $this->db->where([
                'kode_perusahaan'=>$id_user,
            ]);
            $this->db->order_by('id','ASC');
            $data1 = $this->db->get()->result();

            $dataini1 = [array('id'   => '','text' => '',)];
            foreach ($data1 as $data1) {
                $dataini = [
                    'id'   => $data1->id,
                    'text' => $data1->text,
                ];
                $dataini1[] = $dataini;
            }

            echo json_encode($dataini1);
        }
    }
}
