<?php
$pageTitle = 'APK GNBRC - Achats besoins';
include __DIR__ . '/partials/header.php';
?>

<main class="panel-center">
    <h2>Liste des Achats</h2>

    <div class="form-card">
        <h3>Filtrer par ville</h3>
        <div class="form-inline">
            <select id="ville-filter">
                <option value="">-- Toutes les villes --</option>
                <?php foreach ($villes as $v): ?>
                    <option value="<?= $v['id_ville'] ?>" <?= ($ville_id === (int) $v['id_ville']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($v['nom_ville']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <a href="/besoin/simulation" class="btn btn-edit">Nouvelle simulation</a>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Ville</th>
                <th>Besoin</th>
                <th>Type</th>
                <th>Montant besoin</th>
                <th>Montant achat</th>
                <th>Frais</th>
                <th>Total</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($achats)): ?>
                <?php foreach ($achats as $a): ?>
                    <tr>
                        <td><?= $a['id_achat'] ?></td>
                        <td><?= htmlspecialchars($a['nom_ville']) ?></td>
                        <td><?= htmlspecialchars($a['nom_produit']) ?></td>
                        <td><?= htmlspecialchars($a['nom_type_besoin']) ?></td>
                        <td><?= number_format($a['montant_besoin'], 2) ?> Ar</td>
                        <td><?= number_format($a['montant_achat'], 2) ?> Ar</td>
                        <td><?= number_format($a['montant_frais'], 2) ?> Ar (<?= number_format($a['frais_pourcent'], 2) ?>%)</td>
                        <td><?= number_format($a['montant_total'], 2) ?> Ar</td>
                        <td><?= $a['date_achat'] ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="9">Aucun achat enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>


<!-- JS externalisé -->
<script src="/assets/js/besoin_achats_ville_filter.js"></script>

</body>

</html>