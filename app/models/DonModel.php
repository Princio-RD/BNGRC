<?php

namespace app\models;

use Flight;
use PDO;

class DonModel
{
    /**
     * Récupérer tous les dons avec le produit
     */
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT d.id_don, p.nom_produit, tb.nom_type_besoin,
                    d.quantite, d.date_don, p.prix_unitaire,
                    (d.quantite * p.prix_unitaire) AS montant_total
             FROM don d
             JOIN produit p ON d.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             ORDER BY d.date_don DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Récupérer un don par son ID
     */
    public static function getById(int $id): array|false
    {
        $stmt = Flight::db()->prepare(
            'SELECT d.id_don, d.id_produit, d.quantite, d.date_don,
                    p.nom_produit, p.prix_unitaire
             FROM don d
             JOIN produit p ON d.id_produit = p.id_produit
             WHERE d.id_don = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Insérer un nouveau don
     */
    public static function insert(int $id_produit, int $quantite, string $date_don): bool
    {
        $stmt = Flight::db()->prepare(
            'INSERT INTO don (id_produit, quantite, date_don) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$id_produit, $quantite, $date_don]);
    }

    /**
     * Mettre à jour un don
     */
    public static function update(int $id, int $id_produit, int $quantite, string $date_don): bool
    {
        $stmt = Flight::db()->prepare(
            'UPDATE don SET id_produit = ?, quantite = ?, date_don = ? WHERE id_don = ?'
        );
        return $stmt->execute([$id_produit, $quantite, $date_don, $id]);
    }

    /**
     * Supprimer un don
     */
    public static function delete(int $id): bool
    {
        $stmt = Flight::db()->prepare('DELETE FROM don WHERE id_don = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Montant total des dons (somme quantite * prix_unitaire)
     */
    public static function getTotalAmount(): float
    {
        $stmt = Flight::db()->query(
            'SELECT COALESCE(SUM(d.quantite * p.prix_unitaire), 0)
             FROM don d
             JOIN produit p ON d.id_produit = p.id_produit'
        );
        return (float) $stmt->fetchColumn();
    }

    /**
     * Montant restant des dons (après achats)
     */
    public static function getRemainingAmount(): float
    {
        $totalDons = self::getTotalAmount();
        $stmt = Flight::db()->query('SELECT COALESCE(SUM(montant_total), 0) FROM besoin_achat');
        $totalAchats = (float) $stmt->fetchColumn();
        return round($totalDons - $totalAchats, 2);
    }

    /**
     * Vérifier si le montant est disponible
     */
    public static function verifyAvailableAmount(float $montantTotal): bool
    {
        return self::getRemainingAmount() >= $montantTotal;
    }
}
