<?php
namespace app\models;

use Flight;

class AchatModel {
    public static function getAllDetails($id_ville = null) {
        $sql = "
            SELECT a.*, b.id_ville, v.nom_ville, p.nom_produit
            FROM achat a
            JOIN besoin b ON a.id_besoin = b.id_besoin
            JOIN ville v ON b.id_ville = v.id_ville
            JOIN produit p ON b.id_produit = p.id_produit
        ";
        if ($id_ville) {
            $sql .= " WHERE v.id_ville = :id_ville";
        }
        $sql .= " ORDER BY a.date_achat DESC";
        
        $stmt = Flight::db()->prepare($sql);
        if ($id_ville) {
            $stmt->bindValue(':id_ville', $id_ville, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function insert($id_besoin, $quantite, $cout_unitaire, $frais_pourcentage, $cout_total) {
        $sql = "INSERT INTO achat (id_besoin, quantite_achetee, cout_unitaire_achat, frais_achat_pourcentage, cout_total_achat, date_achat) VALUES (:id_besoin, :quantite, :cout_unitaire, :frais, :cout_total, NOW())";
        $stmt = Flight::db()->prepare($sql);
        $stmt->bindValue(':id_besoin', $id_besoin, \PDO::PARAM_INT);
        $stmt->bindValue(':quantite', $quantite, \PDO::PARAM_INT);
        $stmt->bindValue(':cout_unitaire', $cout_unitaire);
        $stmt->bindValue(':frais', $frais_pourcentage);
        $stmt->bindValue(':cout_total', $cout_total);
        return $stmt->execute();
    }

    public static function getRemainingNeeds($id_ville = null, $order_by = 'date') {
        $sql = "SELECT vbr.*, v.id_ville, v.nombre_sinistres 
                FROM v_besoins_restants vbr
                JOIN besoin b ON vbr.id_besoin = b.id_besoin
                JOIN ville v ON b.id_ville = v.id_ville
                WHERE vbr.quantite_restante > 0";
        if ($id_ville) {
            $sql .= " AND v.id_ville = :id_ville";
        }
        
        if ($order_by === 'quantite') {
            $sql .= " ORDER BY vbr.quantite_restante ASC"; 
        } else if ($order_by === 'urgence') {
            $sql .= " ORDER BY v.nombre_sinistres DESC, vbr.date_besoin ASC"; 
        } else {
            $sql .= " ORDER BY vbr.date_besoin ASC"; 
        }

        $stmt = Flight::db()->prepare($sql);
        if ($id_ville) {
            $stmt->bindValue(':id_ville', $id_ville, \PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getRemainingStock($id_produit) {
        $stmt = Flight::db()->prepare("
            SELECT p.nom_produit,
                   (COALESCE(d.total_don, 0) - COALESCE(dist.total_dist, 0)) AS quantite_restante
            FROM produit p
            LEFT JOIN (
                SELECT id_produit, SUM(quantite) AS total_don
                FROM don GROUP BY id_produit
            ) d ON p.id_produit = d.id_produit
            LEFT JOIN (
                SELECT don.id_produit, SUM(distribution.quantite_distribution) AS total_dist
                FROM distribution
                JOIN don ON distribution.id_don = don.id_don
                GROUP BY don.id_produit
            ) dist ON p.id_produit = dist.id_produit
            WHERE p.id_produit = :id_produit
        ");
        $stmt->bindValue(':id_produit', $id_produit, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? (float)$result['quantite_restante'] : 0;
    }

    public static function getCashBalance() {
        $stmt_dons = Flight::db()->prepare("SELECT SUM(montant) FROM don_argent");
        $stmt_dons->execute();
        $total_dons = (float)$stmt_dons->fetchColumn();

        $stmt_achats = Flight::db()->prepare("SELECT SUM(cout_total_achat) FROM achat");
        $stmt_achats->execute();
        $total_achats = (float)$stmt_achats->fetchColumn();

        return $total_dons - $total_achats;
    }

    public static function getPurchaseFee() {
        $stmt = Flight::db()->prepare("SELECT valeur FROM configuration WHERE cle = 'frais_achat_pourcentage'");
        $stmt->execute();
        return (float)$stmt->fetchColumn();
    }

    public static function getAvailableInKindDonations() {
        $sql = "
            SELECT 
                d.id_don, 
                d.id_produit, 
                (d.quantite - COALESCE(dist_agg.total_distribue, 0)) as quantite_restante
            FROM don d
            LEFT JOIN (
                SELECT id_don, SUM(quantite_distribution) as total_distribue FROM distribution GROUP BY id_don
            ) dist_agg ON d.id_don = dist_agg.id_don
            HAVING quantite_restante > 0
            ORDER BY d.date_don ASC"; // FIFO: on utilise les plus anciens dons en premier
        $stmt = Flight::db()->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}