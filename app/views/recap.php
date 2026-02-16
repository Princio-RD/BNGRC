<?php
$pageTitle = 'APK GNBRC - Récapitulatif';
include __DIR__ . '/partials/header.php';
?>

<main class="panel-center">
    <h2>Récapitulatif</h2>

    <div class="dashboard-stats">
        <div class="stat-card">
            <h3 id="total-besoins"><?= number_format($summary['total_besoins'] ?? 0, 2) ?></h3>
            <p>Total besoins (Ar)</p>
        </div>
        <div class="stat-card">
            <h3 id="total-achete"><?= number_format($summary['total_achete'] ?? 0, 2) ?></h3>
            <p>Besoins satisfaits (Ar)</p>
        </div>
        <div class="stat-card">
            <h3 id="total-restant"><?= number_format($summary['total_restant'] ?? 0, 2) ?></h3>
            <p>Besoins restants (Ar)</p>
        </div>
        <div class="stat-card">
            <h3 id="total-dons"><?= number_format($total_dons ?? 0, 2) ?></h3>
            <p>Dons totaux (Ar)</p>
        </div>
        <div class="stat-card">
            <h3 id="dons-utilises"><?= number_format($dons_utilises ?? 0, 2) ?></h3>
            <p>Dons utilisés (Ar)</p>
        </div>
        <div class="stat-card">
            <h3 id="dons-restants"><?= number_format($dons_restants ?? 0, 2) ?></h3>
            <p>Dons restants (Ar)</p>
        </div>
    </div>

    <div class="form-card" style="margin-top:20px;">
        <button id="btn-refresh" class="btn btn-edit">Actualiser</button>
        <span id="refresh-status" class="hint"></span>
    </div>

    <table class="table" id="recap-table">
        <thead>
            <tr>
                <th>Ville</th>
                <th>Besoin</th>
                <th>Type</th>
                <th>Montant total</th>
                <th>Montant acheté</th>
                <th>Montant restant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($details)): ?>
                <?php foreach ($details as $d): ?>
                    <?php $status = ((float) $d['montant_restant'] <= 0) ? 'Satisfait' : 'Restant'; ?>
                    <tr>
                        <td><?= htmlspecialchars($d['nom_ville']) ?></td>
                        <td><?= htmlspecialchars($d['nom_produit']) ?></td>
                        <td><?= htmlspecialchars($d['nom_type_besoin']) ?></td>
                        <td><?= number_format($d['montant_total'], 2) ?> Ar</td>
                        <td><?= number_format($d['montant_achete'], 2) ?> Ar</td>
                        <td><?= number_format($d['montant_restant'], 2) ?> Ar</td>
                        <td><?= $status ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">Aucun besoin enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script>
    const refreshBtn = document.getElementById('btn-refresh');
    const statusEl = document.getElementById('refresh-status');

    const fmt = (value) => {
        const num = Number(value) || 0;
        return num.toFixed(2);
    };

    const escapeHtml = (value) => {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    };

    const updateTable = (details) => {
        const tbody = document.querySelector('#recap-table tbody');
        if (!tbody) return;

        if (!details || details.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7">Aucun besoin enregistré.</td></tr>';
            return;
        }

        tbody.innerHTML = details.map((d) => {
            const restant = Number(d.montant_restant || 0);
            const status = restant <= 0 ? 'Satisfait' : 'Restant';
            return `
        <tr>
                        <td>${escapeHtml(d.nom_ville)}</td>
                        <td>${escapeHtml(d.nom_produit)}</td>
                        <td>${escapeHtml(d.nom_type_besoin)}</td>
          <td>${fmt(d.montant_total)} Ar</td>
          <td>${fmt(d.montant_achete)} Ar</td>
          <td>${fmt(d.montant_restant)} Ar</td>
          <td>${status}</td>
        </tr>
      `;
        }).join('');
    };

    const updateStats = (data) => {
        document.getElementById('total-besoins').textContent = fmt(data.summary.total_besoins);
        document.getElementById('total-achete').textContent = fmt(data.summary.total_achete);
        document.getElementById('total-restant').textContent = fmt(data.summary.total_restant);
        document.getElementById('total-dons').textContent = fmt(data.total_dons);
        document.getElementById('dons-utilises').textContent = fmt(data.dons_utilises);
        document.getElementById('dons-restants').textContent = fmt(data.dons_restants);
    };

    if (refreshBtn) {
        refreshBtn.addEventListener('click', async () => {
            statusEl.textContent = 'Actualisation...';
            try {
                const response = await fetch('/recap/actualiser', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                });
                const data = await response.json();
                updateStats(data);
                updateTable(data.details);
                statusEl.textContent = 'Mis à jour.';
            } catch (e) {
                statusEl.textContent = 'Erreur de mise à jour.';
            }
        });
    }
</script>

</body>

</html>