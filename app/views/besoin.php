<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APK GNBRC - Besoin</title>
  <!-- <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>style/style.css"> -->
    <link rel="stylesheet" href="/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<div class="content">
  <aside class="panel-gauche">
    <ul class="menu">
      <!-- <li class="menu-item"><a href="<?php echo Flight::get('flight.base_url'); ?>/">Tableau de bord</a></li>
      <li class="menu-item"><a href="<?php echo Flight::get('flight.base_url'); ?>/besoin">Besoin</a></li>
      <li class="menu-item"><a href="<?php echo Flight::get('flight.base_url'); ?>/dons">Dons disponible</a></li>
      <li class="menu-item"><a href="<?php echo Flight::get('flight.base_url'); ?>/ville">Ville</a></li> -->

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
    <h2>Liste des Besoins</h2>

  
    <div class="form-card">
      <h3>Ajouter un besoin</h3>
      <form method="POST" action="/besoin" class="form-inline">
        <select name="id_ville" required>
          <option value="">-- Ville --</option>
          <?php foreach ($villes as $v): ?>
            <option value="<?= $v['id_ville'] ?>"><?= htmlspecialchars($v['nom_ville']) ?></option>
          <?php endforeach; ?>
        </select>
        <select name="id_produit" required>
          <option value="">-- Produit --</option>
          <?php foreach ($produits as $p): ?>
            <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?> (<?= $p['prix_unitaire'] ?> Ar)</option>
          <?php endforeach; ?>
        </select>
        <input type="number" name="quantite" placeholder="Quantité" required min="1">
        <input type="datetime-local" name="date_besoin" required>
        <button type="submit" class="btn btn-add">Ajouter</button>
      </form>
    </div>


    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Ville</th>
          <th>Produit</th>
          <th>Type</th>
          <th>Qté Demandée</th>
          <th>Qté Satisfaite</th>
          <th>Qté Restante</th>
          <th>Prix unitaire</th>
          <th>Montant total</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($besoins)): ?>
          <?php foreach ($besoins as $b): ?>
            <tr>
              <td><?= $b['id_besoin'] ?></td>
              <td><?= htmlspecialchars($b['nom_ville']) ?></td>
              <td><?= htmlspecialchars($b['nom_produit']) ?></td>
              <td><?= htmlspecialchars($b['nom_type_besoin'] ?? '') ?></td>
              <td><?= $b['quantite_demandee'] ?></td>
              <td><?= $b['quantite_distribuee'] + $b['quantite_achetee'] ?></td>
              <td style="font-weight: bold;"><?= $b['quantite_restante'] ?></td>
              <td><?= number_format($b['prix_unitaire'], 2) ?> Ar</td>
              <td><?= number_format($b['montant_total'], 2) ?> Ar</td>
              <td><?= $b['date_besoin'] ?></td>
              <td>
                <a href="/besoin/delete/<?= $b['id_besoin'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce besoin ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="10">Aucun besoin en cours.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
