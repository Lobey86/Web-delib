<div class="seances">
<h2>S&eacute;ances trait&eacute;es</h2>


<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<th>Type</th>
		<th>Date S&eacute;ance</th>
		<th>Action</th>
	</tr>
	<?php foreach ($seances as $seance): ?>
	<tr>
		<td><?php echo $seance['Typeseance']['libelle']; ?></td>
		<td><?php echo $seance['Seance']['date']; ?></td>
		<td class="actions">
			<?php echo $html->link(SHY,'/seances/saisirDebatGlobal/' . $seance['Seance']['id'], array('class'=>'link_debat', 'title'=>'Saisir les d�bats g�n�raux de la s�ance'), false, false); ?>
		</td>
	</tr>
	<?php endforeach; ?>

</table>

</div>