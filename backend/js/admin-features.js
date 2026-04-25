// backend/js/admin-features.js

function initSearch(type) {
    const searchInput = document.getElementById('adminSearch');
    const tableBody = document.querySelector('table tbody');

    if (!searchInput || !tableBody) return;

    searchInput.addEventListener('input', function() {
        const query = this.value;
        const sort = document.getElementById('adminSort')?.value || 'id';
        
        fetch(`ajax_search.php?type=${type}&search=${encodeURIComponent(query)}&sort=${sort}`)
            .then(response => response.text())
            .then(html => {
                tableBody.innerHTML = html;
            });
    });
}

function initSort(type) {
    const sortSelect = document.getElementById('adminSort');
    const tableBody = document.querySelector('table tbody');

    if (!sortSelect || !tableBody) return;

    sortSelect.addEventListener('change', function() {
        const query = document.getElementById('adminSearch')?.value || '';
        const sort = this.value;
        
        fetch(`ajax_search.php?type=${type}&search=${encodeURIComponent(query)}&sort=${sort}`)
            .then(response => response.text())
            .then(html => {
                tableBody.innerHTML = html;
            });
    });
}

// Modal Toggle for "Add" button
function initAddModal() {
    const addBtn = document.getElementById('addBtn');
    const modal = document.getElementById('addModal');
    const closeBtn = document.querySelector('.close-modal');

    if (addBtn && modal) {
        addBtn.onclick = () => modal.style.display = 'flex';
        if (closeBtn) closeBtn.onclick = () => modal.style.display = 'none';
        window.onclick = (event) => {
            if (event.target == modal) modal.style.display = 'none';
        }
    }
}
