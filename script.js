// Toggle sidebar visibility
document.addEventListener('DOMContentLoaded', function () {
    const menuButton = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.querySelector('.main-content');

    menuButton.addEventListener('click', function () {
        sidebar.classList.toggle('open');
        mainContent.classList.toggle('shift');
    });
});
