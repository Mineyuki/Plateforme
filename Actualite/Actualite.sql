DROP TABLE IF EXISTS Article;
DROP TABLE IF EXISTS Commentaire;

CREATE TABLE Article(
	id_article	INTEGER		NOT NULL	AUTO_INCREMENT,
	titre		VARCHAR(255)	NOT NULL,
	date		DATETIME	NOT NULL,
	auteur		VARCHAR(255)	NOT NULL,
	corps		VARCHAR(255)	NOT NULL,
	PRIMARY KEY (id_article)
)

CREATE TABLE Commentaire(
	id_article	INTEGER		NOT NULL,
	id_commentaire	INTEGER		NOT NULL	AUTO_INCREMENT,
	date		DATETIME	NOT NULL,	
	pseudo		VARCHAR(255)	NOT NULL,
	commentaire	TEXT,
	PRIMARY KEY (id_commentaire),
	FOREIGN KEY (id_article) REFERENCES Article(id_article)
)
