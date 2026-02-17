// JS pour le filtre par ville sur la page achats
const villeFilter = document.getElementById('ville-filter');
if (villeFilter) {
    villeFilter.addEventListener('change', function() {
        const id = this.value;
        if (id) {
            window.location.href = `/besoin/achats/ville/${id}`;
        } else {
            window.location.href = '/besoin/achats';
        }
    });
}
