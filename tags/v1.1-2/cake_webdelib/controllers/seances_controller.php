<?php
class SeancesController extends AppController {

	var $name = 'Seances';
	var $helpers = array('Html', 'Form', 'Javascript', 'Fck', 'fpdf', 'Html2');
	var $components = array('Date','Email', 'Gedooo');
	var $uses = array('Deliberation', 'Seance', 'User', 'Collectivite', 'Listepresence', 'Vote', 'Model', 'Annex', 'Typeseance', 'Acteur');
	var $cacheAction = 0;

	// Gestion des droits
	var $demandeDroit = array('listerFuturesSeances', 'add', 'afficherCalendrier');
	var $commeDroit = array(
		'index'=>'Seances:listerFuturesSeances',
		'view'=>'Seances:listerFuturesSeances',
		'delete'=>'Seances:listerFuturesSeances',
		'edit'=>'Seances:listerFuturesSeances',
		'afficherProjets'=>'Seances:listerFuturesSeances',
		'addListUsers'=>'Seances:listerFuturesSeances',
		'generateConvocationList'=>'Seances:listerFuturesSeances',
		'generateOrdresDuJour'=>'Seances:listerFuturesSeances',
		'saisirDebatGlobal'=>'Seances:listerFuturesSeances',
		'details'=>'Seances:listerFuturesSeances',
		'saisirDebat'=>'Seances:listerFuturesSeances',
		'voter'=>'Seances:listerFuturesSeances',
		'changeRapporteur'=>'Seances:listerFuturesSeances',
		'changeStatus'=>'Seances:listerFuturesSeances',
		'detailsAvis'=>'Seances:listerFuturesSeances',
		'donnerAvis'=>'Seances:listerFuturesSeances',
		'saisirSecretaire'=>'Seances:listerFuturesSeances',
		'getListActeurs'=>'Seances:listerFuturesSeances'
	);

