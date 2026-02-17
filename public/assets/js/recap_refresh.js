// JS pour l'AJAX de rafraîchissement sur la page récapitulation
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
        .replace(/'/g, '&#39;');
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
