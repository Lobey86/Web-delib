<div class="seances">
<h2>Information de votre collectivit&eacute;</h2>

<table cellpadding="0" cellspacing="0">
<tr>
	<th>Collectivit�</th>
	<th>Logo</th>
	<th>Actions</th>
</tr>
<tr>
	<td style="text-align:center"><?php echo $collectivite['0']['Collectivite']['nom']; ?>
		<br/><br/><?php echo $collectivite['0']['Collectivite']['adresse']; ?>
		<br/><?php echo $collectivite['0']['Collectivite']['CP'].' '.$collectivite['0']['Collectivite']['ville']; ?>
		<br/><br/><?php echo $collectivite['0']['Collectivite']['telephone']; ?>
	</td>
	<td><?php  echo $html->image($logo_path);?></td>
	<td  class="actions">
		<?php echo $html->link(SHY,'/collectivites/edit/1', array('class'=>'link_modifier', 'title'=>'Modifier'), false, false)?>

		<?php echo $html->link(SHY,'/collectivites/setLogo/0', array('class'=>'link_inserer_logo', 'title'=>'Ins�rer Logo'), false, false)?>
		<?php //echo $html->link(SHY,'/collectivites/setMails/0', array('class'=>'link_param_mail', 'title'=>'Parametrage des mails'), false, false)?>
	</td>

</tr>
</table>

</div>