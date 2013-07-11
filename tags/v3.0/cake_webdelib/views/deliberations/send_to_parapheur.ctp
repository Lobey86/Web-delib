<?php echo $javascript->link('utils.js'); ?>
<div class="deliberations">
<?php if (isset($message))  echo ($message); ?>
<h2>D&eacute;lib&eacute;rations envoy&eacute;es &agrave; la signature</h2>
<?php echo $form->create('Deliberation',array('url'=>'/deliberations/sendToParapheur','type'=>'file')); ?>
<table>
	<th></th>
 	<th>Num�ro D&eacute;lib&eacute;ration</th>
 	<th>Objet</th>
 	<th>Titre</th>
 	<th>statut</th>
<tr>
<?php
	foreach ($deliberations as $delib) {
		if ($delib['Deliberation']['etat_parapheur']==null)
		    echo("<td>".$form->checkbox('Deliberation.id_'.$delib['Deliberation']['id'])."</td>");
		else
		    echo("<td></td>");
                echo "<td>".$html->link($delib['Deliberation']['num_delib'], '/deliberations/downloadDelib/'.$delib['Deliberation']['id']);
		?>
		</td>
		<td>
		<?php echo ($delib['Deliberation']['objet']) ; ?>
		</td>
		<td>
		<?php echo ($delib['Deliberation']['titre']); ?>
		</td>

		   <?php
		        if ($delib['Deliberation']['etat_parapheur']==1) {
			   echo  ("<td>En cours de signature</td>");
			}
		        elseif($delib['Deliberation']['etat_parapheur']==2) {
			   $delib_id = $delib['Deliberation']['id'];
			   echo  ("<td><a href='/deliberations/downloadSignature/$delib_id'>D�lib�ration sign�e</a></td>");
		        }
			else{
 		            echo("<td>&nbsp;</td>");
			}
		   ?>
		</tr>
<?php	 } ?>

	</table>
	<br />
        <?php  echo ('Circuit : '); ?>
        <?php  echo ($form->input('Deliberation.circuit_id', array('options'=>$circuits)).'<br /><br />'); ?>
	<div class="submit">
		<?php echo $form->submit('Envoyer',array('div'=>false));?>
	</div>

<?php echo $form->end(); ?>
</div>