// JS pour la page des achats de besoins
// Utilisez le module Ajax pour les appels AJAX

(() => {
	const villeFilter = document.getElementById('ville-filter');
	const statusEl = document.getElementById('achats-status');
	const tableBody = document.querySelector('table.table tbody');

	if (!villeFilter || !tableBody) return;

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

	const renderTable = (achats) => {
		if (!achats || achats.length === 0) {
			tableBody.innerHTML = '<tr><td colspan="9">Aucun achat enregistré.</td></tr>';
			return;
		}

		tableBody.innerHTML = achats.map((a) => {
			return `
				<tr>
					<td>${escapeHtml(a.id_achat)}</td>
					<td>${escapeHtml(a.nom_ville)}</td>
					<td>${escapeHtml(a.nom_produit)}</td>
					<td>${escapeHtml(a.nom_type_besoin)}</td>
					<td>${fmt(a.montant_besoin)} Ar</td>
					<td>${fmt(a.montant_achat)} Ar</td>
					<td>${fmt(a.montant_frais)} Ar (${fmt(a.frais_pourcent)}%)</td>
					<td>${fmt(a.montant_total)} Ar</td>
					<td>${escapeHtml(a.date_achat)}</td>
				</tr>
			`;
		}).join('');
	};

	villeFilter.addEventListener('change', () => {
		const id = villeFilter.value;
		const url = id ? `/besoin/achats/ville/${id}` : '/besoin/achats';

		Ajax.get(url, {
			statusElement: statusEl,
			onSuccess: (data) => {
				renderTable(data?.achats || []);
			},
			onError: () => {
				if (statusEl) statusEl.textContent = 'Erreur lors du chargement.';
			}
		});
	});
})();
