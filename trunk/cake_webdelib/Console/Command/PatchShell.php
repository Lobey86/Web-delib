<?php

App::uses('ComponentCollection', 'Controller');
App::uses('Component', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class PatchShell extends AppShell {

    public $tasks = array(
        'Tdt', // 4.1.01 => 4.1.02
        'Gedooo', // 4.1.02 => 4.1.03
        'AjouteSectionAnnexe' // 4.1.xx => 4.2
    );
    public $uses = array('Annex', 'Deliberation');

    public function main() {
        $this->out('Script de patch de Webdelib');
        // Désactivation du cache
        Configure::write('Cache.disable', true);
        // Création de styles perso
        $this->stdout->styles('time', array('text' => 'magenta'));
        $this->stdout->styles('important', array('text' => 'red', 'bold' => true));
        
        // Quelle version installer ? test des arguments
        switch ($this->command) {
            case "4101to4102":
                $this->Version_4101to4102();
                break;

            case "4102to4103":
                $this->Version_4102to4103();
                break;

            case "41to42": //Modification des modèles, ajout de la section Annexes avec variable fichier
                $this->Version_41to42();
                break;

            case null: // Pas de commande
                $this->out("\n<error>Un nom de patch est nécessaire, tapez 'Console/cake patch -h' pour afficher l'aide.</error>\n");
                break;

            default : // Commande inconnue
                $this->out("\n<error>Commande '" . $this->command . "' inconnue, nom de patch attendu, tapez 'Console/cake patch -h' pour afficher l'aide.</error>\n");
        }
    }

    /**
     * Options d'éxecution et validation des arguments
     *
     * @return Parser $parser
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();
        $parser->description(__('Commandes de mise à jour de webdelib.'));

        $parser->addSubcommand('41to42', array(
            'help' => __('Ajoute une section annexe à la fin des modèles d\'édition si ils ont l\'attribut joindre_annexe.')
        ));

        $parser->addSubcommand('4102to4103', array(
            'help' => __('Application du patch de mise à jour de 4.1.02 à 4.1.03.'),
            'parser' => array(
                'options' => array(
                    'classification' => array(
                        'name' => 'classification',
                        'required' => false,
                        'short' => 'c',
                        'help' => 'Mise à jour de classification.',
                        'boolean' => true
                    ),
                    'textes' => array(
                        'name' => 'textes',
                        'required' => false,
                        'short' => 't',
                        'help' => 'Tests à effectuer (projets et annexes)',
                        'choices' => array('all', 'noseance', 'nontraitees'),
                        'default' => 'all'
                    )
                )
            )
        ));

        $parser->addSubcommand('4101to4102', array(
            'help' => __('Application du patch de mise à jour de 4.1.01 à 4.1.02.'),
            'parser' => array(
                'options' => array(
                    'PDFtoODT' => array(
                        'help' => __('Conversion PDFtoODT.'),
                        'required' => false,
                        'short' => 'o',
                        'boolean' => true
                    ),
                    'classification' => array(
                        'help' => __('Mise à jour de la classification.'),
                        'required' => false,
                        'short' => 'c',
                        'boolean' => true
                    ),
                    'num_pref' => array(
                        'help' => __('Mise à jour des num_pref'),
                        'required' => false,
                        'short' => 'n',
                        'boolean' => true
                    )
                )
            )
        ));
        return $parser;
    }
    public function Version_41to42()
    {
        $this->out("<important>Mise à jour de Webdelib 4.1.xx => 4.2</important>\n");
        $this->out('Recherche des modèles avec jointure des annexes...');
        $this->AjouteSectionAnnexe->execute();
        //Passage du script sql de migration
        $db = ConnectionManager::getDataSource('default');
        $this->out('Mise à jour de la base de données..');

        $migratesql = APP.DS.'Config'.DS.'Schema'.DS.'patches'.DS.'4.1_to_4.2.sql';
        if (!file_exists($migratesql)){
            $this->out("<error>Patch sql 4.1=>4.2 introuvable, veuillez vous assurer d'avoir la dernière version des sources de Webdelib</error>");
            return;
        }
        $newpluginsql = APP.DS.'Plugin'.DS.'ModelOdtValidator'.DS.'Config'.DS.'Schema'.DS.'FormatValidator-v1.sql';
        if (!file_exists($newpluginsql)){
            $this->out("<error>Script sql du plugin ModelOdtValidator introuvable, veuillez vous assurer d'avoir installé le plugin</error>");
            return;
        }

        $newpluginfile = new File($newpluginsql);
        $migratefile = new File($migratesql);

        if ($migratefile->exists() && $newpluginfile->exists()){
            $db->begin();
            try{
                $sql = explode(';', $newpluginfile->read());
                foreach ($sql as $sqlline){
                    $line = trim($sqlline);
                    if (!empty($line) && !in_array(strtolower($line), array('begin', 'commit')))
                        $db->rawQuery($line);
                }
                $sql = explode(';', $migratefile->read());
                foreach ($sql as $sqlline){
                    $line = trim($sqlline);
                    if (!empty($line) && !in_array(strtolower($line), array('begin', 'commit')))
                        $db->rawQuery($line);
                }
                $db->commit();
            }catch (Exception $e){
                $this->out("<error>Erreur sql (ligne ".$e->getLine().")</error>");
                $this->out($e->getMessage());
                $db->rollback();
            }
        }
        else
            $this->out("<error>sql de mise à jour introuvable</error>");
    }
    /** Mise à jour de la version 4.1.02 à la version 4.1.03
     * Génération des annexes en odt valide, Mise à jour de classification, 
     * Changement du num préfecture
     */
    public function Version_4102to4103()
    {
        $errors = array();
        $warnings = array();
        $logPath = Configure::read("WEBDELIB_PATH") . DS . "tmp" . DS . "logs" . DS . "gedooo.log";
        $success = true;
        $this->out("\n<important>Démarrage du patch de mise à jour de Webdelib 4.1.02 vers 4.1.03...</important>\n");
        $time_start = microtime(true);

        $this->Gedooo->execute();
        
        $textesInError = $this->Gedooo->testTextes($this->params['textes']);
        if (!empty($textesInError)) {
            $error_msg = "Textes non conformes : \n";
            foreach ($textesInError as $texte) {
                $error_msg .= "\t# ". $texte['model']." " . $texte['id'] . ' : \'' . $texte['filename'] . '\' (colonne: ' . $texte['column'] . ")\n";
            }
            $warnings[] = "Des textes de projet peuvent causer des problèmes (voir : $logPath).";
            $this->out("\n<error>$error_msg</error>");
        } else {
            $this->out("\n<info>Tous les textes sont conformes !!</info>");
        }

        $time_end = microtime(true);
        $this->out("<time>Temps écoulé durant la phase de test des textes : " . round($time_end - $time_start) . ' secondes</time>', 1, Shell::VERBOSE);

        // Message avertissant l'utilisateur de l'emplacement du fichier log
        //$this->out("\n<important>Emplacement fichier log Gedooo : " . $this->Gedooo->logPath . "</important>\n");

        //Mise à jour de la classification
        if (!empty($this->params['classification'])) {
            if (Configure::read("USE_S2LOW")) {
                $this->out('<info>Mise à jour classification...</info>');
                $success = $this->Tdt->classification() && $success;
                if ($success)
                    $this->out('<info>Mise à jour de la classification Terminée</info>');
                else
                    $this->out('<warning>Warning : Problème lors de la mise à jour de la classification !!</warning>');
            }
            else
                $warnings[] = '<warning>Warning : l\'utilisation de S2LOW est désactivée (voir fichier webdelib.inc), mise à jour de la classification impossible...</warning>';
        }
        
        if (!empty($warnings)){
            $this->out("\n<warning>Avertissements : </warning>");
            foreach ($warnings as $warning) {
                $this->out("\t<warning>* ".$warning.'</warning>');
            }
        }

        if ($success) {
            $this->footer('<important>Patch de la version 4.1.02 vers la 4.1.03 accompli avec succès !</important>');
//            $this->footer('<comment>Fin de l\'éxecution du patch 4.1.03</comment>');
        }
        else
            $this->footer('<error>Erreur : un problème est survenu lors de l\'application du patch !!</error>');
        
    }

    /* Mise à jour de la version 4.1.01 à la version 4.1.02
     * Génération des annexes en odt valide, Mise à jour de classification, 
     * Changement du num préfecture
     */

    public function Version_4101to4102() {
        $success = true;
        $this->out("Patch Processing...\n");
        $collection = new ComponentCollection();
        $action = 0;

        //Génération des annexes en odt valide
        if (!empty($this->params['PDFtoODT'])) {
            $action++;
            App::uses('PdfComponent', 'Controller/Component');
            $this->out((isset($this->params['option']) ? '' : '[1]') . "Migration des pdf en odt...");
            $this->Pdf = new PdfComponent($collection);

            $annexes = $this->Annex->find('all', array('fields' => array('id', 'filename', 'filename_pdf', 'data_pdf'),
                'order' => 'id ASC',
                'recursive' => -1));

            $i = 0;
            foreach ($annexes as $annexe) {
                if (strpos($annexe['Annex']['filename'], 'odt') === false)
                    continue;

                $this->out('Generation ' . $annexe['Annex']['id'] . '...');

                $i++;
                $newAnnexe['Annex']['id'] = $annexe['Annex']['id'];
                $outputDir = tempdir();
                $folder = new Folder($outputDir);
                $file = new File($outputDir . $annexe['Annex']['id'] . '.pdf', false);
                $file->write($annexe['Annex']['data_pdf']);
                $newAnnexe['Annex']['data'] = $this->Pdf->toOdt($file->pwd());
                $newAnnexe['Annex']['filetype'] = 'application/pdf';
                $newAnnexe['Annex']['size'] = $file->size();

                $file->delete();
                $file->close();
                $folder->delete();
                $success = $this->Annex->save($newAnnexe['Annex']) & $success;
                if (!$success)
                    $this->out('<error>Erreur sur la sauvegarde : ' . $annexe['id'] . "<error>\n");
                else
                    $this->out('Sauvegarde Terminée : ' . $annexe['Annex']['id'] . "\n");
            }

            $this->out((isset($this->params['option']) ? '' : '[1]') . 'Migration des pdf en odt Terminée (' . $i . ' modifications');
        }

        //Mise à jour de la classification
        if (!empty($this->params['classification'])) {
            $action++;
            $this->out((isset($this->params['option']) ? '' : '[2]') . 'Mise à jour classification...');
            $success = $this->Tdt->classification() & $success;
            if ($success)
                $this->out((isset($this->params['option']) ? '' : '[2]') . 'Mise à jour classification Terminée');
            else
                $this->out('<error>Erreur</error> : Mise à jour classification !!');
        }

        //Mise à jour num préfecture
        if (!empty($this->params['num_pref'])) {
            $action++;
            $this->out((isset($this->params['option']) ? '' : '[3]') . 'Mise à jour numéro Préfecture...');
            App::uses('DeliberationsController', 'Controller');
            $this->Delib = new DeliberationsController($collection);
            $deliberations = $this->Deliberation->find('all', array('fields' => array('id', 'num_pref'),
                'recursive' => -1));

            foreach ($deliberations as $deliberation) {
                $this->out('Migration deliberation : ' . $deliberation['Deliberation']['id'] . '...');
                $num_pref = strstr($deliberation['Deliberation']['num_pref'], ' - ', true);
                if (isset($num_pref) && !empty($num_pref)) {
                    $this->Deliberation->id = $deliberation['Deliberation']['id'];
                    $success = $this->Deliberation->saveField('num_pref', $num_pref) & $success;
                    if (!$success)
                        $this->out("<error>Erreur</error> sur la sauvegarde : " . $deliberation['Deliberation']['id'] . "\n");
                    else
                        $this->out('Sauvegarde Terminée : ' . $deliberation['Deliberation']['id'] . "\n");
                }
                else
                    $this->out('Rien à faire : ' . $deliberation['Deliberation']['id'] . "\n");
            }

            if ($success)
                $this->out((isset($this->params['option']) ? '' : '[3]') . 'Mise à jour numéro Préfecture Terminée');
            else
                $this->out('<error>Erreur</error> : Mise à jour numéro Préfecture !!');
        }

        if ($action === 0) {
            $success = false;
            $this->out('<question>Commande inconnue !!</question>');
        }
        if ($success)
            $this->footer('<info>patch complete<info>');
        else
            $this->footer('<warning>patch incomplete !!</warning>');
    }

    /**
     * Affiche un message entouré de deux barres horizontales
     * @param string $var message
     */
    public function footer($var) {
        $this->hr();
        $this->out($var);
        $this->hr();
    }

}
