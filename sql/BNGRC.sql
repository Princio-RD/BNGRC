
create DATABASE BNGRC;
USE BNGRC;

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
CREATE TABLE distribution(
    id_distribution INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin),
    id_don INT NOT NULL,
    FOREIGN KEY(id_don) REFERENCES don(id_don),
    date_distribution DATETIME NOT NULL,
    quantite_distribution INT NOT NULL

);

CREATE TABLE besoin_achat (
    id_achat INT PRIMARY KEY AUTO_INCREMENT,
    id_besoin INT NOT NULL,
    id_don INT NULL,
    id_ville INT NOT NULL,
    montant_achat DECIMAL(12, 2) NOT NULL,
    frais_pourcent DECIMAL(5, 2) NOT NULL,
    montant_frais DECIMAL(12, 2) NOT NULL,
    montant_total DECIMAL(12, 2) NOT NULL,
    date_achat DATETIME NOT NULL,
    FOREIGN KEY(id_besoin) REFERENCES besoin(id_besoin),
    FOREIGN KEY(id_don) REFERENCES don(id_don),
    FOREIGN KEY(id_ville) REFERENCES ville(id_ville)
);

CREATE INDEX idx_besoin_achat_besoin ON besoin_achat(id_besoin);
CREATE INDEX idx_besoin_achat_ville ON besoin_achat(id_ville);
CREATE INDEX idx_besoin_achat_don ON besoin_achat(id_don);

INSERT INTO region (nom_region) VALUES 
('Vakinankaratra'),
('Analamanga'),
('Atsinanana');


INSERT INTO ville (nom_ville, id_region, nombre_sinistres) VALUES 
('Antsirabe', 1, 50),
('Antananarivo', 2, 80),
('Toamasina', 3, 30);

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

INSERT INTO distribution (id_besoin, id_don, date_distribution, quantite_distribution) VALUES
(1, 1, '2026-02-16 16:00:00', 50),
(2, 2, '2026-02-17 17:00:00', 25),
(3, 3, '2026-02-18 18:00:00', 100);

-- ==================== DONNÉES SUPPLÉMENTAIRES ====================

INSERT INTO region (id_region, nom_region) VALUES
(4, 'Amoron\'i Mania'),
(5, 'Boeny'),
(6, 'DIANA'),
(7, 'Atsimo-Andrefana'),
(8, 'SAVA');

INSERT INTO ville (id_ville, nom_ville, id_region, nombre_sinistres) VALUES
(4, 'Fianarantsoa', 4, 60),
(5, 'Mahajanga', 5, 40),
(6, 'Antsiranana', 6, 35),
(7, 'Toliara', 7, 55),
(8, 'Sambava', 8, 25),
(9, 'Manakara', 4, 20),
(10, 'Ambanja', 6, 30),
(11, 'Morondava', 7, 45),
(12, 'Andapa', 8, 15);

INSERT INTO type_besoin (id_type_besoin, nom_type_besoin) VALUES
(4, 'Hygiène'),
(5, 'Logement');

INSERT INTO produit (id_produit, nom_produit, id_type_besoin, prix_unitaire) VALUES
(4, 'Eau potable (bouteille)', 1, 2000.00),
(5, 'Huile alimentaire', 1, 8000.00),
(6, 'Couverture', 2, 18000.00),
(7, 'Pantalon', 2, 22000.00),
(8, 'Kit de premiers soins', 3, 12000.00),
(9, 'Savon', 4, 1500.00),
(10, 'Gel hydroalcoolique', 4, 5000.00),
(11, 'Tente familiale', 5, 150000.00),
(12, 'Bâche plastique', 5, 35000.00),
(13, 'Lait en poudre', 1, 12000.00),
(14, 'Moustiquaire', 3, 25000.00),
(15, 'Seau', 4, 8000.00);

INSERT INTO besoin (id_besoin, id_ville, id_produit, quantite, date_besoin) VALUES
(4, 4, 4, 300, '2026-02-19 08:00:00'),
(5, 5, 5, 120, '2026-02-19 08:30:00'),
(6, 6, 6, 80, '2026-02-19 09:00:00'),
(7, 7, 7, 60, '2026-02-19 09:30:00'),
(8, 8, 8, 150, '2026-02-19 10:00:00'),
(9, 9, 9, 400, '2026-02-19 10:30:00'),
(10, 10, 10, 200, '2026-02-19 11:00:00'),
(11, 11, 11, 25, '2026-02-19 11:30:00'),
(12, 12, 12, 90, '2026-02-19 12:00:00'),
(13, 4, 13, 70, '2026-02-19 12:30:00'),
(14, 5, 14, 110, '2026-02-19 13:00:00'),
(15, 6, 15, 95, '2026-02-19 13:30:00'),
(16, 7, 4, 220, '2026-02-19 14:00:00'),
(17, 8, 5, 140, '2026-02-19 14:30:00'),
(18, 9, 6, 50, '2026-02-19 15:00:00'),
(19, 10, 9, 350, '2026-02-19 15:30:00'),
(20, 11, 12, 60, '2026-02-19 16:00:00');

