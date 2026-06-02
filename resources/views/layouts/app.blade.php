<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('Reservel-favicon.ico') }}">

    @if (app()->getLocale() == 'ar')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @else
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: '#154269',
                        'navy-dark': '#0f2f4a',
                        teal: '#4c9183',
                        'teal-dark': '#3a7065',
                        'mid-blue': '#4e81ad',
                    }
                }
            },
            corePlugins: {
                preflight: false,
            }
        }
    </script>

    <style>
        /* Scrollbar styling for notifications modal */
        #notificationsContainer::-webkit-scrollbar {
            width: 8px;
        }

        #notificationsContainer::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        #notificationsContainer::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }

        #notificationsContainer::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Firefox scrollbar */
        #notificationsContainer {
            scrollbar-color: #888 #f1f1f1;
            scrollbar-width: thin;
        }

        :root {
            --primary-color: #154269;
            --secondary-color: #4e81ad;
            --success-color: #32965a;
            --warning-color: #de8337;
            --danger-color: #ae2e3c;
            --bright-red: #bf2234;
            --info-color: #4c9183;
            --light-bg: #f8fafc;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar .nav-link,
        .navbar-brand,
        .navbar i {
            color: var(--light-bg) !important;
        }

        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-info {
            background-color: var(--info-color) !important;
        }

        .bg-success {
            background-color: var(--success-color) !important;
        }

        .bg-warning {
            background-color: var(--warning-color) !important;
        }

        .bg-danger {
            background-color: var(--danger-color) !important;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .text-success {
            color: var(--success-color) !important;
        }

        .text-danger {
            color: var(--danger-color) !important;
        }

        .btn-primary {
            background-color: var(--secondary-color) !important;
            border: none !important;
            color: white !important;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .btn-primary i {
            color: white !important;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .card-header.bg-primary h4,
        .card-header.bg-primary i {
            color: var(--light-bg) !important;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 700;
            display: inline-block;
        }

        .status-available {
            background: #d1fae5;
            color: var(--success-color) !important;
        }

        .status-in_use {
            background: #ffedd5;
            color: var(--warning-color) !important;
        }

        .status-in_repair {
            background: #fee2e2;
            color: var(--danger-color) !important;
        }

        .progress-bar {
            background-color: var(--warning-color) !important;
        }

        .asset-image-small {
            width: 45px;
            height: 45px;
            object-fit: cover;
            border-radius: 8px;
        }

        .fa-building,
        .fa-door-open,
        .fa-laptop,
        .fa-box,
        .fa-calendar-alt {
            color: var(--primary-color) !important;
        }

        .bg-primary i,
        .btn-primary i,
        .navbar i,
        .card-header.bg-primary i {
            color: var(--light-bg) !important;
        }

        .bg-light i.fa-building,
        .bg-light i.fa-laptop {
            color: var(--primary-color) !important;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Notification Styles */
        #notificationBell {
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 40px;
            width: 40px;
            vertical-align: middle;
        }

        #notificationBell:hover {
            transform: scale(1.2);
        }

        #notificationBadge {
            font-size: 0.65rem;
            padding: 2px 5px !important;
            min-width: 18px;
            height: 18px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
        }

        .notification-item {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            transition: background-color 0.2s ease;
        }

        .notification-item:hover {
            background-color: #f8fafc;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-text {
            font-size: 0.95rem;
            color: #334155;
        }

        .notification-time {
            font-size: 0.85rem;
            color: #94a3b8;
            margin-top: 4px;
        }

        .notification-dismiss-btn {
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .notification-item:hover .notification-dismiss-btn {
            opacity: 1;
        }

        #notificationsModal .modal-body {
            max-height: 400px;
            overflow-y: auto;
        }

        .empty-notifications {
            text-align: center;
            padding: 30px 20px;
            color: #94a3b8;
        }

        .empty-notifications i {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #cbd5e1;
        }

        /* ── Tailwind Navbar Overrides ── */
        #main-navbar {
            background-color: #154269;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
        }

        .nav-link-item {
            position: relative;
            color: #c8ddf0 !important;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            transition: color 0.2s ease, background-color 0.2s ease;
            white-space: nowrap;
        }

        .nav-link-item:hover,
        .nav-link-item.active {
            color: #ffffff !important;
            background-color: rgba(76, 145, 131, 0.25);
        }

        .nav-link-item.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background-color: #4c9183;
            border-radius: 2px;
        }

        .nav-link-item i {
            color: inherit !important;
        }

        .lang-pill {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 999px;
            border: 1.5px solid rgba(76, 145, 131, 0.5);
            color: #c8ddf0;
            background: transparent;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .lang-pill:hover,
        .lang-pill.active-lang {
            background-color: #4c9183;
            border-color: #4c9183;
            color: #ffffff;
            text-decoration: none;
        }

        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #c8ddf0 !important;
            font-size: 0.875rem;
            font-weight: 500;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 8px;
            padding: 6px 12px;
            transition: background 0.2s ease, border-color 0.2s ease;
        }

        .user-dropdown-toggle:hover {
            background: rgba(76, 145, 131, 0.25);
            border-color: rgba(76, 145, 131, 0.5);
            color: #ffffff !important;
        }

        .user-dropdown-toggle i {
            color: inherit !important;
        }

        .user-role-badge {
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
            background-color: rgba(76, 145, 131, 0.35);
            color: #a8d8cc;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .bell-btn {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            background: rgba(255, 255, 255, 0.06);
            color: #c8ddf0 !important;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .bell-btn:hover {
            background: rgba(76, 145, 131, 0.25);
            border-color: rgba(76, 145, 131, 0.5);
            color: #ffffff !important;
            transform: scale(1.05);
        }

        .bell-btn i {
            color: inherit !important;
        }

        #notificationBadge {
            position: absolute;
            top: -4px;
            right: -4px;
            background-color: #ae2e3c;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 17px;
            height: 17px;
            border-radius: 999px;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
            border: 2px solid #154269;
        }

        .mobile-menu-btn {
            display: none;
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            padding: 6px 10px;
            color: white;
            cursor: pointer;
        }

        /* Desktop: navbar collapse always visible as a flex row */
        #navbarCollapse {
            display: flex;
            flex: 1;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 991px) {
            .mobile-menu-btn {
                display: flex;
                align-items: center;
            }

            #navbarCollapse {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background-color: #0f2f4a;
                border-top: 1px solid rgba(76, 145, 131, 0.3);
                padding: 12px 16px 16px;
                z-index: 1000;
                display: none !important;
                flex-direction: column;
                gap: 4px;
                flex: unset;
            }

            #navbarCollapse.open {
                display: flex !important;
            }

            .navbar-nav-links,
            .navbar-right-items {
                flex-direction: column;
                align-items: flex-start;
                gap: 4px;
            }

            .navbar-divider {
                display: none;
            }

            .user-dropdown-toggle {
                width: 100%;
            }
        }

        .dropdown-menu {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            padding: 6px;
        }

        .dropdown-item {
            border-radius: 6px;
            padding: 8px 12px;
            font-size: 0.875rem;
            color: #334155;
            transition: background-color 0.15s ease;
        }

        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: #154269;
        }
    </style>

    @stack('styles')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>
    <!-- ── Navbar ── -->
    <nav id="main-navbar" class="sticky-top" style="z-index: 1030;">
        <div class="container-fluid px-4" style="display: flex; align-items: center; height: 62px; gap: 16px;">

            <!-- Brand / Logo -->
            <a href="{{ route('dashboard') }}"
                style="display: flex; align-items: center; text-decoration: none; flex-shrink: 0;">
                <span
                    style="font-size: 1.15rem; font-weight: 700; color: #ffffff; letter-spacing: -0.01em;">Reservel</span>
            </a>

            <!-- Mobile toggle -->
            <button class="mobile-menu-btn ms-auto" id="mobileMenuBtn" aria-expanded="false"
                aria-controls="navbarCollapse">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Collapsible content -->
            <div id="navbarCollapse">

                <!-- Nav Links (center) -->
                <div class="navbar-nav-links" style="display: flex; align-items: center; gap: 2px; flex: 1;">
                    @auth
                        <a href="{{ route('assets.index') }}"
                            class="nav-link-item {{ request()->routeIs('assets.*') ? 'active' : '' }}">
                            <i class="fas fa-box me-1"></i>{{ __('messages.assets') }}
                        </a>
                        <a href="{{ route('bookings.index') }}"
                            class="nav-link-item {{ request()->routeIs('bookings.*') ? 'active' : '' }}">
                            <i class="fas fa-calendar-check me-1"></i>{{ __('messages.bookings') }}
                        </a>
                        <a href="{{ route('reports.index') }}"
                            class="nav-link-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ __('messages.reports') }}
                        </a>
                    @endauth
                </div>

                <!-- Right items -->
                <div class="navbar-right-items" style="display: flex; align-items: center; gap: 8px;">

                    <!-- Language Switcher -->
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <a href="{{ route('locale.set', 'fr') }}"
                            class="lang-pill {{ app()->getLocale() == 'fr' ? 'active-lang' : '' }}">FR</a>
                        <a href="{{ route('locale.set', 'ar') }}"
                            class="lang-pill {{ app()->getLocale() == 'ar' ? 'active-lang' : '' }}">AR</a>
                    </div>

                    <div class="navbar-divider" style="width: 1px; height: 28px; background: rgba(255,255,255,0.15);">
                    </div>

                    @auth
                        <!-- Notification Bell -->
                        @if (auth()->user()->isAdmin())
                            <button type="button" class="bell-btn" id="notificationBell" data-bs-toggle="modal"
                                data-bs-target="#notificationsModal">
                                <i class="fas fa-bell" style="font-size: 0.95rem;"></i>
                                <span id="notificationBadge" style="display: none;">
                                    <span id="unreadCount">0</span>
                                </span>
                            </button>
                        @endif

                        <!-- User Dropdown -->
                        <div class="dropdown">
                            <button class="user-dropdown-toggle dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false" style="border: none;">
                                <i class="fas fa-user-circle" style="font-size: 1rem;"></i>
                                <span>{{ auth()->user()->name }}</span>
                                @if (auth()->user()->isAdmin())
                                    <span class="user-role-badge">Admin</span>
                                @endif
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="fas fa-id-card me-2 text-muted"></i>{{ __('messages.profile') }}
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider my-1">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i>{{ __('messages.logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="container my-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Notifications Modal -->
    <div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationsModalLabel">{{ __('messages.notifications') }}</h5>
                    <button id="dismissAllBtn" type="button" class="btn btn-sm btn-outline-danger ms-auto"
                        style="display: none; margin-right: 10px;">
                        {{ __('messages.dismiss_all') }}
                    </button>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="notificationsContainer" style="max-height: 400px; overflow-y: auto;">
                    <div class="empty-notifications">
                        <i class="fas fa-bell-slash"></i>
                        <p>{{ __('messages.no_notifications') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const notificationBell = document.getElementById('notificationBell');
        const notificationsContainer = document.getElementById('notificationsContainer');
        const notificationBadge = document.getElementById('notificationBadge');
        const unreadCount = document.getElementById('unreadCount');
        const dismissAllBtn = document.getElementById('dismissAllBtn');

        // Load notifications when modal is shown
        document.getElementById('notificationsModal').addEventListener('show.bs.modal', loadNotifications);

        // Clear polling interval when modal closes
        let notificationPollingInterval = null;
        document.getElementById('notificationsModal').addEventListener('hide.bs.modal', function() {
            if (notificationPollingInterval !== null) {
                clearInterval(notificationPollingInterval);
                notificationPollingInterval = null;
            }
        });

        // Dismiss all notifications
        dismissAllBtn?.addEventListener('click', dismissAllNotifications);

        function loadNotifications() {
            axios.get('{{ route('notifications.index') }}')
                .then(response => {
                    const notifications = response.data.notifications;
                    const unreadCount = response.data.unread_count;

                    if (notifications.length === 0) {
                        notificationsContainer.innerHTML = `
                            <div class="empty-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <p>{{ __('messages.no_notifications') }}</p>
                            </div>
                        `;
                        dismissAllBtn.style.display = 'none';
                    } else {
                        let html = '';
                        notifications.forEach(notification => {
                            html += `
                                <div class="notification-item ${!notification.read_at ? 'bg-light' : ''}" style="cursor: pointer; padding: 12px; border-bottom: 1px solid #dee2e6; transition: background-color 0.2s;" data-notification-id="${notification.id}" onmouseover="this.style.backgroundColor='#e9ecef'" onmouseout="this.style.backgroundColor=''">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1" style="cursor: pointer;" onclick="goToReports()">
                                            <p class="notification-text mb-0">
                                                ${notification.data.message}
                                            </p>
                                            <div class="notification-time">
                                                ${formatTime(notification.created_at)}
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-link p-0 notification-dismiss-btn text-danger" data-notification-id="${notification.id}" style="margin-left: 10px; cursor: pointer;">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                        notificationsContainer.innerHTML = html;

                        // Add event listeners to dismiss buttons
                        document.querySelectorAll('.notification-dismiss-btn').forEach(btn => {
                            btn.addEventListener('click', function(e) {
                                e.stopPropagation();
                                e.preventDefault();
                                const notificationId = this.getAttribute('data-notification-id');
                                dismissNotification(notificationId);
                            });
                        });

                        dismissAllBtn.style.display = notifications.length > 0 ? 'block' : 'none';
                    }
                    updateBadge(unreadCount);
                })
                .catch(error => {
                    // silently fail — bell stays empty
                });
        }

        function dismissAllNotifications() {
            const dismissBtns = document.querySelectorAll('.notification-dismiss-btn');
            const deletePromises = [];

            dismissBtns.forEach(btn => {
                const notificationId = btn.getAttribute('data-notification-id');
                if (notificationId) {
                    deletePromises.push(axios.delete(`/notifications/${notificationId}`));
                }
            });

            if (deletePromises.length > 0) {
                Promise.all(deletePromises)
                    .then(() => {
                        loadNotifications();
                    })
                    .catch(error => {
                        /* silent */ });
            }
        }

        function dismissNotification(notificationId) {
            axios.delete(`/notifications/${notificationId}`)
                .then(() => {
                    loadNotifications();
                })
                .catch(error => {
                    /* silent */ });
        }

        function goToReports() {
            window.location.href = '{{ route('reports.index') }}';
        }

        function updateBadge(count) {
            if (count > 0) {
                unreadCount.textContent = count;
                notificationBadge.style.display = 'inline-flex';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        function formatTime(createdAt) {
            const now = new Date();
            const date = new Date(createdAt);
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) {
                return '{{ __('messages.just_now') }}';
            } else if (seconds < 3600) {
                const minutes = Math.floor(seconds / 60);
                return '{{ __('messages.minutes_ago') }}'.replace(':count', minutes);
            } else if (seconds < 86400) {
                const hours = Math.floor(seconds / 3600);
                return '{{ __('messages.hours_ago') }}'.replace(':count', hours);
            } else {
                const days = Math.floor(seconds / 86400);
                return '{{ __('messages.days_ago') }}'.replace(':count', days);
            }
        }

        // Load badge count on page load (without starting uncleared polling outside modal)
        @auth
        @if (auth()->user()->isAdmin())
            loadNotifications();
        @endif
        @endauth
    </script>

    <script>
        // Mobile hamburger — proper open/close toggle
        (function() {
            const btn = document.getElementById('mobileMenuBtn');
            const menu = document.getElementById('navbarCollapse');
            if (!btn || !menu) return;

            btn.addEventListener('click', function() {
                const isOpen = menu.classList.toggle('open');
                btn.setAttribute('aria-expanded', isOpen);
            });

            // Close when clicking a nav link (UX on mobile)
            menu.querySelectorAll('.nav-link-item').forEach(function(link) {
                link.addEventListener('click', function() {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                });
            });

            // Close when clicking outside
            document.addEventListener('click', function(e) {
                if (!btn.contains(e.target) && !menu.contains(e.target)) {
                    menu.classList.remove('open');
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
