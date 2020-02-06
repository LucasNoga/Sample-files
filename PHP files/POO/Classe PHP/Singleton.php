<?php
/**
* Cette classe ne devra avoir qu'une seule instance
* dans toute l'application
*/
class ApplicationConfiguration {
   /**
    * Cette propriété statique servira de variable d'instance.
    * La méthode de construction utilisera cette
    * dernière afin d'y sauvegarder / récupérer l'instance unique.
    */
   private static $_instance = false;
 
   /**
    * On indique que le constructeur est privé pour éviter
    * toute instanciation non maîtrisée
    */
   private function __construct (){
      //chargement du fichier de configuration
   }
 
   /**
    * On évite tout clonage pour ne pas avoir deux instances en //
    */
   private function __clone (){}
 
   /**
    * C'est la méthode qui "remplace" le constructeur vis à vis
    * des autres classes.
    *
    * Son rôle est de créer / distribuer une unique
    * instance de notre objet.
    */
   public static function getInstance (){
      //Si l'instance n'existe pas encore, alors elle est créée.
      if (self::$_instance === false){
         self::$_instance = new ApplicationConfiguration ();
      }
      //L'instance existe, on peut la retourner à l'extérieur.
      return self::$_instance;
   }
}
?>