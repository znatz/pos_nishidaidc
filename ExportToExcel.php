<?php
session_start();
?>
<?php
// echo "fccode=".$_SESSION["fc_code"];
// 接続文字列
require_once 'ConstantDb.php';
require_once './ExcelHelper.php';

//Cleanup Output Folder
$files = glob(dirname(__FILE__).'/output/*.xlsx');
foreach($files as $file){ // iterate files
  if(is_file($file))
    unlink($file); // delete file
}

// パラメータ取得
$date1 = $_GET['date1'];
$date2 = $_GET['date2'];
$d1 =str_replace('-', '', $date1);
$d2 =str_replace('-', '', $date2);
$date1 = date('Y/m/d', strtotime($date1));
$date2 = date('Y/m/d', strtotime($date2));

$sql_bdamas=<<<EOS
SELECT
    *
FROM
    BDAMAS
ORDER BY
    コード;
EOS;

//ORDER BY 売上区分,コード;

$sql = <<<EOS
Call GetSalesByPatient('$date1','$date2')
EOS;


if (preg_match("/Windows/", $_ENV["OS"])) {
    $sql_bdamas = mb_convert_encoding($sql_bdamas, "SJIS", "EUC-JP");
    $sql = mb_convert_encoding($sql, "SJIS", "EUC-JP");
}

// DB接続
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
if (!$link) {
    print "error ";
    die('connect failed' . mysql_error());
}
mysql_query('SET NAMES SJIS'); //日本語Field対応
$use = mysql_select_db(DB_NAME, $link);
if (!$use) {
    print "error ";
    mysql_close($link);
    die('use db failed' . mysql_error());
}
mysql_set_charset('utf8');
//POST処理開始
//大分類マスタ
$bdamas=array();
$result_bdamas = mysql_query($sql_bdamas);
while( $row = mysql_fetch_array( $result_bdamas, MYSQL_ASSOC ) ){
    $bdamas[] = $row;
}



//集計クエリ
$result = mysql_query($sql); //or die(mysql_error($link));
// $result = mysql_multi_query($sql);
if (!$result) {
    print $sql;
    print "error1 ";
    mysql_close($link);
    die('query failed' . mysql_error());
}

function cellColor($cells,$color){
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
        )
    ));
}

