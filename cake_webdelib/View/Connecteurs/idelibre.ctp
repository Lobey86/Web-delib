<?php
echo $this->BsForm->create('Connecteur', array('url' => array('controller' => 'connecteurs', 'action' => 'makeconf', 'idelibre'), 'type' => 'file'));
?>
<div id="configIdelibre">
    <fieldset>
        <legend>Activation du service I-delibRE</legend>
        <?php
        echo $this->BsForm->radio('use_idelibre',array('true' => 'Oui', 'false' => 'Non'), array(
            'value' => Configure::read('USE_IDELIBRE') ? 'true' : 'false',
            'onChange' => 'changeActivation(this)'
        ));
        ?>
    </fieldset>
    <div class='spacer'></div>
    <div id='config_content' <?php echo Configure::read('USE_IDELIBRE') === false ? 'style="display: none;"' : ''; ?>>
        <fieldset>
            <legend>Informations d'authentification</legend>
            <?php
            echo $this->BsForm->input('idelibre_host', array(
                'type' => 'text',
                "placeholder" => "https://idelibre.adullact.org",
                'label' => 'URL',
                'value' => Configure::read('IDELIBRE_HOST')
            ));

            echo $this->BsForm->input('idelibre_conn', array(
                'type' => 'text',
                'placeholder' => 'Nom de la connexion',
                'title' => 'Nom de la variable de connexion dans le fichier database.php de i-delibRE',
                'label' => 'Connexion de la collectivité',
                'value' => Configure::read('IDELIBRE_CONN')
            ));

            echo $this->BsForm->input('idelibre_login', array(
                'type' => 'text',
                'placeholder' => 'Nom d\'utilisateur',
                'label' => 'Login',
                'value' => Configure::read('IDELIBRE_LOGIN')
            ));

            echo $this->BsForm->input('idelibre_pwd', array(
                'type' => 'password',
                'placeholder' => 'Mot de passe utilisateur',
                'label' => 'Mot de passe',
                'value' => Configure::read('IDELIBRE_PWD')));
            ?>
        </fieldset>
<!--        <fieldset id='infos_certificat'>
            <legend>Certificat de connexion</legend>
            <?php
//            echo $this->BsForm->radio('idelibre_use_cert',array('true' => 'Oui', 'false' => 'Non'), array(
//                'value' => Configure::read('IDELIBRE_USE_CERT') ? 'true' : 'false',
//                'onChange' => 'changeActivationCert(this)'
//            ));
//            echo $this->Html->tag('div', null, array('id' => 'idelibre_cert', 'style' => (Configure::read('IDELIBRE_USE_CERT')) ? '':'display:none'));
//            echo $this->Html->tag('hr', '');
//            echo $this->BsForm->input('clientcert', array(
//                'type' => 'file',
//                'label' => 'Certificat (p12)'
//            ));
//            echo $this->BsForm->input('idelibre_certpwd', array(
//                'type' => 'password',
//                'placeholder' => "Mot de passe du certificat",
//                'value' => Configure::read('IDELIBRE_CERTPWD'),
//                'label' => 'Mot de passe'));
//            echo $this->Html->tag('/div');
            ?>
        </fieldset>-->
    </div>
</div>
   <?php
echo $this->Html2->btnSaveCancel('', array('controller' => 'connecteurs', 'action' => 'index'));
echo $this->BsForm->end();
    ?>
<script type="text/javascript">
function changeActivation(element) {
    if ($(element).val() == 'true') {
        $('#config_content').show();
    } else {
        $('#config_content').hide();
    }
}

function changeActivationCert(element) {
    if ($(element).val() == 'true') {
        $('#idelibre_cert').show();
    } else {
        $('#idelibre_cert').hide();
    }
}
</script>