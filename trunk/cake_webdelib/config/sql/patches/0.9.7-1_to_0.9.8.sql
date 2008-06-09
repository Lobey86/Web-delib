ALTER TABLE `models` ADD `name` VARCHAR( 255 ) NULL ;
ALTER TABLE `models` ADD `size` INT( 11 ) NOT NULL ;
ALTER TABLE `models` ADD `extension`  VARCHAR( 255 ) NULL ;
ALTER TABLE `models` CHANGE `texte` `content` LONGBLOB NOT NULL;

ALTER TABLE `models` ADD `modele` VARCHAR( 50 ) NOT NULL AFTER `id` ;

ALTER TABLE `deliberations` ADD `texte_projet_size` INT( 11 ) NOT NULL ;
ALTER TABLE `deliberations` ADD `texte_projet_type`  VARCHAR( 255 ) NULL ;
ALTER TABLE `deliberations` ADD `texte_projet_name`  VARCHAR( 255 ) NULL ;
ALTER TABLE `deliberations` ADD `texte_synthese_name`  VARCHAR( 255 ) NULL ;
ALTER TABLE `deliberations` ADD `texte_synthese_size` INT( 11 ) NOT NULL ;
ALTER TABLE `deliberations` ADD `texte_synthese_type`  VARCHAR( 255 ) NULL ;
ALTER TABLE `deliberations` ADD `deliberation_size` INT( 11 ) NOT NULL ;
ALTER TABLE `deliberations` ADD `deliberation_type`  VARCHAR( 255 ) NULL ;
ALTER TABLE `deliberations` ADD `deliberation_name`  VARCHAR( 255 ) NULL ;