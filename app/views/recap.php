<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récapitulation Financière</title>
    <link rel="stylesheet" href="/style/style.css">
</head>
<body>

<?php include __DIR__ . '/partials/header.php'; ?>

        <h2>Récapitulation des Besoins (Montants)</h2>

        <div class="controls">
            <span class="loader" id="loader"></span>
            <button id="btn-refresh" class="btn btn-primary">Actualiser les données</button>
        </div>

        <div class="recap-cards">
            <div class="card bg-blue">
                <h3 id="total-needs">0 Ar</h3>
                <p>Besoins Totaux</p>
            </div>
            <div class="card bg-green">
                <h3 id="satisfied-needs">0 Ar</h3>
                <p>Besoins Satisfaits</p>
            </div>
            <div class="card bg-orange">
                <h3 id="remaining-needs">0 Ar</h3>
                <p>Besoins Restants</p>
            </div>
        </div>

<script>
    function formatMoney(amount) {
        return new Intl.NumberFormat('fr-MG', { style: 'currency', currency: 'MGA', maximumFractionDigits: 2 }).format(amount).replace('MGA', 'Ar');
    }

    function loadData() {
        const loader = document.getElementById('loader');
        loader.style.display = 'inline-block';
        
        fetch('/recap/data')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-needs').textContent = formatMoney(data.total);
                document.getElementById('satisfied-needs').textContent = formatMoney(data.satisfait);
                document.getElementById('remaining-needs').textContent = formatMoney(data.restant);
                loader.style.display = 'none';
            })
            .catch(err => { console.error(err); loader.style.display = 'none'; });
    }

    document.getElementById('btn-refresh').addEventListener('click', loadData);
    document.addEventListener('DOMContentLoaded', loadData);
</script>

<?php include __DIR__ . '/partials/footer.php'; ?>
</body>
</html>