<?php

class ProfilsController extends AppController {

    var $name = 'Profils';
    var $helpers = array('Tree', 'Fck');
    var $components = array('Dbdroits', 'Menu', 'Progress', 'Email');
    var $uses = array('Profil', 'Aro');
    // Gestion des droits
    var $commeDroit = array(
        'add' => 'Profils:index',
        'delete' => 'Profils:index',
        'edit' => 'Profils:index',
        'view' => 'Profils:index',
        'notifier' => 'Profils:index'
    );

    function index() {
        $profils = $this->Profil->find('threaded', array('order' => 'Profil.id ASC', 'recursive' => -1));
        $this->_isDeletable($profils);
        $this->set('data', $profils);
    }

    function _isDeletable(&$profils) {
        foreach ($profils as &$profil) {
            if ($this->Profil->User->find('first', array('conditions' => array('User.profil_id' => $profil['Profil']['id']), 'recursive' => -1)))
                $profil['Profil']['deletable'] = false;
            else
                $profil['Profil']['deletable'] = true;
            if ($profil['children'] != array())
                $this->_isDeletable($profil['children']);
        }
    }

    function view($id = null) {
        if (!$id) {
            $this->Session->setFlash('Invalide id pour le profil.');
            $this->redirect(array('action'=>'index'));
        }
        $this->set('profil', $this->Profil->read(null, $id));
    }

