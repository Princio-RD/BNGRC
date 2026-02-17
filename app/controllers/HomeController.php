<?php

namespace app\controllers;

use Flight;
use app\models\VilleModel;
use app\models\BesoinModel;
use app\models\DonModel;
use app\models\RegionModel;
use app\models\ProduitModel;
use app\models\DistributionModel;

class HomeController
{
    // ==================== TABLEAU DE BORD ====================
    public static function index()
    {
        $besoins = BesoinModel::getAll();
        $dons = DonModel::getAll();
        $villes = VilleModel::getAll();
        $distributions = DistributionModel::getAll();

        Flight::render('home', [
            'besoins' => $besoins,
            'dons' => $dons,
            'villes' => $villes,
            'distributions' => $distributions,
        ]);
    }

    // ==================== BESOIN ====================
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

    // ==================== DONS ====================
    public static function donList()
    {
        $dons = DonModel::getAll();
        $produits = ProduitModel::getAll();

        Flight::render('dons', [
            'dons' => $dons,
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

    // ==================== VILLE ====================
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
}