INSERT INTO don (id_don, id_produit, quantite, date_don) VALUES
(4, 4, 200, '2026-02-19 09:10:00'),
(5, 5, 80, '2026-02-19 09:20:00'),
(6, 6, 40, '2026-02-19 09:40:00'),
(7, 7, 30, '2026-02-19 10:10:00'),
(8, 8, 100, '2026-02-19 10:20:00'),
(9, 9, 300, '2026-02-19 10:40:00'),
(10, 10, 120, '2026-02-19 11:10:00'),
(11, 11, 10, '2026-02-19 11:40:00'),
(12, 12, 70, '2026-02-19 12:10:00'),
(13, 13, 60, '2026-02-19 12:40:00'),
(14, 14, 90, '2026-02-19 13:10:00'),
(15, 15, 75, '2026-02-19 13:40:00'),
(16, 4, 150, '2026-02-19 14:10:00'),
(17, 5, 100, '2026-02-19 14:40:00'),
(18, 6, 30, '2026-02-19 15:10:00'),
(19, 9, 250, '2026-02-19 15:40:00'),
(20, 12, 40, '2026-02-19 16:10:00');

INSERT INTO distribution (id_distribution, id_besoin, id_don, date_distribution, quantite_distribution) VALUES
(4, 4, 4, '2026-02-19 17:00:00', 150),
(5, 5, 5, '2026-02-19 17:10:00', 60),
(6, 6, 6, '2026-02-19 17:20:00', 30),
(7, 7, 7, '2026-02-19 17:30:00', 20),
(8, 8, 8, '2026-02-19 17:40:00', 70),
(9, 9, 9, '2026-02-19 17:50:00', 200),
(10, 10, 10, '2026-02-19 18:00:00', 80),
(11, 11, 11, '2026-02-19 18:10:00', 5),
(12, 12, 12, '2026-02-19 18:20:00', 40),
(13, 13, 13, '2026-02-19 18:30:00', 30),
(14, 14, 14, '2026-02-19 18:40:00', 50),
(15, 15, 15, '2026-02-19 18:50:00', 40);

INSERT INTO besoin_achat (id_achat, id_besoin, id_don, id_ville, montant_achat, frais_pourcent, montant_frais, montant_total, date_achat) VALUES
(1, 4, NULL, 4, 200000.00, 10.00, 20000.00, 220000.00, '2026-02-19 19:00:00'),
(2, 5, NULL, 5, 300000.00, 10.00, 30000.00, 330000.00, '2026-02-19 19:10:00'),
(3, 6, NULL, 6, 400000.00, 10.00, 40000.00, 440000.00, '2026-02-19 19:20:00'),
(4, 7, NULL, 7, 350000.00, 10.00, 35000.00, 385000.00, '2026-02-19 19:30:00'),
(5, 8, NULL, 8, 250000.00, 10.00, 25000.00, 275000.00, '2026-02-19 19:40:00'),
(6, 9, NULL, 9, 150000.00, 10.00, 15000.00, 165000.00, '2026-02-19 19:50:00'),
(7, 10, NULL, 10, 180000.00, 10.00, 18000.00, 198000.00, '2026-02-19 20:00:00'),
(8, 11, NULL, 11, 120000.00, 10.00, 12000.00, 132000.00, '2026-02-19 20:10:00'),
(9, 12, NULL, 12, 90000.00, 10.00, 9000.00, 99000.00, '2026-02-19 20:20:00'),
(10, 13, NULL, 4, 160000.00, 10.00, 16000.00, 176000.00, '2026-02-19 20:30:00'),
(11, 14, NULL, 5, 220000.00, 10.00, 22000.00, 242000.00, '2026-02-19 20:40:00'),
(12, 15, NULL, 6, 140000.00, 10.00, 14000.00, 154000.00, '2026-02-19 20:50:00'),
(13, 16, NULL, 7, 200000.00, 10.00, 20000.00, 220000.00, '2026-02-19 21:00:00'),
(14, 17, NULL, 8, 260000.00, 10.00, 26000.00, 286000.00, '2026-02-19 21:10:00'),
(15, 18, NULL, 9, 180000.00, 10.00, 18000.00, 198000.00, '2026-02-19 21:20:00');
