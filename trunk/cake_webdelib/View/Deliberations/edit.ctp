<?php echo $this->Html->script('calendrier.js'); ?>
<?php echo $this->Html->script('utils.js'); ?>
<?php echo $this->Html->script('deliberation.js'); ?>
<?php echo $this->Html->script('ckeditor/ckeditor'); ?>
<?php echo $this->Html->script('ckeditor/adapters/jquery'); ?>
<?php echo $this->Html->script('multidelib.js'); ?>
<!--
<script>
function reset_html(id) {
    $('#'+id).html($('#'+id).html());
}
$(document).ready(function() {

    var file_input_index = 0;
    $('input[type=file]').each(function() {
        file_input_index++;
        $(this).wrap('<div id="file_input_container_'+file_input_index+'"></div>');
        $(this).after('<input type="button" value="Effacer" class="purge_file"  onclick="reset_html(\'file_input_container_'+file_input_index+'\')" />');
    });
   
});
</script>
-->

<?php
	if($this->Html->value('Deliberation.id')) {
		echo "<h1>Modification du projet : ".$this->Html->value('Deliberation.id')."</h1>";
		echo $this->Form->create('Deliberation', array('url'=>'/deliberations/edit/'.$this->Html->value('Deliberation.id'), 'type'=>'file', 'name'=>'Deliberation'));
	} else {
		echo "<h1>Ajout d'un projet</h1>";
		echo $this->Form->create('Deliberation', array('url'=>'/deliberations/add','type'=>'file', 'name'=>'Deliberation'));
	}
?>

<div class='onglet'>
	<a href="#" id="emptylink" alt=""></a>
	<a href="javascript:afficheOnglet(1)" id='lienTab1' class="ongletCourant">Informations principales</a>
	<a href="javascript:afficheOnglet(2)" id='lienTab2'>Textes</a>
	<a href="javascript:afficheOnglet(3)" id='lienTab3'>Annexe(s)</a>
<?php if (!empty($infosupdefs)): ?>
	<a href="javascript:afficheOnglet(4)" id='lienTab4'>Informations suppl&eacute;mentaires</a>
<?php endif; ?>
<?php if (Configure::read('DELIBERATIONS_MULTIPLES')): ?>
	<a href="javascript:afficheOnglet(5)" id='lienTab5' style="display: none">D&eacute;lib&eacute;rations rattach&eacute;es</a>
<?php endif; ?>
</div>

