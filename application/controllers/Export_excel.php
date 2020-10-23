<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Helper\Sample;

//require FCPATH . 'vendor/phpoffice/phpspreadsheet/samples/Header.php';

class Export_excel extends CI_Controller {

    function __construct(){
        parent::__construct();
		$this->load->library('datatables');
        $this->load->model('grafik_model');
    }

	public function index(){
        echo "Silent Is Gold";
	}

    public function history_house_hour(){
        $fil1 = 'HOUR_1';
        $fil2 = $this->input->post('value1');
        $fil3 = $this->input->post('value2');
        $id_user   = $this->session->userdata('id_user');
        $filhour   = $this->input->post('value6');
        $filperiode = $this->input->post('value5');

        if($filhour == '-1'){
            $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
        }

        $hasil_data = $this->db->query("SELECT id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,isi_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_kandang = '".$fil3."' AND kode_perusahaan = '".$id_user."' AND periode = '".$filperiode."' AND grow_value = '".$filhour."' ")->result();

        //SET LABEL
        if($fil1 == 'HOUR_1'){$addlabel = ' : Grow Day '.$filhour.' ';}
        $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
        $xlabel = preg_replace("/[^a-zA-Z]/", "", $label);

        $helper = new Sample();
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $filename = $label.$addlabel; // set filename for excel file to be exported

        $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
        ->setLastModifiedBy('Ansell')
        ->setTitle($filename)
        ->setSubject($filename)
        ->setKeywords('Ansell Jaya')
        ->setCategory('dboansell');
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.95);
        
