<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Simulation de Distribution</title>
  <link rel="stylesheet" href="/style/style.css">
  <style>
    .flash-message { padding: 15px; margin-bottom: 20px; border-radius: 4px; }
    .flash-message.success { background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; }
    .flash-message.error { background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24; }
    .simulation-container { display: flex; gap: 20px; align-items: flex-start; }
    .simulation-actions { border: 1px solid #ccc; padding: 20px; border-radius: 5px; background: #f9f9f9; }
    .simulation-results { flex-grow: 1; }
    .summary-card { background-color: #eef; border: 1px solid #cce; padding: 15px; margin-top: 20px; border-radius: 5px; }
  </style>
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
        <li class="menu-item"><a href="/achats">Achats</a></li>
        <li class="menu-item active"><a href="/simulation">Simulation</a></li>
      </ul>
    </aside>
    <main class="panel-center">
      <h2>Simulation de Distribution des Dons</h2>

      <?php if (isset($flash_message) && $flash_message): ?>
        <div class="flash-message <?= $flash_message['type'] ?>">
          <?= htmlspecialchars($flash_message['text']) ?>
        </div>
      <?php endif; ?>

      <div class="simulation-container">
        <div class="simulation-actions">
          <p>Cliquez pour simuler la meilleure distribution des dons pour couvrir les besoins actuels.</p>
          <form action="/simulation/run" method="post">
            <button type="submit" class="btn btn-primary">Lancer la Simulation</button>
          </form>
        </div>

        <?php if (isset($simulation_plan) && $simulation_plan !== null): ?>
          <div class="simulation-results">
            <h3>Résultat de la Simulation</h3>
            <?php if (empty($simulation_plan)): ?>
              <p>Aucune action de distribution ou d'achat n'est possible ou nécessaire avec les ressources actuelles.</p>
            <?php else: ?>
              <table class="table">
                <thead>
                  <tr>
                    <th>Ville</th>
                    <th>Produit</th>
                    <th>Action</th>
                    <th>Source</th>
                    <th>Quantité</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($simulation_plan as $action): ?>
                    <tr>
                      <td><?= htmlspecialchars($action['ville']) ?></td>
                      <td><?= htmlspecialchars($action['produit']) ?></td>
                      <td><?= htmlspecialchars(ucfirst($action['type'])) ?></td>
                      <td><?= htmlspecialchars($action['source']) ?></td>
                      <td><?= htmlspecialchars($action['quantite']) ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>

              <?php if (isset($simulation_summary)): ?>
                <div class="summary-card">
                  <h4>Résumé Financier</h4>
                  <p>Solde monétaire initial: <strong><?= number_format($simulation_summary['solde_initial'], 2, ',', ' ') ?> Ar</strong></p>
                  <p>Coût total des achats: <strong><?= number_format($simulation_summary['cout_total_achats'], 2, ',', ' ') ?> Ar</strong></p>
                  <p>Solde monétaire final: <strong><?= number_format($simulation_summary['solde_final'], 2, ',', ' ') ?> Ar</strong></p>
                </div>
              <?php endif; ?>

              <form action="/simulation/validate" method="post" style="margin-top: 20px;">
                <input type="hidden" name="plan" value="<?= htmlspecialchars(json_encode($simulation_plan)) ?>">
                <button type="submit" class="btn btn-success" onclick="return confirm('Êtes-vous sûr de vouloir valider cette simulation ? Les distributions et achats seront enregistrés de manière permanente.');">
                  Valider la Simulation
                </button>
              </form>
            <?php endif; ?>
          </div>
        <?php endif; ?>
      </div>

    </main>
  </div>

  <?php include __DIR__ . '/partials/footer.php'; ?>

</body>

</html>
