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
   // $spreadsheet1 = new Spreadsheet();
$sheet=$spreadsheet->setActiveSheetIndex(0);
//$sheet1=$spreadsheet1->setActiveSheetIndex(0);
//$pool = new \Cache\Adapter\Apcu\ApcuCachePool();
//$simpleCache = new \Cache\Bridge\SimpleCache\SimpleCacheBridge($pool);
//
//\PhpOffice\PhpSpreadsheet\Settings::setCache($simpleCache);
$i=1;
$j=1;
$xml_reader = new XMLReader;
//$xml_reader->open('test.xml');
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
    //проверка на нужный город гдето тут должна быть
    if(checkCity($xml->ADDRESS)==true)
    {
        $sheet->setCellValue('A'.$i, $xml->NAME);
        $sheet->setCellValue('B'.$i, $xml->SHORT_NAME);
        $sheet->setCellValue('C'.$i, $xml->EDRPOU);
        $sheet->setCellValue('D'.$i, $xml->ADDRESS);
        $sheet->setCellValue('E'.$i, $xml->BOSS);
        $sheet->setCellValue('F'.$i, $xml->KVED);
        $sheet->setCellValue('G'.$i, $xml->STAN);
    }
    else{
//        $sheet1->setCellValue('A'.$i, $xml->NAME);
//        $sheet1->setCellValue('B'.$i, $xml->SHORT_NAME);
//        $sheet1->setCellValue('C'.$i, $xml->EDRPOU);
//        $sheet1->setCellValue('D'.$i, $xml->ADDRESS);
//        $sheet1->setCellValue('E'.$i, $xml->BOSS);
//        $sheet1->setCellValue('F'.$i, $xml->KVED);
//        $sheet1->setCellValue('G'.$i, $xml->STAN);
        $j++;
    }
    // now you can use your simpleXML object ($xml).

if ($i%5000==0)
{
    terminal_log($i);
}
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
//if($i==10000)die;
}
$objWriter = new Xlsx($spreadsheet);
//$objWriter1 = new Xlsx($spreadsheet1);
$objWriter->save('kiev.xlsx');
//$objWriter1->save('regions.xlsx');
// don’t forget to close the file
$xml_reader->close();
terminal_log($j);
function checkCity($adress) {
    $search = array(' ',',','.','1','2','3','4','5','6','7','8','9','0');
    $replace ='';
    $str = str_replace($search,$replace,$adress);
    $str = mb_strtolower($str);
    //var_dump($str.'<br>');
    if (strstr($str,'київ')==true)
    {
        return true;
    }
    else return false;

}
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
//class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {
//
//    public function readCell($column, $row, $worksheetName = '') {
//          return false;
//    }
//}
