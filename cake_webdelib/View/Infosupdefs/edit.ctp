<script>
window.onload=initAffichage;

/*
* Affiche ou masque les options en fonction du type d'info sup
*/
function afficheOptions(typeInfoSup) {

	/* On masque toutes les options */
	document.getElementById("taille").style.display = 'none';
	document.getElementById("val_initiale").style.display = 'none';
	document.getElementById("val_initiale_boolean").style.display = 'none';
	document.getElementById("val_initiale_date").style.display = 'none';
	if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = 'none';
	document.getElementById("gestionListe").style.display = 'none';

	/* si le choix est vide : on sort */
	if((typeInfoSup.value.length==0) || (typeInfoSup.value==null)) return;

	/* on affiche en fonction du type d'info sup */
	switch(typeInfoSup.value) {
	case "text":
		document.getElementById("taille").style.display = '';
		document.getElementById("val_initiale").style.display = '';
		if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = '';
		break;
	case "richText":
		document.getElementById("val_initiale").style.display = '';
		if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = '';
		break;
	case "date":
		document.getElementById("val_initiale_date").style.display = '';
		if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = '';
		break;
	case "file":
		break;
	case "boolean":
		document.getElementById("val_initiale_boolean").style.display = '';
		if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = '';
		break;
	case "odtFile":
		break;
	case "list":
		if (document.getElementById("recherche") != null) document.getElementById("recherche").style.display = '';
		document.getElementById("gestionListe").style.display = '';
		break;
	}
}

function initAffichage() {
	selectTypeInfoSup = document.getElementById("selectTypeInfoSup");
	afficheOptions(selectTypeInfoSup);
}
</script>

<?php
echo $this->Html->script('calendrier.js');

echo $this->Html->tag('h2', $titre);

echo $this->Form->create('Infosupdef',array('url'=>array('action'=>$this->request->action), 'type'=>'post' ,'name'=>'infoSupForm'));
?>
	<div class="required">
	 	<?php echo $this->Form->input('Infosupdef.nom', array('label'=>'Nom <acronym title="obligatoire">*</acronym>','size' => '40', 'title'=>'Nom affiché dans le formulaire d\'édition des projets'));?>
	</div>
	<br/>
	<div class="required">
	 	<?php echo $this->Form->input('Infosupdef.commentaire', array('label'=>'Commentaire','size' => '80', 'title'=>'Bulle d\'information affiché dans le formulaire d\'édition des projets'));?>
	</div>
	<br/>
	<div class="required">
	 	<?php echo $this->Form->input('Infosupdef.code', array('label'=>'Code <acronym title="obligatoire">*</acronym>','size' => '40', 'title'=>'Code unique utilisé pour les éditions (pas d\'espace ni de caractère spécial)'), false, false);?>
	</div>
	<br/>
	<div class="required">
		<?php
			$htmlAttributes['disabled'] = '';
			$empty=false;
			if (($this->action=='edit') && !$Infosupdef->isDeletable($this->request->data['Infosupdef']['id'])) {
				$htmlAttributes['disabled'] = 'disabled';
				echo $this->Form->hidden('Infosupdef.type');
				$empty=true;
			}
		?>
	 	<?php echo $this->Form->input('Infosupdef.type',array('label'=>'type <acronym title="obligatoire">(*)</acronym>', 'options'=>$types, 'id'=>'selectTypeInfoSup', 'onChange'=>"afficheOptions(this);", 'disabled'=>$htmlAttributes['disabled'], 'showEmpty'=>$empty)); ?>
	</div>
	<div id="gestionListe">
		<span>Note : la gestion des éléments de la liste est accessible &agrave; partir de la liste des informations suppl&eacute;mentaires.</span>
	</div>
	</ br>
	<div class="required" id="taille">
	 	<?php echo $this->Form->input('Infosupdef.taille', array('label'=>'Taille','size' => '2', 'title'=>'Taille du champ affiché dans le formulaire d\'édition des projets (uniquement pour le type Texte)'));?>
	</div>
	<br/>
	<div class="required" id="val_initiale">
	 	<?php echo $this->Form->input('Infosupdef.val_initiale', array('label'=>'Valeur initiale','size' => '80', 'title'=>'Valeur initiale lors de la création d\'un projet'));?>
	</div>
	<div class="required" id="val_initiale_boolean">
	 	<?php echo $this->Form->input('Infosupdef.val_initiale_boolean', array('label'=>'Valeur initiale','options'=>$listEditBoolean));?>
	</div>
	<div class="required" id="val_initiale_date">
		<?php echo $this->Form->input('Infosupdef.val_initiale_date', array('div'=>false, 'label'=>'Valeur initiale','id'=>'InfosupdefValInitialeDate', 'size'=>'9', 'title'=>'Valeur initiale lors de la création d\'un projet'));?>
		<?php echo '&nbsp;';?>
		<?php echo $this->Html->link($this->Html->image("calendar.png", array('style'=>"border:0;")), "javascript:show_calendar('infoSupForm.InfosupdefValInitialeDate', 'f');", array('escape' => false), false);?>
	</div>
	<br/>
	<br/>
<?php
	if($this->request->data['Infosupdef']['model'] == 'Deliberation')
		echo $this->Form->label('Infosupdef.recherche', $this->Form->input('Infosupdef.recherche', array('type'=>'checkbox', 'label'=>false, 'div'=>false)).' Inclure dans la recherche', array('class'=>'span2', 'id'=>'recherche'));
	else
		echo $this->Form->hidden('Infosupdef.recherche', array('value'=>false));

	echo $this->Form->label('Infosupdef.actif', $this->Form->input('Infosupdef.actif',array('type'=>'checkbox', 'label'=>false, 'div'=>false)).' information active', array('class'=>'span2'));
	echo $this->Html->tag('div', '', array('class'=>'spacer'));
?>
	<br/>

	<?php echo $this->Form->input('Profil', array('options' =>$profils, 'multiple' => true, 'size' => 10,
		'label'=>'Profils autorisés', 'title'=>'l\'information supplémentaire ne sera utilisable que pour les profils sélectionnés dans cette liste')); ?>

	<div class="submit">
	<?php
		echo $this->Form->hidden('Infosupdef.id');
		echo $this->Form->hidden('Infosupdef.model');
		$this->Html2->boutonsSaveCancelUrl($lienRetour); 
	?>
	</div>
<?php echo $this->Form->end(); ?>
