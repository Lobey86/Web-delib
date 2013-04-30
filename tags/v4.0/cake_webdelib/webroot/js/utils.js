window.onload=function(){
//	montre();
	afficheOngletEnErreur(
)};

function montre(id) {
	var d = document.getElementById(id);
		for (var i = 1; i<=15; i++) {
			if (document.getElementById('smenu'+i)) {document.getElementById('smenu'+i).style.display='none';}
		}
	if (d) {d.style.display='block';}
}

function OuvrirFenetre(url,nom,detail) {
	var w = window.open(url,nom,detail);
}

function FermerFenetre(){
	var choix = confirm("Voulez-vous fermer la fenetre ?");
	if (choix)  window.close();
}
function FermerFenetre2(){
	var choix = confirm("Voulez-vous fermer la fenetre ?");
	if (choix)  history.back();
}
function returnChoice(text,arg) {

    var1 = 'classif1';
    var2 = 'classif2';
    elt1 = window.opener.document.getElementById(var1);
    elt2 = window.opener.document.getElementById(var2);

    if (text){
    	elt1.value = text;
    	elt2.value = arg;
    } else {
      elt1.value = '';
      elt2.value = '';
    }
	var a = window.opener.document.getElementById('classification_text');
  	a.firstChild.nodeValue = '[Modifier la classification]';

  window.close();
}

function return_choice_lot(text,arg,delibId) {

    var1 = delibId+'classif1';
    var2 = delibId+'classif2';
    elt1 = window.opener.document.getElementById(var1);
    elt2 = window.opener.document.getElementById(var2);
    if (text){
    	elt1.value = text;
    	elt2.value = arg;
    } else {
      elt1.value = '';
      elt2.value = '';
    }
	var a = window.opener.document.getElementById(delibId+'_classification_text');
  	//a.firstChild.nodeValue = '[Changer la localisation]';
    window.close();
}

function disable(id,val)
{
  if (val==1)
  	document.getElementById(id).disabled=true;
  else
      document.getElementById(id).disabled=false;
}


function changeService(params)
{
	var url = params.id+"services/changeService/"+params.value;
	document.location=url;
}


function changeFormat(params)
{
    var url = params.id+"users/changeFormat/"+params.value;
    document.location=url;
}

function add_field(num) {

	var a = document.getElementById('lien_annexe');
	a.firstChild.nodeValue = 'Joindre une autre annexe';

  if(navigator.appName=='Microsoft Internet Explorer'){
  	var br = document.createElement('br');
 	var d = document.getElementById('cible'+num);
	var p = document.createElement("p");
	d.appendChild(p);
	var n = d.childNodes.length;
 	p.id = n;
  }

 else {
	var d = document.getElementById('cible'+num);
	var p = document.createElement("p");
	var n = d.childNodes.length;
	var br = document.createElement('br');
	p.id = n;
 	d.appendChild(p);
 }

	var div2 = document.createElement('div');
	var input2 = document.createElement('input');
	input2.id = 'AnnexeFile_'+n;
	input2.type = 'file';
	input2.size = '40';
	input2.name = num+'_file_'+n;
	div2.appendChild(input2);
	p.appendChild(div2);

//	var link = document.createElement('a');
//	link.id = 'Lien_'+n;
//	link.href = 'javascript:del_field('+n+')';
//	text = document.createTextNode('Supprimer');
//	link.appendChild(text);
//	p.appendChild(br);
//	p.appendChild(link);

	document.getElementById('cible'+num).style.visibility = 'visible';
}

function del_field(node)
{
	var node = document.getElementById(node);
	var parent = node.parentNode;
	parent.removeChild(node);


//changement du texte du lien lorsqu'il ne reste plus d'annexe

	var d = document.getElementById('cible');
	var n = d.childNodes.length;

	if (n<2) {
		var a = document.getElementById('lien_annexe');
		a.firstChild.nodeValue = 'Joindre une annexe';
	}
}

