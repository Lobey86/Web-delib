<h2>Envoyer le projet à un utilisateur</h2>

<?php
    $options = array('detour' =>'Envoyé à : ', 'retour' => 'Aller-retour :', 'validation'=> 'Validation finale :');
    $attributes=array('legend'=>false, 'separator' => '<br />', 'width' => 100, 'value' => 'detour');

    echo $form->create('Insert', array('url'=>'/deliberations/rebond/'.$delib_id,'type'=>'post'));
    echo $form->input('user_id', array('label'=>'Destinataire', 'title'=>"A qui voulez vous envoyer le projet ? : "));
    echo '<br/>';
    if ($typeEtape == CAKEFLOW_COLLABORATIF) {
	$disable = 'disabled';
	echo $form->hidden('retour', array('value'=>1));
        echo $form->input('retour', array('label'=>'Aller-retour :', 'type'=>'radio', 'disabled'=>'disabled', 'options' => $options));
        echo '<br/>';
        echo $html->div('profil', 'Note : pour les étapes collaboratives (ET), l\'aller-retour est obligatoire.');
    } else
        echo $form->radio('option', $options,$attributes);
?>
<br/> <br/> <br/>
<?php
	echo '<div class="submit">';
		echo $form->submit('Valider', array('div'=>false, 'class'=>'bt_add', 'name'=>'Valider'));
		echo $html->link('Annuler', array('action'=>'traiter', $delib_id), array('class'=>'link_annuler', 'name'=>'Annuler'));
	echo '</div>';
    echo $form->end();
?>
