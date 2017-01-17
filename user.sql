DROP TABLE IF EXISTS membres Cascade;

CREATE TABLE membres (
	membre_id		INT(11)			NOT NULL	AUTO_INCREMENT,	
	nom				VARCHAR(255)	NOT NULL,
	prenom			VARCHAR(255)	NOT NULL,
	membre_pseudo	VARCHAR(128)	DEFAULT NULL,
	mail			VARCHAR(255)	NOT NULL,
	motdepasse		VARCHAR(255)	NOT NULL,
	membre_avatar	VARCHAR(100)	DEFAULT 'no-avatar.png',
	membre_post		INT(11)			DEFAULT NULL,
	membre_rang		TINYINT(4)		DEFAULT '2',
	categorie		VARCHAR(50)		DEFAULT NULL,
	PRIMARY KEY (membre_id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

CREATE TABLE IF NOT EXISTS forum_categorie (
	cat_id int(11) NOT NULL AUTO_INCREMENT,
	cat_nom varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
	cat_ordre int(11) NOT NULL,
	PRIMARY KEY (cat_id),
	UNIQUE KEY (cat_ordre)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE forum_config (
 config_nom varchar(200) NOT NULL,
 config_valeur varchar(500) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE forum_forum (
 forum_id int(11) NOT NULL AUTO_INCREMENT,
 forum_cat_id tinyint(4) NOT NULL,
 forum_name varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 forum_desc text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 forum_ordre mediumint(8) NOT NULL,
 forum_last_post_id int(11) NOT NULL,
 forum_topic mediumint(8) NOT NULL,
 forum_post mediumint(8) NOT NULL,
 auth_view tinyint(4) NOT NULL,
 auth_post tinyint(4) NOT NULL,
 auth_topic tinyint(4) NOT NULL,
 auth_annonce tinyint(4) NOT NULL,
 auth_modo tinyint(4) NOT NULL,
 PRIMARY KEY (forum_id)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

CREATE TABLE forum_mp (
 mp_id int(11) NOT NULL AUTO_INCREMENT,
 mp_expediteur int(11) NOT NULL,
 mp_receveur int(11) NOT NULL,
 mp_titre varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 mp_text text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 mp_time int(11) NOT NULL,
 `mp_lu` ENUM( '0', '1' ) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 PRIMARY KEY (mp_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE forum_post (
 post_id int(11) NOT NULL AUTO_INCREMENT,
 post_createur int(11) NOT NULL,
 post_texte text CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 post_time int(11) NOT NULL,
 topic_id int(11) NOT NULL,
 post_forum_id int(11) NOT NULL,
 PRIMARY KEY (post_id)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

CREATE TABLE forum_topic (
 topic_id int(11) NOT NULL AUTO_INCREMENT,
 forum_id int(11) NOT NULL,
 topic_titre char(60) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 topic_createur int(11) NOT NULL,
 topic_vu mediumint(8) NOT NULL,
 topic_time int(11) NOT NULL,
 topic_genre varchar(30) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
 topic_last_post int(11) DEFAULT NULL,
 topic_first_post int(11) DEFAULT NULL,
 topic_post mediumint(8) DEFAULT NULL,
 topic_locked ENUM( '0', '1' ) NOT NULL,
 PRIMARY KEY (topic_id),
 UNIQUE KEY topic_last_post (topic_last_post)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
