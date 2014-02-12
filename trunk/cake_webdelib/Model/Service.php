<?php
class Service extends AppModel
{
    public $name = 'Service';
    public $displayField = "libelle";
    public $actsAs = array('Tree');
    public $validate = array(
        'libelle' => array(
            array(
                'rule' => 'notEmpty',
                'message' => 'Entrer le libellé.'
            )
        )
    );
    public $hasAndBelongsToMany = array(
        'User' => array(
            'classname' => 'User',
            'joinTable' => 'users_services',
            'foreignKey' => 'service_id',
            'associationForeignKey' => 'user_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'unique' => true,
            'finderQuery' => '',
            'deleteQuery' => ''),
        'Acteur' => array(
            'classname' => 'Acteur',
            'joinTable' => 'acteurs_services',
            'foreignKey' => 'service_id',
            'associationForeignKey' => 'acteur_id',
            'conditions' => '',
            'order' => '',
            'limit' => '',
            'unique' => true,
            'finderQuery' => '',
            'deleteQuery' => '')
    );

    /* retourne le libelle du service $id et de ses parents sous la forme parent1/parent12/service_id */
    function doList($id)
    {
        return $this->_doList($id);
    }

    /* fonction récursive de doList */
    function _doList($id)
    {
        $service = $this->find('first', array(
            'conditions' => array('Service.id' => $id),
            'fields' => array('libelle', 'parent_id'),
            'recursive' => -1));
        if (empty($service))
            return "Impossible de récupérer le service";
        if (!Configure::read('AFFICHE_HIERARCHIE_SERVICE'))
            return $service['Service']['libelle'];

        if (empty($service['Service']['parent_id']))
            return $service['Service']['libelle'];
        else
            return $this->_doList($service['Service']['parent_id']) . '/' . $service['Service']['libelle'];
    }

    function makeBalise(&$oMainPart, $service_id)
    {
        $service = $this->find('first', array(
            'conditions' => array('Service.id' => $service_id),
            'fields' => array('libelle'),
            'recursive' => -1));
        
        if(empty($service))return;
        $oMainPart->addElement(new GDO_FieldType('service_emetteur', $service['Service']['libelle'], 'text'));
        $oMainPart->addElement(new GDO_FieldType('service_avec_hierarchie', $this->_doList($service_id), 'text'));
    }

    /**
     * doListId Retourne toute la liste de services disponibles par rapport à un service donné
     * @param int $id
     * @return array Liste Id de tous les services disponibles
     */
    function doListId($id)
    {
        return $this->_doListId($id);
    }

    /**
     * _doListId Retourne la liste de services disponibles par rapport à un service donné. Fonction privée recursive
     * @param int $id
     * @return array Liste Id de tous les services disponibles
     */
    function _doListId($id)
    {
        $aArray = array();
        $service = $this->find('all', array(
            'conditions' => array('Service.parent_id' => $id),
            'fields' => array('id', 'parent_id'),
            'recursive' => -1));

        if (!empty($service)) {
            $aArray[] = $id;
            foreach ($service as $champs) {
                $aServices = $this->_doListId($champs['Service']['id']);
                if (!empty($aServices))
                    foreach ($aServices as $aService) {
                        $aArray[] = $aService;
                    }
            }
        } else
            $aArray[] = $id;
        return $aArray;
    }

    /**
     * fonction d'initialisation des variables de fusion pour le service émetteur d'un projet
     * les bibliothèques Gedooo doivent être inclues par avance
     * génère une exception en cas d'erreur
     * @param object_by_ref $oMainPart variable Gedooo de type maintPart du document à fusionner
     * @param integer $id id du modèle lié
     * @param objet_by_ref $modelOdtInfos objet PhpOdtApi du fichier odt du modèle d'édition
     */
    function setVariablesFusion(&$oMainPart, $id, &$modelOdtInfos) {
        if ($modelOdtInfos->hasUserField('service_emetteur'))
            $oMainPart->addElement(new GDO_FieldType('service_emetteur', $this->field('libelle', array('id'=>$id)), 'text'));
        if ($modelOdtInfos->hasUserField('service_avec_hierarchie'))
            $oMainPart->addElement(new GDO_FieldType('service_avec_hierarchie', $this->_doList($id), 'text'));
    }
}
?>
