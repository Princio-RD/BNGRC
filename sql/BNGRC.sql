
DROP DATABASE IF EXISTS BNGRC;
CREATE DATABASE BNGRC;
USE BNGRC;

-- =====================================
-- TABLES
-- =====================================

CREATE TABLE region (
    id_region INT PRIMARY KEY AUTO_INCREMENT,
    nom_region VARCHAR(255) NOT NULL
);

CREATE TABLE ville (
    id_ville INT PRIMARY KEY AUTO_INCREMENT,
    nom_ville VARCHAR(255) NOT NULL,
    id_region INT NOT NULL,
    nombre_sinistres INT DEFAULT 0,
    FOREIGN KEY(id_region) REFERENCES region(id_region)
);

CREATE TABLE type_besoin (
    id_type_besoin INT PRIMARY KEY AUTO_INCREMENT,
    nom_type_besoin VARCHAR(255) NOT NULL
);

CREATE TABLE produit (
    id_produit INT PRIMARY KEY AUTO_INCREMENT,
    nom_produit VARCHAR(255) NOT NULL,
    id_type_besoin INT NOT NULL,
    prix_unitaire DECIMAL(10,2) NOT NULL,
    FOREIGN KEY(id_type_besoin) REFERENCES type_besoin(id_type_besoin)
);

CREATE TABLE besoin (
    id_besoin INT PRIMARY KEY AUTO_INCREMENT,
    id_ville INT NOT NULL,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    date_besoin DATETIME NOT NULL,
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville),
    FOREIGN KEY(id_produit) REFERENCES produit(id_produit)
);

CREATE TABLE don (
    id_don INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    quantite INT NOT NULL,
    date_don DATETIME NOT NULL,
    FOREIGN KEY(id_produit) REFERENCES produit(id_produit)
);

CREATE TABLE don_argent ( 
    id_don_argent INT PRIMARY KEY AUTO_INCREMENT,
    montant DECIMAL(15,2) NOT NULL,
    date_don DATETIME NOT NULL
);

CREATE TABLE distribution( 
    id_distribution INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    id_don INT NOT NULL,
    date_distribution DATETIME NOT NULL,
    quantite_distribution INT NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin),
    FOREIGN KEY(id_don) REFERENCES don(id_don)
);

CREATE TABLE configuration (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(255) NOT NULL
);

CREATE TABLE achat (
    id_achat INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    quantite_achetee INT NOT NULL,
    cout_unitaire_achat DECIMAL(10,2) NOT NULL,
    frais_achat_pourcentage DECIMAL(5,2) NOT NULL,
    cout_total_achat DECIMAL(15,2) NOT NULL,
    date_achat DATETIME NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin)
);

-- =====================================
-- INSERTIONS
-- =====================================

-- Regions
INSERT INTO region (nom_region) VALUES 
('Atsinanana'),
('Vatovavy'),
('Atsimo Atsinanana'),
('Diana'),
('Menabe');

-- Villes
INSERT INTO ville (nom_ville, id_region) VALUES 
('Toamasina', 1),
('Mananjary', 2),
('Farafangana', 3),
('Nosy Be', 4),
('Morondava', 5);

-- Types
INSERT INTO type_besoin (nom_type_besoin) VALUES 
('nature'),
('materiel'),
('argent');

-- Produits
INSERT INTO produit (nom_produit, id_type_besoin, prix_unitaire) VALUES
('Riz (kg)', 1, 3000),
('Eau (L)', 1, 1000),
('Huile (L)', 1, 6000),
('Haricots', 1, 4000),
('Tôle', 2, 25000),
('Bâche', 2, 15000),
('Clous (kg)', 2, 8000),
('Bois', 2, 10000),
('groupe', 2, 6750000),
('Argent', 3, 1);

-- =====================================
-- BESOINS (TES DONNEES)
-- =====================================

INSERT INTO besoin (id_ville, id_produit, quantite, date_besoin) VALUES

-- TOAMASINA
(1,1,800,'2026-02-16'),
(1,2,1500,'2026-02-15'),
(1,5,120,'2026-02-16'),
(1,6,200,'2026-02-15'),
(1,10,12000000,'2026-02-16'),
(1,9,3,'2026-02-15'),

-- MANANJARY
(2,1,500,'2026-02-15'),
(2,3,120,'2026-02-16'),
(2,5,80,'2026-02-15'),
(2,7,60,'2026-02-16'),
(2,10,6000000,'2026-02-15'),

