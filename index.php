<?php
session_start();

// �ڑ�������
require_once 'ConstantDb.php';
// �Z�b�V�����ϐ��j��
$_SESSION = array();
// �G���[���b�Z�[�W���i�[����ϐ���������
$error_message = "";

// ���O�C���{�^���������ꂽ���𔻒�
// ���߂ẴA�N�Z�X�ł͔F�؂͍s�킸�G���[���b�Z�[�W�͕\�����Ȃ��悤��
if (isset($_POST["login"])) {
	if (isset($_POST["user_name"])){
		if (isset($_POST["password"])){
			$work1 = $_POST['user_name'];
			$work2 = $_POST['password'];
$sql=<<<EOS
  SELECT SQL_CACHE a.* From login As a
 Where a.strUser='$work1'
 And a.strPassword='$work2'
EOS;
			if(preg_match("/Windows/",$_ENV["OS"])){
			  $sql=mb_convert_encoding($sql,"SJIS","EUC-JP");
			}

			// DB�ڑ�
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASS);
			if (!$link) {
				print "error ";
				die('connect failed'.mysql_error());
			}
			mysql_query('SET NAMES SJIS');//���{��Field�Ή�
			$use = mysql_select_db(DB_NAME, $link);
			if (!$use) {
				print "error ";
				mysql_close($link);
				die('use db failed'.mysql_error());
			}
			mysql_set_charset('utf8');
			//POST�����J�n
			$result = mysql_query($sql);
			if (!$result) {
				print $sql;
				print "error1 ";
				mysql_close($link);
				die('query failed'.mysql_error());
			}
			$rowCount = mysql_num_rows($result);
			$fc_code = 0;
			if ($rowCount > 0) {
				while ($data = mysql_fetch_array($result)){
					$fc_code = $data[3];
				}
			}
			// DB�ؒf
			mysql_close($link);
			if ($rowCount > 0) {
				// ���O�C�������������؂��Z�b�V�����ɕۑ�
				$_SESSION["user_name"] = $_POST["user_name"];
				$_SESSION["password"] = $_POST["password"];
				$_SESSION["fc_code"] = $fc_code;
				header("Location: posmenu.php");
				exit;
			}
		}
	}
//	if ($_POST["user_name"] == "posco" && $_POST["password"] == "") {
		// ���O�C�������������؂��Z�b�V�����ɕۑ�
//		$_SESSION["user_name"] = $_POST["user_name"];
//		header("Location: posmenu.php");
//		exit;
//	}
	$error_message = "���[�UID�������̓p�X���[�h������Ă��܂��B";
}
?>

<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=Shift_JIS">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta name="GENERATOR" content="JustSystems Homepage Builder Version 18.0.7.0 for Windows">
<TITLE>POSCO WEB �{���V�X�e��</TITLE>
<link rel="stylesheet" href="css/hpbparts.css" type="text/css" id="hpbparts">
<link rel="stylesheet" href="css/container_5Ab_2c_top.css" type="text/css" id="hpbcontainer">
<link rel="stylesheet" href="css/main_5Ab_2c.css" type="text/css" id="hpbmain">
<link rel="stylesheet" href="css/user.css" type="text/css" id="hpbuser">
<LINK rel="stylesheet" href="images/table.css" type="text/css" id="_HPB_TABLE_CSS_ID_">
</HEAD>
<body id="hpb-template-05-01b-01" class="hpb-layoutset-02" bgcolor="#ffffff">
<div id="hpb-skip"><a href="#hpb-title">�{���փX�L�b�v</a></div>
<!-- container -->
<div id="hpb-container">
  <!-- header -->
  <div id="hpb-header" style="height : 77px;">
    <div id="hpb-headerMain">
      <p><b><i><font size="+1">����w�O�ʂ莕�ȗl<br>
      POSCO�N���E�h�T�[�r�X</font></i></b></p>
    </div>
    <div id="hpb-headerLogo" style="width : 2000px;"></div>
    <div id="hpb-headerExtra1">
      <p></p>
      <p class="address" style="text-align : right;" align="right">
	  �V�X�e���Ɋւ��邨�⍇���F0985-56-0369</p>
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
<div class="form-title"><h2>Web�{�����O�C��</h2></div>
<div class="form-title">���[�U�[ID</div>
<input class="form-field" type="text" id="user_name" name="user_name" value="" autocomplete="off" /><br />
<div class="form-title">�p�X���[�h</div>
<input class="form-field" type="password" id="password" name="password" value="" autocomplete="off" /><br />
<div class="submit-container">
<input class="submit-button" type="submit" id="login" name="login" value="���O�C��" />
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