<div id="tab1">
        <fieldset id='info'>
	<div class='demi'>
		<?php echo '<b><u>Rédacteur</u></b> : <i>'.$this->Html->value('Redacteur.prenom').' '.$this->Html->value('Redacteur.nom').'</i>';?>
		<br/>
		<?php echo '<b><u>Service émetteur</u></b> : <i>'.$this->Html->value('Service.libelle').'</i>'; ?>
	</div>
	<div class='demi'>
		<?php echo '<b><u>Date cr&eacute;ation</u></b> : <i>'. $this->Html->value('Deliberation.created').'</i>';?>
		<br/>
		<?php echo '<b><u>Date de modification</u></b> : <i>'. $this->Html->value('Deliberation.modified').'</i>';?>
	</div>
        </fieldset>
	<div class='spacer'></div>
        <?php echo $this->Form->input('Deliberation.typeacte_id', array('label'    => 'Type d\'acte <acronym title="obligatoire">(*)</acronym>', 
                                                                        'options'  => $this->Session->read('user.Nature'), 
                                                                        'empty'    => '(sélectionner le type d\'acte)', 
                                                                        'id'       => 'listeTypeactesId',
                                                                        'onChange' => "updateTypeseances(this);", 
                                                                        'escape'   => false));  ?>
	<div class='spacer'></div>
 	<?php echo $this->Form->input('Deliberation.objet', array('type'=>'textarea','label'=>'Libellé <acronym title="obligatoire">(*)</acronym>','cols' => '60','rows'=> '2'));?>

	<div class='spacer'></div>
 	<?php echo $this->Form->input('Deliberation.titre', array('type'=>'textarea','label'=>'Titre','cols' => '60','rows'=> '2'));?>

	<div class='spacer'></div>

        <div id='selectTypeseances' class='gauche'>
        <?php
          if (!empty( $typeseances))
             echo $this->Form->input('Typeseance', array('options'  => $typeseances,
                                                      'label'    => 'Types de séance',
                                                      'size'     => 10,
                                                      'onchange' => "updateDatesSeances(this);",
                                                      'multiple' => true));
        ?>
        </div>
        <div id='selectDatesSeances' class='droite'>
        <?php
          if (!empty($seances))
                echo $this->Form->input('Seance', array( 'options'  => $seances,
                                                         'label'    => 'Dates de séance',
                                                         'size'     => 10,
                                                         'multiple' => true));
        ?>  
        </div>

	<div class='spacer'></div>
	<?php echo $this->Form->input('Deliberation.rapporteur_id', array('label'=>'Rapporteur', 'options'=>$rapporteurs, 'empty'=>true)); ?>

	<div class='spacer'></div>
	<?php echo $this->Form->input('Deliberation.theme_id', array('label'=>'Thème <acronym title="obligatoire">(*)</acronym>', 'options'=>$themes, 'default'=>$this->Html->value('Deliberation.theme_id'), 'empty'=>'(sélectionner le thème)', 'escape'=>false)); ?>
	<div class='spacer'></div>

	<?php 
	    if ($USE_PASTELL)
                echo $this->Form->input('Deliberation.num_pref', array('label'=>'Nomenclature <acronym title="obligatoire">(*)</acronym>', 'options'=>$nomenclatures, 'default'=>$this->Html->value('Deliberation.num_pref'), 'empty'=>true, 'escape'=>false)); 
            else {
                echo $this->Form->input( 'Deliberation.num_pref',
				   array('div'      => false,
                                         'label'    => 'Num Pref',
                                         'id'       => 'classif1', 
                                         'size'     => '60',
					 'readonly' => 'readonly'));
        ?>

		<a class="list_form" href="#add" onclick="javascript:window.open('<?php echo $this->base; ?>/deliberations/classification', 'Select_attribut', 'scrollbars=yes,width=570,height=450');" id="classification_text">[Choisir la classification]</a>
               <?php echo $this->Form->hidden('Deliberation.num_pref',array('id'=>'classif2','name'=>'classif2'));
            }
        ?>
	<div class='spacer'></div>

	<?php echo $this->Form->label('Deliberation.date_limite', 'Date limite');?>
	<?php
		if (!empty($this->data['Deliberation']['date_limite']) && $this->data['Deliberation']['date_limite'] != '01/01/1970')
			$value = "value='".$this->data['Deliberation']['date_limite']."'";
		else
			$value = "value=''";
	?>
	<input name="date_limite" size="9" <?php echo $value; ?>"/>&nbsp;<a href="javascript:show_calendar('Deliberation.date_limite','f');" alt="" id="afficheCalendrier"><?php echo $this->Html->image("calendar.png", array('style'=>"border='0'")); ?></a>
	<div class='spacer'></div>


<?php 
        if ($DELIBERATIONS_MULTIPLES) {
           echo $this->Form->input('Deliberation.is_multidelib', array(
	                     'type'=>'checkbox',
	                     'disabled'=>  isset($this->data['Multidelib']) ,
                             'checked'=>  isset($this->data['Multidelib']) OR (isset($this->data['Deliberation']['is_multidelib']) && $this->data['Deliberation']['is_multidelib']==1)?true:false,
		             'label'=>'Multi Délibération',
		             'onClick'=> "multiDelib(this);" ));
        }
?>

	<div class='spacer'></div>
</div>

<div id="tab2" style="display: none;">
    <?php echo $this->element('texte', array('key' => 'texte_projet'));?>
	<div class='spacer'></div>

    <?php echo $this->element('texte', array('key' => 'texte_synthese'));?>
	<div class='spacer'></div>

	<div id='texteDelibOngletTextes'>
            <div id='texteDeliberation'>
                <?php echo $this->element('texte', array('key' => 'deliberation'));?>
            </div>
        </div>
	<div class='spacer'></div>
</div>

