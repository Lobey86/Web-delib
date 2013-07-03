<?php
/*
 * Gestion dynamique des droits des utilisateurs sur les actions (méthodes) des controleurs
 * Interface des tables acos, aros, aros_acos
 * cru : Collectivité, Rôle, Utilisateur
 * ca  : Controller-Action
 *
*/
class DbdroitsComponent extends Component
{
	var $components = array('Acl', 'Menu');

/*
 * Ajoute le controlleur-action $ca dans la table acos ayant comme parent $parent
 * $ca peut avoir les formes suivantes :
 *  - array : ('controller' => string,	'action' => string)
 *  - string : 'controller:action'
 * $caParent peut avoir les formes suivantes :
 *  - array : ('controller' => string,	'action' => string)
 * 	- string : 'controller:action'
 */
function addCa($ca, $caParent = 0) {
	// traitement du parent
	if (empty($caParent))
		$parent_id = 0;
	else {
		$parentAlias = is_array($caParent) ? $caParent['controller'].':'.$caParent['action'] : $caParent;
		$parent_id = $this->Acl->Aco->field('id', array('alias'=>$parentAlias));
	}

	// creation et sauvegarde
	$alias = is_array($ca) ? $ca['controller'].':'.$ca['action'] : $ca;
	$this->Acl->Aco->create();
	if ($this->Acl->Aco->save(array('alias' => $alias, 'parent_id' => $parent_id)))
		return true;
	else
		return false;
}

/*
 * Supprime le controlleur-action $ca la table acos
 * $ca peut avoir les formes suivantes :
 *  - array : ('controller' => string,	'action' => string)
 * 	- string : 'controller:action'
 */
function deleteCa($ca) {
	// lecture de l'id de l'acos a supprimer
	$alias = is_array($ca) ? $ca['controller'].':'.$ca['action'] : $ca;
	$acoId = $this->Acl->Aco->field('id', array('alias'=>$alias));

	// suppression de l'occurence et des descendants
	$this->Acl->Aco->delete($acoId, true);
}

/*
 * Ajoute la collectivité, le rôle ou l'utilisateur $cru à la table Aro ayant comme parent $cruParent
 * $cru : array('model'=> string, 'foreign_key' => integer, 'alias' => string)
 * $cruParent : array('model' => string, 'foreign_key' => integer)
 */
function addCru($cru, $cruParent=0) {
	// traitement du parent
	if (!empty($cruParent) && !empty($cruParent['foreign_key']))
		$cru['parent_id'] = $this->Acl->Aro->field('id', $cruParent);
	else
		$cru['parent_id'] = 0;

	// creation et sauvegarde
	$this->Acl->Aro->create();
	if ($this->Acl->Aro->save($cru))
		return true;
	else
		return false;
}

/*
 * Supprime la collectivité, role, utilisateur $cru de la table aros
 * $cru : array('model' => string,	'foreign_key' => integer)
 */
function deleteCru($cru) {
	// initialisations
	$aroId = $this->Acl->Aro->field('id', $cru);

	// suppression de l'occurence et des descendants
	$this->Acl->Aro->delete($aroId, true);
}

/*
 * Mise à jour de la collectivité, du rôle ou de l'utilisateur $cru de la table aros
 * La mise à jour porte sur l'alias et le parent
 * $cru : array('model'=> string, 'foreign_key' => integer, 'alias' => string)
 * $cruParent : array('model' => string, 'foreign_key' => integer)
 */
function majCru($cru, $cruParent=0) {
	// lecture en base de l'occurence
	$aro = $this->findCru($cru);

	// Mise à jour de l'occurence
	if (!empty($aro)) {
		$aro['Aro']['alias'] = $cru['alias'];
		if (!empty($cruParent) && !empty($cruParent['foreign_key']))
			$aro['Aro']['parent_id'] = $this->Acl->Aro->field('id', $cruParent);
		else
			$aro['Aro']['parent_id'] = 0;

		$this->Acl->Aro->save($aro);
	}
}

/*
 * Retourne l'élément de la table aros correspondant à $cru
 * $cru peut avoir les formes suivantes :
 *  - array : ('model' => string, 'foreign_key' => integer)
 *  - integer : id
 */
function findCru($cru) {
	if (empty($cru)) return false;

	// initialisations
	if (is_array($cru))
		$conditions = array('model'=>$cru['model'], 'foreign_key'=>$cru['foreign_key']);
	else
		$conditions = array('id' => $cru);

	// lecture de l'occurence en base
	return $this->Acl->Aro->find('first', array(
		'conditions' => $conditions,
		'recursive' => -1));
}


/*
 * Mise à jour de la table acos avec les entrées du menu principal et les actions des controlleurs
 */
function majActions() {
	// initialisation
	$acosEnBase = array();
	$acosMaj = array();

	// Liste des aco en base
	$acos = $this->Acl->Aco->find('all', array('fields'=>array('id', 'alias', 'parent_id')));
	foreach ($acos as $aco) {
		// Recherche de l'alias du parent
		if (!empty($aco['Aco']['parent_id']))
			$aco['Aco']['parent_alias'] = $this->Acl->Aco->field('alias', array('id'=>$aco['Aco']['parent_id']));
		else
			$aco['Aco']['parent_alias'] = '';
		$acosEnBase[]=$aco['Aco'];
	}
	
	// Liste des alias 'controller:action' du menu principal et des controlleurs
	$acosMaj = $this->Menu->listeAliasMenuControlleur();

	// Suppression des acos en base qui ne sont pas dans les acos de la mise à jour
	$acosASupprimer = array();
	foreach($acosEnBase as $i=>$acoEnBase) {
		$trouve = false;
		foreach ($acosMaj as $acoMaj) {
			if (($acoEnBase['alias'] === $acoMaj['alias']) && ($acoEnBase['parent_alias'] === $acoMaj['parent_alias'])) {
				$trouve = true;
				break;
			}
		}
		if (!$trouve) $acosASupprimer[] = array('id'=>$acoEnBase['id'], 'iTab'=>$i);
	}
	foreach($acosASupprimer as $acoASupprimer)	{
			$this->Acl->Aco->delete($acoASupprimer['id'], true);
			unset($acosEnBase[$acoASupprimer['iTab']]);
	}

	// Ajout en base des controlleur-action si ils n'y sont pas déjà
	foreach($acosMaj as $acoMaj) {
		$trouve = false;
		foreach($acosEnBase as $acoEnBase)
			if (($acoEnBase['alias'] === $acoMaj['alias']) && ($acoEnBase['parent_alias'] === $acoMaj['parent_alias'])) {
				$trouve = true;
				break;
			}
		if (!$trouve) $this->addCa($acoMaj['alias'], $acoMaj['parent_alias']);
	}
}

/**
 * Retourne un tableau des droits sur les actions (table acos) pour la collectivité, rôle, utilisateur $cru
 *
 * @param $cru : array : ('model' => string, 'foreign_key' => integer)
 * @return array('acosAlias'=>1:0)
 */
function litCruDroits($cru=null) {
	// Initialisations
	$ret = array();

	// liste des Menu, Controlleur-Action (mca)
	$listeMca = $this->Menu->listeAliasMenuControlleur();
	foreach($listeMca as $mca)
            
            $ret[$mca['alias']] = $this->Acl->check($cru, $mca['alias']);
 
         }
	$this->log($ret);
	return $ret;
}

