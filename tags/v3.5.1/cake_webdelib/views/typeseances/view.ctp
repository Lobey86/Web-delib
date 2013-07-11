<div id="vue_cadre">
<h3>Fiche Types de s&eacute;ance</h3>

<dl>
	<div class="demi">
		<dt>Libelle</dt>
		<dd>&nbsp;<?php echo $typeseance['Typeseance']['libelle']?></dd>
	</div>
	<div class="demi">
	<dt>Nombre de jours avant retard</dt>
	<dd><?php echo $typeseance['Typeseance']['retard']; ?></dd>
	</div>
	<div class="spacer"></div>
	<div class="demi">
		<dt>Action</dt>
		<dd>&nbsp;<?php echo $typeseance['Typeseance']['action'] ? 'Avis' : 'Vote'?></dd>
	</div>
	<div class="demi">
		<dt>Compteur</dt>
		<dd>&nbsp;<?php echo $typeseance['Compteur']['nom']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="demi">
		<dt>Model de la convocation</dt>
		<dd>&nbsp;<?php echo $typeseance['Modelconvocation']['modele']; ?></dd>
	</div>
	<div class="demi">
		<dt>Model de l'ordre du jour</dt>
		<dd>&nbsp;<?php echo $typeseance['Modelordredujour']['modele']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="demi">
		<dt>Model du PV sommaire</dt>
		<dd>&nbsp;<?php echo $typeseance['Modelpvsommaire']['modele']?></dd>
	</div>
	<div class="demi">
		<dt>Model du PV d�taill�</dt>
		<dd>&nbsp;<?php echo $typeseance['Modelpvdetaille']['modele']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="demi">
		<dt>Date de c&eacute;ration</dt>
		<dd>&nbsp;<?php echo $typeseance['Typeseance']['created']?></dd>
	</div>
	<div class="demi">
		<dt>Date de modification</dt>
		<dd>&nbsp;<?php echo $typeseance['Typeseance']['modified']?></dd>
	</div>
	<div class="spacer"></div>
</dl>

<br />
<ul id="actions_fiche">
	<li><?php echo $html->link(SHY, '/typeseances/index', array('class'=>'link_annuler_sans_border', 'title'=>'Retourner � la liste'), false, false) ?> </li>
	<li><?php echo $html->link(SHY, '/typeseances/edit/' . $typeseance['Typeseance']['id'], array('class'=>'link_modifier', 'title'=>'Modifier'), false, false) ?> </li>
</ul>

</div>