        //STYLE
        $stylejudul = [
            'font' => [
                'size' => 14,
                'bold' => true,
            ]
        ];
        $stylejudultabel = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabel = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabelcenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        // Add some data
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);

        // manually set table data value
        $sheet1->setCellValue('A1', $filename);

        $sheet1->setCellValue('A22', 'No');
        $sheet1->setCellValue('B22', 'Tanggal');
        $sheet1->setCellValue('C22', 'Jam');
        $sheet1->setCellValue('D22', 'Grow Day');
        $sheet1->setCellValue('E22', $xlabel);

        $getsheet1 = $spreadsheet->getActiveSheet();

        //Setting Ukuran dan STYLE
        $getsheet1->getRowDimension(3)->setRowHeight(32);
        $getsheet1->getColumnDimension('A')->setWidth(5);
        $getsheet1->getColumnDimension('B')->setWidth(18);
        $getsheet1->getColumnDimension('C')->setWidth(15);
        $getsheet1->getColumnDimension('D')->setWidth(15);
        $getsheet1->getColumnDimension('E')->setWidth(26);
        $getsheet1->getColumnDimension('F')->setWidth(1);

        $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
        $getsheet1->getStyle('A22:F22')->applyFromArray($stylejudultabel);
        $spreadsheet->getActiveSheet()->mergeCells('E22:F22');

        //GENERATE DATA
        $nomor = 1;
        $endrow = 0;

        foreach ($hasil_data as $dataisi) {
            $endrow = (int)$nomor+22;
            $sheet1->setCellValue('A'.$endrow, $nomor);
            $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->tanggal_value));
            $sheet1->setCellValue('C'.$endrow, $dataisi->jam_value);
            $sheet1->setCellValue('D'.$endrow, $dataisi->grow_value);
            $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
            $spreadsheet->getActiveSheet()->mergeCells('E'.$endrow.':F'.$endrow);
            $getsheet1->getStyle('A'.$endrow.':F'.$endrow)->applyFromArray($styleisitabel);
            $nomor++;
        }

        $getsheet1->setTitle($xlabel);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$E$22', null, 1), // 2010
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$C$23:$C$'.$endrow, null, 1), // Q1 to Q4
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$E$23:$E$'.$endrow, null, 1),
        ];

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);

        $title = new Title('Grafik '.$filename);
        $yAxisLabel = new Title('Value');
        $xAxisLabel = new Title('Growday');

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            null  // yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A3');
        $chart->setBottomRightPosition('F20');

        // Add the chart to the worksheet
        $getsheet1->addChart($chart);

        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:F'.$endrow);

        // Save Excel 2007 file
        $filename = 'download/print.xlsx';//$helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);

        echo json_encode(['status'=>true,'url'=>base_url($filename)]);
    }

    public function reportmtd_history_house_hour($url){
        $fil1 = $this->input->post('value1');
        $fil2 = $this->input->post('value2')[$url];
        $fil3 = $this->input->post('value3');
        $id_user   = $this->session->userdata('id_user');
        $filhour1 = $this->input->post('value61');
        $filhour2 = $this->input->post('value62');
        $filperiode = $this->input->post('value7');
        $nomor = $this->input->post('valnomor');
        $valpem = $this->input->post('valpem');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

        if($filhour1 == $filhour2){
            if($filhour1 == '-1'){
                $filhour1 = $this->db->query("SELECT growday FROM data_record WHERE periode = '".$filperiode."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY growday DESC LIMIT 1")->row_array()['growday'];
                $filhour2 = $filhour1;
            }
            $esqlgrow = "AND growday = '".$filhour1."'";
        }else{
            $esqlgrow = "AND growday BETWEEN '".$filhour1."' AND '".$filhour2."'";
        }

        $esqlgen  = "SELECT growday, date_record, DATE_FORMAT(date_record,'%d-%m-%Y') AS ttanggal_value,DATE_FORMAT(date_record,'%H:%i:%s') AS jjam_value,".$fil2." AS isi_value FROM `data_record` ";
        $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' ";
        $esqlorder = "ORDER BY date_record ASC";

        if($fil1 == 'HOUR_1'){
            if($filhour1 == $filhour2){
                $addlabel = ' : Grow Day '.$filhour1.' ';
            }else{
                $addlabel = ' : Grow Day '.$filhour1.' - '.$filhour2.' ';
            }
        }

        $label = $this->grafik_model->list_data('all');
        $glabel = $label[$fil2].$addlabel;
        $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';
        $xlabel = preg_replace("/[^a-zA-Z]/", "", $label[$fil2]);

        //Data Utama
        $esqlprimary  = $esqlgen;
        $esqlprimary .= "AND kode_kandang = '".$fil3."'";
        $esqlprimary .= "AND periode = '".$filperiode."' ";
        $esqlprimary .= $esqlgrow;
        $esqlprimary .= $esqlorder;

        $dataprimary = $this->db->query($esqlprimary)->result();

        $adata = [];
        foreach ($dataprimary as $value) {
            $jam = date_format(date_create($value->date_record),"H");
            $adata[] = '('.$value->growday.') - '.$jam.':00';
        }
        $isigrowday = $adata;

        $bdata = [];
        foreach ($dataprimary as $value2) {
            $bdata[] = $value2->isi_value;
        }
        $isiprimary = $bdata;

        //Data Pembanding
        $urutan = 7;
        $countsecond = 0;
        for ($i=0; $i < $nomor; $i++) { 
            $urutan = $urutan + 1;
            $esqlsecondary  = $esqlgen;
            $esqlsecondary .= "AND kode_kandang = '".$valpem['valkandang'.$urutan]."'";
            $esqlsecondary .= "AND periode = '".$valpem['valperiode'.$urutan]."' ";
            $esqlsecondary .= $esqlgrow;
            $esqlsecondary .= $esqlorder;
            $datasecondary = $this->db->query($esqlsecondary)->result();
            $cdata = [];
            if ($datasecondary == null) {
                for ($j=0; $j < count($isigrowday); $j++) { 
                    $cdata[$j] = 0;
                }
            }else{
                for ($k=0; $k < count($isigrowday); $k++) { 
                    $cdata[$k] = '';
                    foreach ($datasecondary as $value3) {
                        $jam2 = date_format(date_create($value->date_record),"H");
                        if($isigrowday[$k] == ('('.$value->growday.') - '.$jam.':00')){
                            $cdata[$k] = $value3->isi_value;
                        }
                    }
                    if($cdata[$k] == '' OR $cdata[$k] == null){
                        $cdata[$k] = 0; 
                    }
                }
            }
            $isisecondary[] = $cdata;
            $countsecond = $countsecond + 1;
            $linelabel[$i+1] = $stringkd[$valpem['valkandang'.$urutan]].' ('.$valpem['valperiode'.$urutan].')';
        }

        $datacolum = ['F','G','H','I','J','K','L','M','N'];

        $helper = new Sample();
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $filename = $label[$fil2].$addlabel; // set filename for excel file to be exported

        $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
        ->setLastModifiedBy('Ansell')
        ->setTitle($filename)
        ->setSubject($filename)
        ->setKeywords('Ansell Jaya')
        ->setCategory('dboansell');

        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);                
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.95);
    
        //STYLE
        $stylejudul = [
            'font' => [
                'size' => 14,
                'bold' => true,
            ]
        ];
        $stylejudultabel = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabel = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabelcenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        // Add some data
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);

        // manually set table data value
        $sheet1->setCellValue('A1', $filename);

        $sheet1->setCellValue('A22', 'No');
        $sheet1->setCellValue('B22', 'Tanggal');
        $sheet1->setCellValue('C22', 'Jam');
        $sheet1->setCellValue('D22', 'Grow Day');
        $sheet1->setCellValue('E22', $linelabel[0]);
        $titrowlast = 0;
        if ($countsecond > 0) {
            for ($i=1; $i < count($linelabel); $i++) { 
            $sheet1->setCellValue($datacolum[($i - 1)].'22', $linelabel[$i]);
            $titrowlast = $i - 1;
            }
        }
        $sheet1->setCellValue($datacolum[7].'22', 'Grow & Jam');

        $getsheet1 = $spreadsheet->getActiveSheet();

        //Setting Ukuran dan STYLE
        $getsheet1->getRowDimension(3)->setRowHeight(32);
        $getsheet1->getColumnDimension('A')->setWidth(5);
        $getsheet1->getColumnDimension('B')->setWidth(18);
        $getsheet1->getColumnDimension('C')->setWidth(15);
        $getsheet1->getColumnDimension('D')->setWidth(15);
        $getsheet1->getColumnDimension('E')->setWidth(15);
        $getsheet1->getColumnDimension($datacolum[0])->setWidth(14);
        $getsheet1->getColumnDimension($datacolum[1])->setWidth(14);
        $getsheet1->getColumnDimension($datacolum[2])->setWidth(14);
        $getsheet1->getColumnDimension($datacolum[3])->setWidth(14);
        $getsheet1->getColumnDimension($datacolum[4])->setWidth(1);
        $getsheet1->getColumnDimension($datacolum[7])->setWidth(14);

        if($nomor <= 0){
            $kolommulai = 'E';
        }else if($nomor == 4){
            $kolommulai = $datacolum[$nomor];
        }else{
            $kolommulai = $datacolum[($nomor - 1)];
        }

        $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
        $getsheet1->getStyle('A22:'.$kolommulai.'22')->applyFromArray($stylejudultabel);
        $getsheet1->getStyle($datacolum[7].'22')->applyFromArray($stylejudultabel);

        $spreadsheet->getActiveSheet()->mergeCells($datacolum[3].'22:'.$datacolum[4].'22');

        //GENERATE DATA
        $anomor = 1;
        $endrow = 0;

        foreach ($dataprimary as $dataisi) {
            $endrow = (int)$anomor+22;
            $sheet1->setCellValue('A'.$endrow, $anomor);
            $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->ttanggal_value));
            $sheet1->setCellValue('C'.$endrow, $dataisi->jjam_value);
            $sheet1->setCellValue('D'.$endrow, $dataisi->growday);
            $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
            $rowlast = 0;
            if ($countsecond > 0) {
                for ($i=1; $i < count($linelabel); $i++) {
                    $sheet1->setCellValue($datacolum[($i-1)].$endrow, $isisecondary[($i - 1)][($anomor - 1)]);
                    $rowlast = $i - 1;
                }
            }
            $sheet1->setCellValue($datacolum[7].$endrow, '('.$dataisi->growday.') '.$dataisi->jjam_value);
            $spreadsheet->getActiveSheet()->mergeCells($datacolum[3].$endrow.':'.$datacolum[4].$endrow);
            $getsheet1->getStyle('A'.$endrow.':'.$kolommulai.$endrow)->applyFromArray($styleisitabel);
            $getsheet1->getStyle($datacolum[7].$endrow)->applyFromArray($styleisitabel);
            $anomor++;
        }

        $getsheet1->setTitle($xlabel);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$E$22', null, 1), // 2010
        ];

        if ($countsecond > 0) {
            for ($i=1; $i < count($linelabel); $i++) {
               $dataSeriesLabels[$i] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$'.$datacolum[($i-1)].'$22', null, 1);
            }
        }

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$'.$datacolum[7].'$23:$'.$datacolum[7].'$'.$endrow, null, 4), // Q1 to Q4
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$E$23:$E$'.$endrow, null, 4),
        ];

        if ($countsecond > 0) {
            for ($i=1; $i < count($linelabel); $i++) {
                $dataSeriesValues[$i] = new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$'.$datacolum[($i-1)].'$23:$'.$datacolum[($i-1)].'$'.$endrow, null, 4);
            }
        }

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_CLUSTERED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );

        // Set additional dataseries parameters
        //     Make it a vertical column rather than a horizontal bar graph
        $series->setPlotDirection(DataSeries::DIRECTION_COL);

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);

        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);

        $title = new Title('Grafik '.$filename);
        $yAxisLabel = new Title('Value');
        $xAxisLabel = new Title('Growday');

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            null  // yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A3');
        $chart->setBottomRightPosition($datacolum[4].'20');

        // Add the chart to the worksheet
        $getsheet1->addChart($chart);

        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:'.$datacolum[4].$endrow);

        // Save Excel 2007 file
        $filename = 'download/print_'.$xlabel.'.xlsx';//$helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);

        echo json_encode(['status'=>true,'url'=>base_url($filename)]);
    }

    public function ydhistory_house_hour(){
        $data1kandang = $this->input->post('data1kandang');
        $data1periode = $this->input->post('data1periode');
        $data1data    = $this->input->post('data1data');
        $data2kandang = $this->input->post('data2kandang');
        $data2periode = $this->input->post('data2periode');
        $data2data    = $this->input->post('data2data');
        $filhour1     = $this->input->post('value61');
        $filhour2     = $this->input->post('value62');
        $namagrafik   = $this->input->post('namagrafik');
        $id_user      = $this->session->userdata('id_user');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }

        if($filhour1 == $filhour2){
            if($filhour1 == '-1'){
                $filhour1 = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = 'HOUR_1' AND nama_data = '".$data1data."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$data1kandang."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
                $filhour2 = $filhour1;
            }
            $esqlgrow = "AND grow_value = '".$filhour1."'";
        }else{
            $esqlgrow = "AND grow_value BETWEEN '".$filhour1."' AND '".$filhour2."' ";
        }

        $esqlgen  = "SELECT grow_value AS grow, DATE_FORMAT(image2.tanggal_value,'%d-%m-%Y') AS ttanggal_value, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') AS jjam_value,isi_value FROM `image2` ";
        $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = 'HOUR_1' ";
        $esqlorder = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        $label = $this->umum_model->get('kode_data',['kode_data'=>$data1data])->row_array()['nama_data'];
        $glabel = $this->input->post('namagrafik');
        $linelabel[0] = $stringkd[$data1kandang].' ('.$data1periode.') - '.$label;
        $xlabel = preg_replace("/[^a-zA-Z]/", "", $namagrafik);

        //Data Utama
        $esql1  = $esqlgen;
        $esql1 .= "AND nama_data = '".$data1data."'";
        $esql1 .= "AND kode_kandang = '".$data1kandang."'";
        $esql1 .= "AND periode = '".$data1periode."' ";
        $esql1 .= $esqlgrow;
        $esql1 .= $esqlorder;

        $dataprimary1 = $this->db->query($esql1)->result();

        $adata = [];
        foreach ($dataprimary1 as $value) {
            $adata[] = '('.$value->grow.') - '.$value->jjam_value.':00';
        }
        $isigrowday1 = $adata;

        $bdata = [];
        foreach ($dataprimary1 as $value2) {
            $bdata[] = $value2->isi_value;
        }
        $isidatagrafik[0] = $bdata;
        //END Data Utama

        //Data 2
        $esql2  = $esqlgen;
        $esql2 .= "AND nama_data = '".$data2data."'";
        $esql2 .= "AND kode_kandang = '".$data2kandang."'";
        $esql2 .= "AND periode = '".$data2periode."' ";
        $esql2 .= $esqlgrow;
        $esql2 .= $esqlorder;

        $dataprimary2 = $this->db->query($esql2)->result();
        $cdata2 = [];
        if ($dataprimary2 == null) {
            for ($j=0; $j < count($isigrowday1); $j++) { 
                $cdata2[$j] = 0;
            }
        }else{
            for ($k=0; $k < count($isigrowday1); $k++) { 
                $cdata2[$k] = '';
                foreach ($dataprimary2 as $value3) {
                    if($isigrowday1[$k] == '('.$value3->grow.') - '.$value3->jjam_value.':00'){
                        $cdata2[$k] = $value3->isi_value;
                    }
                }
                if($cdata2[$k] == '' OR $cdata2[$k] == null){
                    $cdata2[$k] = 0; 
                }
            }
        }

        $isidatagrafik[1] = $cdata2;
        $label2 = $this->umum_model->get('kode_data',['kode_data'=>$data2data])->row_array()['nama_data'];
        $linelabel[1] = $stringkd[$data2kandang].' ('.$data2periode.') - '.$label2;
        //END Data 2

        $datacolum = ['F','G','H','I','J','K','L','M','N'];
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $filename = $namagrafik; // set filename for excel file to be exported

        $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
        ->setLastModifiedBy('Ansell')
        ->setTitle($filename)
        ->setSubject($filename)
        ->setKeywords('Ansell Jaya')
        ->setCategory('dboansell');

        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);                
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.95);

        //STYLE
        $stylejudul = [
            'font' => [
                'size' => 14,
                'bold' => true,
            ]
        ];
        $stylejudultabel = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabel = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabelcenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        // Add some data
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);

        // manually set table data value
        $sheet1->setCellValue('A1', $namagrafik);

        $sheet1->setCellValue('A22', 'No');
        $sheet1->setCellValue('B22', 'Tanggal');
        $sheet1->setCellValue('C22', 'Jam');
        $sheet1->setCellValue('D22', 'Grow Day');
        $sheet1->setCellValue('E22', $linelabel[0]);
        $sheet1->setCellValue('F22', $linelabel[1]);
        $sheet1->setCellValue($datacolum[7].'22', 'Grow & Jam');

        $getsheet1 = $spreadsheet->getActiveSheet();

        //Setting Ukuran dan STYLE
        $getsheet1->getRowDimension(3)->setRowHeight(32);
        $getsheet1->getColumnDimension('A')->setWidth(5);
        $getsheet1->getColumnDimension('B')->setWidth(18);
        $getsheet1->getColumnDimension('C')->setWidth(15);
        $getsheet1->getColumnDimension('D')->setWidth(15);
        $getsheet1->getColumnDimension('E')->setWidth(25);
        $getsheet1->getColumnDimension($datacolum[0])->setWidth(24);
        $getsheet1->getColumnDimension($datacolum[1])->setWidth(10);
        $getsheet1->getColumnDimension($datacolum[2])->setWidth(6);
        $getsheet1->getColumnDimension($datacolum[3])->setWidth(6);
        $getsheet1->getColumnDimension($datacolum[4])->setWidth(1);
        $getsheet1->getColumnDimension($datacolum[7])->setWidth(14);

        $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
        $getsheet1->getStyle('A22:F22')->applyFromArray($stylejudultabel);
        $getsheet1->getStyle($datacolum[7].'22')->applyFromArray($stylejudultabel);

        $spreadsheet->getActiveSheet()->mergeCells($datacolum[3].'22:'.$datacolum[4].'22');

        //GENERATE DATA
        $anomor = 1;

        foreach ($dataprimary1 as $dataisi) {
            $endrow = (int)$anomor+22;
            $sheet1->setCellValue('A'.$endrow, $anomor);
            $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->ttanggal_value));
            $sheet1->setCellValue('C'.$endrow, $dataisi->jjam_value.':00');
            $sheet1->setCellValue('D'.$endrow, $dataisi->grow);
            $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
            $sheet1->setCellValue('F'.$endrow, $isidatagrafik[1][($anomor - 1)]);
            $sheet1->setCellValue($datacolum[7].$endrow, '('.$dataisi->grow.') '.$dataisi->jjam_value.':00');
            $spreadsheet->getActiveSheet()->mergeCells($datacolum[3].$endrow.':'.$datacolum[4].$endrow);
            $getsheet1->getStyle('A'.$endrow.':F'.$endrow)->applyFromArray($styleisitabel);
            $getsheet1->getStyle($datacolum[7].$endrow)->applyFromArray($styleisitabel);
            $anomor++;
        }

        $getsheet1->setTitle($xlabel);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$E$22', null, 1),
        ];

        $dataSeriesSecondaryLabels  = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$F$22', null, 1),
        ];

        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$'.$datacolum[7].'$23:$'.$datacolum[7].'$'.$endrow, null, 4), // Q1 to Q4
        ];

        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$E$23:$E$'.$endrow, null, 4),
        ];

        $dataSeriesSecondaryValues  = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$F$23:$F$'.$endrow, null, 4),
        ];
        
        //	Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues        // plotValues
        );
        
        //	Build the dataseries
        $seriesSecondary  = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesSecondaryValues) - 1), // plotOrder
            $dataSeriesSecondaryLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesSecondaryValues        // plotValues
        );
        
        //construct(  $plotType = null, 
        //            $plotGrouping = null, 
        //            array $plotOrder = [], 
        //            array $plotLabel = [], 
        //            array $plotCategory = [], 
        //            array $plotValues = [], 
        //            $plotDirection = null, 
        //            $smoothLine = false, 
        //            $plotStyle = null)
        
        
        //	Set the series in the plot area
        $plotArea = new PlotArea(null, [$series], [$seriesSecondary]);
        //	Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);
        
        $title = new Title('Grafik '.$namagrafik);

        $yAxisLabel = new Title($label);
        
        $secondaryYAxisLabel  = new Title($label2);
        
        //	Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel,  // yAxisLabel
            null, // xAxis
            null,  // yAxis
            null,  // majorGridlines
            null,  //minor Gridlines
            $secondaryYAxisLabel    // secondaryYAxisLabel
        );
        
        //	Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A3');
        $chart->setBottomRightPosition($datacolum[4].'21');
        
        // Add the chart to the worksheet
        $getsheet1->addChart($chart);

        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:'.$datacolum[4].$endrow);

        // Save Excel 2007 file
        $filename = 'download/print_'.$xlabel.'.xlsx';//$helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);

        echo json_encode(['status'=>true,'url'=>base_url($filename)]);
    }

    public function zzydhistory_house_day(){
        $fil1 = 'DAY_1';
        $fil2 = $this->input->post('value1');
        $fil3 = $this->input->post('value2');
        $id_user   = $this->session->userdata('id_user');
        $fildari   = $this->input->post('value3');
        $filsampai = $this->input->post('value4');
        $filperiode = $this->input->post('value5');

        if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
            $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
            $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' AND periode = '".$filperiode."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
        }

        $hasil_data = $this->db->query("SELECT id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,isi_value FROM image2 WHERE kategori = '".$fil1."'AND nama_data = '".$fil2."' AND kode_kandang = '".$fil3."' AND kode_perusahaan = '".$id_user."' AND periode = '".$filperiode."' AND grow_value BETWEEN '".$fildari."' AND '".$filsampai."' ")->result();

        //SET LABEL
        if($fil1 == 'DAY_1'){$addlabel = ' : Grow Day '.$fildari.' s/d '.$filsampai.' ';}
        $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
        $xlabel = preg_replace("/[^a-zA-Z]/", "", $label);

        $helper = new Sample();
        $spreadsheet = new Spreadsheet(); // instantiate Spreadsheet

        $filename = $label.$addlabel; // set filename for excel file to be exported

        $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
        ->setLastModifiedBy('Ansell')
        ->setTitle($filename)
        ->setSubject($filename)
        ->setKeywords('Ansell Jaya')
        ->setCategory('dboansell');
        $spreadsheet->getActiveSheet()->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
        $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.95);
        
        //STYLE
        $stylejudul = [
            'font' => [
                'size' => 14,
                'bold' => true,
            ]
        ];
        $stylejudultabel = [
            'font' => [
                'size' => 12,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabel = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];
        $styleisitabelcenter = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ]
        ];

        // Add some data
        $sheet1 = $spreadsheet->setActiveSheetIndex(0);

        // manually set table data value
        $sheet1->setCellValue('A1', $filename);

        $sheet1->setCellValue('A22', 'No');
        $sheet1->setCellValue('B22', 'Tanggal');
        $sheet1->setCellValue('C22', 'Jam');
        $sheet1->setCellValue('D22', 'Grow Day');
        $sheet1->setCellValue('E22', $xlabel);

        $getsheet1 = $spreadsheet->getActiveSheet();

        //Setting Ukuran dan STYLE
        $getsheet1->getRowDimension(3)->setRowHeight(32);
        $getsheet1->getColumnDimension('A')->setWidth(5);
        $getsheet1->getColumnDimension('B')->setWidth(18);
        $getsheet1->getColumnDimension('C')->setWidth(15);
        $getsheet1->getColumnDimension('D')->setWidth(15);
        $getsheet1->getColumnDimension('E')->setWidth(26);
        $getsheet1->getColumnDimension('F')->setWidth(1);

        $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
        $getsheet1->getStyle('A22:F22')->applyFromArray($stylejudultabel);
        $spreadsheet->getActiveSheet()->mergeCells('E22:F22');

        //GENERATE DATA
        $nomor = 1;
        $endrow = 0;

        foreach ($hasil_data as $dataisi) {
            $endrow = (int)$nomor+22;
            $sheet1->setCellValue('A'.$endrow, $nomor);
            $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->tanggal_value));
            $sheet1->setCellValue('C'.$endrow, $dataisi->jam_value);
            $sheet1->setCellValue('D'.$endrow, $dataisi->grow_value);
            $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
            $spreadsheet->getActiveSheet()->mergeCells('E'.$endrow.':F'.$endrow);
            $getsheet1->getStyle('A'.$endrow.':F'.$endrow)->applyFromArray($styleisitabel);
            $nomor++;
        }

        $getsheet1->setTitle($xlabel);

        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$E$22', null, 1), // 2010
        ];
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$D$23:$D$'.$endrow, null, 4), // Q1 to Q4
        ];
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, $xlabel.'!$E$23:$E$'.$endrow, null, 4),
        ];

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues // plotValues
        );

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOP, null, false);

        $title = new Title('Grafik '.$filename);
        $yAxisLabel = new Title('Value');
        $xAxisLabel = new Title('Growday');

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            null  // yAxisLabel
        );

        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A3');
        $chart->setBottomRightPosition('F20');

        // Add the chart to the worksheet
        $getsheet1->addChart($chart);

        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:F'.$endrow);

        // Save Excel 2007 file
        $filename = 'download/print.xlsx';//$helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);

        echo json_encode(['status'=>true,'url'=>base_url($filename)]);
    }}
