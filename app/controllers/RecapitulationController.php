<?php

namespace app\controllers;

use Flight;
use app\models\BesoinModel;
use app\models\DonModel;
use app\models\BesoinAchatModel;

class RecapitulationController
{
    public static function show()
    {
        $summary = BesoinModel::getTotalNeedsSummary();
        $details = BesoinModel::getNeedsWithStatus();

        $totalDons = DonModel::getTotalAmount();
        $donsRestants = DonModel::getRemainingAmount();
        $donsUtilises = round($totalDons - $donsRestants, 2);

        Flight::render('recap', [
            'summary' => $summary,
            'details' => $details,
            'total_dons' => $totalDons,
            'dons_restants' => $donsRestants,
            'dons_utilises' => $donsUtilises,
        ]);
    }

    public static function refresh()
    {
        $summary = BesoinModel::getTotalNeedsSummary();
        $details = BesoinModel::getNeedsWithStatus();

        $totalDons = DonModel::getTotalAmount();
        $donsRestants = DonModel::getRemainingAmount();
        $donsUtilises = round($totalDons - $donsRestants, 2);

        Flight::json([
            'summary' => $summary,
            'details' => $details,
            'total_dons' => $totalDons,
            'dons_restants' => $donsRestants,
            'dons_utilises' => $donsUtilises,
        ]);
    }
}
