function lister_services(params, url)
{
	var urlb=url+params.value;
	document.location=urlb;
}

function lister_circuits(params, url)
{
	$('.submit').hide();
        var circuit_id  = params.value;
        if (circuit_id == "" )
            circuit_id=0;
        
	var urlb=url+circuit_id+'/1';
	document.location=urlb;
}


function checkSelectedCircuit($id)
{
	if($id=="0")
	{
		alert('Vous devez d\'abord choisir un circuit');
	}
}
