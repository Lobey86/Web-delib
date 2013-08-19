<div id="vue_cadre">
<h3>Fiche utilisateur</h3>

<dl>

	<div class="tiers">
		<dt>Login</dt>
		<dd>&nbsp;<?php echo $user['User']['login']?></dd>
	</div>
	<div class="tiers">
		<dt>Nom</dt>
		<dd>&nbsp;<?php echo $user['User']['nom']?></dd>
	</div>
	<div class="tiers">
		<dt>Pr&eacute;nom</dt>
		<dd>&nbsp;<?php echo $user['User']['prenom']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="tiers">
		<dt>Telephone fixe</dt>
		<dd>&nbsp;<?php echo $user['User']['telfixe']?></dd>
	</div>
	<div class="tiers">
		<dt>Telephone mobile</dt>
		<dd>&nbsp;<?php echo $user['User']['telmobile']?></dd>
	</div>
	<div class="tiers">
		<dt>E-mail</dt>
		<dd>&nbsp;<?php echo $user['User']['email']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="tiers">
		<dt>Notification mail</dt>
		<dd>&nbsp;<?php echo $user['User']['accept_notif'] ? 'Oui' : 'Non'; ?></dd>
	</div>
	<div class="tiers">
		<dt>Profil</dt>
		<dd>&nbsp;<?php echo $user['Profil']['libelle']?></dd>
	</div>
	<div class="tiers">
		<dt>Service(s)</dt>
		<?php
			foreach ($user['Service'] as $service){
				echo '<dd>&nbsp;';
					echo $service['libelle'].'<br/>';
				echo '</dd>';
			};
		?>
	</div>
	<div class="spacer"></div>

	<div class="tiers">
		<dt>Circuit par d&eacute;faut</dt>
		<dd>&nbsp;<?php echo $circuitDefautLibelle?></dd>
	</div>
	<div class="tiers">
		<dt>Note</dt>
		<dd>&nbsp;<?php echo $user['User']['note']?></dd>
	</div>
	<div class="spacer"></div>

	<div class="tiers">
		<dt>Date de cr&eacute;ation</dt>
		<dd>&nbsp;<?php echo $user['User']['created']?></dd>
	</div>
	<div class="tiers">
		<dt>Date de modification</dt>
		<dd>&nbsp;<?php echo $user['User']['modified']?></dd>
	</div>
	<div class="spacer"></div>

</dl>

<div class='btn-group' style='text-align: center;'>
	<?php
                $this->Html2->boutonRetour('index', 'float:none;');
		if ($Droits->check($this->Session->read('user.User.id'), 'Users:edit'))
                    $this->Html2->boutonModifierUrl('/users/edit/' . $user['User']['id'], 'Modifier', 'Modifier', 'float:none;', '');
	?>
</div>
<div class="spacer"></div>
</div>
