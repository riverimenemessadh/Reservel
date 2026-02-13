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
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ route('dashboard') }}">
                <i class="fas fa-building me-2"></i>{{ __('messages.dashboard') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('assets.index') }}">
                                <i class="fas fa-box me-1"></i>{{ __('messages.assets') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('bookings.index') }}">
                                <i class="fas fa-calendar-check me-1"></i>{{ __('messages.bookings') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reports.index') }}">
                                <i class="fas fa-exclamation-circle me-1"></i>{{ __('messages.reports') }}
                            </a>
                        </li>
                    @endauth
                </ul>
                <ul class="navbar-nav">
                    @auth
                        @if(auth()->user()->isAdmin())
                        <!-- Notification Bell Icon -->
                        <li class="nav-item me-3">
                            <button type="button" class="btn btn-link position-relative p-0" id="notificationBell" 
                                    data-bs-toggle="modal" data-bs-target="#notificationsModal" 
                                    style="color: var(--light-bg); border: none; background: none; cursor: pointer;">
                                <i class="fas fa-bell fs-5"></i>
                                <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge bg-danger rounded-pill" style="display: none;">
                                    <span id="unreadCount">0</span>
                                </span>
                            </button>
                        </li>
                        @endif
                    @endauth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i
                                class="fas fa-language me-1"></i>{{ app()->getLocale() == 'ar' ? 'العربية' : 'Français' }}
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('locale.set', 'fr') }}">Français</a></li>
                            <li><a class="dropdown-item" href="{{ route('locale.set', 'ar') }}">العربية</a></li>
                        </ul>
                    </li>
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item"
                                        href="{{ route('profile.show') }}">{{ __('messages.profile') }}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">{{ __('messages.logout') }}</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
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
                    <button id="dismissAllBtn" type="button" class="btn btn-sm btn-outline-danger ms-auto" style="display: none; margin-right: 10px;">
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

    @push('scripts')
    <script>
        const notificationBell = document.getElementById('notificationBell');
        const notificationsContainer = document.getElementById('notificationsContainer');
        const notificationBadge = document.getElementById('notificationBadge');
        const unreadCount = document.getElementById('unreadCount');
        const dismissAllBtn = document.getElementById('dismissAllBtn');

        // Load notifications when modal is shown
        document.getElementById('notificationsModal').addEventListener('show.bs.modal', loadNotifications);

        // Dismiss all notifications
        dismissAllBtn?.addEventListener('click', dismissAllNotifications);

        function loadNotifications() {
            console.log('Loading notifications...');
            axios.get('{{ route("notifications.index") }}')
                .then(response => {
                    console.log('Notifications response:', response.data);
                    const notifications = response.data.notifications;
                    const unreadCount = response.data.unread_count;

                    if (notifications.length === 0) {
                        notificationsContainer.innerHTML = `
                            <div class="empty-notifications">
                                <i class="fas fa-bell-slash"></i>
                                <p>{{ __('messages.no_notifications') }}</p>
                            </div>
                        `;
                        markAllReadBtn.style.display = 'none';
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
                    console.error('Error loading notifications:', error);
                    console.log('Error response status:', error.response?.status);
                    console.log('Error response data:', error.response?.data);
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
                    .catch(error => console.error('Error dismissing all notifications:', error));
            }
        }

        function dismissNotification(notificationId) {
            axios.delete(`/notifications/${notificationId}`)
                .then(() => {
                    loadNotifications();
                })
                .catch(error => console.error('Error dismissing notification:', error));
        }

        function goToReports() {
            window.location.href = '{{ route("reports.index") }}';
        }

        function updateBadge(count) {
            if (count > 0) {
                unreadCount.textContent = count;
                notificationBadge.style.display = 'inline-block';
            } else {
                notificationBadge.style.display = 'none';
            }
        }

        function formatTime(createdAt) {
            const now = new Date();
            const date = new Date(createdAt);
            const seconds = Math.floor((now - date) / 1000);

            if (seconds < 60) {
                return '{{ __("messages.just_now") }}';
            } else if (seconds < 3600) {
                const minutes = Math.floor(seconds / 60);
                return '{{ __("messages.minutes_ago") }}'.replace(':count', minutes);
            } else if (seconds < 86400) {
                const hours = Math.floor(seconds / 3600);
                return '{{ __("messages.hours_ago") }}'.replace(':count', hours);
            } else {
                const days = Math.floor(seconds / 86400);
                return '{{ __("messages.days_ago") }}'.replace(':count', days);
            }
        }

        // Load badge count on page load
        @auth
            @if(auth()->user()->isAdmin())
                loadNotifications();
                setInterval(loadNotifications, 30000); // Refresh every 30 seconds
            @endif
        @endauth
    </script>
    @endpush

    @stack('scripts')
</body>

</html>
