<h2>Liste des th�mes</h2>

<div id="arbre">
<?php echo $tree->showTree('Theme','libelle', $data,0,$this->base, array('Editer'=>'edit','Supprimer'=>'delete'), 'order'); ?>
</div>

<div>
<?php echo $html->link('Ajouter un th�me', '/themes/add', array('class'=>'link_add', 'title'=>'Ajouter')); ?>
</div>