<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APK GNBRC - Simulation achats</title>
    <link rel="stylesheet" href="/style/style.css">
</head>

<body>

    <?php include __DIR__ . '/partials/header.php'; ?>

    <div class="content">
        <aside class="panel-gauche">
            <ul class="menu">
                <li class="menu-item"><a href="/">Tableau de bord</a></li>
                <li class="menu-item"><a href="/besoin">Besoin</a></li>
                <li class="menu-item"><a href="/dons">Dons disponible</a></li>
                <li class="menu-item"><a href="/ville">Ville</a></li>
                <li class="menu-item"><a href="/besoin/achats">Achats besoins</a></li>
                <li class="menu-item"><a href="/besoin/simulation">Simulation achats</a></li>
                <li class="menu-item"><a href="/recap">Récapitulatif</a></li>
            </ul>
        </aside>
        <main class="panel-center">
            <h2>Simulation d'achat</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-error">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <div class="form-card">
                <h3>Filtrer les besoins par ville</h3>
                <form method="GET" action="/besoin/simulation" class="form-inline">
                    <select name="ville_id">
                        <option value="">-- Toutes les villes --</option>
                        <?php foreach ($villes as $v): ?>
                            <option value="<?= $v['id_ville'] ?>" <?= ($ville_id === (int) $v['id_ville']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($v['nom_ville']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-edit">Filtrer</button>
                </form>
            </div>

            <div class="form-card">
                <h3>Simulation</h3>
                <?php if (!empty($besoins)): ?>
                    <form method="POST" action="/besoin/simulation" class="form-inline">
                        <select name="id_besoin" required>
                            <option value="">-- Besoin --</option>
                            <?php foreach ($besoins as $b): ?>
                                <option value="<?= $b['id_besoin'] ?>">
                                    <?= htmlspecialchars($b['nom_ville']) ?> - <?= htmlspecialchars($b['nom_produit']) ?> (Restant: <?= number_format($b['montant_restant'], 2) ?> Ar)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <input type="number" name="montant_achat" step="0.01" min="1" placeholder="Montant à acheter" required>
                        <button type="submit" class="btn btn-add">Simuler</button>
                    </form>
                    <p class="hint">Frais appliqués: <?= number_format($frais_pourcent, 2) ?>%</p>
                <?php else: ?>
                    <p>Aucun besoin restant à acheter.</p>
                <?php endif; ?>
            </div>

            <?php if (!empty($result) && empty($errors)): ?>
                <div class="form-card">
                    <h3>Résultat de la simulation</h3>
                    <p><strong>Besoin:</strong> <?= htmlspecialchars($result['besoin']['nom_produit'] ?? '') ?> (<?= htmlspecialchars($result['besoin']['nom_ville'] ?? '') ?>)</p>
                    <p><strong>Montant achat:</strong> <?= number_format($result['montant_achat'], 2) ?> Ar</p>
                    <p><strong>Frais (<?= number_format($result['frais_pourcent'], 2) ?>%):</strong> <?= number_format($result['montant_frais'], 2) ?> Ar</p>
                    <p><strong>Total à payer:</strong> <?= number_format($result['montant_total'], 2) ?> Ar</p>
                    <p><strong>Besoin restant:</strong> <?= number_format($result['montant_restant_besoin'], 2) ?> Ar</p>
                    <p><strong>Dons restants:</strong> <?= number_format($result['dons_restants'], 2) ?> Ar</p>

                    <form method="POST" action="/besoin/simulation/valider" class="form-inline">
                        <input type="hidden" name="id_besoin" value="<?= (int) $result['id_besoin'] ?>">
                        <input type="hidden" name="montant_achat" value="<?= htmlspecialchars($result['montant_achat']) ?>">
                        <button type="submit" class="btn btn-add">Valider l'achat</button>
                    </form>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>