<?php

    App::import(array('Model', 'AppModel', 'File'));
    App::import(array('Model', 'Deliberation', 'File'));
    App::import(array('Model', 'Commentaire', 'File'));

    class ParapheurShell extends Shell{
	
        var $uses = array( 'Deliberation', 'Commentaire');
        function startup() {
        }

        function main() {

            App::uses('AppShell', 'Console/Command');
            App::uses('ComponentCollection', 'Controller');
            App::uses('IparapheurComponent', 'Controller/Component');
            $collection = new ComponentCollection();
            $this->Parafwebservice =new IparapheurComponent($collection);
            // Controle de l'avancement des délibérations dans le parapheur
            $delibs = $this->Deliberation->find('all',
                                                array('conditions' => array('Deliberation.etat >' =>0,
                                                                            'Deliberation.etat_parapheur' => 1 ),
                                                      'recursive' => -1,
                                                      'fields'    => array('id', 'objet', 'typeacte_id')));

            foreach ($delibs as $delib) {
                $compteur_id = 0;
                $typeacte = $this->Deliberation->Typeacte->find('first', array('conditions' => array('Typeacte.id' => $delib['Deliberation']['typeacte_id'])));
                if ($typeacte['Nature']['code'] != 'DE')
                    $compteur_id = $typeacte['Typeacte']['compteur_id'];
                $objetDossier = $this->Parafwebservice->handleObject($delib['Deliberation']['objet']);
                $this->_checkEtatParapheur($delib['Deliberation']['id'], false,  $objetDossier, $compteur_id);
            }
      
        }
        
         function _checkEtatParapheur($delib_id, $tdt=false, $objet, $compteur_id=0) {

             
            App::uses('AppShell', 'Console/Command');
            App::uses('ComponentCollection', 'Controller');
            App::uses('IparapheurComponent', 'Controller/Component');
            $collection = new ComponentCollection();
            $this->Parafwebservice =new IparapheurComponent($collection);

            $this->Deliberation->id = $delib_id;
            $histo = $this->Parafwebservice->getHistoDossierWebservice("$delib_id $objet");
            for ($i =0; $i < count($histo['logdossier']); $i++){
                if(!$tdt){
                   if (($histo['logdossier'][$i]['status']  ==  'Signe')    ||
                       ($histo['logdossier'][$i]['status']  ==  'Archive')) {

                   // TODO LIST : Récupère la date et heure de signature  + QUi l'a signé (annotation)
                           $this->Commentaire->create();
                           $comm ['Commentaire']['delib_id'] = $delib_id;
                           $comm ['Commentaire']['agent_id'] = -1;
                           $comm ['Commentaire']['texte'] = utf8_decode($histo['logdossier'][$i]['nom']." : ".$histo['logdossier'][$i]['annotation']);
                           $comm ['Commentaire']['commentaire_auto'] = 0;
                           $this->Commentaire->save($comm['Commentaire']);

                           $delib=$this->Deliberation->find('first', array('conditions' => array("Deliberation.id" => $delib_id)));
                           if ($delib['Deliberation']['etat_parapheur']==1){
                               if ($histo['logdossier'][$i]['status']  ==  'Signe') {
                                   $dossier = $this->Parafwebservice->GetDossierWebservice("$delib_id $objet");
                                   if (!empty($dossier['getdossier'][10])) {
                                       $this->Deliberation->saveField('delib_pdf',  base64_decode($dossier['getdossier'][8]));
                                       $this->Deliberation->saveField('signature',  base64_decode($dossier['getdossier'][10]));
                                   }
                                   $this->Deliberation->saveField('signee',  1);
                                   if ($compteur_id != 0 ) {
                                       $this->Deliberation->saveField('date_acte',  date("Y-m-d H:i:s", strtotime("now")));
                                       $num =  $this->Deliberation->Seance->Typeseance->Compteur->genereCompteur($compteur_id);
                                       $this->Deliberation->saveField('num_delib', $num);
                                   }
                               }
                               // etat_paraph à 1, donc, nous sommes en post_seance, on ne supprime pas le projet
                               $this->Deliberation->saveField('etat_parapheur', 2);
                               $this->Parafwebservice->archiverDossierWebservice("$delib_id $objet", "EFFACER");
                           }
                       }
                       elseif(($histo['logdossier'][$i]['status']=='RejetSignataire')||
                              ($histo['logdossier'][$i]['status']=='RejetVisa') ){ // Cas de refus dans le parapheur

                           $this->Commentaire->create();
                           $comm ['Commentaire']['delib_id'] = $delib_id;
                           $comm ['Commentaire']['agent_id'] = -1;
                           $comm ['Commentaire']['texte'] = utf8_decode($histo['logdossier'][$i]['nom']." : ".$histo['logdossier'][$i]['annotation']);
                           $comm ['Commentaire']['commentaire_auto'] = 0;
                           $this->Commentaire->save($comm['Commentaire']);
                           $this->Deliberation->saveField('etat_parapheur', -1);
                          // $this->Deliberation->refusDossier($delib_id);
                           // Supprimer le dossier du parapheur
                           $effdos = $this->Parafwebservice->effacerDossierRejeteWebservice("$delib_id $objet"); 
                       }
            }
            else{
                if ($histo['logdossier'][$i]['status']  ==  'EnCoursTransmission')
                    return true;
            }
            }
            return false;
        }

        
    }
?>