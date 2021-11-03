<?php
// �ڑ�������
require_once 'ConstantDb.php';

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

function convert_file_to_utf8($source, $target) {
    $content=file_get_contents($source);
    # detect original encoding
    // $original_encoding=mb_detect_encoding($content, "UTF-8, ISO-8859-1, ISO-8859-15", true);
    # now convert
    if ($original_encoding!='UTF-8') {
        // $content=mb_convert_encoding($content, 'UTF-8', $original_encoding);
			$content = mb_convert_encoding($content, 'UTF-8', 'sjis-win');
    }
    // $bom=chr(239) . chr(187) . chr(191); # use BOM to be on safe side
    echo $content;
    file_put_contents($target, $content);
}

foreach(glob('/home/posco/posco/0068-NishidaiDC/uriage/{s_bur*.csv}', GLOB_BRACE) as $image) {
  echo "Filename: " . $image . "<br />";
  // convert_file_to_utf8($image,$image);
  $sql = "LOAD DATA LOCAL INFILE '" . $image . "' REPLACE INTO TABLE `Burdata` FIELDS TERMINATED BY '	' LINES TERMINATED BY '\r\n'"; 
  //POST�����J�n
  $result = mysql_query($sql);
  echo "Filename2: " . $result . "<br />";
  if (!$result) {
	print $sql;
	print "error1 ";
	die('query failed'.mysql_error($link));
	mysql_close($link);
  }else{
        unlink($image);
  }
}

// DB�ؒf
mysql_close($link);

?>

