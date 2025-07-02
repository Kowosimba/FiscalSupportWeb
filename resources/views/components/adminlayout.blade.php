<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Modern Helpdesk Dashboard">
    <meta name="author" content="">
    <title>Helpdesk - Dashboard</title>
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
 
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
</head>

<body>
    <div class="panel-nav">
        <a href="dashboard.html" class="panel-nav-item active">
            <i class="fas fa-ticket-alt"></i>
            <span class="nav-item-text">Tickets</span>
        </a>
        <a href="call-logs.html" class="panel-nav-item">
            <i class="fas fa-phone"></i>
            <span class="nav-item-text">Call Logs</span>
        </a>
        <a href="content.html" class="panel-nav-item">
            <i class="fas fa-file-alt"></i>
            <span class="nav-item-text">Content</span>
        </a>
        <a href="analytics.html" class="panel-nav-item">
            <i class="fas fa-chart-line"></i>
            <span class="nav-item-text">Analytics</span>
        </a>
        <a href="settings.html" class="panel-nav-item">
            <i class="fas fa-cog"></i>
            <span class="nav-item-text">Settings</span>
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dom = {
                body: document.body,
                sidebar: document.querySelector('.sidebar'),
                sidebarToggle: document.getElementById('sidebarToggle'),
                mobileOverlay: document.getElementById('mobileOverlay'),
                userDropdown: document.getElementById('userDropdown'),
                userDropdownMenu: document.getElementById('userDropdownMenu'),
                logoutTrigger: document.getElementById('logoutTrigger'),
                logoutModal: document.getElementById('logoutModal'),
                modalBackdrop: document.getElementById('modalBackdrop')
            };

            function toggleSidebar() {
                if (window.innerWidth < 768) {
                    dom.sidebar.classList.toggle('mobile-show');
                    dom.mobileOverlay.classList.toggle('show');
                    dom.mobileOverlay.setAttribute('aria-hidden', !dom.sidebar.classList.contains('mobile-show'));
                } else {
                    dom.body.classList.toggle('sidebar-collapsed');
                }
            }

            function closeSidebar() {
                dom.sidebar.classList.remove('mobile-show');
                dom.mobileOverlay.classList.remove('show');
                dom.mobileOverlay.setAttribute('aria-hidden', 'true');
            }

            function toggleDropdown() {
                const isExpanded = dom.userDropdown.getAttribute('aria-expanded') === 'true';
                dom.userDropdownMenu.classList.toggle('show');
                dom.userDropdown.setAttribute('aria-expanded', !isExpanded);
            }

            function closeAllDropdowns() {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                    menu.previousElementSibling.setAttribute('aria-expanded', 'false');
                });
            }

            function toggleModal(modal) {
                modal.classList.toggle('show');
                dom.modalBackdrop.classList.toggle('show');
                modal.setAttribute('aria-hidden', !modal.classList.contains('show'));
            }

            document.addEventListener('click', function(e) {
                if (e.target.closest('#sidebarToggle')) {
                    toggleSidebar();
                }

                if (e.target === dom.mobileOverlay) {
                    closeSidebar();
                }

                if (e.target.closest('#userDropdown')) {
                    toggleDropdown();
                } else if (!e.target.closest('.dropdown-menu')) {
                    closeAllDropdowns();
                }

                if (e.target.hasAttribute('data-toggle') && e.target.dataset.toggle === 'modal') {
                    e.preventDefault();
                    const targetModal = document.querySelector(e.target.dataset.target);
                    if (targetModal) toggleModal(targetModal);
                }

                if (e.target.closest('[data-dismiss="modal"]')) {
                    const modal = e.target.closest('.modal');
                    if (modal) toggleModal(modal);
                }
            });

            dom.modalBackdrop.addEventListener('click', function() {
                document.querySelectorAll('.modal.show').forEach(modal => {
                    toggleModal(modal);
                });
            });

            function handleResize() {
                if (window.innerWidth >= 768 && dom.sidebar.classList.contains('mobile-show')) {
                    closeSidebar();
                }
            }

            window.addEventListener('resize', handleResize);
        });
    </script>
</body>
</html>