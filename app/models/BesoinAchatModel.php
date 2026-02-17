<?php

namespace app\models;

use Flight;

class BesoinAchatModel
{
    /**
     * Récupérer tous les achats avec détails
     */
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT ba.id_achat, ba.id_besoin, ba.id_ville, ba.montant_achat, ba.frais_pourcent,
                    ba.montant_frais, ba.montant_total, ba.date_achat,
                    v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    (b.quantite * p.prix_unitaire) AS montant_besoin
             FROM besoin_achat ba
             JOIN besoin b ON ba.id_besoin = b.id_besoin
             JOIN ville v ON ba.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             ORDER BY ba.date_achat DESC'
        );
        return $stmt->fetchAll();
    }

    /**
     * Récupérer les achats filtrés par ville
     */
    public static function getByVille(int $villeId): array
    {
        $stmt = Flight::db()->prepare(
            'SELECT ba.id_achat, ba.id_besoin, ba.id_ville, ba.montant_achat, ba.frais_pourcent,
                    ba.montant_frais, ba.montant_total, ba.date_achat,
                    v.nom_ville, p.nom_produit, tb.nom_type_besoin,
                    (b.quantite * p.prix_unitaire) AS montant_besoin
             FROM besoin_achat ba
             JOIN besoin b ON ba.id_besoin = b.id_besoin
             JOIN ville v ON ba.id_ville = v.id_ville
             JOIN produit p ON b.id_produit = p.id_produit
             JOIN type_besoin tb ON p.id_type_besoin = tb.id_type_besoin
             WHERE ba.id_ville = ?
             ORDER BY ba.date_achat DESC'
        );
        $stmt->execute([$villeId]);
        return $stmt->fetchAll();
    }

    /**
     * Créer un achat
     */
    public static function create(array $data): bool
    {
        $stmt = Flight::db()->prepare(
            'INSERT INTO besoin_achat
                (id_besoin, id_don, id_ville, montant_achat, frais_pourcent, montant_frais, montant_total, date_achat)
             VALUES
                (:id_besoin, :id_don, :id_ville, :montant_achat, :frais_pourcent, :montant_frais, :montant_total, :date_achat)'
        );
        return $stmt->execute([
            ':id_besoin' => $data['id_besoin'],
            ':id_don' => $data['id_don'],
            ':id_ville' => $data['id_ville'],
            ':montant_achat' => $data['montant_achat'],
            ':frais_pourcent' => $data['frais_pourcent'],
            ':montant_frais' => $data['montant_frais'],
            ':montant_total' => $data['montant_total'],
            ':date_achat' => $data['date_achat'],
        ]);
    }

    /**
     * Calculer le total avec frais
     */
    public static function calculateTotal(float $montant, float $fraisPourcent): array
    {
        $montantFrais = round($montant * $fraisPourcent / 100, 2);
        $montantTotal = round($montant + $montantFrais, 2);
        return [
            'montant_frais' => $montantFrais,
            'montant_total' => $montantTotal,
        ];
    }

    /**
     * Vérifier si un achat existe déjà pour un besoin
     */
    public static function verifyDoublon(int $besoinId): bool
    {
        $stmt = Flight::db()->prepare('SELECT COUNT(*) FROM besoin_achat WHERE id_besoin = ?');
        $stmt->execute([$besoinId]);
        return (int) $stmt->fetchColumn() > 0;
    }

    /**
     * Total dépensé (montant_total)
     */
    public static function getTotalSpent(): float
    {
        $stmt = Flight::db()->query('SELECT COALESCE(SUM(montant_total), 0) FROM besoin_achat');
        return (float) $stmt->fetchColumn();
    }

    /**
     * Total acheté (montant_achat)
     */
    public static function getTotalBase(): float
    {
        $stmt = Flight::db()->query('SELECT COALESCE(SUM(montant_achat), 0) FROM besoin_achat');
        return (float) $stmt->fetchColumn();
    }

    /**
     * Total acheté par besoin
     */
    public static function getTotalByBesoin(int $besoinId): float
    {
        $stmt = Flight::db()->prepare('SELECT COALESCE(SUM(montant_achat), 0) FROM besoin_achat WHERE id_besoin = ?');
        $stmt->execute([$besoinId]);
        return (float) $stmt->fetchColumn();
    }
}
