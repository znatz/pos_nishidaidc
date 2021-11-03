<?php
$target_dir = "../../posco/0068-NishidaiDC/uriage/";
$content = $_POST["csv"];
$name = $_POST["name"];

$s_csv = $target_dir. $name;
$fp = fopen($s_csv,"wb");
fwrite($fp,$content);
fclose($fp);

echo "DONE";
?>