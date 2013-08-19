<div id="vue_cadre">
<h3>Fiche Type de s&eacute;ance</h3>

<dl>
	<div class="demi">
		<dt>Libelle</dt>
		<dd>&nbsp;<?php echo $typeacte['Typeacte']['libelle']?></dd>
	</div>
	<div class="demi">
		<dt>Nature</dt>
		<dd>&nbsp;<?php echo $typeacte['Nature']['libelle']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="demi">
		<dt>Modèle de projet</dt>
		<dd>&nbsp;<?php echo $typeacte['Modelprojet']['modele']; ?></dd>
	</div>

	<div class="demi">
		<dt>Modèle de document final</dt>
		<dd>&nbsp;<?php echo $typeacte['Modeldeliberation']['modele']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="demi">
		<dt>Date de cr&eacute;ation</dt>
		<dd>&nbsp;<?php echo $typeacte['Typeacte']['created']?></dd>
	</div>
	<div class="demi">
		<dt>Date de modification</dt>
		<dd>&nbsp;<?php echo $typeacte['Typeacte']['modified']?></dd>
	</div>
	<div class="spacer"></div>
</dl>

<br />
<ul id="actions_fiche">
	<li><?php echo $this->Html->link('<i class="icon-arrow-left"></i> Retour', '/typeactes/index', array('class'=>'btn', 
                                                                       'title'=>'Retourner à la liste',
                                                                       'escape' => false), false) ?> </li>
	<li><?php echo $this->Html->link('<i class="icon-edit"></i> Modifier', '/typeactes/edit/' . $typeacte['Typeacte']['id'], array('class'=>'btn', 
                                                                                                     'title'=>'Modifier', 
                                                                                                     'escape' =>false), false) ?> </li>
</ul>

</div>
