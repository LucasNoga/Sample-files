<?php
echo '<pre>';
var_dump($_SERVER);
echo '</pre>';
copy(__FILE__, "yo.php");

echo "dans le fichier php, il y a :" ."<br>";
readfile("yo.php");

echo "<br>" .'test de fonction' ."<br>";
echo mcrypt_get_iv_size('abs', 'ddf') . "<br>";
?>