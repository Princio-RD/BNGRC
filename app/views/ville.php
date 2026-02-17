<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>APK GNBRC - Ville</title>
  <!-- <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>style/style.css"> -->
     <link rel="stylesheet" href="/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

<?php include __DIR__ . '/partials/nav.php'; ?>

<div class="content">
  <main class="panel-center">
    <h2>Liste des Villes</h2>

    <!-- Formulaire d'ajout -->
    <div class="form-card">
      <h3>Ajouter une ville</h3>
      <form method="POST" action="/ville" class="form-inline">
        <input type="text" name="nom_ville" placeholder="Nom de la ville" required>
        <input type="number" name="nombre_sinistres" placeholder="Nombre de sinistrés" min="0" required>
        <select name="id_region" required>
          <option value="">-- Région --</option>
          <?php foreach ($regions as $r): ?>
            <option value="<?= $r['id_region'] ?>"><?= htmlspecialchars($r['nom_region']) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-add">Ajouter</button>
      </form>
    </div>

    <!-- Tableau des villes -->
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom de la ville</th>
          <th>Région</th>
          <th>Nombre de sinistrés</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($villes)): ?>
          <?php foreach ($villes as $v): ?>
            <tr>
              <td><?= $v['id_ville'] ?></td>
              <td><?= htmlspecialchars($v['nom_ville']) ?></td>
              <td><?= htmlspecialchars($v['nom_region']) ?></td>
              <td><?= $v['nombre_sinistres'] ?? 0 ?></td>
              <td>
                <a href="/ville/delete/<?= $v['id_ville'] ?>" class="btn btn-delete" onclick="return confirm('Supprimer cette ville ?')">Supprimer</a>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="5">Aucune ville enregistrée.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

</body>
</html>
