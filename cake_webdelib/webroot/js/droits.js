/* Affiche ou masque les lignes de l1 � l2 et modifie l'image imgId */
function toggleLigne(l1, l2, imgId) {
	var trElement;
	var imgElement;
	var masquer;

	// Traitement de l'image du bouton imdId
	imgElement = document.getElementById(imgId);
	masquer = (imgElement.src.substr(imgElement.src.lastIndexOf('/')+1) == 'replier.png');
	imgElement.src = masquer ? '../img/icons/deplier.png' : '../img/icons/replier.png';

	// Parcours des lignes � traiter
	for(i=l1; i<=l2; i++) {
		lId = 'l' + i;
		trElement = document.getElementById(lId) ;

		if (masquer) trElement.style.display = 'none';
		else {
			imgId = 'togLigImg' + i;
			imgElement = document.getElementById(imgId);
			if (imgElement) imgElement.src = '../img/icons/replier.png';
			trElement.style.display = '';
		}

	}
};

/* Affiche ou masque les colonnes modifie l'image imgId */
function toggleCol(enTeteId, nbColonne, nbLigne, imgId) {
	var i, c, l, iLigEnTete, c1, c2;
	var eleId, cId, lId, Element, imgElement;
	var masquer;

	// Initialisations
	c1 = Number(enTeteId.substr(enTeteId.length-2))+1;
	c2 = c1 + nbColonne -1;

	// Traitement de l'image du bouton imdId
	imgElement = document.getElementById(imgId);
	masquer = (imgElement.src.substr(imgElement.src.lastIndexOf('/')+1) == 'replier.png');
	imgElement.src = masquer ? '../img/icons/deplier.png' : '../img/icons/replier.png';

	// traitement de la cellule courante et des cellules parentes du dessus
	iLigEnTete = (enTeteId.length - 1) / 2;
	Element = document.getElementById(enTeteId);
	nbColAMasquer = Element.colSpan -1;
	for(i=1; i<=iLigEnTete; i++) {
		eleId = enTeteId.substr(0, (i*2)+1);
		Element = document.getElementById(eleId);
		if (masquer) Element.colSpan = Element.colSpan - nbColAMasquer;
		else Element.colSpan = Element.colSpan + (c2-c1+1);
	}
	// Traitement des cellules filles du dessous
	if (masquer) masqueColRec(enTeteId, c1, c2);
	else afficheColRec(enTeteId, c1, c2);

	// Parcours des colonnes � traiter
	for(c=c1; c<=c2; c++) {
		cId = 'c' + c;
		// traitement des c�llules de la colonne
		for(l=1; l<=nbLigne; l++) {
			lId = 'l' + l;
			Element = document.getElementById(lId+cId) ;
			Element.style.display = (masquer) ? "none" : "";
		}
	}
};

/* Masque les c�lulles situ�es sous la cellule enTeteId de la colonne colDep � colFin */
function masqueColRec(enTeteId, colDep, colFin) {
	var i;
	var eleId;
	var Element;

	// Sort si la premi�re cellule sous enTeteId n'existe pas
	eleId = enTeteId + (colDep<10 ? '0' : '') + colDep;
	Element = document.getElementById(eleId);
	if (!Element) return;

	// Parcours des cellules
	for (i=colDep; i<=colFin; i++) {
		eleId = enTeteId + (i<10 ? '0' : '')+ i.toString();
		Element = document.getElementById(eleId);
		if (Element) {
			Element.style.display = "none";
			// traitement des sous c�lulles
			masqueColRec(eleId, i, colFin);
		}
	}
};

/* Affiche les c�lulles situ�es sous la cellule enTeteId de la colonne colDep � colFin */
function afficheColRec(enTeteId, colDep, colFin) {
	var i;
	var eleId;
	var Element, imgElement;
	var nbCelAff=0;

	// Sort si la premi�re cellule sous enTeteId n'existe pas
	eleId = enTeteId + (colDep<10 ? '0' : '') + colDep;
	Element = document.getElementById(eleId);
	if (!Element) return 0;

	// Parcours des cellules filles de enTeteId
	for (i=colDep; i<=colFin; i++) {
		eleId = enTeteId + (i<10 ? '0' : '')+ i.toString();
		Element = document.getElementById(eleId);
		if (Element) {
			Element.style.display = "";
			nbCelAff++;
			// traitement des sous c�lulles
			imgId = eleId + 'Img';
			imgElement = document.getElementById(imgId);
			if (imgElement) {
				nbColSpan = afficheColRec(eleId, i, colFin);
				nbCelAff += nbColSpan - 1;
				Element.colSpan = nbColSpan;
				if (imgElement.src.substr(imgElement.src.lastIndexOf('/')+1) == 'deplier.png') {
					imgElement.src = '../img/icons/replier.png';
				};
			}
			else afficheColRec(eleId, i, i);
		}
	}
	return nbCelAff;
};

