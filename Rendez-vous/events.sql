CREATE TABLE events (
	id		BIGINT(20)	UNSIGNED NOT NULL	AUTO_INCREMENT,
	mailExpe	varchar(50)	DEFAULT NULL,
	mailDest	varchar(50)	DEFAULT NULL,
	DateH		datetime	DEFAULT NULL,
	title		varchar(255)	DEFAULT NULL,
	PRIMARY KEY ('id'),
	UNIQUE KEY 'id' ('id')
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
