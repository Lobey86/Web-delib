<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2007, Cake Software Foundation, Inc.
 *                                1785 E. Sahara Avenue, Suite 490-204
 *                                Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright        Copyright 2005-2007, Cake Software Foundation, Inc.
 * @link                http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package            cake
 * @subpackage        cake.cake
 * @since            CakePHP(tm) v 0.2.9
 * @version            $Revision: 4409 $
 * @modifiedby        $LastChangedBy: phpnut $
 * @lastmodified    $Date: 2007-02-02 07:20:59 -0600 (Fri, 02 Feb 2007) $
 * @license            http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package        cake
 * @subpackage    cake.cake
 */
class AppModel extends Model
{
    //var $actsAs=array('Containable');

    /**
     * @deprecated au profit de la fonction checkFormat
     * @param $field_validation
     * @param $content
     * @param $allowed_mimetypes
     * @return bool
     */
    function checkMimetype($field_validation, $content, $allowed_mimetypes)
    {
        if (empty($this->data[$this->alias][$content]))
            return true;

        if ((!in_array($this->data[$this->alias][$content . '_type'], $allowed_mimetypes)))
            return false;

        App::uses('Fido', 'Lib');
        $DOC_TYPE = Configure::read('DOC_TYPE');
        $tmpfname = tempnam(TMP, "CHK_");
        $file = new File($tmpfname, true);
        $file->write($this->data[$this->alias][$content]);
        $details = Fido::analyzeFile($file->path);
        $file->delete();

        if (array_key_exists($details['mimetype'], $DOC_TYPE))
            return (in_array($details['mimetype'], $allowed_mimetypes));

        return false;
    }

    /**
     * Validation du format de fichier par FIDO
     */
    public function checkFormat($data, $extension, $required = false)
    {
        $data = array_shift($data);
        if(!$required && $data['error'] == 4){
            return true;
        }
        if($required && $data['error'] !== 0){
            return false;
        }
        if ($data['size'] == 0 || $data['error'] != 0) {
            $this->validate['content']['message'] = 'Erreur dans le document ou lors de l&apos;envoi.';
            return false;
        }
        App::import('Component','Fido');
        $this->Fido = new FidoComponent();
        $allowed = $this->Fido->checkFile($data['tmp_name']);

        return ($allowed && $this->Fido->lastResults['extension'] == $extension);
    }

    function listFields($params = array())
    {
        // Initialisation des clés manquantes de $params avec les valeurs de $this->$displayFields
        if (isset($this->displayFields))
            $params = array_merge($this->displayFields, $params);

        // Si la liste des champs ou le format ne sont pas définis on retourne la fonction find('list')
        if (empty($params['fields']) || empty($params['format']))
            return $this->find('list', $params);

        // Ajout de la clé primaire dans la liste des champs si elle n'y est pas déjà
        $clePrimaireAjoutee = false;
        if (!in_array($this->primaryKey, $params['fields'])) {
            $params['fields'][] = $this->primaryKey;
            $clePrimaireAjoutee = true;
        }

        // On force la récursivite à -1
        $params['recursive'] = -1;

        // Execution du find
        $recs = $this->find('all', $params);

        // Constitution de la liste de retour
        $ret = array();
        foreach ($recs as $rec) {
            $id = $rec[$this->alias][$this->primaryKey];
            if ($clePrimaireAjoutee) unset($rec[$this->alias][$this->primaryKey]);
            $ret[$id] = vsprintf($params['format'], $rec[$this->alias]);
        }

        return $ret;
    }

    function changeBoolean($model, $id, $field)
    {
        $mod = new $model;
        $data = $mod->find('first', array('conditions' => array("$model.id" => $id),
            'recursive' => -1,
            'fields' => array("$field")));
        $mod->id = $id;
        return ($mod->saveField($field, !$data[$model][$field]));
    }

    /*function isUnique($field, $value, $id)
        {
            $fields[$this->name.'.'.$field] = $value;
            if (empty($id))
                // add
                $fields[$this->name.'.id'] = "!= NULL";
            else
                // edit
                $fields[$this->name.'.id'] = "!= $id";

            $this->recursive = -1;
            if ($this->hasAny($fields))
            {
                $this->invalidate('unique_'.$field);
                return false;
            }
            else
                return true;
       }*/

    public function isUploadedFile($params)
    {
        $val = array_shift($params);
        if ((isset($val['error']) && $val['error'] == 0) ||
            (!empty($val['tmp_name']) && $val['tmp_name'] != 'none')
        ) {
            return true;
        }
        return false;
    }
}

?>
