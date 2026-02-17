<?php
$pageTitle = 'APK GNBRC';
include __DIR__ . '/partials/header.php';
?>

<main class="panel-center">
  <h2>Tableau de bord</h2>

  <!-- Résumé -->
  <div class="dashboard-stats">
    <div class="stat-card">
      <h3><?= count($besoins) ?></h3>
      <p>Besoins enregistrés</p>
    </div>
    <div class="stat-card">
      <h3><?= count($dons) ?></h3>
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
            <td><?= $b['quantite'] ?></td>
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
</main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>