	function index() {
		$this->Seance->recursive = 0;
		$seances = $this->Seance->findAll(null,null,'date asc');
		for ($i=0; $i<count($seances); $i++)
		    $seances[$i]['Seance']['date'] = $this->Date->frenchDateConvocation(strtotime($seances[$i]['Seance']['date']));

		$this->set('seances', $seances);
	}


	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalide id pour la seance.');
			$this->redirect('/seances/index');
		}
		$this->set('seance', $this->Seance->read(null, $id));
	}

	function add($timestamp=null) {
		if (empty($this->data)) {
			if (isset($timestamp))
			    $this->set('date', date('d/m/Y',$timestamp));

			$this->set('typeseances', $this->Seance->Typeseance->generateList());
			$this->set('selectedTypeseances', null);
			$this->render();
		} else {
			$this->cleanUpFields('Seance');
			$this->data['Seance']['date']=  $this->Utils->FrDateToUkDate($this->params['form']['date']);
			$this->data['Seance']['date'] = $this->data['Seance']['date'].' '.$this->data['Seance']['date_hour'].':'.$this->data['Seance']['date_min'];

			if ($this->Seance->save($this->data)) {
				$this->Session->setFlash('La s&eacute;ance a &eacute;t&eacute; sauvegard&eacute;');
				$this->redirect('/seances/listerFuturesSeances');
			} else {
				$this->Session->setFlash('Corrigez les erreurs ci-dessous.');
				$this->set('typeseances', $this->Seance->Typeseance->generateList());
				if (empty($this->data['Typeseance']['Typeseance'])) {
					$this->data['Typeseance']['Typeseance'] = null;
				}
				$this->set('selectedTypeseances', $this->data['Typeseance']['Typeseance']);
			}
		}
	}

	function edit($id = null) {
		if (empty($this->data)) {
			if (!$id) {
				$this->Session->setFlash('Invalide id pour la seance');
				$this->redirect('/seances/index');
			}
			$this->data = $this->Seance->read(null, $id);
			$this->set('typeseances', $this->Seance->Typeseance->generateList());
			if (empty($this->data['Typeseance'])) { $this->data['Typeseance'] = null; }
				$this->set('selectedTypeseances', $this->_selectedArray($this->data['Typeseance']));
		} else {
			$this->cleanUpFields('Seance');
			if ($this->Seance->save($this->data)) {
				$this->Session->setFlash('La s&eacute;ance a &eacute;t&eacute; sauvegard&eacute;');
				$this->redirect('/seances/index');
			} else {
				$this->Session->setFlash('Corrigez les erreurs ci-dessous.');
				if (empty($this->data['Typeseance']['Typeseance'])) { $this->data['Typeseance']['Typeseance'] = null; }
					$this->set('selectedTypeseances', $this->data['Typeseance']['Typeseance']);
			}
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalide id pour la seance');
			$this->redirect('/seances/index');
		}
		if ($this->Seance->del($id)) {
			$this->Session->setFlash('La s&eacute;ance a &eacute;t&eacute; suprim&eacute;e');
			$this->redirect('/seances/index');
		}
	}

	function listerFuturesSeances() {
            $this->set('USE_GEDOOO', USE_GEDOOO);
            if (empty ($this->data)) {
                $condition= 'Seance.traitee = 0';
	        $seances = $this->Seance->findAll(($condition),null,'date asc');

	 	for ($i=0; $i<count($seances); $i++){
		    $seances[$i]['Seance']['dateEn'] =  $seances[$i]['Seance']['date'];
		    $seances[$i]['Seance']['date'] = $this->Date->frenchDateConvocation(strtotime($seances[$i]['Seance']['date']));
	       }
                $this->set('seances', $seances);
	    }
	}

	function listerAnciennesSeances() {
			if (empty ($this->data)) {
			//$condition= 'date <= "'.date('Y-m-d H:i:s').'"';
			$condition= 'Seance.traitee = 1';
			$seances = $this->Seance->findAll(($condition),null,'date asc');

			for ($i=0; $i<count($seances); $i++)
			    $seances[$i]['Seance']['date'] = $this->Date->frenchDateConvocation(strtotime($seances[$i]['Seance']['date']));

			$this->set('seances', $seances);
		}
	}

	function changeStatus ($seance_id) {
		$this->data=$this->Seance->read(null,$seance_id);
		$this->data['Seance']['traitee']=1;
		if ($this->Seance->save($this->data))
			$this->redirect('/seances/listerFuturesSeances');
	}

	function afficherCalendrier ($annee=null){

		vendor('Calendar/includeCalendarVendor');

		define ('CALENDAR_MONTH_STATE',CALENDAR_USE_MONTH_WEEKDAYS);

		if (!isset($annee))
		     $annee = date('Y');

 		$tabJoursSeances = array();
 		$fields = 'date';
        $condition = "annee = $annee";
        $joursSeance = $this->Seance->findAll(null, $fields);
        foreach ($joursSeance as $date) {
        	$date = strtotime(substr($date['Seance']['date'], 0, 10));
        	array_push($tabJoursSeances,  $date);
        }

  		$Year = new Calendar_Year($annee);
		$Year->build();
	    $today = mktime('0','0','0');
	    $i = 0;

		$calendrier = "<table>\n<tr   style=\"vertical-align:top;\">\n";
		while ( $Month = $Year->fetch() ) {

	  		$calendrier .= "<td><table class=\"month\">\n" ;
	     	$calendrier .= "<caption class=\"month\">".$this->Date->months[$Month->thisMonth('int')]."</caption>\n" ;
	     	$calendrier .= "<tr><th>Lu</th><th>Ma</th><th>Me</th><th>Je</th><th>Ve</th><th>Sa</th><th>Di</th></tr>\n";
	   		$Month->build();

			while ( $Day = $Month->fetch() ) {
		        if ( $Day->isFirst() == 1 ) {
		       		$calendrier .= "<tr>\n" ;
		        }

		        if ( $Day->isEmpty() ) {
		           $calendrier .=  "<td>&nbsp;</td>\n" ;
		        }
		        else {
					$timestamp = $Day->thisDay('timestamp');
		            if ($today == $Day->thisDay('timestamp')){
		                 $balise="today";
		            }
		            elseif (in_array ($Day->thisDay('timestamp'), $tabJoursSeances) )
		            {
		            	$balise="seance";
		            }
		            else {
		            	$balise="normal";
		            }
		            $calendrier .=  "<td><a href =\"add/$timestamp\"><p class=\"$balise\">".$Day->thisDay()."</p></a></td>\n" ;
		        }
		        if ( $Day->isLast() ) {
		           $calendrier .=  "</tr>\n" ;
		        }
			}

     		$calendrier .= "</table>\n</td>\n" ;

	    	if ($i==5)
	        	$calendrier .= "</tr><tr   style=\"vertical-align:top;\">\n" ;

	    	$i++;
		}
		$calendrier .=  "</tr>\n</table>\n" ;

		$this->set('annee', $annee);
		$this->set('calendrier',$calendrier);
	}

	function afficherProjets ($id=null, $return=null)
	{
		$condition= "seance_id=$id AND (etat != -1 )";
		if (!isset($return)) {
		    $this->set('lastPosition', $this->requestAction("deliberations/getLastPosition/$id") - 1 );
			$deliberations = $this->Deliberation->findAll($condition,null,'Deliberation.position ASC');
			for ($i=0; $i<count($deliberations); $i++) {
				$id_service = $deliberations[$i]['Service']['id'];
				$deliberations[$i]['Service']['libelle'] = $this->requestAction("services/doList/$id_service");
				$deliberations[$i]['rapp_id'] = $this->requestAction("deliberations/getRapporteur/".$deliberations[$i]['Deliberation']['id']);
			}
			$this->set('seance_id', $id);
//			$this->set('rapporteurs', $this->Deliberation->User->generateList('statut=1'));
			$this->set('rapporteurs', $this->Deliberation->Acteur->generateListElus());
			$this->set('projets', $deliberations);
			$this->set('date_seance', $this->Date->frenchDateConvocation(strtotime($this->GetDate($id))));
		}
		else
		    return ($this->Deliberation->findAll($condition,null,'Deliberation.position ASC'));
	}

    function changeRapporteur($newRapporteur,$delib_id) {
    	$this->Deliberation->create();
    	$this->data['Deliberation']['id']=$delib_id;
    	$this->data['Deliberation']['rapporteur_id']= $newRapporteur;
		if ($this->Deliberation->save($this->data['Deliberation'])){
    		//redirection sur la page où on était avant de changer de service
       		$this->Redirect($this->Session->read('user.User.lasturl'));
       	}
    }

    function getDate($id=null)
    {
		if (empty($id))
			return '';
		else{
		$condition = "Seance.id = $id";
        $objCourant = $this->Seance->findAll($condition);
		return $objCourant['0']['Seance']['date'];}
    }

    function getType($id)
    {
		$condition = "Seance.id = $id";
        return $this->Seance->findAll($condition);
    }

	function generateConvocationList ($id_seance=null) {
	    $seance = $this->Seance->read(null, $id_seance);
	    $acteursConvoques = $this->Seance->Typeseance->acteursConvoquesParTypeSeanceId($seance['Seance']['type_id']);
	    $model = $this->Model->read(null, $seance['Typeseance']['modelconvocation_id']);
	    $jour = $this->Date->days[intval(date('w'))];
	    $mois = $this->Date->months[intval(date('m'))];
	    $collectivite = $this->Collectivite->findAll();
            $date_seance = $this->Date->frenchDateConvocation(strtotime($seance['Seance']['date']));

            $search = array(
			"#LOGO_COLLECTIVITE#",
			"#NOM_COLLECTIVITE#",
			"#ADRESSE_COLLECTIVITE#",
			"#CP_COLLECTIVITE#",
			"#VILLE_COLLECTIVITE#",
			"#TELEPHONE_COLLECTIVITE#",
			"#NOM_ACTEUR#",
			"#PRENOM_ACTEUR#",
			"#SALUTATION_ACTEUR#",
			"#TITRE_ACTEUR#",
			"#ADRESSE1_ACTEUR#",
			"#ADRESSE2_ACTEUR#",
			"#CP_ACTEUR#",
			"#VILLE_ACTEUR#",
			"#DATE_DU_JOUR#",
			"#TYPE_SEANCE#",
			"#DATE_SEANCE#",
			"#LISTE_PROJETS_SOMMAIRES#",
			"#LISTE_PROJETS_DETAILLES#"
		);

	    vendor('fpdf/html2fpdf');
	    $pdf = new HTML2FPDF();
            foreach($acteursConvoques as $acteur) {
                $pdf->AddPage();
		$emailPdf = new HTML2FPDF();
		$emailPdf->AddPage();
	        $replace = array(
			'<img src="files/image/logo.jpg">',
			$collectivite[0]['Collectivite']['nom'],
			$collectivite[0]['Collectivite']['adresse'],
			$collectivite[0]['Collectivite']['CP'],
			$collectivite[0]['Collectivite']['ville'],
			$collectivite[0]['Collectivite']['telephone'],
			$acteur['Acteur']['nom'],
			$acteur['Acteur']['prenom'],
			$acteur['Acteur']['salutation'],
			$acteur['Acteur']['titre'],
			$acteur['Acteur']['adresse1'],
			$acteur['Acteur']['adresse2'],
			$acteur['Acteur']['cp'],
			$acteur['Acteur']['ville'],
			$jour.' '.date('d').' '.$mois.' '.date('Y'),
			$seance['Typeseance']['libelle'],
			$date_seance,
			$this->requestAction("/models/listeProjets/$id_seance/0"),
			$this->requestAction("/models/listeProjets/$id_seance/1")
		);
	        $generation = str_replace($search,$replace,$model['Model']['content']);
	        $pdf->WriteHTML($generation);
		$emailPdf->WriteHTML($generation);

                $pos =  strrpos ( getcwd(), 'webroot');
		$path = substr(getcwd(), 0, $pos);
		$convoc_path = $path."webroot/files/convocations/convoc_".$acteur['Acteur']['id'].".pdf";
		$emailPdf->Output($convoc_path ,'F');
		$this->sendConvoc($acteur['Acteur']['id'], $convoc_path, $seance['Typeseance']['libelle'], $date_seance);
                unlink($convoc_path);
    	    }
            $pdf->Output('convocations.pdf','D');
	}

	function sendConvoc($user_id,  $convoc_path, $type_seance, $date_seance) {
		$condition = "User.id = $user_id";
		$data = $this->User->findAll($condition);
		// Si l'utilisateur accepte les mails
		if ($data['0']['User']['accept_notif']){
			$to_mail = $data['0']['User']['email'];
			$to_nom = $data['0']['User']['nom'];
			$to_prenom = $data['0']['User']['prenom'];

			$this->Email->template = 'email/convoquer';
			$text = "Convocation";
            $this->set('data', utf8_encode( "Vous venez de recevoir une convocation au  $type_seance du $date_seance"));
            $this->Email->to = $to_mail;
            $this->Email->subject = utf8_encode("Convocation au $type_seance du $date_seance");
       	    $this->Email->attach($convoc_path, 'convocation.pdf');
            $result = $this->Email->send();
		}
	}

	function generateOrdresDuJour ($id_seance = null) {
		$seance = $this->Seance->read(null, $id_seance);
		$acteursConvoques = $this->Seance->Typeseance->acteursConvoquesParTypeSeanceId($seance['Seance']['type_id']);
		$model = $this->Model->read(null, $seance['Typeseance']['modelordredujour_id']);
		$jour=$this->Date->days[intval(date('w'))];
		$mois=$this->Date->months[intval(date('m'))];
		$collectivite=  $this->Collectivite->findAll();
		$date_seance=  $this->Date->frenchDate(strtotime($seance['Seance']['date']));

		vendor('fpdf/html2fpdf');
		$pdf = new HTML2FPDF();

		$search = array(
			"#LOGO_COLLECTIVITE#",
			"#NOM_COLLECTIVITE#",
			"#ADRESSE_COLLECTIVITE#",
			"#CP_COLLECTIVITE#",
			"#VILLE_COLLECTIVITE#",
			"#TELEPHONE_COLLECTIVITE#",
			"#NOM_ACTEUR#",
			"#PRENOM_ACTEUR#",
			"#SALUTATION_ACTEUR#",
			"#TITRE_ACTEUR#",
			"#ADRESSE1_ACTEUR#",
			"#ADRESSE2_ACTEUR#",
			"#CP_ACTEUR#",
			"#VILLE_ACTEUR#",
			"#DATE_DU_JOUR#",
			"#TYPE_SEANCE#",
			"#DATE_SEANCE#",
			"#LISTE_PROJETS_SOMMAIRES#",
			"#LISTE_PROJETS_DETAILLES#"
		);

   		foreach($acteursConvoques as $acteur) {
			$pdf->AddPage();
			$replace = array(
				'<img src="files/image/logo.jpg">',
				$collectivite[0]['Collectivite']['nom'],
				$collectivite[0]['Collectivite']['adresse'],
				$collectivite[0]['Collectivite']['CP'],
				$collectivite[0]['Collectivite']['ville'],
				$collectivite[0]['Collectivite']['telephone'],
				$acteur['Acteur']['nom'],
				$acteur['Acteur']['prenom'],
				$acteur['Acteur']['salutation'],
				$acteur['Acteur']['titre'],
				$acteur['Acteur']['adresse1'],
				$acteur['Acteur']['adresse2'],
				$acteur['Acteur']['cp'],
				$acteur['Acteur']['ville'],
				$jour.' '.date('d').' '.$mois.' '.date('Y'),
				$seance['Typeseance']['libelle'],
				$date_seance,
				$this->requestAction("/models/listeProjets/$id_seance/0"),
				$this->requestAction("/models/listeProjets/$id_seance/1")
			);
			$generation = str_replace($search,$replace,$model['Model']['content']);
			$pdf->WriteHTML($generation);

    	}
		$pdf->Output('odj.pdf','D');

	}

	function delUserFromList($user_id, $seance_id) {
		$data = $this->Listepresence->findAll("seance_id = $seance_id AND user_id = $user_id");
		$this->Listepresence->del($data[0]['Listepresence']['id']);
	}

	function addUserFromList($user_id, $seance_id) {
		$this->params['data']['Listepresence']['id']='';
		$this->params['data']['Listepresence']['seance_id'] = $seance_id;
		$this->params['data']['Listepresence']['user_id'] = $user_id ;
		$this->Listepresence->save($this->params['data']);
	}

	function isInList($user_id, $listInscrits){
		$isIn = false;
		foreach ($listInscrits as $inscrit)
			if ($inscrit['User']['id'] == $user_id){
			   	//echo($inscrit['User']['nom']." est dans la liste <br>");
			    return true;
			}
	     return $isIn;
	}

	function details ($seance_id=null) {
		$this->set('USE_GEDOOO', USE_GEDOOO);
		$deliberations=$this->afficherProjets($seance_id, 0);
		$ToutesVotees = true;
		for ($i=0; $i<count($deliberations); $i++){
                    $id_service = $deliberations[$i]['Service']['id'];
		    $deliberations[$i]['Service']['libelle'] = $this->requestAction("services/doList/$id_service");
		    $deliberations[$i]['Model']['id'] = $this->requestAction("deliberations/getModelId/". $deliberations[$i]['Deliberation']['id']);
                    if (($deliberations[$i]['Deliberation']['etat']!=3)AND($deliberations[$i]['Deliberation']['etat']!=4))
                        $ToutesVotees = false;
		}
		$this->set('deliberations',$deliberations);
		$date_tmpstp = strtotime($this->GetDate($seance_id));
		$this->set('date_tmpstp', $date_tmpstp);
		$this->set('date_seance', $this->Date->frenchDateConvocation($date_tmpstp));
		$this->set('seance_id', $seance_id);
		$this->set('canClose', $ToutesVotees);
	}

	function effacerVote($deliberation_id=null) {
		$condition = "delib_id = $deliberation_id";
		$votes = $this->Vote->findAll($condition);
		foreach($votes as $vote)
  		    $this->Vote->del($vote['Vote']['id']);
	}

	function voter($deliberation_id=null) {
		$this->Deliberation->recursive = -1;
		$deliberation = $this->Deliberation->read(null, $deliberation_id);
		$seance = $this->Seance->read(null, $deliberation['Deliberation']['seance_id']);

		if (empty($this->data)) {
			// Initialisation du d�tail du vote
			$donnees = $this->Vote->findAll("delib_id = $deliberation_id");
			foreach($donnees as $donnee){
				$this->data['detailVote'][$donnee['Vote']['acteur_id']]=$donnee['Vote']['resultat'];
			}
			// Initialisation du total des voix
			$this->data['Deliberation']['vote_nb_oui'] = $deliberation['Deliberation']['vote_nb_oui'];
			$this->data['Deliberation']['vote_nb_non'] = $deliberation['Deliberation']['vote_nb_non'];
			$this->data['Deliberation']['vote_nb_abstention'] = $deliberation['Deliberation']['vote_nb_abstention'];
			$this->data['Deliberation']['vote_nb_retrait'] = $deliberation['Deliberation']['vote_nb_retrait'];
			// Initialisation du resultat
			$this->data['Deliberation']['etat'] = $deliberation['Deliberation']['etat'];
			// Initialisation du commentaire
			$this->data['Deliberation']['vote_commentaire'] = $deliberation['Deliberation']['vote_commentaire'];

			$this->set('deliberation' , $deliberation);
			$this->set('presents' , $this->requestAction('/deliberations/afficherListePresents/'.$deliberation_id));
		} else {
			$this->data['Deliberation']['id'] = $deliberation_id;
			$this->effacerVote($deliberation_id);
			switch ($this->data['Vote']['typeVote']) {
			case 1:
				// Saisie du d�tail du vote
				$this->data['Deliberation']['vote_nb_oui'] = 0;
				$this->data['Deliberation']['vote_nb_non'] = 0;
				$this->data['Deliberation']['vote_nb_abstention'] = 0;
				$this->data['Deliberation']['vote_nb_retrait'] = 0;
				foreach($this->data['detailVote']as $acteur_id => $vote){
					$this->Vote->create();
					$this->data['Vote']['acteur_id']=$acteur_id;
					$this->data['Vote']['delib_id']=$deliberation_id;
					$this->data['Vote']['resultat']=$vote;
			    	$this->Vote->save($this->data['Vote']);
					if ($vote == 3)
						$this->data['Deliberation']['vote_nb_oui']++;
					elseif ($vote == 2)
						$this->data['Deliberation']['vote_nb_non']++;
					elseif ($vote == 4)
						$this->data['Deliberation']['vote_nb_abstention']++;
					elseif ($vote == 5)
						$this->data['Deliberation']['vote_nb_retrait']++;
				}
				if ($this->data['Deliberation']['vote_nb_oui']>$this->data['Deliberation']['vote_nb_non'])
					$this->data['Deliberation']['etat'] = 3;
				else
					$this->data['Deliberation']['etat'] = 4;
    			break;
			case 2:
				// Saisie du total du vote
				if ($this->data['Deliberation']['vote_nb_oui']>$this->data['Deliberation']['vote_nb_non'])
					$this->data['Deliberation']['etat'] = 3;
				else
					$this->data['Deliberation']['etat'] = 4;
    			break;
			case 3:
				// Saisie du resultat global
				$this->data['Deliberation']['vote_nb_oui'] = 0;
				$this->data['Deliberation']['vote_nb_non'] = 0;
				$this->data['Deliberation']['vote_nb_abstention'] = 0;
				$this->data['Deliberation']['vote_nb_retrait'] = 0;
			    break;
			}

		    // Attribution du num�ro de la d�lib�ration si adopt�e et si pas d�j� attribu�
			if ( ($this->data['Deliberation']['etat'] == 3)
				&& empty($deliberation['Deliberation']['num_delib']) )
				$this->data['Deliberation']['num_delib'] = $this->Seance->Typeseance->Compteur->genereCompteur($seance['Typeseance']['compteur_id']);

			$this->Deliberation->save($this->data);
			$this->redirect('seances/details/'.$deliberation['Deliberation']['seance_id']);
		}
	}


	function saisirDebat ($id = null)	{
            $seance_id = $this->requestAction('/deliberations/getCurrentSeance/'.$id);
            if (empty($this->data)) {
                $this->data = $this->Deliberation->read(null, $id);        
	        $this->set('delib', $this->data);  
	    } 
            else { 
		if (isset($this->data['Deliberation']['texte_doc'])){
                    if ($this->data['Deliberation']['texte_doc']['size']!=0){
                        $this->data['Deliberation']['debat_name'] = $this->data['Deliberation']['texte_doc']['name'];
                        $this->data['Deliberation']['debat_size'] = $this->data['Deliberation']['texte_doc']['size'];
                        $this->data['Deliberation']['debat_type'] = $this->data['Deliberation']['texte_doc']['type'];
                        $this->data['Deliberation']['debat']      = $this->getFileData($this->data['Deliberation']['texte_doc']['tmp_name'], $this->data['Deliberation']['texte_doc']['size']);
                        unset($this->data['Deliberation']['texte_doc']);
                    }
                }
		$this->data['Deliberation']['id']=$id;
                if ($this->Deliberation->save($this->data)) {
                    $this->redirect('/seances/saisirDebat/'.$id);
                } else {
                    $this->Session->setFlash('Please correct errors below.');
                }
            }
	}


	function getFileData($fileName, $fileSize) {
		return fread(fopen($fileName, "r"), $fileSize);
	}

	function saisirDebatGlobal ($id = null) {

		if (empty($this->data)) {
			$this->data = $this->Seance->read(null, $id);
			$this->set('annexes',$this->Annex->findAll('Annex.seance_id='.$id.' AND type="A"'));
		        $this->set('seance', $this->data);
		} else{
                     if (isset($this->data['Seance']['texte_doc'])){
			if ($this->data['Seance']['texte_doc']['size']!=0){
                             $this->data['Seance']['id'] = $id;
			     $this->data['Seance']['debat_global_name'] = $this->data['Seance']['texte_doc']['name'];
                             $this->data['Seance']['debat_global_size'] = $this->data['Seance']['texte_doc']['size'];
                             $this->data['Seance']['debat_global_type'] = $this->data['Seance']['texte_doc']['type'];
                             $this->data['Seance']['debat_global']      = $this->getFileData($this->data['Seance']['texte_doc']['tmp_name'], $this->data['Seance']['texte_doc']['size']);
                             $this->Seance->save($this->data);
                             unset($this->data['Seance']['texte_doc']);
                         }
                     }
		
		        $this->data['Seance']['id']=$id;
			if(!empty($this->params['form']))
			{
				$seance = array_shift($this->params['form']);
				$annexes = $this->params['form'];

				$uploaded = true;
				$size = count($this->params['form']);
				$counter = 1;

				while($counter <= ($size/2))
				{
					if(!is_uploaded_file($annexes['file_'.$counter]['tmp_name'])){
						$uploaded = false;
					}
					$counter++;
				}

				if($uploaded) {
					if ($this->Seance->save($this->data)) {
					$counter = 1;

						while($counter <= ($size/2)) {
							$this->data['Annex']['id'] = null;
							$this->data['Annex']['deliberation_id'] = 0;
							$this->data['Annex']['seance_id'] = $id;
							$this->data['Annex']['titre'] = $annexes['titre_'.$counter];
							$this->data['Annex']['type'] = 'A';
							$this->data['Annex']['filename'] = $annexes['file_'.$counter]['name'];
							$this->data['Annex']['filetype'] = $annexes['file_'.$counter]['type'];
							$this->data['Annex']['size'] = $annexes['file_'.$counter]['size'];
							$this->data['Annex']['data'] = $this->getFileData($annexes['file_'.$counter]['tmp_name'], $annexes['file_'.$counter]['size']);
							if(!$this->Annex->save($this->data))
							{
								echo "pb de sauvegarde de l\'annexe ".$counter;
							}
						$counter++;
						}
						$this->redirect('/seances/listerFuturesSeances');
					} else {
						$this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.');
					}
				}
			}
		}
	}


        function generer ($seance_id, $model_id, $editable=0){
	    include ('vendors/progressbar.php');
            $editable = $this->Session->read('user.format.sortie');
	    $cpt = 1;
            // Pr�paration des répertoires et URL pour la création des fichiers
            $dyn_path = "/files/generee/seances/$seance_id/";
            $path = WEBROOT_PATH.$dyn_path;
	    $urlWebroot =  'http://'.$_SERVER['HTTP_HOST'].$this->base.$dyn_path;
            $urlFiles =  'http://'.$_SERVER['HTTP_HOST'].$this->base."/files/generee/modeles/$seance_id/";
	    $pathFile =  WEBROOT_PATH."/files/generee/modeles/$seance_id/";
	    if (!$this->Gedooo->checkPath($path))
                die("Webdelib ne peut pas ecrire dans le repertoire : $path");

            //Cr�ation du model ott
            $content = $this->requestAction("/models/getModel/$model_id");
	    $data = $this->Model->read(null, $model_id);
	    $nomModel = $data['Model']['modele'];
            $model = $this->Gedooo->createFile($path,'model_'.$model_id.'.odt', $content);

	    $data = $this->Seance->read(null, $seance_id);
            $acteursConvoques = $this->Seance->Typeseance->acteursConvoquesParTypeSeanceId($data['Seance']['type_id']);
	    $nbActeurs = count($acteursConvoques);
	    $listFiles = array();
	    Initialize(200, 100,200, 30,'#000000','#FFCC00','#006699');
            $dataColl = $this->Collectivite->read(null, 1);
	        //
                //*****************************************
                //* Création du fichier XML de données    *
                //*****************************************
            ProgressBar(3, 'Lecture des donn&eacute;es pour votre collectivit&eacute;');
            $balises  = $this->Gedooo->CreerBalise('nom_collectivite', $dataColl['Collectivite']['nom'], 'string');
            $balises .= $this->Gedooo->CreerBalise('adresse_collectivite', $dataColl['Collectivite']['adresse'], 'string');
            $balises .= $this->Gedooo->CreerBalise('cp_collectivite', $dataColl['Collectivite']['CP'], 'string');
            $balises .= $this->Gedooo->CreerBalise('ville_collectivite', $dataColl['Collectivite']['ville'], 'string');
            $balises .= $this->Gedooo->CreerBalise('telephone_collectivite', $dataColl['Collectivite']['telephone'], 'string');
            $balises .= $this->Gedooo->CreerBalise('logo_collectivite', $urlWebroot.'logo.html', 'content');
            if (GENERER_DOC_SIMPLE==false){
	        if ( $data['Seance']['debat_global_name']== "")
                    $nameDebat = "vide";
	        else
                    $nameDebat = $data['Seance']['debat_global_name'];
            }
            else {
               $nameDebat =  'debat.html';
            }
		
            //Création du fichier des débats globaux à la séance
	    $this->Gedooo->createFile($path, $nameDebat, '<p>'.$data['Seance']['debat_global'].'</p>');
            $balises .= $this->Gedooo->CreerBalise('debat_seance', $urlWebroot.$nameDebat, 'content');

	    // Création de la liste des projets detailles
            ProgressBar(33, 'Lecture des donn&eacute;es pour vos projets sommaires');
	    $listeProjetsDetailles = $this->requestAction("/models/listeProjets/$seance_id/1");
            $this->Gedooo->createFile($path, 'ProjetsDetailles.html',  $listeProjetsDetailles);
            $balises .= $this->Gedooo->CreerBalise('projets_detailles', $urlWebroot.'ProjetsDetailles.html', 'content');

	    // Création de la liste des projets sommaires
            ProgressBar(66, 'Lecture des donn&eacute;es pour vos projets d&eacute;taill&eacute;s');
	    $listeProjetsSommaires = $this->requestAction("/models/listeProjets/$seance_id/0");
            $this->Gedooo->createFile($path, 'ProjetsSommaires.html',  $listeProjetsSommaires);
            $balises .= $this->Gedooo->CreerBalise('projets_sommaires', $urlWebroot.'ProjetsSommaires.html', 'content');
               
            // Création de la liste des projets ODJ
            ProgressBar(99, 'Lecture des donn&eacute;es pour vos projets odj');
            $listeProjetsOdj = $this->requestAction("/models/listeProjets/$seance_id/2");
            $this->Gedooo->createFile($path, 'ProjetsOdj.html',  $listeProjetsOdj);
            $balises .= $this->Gedooo->CreerBalise('projets_odj', $urlWebroot.'ProjetsOdj.html', 'content');

            // Informations sur la seance
	    if (isset($data['Seance']['date'])){
                $balises .= $this->Gedooo->CreerBalise('date_seance', $this->Date->frenchDateConvocation(strtotime($data['Seance']['date'])), 'string');
                $balises .= $this->Gedooo->CreerBalise('date_seance_maj', strtoupper($this->Date->frenchDateConvocation(strtotime($data['Seance']['date']))), 'string');
	        $balises .= $this->Gedooo->CreerBalise('date_seance_sans_heure', $this->Date->frenchDate(strtotime($data['Seance']['date'])), 'string');
                $balises .= $this->Gedooo->CreerBalise('date_seance_lettres_maj', strtoupper($this->Date->dateLettres(strtotime($data['Seance']['date']))), 'string');
                $balises .= $this->Gedooo->CreerBalise('date_seance_lettres', $this->Date->dateLettres(strtotime($data['Seance']['date'])), 'string');
            }
            $balises .= $this->Gedooo->CreerBalise('type_seance', $this->requestAction('/typeseances/getField/'.$data['Seance']['type_id'].'/libelle'), 'string');

	    foreach ($acteursConvoques as $acteur ) {
                // Informations sur la collectivité
                ProgressBar($cpt*(100/$nbActeurs), 'Lecture des donn&eacute;es pour : <b>'. $acteur['Acteur']['prenom']." ".$acteur['Acteur']['nom'].'</b>');
                $this->Gedooo->createFile($path, 'logo.html', '<img src="'. 'http://'.$_SERVER['HTTP_HOST'].$this->base.'/files/image/logo.jpg" />');

                // Informations sur l'acteur
                $balises .= $this->Gedooo->CreerBalise('type_acteur', $acteur['Typeacteur']['nom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('type_commentaire_acteur', $acteur['Typeacteur']['commentaire'], 'string');
                $balises .= $this->Gedooo->CreerBalise('nom_acteur', $acteur['Acteur']['nom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('prenom_acteur', $acteur['Acteur']['prenom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('salutation_acteur', $acteur['Acteur']['salutation'], 'string');
                $balises .= $this->Gedooo->CreerBalise('titre_acteur', $acteur['Acteur']['titre'], 'string');
                $balises .= $this->Gedooo->CreerBalise('date_naissance_acteur', $acteur['Acteur']['date_naissance'], 'string');
                $balises .= $this->Gedooo->CreerBalise('adresse1_acteur', $acteur['Acteur']['adresse1'], 'string');
                $balises .= $this->Gedooo->CreerBalise('adresse2_acteur', $acteur['Acteur']['adresse2'], 'string');
                $balises .= $this->Gedooo->CreerBalise('cp_acteur', $acteur['Acteur']['cp'], 'string');
                $balises .= $this->Gedooo->CreerBalise('ville_acteur', $acteur['Acteur']['ville'], 'string');
                $balises .= $this->Gedooo->CreerBalise('email_acteur', $acteur['Acteur']['email'], 'string');
                $balises .= $this->Gedooo->CreerBalise('telfixe_acteur', $acteur['Acteur']['telfixe'], 'string');
                $balises .= $this->Gedooo->CreerBalise('telmobile_acteur', $acteur['Acteur']['telmobile'], 'string');
                $balises .= $this->Gedooo->CreerBalise('note_acteur', $acteur['Acteur']['note'], 'string');
                $balises .= $this->Gedooo->CreerBalise('position_acteur', $acteur['Acteur']['position'], 'string');

	        // Informations sur la seance
                $balises .= $this->Gedooo->CreerBalise('seance_id', $seance_id, 'string');
                $balises .= $this->Gedooo->CreerBalise('nom_secretaire', $data['Secretaire']['nom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('prenom_secretaire', $data['Secretaire']['prenom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('salutation_secretaire', $data['Secretaire']['salutation'], 'string');
                $balises .= $this->Gedooo->CreerBalise('titre_secretaire', $data['Secretaire']['titre'], 'string');
                $balises .= $this->Gedooo->CreerBalise('note_secretaire', $data['Secretaire']['note'], 'string');
<<<<<<< .mine
	    
		$balises .= $this->Gedooo->CreerBalise('date_jour_courant', $this->Date->frenchDate(strtotime("now")), 'string');
		
		// création du fichier XML
=======
	    
		$balises .= $this->Gedooo->CreerBalise('date_jour_courant', $this->Date->frenchDate(strtotime("now")), 'string');
		
		// Informations sur la seance
	        if (isset($data['Seance']['date'])){
                    $balises .= $this->Gedooo->CreerBalise('date_seance', $this->Date->frenchDateConvocation(strtotime($data['Seance']['date'])), 'string');
                    $balises .= $this->Gedooo->CreerBalise('date_seance_maj', strtoupper($this->Date->frenchDateConvocation(strtotime($data['Seance']['date']))), 'string');
	            $balises .= $this->Gedooo->CreerBalise('date_seance_sans_heure', $this->Date->frenchDate(strtotime($data['Seance']['date'])), 'string');
                    $balises .= $this->Gedooo->CreerBalise('date_seance_lettres_maj', strtoupper($this->Date->dateLettres(strtotime($data['Seance']['date']))), 'string');
                    $balises .= $this->Gedooo->CreerBalise('date_seance_lettres', $this->Date->dateLettres(strtotime($data['Seance']['date'])), 'string');
                }

		$balises .= $this->Gedooo->CreerBalise('type_seance', $this->requestAction('/typeseances/getField/'.$data['Seance']['type_id'].'/libelle'), 'string');
                if (GENERER_DOC_SIMPLE==false){
		    if ( $data['Seance']['debat_global_name']== "")
		        $nameDebat = "vide";
		    else
                        $nameDebat = $data['Seance']['debat_global_name'];
                }
                else {
                   $nameDebat =  'debat.html';
                }

                //Création du fichier des débats globaux à la séance
		$this->Gedooo->createFile($path, $nameDebat, '<p>'.$data['Seance']['debat_global'].'</p>');
                $balises .= $this->Gedooo->CreerBalise('debat_seance', $urlWebroot.$nameDebat, 'content');

	        // Création de la liste des projets detailles
	        $listeProjetsDetailles = $this->requestAction("/models/listeProjets/$seance_id/1");
                $this->Gedooo->createFile($path, 'ProjetsDetailles.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('projets_detailles', $urlWebroot.'ProjetsDetailles.html', 'content');

	        // Création de la liste des projets sommaires
	        $listeProjetsSommaires = $this->requestAction("/models/listeProjets/$seance_id/0");
                $this->Gedooo->createFile($path, 'ProjetsSommaires.html',  $listeProjetsSommaires);
                $balises .= $this->Gedooo->CreerBalise('projets_sommaires', $urlWebroot.'ProjetsSommaires.html', 'content');
               
                // Création de la liste des projets ODJ
                $listeProjetsOdj = $this->requestAction("/models/listeProjets/$seance_id/2");
                $this->Gedooo->createFile($path, 'ProjetsOdj.html',  $listeProjetsOdj);
                $balises .= $this->Gedooo->CreerBalise('projets_odj', $urlWebroot.'ProjetsOdj.html', 'content');
 
		
		// création du fichier XML
>>>>>>> .r1474
                $datas    = $this->Gedooo->createFile($path,'data.xml', $balises);

		// Envoi du fichier à GEDOOo
                if ($editable == 0)
                    $extension = 'pdf';
                elseif  ($editable == 1)
                    $extension = 'odt';
	        elseif  ($editable == 2)
		    $extension = 'html';

                $nomFichier =  $acteur['Acteur']['id'].'.'.$extension;
		$this->Gedooo->sendFiles($model, $datas, $editable, 1,  $nomFichier, $seance_id);
                ProgressBar($cpt*(100/$nbActeurs), 'Document g&eacute;n&eacute;r&eacute; pour : <b>'. $acteur['Acteur']['prenom']." ".$acteur['Acteur']['nom'].'</b>');
                if ($acteur['Acteur']['email']!=''){
                    $to_mail   = $acteur['Acteur']['email'];
                    $to_nom    = $acteur['Acteur']['nom'];
                    $to_prenom = $acteur['Acteur']['prenom'];
                    $this->Email->attachments = null;
                    $this->Email->template = 'email/convoquer';
                    $this->set('data', utf8_encode( "Vous venez de recevoir un document de Webdelib ($nomModel)"));
                    $this->Email->to = $to_mail;
                    $this->Email->subject = utf8_encode("Vous venez de recevoir un document de Webdelib ($nomModel)");
		    $this->Email->attach($pathFile.$nomFichier, $nomFichier);
		    $result = $this->Email->send();
                    ProgressBar($cpt*(100/$nbActeurs), 'Document envoy&eacute; &agrave; : <b>'. $acteur['Acteur']['prenom']." ".$acteur['Acteur']['nom'].'</b>');
		    unset($result);
                }

		// Création d'un tableau pour l'affichage et le stockage des fichiers à récuperer
		$listFiles[$urlFiles.$nomFichier] = $acteur['Acteur']['prenom']." ".$acteur['Acteur']['nom'];
                $cpt++;
            }
	    $listFiles[$urlFiles.'documents.zip'] = 'Tous les documents';
	    $this->set('listFiles', $listFiles);
	    $this->set('seance_id', $seance_id);
            $this->render();
        }

        function genererPV ($seance_id, $model_id, $editable=0) {
            $editable = $this->Session->read('user.format.sortie');
	    
	    $dyn_path = "/files/generee/seances/$seance_id/";
            $path = WEBROOT_PATH.$dyn_path;
	    $urlWebroot = 'http://'.$_SERVER['HTTP_HOST'].$this->base.$dyn_path;
            $urlFiles = 'http://'.$_SERVER['HTTP_HOST'].$this->base.'/files/generee/modeles/';
	    $pathFile =  WEBROOT_PATH.'/files/generee/modeles/';
	    if (!$this->Gedooo->checkPath($path))
                die("Webdelib ne peut pas ecrire dans le repertoire : $path");

            //Cr�ation du model ott
            $content = $this->requestAction("/models/getModel/$model_id");
	    $data = $this->Model->read(null, $model_id);
	    $nomModel = $data['Model']['modele'];
            $model = $this->Gedooo->createFile($path,'model_'.$model_id.'.odt', $content);

	    $data = $this->Seance->read(null, $seance_id);
	        //
                //*****************************************
                //* Création du fichier XML de données    *
                //*****************************************
                // Informations sur la collectivité
                $this->Gedooo->createFile($path, 'logo.html', '<img src="'. 'http://'.$_SERVER['HTTP_HOST'].$this->base.'/files/image/logo.jpg" />');
                $dataColl = $this->Collectivite->read(null, 1);
                $balises  = $this->Gedooo->CreerBalise('nom_collectivite', $dataColl['Collectivite']['nom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('adresse_collectivite', $dataColl['Collectivite']['adresse'], 'string');
                $balises .= $this->Gedooo->CreerBalise('cp_collectivite', $dataColl['Collectivite']['CP'], 'string');
                $balises .= $this->Gedooo->CreerBalise('ville_collectivite', $dataColl['Collectivite']['ville'], 'string');
                $balises .= $this->Gedooo->CreerBalise('telephone_collectivite', $dataColl['Collectivite']['telephone'], 'string');
                $balises .= $this->Gedooo->CreerBalise('logo_collectivite', $urlWebroot.'logo.html', 'content');

	        // Informations sur la seance
                $balises .= $this->Gedooo->CreerBalise('seance_id', $seance_id, 'string');
                $balises .= $this->Gedooo->CreerBalise('nom_secretaire', $data['Secretaire']['nom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('prenom_secretaire', $data['Secretaire']['prenom'], 'string');
                $balises .= $this->Gedooo->CreerBalise('salutation_secretaire', $data['Secretaire']['salutation'], 'string');
                $balises .= $this->Gedooo->CreerBalise('titre_secretaire', $data['Secretaire']['titre'], 'string');
                $balises .= $this->Gedooo->CreerBalise('note_secretaire', $data['Secretaire']['note'], 'string');
                // Informations sur la seance
	        if (isset($data['Seance']['date'])) {
                    $balises .= $this->Gedooo->CreerBalise('date_seance', $this->Date->frenchDateConvocation(strtotime($data['Seance']['date'])), 'string');
		    $balises .= $this->Gedooo->CreerBalise('date_seance_maj', strtoupper($this->Date->frenchDateConvocation(strtotime($data['Seance']['date']))), 'string');
		}
                $balises .= $this->Gedooo->CreerBalise('type_seance', $this->requestAction('/typeseances/getField/'.$data['Seance']['type_id'].'/libelle'), 'string');
                if (GENERER_DOC_SIMPLE==false)
                    if ( $data['Seance']['debat_global_name']== "")
		        $nameDebat = "vide";
	            else
		        $nameDebat = $data['Seance']['debat_global_name'];
                else 
                   $nameDebat =  'debat.html';

                //Création du fichier des débats globaux à la séance
		$this->Gedooo->createFile($path, $nameDebat, '<p>'.$data['Seance']['debat_global'].'</p>');
                $balises .= $this->Gedooo->CreerBalise('debat_seance', $urlWebroot.$nameDebat, 'content');
	    
                $listeProjetsDetailles = $this->listeActeursMouvements($seance_id);
                $this->Gedooo->createFile($path, 'mouvements.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('liste_mouvements', $urlWebroot.'mouvements.html', 'content');
                
                $listeProjetsDetailles = $this->listeActeursPresents($seance_id);
                $this->Gedooo->createFile($path, 'presents.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('liste_presents', $urlWebroot.'presents.html', 'content');
                
                $listeProjetsDetailles = $this->listeActeursAbsents($seance_id);
                $this->Gedooo->createFile($path, 'absents.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('liste_absents', $urlWebroot.'absents.html', 'content');
    
                $listeProjetsDetailles = $this->listeActeursMandates($seance_id);
                $this->Gedooo->createFile($path, 'mandates.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('liste_mandates', $urlWebroot.'mandates.html', 'content');
	    
	        // Création de la liste des projets detailles
	        $listeProjetsDetailles = $this->requestAction("/models/listeProjets/$seance_id/1");
                $this->Gedooo->createFile($path, 'ProjetsDetailles.html',  $listeProjetsDetailles);
                $balises .= $this->Gedooo->CreerBalise('projets_detailles', $urlWebroot.'ProjetsDetailles.html', 'content');

	        // Création de la liste des projets sommaires
	        $listeProjetsSommaires = $this->requestAction("/models/listeProjets/$seance_id/0");
                $this->Gedooo->createFile($path, 'ProjetsSommaires.html',  $listeProjetsSommaires);
                $balises .= $this->Gedooo->CreerBalise('projets_sommaires', $urlWebroot.'ProjetsSommaires.html', 'content');
                // création du fichier XML
                $datas    = $this->Gedooo->createFile($path,'data.xml', $balises);

		// Envoi du fichier à GEDOOo
                if ($editable == 0)
                    $extension = 'pdf';
                elseif ($editable == 1)
                    $extension = 'odt';
		elseif ($editable == 2)
		    $extension = 'html';
		$this->Gedooo->sendFiles($model, $datas, $editable);
            $this->render();
        }

	function detailsAvis ($seance_id=null) {
		// initialisations
		$deliberations=$this->afficherProjets($seance_id, 0);
		$date_tmpstp = strtotime($this->GetDate($seance_id));
		$toutesVisees = true;

		for ($i=0; $i<count($deliberations); $i++){
                    $id_service = $deliberations[$i]['Service']['id'];
		    $deliberations[$i]['Service']['libelle'] = $this->requestAction("services/doList/$id_service");
		    $deliberations[$i]['Model']['id'] = $this->requestAction("deliberations/getModelId/". $deliberations[$i]['Deliberation']['id']);
		    if (empty($deliberations[$i]['Deliberation']['avis']))
		        $toutesVisees = false;
		}

		$this->set('USE_GEDOOO', USE_GEDOOO);
		$this->set('deliberations',$deliberations);
		$this->set('date_seance', $this->Date->frenchDateConvocation($date_tmpstp));
		$this->set('seance_id', $seance_id);
		$this->set('canClose', (($date_tmpstp <= strtotime(date('Y-m-d H:i:s'))) && $toutesVisees));
	}

	function donnerAvis ($deliberation_id=null) {
		// Initialisations
		$sortie = false;
		$deliberation = $this->Deliberation->read(null, $deliberation_id);
		$seanceIdCourante = $deliberation['Seance']['id'];

		if (!empty($this->data)) {
			// En fonction de l'avis s�lectionn�
			if (!array_key_exists('avis', $this->data['Deliberation'])) {
				$this->Seance->invalidate('avis');
			} elseif ($this->data['Deliberation']['avis'] == 2) {
				// D�favorable : le projet repasse en �tat = 0
				$this->data['Deliberation']['etat'] = 0;
				unset($this->data['Deliberation']['seance_id']);
				$this->Deliberation->save($this->data);
				// ajout du commentaire
				$this->data['Commentaire']['delib_id'] = $this->data['Deliberation']['id'];
				$this->data['Commentaire']['texte'] = 'A re�u un avis d�favorable en '
					. $this->Seance->Typeseance->field('Typeseance.libelle', 'Typeseance.id = '.$deliberation['Seance']['type_id'])
					. ' du ' . $this->Date->frenchDate(strtotime($deliberation['Seance']['date']));
				 $this->Deliberation->Commentaire->save($this->data);

				$sortie = true;
			} elseif ($this->data['Deliberation']['avis'] == 1) {
				// Favorable : on attribue une nouvelle date de s�ance si elle est s�lectionn�e
				if (empty($this->data['Deliberation']['seance_id'])) {
					unset($this->data['Deliberation']['seance_id']);
					// on calcule le num�ro de la d�lib�ration car il n'y a pas de s�ance suivante attribu�e
					if (empty($deliberation['Deliberation']['num_delib'])) {
						$compteurId = $this->Seance->Typeseance->field('compteur_id', 'Typeseance.id = '.$deliberation['Seance']['type_id']);
						$this->data['Deliberation']['num_delib'] = $this->Seance->Typeseance->Compteur->genereCompteur($compteurId);
					}
				} else
					$this->data['Deliberation']['position'] = $this->Deliberation->findCount("seance_id =".$this->data['Deliberation']['seance_id']." AND (etat != -1 )")+1;
				$this->Deliberation->save($this->data['Deliberation']);
				// ajout du commentaire
				$this->data['Commentaire']['delib_id'] = $this->data['Deliberation']['id'];
				$this->data['Commentaire']['texte'] = 'A re�u un avis favorable en '
					. $this->Seance->Typeseance->field('Typeseance.libelle', 'Typeseance.id = '.$deliberation['Seance']['type_id'])
					. ' du ' .$this->Date->frenchDate(strtotime($deliberation['Seance']['date']));
				$this->Deliberation->Commentaire->save($this->data);

				$sortie = true;
			}

			$this->data = $deliberation;
		}
		if ($sortie)
			$this->redirect('/seances/detailsAvis/'.$seanceIdCourante);
		else {
			$this->data = $deliberation;
			$this->set('avis', array(1 => 'Favorable', 2 => 'D�favorable'));
			$condition= 'date >= "'.date('Y-m-d H:i:s').'"';
			$this->set('seances', $this->Seance->generateList($condition,'date asc',null,'{n}.Seance.id','{n}.Seance.date'));
		}
	}

        function saisirSecretaire($seance_id) {
            $this->set('seance_id', $seance_id);
            $seance = $this->Seance->read(null, $seance_id);
            $acteursConvoques = $this->Seance->Typeseance->acteursConvoquesParTypeSeanceId($seance['Seance']['type_id']);
            foreach( $acteursConvoques as  $acteurConvoque)
	        $tab[$acteurConvoque['Acteur']['id']] =  $acteurConvoque['Acteur']['prenom'].' '. $acteurConvoque['Acteur']['nom'];
            $this->set('acteurs', $tab);

	    if (empty($this->data)) {
	        $this->set('selectedActeurs', $seance['Seance']['secretaire_id']);
            }
	    else {
		$seance['Seance']['secretaire_id'] = $this->data['Acteur']['Acteur'];
		if ($this->Seance->save($seance))
		    $this->redirect('/seances/listerFuturesSeances');
	    }
        }

        function getListActeurs($seance_id, $choixListe=1) {
	    $presents = array();
	    $absents  = array();
	    $mandats = array();
	    $mouvements = array();
	    $tab = array();

	    $delibs = $this->Deliberation->findAll("Deliberation.seance_id = $seance_id");
	    $nb_delib = count($delibs);
	    foreach ($delibs as $delib) 
		array_push($tab, $delib['Deliberation']['id']); 
	    
	    $conditions = "Listepresence.delib_id=";
            $conditions .= implode(" OR Listepresence.delib_id=", $tab);           
	    $presences = $this->Listepresence->findAll($conditions, null, 'Acteur.position');
	    foreach( $presences as  $presence) {
	       $acteur_id = $presence['Listepresence']['acteur_id'];
	       $tot_presents = $this->Listepresence->findAll("Listepresence.acteur_id =  $acteur_id AND ($conditions) AND Listepresence.present=1");
	       $nb_presence = count($tot_presents);
	       if ($nb_presence == $nb_delib) 
	           array_push($presents, $acteur_id);
	       elseif ( $nb_presence == 0) {
		   $tmp = $this->Listepresence->findAll("Listepresence.acteur_id =  $acteur_id AND ($conditions) AND Listepresence.present=0 AND Listepresence.mandataire=0");
                   $nb_absence=count($tmp);
                   if ( $nb_absence ==  $nb_delib) 
	               array_push($absents, $acteur_id);
                   else {
		        $tmp2 = $this->Listepresence->findAll("Listepresence.acteur_id =  $acteur_id AND ($conditions) AND Listepresence.present=0 AND Listepresence.mandataire!=0");
	               foreach($tmp2 as $mandat) {
                           if (!isset($mandat['Listepresence']['acteur_id']))
			       $mandat['Listepresence']['acteur_id'] = array();
			    $mandats[$mandat['Listepresence']['acteur_id']] = $mandat['Listepresence']['mandataire'];
		       }
	           }
	       }
               else { 
	           foreach ($tot_presents as $pres) {
	               if (!isset($mouvements[$acteur_id]))
		           $mouvements[$acteur_id] = array();
	               $mouvements[$acteur_id] =  $pres['Listepresence']['delib_id'];
		   }
	       }
	   }
	   
	   if ($choixListe ==1 )
	       return(array_unique($presents));
           elseif ($choixListe ==2 )
	       return(array_unique($absents));
           elseif ($choixListe ==3 )
	       return(array_unique($mandats));
           elseif ($choixListe ==4 )
	       return(array_unique($mouvements));
	} 

       	function listeActeursPresents($seance_id) {
	    // Lecture du modele
	    $texte = $this->Model->field('content', 'id=8');
	    $listeActeurs = "";
            $acteurs = $this->getListActeurs($seance_id, 1);
            foreach($acteurs as $key => $acteur_id) {
                $acteur = $this->Acteur->findById($acteur_id );
	        $searchReplace = array(
		    "#NOUVELLE_PAGE#" => "<newpage>",
		    "#NOM_PRESENT#" => $acteur['Acteur']['nom'],
		    "#PRENOM_PRESENT#" => $acteur['Acteur']['prenom'],
		    "#SALUTATION_PRESENT#" => $acteur['Acteur']['salutation'],
		    "#TITRE_PRESENT#" => $acteur['Acteur']['titre'],
		    "#ADRESSE1_PRESENT#" => $acteur['Acteur']['adresse1'],
		    "#ADRESSE2_PRESENT#" => $acteur['Acteur']['adresse2'],
		    "#CP_PRESENT#" => $acteur['Acteur']['cp'],
		    "#VILLE_PRESENT#" => $acteur['Acteur']['ville']
		 );
		$listeActeurs .= str_replace(array_keys($searchReplace), array_values($searchReplace), $texte);
            }
	    return($listeActeurs);
	}
 
        function listeActeursAbsents($seance_id) {
	     // Lecture du modele
	    $texte = $this->Model->field('content', 'id=9');
	    $listeActeurs = "";
            $acteurs = $this->getListActeurs($seance_id, 2);
            foreach($acteurs as $id =>$acteur_id ) {
                $acteur = $this->Acteur->findById($acteur_id);
	        $searchReplace = array(
		    "#NOUVELLE_PAGE#" => "<newpage>",
		    "#NOM_ABSENT#" => $acteur['Acteur']['nom'],
		    "#PRENOM_ABSENT#" => $acteur['Acteur']['prenom'],
		    "#SALUTATION_ABSENT#" => $acteur['Acteur']['salutation'],
		    "#TITRE_ABSENT#" => $acteur['Acteur']['titre'],
		    "#ADRESSE1_ABSENT#" => $acteur['Acteur']['adresse1'],
		    "#ADRESSE2_ABSENT#" => $acteur['Acteur']['adresse2'],
		    "#CP_ABSENT#" => $acteur['Acteur']['cp'],
		    "#VILLE_ABSENT#" => $acteur['Acteur']['ville']
		 );
		 $listeActeurs .= str_replace(array_keys($searchReplace), array_values($searchReplace), $texte);
            }
	    return( $listeActeurs); 
	}

        function listeActeursMandates($seance_id) {
            // Lecture du modele
            $texte = $this->Model->field('content', 'id=10');
            $listeActeurs = "";
            $acteurs = $this->getListActeurs($seance_id, 3);
            foreach($acteurs as $mandate_id => $mandataire_id) {
                $mandataire = $this->Acteur->findById($mandataire_id);
                $mandate = $this->Acteur->findById($mandate_id);
                $searchReplace = array(
                    "#NOUVELLE_PAGE#" => "<newpage>",
                    "#NOM_MANDATE#" => $mandate['Acteur']['nom'],
                    "#PRENOM_MANDATE#" => $mandate['Acteur']['prenom'],
                    "#SALUTATION_MANDATE#" => $mandate['Acteur']['salutation'],
                    "#TITRE_MANDATE#" => $mandate['Acteur']['titre'],
                    "#NOM_MANDATAIRE#" => $mandataire['Acteur']['nom'],
                    "#PRENOM_MANDATAIRE#" => $mandataire['Acteur']['prenom'],
                    "#SALUTATION_MANDATAIRE#" => $mandataire['Acteur']['salutation'],
                    "#TITRE_MANDATAIRE#" => $mandataire['Acteur']['titre'],
                    "#ADRESSE1_MANDATAIRE#" => $mandataire['Acteur']['adresse1'],
                    "#ADRESSE2_MANDATAIRE#" => $mandataire['Acteur']['adresse2'],
                    "#CP_MANDATAIRE#" => $mandataire['Acteur']['cp'],
                    "#VILLE_MANDATAIRE#" => $mandataire['Acteur']['ville']
                );
                $listeActeurs .= str_replace(array_keys($searchReplace), array_values($searchReplace), $texte);
            }
            return($listeActeurs);
        }

        function listeActeursMouvements($seance_id) {
            // Lecture du modele
            $texte = $this->Model->field('content', 'id=7');
            $listeActeurs = "";
            $acteurs = $this->getListActeurs($seance_id, 4);
            foreach($acteurs as $acteur_id => $delib_id) {
                $mandate = $this->Acteur->findById($acteur_id);
		$delib = $this->Deliberation->findById($delib_id);
                $searchReplace = array(
                    "#NOUVELLE_PAGE#" => "<newpage>",
                    "#NOM_ACTEUR#" => $mandate['Acteur']['nom'],
                    "#PRENOM_ACTEUR#" => $mandate['Acteur']['prenom'],
                    "#SALUTATION_ACTEUR#" => $mandate['Acteur']['salutation'],
                    "#TITRE_ACTEUR#" => $mandate['Acteur']['titre'],
                    "#ADRESSE1_ACTEUR#" => $mandate['Acteur']['adresse1'],
                    "#ADRESSE2_ACTEUR#" => $mandate['Acteur']['adresse2'],
                    "#CP_ACTEUR#" => $mandate['Acteur']['cp'],
                    "#VILLE_ACTEUR#" => $mandate['Acteur']['ville'],
                    "#IDENTIFIANT_DELIB#" => $delib['Deliberation']['id'],
                    "#TITRE_DELIB#" => $delib['Deliberation']['titre'],
                    "#OBJET_DELIB#" => $delib['Deliberation']['objet'],
                    "#NUMERO_DELIB#" => $delib['Deliberation']['num_delib']
                );
                $listeActeurs .= str_replace(array_keys($searchReplace), array_values($searchReplace), $texte);
            }
            return($listeActeurs);
        }

        function download($id=null, $file){
            header('Content-type: '.$this->getFileType($id, $file));
            header('Content-Length: '.$this->getSize($id, $file));
            header('Content-Disposition: attachment; filename='.$this->getFileName($id, $file));
            echo $this->getData($id, $file);
            exit();
        }


        function getFileType($id=null, $file) {
            $objCourant = $this->Seance->read(null, $id);
            return $objCourant['Seance'][$file."_type"];
        }

        function getFileName($id=null, $file) {
            $objCourant = $this->Seance->read(null, $id);
            return $objCourant['Seance'][$file."_name"];
        }

        function getSize($id=null, $file) {
             $objCourant = $this->Seance->read(null, $id);
            return $objCourant['Seance'][$file."_size"];
        }

        function getData($id=null, $file) {
            $objCourant = $this->Seance->read(null, $id);
            return $objCourant['Seance'][$file];
        }

}
?>