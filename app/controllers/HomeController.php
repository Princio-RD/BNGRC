<?php

namespace app\controllers;

use Flight;
use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\DonModel;
use app\models\RegionModel;
use app\models\ProduitModel;
use app\models\DistributionModel;
use app\models\AchatModel;
use app\models\DonArgentModel;

class HomeController
{
 
    public static function index()
    {
        $besoins = BesoinModel::getAll();
        $dons = DonModel::getAll();
        $villes = VilleModel::getAll();
        $distributions = DistributionModel::getAll();
        $produits = ProduitModel::getAll();
        $dons_argent = DonArgentModel::getAll();

        $stats = [];
        try {
            $stats = Flight::db()->query("SELECT * FROM v_dons_par_ville")->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // La vue n'existe peut-être pas encore ou erreur DB
        }

        Flight::render('home', [
            'besoins' => $besoins,
            'dons' => $dons,
            'villes' => $villes,
            'distributions' => $distributions,
            'produits' => $produits,
            'stats' => $stats,
            'dons_argent' => $dons_argent
        ]);
    }

    
    public static function besoinList()
    {
        $besoins = BesoinModel::getAll();
        $villes = VilleModel::getAll();
        $produits = ProduitModel::getAll();

        Flight::render('besoin', [
            'besoins' => $besoins,
            'villes' => $villes,
            'produits' => $produits,
        ]);
    }

    public static function besoinInsert()
    {
        $id_ville = (int) Flight::request()->data->id_ville;
        $id_produit = (int) Flight::request()->data->id_produit;
        $quantite = (int) Flight::request()->data->quantite;
        $date_besoin = Flight::request()->data->date_besoin;

        BesoinModel::insert($id_ville, $id_produit, $quantite, $date_besoin);
        Flight::redirect('/besoin');
    }

    public static function besoinUpdate(int $id)
    {
        $id_ville = (int) Flight::request()->data->id_ville;
        $id_produit = (int) Flight::request()->data->id_produit;
        $quantite = (int) Flight::request()->data->quantite;
        $date_besoin = Flight::request()->data->date_besoin;

        BesoinModel::update($id, $id_ville, $id_produit, $quantite, $date_besoin);
        Flight::redirect('/besoin');
    }

    public static function besoinDelete(int $id)
    {
        BesoinModel::delete($id);
        Flight::redirect('/besoin');
    }

   
    public static function donList()
    {
        $dons = DonModel::getAll();
        $dons_argent = DonArgentModel::getAll();
        $produits = ProduitModel::getAll();

        Flight::render('dons', [
            'dons' => $dons,
            'dons_argent' => $dons_argent,
            'produits' => $produits,
        ]);
    }

    public static function donInsert()
    {
        $id_produit = (int) Flight::request()->data->id_produit;
        $quantite = (int) Flight::request()->data->quantite;
        $date_don = Flight::request()->data->date_don;

        DonModel::insert($id_produit, $quantite, $date_don);
        Flight::redirect('/dons');
    }

    public static function donArgentInsert()
    {
        $montant = (float) Flight::request()->data->montant;
        $date_don = Flight::request()->data->date_don;
        DonArgentModel::insert($montant, $date_don);
        Flight::redirect('/dons');
    }

    public static function donArgentUpdate(int $id)
    {
        $montant = (float) Flight::request()->data->montant;
        $date_don = Flight::request()->data->date_don;
        DonArgentModel::update($id, $montant, $date_don);
        Flight::redirect('/dons');
    }

    public static function donArgentDelete(int $id)
    {
        DonArgentModel::delete($id);
        Flight::redirect('/dons');
    }

    public static function donUpdate(int $id)
    {
        $id_produit = (int) Flight::request()->data->id_produit;
        $quantite = (int) Flight::request()->data->quantite;
        $date_don = Flight::request()->data->date_don;

        DonModel::update($id, $id_produit, $quantite, $date_don);
        Flight::redirect('/dons');
    }

    public static function donDelete(int $id)
    {
        DonModel::delete($id);
        Flight::redirect('/dons');
    }

   
    public static function villeList()
    {
        $villes = VilleModel::getAll();
        $regions = RegionModel::getAll();

        Flight::render('ville', [
            'villes' => $villes,
            'regions' => $regions,
        ]);
    }

    public static function villeInsert()
    {
        $nom_ville = Flight::request()->data->nom_ville;
        $id_region = (int) Flight::request()->data->id_region;
        $nombre_sinistres = (int) Flight::request()->data->nombre_sinistres;

        VilleModel::insert($nom_ville, $id_region, $nombre_sinistres);
        Flight::redirect('/ville');
    }

