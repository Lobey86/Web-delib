<div id="vue_cadre">
<h3>Fiche acteur</h3>

<dl>
	<div class="demi">
		<dt>Identit&eacute;</dt>
		<dd>&nbsp;<?php echo $acteur['Acteur']['salutation'].' '.$acteur['Acteur']['prenom'].' '.$acteur['Acteur']['nom'].($acteur['Acteur']['titre'] ? ', ':'').$acteur['Acteur']['titre']?></dd>
		<dt>Adresse postale</dt>
			<dd class="compact"><?php echo $acteur['Acteur']['adresse1']?></dd>
			<dd class="compact"><?php echo $acteur['Acteur']['adresse2']?></dd>
			<dd class="compact"><?php echo $acteur['Acteur']['cp']?></dd>
			<dd class="compact"><?php echo $acteur['Acteur']['ville']?></dd>
		<dt>Contacts</dt>
			<dd class="compact">T�l�phone fixe : <?php echo $acteur['Acteur']['telfixe']?></dd>
			<dd class="compact">T�l�phone mobile : <?php echo $acteur['Acteur']['telmobile']?></dd>
			<dd class="compact">Adresse email : <?php echo $acteur['Acteur']['email']?></dd>
	</div>
	<div class="demi">
		<dt>Type</dt>
		<dd>&nbsp;<?php echo $acteur['Typeacteur']['nom']?></dd>
		<?php if($acteur['Typeacteur']['elu']) {
			echo "<dt>Num�ro d'ordre dans le conseil</dt>";
			echo "<dd>".$acteur['Acteur']['position']."</dd>";
			echo "<dt>D�l�gations</dt>";
			foreach ($acteur['Service'] as $service){
				echo '<dd class="compact">'.$service['libelle'].'</dd>';
			};
			echo "<dt>Date Naissance</dt>";
			echo "<dd>".$acteur['Acteur']['date_naissance']."</dd>";
		} ?>
	</div>
	<div class="spacer"></div>

	<div class="tiers">
		<dt>Note</dt>
		<dd>&nbsp;<?php echo $acteur['Acteur']['note']?></dd>
	</div>
	<div class="tiers">
		<dt>Date de cr&eacute;ation</dt>
		<dd>&nbsp;<?php echo $acteur['Acteur']['created']?></dd>
	</div>
	<div class="tiers">
		<dt>Date de modification</dt>
		<dd>&nbsp;<?php echo $acteur['Acteur']['modified']?></dd>
	</div>
	<div class="spacer"></div>

</dl>

<ul id="actions_fiche">
	<?php
		echo '<li>' . $html->link(SHY, $session->read('user.User.lasturl'), array('class'=>'link_annuler_sans_border', 'title'=>'Annuler'), false, false) . '</li>';
		if ($Droits->check($session->read('user.User.id'), 'Acteurs:edit'))
			echo '<li>'.$html->link(SHY, '/acteurs/edit/' . $acteur['Acteur']['id'], array('class'=>'link_modifier', 'title'=>'Modifier'), false, false).'</li>';
	?>
</ul>

</div>