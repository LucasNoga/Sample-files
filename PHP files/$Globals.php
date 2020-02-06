<?php
$x="PostgreSQL";
$y="MySQL";
$z=&$x;
$x="PHP 5";
$y=&$x;
echo '$x vaut : '. $x .'<br/>';
echo '$y vaut : '. $y .'<br/>';
echo '$z vaut : '. $z .'<br/>'; 

echo('le tableau $_GLOBALS est : <br/>');
echo $GLOBALS['x'] . '<br/>'; 
echo $GLOBALS['y'] . '<br/>'; 
echo $GLOBALS['z'] . '<br/>'; 

echo '<br/>Version de php' . phpversion() .'<br/>';
echo 'OS :' . PHP_OS .'<br/>';
echo 'Langue du navigateur client : ' . $_SERVER['HTTP_ACCEPT_LANGUAGE'] . '<br/>'; 


?>