    public static function villeUpdate(int $id)
    {
        $nom_ville = Flight::request()->data->nom_ville;
        $id_region = (int) Flight::request()->data->id_region;
        $nombre_sinistres = (int) Flight::request()->data->nombre_sinistres;

        VilleModel::update($id, $nom_ville, $id_region, $nombre_sinistres);
        Flight::redirect('/ville');
    }

    public static function villeDelete(int $id)
    {
        VilleModel::delete($id);
        Flight::redirect('/ville');
    }

    public static function achatPage()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $id_ville_filtre = Flight::request()->query->id_ville;

        $besoins_restants = AchatModel::getRemainingNeeds($id_ville_filtre);
        $achats_historique = AchatModel::getAllDetails($id_ville_filtre);
        $villes = VilleModel::getAll();
        $solde_argent = AchatModel::getCashBalance();
        $frais = AchatModel::getPurchaseFee();

        $flash_error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);

        Flight::render('achats', [
            'besoins_restants' => $besoins_restants,
            'achats_historique' => $achats_historique,
            'villes' => $villes,
            'solde_argent' => $solde_argent,
            'frais' => $frais,
            'id_ville_filtre' => $id_ville_filtre,
            'flash_error' => $flash_error
        ]);
    }

    public static function achatProcess()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $id_besoin = (int) Flight::request()->data->id_besoin;
        $quantite_a_acheter = (int) Flight::request()->data->quantite_a_acheter;
        
        $stmt = Flight::db()->prepare("SELECT * FROM v_besoins_restants WHERE id_besoin = :id_besoin AND quantite_restante > 0");
        $stmt->bindValue(':id_besoin', $id_besoin, \PDO::PARAM_INT);
        $stmt->execute();
        $besoin = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$besoin || $quantite_a_acheter <= 0 || $quantite_a_acheter > $besoin['quantite_restante']) {
            $_SESSION['flash_error'] = "Demande d'achat invalide ou quantité incorrecte.";
            Flight::redirect('/achats');
            return;
        }

        $stock_restant = AchatModel::getRemainingStock($besoin['id_produit']);
        if ($stock_restant > 0) {
            $_SESSION['flash_error'] = "Achat impossible: le produit '" . htmlspecialchars($besoin['nom_produit']) . "' est déjà disponible dans les dons en nature (Stock: $stock_restant).";
            Flight::redirect('/achats');
            return;
        }

        $frais_pourcentage = AchatModel::getPurchaseFee();
        $cout_unitaire = (float)$besoin['prix_unitaire'];
        $cout_total = $quantite_a_acheter * $cout_unitaire * (1 + $frais_pourcentage / 100);
        $solde_argent = AchatModel::getCashBalance();

        if ($solde_argent < $cout_total) {
            $_SESSION['flash_error'] = "Fonds insuffisants. Coût: " . number_format($cout_total, 2) . " Ar, Solde: " . number_format($solde_argent, 2) . " Ar.";
            Flight::redirect('/achats');
            return;
        }

        AchatModel::insert($id_besoin, $quantite_a_acheter, $cout_unitaire, $frais_pourcentage, $cout_total);
        Flight::redirect('/achats');
    }

    public static function simulationPage()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $simulation_plan = $_SESSION['simulation_plan'] ?? null;
        $simulation_summary = $_SESSION['simulation_summary'] ?? null;
        $flash_message = $_SESSION['flash_message'] ?? null;

        
        unset($_SESSION['simulation_plan'], $_SESSION['simulation_summary'], $_SESSION['flash_message']);

        Flight::render('simulation', [
            'simulation_plan' => $simulation_plan,
            'simulation_summary' => $simulation_summary,
            'flash_message' => $flash_message
        ]);
    }

    private static function distribuer_proportionnellement(int $total_a_distribuer, array $besoins): array
    {
        $total_besoins = array_sum($besoins);
        if ($total_besoins == 0) {
            return array_fill_keys(array_keys($besoins), 0);
        }

        $allocations = [];
        $restes = [];

       
        foreach ($besoins as $id => $quantite_besoin) {
            $part_ideale = ($quantite_besoin / $total_besoins) * $total_a_distribuer;
            $allocations[$id] = floor($part_ideale);
            $restes[$id] = $part_ideale - $allocations[$id];
        }

        // 2. Calculer le reste à distribuer après l'allocation de base
        $total_alloue = array_sum($allocations);
        $reste_a_distribuer = $total_a_distribuer - $total_alloue;

        // 3. Trier les besoins par la partie fractionnaire (reste) en ordre décroissant
        arsort($restes);

        // 4. Distribuer le reste (1 par 1) aux besoins ayant les plus grands restes
        foreach (array_keys($restes) as $id) {
            if ($reste_a_distribuer-- > 0) {
                $allocations[$id]++;
            }
        }
        return $allocations;
    }

    public static function runSimulation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $type_simulation = Flight::request()->data->type_simulation ?? 'date';
        $besoins = AchatModel::getRemainingNeeds(null, $type_simulation); // L'ordre reste important pour la phase d'achat
        
        $dons_disponibles = AchatModel::getAvailableInKindDonations(); // Triés par date (FIFO)
        $solde_argent = AchatModel::getCashBalance();
        $frais_pourcentage = AchatModel::getPurchaseFee();

        $plan = [];
        $cout_total_achats = 0;

        // Map pour suivre les quantités restantes pour chaque besoin
        $besoins_restants_map = [];
        foreach ($besoins as $besoin) {
            $besoins_restants_map[$besoin['id_besoin']] = [
                'quantite_restante' => (int)$besoin['quantite_restante'],
                'details' => $besoin // garder toutes les infos
            ];
        }

        // --- 1. Phase de Distribution Proportionnelle des Dons en Nature ---
        foreach ($dons_disponibles as $don) {
            $id_produit_don = $don['id_produit'];
            $quantite_don = (int)$don['quantite_restante'];
            if ($quantite_don <= 0) continue;

            // Trouver tous les besoins actuels pour ce produit
            $besoins_pour_produit = [];
            foreach ($besoins_restants_map as $id_besoin => $data) {
                if ($data['details']['id_produit'] == $id_produit_don && $data['quantite_restante'] > 0) {
                    $besoins_pour_produit[$id_besoin] = $data['quantite_restante'];
                }
            }

            if (empty($besoins_pour_produit)) continue;

            // Distribuer le don actuel proportionnellement
            $allocations = self::distribuer_proportionnellement($quantite_don, $besoins_pour_produit);

            // Ajouter les actions au plan et mettre à jour les besoins restants
            foreach ($allocations as $id_besoin => $quantite_allouee) {
                if ($quantite_allouee > 0) {
                    $besoin_details = $besoins_restants_map[$id_besoin]['details'];
                    $plan[] = [
                        'type' => 'distribution',
                        'id_besoin' => $id_besoin,
                        'id_don' => $don['id_don'],
                        'quantite' => $quantite_allouee,
                        'ville' => $besoin_details['nom_ville'],
                        'produit' => $besoin_details['nom_produit'],
                        'source' => 'Don #' . $don['id_don']
                    ];
                    $besoins_restants_map[$id_besoin]['quantite_restante'] -= $quantite_allouee;
                }
            }
        }

        // --- 2. Phase d'Achat pour les Besoins Restants ---
        foreach ($besoins as $besoin) {
            $id_besoin = $besoin['id_besoin'];
            $quantite_restante_besoin = $besoins_restants_map[$id_besoin]['quantite_restante'];

            if ($quantite_restante_besoin > 0) {
                $cout_achat = $quantite_restante_besoin * (float)$besoin['prix_unitaire'] * (1 + $frais_pourcentage / 100);
                
                if (($solde_argent - $cout_total_achats) >= $cout_achat) {
                    $plan[] = [ 'type' => 'achat', 'id_besoin' => $id_besoin, 'quantite' => $quantite_restante_besoin, 'cout_unitaire' => (float)$besoin['prix_unitaire'], 'frais' => $frais_pourcentage, 'cout_total' => $cout_achat, 'ville' => $besoin['nom_ville'], 'produit' => $besoin['nom_produit'], 'source' => 'Argent' ];
                    $cout_total_achats += $cout_achat;
                }
            }
        }

        $_SESSION['simulation_plan'] = $plan;
        $_SESSION['simulation_summary'] = [
            'cout_total_achats' => $cout_total_achats,
            'solde_initial' => $solde_argent,
            'solde_final' => $solde_argent - $cout_total_achats
        ];

        Flight::redirect('/simulation');
    }

    public static function validateSimulation()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $plan_json = Flight::request()->data->plan;
        $plan = json_decode($plan_json, true);

        if (empty($plan)) {
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Aucun plan de simulation à valider.'];
            Flight::redirect('/simulation');
            return;
        }

        try {
            Flight::db()->beginTransaction();

            foreach ($plan as $action) {
                if ($action['type'] === 'distribution') {
                    // On suppose que DistributionModel::insert existe
                    DistributionModel::insert($action['id_besoin'], $action['id_don'], date('Y-m-d H:i:s'), $action['quantite']);
                } elseif ($action['type'] === 'achat') {
                    AchatModel::insert($action['id_besoin'], $action['quantite'], $action['cout_unitaire'], $action['frais'], $action['cout_total']);
                }
            }

            Flight::db()->commit();
            $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'La simulation a été validée et les distributions ont été enregistrées avec succès.'];
        } catch (\Exception $e) {
            Flight::db()->rollBack();
            error_log($e->getMessage()); // Log l'erreur pour le débogage
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Une erreur est survenue lors de la validation. Aucune modification n\'a été enregistrée.'];
        }

        Flight::redirect('/simulation');
    }

    public static function recapPage()
    {
        Flight::render('recap');
    }

    public static function recapData()
    {
        $sql = "SELECT 
                    SUM(quantite_demandee * prix_unitaire) as total, 
                    SUM((quantite_distribuee + quantite_achetee) * prix_unitaire) as satisfait, 
                    SUM(quantite_restante * prix_unitaire) as restant 
                FROM v_besoins_restants";
        $stats = Flight::db()->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        Flight::json([
            'total' => (float)($stats['total'] ?? 0),
            'satisfait' => (float)($stats['satisfait'] ?? 0),
            'restant' => (float)($stats['restant'] ?? 0)
        ]);
    }

    public static function resetData()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $type_reset = Flight::request()->data->type_reset ?? 'light';
        
        try {
            Flight::db()->query("SET FOREIGN_KEY_CHECKS = 0");
            
            if ($type_reset === 'full') {
                // Réinitialisation complète : vider toutes les tables
                Flight::db()->query("TRUNCATE TABLE distribution");
                Flight::db()->query("TRUNCATE TABLE achat");
                Flight::db()->query("TRUNCATE TABLE don_argent");
                Flight::db()->query("TRUNCATE TABLE don");
                Flight::db()->query("TRUNCATE TABLE besoin");
                Flight::db()->query("TRUNCATE TABLE ville");
                Flight::db()->query("TRUNCATE TABLE produit");
                Flight::db()->query("TRUNCATE TABLE type_besoin");
                Flight::db()->query("TRUNCATE TABLE region");
                Flight::db()->query("TRUNCATE TABLE configuration");
                
                // Réinsérer les données initiales
                // Régions
                Flight::db()->query("INSERT INTO region (nom_region) VALUES ('Vakinankaratra'), ('Analamanga'), ('Atsinanana')");
                
                // Villes
                Flight::db()->query("INSERT INTO ville (nom_ville, id_region, nombre_sinistres) VALUES 
                    ('Antsirabe', 1, 500),
                    ('Antananarivo', 2, 1200),
                    ('Toamasina', 3, 800)");
                
                // Types de besoin
                Flight::db()->query("INSERT INTO type_besoin (nom_type_besoin) VALUES ('Alimentaire'), ('Vestimentaire'), ('Médical')");
                
                // Produits
                Flight::db()->query("INSERT INTO produit (nom_produit, id_type_besoin, prix_unitaire) VALUES 
                    ('Riz', 1, 2500.00),
                    ('T-shirt', 2, 15000.00),
                    ('Paracétamol', 3, 1000.00)");
                
                // Besoins
                Flight::db()->query("INSERT INTO besoin (id_ville, id_produit, quantite, date_besoin) VALUES 
                    (1, 1, 100, '2026-02-16 10:00:00'),
                    (2, 2, 50, '2026-02-17 11:00:00'),
                    (3, 3, 200, '2026-02-18 12:00:00')");
                
                // Dons en nature
                Flight::db()->query("INSERT INTO don (id_produit, quantite, date_don) VALUES 
                    (1, 50, '2026-02-16 14:00:00'),
                    (2, 25, '2026-02-17 15:00:00'),
                    (3, 100, '2026-02-18 16:00:00'),
                    (1, 100, '2026-02-19 08:00:00')");
                
                // Don en argent
                Flight::db()->query("INSERT INTO don_argent (montant, date_don) VALUES (500000, '2026-02-20 10:00:00')");
                
                // Configuration
                Flight::db()->query("INSERT INTO configuration (cle, valeur) VALUES ('frais_achat_pourcentage', '10') ON DUPLICATE KEY UPDATE valeur = '10'");
                
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Réinitialisation complète effectuée. Toutes les données ont été remises à leur état initial.'];
            } else {
                // Réinitialisation légère : seulement distributions et achats
                Flight::db()->query("TRUNCATE TABLE distribution");
                Flight::db()->query("TRUNCATE TABLE achat");
                $_SESSION['flash_message'] = ['type' => 'success', 'text' => 'Les distributions et achats ont été supprimés. Les IDs recommenceront à 1.'];
            }
            
            Flight::db()->query("SET FOREIGN_KEY_CHECKS = 1");
        } catch (\Exception $e) {
            Flight::db()->query("SET FOREIGN_KEY_CHECKS = 1");
            $_SESSION['flash_message'] = ['type' => 'error', 'text' => 'Erreur lors de la réinitialisation : ' . $e->getMessage()];
        }
        Flight::redirect('/simulation');
    }
}