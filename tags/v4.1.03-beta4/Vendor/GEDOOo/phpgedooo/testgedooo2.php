<?php
require_once("GDO_wsdl.inc");
require_once("GDO_Utility.class");
require_once("GDO_FieldType.class");
require_once("GDO_ContentType.class");
require_once("GDO_DrawingType.class");
require_once("GDO_ShapeType.class");
require_once("GDO_IterationType.class");
require_once("GDO_PartType.class");
require_once("GDO_FusionType.class");
require_once("GDO_MatrixType.class");
require_once("GDO_MatrixRowType.class");
require_once("GDO_AxisTitleType.class");
//
// initialisation des donnée pour la démo
//
$developers[] = array("nom"=>"Kijewska", "prenom"=>"Christophe");
$developers[] = array("nom"=>"Allart", "prenom"=>"Philippe");

$shapes[] =  array("nom"=>"A", "style"=>"Bleu");
$shapes[] =  array("nom"=>"B", "style"=>"Rouge");
$shapes[] =  array("nom"=>"C", "style"=>"Jaune");

$projet = "GED'OOo";
$description="Sytème d'intégration de contenu prenant en copte la production de document, l'indexation, la recherche et l'archivage";
$standards="SOAP, WSDL, CMIS";

$sModele = "ressources/testgedooo.ott";
$sMimeType = "application/pdf";
//
// Organisation des données
//
$u = new GDO_Utility();

$oMainPart = new GDO_PartType();

$oMainPart->addElement(new GDO_FieldType("projet", $projet, "text"));
$oMainPart->addElement(new GDO_FieldType("description", $description, "text"));
$oMainPart->addElement(new GDO_FieldType("standards", $standards, "text"));

$oIteration = new GDO_IterationType("developers");

foreach($developers as $aDeveloper) {
	$oDevPart = new GDO_PartType();
	$oDevPart->addElement(new GDO_FieldType("nom", $aDeveloper["nom"], "text"));
	$oDevPart->addElement(new GDO_FieldType("prenom", $aDeveloper["prenom"], "text"));
	$bImage = $u->ReadFile("ressources/".$aDeveloper["nom"].".png");
	$oDevPart->addElement(new GDO_ContentType("photo", "", "image/png", 
							"binary", $bImage));
	$oIteration->addPart($oDevPart);
	}
	
$oMainPart->addElement($oIteration);

$oDrawing = new GDO_DrawingType("MonDessin");

foreach($shapes as $ashape) {
	$oDrawing->addShape(new GDO_ShapeType($aShape["nom"], $aShape["style"]));
	}
$oMainPart->addElement($oDrawing);


//
// Creation du contenu pour le modèle
// Ici on lit le document et on le charge dans l'objet
// Il est possible également de donner seulement une URL
//
$bTemplate = $u->ReadFile($sModele);
$oTemplate = new GDO_ContentType("", 
				"modele.ott", 
				$u->getMimeType($sModele), 
				"binary",
				$bTemplate);		
//
// Lancement de la fusion
//
$oFusion = new GDO_FusionType($oTemplate, $sMimeType, $oMainPart);
 $oFusion->setDebug();
$oFusion->process();

//
// Envoi du résultat
//
$oFusion->SendContentToClient();		
?>
