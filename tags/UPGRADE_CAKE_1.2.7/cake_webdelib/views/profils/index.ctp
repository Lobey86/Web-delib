<h2>Liste des profils des utilisateurs</h2>

<div id="arbre">
	<?php echo $tree->showTree('Profil', 'libelle', $data, 0, $this->base, array('Editer'=>'edit', 'Supprimer'=>'delete')); ?>
</div>

<div>
	<?php echo $html->link('Ajouter un profil', '/profils/add', array('class'=>'link_add', 'title'=>'Ajouter')); ?>
</div>
