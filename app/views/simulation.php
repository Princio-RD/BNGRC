<?php
$pageTitle = 'APK GNBRC - Simulation achats';
include __DIR__ . '/partials/header.php';
?>

<main class="panel-center">
    <h2>Simulation d'achat</h2>

    <div id="simulation-errors" class="alert alert-error" <?= empty($errors) ? 'style="display:none;"' : '' ?>>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div id="simulation-success" class="alert alert-success" <?= empty($success) ? 'style="display:none;"' : '' ?>>
        <?= htmlspecialchars($success ?? '') ?>
    </div>

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
            <form method="POST" action="/besoin/simulation" class="form-inline" id="form-simulation">
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
            <span id="simulation-status" class="hint"></span>
            <p class="hint">Frais appliqués: <?= number_format($frais_pourcent, 2) ?>%</p>
        <?php else: ?>
            <p>Aucun besoin restant à acheter.</p>
        <?php endif; ?>
    </div>

    <div id="simulation-result" class="form-card" <?= (!empty($result) && empty($errors)) ? '' : 'style="display:none;"' ?>>
        <h3>Résultat de la simulation</h3>
        <p><strong>Besoin:</strong> <span id="result-besoin"><?= htmlspecialchars(($result['besoin']['nom_produit'] ?? '') . ' (' . ($result['besoin']['nom_ville'] ?? '') . ')') ?></span></p>
        <p><strong>Montant achat:</strong> <span id="result-montant-achat"><?= !empty($result) ? number_format($result['montant_achat'], 2) . ' Ar' : '' ?></span></p>
        <p><strong>Frais (<span id="result-frais-pourcent"><?= !empty($result) ? number_format($result['frais_pourcent'], 2) . '%' : '' ?></span>):</strong> <span id="result-montant-frais"><?= !empty($result) ? number_format($result['montant_frais'], 2) . ' Ar' : '' ?></span></p>
        <p><strong>Total à payer:</strong> <span id="result-montant-total"><?= !empty($result) ? number_format($result['montant_total'], 2) . ' Ar' : '' ?></span></p>
        <p><strong>Besoin restant:</strong> <span id="result-montant-restant"><?= !empty($result) ? number_format($result['montant_restant_besoin'], 2) . ' Ar' : '' ?></span></p>
        <p><strong>Dons restants:</strong> <span id="result-dons-restants"><?= !empty($result) ? number_format($result['dons_restants'], 2) . ' Ar' : '' ?></span></p>

        <form method="POST" action="/besoin/simulation/valider" class="form-inline" id="form-validate">
            <input type="hidden" name="id_besoin" id="result-id-besoin" value="<?= !empty($result) ? (int) $result['id_besoin'] : '' ?>">
            <input type="hidden" name="montant_achat" id="result-montant-achat-input" value="<?= !empty($result) ? htmlspecialchars($result['montant_achat']) : '' ?>">
            <button type="submit" class="btn btn-add">Valider l'achat</button>
        </form>
        <span id="validation-status" class="hint"></span>
    </div>
</main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>