<?php

namespace app\models;

use Flight;
use PDO;

class DistributionModel
{
    /**
     * Récupérer toutes les distributions avec détails
     */
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
}
