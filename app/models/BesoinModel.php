<?php

namespace app\models;

use Flight;
use PDO;

class BesoinModel
{
    /**
     * Récupérer tous les besoins avec ville et produit
     */
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT b.id_besoin, v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    b.quantite, b.date_besoin, p.prix_unitaire,
                    (b.quantite * p.prix_unitaire) AS montant_total
             FROM besoin b
             JOIN ville v ON b.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             ORDER BY b.date_besoin DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un besoin par son ID
     */
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

    /**
     * Insérer un nouveau besoin
     */
    public static function insert(int $id_ville, int $id_produit, int $quantite, string $date_besoin): bool
    {
        $stmt = Flight::db()->prepare(
            'INSERT INTO besoin (id_ville, id_produit, quantite, date_besoin) VALUES (?, ?, ?, ?)'
        );
        return $stmt->execute([$id_ville, $id_produit, $quantite, $date_besoin]);
    }

    /**
     * Mettre à jour un besoin
     */
    public static function update(int $id, int $id_ville, int $id_produit, int $quantite, string $date_besoin): bool
    {
        $stmt = Flight::db()->prepare(
            'UPDATE besoin SET id_ville = ?, id_produit = ?, quantite = ?, date_besoin = ? WHERE id_besoin = ?'
        );
        return $stmt->execute([$id_ville, $id_produit, $quantite, $date_besoin, $id]);
    }

    /**
     * Supprimer un besoin
     */
    public static function delete(int $id): bool
    {
        $stmt = Flight::db()->prepare('DELETE FROM besoin WHERE id_besoin = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Récupérer les besoins restants (montants) par ville
     */
    public static function getRemainingNeedsByVille(?int $villeId = null): array
    {
        $sql =
            'SELECT b.id_besoin, b.id_ville, v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    b.quantite, p.prix_unitaire,
                    (b.quantite * p.prix_unitaire) AS montant_total,
                    COALESCE(SUM(ba.montant_achat), 0) AS montant_achete,
                    ((b.quantite * p.prix_unitaire) - COALESCE(SUM(ba.montant_achat), 0)) AS montant_restant
             FROM besoin b
             JOIN ville v ON b.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             LEFT JOIN besoin_achat ba ON ba.id_besoin = b.id_besoin';

        $params = [];
        if ($villeId !== null) {
            $sql .= ' WHERE b.id_ville = ?';
            $params[] = $villeId;
        }

        $sql .= ' GROUP BY b.id_besoin, b.id_ville, v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    b.quantite, p.prix_unitaire
                  HAVING montant_restant > 0
                  ORDER BY b.date_besoin DESC';

        $stmt = Flight::db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Résumé des besoins (total, acheté, restant)
     */
    public static function getTotalNeedsSummary(): array
    {
        $stmt = Flight::db()->query(
            'SELECT
                COALESCE(SUM(t.montant_total), 0) AS total_besoins,
                COALESCE(SUM(t.total_achete), 0) AS total_achete,
                COALESCE(SUM(t.montant_total - t.total_achete), 0) AS total_restant
             FROM (
                SELECT b.id_besoin,
                       (b.quantite * p.prix_unitaire) AS montant_total,
                       COALESCE(ba.total_achete, 0) AS total_achete
                FROM besoin b
                JOIN produit p ON b.id_produit = p.id_produit
                LEFT JOIN (
                    SELECT id_besoin, SUM(montant_achat) AS total_achete
                    FROM besoin_achat
                    GROUP BY id_besoin
                ) ba ON ba.id_besoin = b.id_besoin
             ) t'
        );
        return (array) $stmt->fetch();
    }

    /**
     * Détails des besoins avec statut (satisfait/restant)
     */
    public static function getNeedsWithStatus(): array
    {
        $stmt = Flight::db()->query(
            'SELECT b.id_besoin, v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    b.quantite, p.prix_unitaire,
                    (b.quantite * p.prix_unitaire) AS montant_total,
                    COALESCE(ba.total_achete, 0) AS montant_achete,
                    ((b.quantite * p.prix_unitaire) - COALESCE(ba.total_achete, 0)) AS montant_restant
             FROM besoin b
             JOIN ville v ON b.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             LEFT JOIN (
                SELECT id_besoin, SUM(montant_achat) AS total_achete
                FROM besoin_achat
                GROUP BY id_besoin
             ) ba ON ba.id_besoin = b.id_besoin
             ORDER BY b.date_besoin DESC'
        );
        return $stmt->fetchAll();
    }
}