function checkForm (Form, id){
	if(Form.listeTypeactesId.value == "") {
		alert( "Le type d'acte est obligatoire");
                return false;
	}
	if(Form.DeliberationObjet.value == "") {
		alert( "Le libellé est obligatoire");
                return false;
	}
	if(Form.DeliberationThemeId.value == "") {
		alert( "Le thème est obligatoire");
                return false;
	}
	return true;
}

/******************************************************************************/
/* Gestion des onglets pour l'affichage des informations supplémentaires      */
/******************************************************************************/
function afficheOnglet(nOnglet) {
	for (var i = 1; i<=10; i++) {
		divTab = document.getElementById('tab'+i);
		lienTab = document.getElementById('lienTab'+i);
		if (divTab && lienTab) {
			if (i == nOnglet) {
				divTab.style.display = '';
				lienTab.className = 'ongletCourant';
			} else {
				divTab.style.display = 'none';
				lienTab.className = '';
			}
		}
	}
}

/******************************************************************************/
/* Fonction de suppression d'une information supplémentaire de type fichier   */
/******************************************************************************/
function infoSupSupprimerFichier(infoSupCode, titre) {
	/* Masque le nom du fichier et les liens */
	document.getElementById(infoSupCode+'AfficheFichier').style.display = 'none';
	/* Affiche le span pour l'affichage de l'input */
	var sInput = document.getElementById(infoSupCode+'InputFichier');
	sInput.style.display = '';
	/* Creation de l'input file */
	var inputFichier = document.createElement('input');
	inputFichier.type = 'file';
	inputFichier.id = 'Infosup'+infoSupCode;
	inputFichier.name = 'data[Infosup]['+infoSupCode+']';
	inputFichier.title = titre;
	inputFichier.size = '60';
	/* Ajoute l'input file au span */
	sInput.appendChild(inputFichier);
}

/******************************************************************************/
/* Fonction de suppression d'un fichier joint                                 */
/******************************************************************************/
function supprimerFichierJoint(modele, champ, titre) {
	/* Masque le nom du fichier et les liens */
	document.getElementById(modele+champ+'AfficheFichierJoint').style.display = 'none';
	/* Affiche le span pour l'affichage de l'input */
	var sInput = document.getElementById(modele+champ+'InputFichierJoint');
	sInput.style.display = '';
	/* Creation de l'input file */
	var inputFichier = document.createElement('input');
	inputFichier.type = 'file';
	inputFichier.id = modele+champ;
	inputFichier.name = 'data['+modele+']['+champ+']';
	inputFichier.title = titre;
	inputFichier.size = '60';
	/* Ajoute l'input file au span */
	sInput.appendChild(inputFichier);
}

/******************************************************************************/
/* Fonction pour eviter les doubles click                                     */
/******************************************************************************/
var clicAutorise = true;
function disableDiv(nameDiv) {
    if (clicAutorise){
        var targetElement;
	var loadingElement;
	targetElement = document.getElementById(nameDiv) ;
        loadingElement = document.getElementById('loading');
	if (targetElement.style.display == "none"){
	    targetElement.style.display = "" ;
            loadingElement.style.display = "none" ;
	} else {
	    targetElement.style.display = "none" ;
            loadingElement.style.display = "" ;
	}
    }
   clicAutorise = false;
}

/******************************************************************************/
/* Affichage le premier onglets qui comporte un objet de class classError     */
/******************************************************************************/
function afficheOngletEnErreur() {
	var classErreur = "error-message";
	for (var iTab = 1; iTab<=10; iTab++) {
		divTab = document.getElementById('tab'+iTab);
		if (divTab) {
			var divs = divTab.getElementsByTagName("div");
			for(var j = 0; j < divs.length; j++) {
				if(divs[j].className == classErreur) {
					afficheOnglet(iTab);
					return;
				}
			}
		} else {
			return;
		}
	}
}