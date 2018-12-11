<?php
set_time_limit(0);
//ini_set("memory_limit","4096mb");
ini_set("memory_limit", "-1");
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
//$xml = simplexml_load_file("15.1-EX_XML_EDR_UO.xml");
//$xml = simplexml_load_file("test.xml");


    $spreadsheet = new Spreadsheet();
$sheet=$spreadsheet->setActiveSheetIndex(0);
$pool = new \Cache\Adapter\Apcu\ApcuCachePool();
$simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);

\PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);
$i=1;
$j=1;
$xml_reader = new XMLReader;
$xml_reader->open('15.1-EX_XML_EDR_UO.xml');
//count_XML($xml_reader);die;
// move the pointer to the first product
while ($xml_reader->read() && $xml_reader->name != 'RECORD');
//3302862
// loop through the products
while ($xml_reader->name == 'RECORD')
{
    // load the current xml element into simplexml and we’re off and running!
    $xml = simplexml_load_string($xml_reader->readOuterXML());

    // now you can use your simpleXML object ($xml).
    $sheet->setCellValue('A'.$i, $xml->NAME);
    $sheet->setCellValue('B'.$i, $xml->SHORT_NAME);
    $sheet->setCellValue('C'.$i, $xml->EDRPOU);
    $sheet->setCellValue('D'.$i, $xml->ADDRESS);
    $sheet->setCellValue('E'.$i, $xml->BOSS);
    $sheet->setCellValue('F'.$i, $xml->KVED);
    $sheet->setCellValue('G'.$i, $xml->STAN);
if ($i%1000==0) terminal_log($i);
//if ($i==8){
//    //write?
//   merge($spreadsheet);
//
//    terminal_log($i);
//    $i=$sheet->getHighestRow()+1;
//}
//elseif ($i==11){
//    terminal_log($i);
//    merge($spreadsheet);
//    $i=$sheet->getHighestRow()+1;
//    echo $i;
//    die;
//    //$sheet=$spreadsheet->getActiveSheet();
//    //$sheet=$spreadsheet->setActiveSheetIndex(+1);
//}
    // move the pointer to the next product
    $i++;
    //$j++;
    $xml_reader->next('RECORD');
//if($j==200)die;
}
$objWriter = new Xlsx($spreadsheet);
$objWriter->save('test.xlsx');
// don’t forget to close the file
$xml_reader->close();
function merge($spreadsheet){
    $objWriter = new Xlsx($spreadsheet);
    $objWriter->save('test.xlsx');
    unset($objWriter);
    $reader  = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    //$reader->setReadFilter( new MyReadFilter() );
    $spreadsheet = $reader->load('test.xlsx');
    $sheet = $spreadsheet->getActiveSheet();
        //$sheet=$spreadsheet->setActiveSheetIndex(0);
    return $sheet;
}
function terminal_log($message){
    //$eol = $this->isCli() ? PHP_EOL : '<br />';
    echo date('H:i:s ') . $message . PHP_EOL;
}
function count_XML($xml_reader){
    $count_XML = 0;
    while ($xml_reader->read()){
        if($xml_reader->name == 'RECORD')
            $count_XML++;
    }

    terminal_log('Size XML is '.$count_XML);
    return $count_XML;
}
class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    public function readCell($column, $row, $worksheetName = '') {
          return false;
    }
}
