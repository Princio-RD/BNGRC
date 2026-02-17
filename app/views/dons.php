<?php
$pageTitle = 'APK GNBRC - Dons disponible';
include __DIR__ . '/partials/header.php';
?>

<main class="panel-center">
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
        <tr>
          <td colspan="8">Aucun don enregistré.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>