<?php

namespace app\models;

use Flight;

class DonArgentModel
{
    public static function getAll()
    {
        return Flight::db()->query("SELECT * FROM don_argent ORDER BY date_don DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function insert($montant, $date_don)
    {
        $stmt = Flight::db()->prepare("INSERT INTO don_argent (montant, date_don) VALUES (?, ?)");
        return $stmt->execute([$montant, $date_don]);
    }
}