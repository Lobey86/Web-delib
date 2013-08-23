<div class="typeseances">
<h2>Mod�les d'�dition</h2>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>Mod&egrave;le</th>
	<th>Type</th>
	<th>Nom du mod&egrave;le</th>
	<th>Recherche</th>
	<th>Derni�re modification</th>
	<th>Actions</th>
</tr>
<?php foreach ($models as $model): ?>
<tr height="36px">
	<td><?php echo $model['Model']['modele']; ?></td>
	<td><?php echo $model['Model']['type']; ?></td>
	<td><?php echo $model['Model']['name']; ?></td>
	<td><?php  if ($model['Model']['recherche']) 
                       echo 'Oui';
                   else
                       echo 'Non';
         ?></td>
	<td><?php if ($model['Model']['modified']!= 0)
                   echo $model['Model']['modified']; 
         ?></td>
	<td class="actions">
            <?php echo $html->link(SHY,'/models/view/' . $model['Model']['id'], array('class'=>'link_voir', 'title'=>'Voir'), false, false)?>
	    <?php
	      if ($USE_GEDOOO && ($model['Model']['type'] == 'Document'))
		     echo $html->link(SHY,'/models/import/' . $model['Model']['id'], array('class'=>'link_modifier', 'title'=>'Modifier'), false, false);
              else
	             echo $html->link(SHY,'/models/edit/' . $model['Model']['id'], array('class'=>'link_modifier', 'title'=>'Modifier'), false, false);
	    ?>
	    <?php
	         if ( ($model['Model']['type'] == 'Document') && ($model['Model']['id'] != 1) && ($deletable[$model['Model']['id']]))
		     	echo $html->link(SHY,'/models/delete/' . $model['Model']['id'], array('class'=>'link_supprimer', 'title'=>'Supprimer'), "Confirmer la suppression du mod�le d'�dition ?", false);
            ?>
	</td>
</tr>
<?php endforeach; ?>
</table>
    <ul class="actions">
	<li><?php echo $html->link('Ajouter', '/models/add/', array('class'=>'link_add', 'title'=>'Ajouter un modele')); ?></li>
    </ul>
</div>