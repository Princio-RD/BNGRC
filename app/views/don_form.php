<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajouter un Don</title>
  <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

    <div class="page-header">
        <h2>Nouveau Don</h2>
        <a href="<?php echo Flight::get('flight.base_url'); ?>/dons" class="btn btn-cancel">Retour</a>
    </div>

    <div class="form-card" style="max-width: 600px;">
      <form method="POST" action="<?php echo Flight::get('flight.base_url'); ?>/dons">
        <div class="form-group">
            <label>Produit</label>
            <select name="id_produit" required>
            <option value="">-- Sélectionner un produit --</option>
            <?php foreach ($produits as $p): ?>
                <option value="<?= $p['id_produit'] ?>"><?= htmlspecialchars($p['nom_produit']) ?> (<?= $p['prix_unitaire'] ?> Ar)</option>
            <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Quantité</label>
            <input type="number" name="quantite" placeholder="Ex: 50" required min="1">
        </div>

        <div class="form-group">
            <label>Date du don</label>
            <input type="datetime-local" name="date_don" required>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Enregistrer le don</button>
        </div>
      </form>
    </div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
