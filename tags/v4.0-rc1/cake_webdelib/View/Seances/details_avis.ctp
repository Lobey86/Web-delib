<div class="deliberations">
<h2>Détails des projets de la séance du <?php echo $date_seance?></h2>

<table width='100%' cellpadding="0" cellspacing="0">
<tr>
    <th>Résultat</th>
	<th>Theme</th>
	<th>Service emetteur</th>
	<th>Rapporteur</th>
	<th>Libellé de l'acte</th>
	<th>Titre</th>
	<th width='20%'>Actions</th>
</tr>
<?php foreach ($deliberations as $deliberation): ?>
<tr height='36px'>
	<td>
	<?php
        if ($deliberation['Deliberation']['avis']==1)
            echo $this->Html->image('/img/icons/thumbs_up.png', array('title'=>'Avis favorable'));
  	    elseif ($deliberation['Deliberation']['avis']==2)
    	    echo $this->Html->image('/img/icons/thumbs_down.png', array('title'=>'Avis défavorable'));
	?>
	</td>
	<td><?php echo $deliberation['Theme']['libelle']; ?></td>
	<td><?php echo $deliberation['Service']['libelle']; ?></td>
	<td><?php echo $deliberation['Rapporteur']['nom'].' '.$deliberation['Rapporteur']['prenom']; ?></td>
	<td><?php echo $deliberation['Deliberation']['objet_delib']; ?></td>
	<td><?php //echo $deliberation['Deliberation']['titre']; ?></td>
	<td class="actions" width="80">
		<?php echo $this->Html->link(SHY,
                                       '/seances/saisirDebat/'.$deliberation['Deliberation']['id'], 
                                       array('class'=>'link_debat', 
                                             'title'=>'Saisir les debats'), 
                                       false, 
                                       false); ?>
 		<?php echo $this->Html->link(SHY,
                                       '/seances/donnerAvis/'.$deliberation['Deliberation']['id']."/$seance_id", 
                                       array('class'=>'link_donnerAvis', 
                                             'title'=>'Donner un avis'), 
                                       false, 
                                       false)?>
               <?php 
			if (Configure::read('USE_GEDOOO'))
			    echo $this->Html->link(SHY,
                                             '/models/generer/'.$deliberation['Deliberation']['id'].'/null/'.$deliberation['Model']['id'], 
                                             array('class'=>'link_pdf', 
                                                   'title'=>'Visionner PDF'), 
                                             false, 
                                             false);
			else 
			    echo $this->Html->link(SHY, 
                                             '/deliberations/convert/'.$deliberation['Deliberation']['id'], 
                                             array('class'=>'link_pdf',  
                                                   'title'=>'Visionner PDF'), 
                                             false, 
                                             false);
		?>

</td>
</tr>
<?php endforeach; ?>
</table>

</div>
<br/>
<div class="submit">
    <?php echo $this->Html->link('Retour', 
                           '/seances/listerFuturesSeances', 
                           array('class'=>'link_annuler', 
                                 'name'=>'Retour'))?>
</div>