<div id="tab3" style="display: none;">
	<?php
	$annexeOptions = array('ref' => 'delibPrincipale');
	$tabAnnexes = array();
        if (isset($this->data['Annex'])) {
	    foreach ($this->data['Annex'] as $annexe) {
	        if ($annexe['model'] == 'Projet') 
		    $tabAnnexes[] = $annexe;
            }
        }
	if (isset($this->data['Annex'])) $annexeOptions['annexes'] = $tabAnnexes;
   	echo $this->element('annexe', $annexeOptions);
	echo $this->Html->tag('div', '', array('class'=>'spacer'));
	echo $this->Html->tag('p', 'Note : les modifications apportées ici ne prendront effet que lors de la sauvegarde du projet.');
   	?>
</div>

<?php if (!empty($infosupdefs)): ?>
<div id="tab4" style="display: none;">
	<?php
	foreach($infosupdefs as $infosupdef) {
                // Amélioration 4.1 : on ne peut modifier une infosup qu'en fonction du profil
                $canEdit = 'disabled';
                foreach ($infosupdef['Profil'] as $profil) 
                    if ($profil['id'] == $profil_id) 
                        $canEdit = 'enable';

		$fieldName = 'Infosup.'.$infosupdef['Infosupdef']['code'];
		$fieldId = 'Infosup'.Inflector::camelize($infosupdef['Infosupdef']['code']);
		echo "<div class='required'>";
			echo $this->Form->label($fieldName, $infosupdef['Infosupdef']['nom'], array('name'=>'label'.$infosupdef['Infosupdef']['code']));
			if ($infosupdef['Infosupdef']['type'] == 'text') {
				echo $this->Form->input($fieldName, array('label'=>'', 'type'=> 'textarea', 'size'=>$infosupdef['Infosupdef']['taille'], 'title'=>$infosupdef['Infosupdef']['commentaire'], 'disabled'=> $canEdit  ));
			} elseif ($infosupdef['Infosupdef']['type'] == 'boolean') {
				echo $this->Form->input($fieldName, array('label'=>'', 'type'=>'checkbox', 'title'=>$infosupdef['Infosupdef']['commentaire'], 'disabled'=> $canEdit));
			} elseif ($infosupdef['Infosupdef']['type'] == 'date') {
				echo $this->Form->input($fieldName, array('type'=>'text',  'disabled'=> $canEdit,  'div'=>false, 'label'=>'', 'size'=>'9', 'title'=>$infosupdef['Infosupdef']['commentaire']));
				echo '&nbsp;';
                                if ($canEdit ==  'enable')
				    echo $this->Html->link($this->Html->image("calendar.png", array('style'=>"border='0'")), "javascript:show_calendar('Deliberation.$fieldId', 'f');", array('escape' =>false), false); 
                                elseif ($canEdit ==  'disabled')
                                     echo($this->Html->image("calendar.png", array('style'=>"border='0'")));
			} elseif ($infosupdef['Infosupdef']['type'] == 'richText') {
				echo '<div class="annexesGauche"></div>';
				echo '<div class="fckEditorProjet">';
					echo $this->Form->input($fieldName, array('label'=>'', 'type'=>'textarea', 'disabled'=> $canEdit));
					echo $this->Fck->load($fieldId);
				echo '</div>';
				echo '<div class="spacer"></div>';
			} elseif ($infosupdef['Infosupdef']['type'] == 'file') {
				if (empty($this->data['Infosup'][$infosupdef['Infosupdef']['code']]))
					echo  $this->Form->input($fieldName, array('label'=>'', 'type'=>'file', 'size'=>'60', 'title'=>$infosupdef['Infosupdef']['commentaire'],  'disabled'=> $canEdit));
				else {
					echo '<span id="'.$infosupdef['Infosupdef']['code'].'InputFichier" style="display: none;"></span>';
					echo '<span id="'.$infosupdef['Infosupdef']['code'].'AfficheFichier">';
					echo '['.$this->Html->link($this->data['Infosup'][$infosupdef['Infosupdef']['code']], '/infosups/download/'.$this->data['Deliberation']['id'].'/'.$infosupdef['Infosupdef']['id'], array('title'=>$infosupdef['Infosupdef']['commentaire'])).']';
					echo '&nbsp;&nbsp;';
                                        if ($canEdit == 'enable')
					    echo $this->Html->link('Supprimer', "javascript:infoSupSupprimerFichier('".$infosupdef['Infosupdef']['code']."', '".$infosupdef['Infosupdef']['commentaire']."')", null, 'Voulez-vous vraiment supprimer le fichier joint ?\n\nAttention : ne prendra effet que lors de la sauvegarde\n');
					echo '</span>';
				}
			} elseif ($infosupdef['Infosupdef']['type'] == 'odtFile') {
				if (empty($this->data['Infosup'][$infosupdef['Infosupdef']['code']]))
					echo  $this->Form->input($fieldName, array('label'=>'', 'type'=>'file', 'size'=>'60', 'title'=>$infosupdef['Infosupdef']['commentaire'], 'disabled'=> $canEdit));
				else {
					echo '<span id="'.$infosupdef['Infosupdef']['code'].'InputFichier" style="display: none;"></span>';
					echo '<span id="'.$infosupdef['Infosupdef']['code'].'AfficheFichier">';
					if (Configure::read('GENERER_DOC_SIMPLE')) {
						echo '['.$this->Html->link($this->data['Infosup'][$infosupdef['Infosupdef']['code']], '/infosups/download/'.$this->data['Deliberation']['id'].'/'.$infosupdef['Infosupdef']['id'], array('title'=>$infosupdef['Infosupdef']['commentaire'],  'disabled'=> $canEdit)).']';
					} else {
						$name = $this->data['Infosup'][$infosupdef['Infosupdef']['code']] ;
                                                if ($canEdit == 'enable')
						    $url = Configure::read('PROTOCOLE_DL')."://".$_SERVER['SERVER_NAME']."/files/generee/projet/".$this->data['Deliberation']['id']."/$name"; 
                                                else 
                                                    $url = "http://".$_SERVER['SERVER_NAME']."/files/generee/projet/".$this->data['Deliberation']['id']."/$name";
						echo "<a href='$url'>$name</a> ";
					}
					echo '&nbsp;&nbsp;';
                                        if ($canEdit == 'enable')
					    echo $this->Html->link('Supprimer', "javascript:infoSupSupprimerFichier('".$infosupdef['Infosupdef']['code']."', '".$infosupdef['Infosupdef']['commentaire']."')", null, 'Voulez-vous vraiment supprimer le fichier joint ?\n\nAttention : ne prendra effet que lors de la sauvegarde\n');
					echo '</span>';
				}
			} elseif ($infosupdef['Infosupdef']['type'] == 'list') {
				echo $this->Form->input($fieldName, array('label'=>'', 'options'=>$infosuplistedefs[$infosupdef['Infosupdef']['code']], 'empty'=>true, 'title'=>$infosupdef['Infosupdef']['commentaire'], 'disabled'=> $canEdit));
			}
		echo '</div>';
		echo '<br>';
                echo "<div class='spacer'> </div>";
	};?>
