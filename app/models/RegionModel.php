<?php

namespace app\models;

use Flight;
use PDO;

class RegionModel
{
 
    public static function getAll(): array
    {
        $stmt = Flight::db()->query('SELECT * FROM region ORDER BY nom_region');
        return $stmt->fetchAll();
    }

    public static function getById(int $id): array|false
    {
        $stmt = Flight::db()->prepare('SELECT * FROM region WHERE id_region = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
