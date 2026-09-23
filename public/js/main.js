/**
 * SIM-KERJASAMA Main JavaScript Helpers & Alpine Components
 * STIKes Panti Waluya Malang
 */

// Public Catalog Alpine Component
function publicCatalog() {
    return {
        modalOpen: false,
        loading: false,
        cooperation: null,
        viewMode: localStorage.getItem('sim_view_mode') || 'grid',
        openModal(id) {
            this.modalOpen = true;
            this.loading = true;
            fetch(`/cooperations/${id}/detail`)
                .then(res => res.json())
                .then(data => {
                    this.cooperation = data;
                    this.loading = false;
                })
                .catch(err => {
                    console.error('Error fetching cooperation detail:', err);
                    this.loading = false;
                });
        }
    };
}
