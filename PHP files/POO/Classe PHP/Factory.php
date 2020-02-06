<?php
Exposé de l’exemple

Vous avez développé une bibliothèque de graphes qui remplissait jusqu’alors ses fonctions. Aujourd’hui, vous ne souhaitez plus maintenir votre bibliothèque et avez besoins nouvelles fonctionnalités qui existent dans des bibliothèques tierces.

Deux solutions s’offrent à vous :

    Modifier tout le code qui utilise votre bibliothèque
    Utiliser un adaptateur

Dans votre bibliothèque, vous avez spécifié l’interface IGraph et IGraphData suivante

	
interface IGraph {
   public function setData (IGraphData $graphData);
   public function getPie ();
}
interface IGraphData {
   public function addSlice ($sliceName, $sliceValue);
   public function deleteSlice ($sliceName);
   public function getSlices ();
}

Votre code client ressemble à ceci

	
$widget = new HTMLGraphWidget ();
$graphData = new GraphData ();
$graphData->loadFromArray ('pommes'=>10, 'tomates'=>3,
                          'raisin'=>27, 'carottes'=>4, 'poires'=>6);
 
//la méthode setGraph attend un IGraph en paramètre.
$widget->setGraph (GraphFactory::createFromData ($graphData));

et enfin le code de votre Fabrique
	
class GraphFactory {
   public static function createFromData (IGraphData $graphData){
      $graph = new Graph ();
      $graph->setData ($graphData);
      $graph->setMode ('image'=>'jpeg');
      return $graph;
   }
}

Voilà maintenant l’interface de la nouvelle bibliothèque que vous voulez utiliser
	
interface INewGraph {
   public function setData ($arData);
   public function render ($mode);
}
Solution sans passer par l’adaptateur

Vous allez modifier :

    votre Fabrique pour qu’elle retourne un objet de type INewGraph.
    votre code client pour qu’il prépare les données du graphes au bon format
    vos différents widget qui s’attendent à recevoir un IGraph, pour qu’il acceptent de recevoir un INewGraph

Cela peut représenter un travail conséquent sans compter les cas particuliers ou vous voudrez peut être conserver votre ancienne bibliothèque selon les sections de votre site…. et sans compter le jeu de piste que cela peut représenter dans tout votre code source !
En utilisant l’adaptateur

L’adaptateur consiste en la réalisation d’un objet qui respecte l’interface attendue par le code client (à savoir IGraph) et agrège le nouveau type d’objet (ici de type INewGraph) pour convertir les appels de l’ancienne interface vers la nouvelle.

Très simplement, nous allons avoir l’objet suivant :
	
class GraphToNewGraphAdaptator implements IGraph {
   /**
    * L'objet à adapter
    */
   private $_newGraph;
 
   /**
    * L'adaptateur reçoit en paramètre
    *    de construction l'objet à adapter.
    */
   public function __construct (INewGraph $newGraph){
      $this->_newGraph = $newGraph;
   }
 
   public function setData (IGraphData $graphData){
      //On transmet l'appel à l'objet adapté après avoir transformé
      // (si nécessaire) les paramètres reçus pour qu'ils correspondent
      // au format attendu par le nouvel objet.
      $this->_newGraph->setData ($graphData->getSlices ());
   }
 
   public function getPie (){
      //conversion de l'appel à l'objet adapté
      $this->_newGraph->render ('pie');
   }
}

Vous voilà muni d’un objet de déguisement qui permet à n’importe quel INewGraph de se faire passer pour un IGraph.

Nous n’avons plus qu’à l’utiliser dans notre Fabrique.
	
class GraphFactory {
   public static function createFromData (IGraphData $graphData){
      $graph = new GraphToNewGraphAdaptator (new NewGraph ());
      $graph->setData ($graphData);
      $graph->setMode ('image'=>'jpeg');//supposons que cette méthode
      //était commune, si ce n'est pas le cas il faudra aussi l'adapter.
      return $graph;
   }
}

Et voilà ! Le reste de votre code n’est pas impacté et vous pouvez utiliser la nouvelle bibliothèque sans vous soucier du reste.

On remarque au passage que l’utilisation des Modèles de Conception est un cercle vertueux : plus vous les utilisez à bon escient, plus vous pourrez les mélanger pour profiter de leurs bénéfices. Si nous n’avions pas utilisé de Fabrique pour notre Graph, les choses auraient pu en effet être un peu plus délicates.