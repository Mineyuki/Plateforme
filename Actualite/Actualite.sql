DROP TABLE IF EXISTS Article CASCADE;
DROP TABLE IF EXISTS Commentaire CASCADE;

CREATE TABLE Article(
	id_article	INTEGER		NOT NULL	AUTO_INCREMENT,
	titre		VARCHAR(255)	NOT NULL,
	jour		DATETIME	NOT NULL,
	auteur		VARCHAR(255)	NOT NULL,
	corps		LONGTEXT	NOT NULL,
	validation	TINYINT		DEFAULT 0,
	PRIMARY KEY (id_article)
);

/*
CREATE TABLE Article_Valide(
	id_article_valide	INTEGER	NOT NULL	AUTO_INCREMENT,
	id_article		INTEGER NOT NULL,
	FOREIGN KEY (id_article) REFERENCES Article(id_article)
);*/

CREATE TABLE Commentaire(
	id_article	INTEGER		NOT NULL,
	id_commentaire	INTEGER		NOT NULL	AUTO_INCREMENT,
	jour		DATETIME	NOT NULL,	
	pseudo		VARCHAR(255)	NOT NULL,
	commente	TEXT		NOT NULL,
	PRIMARY KEY (id_commentaire),
	FOREIGN KEY (id_article) REFERENCES Article(id_article)
);
