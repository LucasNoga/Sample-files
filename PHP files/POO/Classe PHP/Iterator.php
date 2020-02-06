<?php


Le Design Pattern Itérateur (Iterator) en PHP
Posté par gerald le 3 décembre 2010 Laisser un commentaire (5) Aller vers les commentaires

On dit souvent que les Modèles de Conception sont la prose de l’informatique : Tout le monde les utilise sans le savoir. 
C’est souvent le cas et c’est particulièrement vrai avec les Itérateurs.

Que permettent les Itérateurs ? Les Itérateurs permettent simplement de parcourir un objet, un peu comme on parcours un tableau avec une boucle for.

Dans de nombreux langages il existe des interfaces Iterator, Iterable, …
Exposé de l’exemple

Nous souhaitons parcourir un répertoire pour appliquer sur les fichier un traitement particulier.
Solution sans les Patterns
   
$hdl = opendir('./');
while ($dirEntry = readdir($hdl))  {
   if (substr($dirEntry, 0, 1) != '.')  {
      if(!is_file($dirEntry)) {
         continue;
      }
      //Applique le traitement à $dirEntry;
   }
}
closedir($hdl);

Dans cette solution, on constate que toute la logique de recherche est mêlée avec les traitements. 
Si à l’avenir on souhaite appliquer un même traitement à une collection différente de fichier, 
voir dans une collection qui n’a rien a voir avec une arborescence de répertoires, 
il sera question de développer cette logique de parcours une nouvelle fois, 
ou de développer cette logique de parcours à part dans des services qui transformeraient 
les données à traiter en une collection primitive (tableaux par exemple).
Solution avec les Iterateurs

La solution avec les Iterateurs est un peu plus élégante car on va masquer au code client la complexité de la recherche dans des fichiers.
1
2
3
   
foreach ( new DirectoryIterator('./') as $Item )  {
//applique le traitement à $Item
}
La SPL

En PHP, il existe une bibliothèque standard nommée la SPL, qui embarque en l’occurrence de nombreux Itérateurs, et c’est l’un d’eux que nous avons utilisé ci dessus.
Evolution de l’exemple

Faisons évoluer l’exemple pour ne rechercher que les fichiers images.

Comment faire ?

    Réaliser le test du type de fichier dans notre code client ?
    Hériter de la classe DirectoryIterator pour pour n’accepter que les fichiers ?
    Utiliser une autre méthode vue précédemment ?

C’est bien sûr la solution 3 que nous allons retenir ici ! Je ne vous laisse ainsi que quelques secondes afin de trouver le Pattern que nous allons utiliser pour rajouter un comportement à notre objet, il s’agit de ….. je vois que vous êtes sur la piste…… oui, bravo, il s’agit du pattern Decorateur.
Utilisation du Decorateur

Nous souhaitons donc réaliser un Décorateur capable de filter les fichiers de type image pour y appliquer des traitements.
Nous allons bien sûr décorer l’interface Iterator et non l’objet DirectyoryIterator, ce qui serait trop restrictif.
Comme tout bon développeur, nous n’allons pas tenter de réinventer la roue et allons partir d’un objet déjà capable de filtrer les données d’un Itérateur, objet standard de la SPL, la classe FilterIterator.
   
class ExtensionFilterIteratorDecorator extends FilterIterator {
   /**
    * extension actuellement filtrée
    *
    * @var string
    */
   private $_ext;
 
   /**
    * Indique si l'élément courant est accepté
    *
    * @return boolean
    */
   public function accept (){
      if (substr ($this->current (), -1 * strlen ($this->_ext)) === $this->_ext){
         return is_readable ($this->current ());
      }
      return false;
   }
 
   /**
    * Définition de l'extension sur laquelle on veut filtrer les données.
    *
    * @param string $pExt
    */
   public function setExtension ($pExt){
      $this->_ext = $pExt;
   }
}

Modification de l’exemple pour utiliser notre décorateur
   
$itFiles = new ExtensionFilterIteratorDecorator (new DirectoryIterator('./')) ;
$itFiles->setExtension ('.jpg');
foreach ( $itFiles as $Item )  {
   //applique le traitement à $Item
}

On voit que la modification du code client est relativement simple, mais continuons de complexifier la recherche de nos fichiers : 
Nous souhaitons maintenant rechercher les fichiers dans une arborescence et non plus dans un seul niveau !
   
$itFiles = new ExtensionFilterIteratorDecorator (new RecursiveIteratorIterator (new RecursiveDirectoryIterator ('./'))) ;
$itFiles->setExtension ('.jpg');
foreach ( $itFiles as $Item )  {
   //applique le traitement à $Item
}

Que c’est t il passé ? Nous avons utilisé la classe Standard de la SPL pour parcourir l’arborescence et non plus le répertoire en lui même. 
Comme cet Itérateur est un Itérateur récursif (il dispose de sous niveaux) il ne se parcours pas de la même façon qu’un Itérateur classique.
Qu’importe ! Il existe une classe qui permet de parcourir un Iterateur récursif « à plat » comme un Itérateur classique : RecursiveIteratorIterator.

On constate alors que notre décorateur de tout à l’heure continue de fonctionne et de remplir son office (alors qu’un héritage n’aurait pas été aussi souple).
Encore plus loin…..

Allons encore plus loin et parcourons plusieurs répertoires à la recherche des images :

   
$directories = new AppendIterator () ;
$directories->append (new RecursiveIteratorIterator (new RecursiveDirectoryIterator ('./')));
$directories->append (new RecursiveIteratorIterator (new RecursiveDirectoryIterator ('/autre_repertoire/')));
$itFiles = new ExtensionFilterIteratorDecorator ($directories);
$files->setExtension ('.php');
foreach ( $itFiles as $Item )  {
   //applique le traitement à $Item
}

Et le tour est joué. Encore une fois, nous avons utilisé les classes de la SPL pour assembler plusieurs Itérateurs de différents types en un seul (AppendIterator).
Tout est possible

Avec notre exemple, il est simple d’appréhender tout type d’évolution : Parcourir les fichiers non plus depuis un disque en local mais depuis une source FTP, 
depuis une requête SQL, des noms de fichiers depuis un fichier texte, une saisie utilisateur….. 
et mélanger le tout sans que le code client ne soit même conscient de ce parcours au final complexe.

Les Itérateurs sont l’un des Modèles de conception les plus intégrés au langage PHP depuis sa version 5, il serait dommage de s’en priver.
?>

