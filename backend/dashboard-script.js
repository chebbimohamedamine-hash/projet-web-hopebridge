// Simple dashboard interactions
document.querySelectorAll('.menu-link').forEach(link => {
    link.addEventListener('click', function() {
        document.querySelector('.menu-link.active').classList.remove('active');
        this.classList.add('active');
    });
});

// Mock notification alert
document.querySelector('.fa-bell').addEventListener('click', () => {
    alert("Vous n'avez pas de nouvelles notifications.");
});
