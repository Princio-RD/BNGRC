<?php

namespace app\models;

use Flight;
use PDO;

class ProduitModel
{
   
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT p.id_produit, p.nom_produit, p.prix_unitaire, tb.nom_type_besoin
             FROM produit p
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             ORDER BY p.nom_produit'
        );
        return $stmt->fetchAll();
    }

   
    public static function getById(int $id): array|false
    {
        $stmt = Flight::db()->prepare(
            'SELECT p.id_produit, p.nom_produit, p.prix_unitaire, p.id_type_besoin, tb.nom_type_besoin
             FROM produit p
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             WHERE p.id_produit = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
