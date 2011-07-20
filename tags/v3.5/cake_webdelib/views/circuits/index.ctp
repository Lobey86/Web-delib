<?php echo $javascript->link('fonctions'); ?>
<h2>Nouveau circuit</h2>
<?php
	$loc=$html->url("/circuits/index/");
    echo $form->input('Circuit.libelle', array('options'=>$circuits, 'default'=>$circuit_id, 'div'=>false, "label"=> 'Libelle du circuit', "onChange"=>"lister_circuits(this, '$loc');", 'empty'=>true));
    echo " <a href=".$html->url("/circuits/add")." class=\"link_add\"> Ajouter un circuit</a> ";
    if ($circuit_id>0) echo " <a href=".$html->url("/circuits/edit/".$circuit_id)." class=\"link_modifier_left\"> Editer le circuit</a> ";
	echo "<br /><br />";
    if ($lastPosition ==0)
        echo " <a href=".$html->url("/circuits/delete/$circuit_id")."> Supprimer ce circuit</a>";
?>

<!-- donn�es concernant le circuit selectionn� -->
   <table>

   <th>service libell&eacute;</th>
   <th>pr�nom</th>
   <th>nom </th>
  <th>position</th>
   <th>&nbsp;</th>
   <th>&nbsp;</th>
   <th>&nbsp;</th>
   
    <tr>
	<?php 
    
	if (isset($listeUserCircuit)) {
		for ($i=0; $i<count($listeUserCircuit['id']); $i++){
	    	echo("<tr>");   
	        echo("<td>".$listeUserCircuit['service_libelle'][$i]."</td>");
	        echo("<td>".$listeUserCircuit['prenom'][$i]."</td>");
	        echo("<td>".$listeUserCircuit['nom'][$i]."</td>");
	        echo("<td>".$listeUserCircuit['position'][$i]."</td>");

		if ($isEditable){
	        $loc="/circuits/intervertirPosition/";
	        if ($listeUserCircuit['position'][$i]!= $lastPosition)
	         	echo ("<td class=\"actions\">".$html->link(SHY, $loc.$listeUserCircuit['id'][$i].'/0/',array('class'=>'link_descendre','title'=>'Descendre'), false, false));
	       else
	            echo("<td class=\"actions\">&nbsp;</td>");
	        if ($listeUserCircuit['position'][$i]!='1')
	        	echo ("<td class=\"actions\">".$html->link(SHY, $loc.$listeUserCircuit['id'][$i].'/1/',array('class'=>'link_monter','title'=>'Monter'), false, false));
	        else
	            echo("<td class=\"actions\">&nbsp;</td>");
			$loc="/circuits/supprimerUser/";
	       echo ("<td class=\"actions\">".$html->link(SHY, $loc.$listeUserCircuit['id'][$i].'/',array('class'=>'link_supprimer','title'=>'Supprimer'), false, false));
	        echo("</tr>");
		}
		}
	}
	?>
	</table>



<br /><br />
<?php
	$loc=$html->url("/circuits/index/$circuit_id/");
	echo $form->input('Service.libelle', array('label'=>'Service', 'options'=>$services, 'default'=>$service_id, "onChange"=>"lister_services(this, '$loc');", 'onclick'=>"javascript:checkSelectedCircuit('".$circuit_id."')", 'empty'=>true, 'escape'=>false));
?>
    <table>
    <tr>
	<?php 
	$loc=$html->url("/circuits/addUser/$circuit_id/$service_id/");
	if (isset($listeUser)) {
		for ($i=0; $i<count($listeUser['id']); $i++){
	    	echo("<tr>");   
		if ($service_id != -1)
	             echo ("<td>".$listeUser['id'][$i]."</td>");
		else
		      echo ("<td>&nbsp;</td>");
	        echo("<td>".$listeUser['prenom'][$i]."</td>");
	        echo("<td>".$listeUser['nom'][$i]."</td>");
	        echo("<td><a href='".$loc.$listeUser['id'][$i]."/'>Ajouter au circuit</a></td>");
	        echo("</tr>");
	    }
	}
	?>
	</table>

<br /><br /><br /><br />

