<h2>Liste des services</h2>

<div id="arbre">
<?php echo $tree->showTree('Service','libelle', $data,0,$this->base, array('Editer'=>'edit','Supprimer'=>'delete'), 'order'); ?>
</div>

<div>
<?php echo $html->link('Ajouter un Service', '/services/add', array('class'=>'link_add', 'title'=>'Ajouter')); ?>
</div>