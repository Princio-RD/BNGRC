<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestion des Achats</title>
  <link rel="stylesheet" href="/style/style.css">
</head>

<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <div class="content">
    <aside class="panel-gauche">
      <ul class="menu">
      <li class="menu-item"><a href="/"><i class="fa-solid fa-chart-line"></i> Tableau de bord</a></li>
        <li class="menu-item"><a href="/besoin"><i class="fa-solid fa-hand-holding-heart"></i> Besoin</a></li>
        <li class="menu-item"><a href="/dons"><i class="fa-solid fa-box-open"></i> Dons disponible</a></li>
        <li class="menu-item"><a href="/ville"><i class="fa-solid fa-city"></i> Ville</a></li>
        <li class="menu-item"><a href="/achats"><i class="fa-solid fa-cart-shopping"></i> Achats</a></li>
        <li class="menu-item"><a href="/simulation"><i class="fa-solid fa-calculator"></i> Simulation</a></li>
        <li class="menu-item"><a href="/recap"><i class="fa-solid fa-file-invoice-dollar"></i> Récapitulation</a></li>

      </ul>
    </aside>
    <main class="panel-center">
      <h2>Gestion des Achats</h2>

      <div class="stat-card-row">
        <div class="stat-card solde">
          <h3><?= number_format($solde_argent, 2, ',', ' ') ?> Ar</h3>
          <p>Solde monétaire disponible</p>
        </div>
        <div class="stat-card frais">
          <h3><?= $frais ?> %</h3>
          <p>Frais sur Achat</p>
        </div>
      </div>

      <?php if ($flash_error): ?>
        <div class="error-flash"><?= htmlspecialchars($flash_error) ?></div>
      <?php endif; ?>

      <form action="/achats" method="get" class="filter-form">
        <label for="id_ville">Filtrer par ville :</label>
        <select name="id_ville" id="id_ville">
          <option value="">Toutes les villes</option>
          <?php foreach ($villes as $ville): ?>
            <option value="<?= $ville['id_ville'] ?>" <?= ($id_ville_filtre == $ville['id_ville']) ? 'selected' : '' ?>>
              <?= htmlspecialchars($ville['nom_ville']) ?>
            </option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn">Filtrer</button>
      </form>

      <!-- Besoins restants à acheter -->
      <h3 style="margin-top:25px;">Besoins à Acheter</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Ville</th>
            <th>Produit</th>
            <th>Qté. Restante</th>
            <th>P.U.</th>
            <th>Acheter</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($besoins_restants)): ?>
            <?php foreach ($besoins_restants as $b): ?>
              <tr>
                <td><?= htmlspecialchars($b['nom_ville']) ?></td>
                <td><?= htmlspecialchars($b['nom_produit']) ?></td>
                <td><?= $b['quantite_restante'] ?></td>
                <td><?= number_format($b['prix_unitaire'], 2, ',', ' ') ?> Ar</td>
                <td>
                  <form action="/achats/process" method="post" style="display:flex; gap:5px;">
                    <input type="hidden" name="id_besoin" value="<?= $b['id_besoin'] ?>">
                    <input type="number" name="quantite_a_acheter" min="1" max="<?= $b['quantite_restante'] ?>" placeholder="Qté" required style="width: 70px;">
                    <button type="submit" class="btn btn-primary">Acheter</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">Aucun besoin à acheter pour le filtre sélectionné.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Historique des achats -->
      <h3 style="margin-top:25px;">Historique des Achats</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Ville</th>
            <th>Produit</th>
            <th>Qté Achetée</th>
            <th>Coût Total (avec frais)</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($achats_historique)): ?>
            <?php foreach ($achats_historique as $achat): ?>
              <tr>
                <td><?= htmlspecialchars($achat['date_achat']) ?></td>
                <td><?= htmlspecialchars($achat['nom_ville']) ?></td>
                <td><?= htmlspecialchars($achat['nom_produit']) ?></td>
                <td><?= htmlspecialchars($achat['quantite_achetee']) ?></td>
                <td><?= number_format($achat['cout_total_achat'], 2, ',', ' ') ?> Ar</td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5">Aucun achat enregistré pour le filtre sélectionné.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

    </main>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>