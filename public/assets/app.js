function confirmDelete(message = 'Are you sure you want to delete this record?') {
    return window.confirm(message);
}

document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('app-sidebar');
    const profileBtn = document.getElementById('profile-btn');
    const dropdown = document.getElementById('profile-dropdown');

    // Toggle sidebar on small screens
    if (hamburger && sidebar) {
        hamburger.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // Toggle profile dropdown menu
    if (profileBtn && dropdown) {
        profileBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.toggle('show');
        });

        // Close dropdown when clicking anywhere outside
        window.addEventListener('click', () => {
            dropdown.classList.remove('show');
        });
    }
});