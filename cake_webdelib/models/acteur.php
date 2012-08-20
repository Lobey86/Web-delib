<?php
/**
* Gestion des s�quences utilis�es par les compteurs param�trables
*
* PHP versions 4 and 5
* @filesource
* @copyright
* @link			http://www.adullact.org
* @package			web-delib
* @subpackage
* @since
* @version			1.0
* @modifiedby
* @lastmodified	$Date: 2007-10-14
* @license
*/

class Acteur extends AppModel
{
	var $name = 'Acteur';

	var $displayField = "nom";
	
	var $validate = array(
		'nom' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Entrer un nom pour l\'acteur'
			)
		),
		'prenom' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Entrer un pr�nom pour l\'acteur'
			)
		),
		'email' => array(
			array(
				'rule' => 'email',
				'allowEmpty' => true,
				'message' => 'Adresse email non valide.'
			)
		),
		'service' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'S�lectionnez un ou plusieurs services'
			)
		),
		'typeacteur_id' => array(
			array(
				'rule' => 'notEmpty',
				'message' => 'Selectionner un type d\'acteur'
			)
		)
	);

	var $belongsTo = array('Suppleant' => array( 'className' => 'Acteur', 'foreignKey' => 'suppleant_id'),
                               'Typeacteur' => array( 'className' => 'Typeacteur', 'foreignKey' => 'typeacteur_id'));


	var $hasAndBelongsToMany = array(
		'Service' => array(
			'classname'=>'Service',
			'joinTable'=>'acteurs_services',
			'foreignKey'=>'acteur_id',
			'associationForeignKey'=>'service_id',
			'conditions'=>'',
			'order'=>'',
			'limit'=>'',
			'unique'=>true,
			'finderQuery'=>'',
			'deleteQuery'=>'')
		);

	/* retourne la liste des acteurs �lus [id]=>[prenom et nom] pour utilisation html->selectTag */
	function generateListElus($order_by=null) {
		$generateListElus = array();
		if ($order_by==null)
			$acteurs = $this->find('all', array('conditions' => array('Typeacteur.elu'=> 1, 'Acteur.actif' => 1), 
                                                             'fields'    => array('id', 'nom', 'prenom'),
                                                             'order'     => 'Acteur.position ASC'));
		else
			$acteurs = $this->find('all', array('conditions' => array('Typeacteur.elu'=> 1,  'Acteur.actif' => 1), 
                                                             'fields'    => array('id', 'nom', 'prenom'),
                                                             'order'     => "$order_by ASC"));
		foreach($acteurs as $acteur) {
				$generateListElus[$acteur['Acteur']['id']] = $acteur['Acteur']['prenom'].' '.$acteur['Acteur']['nom'];
		}
		return $generateListElus;
	}

	/* retourne la liste des acteurs [id]=>[prenom et nom] pour utilisation html->selectTag */
	function generateList($order_by=null) {
		$generateList = array();
                if ($order_by==null)
                        $acteurs = $this->find('all', array('conditions' => array('Acteur.actif' => 1),
                                                             'fields'    => array('id', 'nom', 'prenom'),
                                                             'order'     => 'Acteur.position ASC'));
                else    
                        $acteurs = $this->find('all', array('conditions' => array('Acteur.actif' => 1), 
                                                             'fields'    => array('id', 'nom', 'prenom'),
                                                             'order'     => "$order_by ASC"));


		foreach($acteurs as $acteur) {
			$generateList[$acteur['Acteur']['id']] = $acteur['Acteur']['prenom'].' '.$acteur['Acteur']['nom'];
		}

		return $generateList;
	}

	/* retourne l'id du premier acteur �lu associ� � la d�l�gation $serviceId */
	/* retourne null si non trouv�                                            */
	function selectActeurEluIdParDelegationId($delegationId) {
		$users = $this->find('all', array('conditions' => array('Typeacteur.elu'=>1, 'Acteur.actif'=>1 ),
                                                  'fields'     => array ('id'),
                                                  'order' => 'position ASC'));
             
		foreach($users as $user) {
			foreach($user['Service'] as $service) {
				if ($service['id'] == $delegationId) return $user['Acteur']['id'];
			}
		}
		return null;
	}


	/* retourne le num�ro de position max pour tous les acteurs �lus */
	/* pour rester compatible avec le plus grand nombre de bd, on ne passe pas de requ�te */
	/* mais on fait le calcul en php */
	function getPostionMaxParActeursElus() {
		$acteur = $this->find('all', array ('conditions'=> array('Typeacteur.elu'=>1, 'Acteur.actif'=>1), 
                                                    'fields'    => array('position'),
                                                    'order'     => 'position DESC'));
		return empty($acteur) ? 0 : $acteur[0]['Acteur']['position'];
	}

	/* retourne le libell� correspondant au champ position : = 999 : en dernier, <999 : position */
	function libelleOrdre($ordre = null, $majuscule = false) {
		return ($ordre == 999) ? ($majuscule ? 'En dernier' : 'en dernier') : $ordre;
	}
  
        function makeBalise(&$oMainPart, $acteur_id) {
            $acteur = $this->find('first', 
                                  array('conditions' => array($this->alias.'.id' => $acteur_id),
                                        'recursive'  => -1));
            $alias = trim(strtolower($this->alias));
            $oMainPart->addElement(new GDO_FieldType("salutation_$alias",     utf8_encode($acteur[$this->alias]['salutation']), 'text'));
            $oMainPart->addElement(new GDO_FieldType("prenom_$alias",         utf8_encode($acteur[$this->alias]['prenom']),     'text'));
            $oMainPart->addElement(new GDO_FieldType("nom_$alias",            utf8_encode($acteur[$this->alias]['nom']),        'text'));
            $oMainPart->addElement(new GDO_FieldType("titre_$alias",          utf8_encode($acteur[$this->alias]['titre']),      'text'));
            $oMainPart->addElement(new GDO_FieldType("position_$alias",       utf8_encode($acteur[$this->alias]['position']),   'text'));
            $oMainPart->addElement(new GDO_FieldType("email_$alias",          utf8_encode($acteur[$this->alias]['email']),      'text'));
            $oMainPart->addElement(new GDO_FieldType("telmobile_$alias",      utf8_encode($acteur[$this->alias]['telmobile']),  'text'));
            $oMainPart->addElement(new GDO_FieldType("telfixe_$alias",        utf8_encode($acteur[$this->alias]['telfixe']),    'text'));
            $oMainPart->addElement(new GDO_FieldType("date_naissance_$alias", utf8_encode($acteur[$this->alias]['date_naissance']), 'text'));
            $oMainPart->addElement(new GDO_FieldType("adresse1_$alias",       utf8_encode($acteur[$this->alias]['adresse1']),   'text'));
            $oMainPart->addElement(new GDO_FieldType("adresse2_$alias",       utf8_encode($acteur[$this->alias]['adresse2']),   'text'));
            $oMainPart->addElement(new GDO_FieldType("cp_$alias",             utf8_encode($acteur[$this->alias]['cp']),         'text'));
            $oMainPart->addElement(new GDO_FieldType("ville_$alias",          utf8_encode($acteur[$this->alias]['ville']),      'text'));
            $oMainPart->addElement(new GDO_FieldType("note_$alias",           utf8_encode($acteur[$this->alias]['note']),       'text'));
        }
}
?>
