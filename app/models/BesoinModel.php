<?php

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    
    public static function getAll(): array
    {
        // La vue v_besoins_restants contient déjà toutes les infos nécessaires
        $sql = "SELECT vbr.*,
                       (vbr.quantite_demandee * vbr.prix_unitaire) AS montant_total
                FROM v_besoins_restants vbr
                WHERE vbr.quantite_restante > 0
                ORDER BY vbr.date_besoin DESC";
        $stmt = Flight::db()->query($sql);
        return $stmt->fetchAll();
    }

    
    public static function getById(int $id): array|false
    {
        $stmt = Flight::db()->prepare(
            'SELECT b.id_besoin, b.id_ville, b.id_produit, b.quantite, b.date_besoin,
                    v.nom_ville, p.nom_produit, p.prix_unitaire
             FROM besoin b
             JOIN ville v ON b.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             WHERE b.id_besoin = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

  
    public static function insert(int $id_ville, int $id_produit, int $quantite, string $date_besoin): bool
    {
        $stmt = Flight::db()->prepare(
            'INSERT INTO besoin (id_ville, id_produit, quantite, date_besoin) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$id_ville, $id_produit, $quantite, $date_besoin]);
    }

   
    public static function update(int $id, int $id_ville, int $id_produit, int $quantite, string $date_besoin): bool
    {
        $stmt = Flight::db()->prepare(
            'UPDATE besoin SET id_ville = ?, id_produit = ?, quantite = ?, date_besoin = ? WHERE id_besoin = ?'
        );
        return $stmt->execute([$id_ville, $id_produit, $quantite, $date_besoin, $id]);
    }

  
    public static function delete(int $id): bool
    {
        // Suppression des dépendances (achats et distributions) pour éviter l'erreur de clé étrangère
        Flight::db()->prepare('DELETE FROM achat WHERE id_besoin = ?')->execute([$id]);
        Flight::db()->prepare('DELETE FROM distribution WHERE id_besoin = ?')->execute([$id]);

        $stmt = Flight::db()->prepare('DELETE FROM besoin WHERE id_besoin = ?');
        return $stmt->execute([$id]);
    }
}
