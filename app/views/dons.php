<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APK GNBRC - Dons disponible</title>
  <!-- <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>style/style.css"> -->
     <link rel="stylesheet" href="/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

    <h2>Liste des Dons</h2>

    <!-- Formulaire d'ajout -->
    <div class="form-card">
      <h3>Ajouter un don</h3>
      <form method="POST" action="/dons" class="form-inline">
        <select name="id_produit" required>
          <option value="">-- Produit --</option>
          <?php foreach ($produits as $p): ?>
            <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?> (<?= $p['prix_unitaire'] ?> Ar)</option>
          <?php endforeach; ?>
        </select>
        <input type="number" name="quantite" placeholder="Quantité" required min="1">
        <input type="datetime-local" name="date_don" required>
        <button type="submit" class="btn btn-add">Ajouter</button>
      </form>
    </div>

    <!-- Tableau des dons -->
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
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
        <?php if (!empty($dons)): ?>
          <?php foreach ($dons as $d): ?>
            <tr>
              <td><?= $d['id_don'] ?></td>
              <td><?= htmlspecialchars($d['nom_produit']) ?></td>
              <td><?= htmlspecialchars($d['nom_type_besoin']) ?></td>
              <td><?= $d['quantite'] ?></td>
              <td><?= number_format($d['prix_unitaire'], 2) ?> Ar</td>
              <td><?= number_format($d['montant_total'], 2) ?> Ar</td>
              <td><?= $d['date_don'] ?></td>
              <td>
                <a href="/dons/delete/<?= $d['id_don'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce don ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="8">Aucun don enregistré.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Section Dons d'Argent -->
    <h2>Dons d'Argent</h2>

    <!-- Formulaire d'ajout don argent -->
    <div class="form-card">
      <h3>Ajouter un don d'argent</h3>
      <form method="POST" action="/dons/argent" class="form-inline">
        <input type="number" name="montant" placeholder="Montant (Ar)" required min="1" step="0.01">
        <input type="datetime-local" name="date_don" required>
        <button type="submit" class="btn btn-add">Ajouter</button>
      </form>
    </div>

    <!-- Tableau des dons d'argent -->
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Montant</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($dons_argent)): ?>
          <?php foreach ($dons_argent as $da): ?>
            <tr>
              <td><?= $da['id_don_argent'] ?></td>
              <td><?= number_format($da['montant'], 2) ?> Ar</td>
              <td><?= $da['date_don'] ?></td>
              <td>
                <a href="/dons/argent/delete/<?= $da['id_don_argent'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer ce don d\'argent ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="4">Aucun don d'argent enregistré.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
