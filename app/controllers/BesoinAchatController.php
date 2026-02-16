<?php

namespace app\controllers;

use Flight;
use app\models\BesoinAchatModel;
use app\models\BesoinModel;
use app\models\DonModel;
use app\models\VilleModel;

class BesoinAchatController
{
    public static function index()
    {
        $achats = BesoinAchatModel::getAll();
        $villes = VilleModel::getAll();

        Flight::render('besoin_achats', [
            'achats' => $achats,
            'villes' => $villes,
            'ville_id' => null,
        ]);
    }

    public static function listByVille(int $id)
    {
        $achats = BesoinAchatModel::getByVille($id);
        $villes = VilleModel::getAll();

        Flight::render('besoin_achats', [
            'achats' => $achats,
            'villes' => $villes,
            'ville_id' => $id,
        ]);
    }

    public static function simulationForm()
    {
        $villeId = Flight::request()->query['ville_id'] ?? null;
        $villeId = $villeId !== null ? (int) $villeId : null;

        $besoins = BesoinModel::getRemainingNeedsByVille($villeId);
        $villes = VilleModel::getAll();

        $config = Flight::get('app.config');
        $fraisPourcent = (float) ($config['achats']['frais_pourcent'] ?? 0);

        Flight::render('simulation', [
            'besoins' => $besoins,
            'villes' => $villes,
            'ville_id' => $villeId,
            'frais_pourcent' => $fraisPourcent,
            'result' => null,
            'errors' => [],
            'success' => null,
        ]);
    }

    public static function simulation()
    {
        $idBesoin = (int) Flight::request()->data->id_besoin;
        $montantAchat = (float) Flight::request()->data->montant_achat;

        $config = Flight::get('app.config');
        $fraisPourcent = (float) ($config['achats']['frais_pourcent'] ?? 0);

        $errors = [];
        $besoin = BesoinModel::getById($idBesoin);

        if ($besoin === false) {
            $errors[] = 'Besoin introuvable.';
        }

        if ($montantAchat <= 0) {
            $errors[] = 'Le montant d\'achat doit être positif.';
        }

        $villeId = $besoin !== false ? (int) $besoin['id_ville'] : null;
        $besoins = BesoinModel::getRemainingNeedsByVille($villeId);
        $villes = VilleModel::getAll();

        $montantTotalBesoin = 0.0;
        $montantAchete = 0.0;
        $montantRestantBesoin = 0.0;

        if ($besoin !== false) {
            $montantTotalBesoin = (float) $besoin['prix_unitaire'] * (float) $besoin['quantite'];
            $montantAchete = BesoinAchatModel::getTotalByBesoin($idBesoin);
            $montantRestantBesoin = round($montantTotalBesoin - $montantAchete, 2);

            if (BesoinAchatModel::verifyDoublon($idBesoin) === true) {
                $errors[] = 'Un achat existe déjà pour ce besoin.';
            }

            if ($montantRestantBesoin <= 0) {
                $errors[] = 'Ce besoin est déjà entièrement satisfait.';
            } elseif ($montantAchat > $montantRestantBesoin) {
                $errors[] = 'Le montant dépasse le besoin restant.';
            }
        }

        $calc = BesoinAchatModel::calculateTotal($montantAchat, $fraisPourcent);
        $montantFrais = $calc['montant_frais'];
        $montantTotal = $calc['montant_total'];

        if (empty($errors) && DonModel::verifyAvailableAmount($montantTotal) === false) {
            $errors[] = 'Dons insuffisants pour couvrir cet achat (frais inclus).';
        }

        $result = [
            'id_besoin' => $idBesoin,
            'montant_achat' => $montantAchat,
            'frais_pourcent' => $fraisPourcent,
            'montant_frais' => $montantFrais,
            'montant_total' => $montantTotal,
            'besoin' => $besoin,
            'montant_restant_besoin' => $montantRestantBesoin,
            'dons_restants' => DonModel::getRemainingAmount(),
        ];

        Flight::render('simulation', [
            'besoins' => $besoins,
            'villes' => $villes,
            'ville_id' => $villeId,
            'frais_pourcent' => $fraisPourcent,
            'result' => $result,
            'errors' => $errors,
            'success' => null,
        ]);
    }

