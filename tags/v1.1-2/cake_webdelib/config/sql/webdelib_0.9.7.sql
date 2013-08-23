-- phpMyAdmin SQL Dump
-- version 2.10.3deb1ubuntu0.2
-- http://www.phpmyadmin.net
--
-- Serveur: localhost
-- Généré le : Ven 14 Mars 2008 à 15:19
-- Version du serveur: 5.0.45
-- Version de PHP: 5.2.3-1ubuntu6.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Base de données: `webdelib`
--

-- --------------------------------------------------------

--
-- Structure de la table `acos`
--

CREATE TABLE IF NOT EXISTS `acos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `object_id` int(10) default NULL,
  `alias` varchar(255) NOT NULL default '',
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=270 ;

--
-- Contenu de la table `acos`
--

INSERT INTO `acos` (`id`, `object_id`, `alias`, `lft`, `rght`) VALUES
(1, NULL, 'Pages:home', 1, 2),
(2, NULL, 'Pages:administration', 3, 6),
(3, NULL, 'Droits:edit', 4, 5);

-- --------------------------------------------------------

--
-- Structure de la table `annexes`
--

CREATE TABLE IF NOT EXISTS `annexes` (
  `id` int(11) NOT NULL auto_increment,
  `deliberation_id` int(11) NOT NULL,
  `seance_id` int(11) default NULL,
  `titre` varchar(50) NOT NULL,
  `type` char(1) NOT NULL,
  `filename` varchar(75) NOT NULL,
  `filetype` varchar(255) NOT NULL,
  `size` int(11) NOT NULL,
  `data` mediumblob NOT NULL,
  `created` datetime default NULL,
  `modified` datetime default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `annexes`
--


-- --------------------------------------------------------

--
-- Structure de la table `aros`
--

CREATE TABLE IF NOT EXISTS `aros` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `foreign_key` int(10) unsigned default NULL,
  `alias` varchar(255) NOT NULL default '',
  `lft` int(10) default NULL,
  `rght` int(10) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Contenu de la table `aros`
--

INSERT INTO `aros` (`id`, `foreign_key`, `alias`, `lft`, `rght`) VALUES
(1, 0, 'Profil:Administrateur', 1, 4),
(2, 1, 'Utilisateur:admin', 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `aros_acos`
--

CREATE TABLE IF NOT EXISTS `aros_acos` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `aro_id` int(10) unsigned NOT NULL,
  `aco_id` int(10) unsigned NOT NULL,
  `_create` char(2) NOT NULL default '0',
  `_read` char(2) NOT NULL default '0',
  `_update` char(2) NOT NULL default '0',
  `_delete` char(2) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

--
-- Contenu de la table `aros_acos`
--

INSERT INTO `aros_acos` (`id`, `aro_id`, `aco_id`, `_create`, `_read`, `_update`, `_delete`) VALUES
(1, 1, 1, '1', '1', '1', '1'),
(2, 1, 2, '1', '1', '1', '1'),
(3, 1, 3, '1', '1', '1', '1');


-- --------------------------------------------------------

--
-- Structure de la table `circuits`
--

CREATE TABLE IF NOT EXISTS `circuits` (
  `id` int(11) NOT NULL auto_increment,
  `libelle` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `circuits`
--


-- --------------------------------------------------------

--
-- Structure de la table `collectivites`
--

CREATE TABLE IF NOT EXISTS `collectivites` (
  `id` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `adresse` varchar(255) NOT NULL,
  `CP` int(11) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `telephone` int(10) unsigned zerofill NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `collectivites`
--

INSERT INTO `collectivites` (`id`, `nom`, `adresse`, `CP`, `ville`, `telephone`) VALUES
(1, 'Adullact', '335, Cour Messier', 34000, 'Montpellier', 0467650588);

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int(11) NOT NULL auto_increment,
  `delib_id` int(11) NOT NULL default '0',
  `agent_id` int(11) NOT NULL default '0',
  `texte` varchar(200) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `commentaires`
--


-- --------------------------------------------------------

--
-- Structure de la table `compteurs`
--

CREATE TABLE IF NOT EXISTS `compteurs` (
  `id` int(11) NOT NULL auto_increment COMMENT 'Identifiant interne',
  `nom` varchar(255) NOT NULL COMMENT 'Nom du compteur',
  `commentaire` varchar(255) NOT NULL COMMENT 'Description du compteur',
  `def_compteur` varchar(255) NOT NULL COMMENT 'Expression formatee du compteur',
  `num_sequence` mediumint(11) NOT NULL COMMENT 'Sequence du compteur qui s''incremente de 1 en 1',
  `def_reinit` varchar(255) NOT NULL COMMENT 'Expression formatee du critere de reinitialisation de la sequence',
  `val_reinit` varchar(255) NOT NULL COMMENT 'Derni�re valeur calculee de la r�initialisation',
  `created` datetime NOT NULL COMMENT 'Date et heure de creation du compteur',
  `modified` datetime NOT NULL COMMENT 'Date et heure de modification du compteur',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `compteurs`
--

INSERT INTO `compteurs` (`id`, `nom`, `commentaire`, `def_compteur`, `num_sequence`, `def_reinit`, `val_reinit`, `created`, `modified`) VALUES
(1, 'Deliberations', 'Numero des deliberations votees dans l''ordre du jour des seances', 'DELIB_#0000#', 0, '#AAAA##MM##JJ#', '20080313', '2008-01-07 12:04:25', '2008-03-13 16:22:14');

-- --------------------------------------------------------

--
-- Structure de la table `deliberations`
--

CREATE TABLE IF NOT EXISTS `deliberations` (
  `id` int(11) NOT NULL auto_increment,
  `circuit_id` int(11) default '0',
  `theme_id` int(11) NOT NULL default '0',
  `service_id` int(11) NOT NULL default '0',
  `vote_id` int(11) NOT NULL default '0',
  `redacteur_id` int(11) NOT NULL default '0',
  `rapporteur_id` int(11) NOT NULL default '0',
  `seance_id` int(11) default NULL,
  `position` int(4) NOT NULL,
  `anterieure_id` int(11) NOT NULL,
  `objet` varchar(1000) NOT NULL,
  `titre` varchar(1000) NOT NULL,
  `num_delib` varchar(15) NOT NULL,
  `num_pref` varchar(10) NOT NULL default '',
  `texte_projet` longblob,
  `texte_synthese` longblob,
  `deliberation` longblob,
  `date_limite` date default NULL,
  `date_envoi` datetime default NULL,
  `etat` int(11) NOT NULL default '0',
  `reporte` tinyint(1) NOT NULL default '0',
  `localisation1_id` int(11) NOT NULL default '0',
  `localisation2_id` int(11) NOT NULL default '0',
  `localisation3_id` int(11) NOT NULL default '0',
  `montant` int(10) NOT NULL,
  `debat` longblob NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `deliberations`
--


-- --------------------------------------------------------

--
-- Structure de la table `listepresences`
--

CREATE TABLE IF NOT EXISTS `listepresences` (
  `id` int(11) NOT NULL auto_increment,
  `delib_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `present` tinyint(1) NOT NULL,
  `mandataire` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `listepresences`
