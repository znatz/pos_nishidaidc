<?php
session_start();
?>
<?php
// echo "fccode=".$_SESSION["fc_code"];
// 接続文字列
require_once 'ConstantDb.php';

// パラメータ取得
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
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

$rowCount = mysql_num_rows($result);
if ($rowCount < 1) {
    $idflg = "1";
    if (intval($point) == 0 && intval($sw) == 0) {
        print "No Data ";
    }
} else {
    $idflg = "0";
    
    echo "<link rel='stylesheet' href='css/scroll_quick.css' type='text/css' id='scroll'>";
    // 外枠開始 
    echo "<div class='y_data_area'>";
    // タイトル開始
    echo "<table class='y_data_title'>";
    echo "<border='1' text-align='center' cellspacing='0' cellpadding='5' bordercolor='#333333'>";
    // ヘッダー1行目
    $methods = ['現金','クレジット','その他','未払金'];
    echo "<thead><tr>";
        echo "<td bgcolor='#4169e1' rowspan=2 nowrap width='70'><font color='#FFFFFF'>日付</font></th>";
        echo "<td bgcolor='#4169e1' rowspan=2 nowrap width='70'><font color='#FFFFFF'>患者番号</font></th>";
        echo "<td bgcolor='#4169e1' rowspan=2 nowrap width='100'><font color='#FFFFFF'>患者名</font></th>";
        echo '<td bgcolor="#4169e1" colspan='.(count($bdamas)+1).' nowrap style="color:#FFFFFF">請求金額</th>';
        echo "<td bgcolor='#4169e1' colspan=". count($methods) ." nowrap ><font color='#FFFFFF'>支払方法</font></th>";   //240
        echo "<td bgcolor='#4169e1' rowspan=2 nowrap width='60'><font color='#FFFFFF'>会計時間</font></th>";
    echo "</tr>";
    // ヘッダー2行目
    echo "<tr>";
        echo "<td bgcolor='#4169e1' nowrap width='80'><font color='#FFFFFF'>合計</font></th>";
        foreach ($bdamas as $key => $value) {
            echo "<td bgcolor='#4169e1' nowrap width='80'><font color='#FFFFFF'>".$value["大分類"]."</font></th>";
        }
        foreach ($methods as $method) {
            echo "<td bgcolor='#4169e1' nowrap width='80'><font color='#FFFFFF'>".$method."</font></th>";
        }
    echo "</tr></thead>";
    
    //１ループで１行データが取り出され、データが無くなるとループを抜けます。
    echo "<tbody>";
    while ($data = mysql_fetch_array($result)) {
        echo "<TR>";
        if ($data["順番"] == 0) { 
            echo '<TD style="background-color:#FFFFFF;text-align:center;" width=70 nowrap><font color=#000000>'.$data["日付"].'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:center;" width=70 nowrap><font color=#000000>'.$data["患者番号"].'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:left;" width=100 nowrap><font color=#000000>'.$data["患者名"].'</TD>';
        }elseif($data["順番"] == 1) {   //小計
            echo '<TD style="background-color:#FFFFFF;text-align:center;" width=70 nowrap></TD>';
            echo '<TD style="background-color:#4169e1;text-align:center;" width=220 colspan=2 nowrap><font color=#FFFFFF>'.substr($data["日付"], 5).' 合計 '.' </TD>';
        }elseif($data["順番"] == 2) {   //総合計
            echo '<TD style="background-color:#FFFFFF;text-align:center;" width=70 nowrap></TD>';
            echo '<TD style="background-color:#4169e1;text-align:center;" width=220 colspan=2 nowrap><font color=#FFFFFF>総合計 '.'</TD>';
        } 

        if($data["順番"] == 0) {
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["請求金額"]).'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類01"]).'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類02"]).'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類03"]).'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類04"]).'</TD>';
            echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類09"]).'</TD>';
            foreach ($methods as $method) {
                echo '<TD style="background-color:#FFFFFF;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data[$method]).'</TD>';
            }
        } else {
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["請求金額"]).'</TD>';
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類01"]).'</TD>';
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類02"]).'</TD>';
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類03"]).'</TD>';
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類04"]).'</TD>';
            echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data["大分類09"]).'</TD>';
            foreach ($methods as $method) {
                echo '<TD style="background-color:#90ee90;text-align:right;" width=75 nowrap><font color=#000000>'.number_format($data[$method]).'</TD>';
            }
        }
        echo '<TD style="background-color:#FFFFFF;text-align:center;" width=60 nowrap><font color=#000000>'.$data["会計時間"].'</TD>';
        echo "</TR>";
    
    }
    echo "</tbody>";
    echo "</table>";
    // echo "</div>";
    // echo "</div>";
    // データ終了
    // 外枠終了 
    echo "</div>";
}

// DB切断
mysql_close($link);
?>