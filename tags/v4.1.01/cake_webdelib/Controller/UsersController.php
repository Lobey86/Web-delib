<?php
class UsersController extends AppController {

	var $name = 'Users';
	var $helpers = array('Form', 'Html', 'Html2', 'Session');
	var $uses = array( 'User', 'Service', 'Cakeflow.Circuit', 'Profil', 'Typeacte', 'ArosAdo', 'Aro', 'Ado');
	var $components = array('Utils', 'Acl', 'Menu', 'Dbdroits');

	// Gestion des droits
	var $aucunDroit = array(
			'login',
			'logout',
			'getAdresse',
			'getCP',
			'getNom',
			'getPrenom',
			'getVille',
			'view',
			'changeFormat',
			'changeUserMdp',
	);

	var $commeDroit = array(
			'add'=>'Users:index',
			'delete'=>'Users:index',
			'edit'=>'Users:index',
			'changeMdp'=>'Users:index'
	);

	function index() {
                $this->User->Behaviors->attach('Containable');
                $this->ArosAdo->Behaviors->attach('Containable');
                $this->paginate = array('User' => array(
                                        'fields' => array('DISTINCT User.id', 'User.login', 'User.nom', 'User.prenom', 'User.telfixe', 'User.telmobile' ),
                                        'limit' => 20,
                                        'contain' => array('Profil.libelle', 'Service.libelle'),
                                        'order' => array( 'User.login' => 'asc')));

		$users = $this->paginate('User');
		foreach ($users as &$user) {
                        $aro    = $this->Aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$user['User']['id']),
                                                           'fields'=>array('id'),
                                                           'recursive' => -1));
                        $aros_ados = $this->ArosAdo->find('all', array('conditions' => array('ArosAdo.aro_id' => $aro['Aro']['id'],
                                                                                             'ArosAdo._create' => 1),
                                                                       'contain'    => array('Ado.alias'),
                                                                       'fields'     => array('Ado.id')));
                        foreach( $aros_ados as  $aros_ado)
			     $user['Natures'][] = substr($aros_ado['Ado']['alias'], strlen('Typeacte:'), strlen($aros_ado['Ado']['alias']));

			$user['User']['is_deletable'] = $this->_isDeletable($user, $message);
		}
		$this->set('users', $users);
	}

	function view($id = null) {
		$user = $this->User->read(null, $id);
		if (!$user) {
                        $this->Session->setFlash('Invalide id pour l\'utilisateur', 'growl');
			$this->redirect('/users/index');
		} else {
			$this->set('user', $user);
			$this->set('circuitDefautLibelle', $this->User->circuitDefaut($id, 'nom'));
		}
	}

	function add() {
		// Initialisation
		$sortie = false;

		if (empty($this->data)){
		    // Initialisation des données
		    $this->request->data['User']['accept_notif'] = 0;
		    $this->set('natures', $this->Typeacte->find('all', array('recursive' => -1) ));
		}
		else {
			if ($this->User->save($this->data)) {
				// Ajout de l'utilisateur dans la table aros
				$user_id = $this->User->id;
				$Profil=$this->Profil->find('first',array('conditions'=>array('id'=>$this->data['User']['profil_id']),'recursive'=>-1));
				$this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Profil','foreign_key'=>$this->data['User']['profil_id']));
				$this->Dbdroits->MajCruDroits(
						array('model'=>'User','foreign_key'=>$user_id,'alias'=>$this->data['User']['login']),
						array('model'=>'Profil','foreign_key'=>$this->data['User']['profil_id']),
						$this->data['Droits']
				);
                                $aro    = $this->Aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$user_id),
                                                           'fields'=>array('id'),
                                                           'recursive' => -1));


				foreach ($this->data['Nature'] as $nature_id => $can) {
					$nature_id = substr($nature_id, 3, strlen($nature_id));
                                        $ado    = $this->Ado->find('first',array('conditions'=>array('Ado.model'       => 'Typeacte',
                                                                                                     'Ado.foreign_key' => $nature_id),
                                                                                 'fields'=>array('Ado.id'),
                                                                                 'recursive' => -1));

					if ($can)
						$this->ArosAdo->allow($aro['Aro']['id'], $ado['Ado']['id']);
					else
						$this->ArosAdo->deny($aro['Aro']['id'],  $ado['Ado']['id']);
				}

				//$this->_setNewPermissions( $this->data['User']['profil_id'], $user_id, $this->data['User']['login'] );
				$this->Session->setFlash('L\'utilisateur \''.$this->data['User']['login'].'\' a &eacute;t&eacute; ajout&eacute;', 'growl');
				$sortie = true;
			} else
				$this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.', 'growl');
		}
		if ($sortie)
			$this->redirect('/users/index');
		else {
                    $this->set('selectedCircuits', 0);
                    $this->set('services', $this->User->Service->generateTreeList(array('Service.actif' => 1), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;'));
	            $this->set('selectedServices', null);
	            $this->set('profils', $this->User->Profil->find('list'));
			$this->set('notif', array('1'=>'oui','0'=>'non'));
			$this->set('circuits', $this->Circuit->getList());
                        $natures = $this->Typeacte->find('all', array('recursive' => -1));
                        foreach ($natures as &$nature) 
                            $nature['Nature']['check'] = null;
                        $this->set('natures', $natures);
			$this->render('edit');
		}
	}

	function edit($id = null) {
		$sortie = false;
		if (empty($this->data)) {
			$this->request->data = $this->User->find('first', array('conditions' => array('User.id' =>$id)));
			if (empty($this->data)) {
				$this->Session->setFlash('Invalide id pour l\'utilisateur', 'growl');
				$sortie = true;
  			} else {
                                $aro    = $this->Aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$id),
                                                           'fields'=>array('id'),
                                                           'recursive' => -1));

				$this->set('selectedCircuits', $this->data['User']['circuit_defaut_id']);
				$this->set('selectedServices', $this->_selectedArray($this->data['Service']));
				$this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'User','foreign_key'=>$id));
				$natures = $this->Typeacte->find('all', array('recursive' => -1));
                
				foreach ($natures as &$nature) {
                                        $ado    = $this->Ado->find('first',array('conditions'=>array('Ado.model'       => 'Typeacte',
                                                                                                     'Ado.foreign_key' => $nature['Typeacte']['id']),
                                                                                 'fields'=>array('Ado.id'),
                                                                                 'recursive' => -1));

					$nature['Nature']['check'] = $this->ArosAdo->check($aro['Aro']['id'], $ado['Ado']['id']);
                                }
				$this->set('natures', $natures);
			}
		} else {
			$userDb = $this->User->find('first', array('conditions'=>array('id'=>$id), 'recursive'=>-1));
                        $aro    = $this->Aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$id),
                                                                 'fields'=>array('id'),
                                                                  'recursive' => -1));
			if ($this->User->save($this->data)) {
				foreach ($this->data['Nature'] as $nature_id => $can) {
					$nature_id = substr($nature_id, 3, strlen($nature_id));
                                        $ado    = $this->Ado->find('first',array('conditions'=>array('Ado.model'       => 'Typeacte', 
                                                                                                     'Ado.foreign_key' => $nature_id),
                                                                                 'fields'=>array('Ado.id'),
                                                                                 'recursive' => -1));
                           
                                        
					if ($can)
						$this->ArosAdo->allow($aro['Aro']['id'], $ado['Ado']['id']);
					else
						$this->ArosAdo->deny($aro['Aro']['id'],  $ado['Ado']['id']);
				}
				if ($userDb['User']['profil_id']!=$this->data['User']['profil_id']) {
				    $this->request->data['Droits'] = $this->Dbdroits->litCruDroits(array('model'=>'Profil','foreign_key'=>$this->data['User']['profil_id']));
				}

				$this->Dbdroits->MajCruDroits(
						array('model'=>'User', 'foreign_key'=>$id, 'alias'=>$this->data['User']['login']),
						array('model'=>'Profil','foreign_key'=>$this->data['User']['profil_id']),
						$this->request->data['Droits']
				);

				$this->Session->setFlash('L\'utilisateur \''.$this->data['User']['login'].'\' a &eacute;t&eacute; modifi&eacute;', 'growl');
				$sortie = true;
			} else {
				$this->Session->setFlash('Veuillez corriger les erreurs ci-dessous.', 'growl');
				$this->set('selectedServices', $this->data['Service']['Service']);
			}
		}
		if ($sortie)
			$this->redirect('/users/index');
		else {
			$this->set('services', $this->User->Service->generateTreeList(array('Service.actif' => 1), null, null, '&nbsp;&nbsp;&nbsp;&nbsp;'));
			$this->set('profils', $this->User->Profil->find('list'));
			$this->set('notif',array('1'=>'oui','0'=>'non'));
			$this->set('circuits', $this->Circuit->getList());
			$this->set('listeCtrlAction', $this->Menu->menuCtrlActionAffichage());
                        $aro    = $this->Aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$id),
                                                           'fields'=>array('id'),
                                                           'recursive' => -1));

			$natures = $this->Typeacte->find('all', array('recursive' => -1));
			foreach ($natures as &$nature) {
                            $ado    = $this->Ado->find('first',array('conditions'=>array('Ado.model'       => 'Typeacte',
                                                                                          'Ado.foreign_key' => $nature['Typeacte']['id']),
                                                                                 'fields'=>array('Ado.id'),
                                                                                 'recursive' => -1));

			    $nature['Nature']['check'] = $this->ArosAdo->check($aro['Aro']['id'], $ado['Ado']['id']);
                        }
			$this->set('natures', $natures);
		}
	}

	/* dans le controleur car utilisé dans la vue index pour l'affichage */
	function _isDeletable($user, &$message) {
		$this->loadModel('Deliberation');
		if ($user['User']['id'] == 1) {
			$message = 'L\'utilisateur \''.$user['User']['login'].'\' ne peut pas être supprimé car il est protégé';
			return false;
		} elseif ($user['User']['id'] == $this->Session->read('user.User.id')) {
			$message = 'L\'utilisateur courant \''.$user['User']['login'].'\' ne peut pas être supprimé';
			return false;
		} elseif ($this->Deliberation->find('count', array('conditions' => array('Deliberation.redacteur_id'=>$user['User']['id']),
				'recursive' => -1))) {
				$message = 'L\'utilisateur \''.$user['User']['login'].'\' ne peut pas être supprimé car il est l\'auteur de délibérations';
				return false;
		}
		return true;
	}

	function delete($id = null) {
		$messageErreur = '';
		$user = $this->User->find('first' , array('conditions' => array('User.id' => $id),
                                                          'fields'     => array('id', 'login'),
                                                          'recursive'  => -1));
		if (empty($user))
			$this->Session->setFlash('Invalide id pour l\'utilisateur', 'growl');
		elseif (!$this->_isDeletable($user, $messageErreur)) {
			$this->Session->setFlash($messageErreur);
		} elseif ($this->User->delete($id)) {
			$aro = new Aro();
			$aro_id = $aro->find('first',array('conditions'=>array('model'=>'User', 'foreign_key'=>$id),'fields'=>array('id')));
			$aro->delete($aro_id['Aro']['id']);
			$this->Session->setFlash('L\'utilisateur \''.$user['User']['login'].'\' a &eacute;t&eacute; supprim&eacute;', 'growl');
		}
		$this->redirect('/users/index');
	}

	function getNom ($id) {
		$condition = "User.id = $id";
		$fields = "nom";
		$dataValeur = $this->User->findAll($condition, $fields);
		if (isset($dataValeur['0'] ['User']['nom']))
			return $dataValeur['0'] ['User']['nom'];
		else
			return '';
	}

	function getPrenom ($id) {
		$condition = "User.id = $id";
		$fields = "prenom";
		$dataValeur = $this->User->findAll($condition, $fields);
		if (isset($dataValeur['0'] ['User']['prenom']))
			return $dataValeur['0'] ['User']['prenom'];
		else
			return '';
	}

	function getAdresse ($id) {
		$condition = "User.id = $id";
		$fields = "adresse";
		$dataValeur = $this->User->findAll($condition, $fields);
		if (isset($dataValeur['0'] ['User']['adresse']))
			return $dataValeur['0'] ['User']['adresse'];
		else
			return '';
	}

	function getCP ($id) {
		$condition = "User.id = $id";
		$fields = "CP";
		$dataValeur = $this->User->findAll($condition, $fields);
		if (isset($dataValeur['0'] ['User']['CP']))
			return $dataValeur['0'] ['User']['CP'];
		else
			return '';
	}

	function getVille ($id) {
		$condition = "User.id = $id";
		$fields = "ville";
		$dataValeur = $this->User->findAll($condition, $fields);
		if (isset($dataValeur['0'] ['User']['ville']))
			return $dataValeur['0'] ['User']['ville'];
		else
			return '';
	}

	function login() {
		//pas de message d'erreur
		$this->set('errorMsg',"");
                $this->set('logo_path',   'http://'.$_SERVER['HTTP_HOST'].$this->base."/files/image/logo.jpg");
                
		//si le formulaire d'authentification a été soumis
		if (!empty($this->data)) {
			$isAuthentif = false;
			//cherche si utilisateur enregistré possede ce login
			$user = $this->User->findByLogin($this->data['User']['login']);
			unset($user['Historique']);
			if (empty($user)){
				$this->set('errorMsg',"L'utilisateur ".$this->data['User']['login']." n'existe pas dans l'application.");
				$this->layout='connection';
				$this->render();
				//exit;
			}
			if ($user['User']['id']==1){
				$isAuthentif =  ($user['User']['password'] == md5($this->data['User']['password']));
			}
			else {
				if (Configure::read('USE_AD')){
					include (ROOT.DS.APP_DIR.DS."Vendor/adLDAP.php");
					$ldap=new adLDAP();
					$isAuthentif = $ldap->authenticate($this->data['User']['login'], $this->data['User']['password']);
				}
				elseif (Configure::read('USE_OPENLDAP'))
				$isAuthentif = $this->_checkLDAP($this->data['User']['login'], $this->data['User']['password']);
				else
					$isAuthentif =  ($user['User']['password'] == md5($this->data['User']['password']));

			}

			if ($isAuthentif) {

				//on stocke l'utilisateur en session
				$this->Session->write('user',$user);
				// On stock la collectivite de l'utilisateur en cas de PASTELL
				if (Configure::read('USE_PASTELL')) {
					$this->loadModel('Collectivite');
					$coll = $this->Collectivite->find('first', array('conditions' => array('Collectivite.id'=>1),
							'recursive'  => -1,
							'fields'     => array('id_entity')));
					$this->Session->write('user.Collectivite', $coll);
				}
				// On stock les natures qu'il peut traiter
                                $aro = $this->Aro->find('first', array('conditions' => array('Aro.model'       => 'User', 
                                                                                             'Aro.foreign_key' => $user['User']['id']),
                                                                        'recursive' => -1,
                                                                        'fields'    => array('Aro.id')));
				$natures = array();
				$droits = $this->ArosAdo->find('all', array('conditions'=> array('aro_id'=>$aro['Aro']['id'], '_read'=>1)));
				foreach ($droits as $droit) {
                                   if ($droit['Ado']['foreign_key'] != '') 
					$natures[$droit['Ado']['foreign_key']] = substr($droit['Ado']['alias'], 9, strlen($droit['Ado']['alias']));
                                }
				$this->Session->write('user.Nature', $natures);

				//services auquels appartient l'agent
				$services = array();
				foreach ($user['Service'] as $service)
					$services[$service['id']] = $this->Service->doList($service['id']);

				$this->Session->write('user.Service', $services);
				$this->Session->write('user.User.service', key($services));

				// Chargement du menu dans la session
				$this->Session->write('menuPrincipal', $this->Menu->load('webDelib', $user['User']['id']));
				$this->Session->setFlash('Bienvenue sur Webdelib', 'growl');
				$this->redirect('/');
			}
			else{
				//sinon on prépare le message d'erreur a afficher dans la vue
				$this->set('errorMsg','Mauvais identifiant ou  mot de passe.Veuillez recommencer.');
				$this->layout='connection';
			}
		}
		else {
			$this->layout='connection';
		}
	}


	function logout() {
		//on supprime les infos utilisateur de la session
		$this->Session->destroy();
		$this->redirect('/users/login');
	}

	function changeMdp($id) {
		if (empty($this->data)) {
			$this->request->data = $this->User->read(null, $id);
			if (empty($this->data)) {
				$this->Session->setFlash('Invalide id pour l\'utilisateur');
				$this->redirect('/users/index');
			}
			else
				$this->request->data['User']['password'] = '';
		} else {
			if ($this->User->validatesPassword($this->data)) {
                        $this->User->id = $id;
				$user = $this->User->find('first', array('conditions'=>array('User.id'=> $id),
						'recursive' => -1) );
				if ($this->User->saveField('password', $this->data['User']['password'])) {
					$this->Session->setFlash('Le mot de passe de l\'utilisateur \''.$user['User']['login'].'\' a &eacute;t&eacute; modifi&eacute;');
					$this->redirect('/users/index');
				}
				else
					$this->Session->setFlash('Erreur lors de la saisie des mots de passe.');
			}
			else
				$this->Session->setFlash('Erreur lors de la saisie des mots de passe.');
		}
	}

	function changeFormat($id) {
		$this->Session->delete('user.format.sortie');
		$this->Session->write('user.format.sortie', $id);
		//redirection sur la page où on était avant de changer de service
		$this->redirect($this->Session->read('user.User.myUrl'));
		//$this->redirect($this->Session->read('user.User.lasturl'));
	}

	function _checkLDAP($login, $password) {
		//  $DN = Configure::read('UNIQUE_ID')."=$login, ".BASE_DN;
		$conn=ldap_connect(Configure::read('LDAP_HOST'), Configure::read('LDAP_PORT')) or  die("connexion impossible au serveur LDAP");
		@ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		@ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0); // required for AD


		$bind_attr = 'dn';
		$search_filter = "(" .Configure::read('UNIQUE_ID')."=" . $login . ")";
		$result = @ldap_search($conn, Configure::read('BASE_DN') , $search_filter, array("dn", $bind_attr));

		$info = ldap_get_entries($conn, $result);
		if($info['count'] == 0)
			return false;

		if ($bind_attr == "dn") {
			$found_bind_user = $info[0]['dn'];
		} else {
			$found_bind_user = $info[0][strtolower($bind_attr)][0];
		}
		if (!empty($found_bind_user)) {
			return(@ldap_bind($conn, $info[0]['dn'],  $password));
		} else {
			return false;
		}

	}

	function changeUserMdp() {
		if (empty($this->data)) {
			$this->request->data = $this->User->read(null, $this->Session->read('user.User.id'));
			if (empty($this->data)) {
				$this->Session->setFlash('Invalide id pour l\'utilisateur');
				$this->redirect('/');
			}
			else
				$this->request->data['User']['password'] = '';
		} else {
			if (($this->User->validatesPassword($this->data)) && ($this->User->validOldPassword($this->data))) {
				$user = $this->User->read(null, $this->Session->read('user.User.id'));
				if ($this->User->saveField('password', $this->data['User']['password'])) {
					$this->Session->setFlash('Votre mot de passe a &eacute;t&eacute; modifi&eacute;');
					$this->redirect('/');
				}
				else
					$this->Session->setFlash('Erreur lors de la saisie des mots de passe.');
			}
			else
				$this->Session->setFlash('Erreur lors de la saisie des mots de passe.');
		}
	}
}
?>