--


-- --------------------------------------------------------

--
-- Structure de la table `localisations`
--

CREATE TABLE IF NOT EXISTS `localisations` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default '0',
  `libelle` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `localisations`
--


-- --------------------------------------------------------

--
-- Structure de la table `models`
--

CREATE TABLE IF NOT EXISTS `models` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(100) NOT NULL,
  `libelle` varchar(100) NOT NULL,
  `texte` longblob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contenu de la table `models`
--

INSERT INTO `models` (`id`, `type`, `libelle`, `texte`) VALUES
(2, 'Document', 'convocation', 0x3c703e234c4f474f5f434f4c4c4543544956495445233c2f703e0d0a3c64697620616c69676e3d227269676874223e266e6273703b3c2f6469763e0d0a3c7020616c69676e3d227269676874223e23414452455353455f434f4c4c4543544956495445233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c64697620616c69676e3d226c656674223e3c7374726f6e673e234e4f4d5f454c55233c2f7374726f6e673e3c2f6469763e0d0a3c64697620616c69676e3d226c656674223e23414452455353455f454c55233c2f6469763e0d0a3c7020616c69676e3d226c656674223e2356494c4c455f454c55233c2f703e0d0a3c7020616c69676e3d227269676874223e41202356494c4c455f434f4c4c4543544956495445232c206c652023444154455f44555f4a4f5552233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c6272202f3e0d0a3c7374726f6e673e20202020202020202020202020202020202020202020202020202020202020202020202020202020436f6e766f636174696f6e2061752023545950455f5345414e4345233c2f7374726f6e673e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c64697620616c69676e3d2263656e746572223e0d0a3c64697620616c69676e3d226c656674223e266e6273703b3c2f6469763e0d0a3c64697620616c69676e3d226c656674223e0d0a3c7072653e426f6e6a6f75722c3c2f7072653e0d0a3c2f6469763e0d0a3c2f6469763e0d0a3c703e3c6272202f3e0d0a3c7374726f6e673e4a276169206c27686f6e6e65757220646520766f757320696e76697465722061752023545950455f5345414e434523207175692061757261206c696575206c652023444154455f5345414e4345232064616e733c6272202f3e0d0a234c4945555f5345414e4345232e3c6272202f3e0d0a3c2f7374726f6e673e3c6272202f3e0d0a4a6520766f757320707269652064652063726f6972652c204d6164616d652c204d6f6e73696575722c20656e206c276173737572616e6365206465206d6120636f6e736964266561637574653b726174696f6e2064697374696e6775266561637574653b652e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e5472266561637574653b7320636f726469616c656d656e742e3c2f703e),
(3, 'Document', 'ordre du jour', 0x3c703e234c4f474f5f434f4c4c4543544956495445233c2f703e0d0a3c703e23414452455353455f434f4c4c4543544956495445233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c7020616c69676e3d227269676874223e3c7374726f6e673e234e4f4d5f454c55233c2f7374726f6e673e3c6272202f3e0d0a23414452455353455f454c55233c6272202f3e0d0a2356494c4c455f454c55233c2f703e0d0a3c7020616c69676e3d227269676874223e41202356494c4c455f434f4c4c4543544956495445232c206c652023444154455f44555f4a4f5552233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c7374726f6e673e202020202020202020202020202020202020203c2f7374726f6e673e3c2f703e0d0a3c703e3c7374726f6e673e20202020202020202020202020202020202020202020202020202020202020204f72647265206475206a6f75723c2f7374726f6e673e206475203c7374726f6e673e23545950455f5345414e4345233c2f7374726f6e673e206475203c7374726f6e673e23444154455f5345414e4345233c2f7374726f6e673e3c2f703e0d0a3c703e234c495354455f50524f4a4554535f534f4d4d4149524553233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e),
(1, 'Document', 'projet', 0x3c703e234c4f474f5f434f4c4c4543544956495445233c2f703e0d0a3c703e0d0a3c7461626c652077696474683d22383025222063656c6c73706163696e673d2231222063656c6c70616464696e673d22312220626f726465723d22312220616c69676e3d2263656e746572223e0d0a202020203c74626f64793e0d0a20202020202020203c74723e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e4944454e54494649414e542044552050524f4a45543c2f74643e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e234944454e54494649414e545f50524f4a4554233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e4c6962656c6c653c2f74643e0d0a2020202020202020202020203c74643e234c4942454c4c455f44454c4942233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e54697472653c2f74643e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e2354495452455f44454c4942233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e5468266567726176653b6d653c2f74643e0d0a2020202020202020202020203c74643e234c4942454c4c455f5448454d45233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e536572766963653c2f74643e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e234c4942454c4c455f53455256494345233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e526170706f72746575723c2f74643e0d0a2020202020202020202020203c74643e234e4f4d5f524150504f5254455552233c2f74643e0d0a20202020202020203c2f74723e0d0a20202020202020203c74723e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e446174652053266561637574653b616e63653c2f74643e0d0a2020202020202020202020203c7464206267636f6c6f723d2223393963636666223e23444154455f5345414e4345233c2f74643e0d0a20202020202020203c2f74723e0d0a202020203c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e20203c2f7374726f6e673e3c2f753e3c2f703e0d0a3c68313e3c753e3c7374726f6e673e54455854452050524f4a4554203a203c2f7374726f6e673e3c2f753e3c2f68313e0d0a3c703e2354455854455f50524f4a4554233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c666f6e742073697a653d2235223e3c753e3c7374726f6e673e2054455854452053594e5448455345203a3c2f7374726f6e673e3c2f753e3c2f666f6e743e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e2354455854455f53594e5448455345233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e202054455854452044454c494245524154494f4e203a203c2f7374726f6e673e3c2f753e3c2f703e0d0a3c703e2354455854455f44454c4942233c2f703e),
(4, 'Document', 'deliberation', 0x3c7020616c69676e3d2263656e746572223e0d0a3c7461626c652077696474683d2232303022206865696768743d223735222063656c6c73706163696e673d2231222063656c6c70616464696e673d22312220626f726465723d2232223e0d0a202020203c74626f64793e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e3c6272202f3e0d0a2020202020202020202020203c64697620616c69676e3d2263656e746572223e234c4f474f5f434f4c4c4543544956495445233c2f6469763e0d0a2020202020202020202020203c2f74643e0d0a20202020202020203c2f74723e0d0a202020203c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e526170706f72746575723a20234e4f4d5f524150504f52544555522320235052454e4f4d5f524150504f5254455552233c2f753e3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e23444154455f5345414e4345233c2f703e0d0a3c703e2354455854455f44454c4942233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e4c6973746520646573205072266561637574653b73656e74733c2f7374726f6e673e3c2f753e203a3c2f703e0d0a3c703e234c495354455f50524553454e5453233c2f703e0d0a3c703e3c6272202f3e0d0a3c753e3c7374726f6e673e4c697374652064657320414253454e54533c2f7374726f6e673e3c2f753e203a3c2f703e0d0a3c703e234c495354455f414253454e5453233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e4c6973746520646573204d616e646174266561637574653b733c2f7374726f6e673e3c2f753e203a203c6272202f3e0d0a234c495354455f4d414e4441544149524553233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e524553554c54415420564f54414e54203a203c2f7374726f6e673e3c2f753e3c2f703e0d0a3c703e234c495354455f564f54414e54233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c683220616c69676e3d2263656e746572223e524553554c544154203a203c666f6e7420636f6c6f723d2223666636363030223e3c656d3e2023434f4d4d454e54414952455f44454c4942233c2f656d3e3c2f666f6e743e3c2f68323e),
(5, 'Document', 'P.V. sommaire', 0x3c7020616c69676e3d227269676874223e234c4f474f5f434f4c4c4543544956495445233c2f703e0d0a3c7020616c69676e3d227269676874223e266e6273703b3c2f703e0d0a3c683220616c69676e3d2263656e746572223e3c666f6e742073697a653d2235223e3c753e20505620736f6d6d616972652064752023444154455f5345414e4345233c2f753e3c2f666f6e743e3c2f68323e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c753e3c7374726f6e673e3c666f6e742073697a653d2234223e4c69737465206465732070726f6a657473203a3c2f666f6e743e203c2f7374726f6e673e3c2f753e3c2f703e0d0a3c703e234c495354455f50524f4a4554535f534f4d4d4149524553233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e),
(6, 'Document', 'P.V. detaille', 0x3c703e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e0d0a3c7461626c652077696474683d2231303025222063656c6c73706163696e673d2231222063656c6c70616464696e673d22312220626f726465723d2231223e0d0a202020203c74626f64793e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e0d0a2020202020202020202020203c703e234e4f4d5f434f4c4c4543544956495445233c2f703e0d0a2020202020202020202020203c703e266e6273703b3c2f703e0d0a2020202020202020202020203c703e23414452455353455f434f4c4c4543544956495445233c2f703e0d0a2020202020202020202020203c703e266e6273703b3c2f703e0d0a2020202020202020202020203c703e266e6273703b3c2f703e0d0a2020202020202020202020203c2f74643e0d0a2020202020202020202020203c746420616c69676e3d227269676874223e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e234c4f474f5f434f4c4c4543544956495445233c2f666f6e743e3c2f74643e0d0a20202020202020203c2f74723e0d0a202020203c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f666f6e743e3c2f703e0d0a3c7020616c69676e3d226a75737469667922207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e266e6273703b3c2f703e0d0a3c7020616c69676e3d226a75737469667922207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e3c7374726f6e673e3c753e4461746520533c2f753e3c2f7374726f6e673e3c2f666f6e743e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e3c7374726f6e673e3c753e266561637574653b616e63653c2f753e3c2f7374726f6e673e203a2023444154455f5345414e4345233c2f666f6e743e3c2f703e0d0a3c7020616c69676e3d226a75737469667922207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e3c666f6e742073697a653d2235223e3c753e3c7374726f6e673e3c7374726f6e673e3c7370616e207374796c653d22223e3c7370616e207374796c653d22746578742d6465636f726174696f6e3a206e6f6e653b223e3c7370616e207374796c653d22666f6e742d7374796c653a206e6f726d616c3b223e3c666f6e7420636f6c6f723d2223303030303030223e50726f6a6574732064266561637574653b7461696c6c266561637574653b733c2f666f6e743e3c2f7370616e3e3c2f7370616e3e3c2f7370616e3e3c2f7374726f6e673e3c2f7374726f6e673e3c2f753e3c2f666f6e743e3c7374726f6e673e3c7370616e207374796c653d22223e3c7370616e207374796c653d22746578742d6465636f726174696f6e3a206e6f6e653b223e3c7370616e207374796c653d22666f6e742d7374796c653a206e6f726d616c3b223e3c666f6e7420636f6c6f723d2223303030303030223e203a3c2f666f6e743e3c2f7370616e3e3c2f7370616e3e3c2f7370616e3e3c2f7374726f6e673e3c2f666f6e743e3c2f703e0d0a3c7020616c69676e3d226a75737469667922207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e3c666f6e742073697a653d22332220666163653d22436f7572696572204e6577223e3c7370616e207374796c653d22223e3c7370616e207374796c653d22746578742d6465636f726174696f6e3a206e6f6e653b223e3c7370616e207374796c653d22666f6e742d7374796c653a206e6f726d616c3b223e3c666f6e7420636f6c6f723d2223303030303030223e234c495354455f50524f4a4554535f44455441494c4c4553233c2f666f6e743e3c2f7370616e3e3c2f7370616e3e3c2f7370616e3e3c2f666f6e743e3c2f703e0d0a3c7020616c69676e3d226a75737469667922207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e),
(8, 'Composant documentaire', 'Liste presents', 0x3c703e3c7374726f6e673e234e4f4d5f50524553454e5423203c2f7374726f6e673e235052454e4f4d5f50524553454e54233c2f703e),
(9, 'Composant documentaire', 'Liste absents', 0x3c703e3c7374726f6e673e235052454e4f4d5f414253454e54233c2f7374726f6e673e3c7374726f6e673e203c2f7374726f6e673e3c7374726f6e673e234e4f4d5f414253454e54233c2f7374726f6e673e3c656d3e203c2f656d3e3c2f703e),
(10, 'Composant documentaire', 'liste mandat', 0x3c703e3c7374726f6e673e235052454e4f4d5f4d414e4441544149524523203c2f7374726f6e673e3c7374726f6e673e234e4f4d5f4d414e44415441495245232c203c6272202f3e0d0a3c6272202f3e0d0a3c2f7374726f6e673e3c656d3e203c2f656d3e3c2f703e),
(11, 'Composant documentaire', 'liste votants', 0x3c703e0d0a3c7461626c652077696474683d22393025222063656c6c73706163696e673d2231222063656c6c70616464696e673d22312220626f726465723d2230223e0d0a202020203c74626f64793e0d0a20202020202020203c74723e0d0a2020202020202020202020203c74643e234e4f4d5f564f54414e5423266e6273703b20235052454e4f4d5f564f54414e54233c2f74643e0d0a2020202020202020202020203c74643e6120766f74266561637574653b2023524553554c5441545f564f54414e54233c2f74643e0d0a20202020202020203c2f74723e0d0a202020203c2f74626f64793e0d0a3c2f7461626c653e0d0a3c2f703e),
(12, 'Composant documentaire', 'liste projets detaille', 0x3c703e266e6273703b3c2f703e0d0a3c6f6c3e0d0a202020203c6c693e0d0a202020203c68323e2354495452455f44454c4942233c2f68323e0d0a202020203c2f6c693e0d0a3c2f6f6c3e0d0a3c703e266e6273703b3c2f703e0d0a3c68333e266e6273703b3c2f68333e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c7374726f6e673e3c753e5465787465206465206c612064266561637574653b6c6962266561637574653b726174696f6e3c2f753e3c2f7374726f6e673e203a3c2f703e0d0a3c703e2354455854455f44454c4942233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e3c7374726f6e673e3c753e436f6d6d656e7461697265733c2f753e3c2f7374726f6e673e203a3c2f703e0d0a3c703e23434f4d4d454e54414952455f44454c4942233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c7020616c69676e3d22726967687422207374796c653d22746578742d696e64656e743a20302e34636d3b206d617267696e2d626f74746f6d3a2030636d3b223e266e6273703b3c2f703e0d0a3c703e0d0a3c6d65746120687474702d65717569763d22434f4e54454e542d545950452220636f6e74656e743d22746578742f68746d6c3b20636861727365743d7574662d38223e0d0a3c7469746c653e3c2f7469746c653e0d0a3c6d657461206e616d653d2247454e455241544f522220636f6e74656e743d224f70656e4f66666963652e6f726720322e312020284c696e757829223e0d0a3c6d657461206e616d653d22435245415445442220636f6e74656e743d2232303037313132323b39333735343030223e0d0a3c6d657461206e616d653d224348414e4745442220636f6e74656e743d2232303038303132323b3130313530383030223e200920092009200920093c7374796c6520747970653d22746578742f637373223e0d0a093c212d2d0d0a09094070616765207b2073697a653a203231636d2032392e37636d3b206d617267696e3a2032636d207d0d0a090950207b206d617267696e2d626f74746f6d3a20302e3231636d207d0d0a092d2d3e0d0a093c2f7374796c653e202020202020202020202020202020203c2f6d6574613e0d0a3c2f6d6574613e0d0a3c2f6d6574613e0d0a3c2f6d6574613e0d0a3c2f703e),
(13, 'Composant documentaire', 'liste projets sommaires', 0x3c703e3c753e3c7374726f6e673e4c6962656c6c653c2f7374726f6e673e3c2f753e3c7374726f6e673e203a203c2f7374726f6e673e234c4942454c4c455f44454c4942233c2f703e0d0a3c703e3c7374726f6e673e5469747265203a203c2f7374726f6e673e2354495452455f44454c4942233c2f703e0d0a3c703e266e6273703b3c2f703e0d0a3c703e266e6273703b3c2f703e);

