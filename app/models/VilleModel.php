<?php

namespace app\models;

use Flight;
use PDO;

class VilleModel
{
    /**
     * Récupérer toutes les villes avec leur région
     */
    public static function getAll(): array
    {
        $stmt = Flight::db()->query(
            'SELECT v.id_ville, v.nom_ville, v.nombre_sinistres, r.nom_region 
             FROM ville v 
             JOIN region r ON v.id_region = r.id_region 
             ORDER BY v.nom_ville'
        );
        return $stmt->fetchAll();
    }

    /**
     * Récupérer une ville par son ID
     */
    public static function getById(int $id): array|false
    {
        $stmt = Flight::db()->prepare(
            'SELECT v.id_ville, v.nom_ville, v.id_region, v.nombre_sinistres, r.nom_region 
             FROM ville v 
             JOIN region r ON v.id_region = r.id_region 
             WHERE v.id_ville = ?'
        );
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Insérer une nouvelle ville
     */
    public static function insert(string $nom_ville, int $id_region, int $nombre_sinistres = 0): bool
    {
        $stmt = Flight::db()->prepare(
            'INSERT INTO ville (nom_ville, id_region, nombre_sinistres) VALUES (?, ?, ?)'
        );
        return $stmt->execute([$nom_ville, $id_region, $nombre_sinistres]);
    }

    /**
     * Mettre à jour une ville
     */
    public static function update(int $id, string $nom_ville, int $id_region, int $nombre_sinistres = 0): bool
    {
        $stmt = Flight::db()->prepare(
            'UPDATE ville SET nom_ville = ?, id_region = ?, nombre_sinistres = ? WHERE id_ville = ?'
        );
        return $stmt->execute([$nom_ville, $id_region, $nombre_sinistres, $id]);
    }

    /**
     * Supprimer une ville
     */
    public static function delete(int $id): bool
    {
        $stmt = Flight::db()->prepare('DELETE FROM ville WHERE id_ville = ?');
        return $stmt->execute([$id]);
    }
}
