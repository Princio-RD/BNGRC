<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APK GNBRC</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="/style/style.css">
</head>

<body>

  <?php include __DIR__ . '/partials/header.php'; ?>

  <?php include __DIR__ . '/partials/nav.php'; ?>

  <div class="content">
    <main class="panel-center">
      <h2>Tableau de bord BNGRC</h2>

    
      <div class="dashboard-stats">
        <div class="stat-card">
          <h3><?= count($besoins) ?></h3>
          <p>Besoins en cours</p>
        </div>
        <div class="stat-card">
          <h3><?= count($dons) + (isset($dons_argent) ? count($dons_argent) : 0) ?></h3>
          <p>Dons reçus</p>
        </div>
        <div class="stat-card">
          <h3><?= count($villes) ?></h3>
          <p>Villes</p>
        </div>
        <div class="stat-card">
          <h3><?= count($distributions) ?></h3>
          <p>Distributions</p>
        </div>
      </div>

      <!-- Derniers besoins -->
      <h3 style="margin-top:25px;">Derniers besoins</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Ville</th>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($besoins)): ?>
            <?php foreach (array_slice($besoins, 0, 5) as $b): ?>
              <tr>
                <td><?= htmlspecialchars($b['nom_ville']) ?></td>
                <td><?= htmlspecialchars($b['nom_produit']) ?></td>
                <td><?= $b['quantite_restante'] ?></td>
                <td><?= $b['date_besoin'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4">Aucun besoin.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Derniers dons -->
      <h3 style="margin-top:25px;">Derniers dons</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Produit</th>
            <th>Quantité</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dons)): ?>
            <?php foreach (array_slice($dons, 0, 5) as $d): ?>
              <tr>
                <td><?= htmlspecialchars($d['nom_produit']) ?></td>
                <td><?= $d['quantite'] ?></td>
                <td><?= $d['date_don'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="3">Aucun don.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Derniers dons en argent -->
      <h3 style="margin-top:25px;">Derniers dons en argent</h3>
      <table class="table">
        <thead>
          <tr>
            <th>Montant (Ar)</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($dons_argent)): ?>
            <?php foreach (array_slice($dons_argent, 0, 5) as $da): ?>
              <tr>
                <td><?= number_format($da['montant'], 2, ',', ' ') ?></td>
                <td><?= $da['date_don'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="2">Aucun don en argent.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <!-- Dons attribués à chaque ville -->
      <h3 style="margin-top:25px;">Distribution des dons par ville</h3>
      <table class="table">
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
                <td><?= htmlspecialchars($row['nom_ville']) ?></td>
                <td><?= htmlspecialchars($row['nom_produit']) ?></td>
                <td><?= htmlspecialchars($row['total_distribue']) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="3">Aucune distribution enregistrée.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>