/*
 * Mise à jour des droits pour une collectivité, rôle, utilisateur sur les entrées du menu, controlleurs-action
 * mise à jour de la table acos avec les entrées du menu principal, et des controlleurs-actions
 * mise à jour de la table aros avec la collectivités-rôles-users $cru ayant comme parent $cruParent puis,
 * mise à jour de la table aros_acos avec le contenu de $tabDroits
 * suppression des droits des collectivités-rôles-utilisateurs enfants
 * $cru : array('model'=> string, 'foreign_key' => integer, 'alias' => string)
 * $cruParent : array('model' => string, 'foreign_key' => integer)
 * $tabDroits : array([acoAlias]=>1:0)
 */
function majCruDroits($cru, $cruParent, $tabDroits) {
	/* mise à jour de la table acos */
	$this->majActions();

	/* mise à jour de la table aros */
	if ($this->findCru($cru))
		$this->majCru($cru, $cruParent);
	else
		$this->addCru($cru, $cruParent);

	/* mise à jour de la table aros_acos */
	foreach($tabDroits as $acoAlias => $droit) {
		if ($droit)
			$this->Acl->allow($cru, $acoAlias);
		else
			$this->Acl->deny($cru, $acoAlias);
	}
}

/**
 * Restreint les droits des 'enfants' de la collectivités-rôles-utilisateurs $cru à ses propres droits
 * @param $cru : array('model'=> string, 'foreign_key' => integer)
 */
function restreintCruEnfantsDroits($cru, $tabDroits) {
	/* lectures des enfants de $cru dans aros */
	$aroCru = $this->findCru($cru);
	$aroEnfants = $this->Acl->Aro->children($aroCru['Aro']['id']);
	foreach($aroEnfants as $aroEnfant) {
		/* on resteint les droits si ils sont spécifiques à l'enfant (sinon héritage)*/
		$droitsSpecif = $this->Acl->Aro->query('select count(*) from aros_acos where aro_id = '.$aroEnfant['Aro']['id']);
		if (array_key_exists('count(*)', $droitsSpecif[0][0]))
			$nbDroitsSpecif = $droitsSpecif[0][0]['count(*)'];
		else
			$nbDroitsSpecif = $droitsSpecif[0][0]['count'];
		
		if ($nbDroitsSpecif>0) {
			$cruEnfant = array('model'=>$aroEnfant['Aro']['model'], 'foreign_key'=>$aroEnfant['Aro']['foreign_key']);
			foreach($tabDroits as $acoAlias=>$droit)
				if (!$droit)
					$this->Acl->deny($cruEnfant, $acoAlias);
		}
	}
}

/*
 * Suppression des droits en base (aros_acos) pour une collectivité, rôle, utilisateur sur les entrées du menu, controlleurs-action
 * suppression des occurences de la table aros_acos
 * $cru : array('model'=> string, 'foreign_key' => integer)
 */
function deleteCruDroits($cru) {
	$aroId = $this->Acl->Aro->field('id', $cru);
	if ($aroId) {
		$this->Acl->Aro->Permission->deleteAll(array('Permission.aro_id' => $aroId), false);
	}
}

}?>