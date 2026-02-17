<?php

namespace app\models;

use Flight;

class DonArgentModel
{
    public static function getAll()
    {
        return Flight::db()->query("SELECT * FROM don_argent ORDER BY date_don DESC")->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getById($id)
    {
        $stmt = Flight::db()->prepare("SELECT * FROM don_argent WHERE id_don_argent = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function insert($montant, $date_don)
    {
        $stmt = Flight::db()->prepare("INSERT INTO don_argent (montant, date_don) VALUES (?, ?)");
        return $stmt->execute([$montant, $date_don]);
    }

    public static function update($id, $montant, $date_don)
    {
        $stmt = Flight::db()->prepare("UPDATE don_argent SET montant = ?, date_don = ? WHERE id_don_argent = ?");
        return $stmt->execute([$montant, $date_don, $id]);
    }

    public static function delete($id)
    {
        $stmt = Flight::db()->prepare("DELETE FROM don_argent WHERE id_don_argent = ?");
        return $stmt->execute([$id]);
    }

    public static function getTotal()
    {
        $result = Flight::db()->query("SELECT COALESCE(SUM(montant), 0) as total FROM don_argent")->fetch(\PDO::FETCH_ASSOC);
        return $result['total'];
    }
}
