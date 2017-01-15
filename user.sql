DROP TABLE IF EXISTS membres Cascade;

CREATE TABLE membres (
	nom			VARCHAR(255)	NOT NULL,
	prenom			VARCHAR(255)	NOT NULL,
	mail			VARCHAR(255)	NOT NULL,
	motdepasse		VARCHAR(255)	NOT NULL,
	categorie		VARCHAR(50)	DEFAULT NULL,
	ecriture_article	TINYINT		DEFAULT 0,
	PRIMARY KEY (mail)
);
