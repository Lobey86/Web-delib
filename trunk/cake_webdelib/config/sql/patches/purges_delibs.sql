TRUNCATE TABLE `infosups`;
TRUNCATE TABLE `votes`;
TRUNCATE TABLE `seances`;
TRUNCATE TABLE `deliberations`;
TRUNCATE TABLE `annexes`;
TRUNCATE TABLE `traitements`;
TRUNCATE TABLE `commentaires`;
TRUNCATE TABLE `listepresences`;
UPDATE `sequences` SET `num_sequence` = '1' WHERE `sequences`.`id` =1 ;