</div>
<?php endif; ?>

<?php if (Configure::read('DELIBERATIONS_MULTIPLES')) :?> 
<div id="tab5" style="display: none;">
    <?php echo $this->element('multidelib');?>
</div>
<?php endif; ?>

<div class="spacer" style="border-top: solid 1px #e0ef90;"></div>

<div class="submit">
	<?php echo $this->Form->hidden('Deliberation.id')?>
	<?php
		if ($this->Html->value('Deliberation.id'))
			$onclick = "javascript:return checkForm(form, ".$this->Html->value('Deliberation.id').")";
		else
			$onclick = "javascript:return checkForm(form, 0)";
		echo $this->Form->submit('Sauvegarder', array('div'=>false, 'class'=>'bt_save_border', 'name'=>'Sauvegarder', 'onclick'=>$onclick));
	?>
	<?php echo $this->Html->link('Annuler', '/deliberations/mesProjetsRedaction', array('class'=>'link_annuler', 'name'=>'Annuler'))?>
</div>

<?php echo $this->Form->end(); ?>
<script>
// variables globales

// affichage de l'éditeur de texte intégré ckEditor
function editerTexte(obj, textId, afficheTextId) {
	$('#'+textId).ckeditor();
	$('#'+afficheTextId).hide();
	$(obj).hide();
}
</script>
