<?php

$str  = "assets/uploaded/1A-Pengumuman Kelulusan.pdf";
$exp = explode("/", $str);
print_r($exp);

echo "<br/>";
echo $exp[count($exp)-1];
