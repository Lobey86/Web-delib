<html>
<head><title>Classification</title></head>
<body>
<?php echo $this->Html->script('utils.js'); ?>
<h2>Choisir la classification</h2>

<div id="attribute_list">
<?php
	if (!isset($_GET['id'])) {
        foreach ($classification as $key=>$value) {
	        $val=addslashes(utf8_encode($value));
	        echo $this->Html->link($key.' - '.utf8_encode($value),'#add',array('onclick'=>"javascript:returnChoice('$key - $val','$key');", 'id'=>$key, 'name'=>$key, 'value'=>$key));
	        echo '<br/>';
        }
    }
    else {
    	$id = $_GET['id'];
    	foreach ($classification as $key=>$value) {
	        $val=addslashes(utf8_encode($value));
	       echo $this->Html->link($key.' - '.utf8_encode($value),'#add',array('onclick'=>"javascript:return_choice_lot('$key - $val','$key',$id);", 'id'=>$key, 'name'=>$key, 'value'=>$key));
	        echo '<br/>';
    	}
    }
?>
<br/>
<?php echo $this->Html->link('Fermer la fenêtre','#add',array('onclick'=>"javascript:window.close();")); ?>
</div>
</body>
</html>