-- --------------------------------------------------------

--
-- Structure de la table `profils`
--

CREATE TABLE IF NOT EXISTS `profils` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default '0',
  `libelle` varchar(100) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `profils`
--

INSERT INTO `profils` (`id`, `parent_id`, `libelle`, `created`, `modified`) VALUES
(1, 0, 'Administrateur', '2007-09-03 14:40:53', '2007-09-03 14:40:53');

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

CREATE TABLE IF NOT EXISTS `seances` (
  `id` int(11) NOT NULL auto_increment,
  `type_id` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `traitee` int(1) NOT NULL default '0',
  `debat_global` longblob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `seances`
--


-- --------------------------------------------------------

--
-- Structure de la table `seances_users`
--

CREATE TABLE IF NOT EXISTS `seances_users` (
  `id` int(11) NOT NULL auto_increment,
  `seance_id` int(9) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `seances_users`
--


-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default '0',
  `libelle` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;


-- --------------------------------------------------------

--
-- Structure de la table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL auto_increment,
  `parent_id` int(11) default '0',
  `libelle` varchar(100) NOT NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Contenu de la table `themes`
--

-- --------------------------------------------------------

--
-- Structure de la table `traitements`
--

CREATE TABLE IF NOT EXISTS `traitements` (
  `id` int(11) NOT NULL auto_increment,
  `delib_id` int(11) NOT NULL default '0',
  `circuit_id` int(11) NOT NULL default '0',
  `position` int(11) NOT NULL default '0',
  `date_traitement` datetime default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `traitements`
--


-- --------------------------------------------------------

--
-- Structure de la table `typeseances`
--

CREATE TABLE IF NOT EXISTS `typeseances` (
  `id` int(11) NOT NULL auto_increment,
  `libelle` varchar(100) NOT NULL,
  `retard` int(11) NOT NULL default '0',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `typeseances`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL auto_increment,
  `profil_id` int(11) NOT NULL default '0',
  `service_id` int(11) NOT NULL,
  `statut` int(11) NOT NULL default '0',
  `login` varchar(50) NOT NULL default '',
  `password` varchar(100) NOT NULL default '',
  `titre` varchar(100) default NULL,
  `nom` varchar(50) NOT NULL default '',
  `prenom` varchar(50) NOT NULL default '',
  `email` varchar(255) NOT NULL default '',
  `adresse` varchar(255) NOT NULL default '',
  `CP` int(11) NOT NULL default '0',
  `ville` varchar(50) NOT NULL default '',
  `teldom` int(10) unsigned zerofill default NULL,
  `telmobile` int(10) unsigned zerofill default NULL,
  `date_naissance` date default NULL,
  `accept_notif` tinyint(1) default NULL,
  `position` int(3) default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `profil_id`, `service_id`, `statut`, `login`, `password`, `nom`, `prenom`, `email`, `adresse`, `CP`, `ville`, `teldom`, `telmobile`, `date_naissance`, `accept_notif`, `created`, `modified`) VALUES
(1, 1, 0, 0, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'admin', 'francois.desmaretz@adullact.org', '116 avenue saint clement', 34000, 'Montpellier', 0000000000, 0000000000, '1999-11-30', 0, '0000-00-00 00:00:00', '2008-03-14 14:42:18');

-- --------------------------------------------------------

--
-- Structure de la table `users_circuits`
--

CREATE TABLE IF NOT EXISTS `users_circuits` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `circuit_id` int(11) NOT NULL default '0',
  `service_id` int(11) NOT NULL default '0',
  `position` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Contenu de la table `users_circuits`
--


-- --------------------------------------------------------

--
-- Structure de la table `users_services`
--

CREATE TABLE IF NOT EXISTS `users_services` (
  `user_id` int(11) NOT NULL default '0',
  `service_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`user_id`,`service_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users_services`
--

-- --------------------------------------------------------

--
-- Structure de la table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `delib_id` int(11) NOT NULL default '0',
  `resultat` int(1) NOT NULL,
  `commentaire` varchar(500) default NULL,
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;