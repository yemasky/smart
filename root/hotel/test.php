<?php
phpinfo();
$aa = "sss";
$bb = "aaa_bbb";

$arrayAa = explode('_', $aa);
print_r($arrayAa);


$arrayAa = explode('_', $bb);
print_r($arrayAa);

echo md5(md5('123456') . md5('585568')) . "\r\n";

print_r(implode("'.'", $arrayAa));

//print_r(implode("'.'", $aa));