/* Affiche les lignes du profil s�lectionn� et masque les autres */
function filtreProfil(choix, nbLignesMax) {
	var lDeb, lFin;
	var lPlage;
	var trElement;

	// Initialisations
	if ((choix.value.length==0) || (choix.value==null)) {
		lDeb = 1;
		lFin = nbLignesMax;
	} else {
		lPlage = choix.value;
		lDeb = Number(lPlage.substr(0, lPlage.indexOf('-')));
		lFin = Number(lPlage.substr(lPlage.indexOf('-')+1));
	}

	// Parcours des lignes
	for (i=1; i<=nbLignesMax; i++) {

		lId = 'l' + i;
		trElement = document.getElementById(lId) ;

		if (i<lDeb || i>lFin) trElement.style.display = 'none';
		else {
			imgId = 'togLigImg' + i;
			imgElement = document.getElementById(imgId);
			if (imgElement) imgElement.src = '../img/icons/replier.png';
			trElement.style.display = '';
		}

	}
};

/* Affiche les colonnes du menu s�lectionn� et masque les autres */
function filtreMenu(choix, nbCol, nbLigne) {
	var c, c1, c2;
	var masquer;
	var Element;

	// Initialisations
	if ((choix.value.length==0) || (choix.value==null)) {
		c1 = 1;
		c2 = nbCol;
	} else
	{
		c1 = Number(choix.value.substr(0,choix.value.indexOf('-')));
		c2 = Number(choix.value.substr(choix.value.indexOf('-')+1));
	}

	// Parcours des colonnes
	for (c=1; c<=nbCol; c++) {
		masquer = (c<c1) || (c>c2);

		// Traitement de l'ent�te
		enTeteId = 'C' + (c<10 ? '0' : '') + c;
		Element = document.getElementById(enTeteId);
		if (Element) {
			if (masquer) {
				Element.style.display = "none";
				masqueColRec(enTeteId, c, nbCol);
			} else {
				Element.style.display = "";
				Element.colSpan = afficheColRec(enTeteId, c, nbCol);
				imgId = enTeteId + 'Img';
				imgElement = document.getElementById(imgId);
				if (imgElement) imgElement.src = '../img/icons/replier.png';
			}
		}

		// traitement des cellules de la colonne
		cId = 'c' + c;
		for(l=1; l<=nbLigne; l++) {
			lId = 'l' + l;
			Element = document.getElementById(lId+cId) ;
			Element.style.display = (masquer) ? "none" : "";
		}
	}
};

/* coche ou d�coche les checkBox des cellules comprises entres les colonnes c1 � c2 et entre les lignes l1 � l2*/
function toggleCheckBox(c1, c2, l1, l2) {
	var c, l;
	var cId, lId;
	var toggle;
	var Element, chkBoxEle;

	// Initialisations
	cId = 'c' + c1;
	lId = 'l' + l1;
	Element = document.getElementById(lId+cId) ;
	chkBoxEle = Element.getElementsByTagName('input')[0];
	toggle = chkBoxEle.checked;

	// Parcours des colonnes
	for(c=c1; c<=c2; c++) {
		cId = 'c' + c;
		// Parcours des lignes
		for(l=l1; l<=l2; l++) {
			lId = 'l' + l;
			Element = document.getElementById(lId+cId) ;
			chkBoxEle = Element.getElementsByTagName('input')[0];
			chkBoxEle.checked = toggle;
		}
	}

};

/* Pr�pare les variables et fait le submit du formulaire */
function appliquerModifications(nbCol, nbLigne) {
	var c, l;
	var d, cId, lId;
	var Element, chkBoxEle;

	// Construction de la chaine pour la transmition des droits
	d = '';
	for(l=1; l<=nbLigne; l++) {
		lId = 'l' + l;
		for(c=1; c<=nbCol; c++) {
			cId = 'c' + c;
			Element = document.getElementById(lId+cId) ;
			chkBoxEle = Element.getElementsByTagName('input')[0];
			d += chkBoxEle.checked ? '1' : '0';
		}
	}

	// initialisation de la chaine des droits
	Element = document.getElementById('DroitsStrDroits');
	Element.value = d;

	// envoi du submit
	Element = document.getElementById('frmAppliquer');
	Element.submit();
};

/* coche ou d�coche les checkBox des cellules comprises entres les colonnes c1 � c2 et entre les lignes l1 � l2*/
function toggleCheckBoxDroits(idCheckBox, nbCheckBox) {
	var checked = ($('#chkBoxDroits'+idCheckBox).prop('checked') === true);
	for(i=1; i<=nbCheckBox; i++) {
                $('#chkBoxDroits'+(idCheckBox+i)).prop('checked', checked);
	}
};
