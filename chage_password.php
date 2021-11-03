<?php session_start(); ?>
<?php
// 接続文字列
require_once 'ConstantDb.php';
// セッション変数破棄
//$_SESSION = array();
// エラーメッセージを格納する変数を初期化
$error_message = "";

//ini_set("display_errors",1);
//error_reporting(E_ALL);

// ログインボタンが押されたかを判定
// 初めてのアクセスでは認証は行わずエラーメッセージは表示しないように
if (isset($_POST["Change"])) {
	$pass1 = $_POST['NewPassword1'];
	$pass2 = $_POST['NewPassword2'];
	$usernm= $_SESSION["user_name"];
	$oldpass= $_SESSION["password"];
	//レングスチェック
	$strPassLen1 = strlen($pass1);
	$strPassLen2 = strlen($pass2);
	if($pass1 == $pass2){
		if($strPassLen1 > 5){
$sql=<<<EOS
UPDATE login SET strPassword='$pass1' Where strUser='$usernm' and strPassword='$oldpass'
EOS;
			if(preg_match("/Windows/",$_ENV["OS"])){
			  $sql=mb_convert_encoding($sql,"SJIS","EUC-JP");
			}

			// DB接続
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			if (!$link) {
				print "error ";
				die('connect failed'.mysql_error());
			}
			mysql_query('SET NAMES SJIS');//日本語Field対応
			$use = mysql_select_db(DB_NAME, $link);
			if (!$use) {
				print "error ";
				mysql_close($link);
				die('use db failed'.mysql_error());
			}
			mysql_set_charset('utf8');
			//POST処理開始
			$result = mysql_query($sql);
			if (!$result) {
				print $sql;
				print "error1 ";
				mysql_close($link);
				die('query failed'.mysql_error());
			}
			$rowCount = mysql_num_rows($result);
			// DB切断
			mysql_close($link);
			header("Location: index.php");
			exit;
		}else{
			$error_message = "※パスワードの文字数が足りません。";
		}
	}else{
		$error_message = "※パスワードが一致していません。";
	}
}
if (isset($_POST["Cancel"])) {
	header("Location: posmenu.php");
	exit;
}
?>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="GENERATOR" content="JustSystems Homepage Builder Version 18.0.7.0 for Windows">
<TITLE>POSC WEB 閲覧システム</TITLE>
<link rel="stylesheet" href="css/hpbparts.css" type="text/css" id="hpbparts">
<link rel="stylesheet" href="css/container_5Ab_2c_top.css" type="text/css" id="hpbcontainer">
<link rel="stylesheet" href="css/main_5Ab_2c.css" type="text/css" id="hpbmain">
<link rel="stylesheet" href="css/user.css" type="text/css" id="hpbuser">
<LINK rel="stylesheet" href="images/table.css" type="text/css" id="_HPB_TABLE_CSS_ID_">
</HEAD>
<body id="hpb-template-05-01b-01" class="hpb-layoutset-02" bgcolor="#ffffff">
<div id="hpb-skip"><a href="#hpb-title">本文へスキップ</a></div>
<!-- container -->
<div id="hpb-container">
  <!-- header -->
  <div id="hpb-header" style="height : 77px;">
    <div id="hpb-headerMain">
      <p><b><i><font size="+1">西台駅前通り歯科様<br>
      POSCOクラウドサービス</font></i></b></p>
    </div>
    <div id="hpb-headerLogo" style="width : 2000px;"></div>
    <div id="hpb-headerExtra1">
      <p></p>
      <p class="address" style="text-align : right;" align="right">
	  システムに関するお問合せ：0985-56-0369</p>
    </div>
  </div>
</div>
<!-- container end -->

</body>
<p></p>
<p></p>
<div id="container">
<DIV id="navigation" align="center"></DIV>
<link rel="stylesheet" href="css/user2.css" type="text/css" id="hpbuser2">
<form method="POST" action="" class="form-container">
<div class="form-title"><h2>ログインID：<?php echo $_SESSION["user_name"] ?></h2></div>
<div class="form-title"><h2>パスワードを変更します。(6文字以上)</h2></div>
<div class="form-title">新しいパスワード</div>
<input class="form-field" type="password" id="NewPassword1" name="NewPassword1" value="" autocomplete="off" /><br />
<div class="form-title">新しいパスワード(確認)</div>
<input class="form-field" type="password" id="NewPassword2" name="NewPassword2" value="" autocomplete="off" /><br />
<div class="submit-container">
<input class="submit-button" type="submit" id="Change" name="Change" value="パスワード変更" />
<input class="submit-button" type="submit" id="Cancel" name="Cancel" value="キャンセル" />
				<?php if (isset($error_message)){ ?>
	                <tr>
						<p ><font color="red"> <label><?php echo $error_message ?></font>
						</label></p>
	                </tr>
				<?php } ?>
</div>
</form>
</div>
</HTML>