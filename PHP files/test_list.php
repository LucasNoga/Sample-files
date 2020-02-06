 <?php

$info = array('coffee', 'brown', 'caffeine');

// Liste toutes les variables
list($drink, $color, $power) = $info;
echo "$drink is $color and $power makes it special.<br>";

// Liste certaines variables
list($drink, , $power) = $info;
echo "$drink has $power.<br>";

// Ou bien, n'utilisons que le troisième
list(  $power) = $info;
echo "I need $power!<br>";

// list() ne fonctionne pas avec les chaînes de caractères
list($bar) = "abcde";
var_dump($bar); // NULL
?>
