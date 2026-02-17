create DATABASE BNGRC2;
USE BNGRC2;

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
    FOREIGN KEY(id_type_besoin) REFERENCES type_besoin(id_type_besoin),
    prix_unitaire DECIMAL(10, 2) NOT NULL

); 
CREATE TABLE besoin (
    id_besoin INT PRIMARY KEY AUTO_INCREMENT,
    id_ville INT NOT NULL,
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville),
    id_produit INT NOT NULL,
    FOREIGN KEY(id_produit) REFERENCES produit(id_produit),
    quantite INT NOT NULL,
    date_besoin DATETIME NOT NULL
);

CREATE TABLE don (
    id_don INT PRIMARY KEY AUTO_INCREMENT,
    id_produit INT NOT NULL,
    FOREIGN KEY(id_produit) REFERENCES produit(id_produit),
    quantite INT NOT NULL,
    date_don DATETIME NOT NULL

);

CREATE TABLE don_argent (
    id_don_argent INT PRIMARY KEY AUTO_INCREMENT,
    montant DECIMAL(15, 2) NOT NULL,
    date_don DATETIME NOT NULL
);

CREATE TABLE distribution(
    id_distribution INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin),
    id_don INT NOT NULL,
    FOREIGN KEY(id_don) REFERENCES don(id_don),
    date_distribution DATETIME NOT NULL,
    quantite_distribution INT NOT NULL

);

CREATE TABLE configuration (
    cle VARCHAR(50) PRIMARY KEY,
    valeur VARCHAR(255) NOT NULL
);

CREATE TABLE achat (
    id_achat INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    quantite_achetee INT NOT NULL,
    cout_unitaire_achat DECIMAL(10, 2) NOT NULL,
    frais_achat_pourcentage DECIMAL(5, 2) NOT NULL,
    cout_total_achat DECIMAL(10, 2) NOT NULL,
    date_achat DATETIME NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin)
);

INSERT INTO region (nom_region) VALUES 
('Vakinankaratra'),
('Analamanga'),
('Atsinanana');


INSERT INTO ville (nom_ville, id_region) VALUES 
('Antsirabe', 1),
('Antananarivo', 2),
('Toamasina', 3);

INSERT INTO type_besoin (nom_type_besoin) VALUES 
('Alimentaire'),
('Vestimentaire'),
('Médical');


INSERT INTO produit (nom_produit, id_type_besoin, prix_unitaire) VALUES 
('Riz', 1, 2500.00),
('T-shirt', 2, 15000.00),
('Paracétamol', 3, 1000.00);


INSERT INTO besoin (id_ville, id_produit, quantite, date_besoin) VALUES 
(1, 1, 100, '2026-02-16 10:00:00'),
(2, 2, 50, '2026-02-17 11:00:00'),
(3, 3, 200, '2026-02-18 12:00:00');


INSERT INTO don (id_produit, quantite, date_don) VALUES 
(1, 50, '2026-02-16 14:00:00'),
(2, 25, '2026-02-17 15:00:00'),
(3, 100, '2026-02-18 16:00:00');

INSERT INTO don_argent (montant, date_don) VALUES (500000, '2026-02-20 10:00:00');

INSERT INTO distribution (id_besoin, id_don, date_distribution, quantite_distribution) VALUES
(1, 1, '2026-02-16 16:00:00', 50),
(2, 2, '2026-02-17 17:00:00', 25),
(3, 3, '2026-02-18 18:00:00', 100);

INSERT INTO don (id_produit, quantite, date_don) VALUES 
(1, 100, '2026-02-19 08:00:00');

INSERT INTO distribution (id_besoin, id_don, date_distribution, quantite_distribution) VALUES
(1, 4, '2026-02-19 09:00:00', 30);

INSERT INTO configuration (cle, valeur) VALUES ('frais_achat_pourcentage', '10');

CREATE VIEW v_dons_par_ville AS
SELECT 
    v.nom_ville,
    p.nom_produit,
    SUM(dist.quantite_distribution) as total_distribue
FROM distribution dist
JOIN besoin b ON dist.id_besoin = b.id_besoin
JOIN ville v ON b.id_ville = v.id_ville
JOIN don d ON dist.id_don = d.id_don
JOIN produit p ON d.id_produit = p.id_produit
GROUP BY v.nom_ville, p.nom_produit;

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
    SELECT id_besoin, SUM(quantite_distribution) as total_distribue
    FROM distribution
    GROUP BY id_besoin
) dist_agg ON b.id_besoin = dist_agg.id_besoin
LEFT JOIN (
    SELECT id_besoin, SUM(quantite_achetee) as total_achete
    FROM achat
    GROUP BY id_besoin
) ach_agg ON b.id_besoin = ach_agg.id_besoin;

CREATE OR REPLACE VIEW v_stock_dons_restants AS
SELECT
    p.id_produit,
    p.nom_produit,
    (COALESCE(don_agg.total_done, 0) - COALESCE(dist_agg.total_distribue, 0)) as quantite_restante
FROM produit p
LEFT JOIN (
    SELECT id_produit, SUM(quantite) as total_done FROM don GROUP BY id_produit
) don_agg ON p.id_produit = don_agg.id_produit
LEFT JOIN (
    SELECT d.id_produit, SUM(dist.quantite_distribution) as total_distribue FROM distribution dist JOIN don d ON dist.id_don = d.id_don GROUP BY d.id_produit
) dist_agg ON p.id_produit = dist_agg.id_produit;