-- FARAFANGANA
(3,1,600,'2026-02-16'),
(3,2,1000,'2026-02-15'),
(3,6,150,'2026-02-16'),
(3,8,100,'2026-02-15'),
(3,10,8000000,'2026-02-16'),

-- NOSY BE
(4,1,300,'2026-02-15'),
(4,4,200,'2026-02-16'),
(4,5,40,'2026-02-15'),
(4,7,30,'2026-02-16'),
(4,10,4000000,'2026-02-15'),

-- MORONDAVA
(5,1,700,'2026-02-16'),
(5,2,1200,'2026-02-15'),
(5,6,180,'2026-02-16'),
(5,8,150,'2026-02-15'),
(5,10,10000000,'2026-02-16');

INSERT INTO don (id_produit, quantite, date_don) VALUES
(1,400,'2026-02-16'),
(2,600,'2026-02-16'),
(5,50,'2026-02-17'),
(6,70,'2026-02-17'),
(4,100,'2026-02-17'),
(4,88,'2026-02-17'),
(1,2000,'2026-02-18'),
(5,300,'2026-02-18'),
(2,5000,'2026-02-18'),
(6,500,'2026-02-19');

-- =====================================
-- DONS ARGENT
-- =====================================

INSERT INTO don_argent (montant, date_don) VALUES
(5000000,'2026-02-16'),
(3000000,'2026-02-16'),
(4000000,'2026-02-17'),
(1500000,'2026-02-17'),
(6000000,'2026-02-17'),
(20000000,'2026-02-19');


-- =====================================
-- CONFIGURATION
-- =====================================

INSERT INTO configuration (cle, valeur)
VALUES ('frais_achat_pourcentage','10');

-- =====================================
-- VUES
-- =====================================

CREATE OR REPLACE VIEW v_besoins_par_ville AS
SELECT 
    v.nom_ville,
    p.nom_produit,
    tb.nom_type_besoin,
    SUM(b.quantite) AS total_quantite
FROM besoin b
JOIN ville v ON b.id_ville = v.id_ville
JOIN produit p ON b.id_produit = p.id_produit
JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
GROUP BY v.nom_ville, p.nom_produit, tb.nom_type_besoin;

CREATE OR REPLACE VIEW v_cout_total_besoins AS
SELECT 
    v.nom_ville,
    SUM(b.quantite * p.prix_unitaire) AS cout_total
FROM besoin b
JOIN ville v ON b.id_ville = v.id_ville
JOIN produit p ON b.id_produit = p.id_produit
GROUP BY v.nom_ville;

-- Vue des besoins restants (avec id_ville et id_produit pour les jointures)
CREATE OR REPLACE VIEW v_besoins_restants AS
SELECT
    b.id_besoin,
    b.date_besoin,
    v.id_ville,
    v.nom_ville,
    p.id_produit,
    p.nom_produit,
    tb.nom_type_besoin,
    p.prix_unitaire,
    b.quantite AS quantite_demandee,
    COALESCE(dist_agg.total_distribue, 0) AS quantite_distribuee,
    COALESCE(ach_agg.total_achete, 0) AS quantite_achetee,
    (b.quantite - COALESCE(dist_agg.total_distribue, 0) - COALESCE(ach_agg.total_achete, 0)) AS quantite_restante
FROM besoin b
JOIN ville v ON b.id_ville = v.id_ville
JOIN produit p ON b.id_produit = p.id_produit
JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite_distribution) AS total_distribue
    FROM distribution
    GROUP BY id_besoin
) dist_agg ON b.id_besoin = dist_agg.id_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite_achetee) AS total_achete
    FROM achat
    GROUP BY id_besoin
) ach_agg ON b.id_besoin = ach_agg.id_besoin;

-- Vue du stock de dons restants (avec id_produit pour les jointures)
CREATE OR REPLACE VIEW v_stock_dons_restants AS
SELECT
    p.id_produit,
    p.nom_produit,
    (COALESCE(don_agg.total_done, 0) - COALESCE(dist_agg.total_distribue, 0)) AS quantite_restante
FROM produit p
LEFT JOIN (
    SELECT id_produit, SUM(quantite) AS total_done 
    FROM don 
    GROUP BY id_produit
) don_agg ON p.id_produit = don_agg.id_produit
LEFT JOIN (
    SELECT d.id_produit, SUM(dist.quantite_distribution) AS total_distribue 
    FROM distribution dist 
    JOIN don d ON dist.id_don = d.id_don 
    GROUP BY d.id_produit
) dist_agg ON p.id_produit = dist_agg.id_produit;
