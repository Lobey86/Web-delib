<div class="users">
<h2>Liste des utilisateurs</h2>

<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<th><?php echo $this->Paginator->sort('login', 'Login'); ?></th>
	<th><?php echo $this->Paginator->sort('nom', 'Nom'); ?></th>
	<th><?php echo $this->Paginator->sort('prenom', 'Prénom'); ?></th>
	<th><?php echo $this->Paginator->sort('Profil.libelle', 'Profil'); ?></th>
	<th>Téléphone</th>
	<th>Mobile</th>
	<th>Service</th>
	<th>Type d'acte</th>
	<th width='20%'>Actions</th>
</tr>
<?php

foreach ($users as $user):?>
<tr height="36px">
	<td><?php echo $user['User']['login']; ?></td>
	<td><?php echo $user['User']['nom']; ?></td>
	<td><?php echo $user['User']['prenom']; ?></td>
	<td><?php echo $user['Profil']['libelle']; ?></td>
	<td><?php echo $user['User']['telfixe']; ?></td>
	<td><?php echo $user['User']['telmobile']; ?></td>
	<td><?php
	        foreach ($user['Service'] as $service)
                    if (is_array($service))
		        echo $service['libelle'].'<br/>';
	?></td>
       <td><?php
                if ( isset($user['Natures']) && (!empty($user['Natures']))  ) {
                    foreach ($user['Natures'] as $nature)
                        echo("$nature<br />");
                }
        ?></td>


	<td class="actions">
		<?php echo $this->Html->link(SHY,'/users/view/' . $user['User']['id'], array('class'=>'link_voir',	'escape' => false, 'title'=>'Voir'), false)?>
		<?php echo $this->Html->link(SHY,'/users/edit/' . $user['User']['id'], array('class'=>'link_modifier', 'escape' => false, 'title'=>'Modifier'), false)?>
		<?php echo $this->Html->link(SHY,'/users/changeMdp/' . $user['User']['id'], array('class'=>'link_mdp', 'escape' => false, 'title'=>'Nouveau mot de passe'), false)?>

		<?php
		    if ($user['User']['is_deletable'])
		        echo $this->Html->link(SHY,'/users/delete/' . $user['User']['id'], array('class'=>'link_supprimer','escape' => false, 'title'=>'Supprimer'), 'Etes-vous sur de vouloir supprimer l\'utilisateur "' . $user['User']['prenom'] . ' ' . $user['User']['nom'] .'" ?')?>
	</td>
</tr>
<?php endforeach; ?>
</table>

<div class='paginate'>
	<!-- Affiche les numéros de pages -->
	<?php echo $this->Paginator->numbers(); ?>
	<!-- Affiche les liens des pages précédentes et suivantes -->
	<?php
		echo $this->Paginator->prev('« Précédent ', null, null, array( 'tag' => 'span', 'class' => 'disabled'));
		echo $this->Paginator->next(' Suivant »', null, null, array( 'tag' => 'span', 'class' => 'disabled'));
	?> 
	<!-- Affiche X de Y, où X est la page courante et Y le nombre de pages -->
	<?php echo $this->Paginator->counter(array('format'=>'Page %page% sur %pages%')); ?>
</div>

<ul class="actions">
	<li><?php echo $this->Html->link('Ajouter', '/users/add/', array('class'=>'link_add', 'title'=>'Ajouter un utilisateur')); ?></li>
</ul>
</div>
