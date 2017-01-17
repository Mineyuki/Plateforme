CREATE TABLE 'events' (
 'id' bigint(20) unsigned NOT NULL AUTO_INCREMENT,
 'mailExpe' varchar(50) DEFAULT NULL,
 'mailDest' varchar(50) DEFAULT NULL,
 'sent' tinyint(1) DEFAULT '0',
 'DateH' date DEFAULT NULL,
 'heure' time DEFAULT NULL,
 'title' varchar(255) DEFAULT NULL,
 PRIMARY KEY ('id'),
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1