    function add() {
        $sortie = false;
        if (!empty($this->data)) {
            if (empty($this->data['Profil']['parent_id']))
                $this->request->data['Profil']['parent_id'] = 0;
            if ($this->Profil->save($this->data)) {
                if ($this->data['Profil']['parent_id'] != 0) {
                    $this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['parent_id']));
                    $this->Dbdroits->MajCruDroits(
                            array(
                        'model' => 'Profil',
                        'foreign_key' => $this->Profil->id,
                        'alias' => $this->data['Profil']['libelle']
                            ), array(
                        'model' => 'Profil',
                        'foreign_key' => $this->data['Profil']['parent_id']
                            ), $this->data['Droits']
                    );
                } else {
                    $this->Dbdroits->AddCru(
                            array(
                        'model' => 'Profil',
                        'foreign_key' => $this->Profil->id,
                        'alias' => $this->data['Profil']['libelle']
                            ), null
                    );
                }
                $this->Session->setFlash('Le profil a été sauvegardé', 'growl');
                $sortie = true;
            }
            else
                $this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.', 'growl', array('type'=>'erreur'));
        }
        if ($sortie)
            $this->redirect(array('action'=>'index'));
        else {
            $this->set('profils', $this->Profil->find('list', array('order' => array('Profil.libelle ASC'))));
        }
    }

    function edit($id = null) {
        $sortie = false;
        if (empty($this->data)) {
            $this->data = $this->Profil->find('first', array('conditions' => array("Profil.id" => $id)));
            if (empty($this->data)) {
                $this->Session->setFlash('Invalide id pour le profil', 'growl', array('type' => 'erreur'));
                $sortie = true;
            }
            else
                $this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model' => 'Profil', 'foreign_key' => $id));
        } else {
            $this->Progress->start(200, 100, 200, '#FFCC00', '#006699');
            if (empty($this->data['Profil']['parent_id']))
                $this->request->data['Profil']['parent_id'] = 0;
            $profil = $this->Profil->read(null, $id);
            if ($this->Profil->save($this->data)) {
                if ($profil['Profil']['parent_id'] != $this->data['Profil']['parent_id']) {
                    $this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['parent_id']));
                    $this->Dbdroits->MajCruDroits(
                            array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['id'], 'alias' => $this->data['Profil']['libelle']), array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['parent_id']), $this->data['Droits']
                    );
                } elseif ($profil['Profil']['parent_id'] != 0) {
                    $this->Dbdroits->MajCruDroits(
                            array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['id'], 'alias' => $this->data['Profil']['libelle']), array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['parent_id']), $this->data['Droits']
                    );
                } else {
                    $this->Dbdroits->MajCruDroits(
                            array('model' => 'Profil', 'foreign_key' => $this->data['Profil']['id'], 'alias' => $this->data['Profil']['libelle']), null, $this->data['Droits']
                    );
                }
                $aro_id = $this->Aro->find('first', array('conditions' => array('model' => 'Profil', 'foreign_key' => $id), 'fields' => array('id')));
                $this->Aro->id = $aro_id['Aro']['id'];
                $this->Aro->saveField('parent_id', $this->data['Profil']['parent_id']);
                $Users = $this->Profil->User->find('all', array('conditions' => array('User.profil_id' => $this->data['Profil']['id']), 'recursive' => -1));
                $nbUsers = count($Users);
                $cpt = 0;
                foreach ($Users as $User) {
                    $cpt++;
                    $this->Progress->at($cpt * (100 / $nbUsers), 'Mise à jour des données pour : ' . $User['User']['login'] . '...');
                    $this->Dbdroits->MajCruDroits(
                            array(
                        'model' => 'User', 'foreign_key' => $User['User']['id'], 'alias' => $User['User']['login']), array(
                        'model' => 'Profil', 'foreign_key' => $User['User']['profil_id']), $this->data['Droits']
                    );
                }
                $this->Session->setFlash('Le profil a été modifié', 'growl');
                $sortie = true;
            }
            else
                $this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.', 'growl', array('type' => 'erreur'));
        }
        if ($sortie) {
            $this->Progress->end('/profils');
        } else {
            $profils = $this->Profil->find('list', array(
                'conditions' => array('Profil.id <>' => $id),
                'order' => array('Profil.libelle ASC')));
            $this->set('profils', $profils);
            $this->set('selectedProfil', $this->data['Profil']['parent_id']);
            $this->set('listeCtrlAction', $this->Menu->menuCtrlActionAffichage());
        }
    }

    function delete($id = null) {
        if (!$id) {
            $tab = $this->Profil->findAll("Profil.id=$id");
            $this->Session->setFlash('Invalide id pour le profil', 'growl');
            $this->redirect(array('action'=>'index'));
        }
        if (!$this->Profil->User->find('first', array('conditions' => array('User.profil_id' => $id)))) {
            if ($this->Profil->delete($id)) {
                $aro_id = $this->Aro->find('first', array(
                    'conditions' => array(
                        'model' => 'Profil',
                        'foreign_key' => $id
                    ),
                    'fields' => array('id')));
                if (!empty($aro_id))
                    $this->Aro->delete($aro_id['Aro']['id']);
                $this->Session->setFlash('Le profil a été supprimé', 'growl');
                $this->redirect(array('action'=>'index'));
            }
            else {
                $this->Session->setFlash('Impossible de supprimer ce profil', 'growl', array('type'=>'erreur'));
                $this->redirect(array('action'=>'index'));
            }
        } else {
            $this->Session->setFlash('Impossible de supprimer ce profil car il est attribué.', 'growl', array('type'=>'erreur'));
            $this->redirect(array('action'=>'index'));
        }
    }

    function notifier($profil_id) {
        $profil = $this->Profil->find('first', array('conditions' => array('Profil.id' => $profil_id),
            'recursive' => -1));

        if (empty($this->data)) {
            $this->set('libelle_profil', $profil['Profil']['libelle']);
            $this->set('id', $profil['Profil']['id']);
        } else {
            $this->Progress->start(200, 100, 200, '#FFCC00', '#006699');

            $conditions['AND']['User.profil_id'] = $profil['Profil']['id'];
            $conditions['AND']['User.email ILIKE'] = "%@%";
            $users = $this->Profil->User->find('all', array('conditions' => $conditions,
                'recursive' => -1)
            );
            $nbUsers = count($users);
            $cpt = 0;
            if (Configure::read("SMTP_USE")) {
                $this->Email->smtpOptions = array('port' => Configure::read("SMTP_PORT"),
                    'timeout' => Configure::read("SMTP_TIMEOUT"),
                    'host' => Configure::read("SMTP_HOST"),
                    'username' => Configure::read("SMTP_USERNAME"),
                    'password' => Configure::read("SMTP_PASSWORD"),
                    'client' => Configure::read("SMTP_CLIENT"));
                $this->Email->delivery = 'smtp';
            }
            else
                $this->Email->delivery = 'mail';

            $this->Email->from = Configure::read("MAIL_FROM");
            $this->Email->subject = "Notification aux utilisateurs du profil : " . $profil['Profil']['libelle'];
            $this->Email->template = 'default';
            $this->Email->layout = 'default';
            $this->Email->sendAs = 'html';
            $this->Email->charset = 'UTF-8';
            $this->Email->attachments = null;

            foreach ($users as $user) {
                $this->Progress->at($cpt * (100 / $nbUsers), $user['User']['email'] . '...');
                $cpt++;
                $this->Email->to = $user['User']['email'];
                $this->Email->send($this->data['Profil']['content']);
                sleep(1);
            }
            $this->Progress->end('/profils/index');
        }
    }

}
