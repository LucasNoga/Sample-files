<?php
/**
 * La classe décoratrice respecte elle aussi l'interface attendue par les clients
 */
class AlertLogDecorator implements ILog {
   private $_decorated;
 
   public function __construct (ILog $pDecorated){
      $this->_decorated = $pDecorated;
   }
 
   public function add ($logInformations){
      //réalisation du comportement spécifique, et de l'alerte
      //...
 
      //on délègue maintenant à l'objet la réalisation
      //de la tâche initial
      $this->_decorated->add ($logInformations);
   }
}
	
$log = LoggerFactory::create ('journal principal');
$log->add ('Lancement applicatif');

	
class LoggerFactory {
   public static function create ($type){
      //retourne le nom de l'objet à utiliser
      $logClass = self::getClass ($type);
      //création de l'objet
      $logObject = new $logClass ();
 
      if (self::getOption ('alerte', $type)){
         $logObject = new AlertLogDecorator ($logObject);
      }
 
      if (self::getOption ('logTime', $type)){
         $logObject = new AppendTimeDecorator ($logObject);
      }
 
      if (self::getOption ('debugBackTrace', $type)){
         $logObject = new AppendDebugBackTraceDecorator ($logObject);
      }
 
      //et ainsi de suite
      return $logObject;
   }
}