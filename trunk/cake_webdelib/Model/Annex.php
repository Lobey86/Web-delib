<?php

class Annex extends AppModel {

    var $name = 'Annex';
    var $displayField = 'titre';
    var $belongsTo = array(
        'Deliberation' => array(
            'foreignKey' => 'foreign_key'
        )
    );
    var $validate = array(
        'joindre_ctrl_legalite' => array(
            'rule' => 'checkFileControlLegalite',
            'message' => 'Le format de fichier est invalide pour joindre au contrôle de légalité'),
        'joindre_fusion' => array(
            'rule' => 'checkFileFusion',
            'message' => 'Le format de fichier est invalide pour le joindre à la fusion'),
        'filename' => array(
            'rule' => array('maxLength', 100),
            'message' => 'Le nom du fichier est trop long (100 caract&egrave;res maximum)', 'growl'),
        'titre' => array(
            'rule' => array('maxLength', 200),
            'message' => 'Le titre du fichier est trop long (200 caract&egrave;res maximum)', 'growl')
    );

    function checkFileControlLegalite() {
        if ($this->data['Annex']['joindre_ctrl_legalite'] == 1) {
            $DOC_TYPE = Configure::read('DOC_TYPE');
            if (!empty($this->data['Annex']['filename'])) {
                $mime = $this->data['Annex']['filetype'];
            } else {
                $annex = $this->find('first', array(
                    'conditions' => array('Annex.id' => $this->data['Annex']['id']),
                    'recursive' => -1,
                    'fields' => array('Annex.filetype')));
                $mime = $annex['Annex']['filetype'];
            }
            return !empty($DOC_TYPE[$mime]['joindre_ctrl_legalite']);
        }
        return true;
    }

    function checkFileFusion() {
        if ($this->data['Annex']['joindre_fusion'] == 1) {
            $DOC_TYPE = Configure::read('DOC_TYPE');
            if (!empty($this->data['Annex']['filename'])) {
                $mime = $this->data['Annex']['filetype'];
            } else {
                $annex = $this->find('first', array('conditions' => array('Annex.id' => $this->data['Annex']['id']),
                    'recursive' => -1,
                    'fields' => array('Annex.filetype')));
                $mime = $annex['Annex']['filetype'];
            }
            return !empty($DOC_TYPE[$mime]['joindre_ctrl_legalite']);
        }
        return true;
    }

    function getAnnexesFromDelibId($delib_id, $to_send = 0, $to_merge = 0, $joindreParent = false) {
        $conditions['Annex.foreign_key'] = $delib_id;
        //$conditions['Annex.model'] = 'Deliberation';
        if ($to_send == 1)
            $conditions['Annex.joindre_ctrl_legalite'] = true;
        if ($to_merge == 1)
            $conditions['Annex.joindre_fusion'] = true;

        $annexes = $this->find('all', array('conditions' => $conditions,
            'recursive' => -1,
            'order' => array('Annex.id' => 'ASC'),
            'fields' => array('id', 'model')));
        
        if ($joindreParent){
            $delib = $this->Deliberation->find('first', array('conditions' => array('Deliberation.id' => $delib_id),
                'recursive' => -1,
                'fields' => array('id', 'parent_id')));
            if (isset($delib['Deliberation']['parent_id'])) {
                $tab = $this->getAnnexesFromDelibId($delib['Deliberation']['parent_id']);
                if (isset($tab) && !empty($tab)) {
                    for ($i = 0; $i < count($tab); $i++) {
                        if ($tab[$i]['Annex']['model'] == 'Deliberation')
                            unset($tab[$i]);
                    }
                    $annexes = array_merge($annexes, $tab);
                }
            }
        }
        return $annexes;
    }

    function getAnnexesToSend($delib_id) {
        $conditions = array('foreign_key' => $delib_id);
        $conditions['joindre_ctrl_legalite'] = 1;
        return $this->find('all', array(
                    'conditions' => $conditions,
                    'fields' => array('filename', 'filetype', 'data'
        )));
    }

    function getContentToTdT($annex_id)
    {
        $annex = $this->find('first', array(
            'conditions' => array('Annex.id' => $annex_id),
            'recursive' => -1,
            'fields' => array('data_pdf')
        ));

        return array(
            'type' => 'pdf',
            'data' => $annex['Annex']['data_pdf']
        );
    }

}
