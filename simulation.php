<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulation de Distribution</title>
    <link rel="stylesheet" href="<?php echo Flight::get('flight.base_url'); ?>/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

        <h2>Simulation de Distribution des Dons</h2>

        <?php if (isset($flash_message) && $flash_message): ?>
            <div class="flash-message <?= $flash_message['type'] ?>">
                <?= htmlspecialchars($flash_message['text']) ?>
            </div>
        <?php endif; ?>

        <div class="simulation-container">
            <div class="simulation-actions">
                <h3>Actions</h3>
                <p>Lancer l'algorithme pour calculer la meilleure répartition des stocks et des fonds disponibles pour combler les besoins.</p>
                <form action="<?php echo Flight::get('flight.base_url'); ?>/simulation/run" method="post">
                    <div style="margin-bottom: 15px; text-align: left;">
                        <p style="margin-bottom: 8px; font-weight: 600;">Critère de priorité :</p>
                        <label style="display: block; margin-bottom: 5px; cursor: pointer;">
                            <input type="radio" name="type_simulation" value="date" checked> Par Date (Premier arrivé, premier servi)
                        </label>
                        <label style="display: block; cursor: pointer;">
                            <input type="radio" name="type_simulation" value="quantite"> Par Quantité (Plus petite demande en premier)
                        </label>
                        <label style="display: block; cursor: pointer;">
                            <input type="radio" name="type_simulation" value="urgence"> Par repartiton (Villes les plus sinistrées)
                        </label>
                    </div>
                    <button type="submit" class="btn btn-primary">Lancer la Simulation</button>
                </form>

          <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
          
          <h4 style="margin-bottom: 10px;">Réinitialisation</h4>
          
          
          <form action="<?php echo Flight::get('flight.base_url'); ?>/simulation/reset" method="post" style="margin-bottom: 10px;">
            <input type="hidden" name="type_reset" value="light">
            <button type="submit" class="btn" style="background-color: #ffc107; color: #333; width: 100%;" onclick="return confirm('Cela va supprimer les distributions et achats uniquement. Continuer ?');">
              Réinitialiser Distributions/Achats
            </button>
          </form>
          

          <form action="<?php echo Flight::get('flight.base_url'); ?>/simulation/reset" method="post">
            <input type="hidden" name="type_reset" value="full">
            <button type="submit" class="btn" style="background-color: #dc3545; width: 100%;" onclick="return confirm('ATTENTION : Cela va supprimer TOUTES les données et les remettre à leur état initial (données de démo). Continuer ?');">
              Réinitialisation Complète (données initiales)
            </button>
          </form>
            </div>

            <?php if (isset($simulation_plan) && $simulation_plan !== null): ?>
                <div class="simulation-results">
                    <h3>Résultat de la Simulation</h3>
                    
                    <?php if (empty($simulation_plan)): ?>
                        <div class="flash-message">
                            Aucune action nécessaire ou possible avec les ressources actuelles.
                        </div>
                    <?php else: ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Ville</th>
                                    <th>Produit</th>
                                    <th>Type</th>
                                    <th>Source</th>
                                    <th>Quantité</th>
                                    <th>Coût (Ar)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($simulation_plan as $action): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($action['ville']) ?></td>
                                        <td><?= htmlspecialchars($action['produit']) ?></td>
                                        <td>
                                            <?php if($action['type'] == 'distribution'): ?>
                                                <span class="badge badge-dist">Distribution</span>
                                            <?php else: ?>
                                                <span class="badge badge-achat">Achat</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($action['source']) ?></td>
                                        <td><?= htmlspecialchars($action['quantite']) ?></td>
                                        <td>
                                            <?= isset($action['cout_total']) ? number_format($action['cout_total'], 2, ',', ' ') : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>

                        <?php if (isset($simulation_summary)): ?>
                            <div class="summary-card">
                                <h4>Résumé Financier</h4>
                                <p>Solde initial : <strong><?= number_format($simulation_summary['solde_initial'], 2, ',', ' ') ?> Ar</strong></p>
                                <p>Coût total prévisionnel : <strong><?= number_format($simulation_summary['cout_total_achats'], 2, ',', ' ') ?> Ar</strong></p>
                                <p>Solde final estimé : <strong><?= number_format($simulation_summary['solde_final'], 2, ',', ' ') ?> Ar</strong></p>
                            </div>
                        <?php endif; ?>

                        <div style="margin-top: 20px; text-align: right;">
                            <form action="<?php echo Flight::get('flight.base_url'); ?>/simulation/validate" method="post">
                                <input type="hidden" name="plan" value="<?= htmlspecialchars(json_encode($simulation_plan), ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="btn btn-success" onclick="return confirm('Confirmer la distribution ? Cette action mettra à jour les stocks et la base de données.');">
                                    Valider et Dispatcher
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

<?php include __DIR__ . '/partials/footer.php'; ?>
