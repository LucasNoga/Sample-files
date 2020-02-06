
<?php
echo "<pre>";
//indice 0 à 6
$a = array(0 =>"a","b","c","d","e","f","g"); 

echo "<br>";
var_dump($a);


//commence a l'indice 15 jusqu'a 22
$b = array(15 => "h","i","j","k","l","m","n");

echo "<br>";
var_dump($b);


$c = $a + $b;

echo "<br>";
var_dump($c);