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
    }

	public function index(){
        echo "Silent Is Gold";
	}

    public function history_house_day(){
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
        ->setLastModifiedBy('Tono Ariyanto')
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


    public function reportmtd_history_house_day($url){
            $fil1 = $this->input->post('value1');
            $fil2 = $this->input->post('value2')[$url];
            $fil3 = $this->input->post('value3');
            $id_user   = $this->session->userdata('id_user');
            $fildari   = $this->input->post('value4');
            $filsampai = $this->input->post('value5');
            $filperiode = $this->input->post('value7');
            $nomor = $this->input->post('valnomor');
            $valpem = $this->input->post('valpem');

            $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
            $stringkd = [];
            foreach ($namakd as $key) {
                $stringkd[$key->id] = $key->nama_kandang;
            }

            if (($fildari == '-1' AND $filsampai == '-1') OR $fildari == '-1' OR $filsampai == '-1') {
                $fildari = $this->db->query("SELECT ((SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '4096' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3[0]."' ORDER BY grow_value DESC LIMIT 1) - 6) AS grow_value")->row_array()['grow_value'];
                $filsampai = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = 'DAY_1' AND nama_data = '4096' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3[0]."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
            }

            $esqlgen  = "SELECT id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,isi_value FROM `image2` ";
            $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = '".$fil1."' ";
            $esqlnamadata = "AND nama_data = '".$fil2."'";
            $esqlgrow = "AND grow_value BETWEEN '".$fildari."' AND '".$filsampai."' ";
            $esqlorder = "ORDER BY grow_value ASC";

            if($fil1 == 'DAY_1'){$addlabel = ' : Grow Day '.$fildari.' s/d '.$filsampai.' ';}
            $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
            $glabel = $label.$addlabel;
            $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';
            $xlabel = preg_replace("/[^a-zA-Z]/", "", $label);

            //Data Utama
            $esqlprimary  = $esqlgen;
            $esqlprimary .= $esqlnamadata;
            $esqlprimary .= "AND kode_kandang = '".$fil3."'";
            $esqlprimary .= "AND periode = '".$filperiode."' ";
            $esqlprimary .= $esqlgrow;
            $esqlprimary .= $esqlorder;

            $dataprimary = $this->db->query($esqlprimary)->result();

            foreach ($dataprimary as $value) {
                $adata[]  = $value->grow_value;
            }
            $isigrowday = $adata;

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
                $esqlsecondary .= $esqlnamadata;
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
                            if($isigrowday[$k] == $value3->grow_value){
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

            $filename = $label.$addlabel; // set filename for excel file to be exported

            $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
            ->setLastModifiedBy('Ansell')
            ->setTitle($filename)
            ->setSubject($filename)
            ->setKeywords('Ansell Jaya')
            ->setCategory('dboansell');
            if($nomor > 1){
            $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);                
            }
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
            if ($countsecond > 0) {
                for ($i=1; $i < count($linelabel); $i++) { 
                $sheet1->setCellValue($datacolum[($i - 1)].'22', $linelabel[$i]);
                }
            }

            $getsheet1 = $spreadsheet->getActiveSheet();

            //Setting Ukuran dan STYLE
            $getsheet1->getRowDimension(3)->setRowHeight(32);
            $getsheet1->getColumnDimension('A')->setWidth(5);
            $getsheet1->getColumnDimension('B')->setWidth(18);
            $getsheet1->getColumnDimension('C')->setWidth(15);
            $getsheet1->getColumnDimension('D')->setWidth(15);
            $getsheet1->getColumnDimension('E')->setWidth(15);
            if ($countsecond > 0) {
                for ($i=1; $i < count($linelabel); $i++) {
                $getsheet1->getColumnDimension($datacolum[($i - 1)])->setWidth(14);
                }
            }
            $getsheet1->getColumnDimension($datacolum[$nomor])->setWidth(1);

            $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
            $getsheet1->getStyle('A22:'.$datacolum[$nomor].'22')->applyFromArray($stylejudultabel);
            if($nomor <= 0){
                $kolommulai = 'E';
            }else{
                $kolommulai = $datacolum[($nomor - 1)];
            }
            $spreadsheet->getActiveSheet()->mergeCells($kolommulai.'22:'.$datacolum[($nomor)].'22');

            //GENERATE DATA
            $anomor = 1;
            $endrow = 0;

            foreach ($dataprimary as $dataisi) {
                $endrow = (int)$anomor+22;
                $sheet1->setCellValue('A'.$endrow, $anomor);
                $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->tanggal_value));
                $sheet1->setCellValue('C'.$endrow, $dataisi->jam_value);
                $sheet1->setCellValue('D'.$endrow, $dataisi->grow_value);
                $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
                if ($countsecond > 0) {
                    for ($i=1; $i < count($linelabel); $i++) {
                    $sheet1->setCellValue($datacolum[($i-1)].$endrow, $isisecondary[($i - 1)][($anomor - 1)]);
                    }
                }
                $spreadsheet->getActiveSheet()->mergeCells($kolommulai.$endrow.':'.$datacolum[$nomor].$endrow);
                $getsheet1->getStyle('A'.$endrow.':'.$datacolum[$nomor].$endrow)->applyFromArray($styleisitabel);
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
                new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$D$23:$D$'.$endrow, null, 4), // Q1 to Q4
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
            $chart->setBottomRightPosition($datacolum[$nomor].'20');

            // Add the chart to the worksheet
            $getsheet1->addChart($chart);

            $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:'.$datacolum[$nomor].$endrow);

            // Save Excel 2007 file
            $filename = 'download/print_'.$xlabel.'.xlsx';//$helper->getFilename(__FILE__);
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
        $filhour = $this->input->post('value6');
        $filperiode = $this->input->post('value7');
        $nomor = $this->input->post('valnomor');
        $valpem = $this->input->post('valpem');

        $namakd = $this->umum_model->get('data_kandang',['kode_perusahaan'=>$id_user])->result();
        $stringkd = [];
        foreach ($namakd as $key) {
            $stringkd[$key->id] = $key->nama_kandang;
        }


        if($filhour == '-1'){
            $filhour = $this->db->query("SELECT grow_value FROM image2 WHERE kategori = '".$fil1."' AND nama_data = '".$fil2."' AND kode_perusahaan = '".$id_user."' AND kode_kandang = '".$fil3."' ORDER BY grow_value DESC LIMIT 1")->row_array()['grow_value'];
        }

        $esqlgen  = "SELECT id,DATE_FORMAT(tanggal_value,'%d-%m-%Y') AS tanggal_value,CONCAT(LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0'),':',LPAD(SUBSTRING_INDEX(SUBSTRING_INDEX(jam_value, '-', 2), '-', -1), 2, '0'),':',LPAD(SUBSTRING_INDEX(jam_value, '-', -1), 2, '0')) AS jam_value,grow_value,isi_value FROM `image2` ";
        $esqlgen .= "WHERE kode_perusahaan = '".$id_user."' AND kategori = '".$fil1."' ";
        $esqlnamadata = "AND nama_data = '".$fil2."'";
        $esqlgrow = "AND grow_value = '".$filhour."'";
        $esqlorder = "ORDER BY tanggal_value ASC, LPAD(SUBSTRING_INDEX(jam_value, '-', 1), 2, '0') ASC";

        if($fil1 == 'HOUR_1'){$addlabel = ' : Grow Day '.$filhour.' ';}
        $label = $this->umum_model->get('kode_data',['kode_data'=>$fil2])->row_array()['nama_data'];
        $glabel = $label.$addlabel;
        $linelabel[0] = $stringkd[$fil3].' ('.$filperiode.')';
        $xlabel = preg_replace("/[^a-zA-Z]/", "", $label);

        //Data Utama
        $esqlprimary  = $esqlgen;
        $esqlprimary .= $esqlnamadata;
        $esqlprimary .= "AND kode_kandang = '".$fil3."'";
        $esqlprimary .= "AND periode = '".$filperiode."' ";
        $esqlprimary .= $esqlgrow;
        $esqlprimary .= $esqlorder;

        $dataprimary = $this->db->query($esqlprimary)->result();

        foreach ($dataprimary as $value) {
            $adata[]  = $value->jam_value;
        }
        $isigrowday = $adata;

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
            $esqlsecondary .= $esqlnamadata;
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
                        if($isigrowday[$k] == $value3->jam_value){
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

        $filename = $label.$addlabel; // set filename for excel file to be exported

        $spreadsheet->getProperties()->setCreator('Ansell Jaya Indonesia')
        ->setLastModifiedBy('Ansell')
        ->setTitle($filename)
        ->setSubject($filename)
        ->setKeywords('Ansell Jaya')
        ->setCategory('dboansell');
        if($nomor > 1){
        $spreadsheet->getActiveSheet()->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);                
        }
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
        if ($countsecond > 0) {
            for ($i=1; $i < count($linelabel); $i++) { 
            $sheet1->setCellValue($datacolum[($i - 1)].'22', $linelabel[$i]);
            }
        }

        $getsheet1 = $spreadsheet->getActiveSheet();

        //Setting Ukuran dan STYLE
        $getsheet1->getRowDimension(3)->setRowHeight(32);
        $getsheet1->getColumnDimension('A')->setWidth(5);
        $getsheet1->getColumnDimension('B')->setWidth(18);
        $getsheet1->getColumnDimension('C')->setWidth(15);
        $getsheet1->getColumnDimension('D')->setWidth(15);
        $getsheet1->getColumnDimension('E')->setWidth(15);
        if ($countsecond > 0) {
            for ($i=1; $i < count($linelabel); $i++) {
            $getsheet1->getColumnDimension($datacolum[($i - 1)])->setWidth(14);
            }
        }
        $getsheet1->getColumnDimension($datacolum[$nomor])->setWidth(1);

        $getsheet1->getStyle('A1')->applyFromArray($stylejudul);
        $getsheet1->getStyle('A22:'.$datacolum[$nomor].'22')->applyFromArray($stylejudultabel);
        if($nomor <= 0){
            $kolommulai = 'E';
        }else{
            $kolommulai = $datacolum[($nomor - 1)];
        }
        $spreadsheet->getActiveSheet()->mergeCells($kolommulai.'22:'.$datacolum[($nomor)].'22');

        //GENERATE DATA
        $anomor = 1;
        $endrow = 0;

        foreach ($dataprimary as $dataisi) {
            $endrow = (int)$anomor+22;
            $sheet1->setCellValue('A'.$endrow, $anomor);
            $sheet1->setCellValue('B'.$endrow, tgl_indo_terbalik($dataisi->tanggal_value));
            $sheet1->setCellValue('C'.$endrow, $dataisi->jam_value);
            $sheet1->setCellValue('D'.$endrow, $dataisi->grow_value);
            $sheet1->setCellValue('E'.$endrow, floatval($dataisi->isi_value));
            if ($countsecond > 0) {
                for ($i=1; $i < count($linelabel); $i++) {
                $sheet1->setCellValue($datacolum[($i-1)].$endrow, $isisecondary[($i - 1)][($anomor - 1)]);
                }
            }
            $spreadsheet->getActiveSheet()->mergeCells($kolommulai.$endrow.':'.$datacolum[$nomor].$endrow);
            $getsheet1->getStyle('A'.$endrow.':'.$datacolum[$nomor].$endrow)->applyFromArray($styleisitabel);
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
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, $xlabel.'!$C$23:$C$'.$endrow, null, 4), // Q1 to Q4
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
        $chart->setBottomRightPosition($datacolum[$nomor].'20');

        // Add the chart to the worksheet
        $getsheet1->addChart($chart);

        $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:'.$datacolum[$nomor].$endrow);

        // Save Excel 2007 file
        $filename = 'download/print_'.$xlabel.'.xlsx';//$helper->getFilename(__FILE__);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->setIncludeCharts(true);
        $callStartTime = microtime(true);
        $writer->save($filename);

        echo json_encode(['status'=>true,'url'=>base_url($filename)]);
    }

    public function ydhistory_house_day($value='')
    {
        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->fromArray(
            [
                ['', 2010, 2011, 2012],
                ['Q1', 12, 15, 21],
                ['Q2', 56, 73, 86],
                ['Q3', 52, 61, 69],
                ['Q4', 30, 32, 0],
            ]
        );

        // Set the Labels for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesLabels = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$B$1', null, 1), // 2010
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$C$1', null, 1), // 2011
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$D$1', null, 1), // 2012
        ];
        // Set the X-Axis Labels
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $xAxisTickValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_STRING, 'Worksheet!$A$2:$A$5', null, 4), // Q1 to Q4
        ];
        // Set the Data values for each data series we want to plot
        //     Datatype
        //     Cell reference for data
        //     Format Code
        //     Number of datapoints in series
        //     Data values
        //     Data Marker
        $dataSeriesValues = [
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$B$2:$B$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$C$2:$C$5', null, 4),
            new DataSeriesValues(DataSeriesValues::DATASERIES_TYPE_NUMBER, 'Worksheet!$D$2:$D$5', null, 4),
        ];
        $dataSeriesValues[2]->setLineWidth(60000);

        // Build the dataseries
        $series = new DataSeries(
            DataSeries::TYPE_LINECHART, // plotType
            DataSeries::GROUPING_STACKED, // plotGrouping
            range(0, count($dataSeriesValues) - 1), // plotOrder
            $dataSeriesLabels, // plotLabel
            $xAxisTickValues, // plotCategory
            $dataSeriesValues,        // plotValues
            null,
            null,
            null,
            DataSeries::VALUE_AXIS_POSITION_RIGHT //Sumbu Y Berada dikanan
        );

        // Set the series in the plot area
        $plotArea = new PlotArea(null, [$series]);
        // Set the chart legend
        $legend = new Legend(Legend::POSITION_TOPRIGHT, null, false);

        $title = new Title('Test Stacked Line Chart');
        $yAxisLabel = new Title('Value ($k)');

        // Create the chart
        $chart = new Chart(
            'chart1', // name
            $title, // title
            $legend, // legend
            $plotArea, // plotArea
            true, // plotVisibleOnly
            0, // displayBlanksAs
            null, // xAxisLabel
            $yAxisLabel  // yAxisLabel
        );


        // Set the position where the chart should appear in the worksheet
        $chart->setTopLeftPosition('A7');
        $chart->setBottomRightPosition('H20');

        // Add the chart to the worksheet
        $worksheet->addChart($chart);

            // Save Excel 2007 file
            $filename = 'download/print_test.xlsx';//$helper->getFilename(__FILE__);
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
