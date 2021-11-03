<?php
session_start();
    // ログイン状態のチェック
//echo "user=".$_SESSION["user_name"];
//echo "pass=".$_SESSION["password"];
//echo "fccode=".$_SESSION["fc_code"];
if (!isset($_SESSION["user_name"])) {
	header('Location:http://www.yahoo.co.jp');
	exit();
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta name="GENERATOR" content="JustSystems Homepage Builder Version 18.0.9.0 for Windows">
	<title>POSCOクラウドサービス</title>
	<link rel="stylesheet" href="css/hpbparts.css" type="text/css" id="hpbparts">
	<link rel="stylesheet" href="css/container_5Ab_2c_top.css" type="text/css" id="hpbcontainer">
	<link rel="stylesheet" href="css/main_5Ab_2c.css" type="text/css" id="hpbmain">
	<link rel="stylesheet" href="css/user.css" type="text/css" id="hpbuser">
	<style type="text/css">
  .auto-style1 { text-align: center; }
  /* thead {
    display: block;
  }
  tbody {
    display: block;
    overflow-y: scroll;
    height: 1150px;
  } */
		.loader{display:inline-block;width:30px;height:30px;background:0 0;border:7px solid transparent;border-top-color:#089ded;border-left-color:#089ded;border-radius:50%;animation:loader .75s 10 ease forwards}@keyframes loader{100%{transform:rotate(360deg)}}
	</style>
</head>
<body id="hpb-template-05-01b-01" class="hpb-layoutset-02" bgcolor="#ffffff">
<div id="hpb-skip"><a href="#hpb-title">本文へスキップ</a></div>
<!-- container -->
<div id="hpb-container">
  <!-- header -->
  <div id="hpb-header" style="height : 55px;">
    <div id="hpb-headerMain">
      <p><b><i><font size="+1">西台駅前通り歯科様<br>
      POSCOクラウドサービス</font></i></b></p>
    </div>
    <div id="hpb-headerLogo" style="width : 2000px;"></div>
    <div id="hpb-headerExtra1">
      <p class="tel" style="text-align : right;" align="right">ログインID：<?php echo $_SESSION["user_name"] ?></p>
      <p class="address" style="text-align : right;" align="right">
	  システムに関するお問合せ：0985-56-0369</p>
    </div>
  </div>
	<?php if (strlen($_SESSION["user_name"])>0){
	echo "<p class='password' style='text-align : right;' align='right'><a href='chage_password.php'>パスワード変更はこちら</a></p>";
	} ?>
  <!-- header end --><!-- inner -->
  <div id="hpb-inner">
    <!-- wrapper --><!-- page title --><br>
    <!-- main end --><!-- wrapper end --><!-- navi -->
    <div id="hpb-nav">
      <h3 class="hpb-c-index">ナビゲーション</h3>
      <ul>
        <li id="nav-toppage"><a href="posmenu.php?sw=1"><span class="en">Sales Report</span>患者別集計</a>
        <!-- <li id="nav-toppage" style="float:right"><a href="posmenu.php?sw=9"><span class="en">Sales Report</span>患者別集計テスト</a> -->
        <li id="nav-contact" style="float:right"><a href="index.php"><span class="en">LOGOUT</span>ログアウト</a>
      </ul>
    </div>
  </div>
  <!-- inner end --><!-- footer --><!-- footer end -->
</div>
<!-- container end -->

<form method="POST" action="">
<div>
<?php
$sw = $_GET['sw'];
if (!isset($sw)){$sw = 1;}
if ($sw==1){
	echo "患者別集計";
	$today1 = date("Y-m-d");
}
if ($sw==9){
	echo "患者別集計";
	$today1 = date("Y-m-d");
}
$today2 = date("Y-m-d");
$min = date("Y-m-d",strtotime("-10 year"));
$max = date("Y-m-d",strtotime("10 year"));
// 選択リストの値を取得
$name = "menu1";
// 選択リストの要素を配列に格納 → この配列からドロップダウンリストを作成
$tenpo = "01";

// エラーを画面に表示(1を0にすると画面上にはエラーは出ない)
ini_set('display_errors',0);
$date1 = $_POST['date1'];
$date2 = $_POST['date2'];
$tenpo = $_POST['menu1'];
//echo "tenpo=".$tenpo;
// $hinban = $_POST['hinban1'];
$code = $_POST['code1'];
ini_set('display_errors',1);
if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");
    switch ($kbn) {
      case "クリア":
				$date1 = date("Y-m-d");
				$date2 = date("Y-m-d");
				$tenpo="";
				$hinban="";
				break;
      default:
    }
}
if (isset($date1)){
	$today1=$date1;
	$today2=$date2;
}

?>

<?php if ($sw==1){ ?>
	<p class="auto-style1"><label>期間：<input type="date" name="date1" min=<?php echo $min ?> max=<?php echo $max ?> 
	value=<?php echo $today1 ?>>～<input type="date" name="date2" min=<?php echo $min ?> max=<?php echo $max ?> 
	value=<?php echo $today2 ?>></label></p>
<?php } ?>
<?php if ($sw==9){ ?>
	<p class="auto-style1"><label>期間：<input type="date" name="date1" min=<?php echo $min ?> max=<?php echo $max ?> 
	value=<?php echo $today1 ?>>～<input type="date" name="date2" min=<?php echo $min ?> max=<?php echo $max ?> 
	value=<?php echo $today2 ?>></label></p>
<?php } ?>
<!-- <p class="auto-style2"><input type="submit" value="送信する"></p> -->
<p></p>

<p class="auto-style1"><input type="submit" value="集  計" name="sub1">&nbsp;&nbsp;&nbsp;
<input type="submit" value="クリア" name="sub1">&nbsp;&nbsp;&nbsp;
<!-- <input id="clickMe" type="button" value="Excel出力" onclick="fnExcelReport();" /></div></p> -->
<input id="clickMe" type="button" value="Excel出力" onclick="ExportToExcel();" /></div></p>
&nbsp;
<div id='container'></div>
	&nbsp;</div>
<?php
if (isset($_POST["sub1"])) {
    $kbn = htmlspecialchars($_POST["sub1"], ENT_QUOTES, "UTF-8");
    switch ($kbn) {
        case "集  計": 
					if ($sw==1){
						require 'sales_quick.php';
					}
					if ($sw==9){
						require 'sales_quick_test.php';
					}
        break;
        case "クリア": break;
        default:  echo $kbn."エラー"; exit;
    }
}
?>

</form>
<script>
function ExportToExcel() {
    var xmlhttp = new XMLHttpRequest();

    var d1 = document.getElementsByName('date1')[0].value;
    var d2 = document.getElementsByName('date2')[0].value;
    var tp = d=document.getElementsByTagName("form")[0].firstElementChild

  if(d.innerText.substring(0,5)==="患者別集計"){

      xmlhttp.onreadystatechange = function() {
          if (xmlhttp.readyState == XMLHttpRequest.DONE) {   // XMLHttpRequest.DONE == 4
            if (xmlhttp.status == 200) {
              window.location.assign('http://posco.sakura.ne.jp/nishidaidc-e1e7a06/output/SalesReport('+(d1.replace(/-/g, ''))+'-'+(d2.replace(/-/g, ''))+').xlsx');	
              document.getElementById('container').innerHTML = '';
            }
            else if (xmlhttp.status == 400) {
                alert('There was an error 400');
            }
            else {
                alert('something else other than 200 was returned');
            }
          } else {
            document.getElementById('container').innerHTML = '<div class="loader"></div>';
          }

      };

      xmlhttp.open("GET", "ExportToExcel.php?date1="+d1+"&date2="+d2, true);
      xmlhttp.send();
  }
}

document.getElementById("clickMe").onclick = ExportToExcel;
</script>
</body>
</html>
<?php
// 配列から選択リストを作成する関数
// パラメータ：配列／選択リスト名／選択値
function disp_list($array, $name, $selected_value = "") {
    echo "<select name=" . $name . ">";
    while (list($value, $text) = each($array)) {
        echo "<option ";
        if ($selected_value == mb_substr($text,0,2,"UTF-8")) {
            echo " selected ";
        }
        echo " value=".mb_substr($text,0,2,"UTF-8").">" . $text . "</option>";
    }
    echo "</select>";
}
?>
