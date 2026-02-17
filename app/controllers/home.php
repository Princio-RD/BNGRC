<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BNGRC - Accueil</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        h1, h2 { color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background-color: #f4f4f4; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #007BFF; font-weight: bold; }
        .nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <div class="nav">
        <a href="<?php echo Flight::request()->base; ?>/">Accueil</a>
        <a href="<?php echo Flight::request()->base; ?>/besoin">Besoins</a>
        <a href="<?php echo Flight::request()->base; ?>/dons">Dons</a>
        <a href="<?php echo Flight::request()->base; ?>/ville">Villes</a>
    </div>

    <h1>Tableau de bord BNGRC</h1>

    <!-- Section Derniers dons -->
    <h2>Derniers dons</h2>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dons)): ?>
                <?php 
                // Tri des dons par date décroissante pour afficher les "derniers" en premier
                usort($dons, function($a, $b) {
                    return strtotime($b['date_don']) - strtotime($a['date_don']);
                });
                ?>
                <?php foreach ($dons as $don): ?>
                <tr>
                    <td>
                        <?php 
                        $nom_produit = 'Inconnu';
                        foreach ($produits as $p) {
                            if ($p['id_produit'] == $don['id_produit']) {
                                $nom_produit = $p['nom_produit'];
                                break;
                            }
                        }
                        echo htmlspecialchars($nom_produit);
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($don['quantite']); ?></td>
                    <td><?php echo htmlspecialchars($don['date_don']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Aucun don enregistré.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Section Dons attribués à chaque ville -->
    <h2>Dons attribués à chaque ville</h2>
    <table>
        <thead>
            <tr>
                <th>Ville</th>
                <th>Produit</th>
                <th>Total Distribué</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stats)): ?>
                <?php foreach ($stats as $row): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['nom_ville']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_produit']); ?></td>
                    <td><?php echo htmlspecialchars($row['total_distribue']); ?></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3">Aucune distribution enregistrée.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>