<h2>Modification de la collectivit&eacute;</h2>
<?php echo $this->Form->create('Collectivite',array('url'=>'/collectivites/edit/'.$this->Html->value('Collectivite.id'),'type'=>'file')); ?>
<div class="optional"> 

<?php 
    if (isset($entities))
        echo $this->Form->input('Collectivite.id_entity', array('options'=>$entities, 'selected'=> $selected, 'label' => 'Nom :'));
    else
        echo $this->Form->input('Collectivite.nom',array('label'=>'Nom de la collectivité'));?>
</div>
<br />
<div class="optional">
 		<?php echo $this->Form->input('Collectivite.adresse', array('label'=>'Adresse','size' => '30'));?>
	<br />
 		<?php echo $this->Form->input('Collectivite.CP', array('label'=>'Code Postal'));?>
	<br />
	 	<?php echo $this->Form->input('Collectivite.ville', array('label'=>'Ville'));?>
</div>
<br />
<div > 
 		<?php echo $this->Form->input('Collectivite.telephone', array('label'=>'Num téléphone'));?>
</div>
<br/><br/><br/><br/><br/>
<div class="submit">
	<?php echo $this->Form->hidden('Collectivite.id')?>
	<?php echo $this->Form->submit('Modifier', array('div'=>false, 'class'=>'bt_save_border', 'name'=>'Ajouter'));?>
	<?php echo $this->Html->link('Annuler', '/collectivites/index', array('class'=>'link_annuler', 'name'=>'Annuler'))?>
</div>
<?php echo $this->Form->end(); ?>