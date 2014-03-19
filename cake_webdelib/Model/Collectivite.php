<?php

App::uses('File', 'Utility');

class Collectivite extends AppModel {

    var $name = 'Collectivite';
    var $cacheSources = 'false';
    var $validate = array(
        'nom' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer le nom de la collectivité'
            )
        ),
        'adresse' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer l\'adresse.'
            )
        ),
        'CP' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer le code postal.'
            )
        ),
        'ville' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer la ville.'
            )
        ),
        'telephone' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer le numéro de téléphone.'
            )
        ),
        'logo' => array(
            array(
                'rule' => array('extension', array('jpeg', 'jpg')),
                'message' => 'Merci de soumettre une image valide.'
            )
        )
    );

    function makeBalise(&$oMainPart, $collectivite_id) {
        $collectivite = $this->find('first', array('conditions' => array($this->alias . '.id' => $collectivite_id),
            'recursive' => -1));

        $oMainPart->addElement(new GDO_FieldType('nom_collectivite', $collectivite['Collectivite']['nom'], "text"));
        $oMainPart->addElement(new GDO_FieldType('adresse_collectivite', $collectivite['Collectivite']['adresse'], "text"));
        $oMainPart->addElement(new GDO_FieldType('cp_collectivite', $collectivite['Collectivite']['CP'], "text"));
        $oMainPart->addElement(new GDO_FieldType('ville_collectivite', $collectivite['Collectivite']['ville'], "text"));
        $oMainPart->addElement(new GDO_FieldType('telephone_collectivite', $collectivite['Collectivite']['telephone'], "text"));
    }

    /**
     * fonction d'initialisation des variables de fusion pour la collectivité
     * les bibliothèques Gedooo doivent être inclues par avance
     * génère une exception en cas d'erreur
     * @param object_by_ref $oMainPart variable Gedooo de type maintPart du document à fusionner
     * @param object_by_ref $modelOdtInfos objet PhpOdtApi du fichier odt du modèle d'édition
     * @param int $id id des données à fusionner
     * @throws Exception
     */
    function setVariablesFusion(&$oMainPart, &$modelOdtInfos, $id) {
        // lecture de la collectivité en  base de données
        $collectivite = $this->find('first', array(
            'recursive'  => -1,
            'fields' => array('id', 'nom', 'adresse', 'CP', 'ville', 'telephone'),
            'conditions' => array('id' => $id)));
        if (empty($collectivite))
            throw new Exception('collectivité id:'.$id.' non trouvé en base de données');

        // initialisation des variables
        if ($modelOdtInfos->hasUserField('nom_collectivite'))
            $oMainPart->addElement(new GDO_FieldType('nom_collectivite', $collectivite['Collectivite']['nom'], "text"));
        if ($modelOdtInfos->hasUserField('adresse_collectivite'))
            $oMainPart->addElement(new GDO_FieldType('adresse_collectivite', $collectivite['Collectivite']['adresse'], "text"));
        if ($modelOdtInfos->hasUserField('cp_collectivite'))
            $oMainPart->addElement(new GDO_FieldType('cp_collectivite', $collectivite['Collectivite']['CP'], "text"));
        if ($modelOdtInfos->hasUserField('ville_collectivite'))
            $oMainPart->addElement(new GDO_FieldType('ville_collectivite', $collectivite['Collectivite']['ville'], "text"));
        if ($modelOdtInfos->hasUserField('telephone_collectivite'))
            $oMainPart->addElement(new GDO_FieldType('telephone_collectivite', $collectivite['Collectivite']['telephone'], "text"));
    }


}
