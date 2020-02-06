<?php
Exposé de l’exemple
Supposons que nous devons gérer un restaurant placé dans un aéroport. Ce restaurant accueille une clientèle variée qui parle différentes langues.
Afin de répondre au besoin de la clientèle, le restaurant dispose de serveurs en mesure de dialoguer dans plusieurs langues, sans bien sûr qu’ils ne parlent tous toutes les langues. 
Certains serveurs sont spécialisés dans certains domaines tels le café ou le vin. 
D’autres s’occupent de la terrasse alors que leurs confrères n’aiment pas sortir.
Bien sûr, tout ceci doit prendre en compte la présence ou non des serveurs, l’évolution des compétences, la planning, la charge de chacun, bref de 
nombreuses problématiques en rapport avec les serveurs eux même.
Le but de notre application est d’être en mesure d’affecter une commande au bon serveur.
   
Class Restaurant {
   Private $serveurs ;
   function recevoir (Client $client){
      $client->passeCommande () ;
      //c'est ici que va se situer le problème, qui sera en mesure de répondre ?
   }
}

Sans l’utilisation de pattern
Sans l’utilisation des modèles de conception, nous allons avoir un objet de type commande qu’il va nous falloir essayer d’interpréter, 
puis supposer des capacité de nos serveurs afin de décider celui qui est en mesure de contenter les souhaits du client.
Exemple sans les modèles de conception.
   
Class Restaurant {
   Private $serveurs ;
   function recevoir (Client $client){
      $commande = $client->donneSaCommande () ;
      switch (copier_coller_de_methode_de_traduction_pour_trouver_la_langue ($commande)){
         case 'fr' ://... break;
         case 'en' ://...break;
         case 'en_CA' ://... break;
         case '...toutes les langues du monde si un jour on a un serveur capable de traiter cette langue':
      }
      switch (copier_coller_de_methode_de_traduction_pour_comprendre_la_commande ($commande)){
         case 'Escaliers' ://... break;
         case 'Terrasse' ://... break;
         case 'Cafe' ://... break;
         case 'Vin' ://... break;
      }
      //On est maintenant en mesure de traiter la commande (à moitié comprise) et de l'affecter à un serveur
   }
}

Avec l’utilisation des patterns
Avec l’utilisation de la chaine de responsabilité, nous allons simplement transmettre la commande à 
l’ensemble de nos serveurs pour leur demander s’ils sont en mesure de la traiter, jusqu’à ce que l’un d’entre eux réponde par l’affirmative.
Chaque serveur devra simplement d’un méthode d’acceptation de notre commande.
   
Class MonServeur {
   public function acceptCommande (ICommande $maCommande){
      //Chaque serveur dispose de l'implémentation lui permettant de voir s'il est capable de traiter la commande
   }
}
Class Restaurant {
   Private $serveurs ;
   public function recevoir (Client $client){
      $commande = $client->donneSaCommande () ;
      foreach ($this->_serveurs as $serveur){
         if (($commandeTraitee = $serveur->accepteCommande ($commande)) !== false){
            break;
         }
      }
      if ($commandeTraitee === false){
         //cas exceptionnel, aucun serveur n'est capable de traiter la commande du client
      }
   }
}

Avec l’utilisation des Modèles de Conception, on constate que notre code client est simplifié à l’extrême : 
on se contente de transmettre la demande telle qu’elle à tous 
les serveur (et les serveurs voient s’ils savent ou non prendre en charge cette commande). 
Avec cette méthode, seules les compétences effectives sont implémentées 
(si mes serveurs parlent anglais et français, inutile de perdre du temps dans le code du restaurant à déterminer 
   si le client essaye de s’exprimer en mandarin, chinois ou japonais).
A l’inverse, si demain un serveur est employé et est en mesure de comprendre l’une de ces langues (ou les trois), 
aucune modification de code ne sera nécessaire dans le reste du programme, mon nouveau serveur n’aura qu’a retourner une commande traitée, comme ses collègues.

?>