<div class="deliberations">
<?php echo $javascript->link('utils.js'); ?>
<?php
    if ((@$this->params['filtre'] != 'hide' ) &&
        ($this->params['action'] !='mesProjetsRecherche') &&
        ($this->params['action'] !='tousLesProjetsRecherche') )
        echo $this->element('filtre');
?>


<?php if (isset($message))  echo ($message); ?>
<h2>T&eacute;l&eacute;transmission des d&eacute;lib&eacute;rations</h2>
<?php echo $form->create('Deliberation',array('type'=>'file','url'=>'/deliberations/sendActe')); ?>
    La Classification enregistr�e date du <?php echo $html->link($dateClassification,'/deliberations/getClassification/', array('title'=>'Date classification'))?><br /><br />
	<table width='100%'>
<tr>
	<th></th>
 	<th>Num�ro G�n�r�</th>
 	<th>Libell� de l'acte</th>
 	<th>Titre</th>
 	<th>Classification</th>
 	<th>statut</th>
</tr>
<?php
           $numLigne = 1;
           foreach ($deliberations as $delib) {
		             $rowClass = ($numLigne & 1)?array('height' => '36px'):array( 'height' => '36px', 'class'=>'altrow');
	          echo $html->tag('tr', null, $rowClass);
	          $numLigne++;

		if ($delib['Deliberation']['etat']!= 5)
			echo("<td>".$form->checkbox('Deliberation.id_'.$delib['Deliberation']['id'])."</td>");
		else
		    echo("<td></td>");

                echo "<td>".$html->link($delib['Deliberation']['num_delib'], '/deliberations/downloadDelib/'.$delib['Deliberation']['id']);
		?>
		</td>
		<td><?php echo $delib['Deliberation']['objet_delib']; ?></td>
		<td><?php echo $delib['Deliberation']['titre']; ?></td>

		<td><?php echo $form->input('Deliberation.'.$delib['Deliberation']['id'].'_num_pref',array('label'=>false, 'div'=>false, 'id'=>$delib['Deliberation']['id'].'classif1', 'size' => '60','disabled'=>'disabled', 'value' => $delib['Deliberation'][$delib['Deliberation']['id'].'_num_pref'] ));?><br/>
		<a class="list_form" href="#add" onclick="javascript:window.open('<?php echo $this->base;?>/deliberations/classification?id=<?php echo $delib['Deliberation']['id'];?>', 'Classification', 'scrollbars=yes,,width=570,height=450');" id="<?php echo $delib['Deliberation']['id']; ?> _classification_text">[Choisir la classification]</a>
		 <?php 
		         echo $form->hidden('Deliberation.'.$delib['Deliberation']['id'].'_num_pref',array('id'=>$delib['Deliberation']['id'].'classif2','name'=>$delib['Deliberation']['id'].'classif2')); 
                 ?>
		 </td>
		   <?php
		        if ($delib['Deliberation']['etat']== 5) {
			   $tdt_id = $delib['Deliberation']['tdt_id'];
			   echo  ("<td><a href='https://$host/modules/actes/actes_transac_get_status.php?transaction=$tdt_id'>envoye</a></td>");
			}
		        else
 		            echo("<td>non envoy�</td>");
		   ?>
		</tr>
<?php	 } ?>

	</table>
	<br />

	<div class="submit">
		<?php echo $form->submit('Envoyer',array('div'=>false));?>
	</div>

<?php echo $form->end(); ?>
</div>