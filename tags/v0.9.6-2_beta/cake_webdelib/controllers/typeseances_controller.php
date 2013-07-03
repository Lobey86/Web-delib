<?php
class TypeseancesController extends AppController {

	var $name = 'Typeseances';
	var $helpers = array('Html', 'Form' );

	function index() {
		$this->Typeseance->recursive = 0;
		$this->set('typeseances', $this->Typeseance->findAll());
	}

	function view($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalide id pour le type de seance.');
			$this->redirect('/typeseances/index');
		}
		$this->set('typeseance', $this->Typeseance->read(null, $id));
	}

	function add() {
		if (empty($this->data)) {
			$this->render();
		} else {
			$this->cleanUpFields();
			if ($this->Typeseance->save($this->data)) {
				$this->Session->setFlash('Le type de seance a &eacute;t&eacute; sauvegard&eacute;');
				$this->redirect('/typeseances/index');
			} else {
				$this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.');
			}
		}
	}

	function edit($id = null) {
		if (empty($this->data)) {
			if (!$id) {
				$this->Session->setFlash('Invalide id pour le type de seance');
				$this->redirect('/typeseances/index');
			}
			$this->data = $this->Typeseance->read(null, $id);
		} else {
			$this->cleanUpFields();
			if ($this->Typeseance->save($this->data)) {
				$this->Session->setFlash('Le type de seance a &eacute;t&eacute; modifi&eacute;');
				$this->redirect('/typeseances/index');
			} else {
				$this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.');
			}
		}
	}

	function delete($id = null) {
		if (!$id) {
			$this->Session->setFlash('Invalide id pour le type de seance');
			$this->redirect('/typeseances/index');
		}
		if ($this->Typeseance->del($id)) {
			$this->Session->setFlash('Le type de seance a &eacute;t&eacute; supprim&eacute;');
			$this->redirect('/typeseances/index');
		}
	}

}
?>