    public static function validateSimulation()
    {
        $idBesoin = (int) Flight::request()->data->id_besoin;
        $montantAchat = (float) Flight::request()->data->montant_achat;

        $config = Flight::get('app.config');
        $fraisPourcent = (float) ($config['achats']['frais_pourcent'] ?? 0);

        $errors = [];
        $besoin = BesoinModel::getById($idBesoin);

        if ($besoin === false) {
            $errors[] = 'Besoin introuvable.';
        }

        if ($montantAchat <= 0) {
            $errors[] = 'Le montant d\'achat doit être positif.';
        }

        $villeId = $besoin !== false ? (int) $besoin['id_ville'] : null;
        $besoins = BesoinModel::getRemainingNeedsByVille($villeId);
        $villes = VilleModel::getAll();

        $montantTotalBesoin = 0.0;
        $montantAchete = 0.0;
        $montantRestantBesoin = 0.0;

        if ($besoin !== false) {
            $montantTotalBesoin = (float) $besoin['prix_unitaire'] * (float) $besoin['quantite'];
            $montantAchete = BesoinAchatModel::getTotalByBesoin($idBesoin);
            $montantRestantBesoin = round($montantTotalBesoin - $montantAchete, 2);

            if (BesoinAchatModel::verifyDoublon($idBesoin) === true) {
                $errors[] = 'Un achat existe déjà pour ce besoin.';
            }

            if ($montantRestantBesoin <= 0) {
                $errors[] = 'Ce besoin est déjà entièrement satisfait.';
            } elseif ($montantAchat > $montantRestantBesoin) {
                $errors[] = 'Le montant dépasse le besoin restant.';
            }
        }

        $calc = BesoinAchatModel::calculateTotal($montantAchat, $fraisPourcent);
        $montantFrais = $calc['montant_frais'];
        $montantTotal = $calc['montant_total'];

        if (empty($errors) && DonModel::verifyAvailableAmount($montantTotal) === false) {
            $errors[] = 'Dons insuffisants pour couvrir cet achat (frais inclus).';
        }

        if (!empty($errors)) {
            Flight::render('simulation', [
                'besoins' => $besoins,
                'villes' => $villes,
                'ville_id' => $villeId,
                'frais_pourcent' => $fraisPourcent,
                'result' => [
                    'id_besoin' => $idBesoin,
                    'montant_achat' => $montantAchat,
                    'frais_pourcent' => $fraisPourcent,
                    'montant_frais' => $montantFrais,
                    'montant_total' => $montantTotal,
                    'besoin' => $besoin,
                    'montant_restant_besoin' => $montantRestantBesoin,
                    'dons_restants' => DonModel::getRemainingAmount(),
                ],
                'errors' => $errors,
                'success' => null,
            ]);
            return;
        }

        $db = Flight::db();
        $db->beginTransaction();
        try {
            BesoinAchatModel::create([
                'id_besoin' => $idBesoin,
                'id_don' => null,
                'id_ville' => $villeId,
                'montant_achat' => $montantAchat,
                'frais_pourcent' => $fraisPourcent,
                'montant_frais' => $montantFrais,
                'montant_total' => $montantTotal,
                'date_achat' => date('Y-m-d H:i:s'),
            ]);
            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            $errors[] = 'Erreur lors de la validation de l\'achat.';
        }

        $besoins = BesoinModel::getRemainingNeedsByVille($villeId);

        Flight::render('simulation', [
            'besoins' => $besoins,
            'villes' => $villes,
            'ville_id' => $villeId,
            'frais_pourcent' => $fraisPourcent,
            'result' => null,
            'errors' => $errors,
            'success' => empty($errors) ? 'Achat validé avec succès.' : null,
        ]);
    }
}
