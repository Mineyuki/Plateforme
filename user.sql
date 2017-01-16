DROP TABLE IF EXISTS membres Cascade;

CREATE TABLE membres (
	nom			VARCHAR(255)	NOT NULL,
	prenom			VARCHAR(255)	NOT NULL,
	mail			VARCHAR(255)	NOT NULL,
	motdepasse		VARCHAR(255)	NOT NULL,
	categorie		VARCHAR(50)	DEFAULT NULL,
<<<<<<< HEAD
=======
	formation		VARCHAR(50)	NOT NULL,  --ajoutée par viviane
	ecriture_article	TINYINT		DEFAULT 0,
>>>>>>> 690204060a69f9e3f1b07d1fe592be9cf953978f
	PRIMARY KEY (mail)
);
