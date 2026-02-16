<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APK GNBRC - Besoin</title>
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
      <h2>Liste des Besoins</h2>

      <!-- Formulaire d'ajout -->
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

      <!-- Tableau des besoins -->
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>Ville</th>
            <th>Produit</th>
            <th>Type</th>
            <th>Quantité</th>
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
                <td><?= htmlspecialchars($b['nom_type_besoin']) ?></td>
                <td><?= $b['quantite'] ?></td>
                <td><?= number_format($b['prix_unitaire'], 2) ?> Ar</td>
                <td><?= number_format($b['montant_total'], 2) ?> Ar</td>
                <td><?= $b['date_besoin'] ?></td>
                <td>
                  <a href="/besoin/delete/<?= $b['id_besoin'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce besoin ?')">Supprimer</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="9">Aucun besoin enregistré.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </main>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>