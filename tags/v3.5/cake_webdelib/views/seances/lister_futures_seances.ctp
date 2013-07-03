<div class="seances">
<h2>S&eacute;ances &agrave; traiter</h2>


<table width='100%' cellpadding="0" cellspacing="0" border="0">
    <tr>
        <th width='150px'>Type</th>
	<th width='190px'>Date S&eacute;ance</th>
	<th width='20%'>Pr&eacute;paration</th>
        <th width='20%'>En cours</th>
        <th width='20%'>Finalisation</th>
    </tr>
<?php 
       $numLigne = 1;
       foreach ($seances as $seance): 
          $rowClass = ($numLigne & 1)?array('height' => '36px'):array( 'height' => '36px', 'class'=>'altrow');
       echo $html->tag('tr', null, $rowClass); 
       $numLigne++;
?>

		<td><b><?php echo $seance['Typeseance']['libelle']; ?></b></td>
		<td><?php echo ($html->link($seance['Seance']['date'], "/seances/edit/".$seance['Seance']['id'])); ?></td>
		<td class="actions" width="110px"> <!-- largeur en fonction des icones -->
<?php
			echo $html->link(SHY,'/seances/afficherProjets/' . $seance['Seance']['id'], array('class'=>'link_classer_odj', 'title'=>'Voir l\'ordre des projets', 'alt'=>'odj'), false, false);
			$urlConvoc = '/models/generer/null/'.$seance['Seance']['id'].'/'.$seance['Typeseance']['modelconvocation_id'].'/null/';
			$urlOdj = '/models/generer/null/'.$seance['Seance']['id'].'/'.$seance['Typeseance']['modelordredujour_id'].'/null/';
			$urlConvocUnique = '/models/generer/null/'.$seance['Seance']['id'].'/'.$seance['Typeseance']['modelconvocation_id'].'/null/0/retour/0/true';
			$urlOdjUnique = '/models/generer/null/'.$seance['Seance']['id'].'/'.$seance['Typeseance']['modelordredujour_id'].'/null/0/retour/0/true';
			if (Configure::read('AFFICHE_CONVOCS_ANONYME'))
				echo $html->link(SHY, $urlConvocUnique, array(
					'class'=>'link_convocation_unique',
					'title'=>"Apercu d'une convocation",
					'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration de l\'apercu ?");'), false, false);
		    echo $html->link(SHY, $urlConvoc, array(
				'class'=>'link_convocation',
				'title'=>'G�n�rer la liste des convocations',
				'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration des documents ?");'), false, false);
			if (Configure::read('AFFICHE_CONVOCS_ANONYME'))
				echo $html->link(SHY, $urlOdjUnique, array(
					'class'=>'link_ordre_jour_unique',
					'title'=>"Apercu de l'ordre jour",
					'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration de l\'apercu ?");'), false, false);
			echo $html->link(SHY, $urlOdj, array(
					'class'=>'link_ordre_jour',
					'title'=>'G�n�rer l\'ordre du jour d�taill�',
					'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration des documents ?");'), false, false);
?>
		</td>
		<td class="actions">
			<?php echo $html->link(SHY,'/seances/saisirSecretaire/' . $seance['Seance']['id'], array('class'=>'link_secretaire', 'title'=>'Choix du secr�taire de s�ance'), false, false); ?>
			<?php echo $html->link(SHY,'/seances/saisirDebatGlobal/' . $seance['Seance']['id'], array('class'=>'link_debat', 'title'=>'Saisir les d�bats g�n�raux de la s�ance'), false, false); ?>
			<?php
				if ($seance['Typeseance']['action']==0)
					echo $html->link(SHY,'/seances/details/' . $seance['Seance']['id'],array('class'=>'link_voter', 'title'=>'Afficher les projets et voter'), false, false);
				elseif ($seance['Typeseance']['action']==1)
					echo $html->link(SHY,'/seances/detailsAvis/' . $seance['Seance']['id'],array('class'=>'link_donnerAvis', 'title'=>'Afficher les projets et donner un avis'), false, false);
				elseif ($seance['Typeseance']['action']==2)
					echo $html->link(SHY,'/seances/details/' . $seance['Seance']['id'],array('class'=>'link_actes', 'title'=>'Afficher les projets'), false, false);

			echo $html->link(SHY,'/seances/saisirCommentaire/' . $seance['Seance']['id'], array('class'=>'link_commentaire_seance', 'title'=>'Saisir un commentaire pour la s�ance'), false, false);
                      echo ('</td>');
                      echo ('<td class="actions">');
                      if ($canSign) 
	                  echo $html->link(SHY,'/deliberations/sendToParapheur/' . $seance['Seance']['id'].'/', 
                                           array('class'=>'link_signer', 
                                                 'title'=>'Envoi au parapheur �lectronique'), null, false);

			echo $html->link(SHY,'/models/generer/null/' . $seance['Seance']['id'].'/'.$seance['Typeseance']['modelpvsommaire_id'].'/null/0/retour/0/true', array(
				'class'=>'link_pvsommaire',
				'title'=>'Generation du pv sommaire',
				'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration des documents ?");'),  false, false);
			echo $html->link(SHY,'/models/generer/null/' . $seance['Seance']['id'].'/'.$seance['Typeseance']['modelpvdetaille_id'].'/null/0/retour/0/true', array(
				'class'=>'link_pvcomplet',
				'title'=>'Generation du pv complet',
				'onClick'=>'return avantGeneration("Etes-vous sur de vouloir lancer la g�n�ration des documents ?");'), false, false);

                      echo $html->link(SHY,'/seances/clore/' . $seance['Seance']['id'],  array('class'=>'link_clore_seance', 'title'=>'Cl�ture de la s�ance'),  'Etes-vous sur de vouloir cl�turer la s�ance ?', false);

			?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>

</div>
<script type="text/javascript">

function overlayResize() {
	var overlayEle = $('#overlay');
	if (overlayEle.length > 0) {
		ovPosition = $('#centre').offset();
		ovHeight = $('#centre').outerHeight();
		ovWidth = $('#centre').outerWidth();
		overlayEle
			.css('left', ovPosition.left)
			.css('top', ovPosition.top)
			.width(ovWidth)
			.height(ovHeight);
	}
}
function overlayOn() {
	$('<div></div>').appendTo(document.body).attr('id', 'overlay');
	overlayResize();
}
function avantGeneration(message) {
	if (confirm(message)) {
		$('<div></div>').appendTo(document.body).attr('id', 'overlay');
		overlayResize();
		return true;
	} else
		return false
}

</script>