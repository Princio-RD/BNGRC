<?php

namespace app\models;

use Flight;
use PDO;

class DistributionModel
{
   
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT dist.id_distribution, 
                    v.nom_ville, 
                    p_b.nom_produit AS produit_besoin,
                    b.quantite AS quantite_besoin,
                    p_d.nom_produit AS produit_don,
                    d.quantite AS quantite_don,
                    dist.quantite_distribution, 
                    dist.date_distribution
             FROM distribution dist
             JOIN besoin b ON dist.id_besoin = b.id_besoin
             JOIN don d ON dist.id_don = d.id_don
             JOIN ville v ON b.id_ville = v.id_ville
             JOIN produit p_b ON b.id_produit = p_b.id_produit
             JOIN produit p_d ON d.id_produit = p_d.id_produit
             ORDER BY dist.date_distribution DESC'
        );
        return $stmt->fetchAll();
    }

    public static function insert(int $id_besoin, int $id_don, string $date_distribution, int $quantite): bool
    {
        $sql = "INSERT INTO distribution (id_besoin, id_don, date_distribution, quantite_distribution) VALUES (:id_besoin, :id_don, :date_distribution, :quantite_distribution)";
        $stmt = Flight::db()->prepare($sql);
        $stmt->bindValue(':id_besoin', $id_besoin, PDO::PARAM_INT);
        $stmt->bindValue(':id_don', $id_don, PDO::PARAM_INT);
        $stmt->bindValue(':date_distribution', $date_distribution);
        $stmt->bindValue(':quantite_distribution', $quantite, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
