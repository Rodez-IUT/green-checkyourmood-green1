DROP TABLE IF EXISTS historique;
DROP TABLE IF EXISTS compte;
DROP TABLE IF EXISTS humeur;
DROP TABLE IF EXISTS genre;

-- Création de la table genre
CREATE TABLE genre (
    ID_Gen INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nom VARCHAR(25) NOT NULL
);

-- Création de la table compte
CREATE TABLE compte (
    APIKEY VARCHAR(50) NOT NULL,
    ID_Compte INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nom VARCHAR(50) NOT NULL,
    Prenom VARCHAR(50) NOT NULL,
    Date_de_naissance DATE,
    Code_Gen INT,
    Mot_de_passe VARCHAR(255) NOT NULL,
    Email VARCHAR(50) NOT NULL UNIQUE,
    FOREIGN KEY (Code_Gen) REFERENCES genre(ID_Gen)
);

-- Création de la table humeur
CREATE TABLE humeur (
    ID_Hum INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Libelle VARCHAR(25) NOT NULL,
    Emoji varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Création de la table historique 
CREATE TABLE historique (
    ID_Histo INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Code_Compte INT NOT NULL,
    Code_hum INT NOT NULL,
    Date_Hum DATETIME NOT NULL,
    Date_Ajout DATETIME NOT NULL,
    Informations BLOB,
    FOREIGN KEY (Code_Compte) REFERENCES compte(ID_Compte),
    FOREIGN KEY (Code_Hum) REFERENCES humeur(ID_Hum),
    CONSTRAINT date_hum CHECK (Date_Hum <= Date_Ajout && Date_Hum >= DATE_SUB(Date_Ajout, INTERVAL 1 DAY))
);
