// JS pour la page de simulation d'achat
// Utilisez le module Ajax pour les appels AJAX

(() => {
	const simulationForm = document.getElementById('form-simulation');
	if (!simulationForm) return;

	const validationForm = document.getElementById('form-validate');
	const errorsEl = document.getElementById('simulation-errors');
	const errorsList = errorsEl ? errorsEl.querySelector('ul') : null;
	const successEl = document.getElementById('simulation-success');
	const resultCard = document.getElementById('simulation-result');
	const statusEl = document.getElementById('simulation-status');
	const validationStatusEl = document.getElementById('validation-status');

	const resultBesoin = document.getElementById('result-besoin');
	const resultMontantAchat = document.getElementById('result-montant-achat');
	const resultFraisPourcent = document.getElementById('result-frais-pourcent');
	const resultMontantFrais = document.getElementById('result-montant-frais');
	const resultMontantTotal = document.getElementById('result-montant-total');
	const resultMontantRestant = document.getElementById('result-montant-restant');
	const resultDonsRestants = document.getElementById('result-dons-restants');
	const inputIdBesoin = document.getElementById('result-id-besoin');
	const inputMontantAchat = document.getElementById('result-montant-achat-input');

	const fmt = (value) => {
		const num = Number(value) || 0;
		return num.toFixed(2);
	};

	const clearErrors = () => {
		if (!errorsEl || !errorsList) return;
		errorsList.innerHTML = '';
		errorsEl.style.display = 'none';
	};

	const showErrors = (errors) => {
		if (!errorsEl || !errorsList) return;
		errorsList.innerHTML = '';
		(errors || []).forEach((err) => {
			const li = document.createElement('li');
			li.textContent = err;
			errorsList.appendChild(li);
		});
		errorsEl.style.display = errors && errors.length ? 'block' : 'none';
	};

	const setSuccess = (message) => {
		if (!successEl) return;
		successEl.textContent = message || '';
		successEl.style.display = message ? 'block' : 'none';
	};

	const renderResult = (result) => {
		if (!resultCard) return;

		if (!result) {
			resultCard.style.display = 'none';
			return;
		}

		const besoinLabel = `${result.besoin?.nom_produit ?? ''} (${result.besoin?.nom_ville ?? ''})`;
		if (resultBesoin) resultBesoin.textContent = besoinLabel.trim();
		if (resultMontantAchat) resultMontantAchat.textContent = `${fmt(result.montant_achat)} Ar`;
		if (resultFraisPourcent) resultFraisPourcent.textContent = `${fmt(result.frais_pourcent)}%`;
		if (resultMontantFrais) resultMontantFrais.textContent = `${fmt(result.montant_frais)} Ar`;
		if (resultMontantTotal) resultMontantTotal.textContent = `${fmt(result.montant_total)} Ar`;
		if (resultMontantRestant) resultMontantRestant.textContent = `${fmt(result.montant_restant_besoin)} Ar`;
		if (resultDonsRestants) resultDonsRestants.textContent = `${fmt(result.dons_restants)} Ar`;

		if (inputIdBesoin) inputIdBesoin.value = result.id_besoin ?? '';
		if (inputMontantAchat) inputMontantAchat.value = result.montant_achat ?? '';

		resultCard.style.display = 'block';
	};

	simulationForm.addEventListener('submit', (event) => {
		event.preventDefault();
		clearErrors();
		setSuccess('');

		const formData = new FormData(simulationForm);

		Ajax.post('/besoin/simulation', formData, {
			statusElement: statusEl,
			onSuccess: (data) => {
				if (data?.errors?.length) {
					showErrors(data.errors);
					renderResult(null);
					return;
				}

				renderResult(data?.result ?? null);
			},
			onError: () => {
				showErrors(['Erreur lors de la simulation.']);
			}
		});
	});

	if (validationForm) {
		validationForm.addEventListener('submit', (event) => {
			event.preventDefault();
			clearErrors();
			setSuccess('');

			const formData = new FormData(validationForm);

			Ajax.post('/besoin/simulation/valider', formData, {
				statusElement: validationStatusEl,
				onSuccess: (data) => {
					if (data?.errors?.length) {
						showErrors(data.errors);
						renderResult(data?.result ?? null);
						return;
					}

					renderResult(null);
					setSuccess(data?.success || 'Achat validé avec succès.');
					simulationForm.reset();
				},
				onError: () => {
					showErrors(['Erreur lors de la validation.']);
				}
			});
		});
	}
})();
