<?php session_start(); ?>
<?php
// �ڑ�������
require_once 'ConstantDb.php';
// �Z�b�V�����ϐ��j��
//$_SESSION = array();
// �G���[���b�Z�[�W���i�[����ϐ���������
$error_message = "";

//ini_set("display_errors",1);
//error_reporting(E_ALL);

// ���O�C���{�^���������ꂽ���𔻒�
// ���߂ẴA�N�Z�X�ł͔F�؂͍s�킸�G���[���b�Z�[�W�͕\�����Ȃ��悤��
if (isset($_POST["Change"])) {
	$pass1 = $_POST['NewPassword1'];
	$pass2 = $_POST['NewPassword2'];
	$usernm= $_SESSION["user_name"];
	$oldpass= $_SESSION["password"];
	//�����O�X�`�F�b�N
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
			// DB�ؒf
			mysql_close($link);
			header("Location: index.php");
			exit;
		}else{
			$error_message = "���p�X���[�h�̕�����������܂���B";
		}
	}else{
		$error_message = "���p�X���[�h����v���Ă��܂���B";
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
<TITLE>POSC WEB �{���V�X�e��</TITLE>
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
<div class="form-title"><h2>���O�C��ID�F<?php echo $_SESSION["user_name"] ?></h2></div>
<div class="form-title"><h2>�p�X���[�h��ύX���܂��B(6�����ȏ�)</h2></div>
<div class="form-title">�V�����p�X���[�h</div>
<input class="form-field" type="password" id="NewPassword1" name="NewPassword1" value="" autocomplete="off" /><br />
<div class="form-title">�V�����p�X���[�h(�m�F)</div>
<input class="form-field" type="password" id="NewPassword2" name="NewPassword2" value="" autocomplete="off" /><br />
<div class="submit-container">
<input class="submit-button" type="submit" id="Change" name="Change" value="�p�X���[�h�ύX" />
<input class="submit-button" type="submit" id="Cancel" name="Cancel" value="�L�����Z��" />
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