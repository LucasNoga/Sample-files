<?php
$_GET[1]='salut';



echo "<a href=\"methode_get.php\">script de base</a><br/>";
echo "<a href=\"methode_get.php?i=5&z=3\">parametre avec i = 5 et z = 3</a><br/>";
echo "<a href=\"methode_get.php?a=salut&b=lucas&c=tu&d=es&e=etudiant\">parametre salut lucas tu es etudiant</a><br/>";
?>


<pre>
<?php var_dump($_GET);?>
</pre>