$rowCount = mysql_num_rows($result);
if ($rowCount < 1) {
    $idflg = "1";
    if (intval($point) == 0 && intval($sw) == 0) {
        print "No Data ";
    }
} else {


    date_default_timezone_set('Asia/Tokyo');
    require __DIR__ . '/vendor/autoload.php';

    $book = new PHPExcel();
	$book->getProperties()
        ->setCreator("ポスコ")
        ->setCompany('株式会社ポスコ')
        ->setCreated(strtotime('2020-10-16 03:04:05'))
        ->setModified(strtotime('2020-10-16 04:05:06'))
        ->setTitle("売上日報")
        ->setSubject("売上日報")
        ->setDescription("説明文")
        ->setKeywords("エクセル PHP 出力");

    $sheet = $book->getActiveSheet();

    $column = 0;
    $bdamas_col = 0;
    $shiharai_col = 0;

    // 基本情報
    $sheet->setCellValueByColumnAndRow($column, 1, '日付'); $column += 1;
    $sheet->setCellValueByColumnAndRow($column, 1, '患者番号'); $column += 1;
    $sheet->setCellValueByColumnAndRow($column, 1, '患者名'); $column += 1;

    // 合計請求金額
    $sheet->setCellValueByColumnAndRow($column, 1, '請求金額'); $bdamas_col=$column;

    // 大分類
    $sheet->setCellValueByColumnAndRow($column, 2, '合計'); $column += 1;
    foreach ($bdamas as $key => $value) {
        $sheet->setCellValueByColumnAndRow($column, 2, $value["大分類"]); $column += 1;
    }
    $sheet->mergeCellsByColumnAndRow( 3, 1,  $column - 1, 1 );

    // 精算方法
    $methods = ['現金','クレジット','その他','未払金'];
    $sheet->setCellValueByColumnAndRow($column, 1, '支払方法'); $shiharai_col=$column;
    $sheet->mergeCellsByColumnAndRow($column, 1,  $column + count($methods) - 1, 1 );
    foreach ($methods as $key => $value) {
        $sheet->setCellValueByColumnAndRow($column, 2, $value); $column += 1;
    }

    // 会計時間
    $sheet->setCellValueByColumnAndRow($column, 1, '会計時間'); 

    //縦マージ(前三つと最後一つ)
    $sheet->mergeCellsByColumnAndRow(0, 1, 0, 2);
    $sheet->mergeCellsByColumnAndRow(1, 1, 1, 2);
    $sheet->mergeCellsByColumnAndRow(2, 1, 2, 2);
    $sheet->mergeCellsByColumnAndRow($column, 1, $column, 2);

    // 背景色
    for($c=0;$c<=$column;$c++){
        for($r=1;$r<=2;$r++){
            SetHeaderCell($sheet,$c,$r);
        }
    }
    $idflg = "0";
    
    //１ループで１行データが取り出され、データが無くなるとループを抜けます。
    $r=2;
    while ($data = mysql_fetch_array($result)) {
        $r++;
        if ($data["順番"] == 0) { 
            $sheet->setCellValueByColumnAndRow(0, $r, $data["日付"]);
            SetCenterCell($sheet,0,$r);
            $sheet->setCellValueByColumnAndRow(1, $r, $data["患者番号"]);
            SetCenterCellString($sheet,1,$r);
            $sheet->setCellValueByColumnAndRow(2, $r, $data["患者名"]);
            SetLeftCell($sheet,2,$r);
            $sheet->setCellValueByColumnAndRow(3, $r, $data["請求金額"]);
            SetNumberCell($sheet,3,$r);
        }elseif($data["順番"] == 1) {   //小計
            $sheet->setCellValueByColumnAndRow(1, $r, substr($data["日付"], 5).' 合計 ');
            SetHeaderCell($sheet,1,$r);
            $sheet->mergeCellsByColumnAndRow( 1, $r,  2, $r );
        }elseif($data["順番"] == 2) {   //総合計
            $sheet->setCellValueByColumnAndRow(1, $r, '総合計 ');
            SetHeaderCell($sheet,1,$r);
            $sheet->mergeCellsByColumnAndRow( 1, $r,  2, $r );
        } 


        $sheet->setCellValueByColumnAndRow($bdamas_col, $r, $data["請求金額"]);

        // 大分類
        $bdamas_e = $bdamas_col+1;
        foreach ($bdamas as $key => $value) {
            $sheet->setCellValueByColumnAndRow($bdamas_e, $r, $data["大分類".str_replace(' ','',$value["コード"])]);
            SetNumberSumCell($sheet,$bdamas_e,$r); 
            $bdamas_e++;
        }

        // 精算方法
        foreach($methods as $key => $value){
            $sheet->setCellValueByColumnAndRow($bdamas_e, $r, $data[$value]);
            SetNumberCell($sheet,$bdamas_e,$r);
            $bdamas_e++;
        }
        $sheet->setCellValueByColumnAndRow($bdamas_e, $r, $data["会計時間"]);SetCenterCell($sheet,$bdamas_e,$r);

        // 小計・合計行の数値型セル
        if($data["順番"] > 0) {
            for($c=3;$c<=$bdamas_e;$c++){
                SetNumberCell($sheet,$c,$r);
            }
        }        
    }

    // セル幅
    $sheet->getColumnDimension('A')->setWidth(12);
    $sheet->getColumnDimension('B')->setWidth(10);
    $sheet->getColumnDimension('C')->setWidth(20);
    $sheet->getColumnDimension('D')->setWidth(20);
    for($i=3;$i<$bdamas_e;$i++){
        $sheet->getColumnDimension( PHPExcel_Cell::stringFromColumnIndex($i) )->setWidth(15);
    }
    $sheet->getColumnDimension( PHPExcel_Cell::stringFromColumnIndex($bdamas_e))->setWidth(10);

    // セルボーダー
    $sheet->getStyle("A1:".PHPExcel_Cell::stringFromColumnIndex($bdamas_e).$r)->applyFromArray(
        array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => '000000')
                )
            )
        )
    );
    //--- $sheet->freezePane(PHPExcel_Cell::stringFromColumnIndex($bdamas_e+1).'3');

    $writer = PHPExcel_IOFactory::createWriter($book, 'Excel2007');
    $writer->save(dirname(__FILE__).'/output/SalesReport('. $d1 . '-' . $d2 .').xlsx');

}

// DB切断
mysql_close($link);
?>