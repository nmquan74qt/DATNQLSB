<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 backdrop-blur-md shadow-sm border-b border-slate-100 transition-all duration-300" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-3">
            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center">
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-md shadow-primary/30 group-hover:scale-105 transition-transform">
                        <i class="fa-solid fa-futbol text-xl"></i>
                    </div>
                    <span class="font-heading font-extrabold text-2xl tracking-tight text-slate-900">
                        Pitch<span class="text-primary">Manage</span>
                    </span>
                </a>
            </div>

            <!-- Desktop Menu -->
            <div class="hidden md:flex md:items-center md:space-x-8">
                <a href="{{ route('home') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Trang Chủ</a>
                <a href="{{ route('fields.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Đặt Sân</a>
                <a href="{{ route('fields.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Bảng Giá</a>
                <a href="{{ route('blog.index') }}" class="font-medium text-slate-600 hover:text-primary transition-colors">Tin Tức</a>
            </div>

            <!-- Auth -->
            <div class="hidden md:flex items-center space-x-4">
                @auth
                    <!-- Notification Bell -->
                    <div class="relative" x-data="notificationPoller()" x-init="initPoller()">
                        <button @click="open = !open" class="relative p-2 text-slate-500 hover:text-primary transition-colors focus:outline-none">
                            <i class="fa-regular fa-bell text-xl"></i>
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center border-2 border-white shadow-sm" x-cloak></span>
                        </button>
                        
                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" x-transition.origin.top.right x-cloak class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50">
                            <div class="px-4 py-2 border-b border-slate-100 flex justify-between items-center">
                                <h3 class="font-bold text-slate-800">Thông báo</h3>
                                <button @click="markAllRead" class="text-xs text-primary hover:underline font-medium">Đánh dấu tất cả đã đọc</button>
                            </div>
                            <div class="max-h-80 overflow-y-auto custom-scrollbar">
                                <template x-for="notif in notifications" :key="notif.id">
                                    <a href="javascript:void(0);" class="px-4 py-3 hover:bg-slate-50 flex gap-3 transition-colors border-b border-slate-50" @click="markAsRead(notif.id)">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                             :class="{
                                                 'bg-emerald-100 text-emerald-500': notif.data.type === 'success',
                                                 'bg-blue-100 text-blue-500': notif.data.type === 'info',
                                                 'bg-amber-100 text-amber-500': notif.data.type === 'warning',
                                                 'bg-pink-100 text-pink-500': notif.data.type === 'promo',
                                                 'bg-primary/10 text-primary': !['success', 'info', 'warning', 'promo'].includes(notif.data.type)
                                             }">
                                            <i class="fa-solid"
                                               :class="{
                                                   'fa-check': notif.data.type === 'success',
                                                   'fa-info': notif.data.type === 'info',
                                                   'fa-exclamation': notif.data.type === 'warning',
                                                   'fa-gift': notif.data.type === 'promo',
                                                   'fa-bell': !['success', 'info', 'warning', 'promo'].includes(notif.data.type)
                                               }"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-slate-800" :class="{ 'font-bold': notif.read_at === null }" x-text="notif.data.title"></p>
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-1" x-text="notif.data.message"></p>
                                            <p class="text-xs text-slate-400 mt-1" x-text="formatTime(notif.created_at)"></p>
                                        </div>
                                        <div x-show="notif.read_at === null" class="shrink-0 mt-1 ml-auto">
                                            <div class="w-2 h-2 bg-primary rounded-full"></div>
                                        </div>
                                    </a>
                                </template>
                                <div x-show="notifications.length === 0" class="p-8 text-center text-slate-500">
                                    <i class="fa-regular fa-bell-slash text-3xl mb-2 text-slate-200"></i>
                                    <p class="text-sm">Không có thông báo nào</p>
                                </div>
                            </div>
                            <div class="px-4 py-2 border-t border-slate-100 text-center">
                                <a href="#" class="text-sm font-medium text-primary hover:underline">Xem tất cả</a>
                            </div>
                        </div>

                        <!-- Real-time Toast (Optional, but dropdown popup is enough if we open it automatically) -->
                        <div x-show="toastOpen" x-transition.duration.500ms x-cloak class="fixed top-20 right-4 md:right-8 w-80 bg-white rounded-2xl shadow-2xl border border-emerald-100 overflow-hidden z-[100] cursor-pointer" @click="toastOpen = false; open = true">
                            <div class="p-4 border-l-4 border-emerald-500 flex gap-3">
                                <div class="shrink-0 mt-1 text-emerald-500">
                                    <i class="fa-solid fa-square-check text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-800" x-text="latestNotification?.data?.title"></p>
                                    <p class="text-sm text-slate-600 mt-0.5 line-clamp-2" x-text="latestNotification?.data?.message"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 focus:outline-none group bg-slate-50 hover:bg-slate-100 px-2 py-1.5 rounded-full border border-slate-100 transition-colors">
                            <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&background=10B981&color=fff' }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover">
                            <span class="font-bold text-sm text-slate-700 hidden lg:block">{{ auth()->user()->name }}</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 mr-2 transition-transform" :class="{'rotate-180': open}"></i>
                        </button>
                        <!-- Dropdown menu -->
                        <div x-show="open" x-transition.origin.top.right x-cloak class="absolute right-0 mt-3 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-1 overflow-hidden z-50">
                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors"><i class="fa-solid fa-chart-line w-5"></i> Trang Quản Trị</a>
                            @else
                                <a href="{{ route('customer.dashboard') }}" class="block px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-primary/5 hover:text-primary transition-colors"><i class="fa-regular fa-user w-5"></i> Tài Khoản</a>
                            @endif
                            <div class="h-px bg-slate-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm font-medium text-red-500 hover:bg-red-50 transition-colors"><i class="fa-solid fa-arrow-right-from-bracket w-5"></i> Đăng Xuất</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-primary transition-colors px-4 py-2">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="bg-primary hover:bg-secondary text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-primary/20 transition-all transform hover:-translate-y-0.5">Đăng Ký</a>
                @endauth
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-2xl text-slate-700 focus:outline-none p-2">
                    <i class="fa-solid fa-bars" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-times" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Panel -->
    <div x-show="mobileMenuOpen" x-transition.opacity x-cloak class="md:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl">
        <div class="px-4 pt-2 pb-6 space-y-1">
            <a href="{{ route('home') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-700 hover:bg-slate-50 hover:text-primary">Trang Chủ</a>
            <a href="{{ route('fields.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Đặt Sân</a>
            <a href="{{ route('fields.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Bảng Giá</a>
            <a href="{{ route('blog.index') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary">Tin Tức</a>
            
            <div class="h-px bg-slate-100 my-2"></div>
            
            @auth
                <div class="px-3 py-3 flex items-center gap-3">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}" alt="Avatar" class="w-10 h-10 rounded-full border border-slate-200">
                    <div>
                        <div class="font-bold text-slate-900">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-slate-500">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'staff')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary"><i class="fa-solid fa-chart-line w-6"></i> Trang Quản Trị</a>
                @else
                    <a href="{{ route('customer.dashboard') }}" class="block px-3 py-3 rounded-xl text-base font-bold text-slate-600 hover:bg-slate-50 hover:text-primary"><i class="fa-regular fa-user w-6"></i> Tài Khoản</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button type="submit" class="w-full text-left px-3 py-3 rounded-xl text-base font-bold text-red-500 hover:bg-red-50"><i class="fa-solid fa-arrow-right-from-bracket w-6"></i> Đăng Xuất</button>
                </form>
            @else
                <div class="grid grid-cols-2 gap-4 mt-4 px-3">
                    <a href="{{ route('login') }}" class="text-center px-4 py-2.5 bg-slate-50 border border-slate-200 text-slate-700 rounded-xl font-bold">Đăng Nhập</a>
                    <a href="{{ route('register') }}" class="text-center px-4 py-2.5 bg-primary text-white rounded-xl font-bold shadow-sm">Đăng Ký</a>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('notificationPoller', () => ({
            open: false,
            toastOpen: false,
            notifications: [],
            unreadCount: 0,
            lastCheck: null,
            latestNotification: null,

            initPoller() {
                // Initial load from a global var or API, but since we don't have it initialized, we just fetch
                this.fetchNotifications();
                
                // Poll every 3 seconds for real-time feel
                setInterval(() => {
                    this.pollNewNotifications();
                }, 3000);
            },

            fetchNotifications() {
                // Using the unread poll endpoint initially just to get current state
                fetch('{{ route('notifications.poll') ?? '/notifications/poll' }}')
                    .then(res => res.json())
                    .then(data => {
                        this.notifications = data.notifications;
                        this.unreadCount = data.count;
                        this.lastCheck = data.now;
                    })
                    .catch(err => console.error('Notification error:', err));
            },

            pollNewNotifications() {
                if (!this.lastCheck) return;
                fetch(`{{ route('notifications.poll') ?? '/notifications/poll' }}?last_check=${encodeURIComponent(this.lastCheck)}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.notifications && data.notifications.length > 0) {
                            // New notifications arrived!
                            data.notifications.forEach(n => {
                                // Add to top of list
                                this.notifications.unshift(n);
                            });
                            this.unreadCount = data.count;
                            this.lastCheck = data.now;
                            
                            // Show toast for the latest one
                            this.latestNotification = data.notifications[0];
                            this.toastOpen = true;
                            
                            // Play sound (optional)
                            // new Audio('/sounds/notification.mp3').play().catch(e => {});

                            // Hide toast after 5 seconds
                            setTimeout(() => {
                                this.toastOpen = false;
                            }, 5000);
                        } else {
                            this.lastCheck = data.now;
                        }
                    })
                    .catch(err => console.log('Poll error', err));
            },

            markAsRead(id) {
                fetch(`/notifications/${id}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    let notif = this.notifications.find(n => n.id === id);
                    if(notif && notif.read_at === null) {
                        notif.read_at = new Date().toISOString();
                        this.unreadCount = Math.max(0, this.unreadCount - 1);
                    }
                });
            },

            markAllRead() {
                fetch(`/notifications/read-all`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                }).then(() => {
                    this.notifications.forEach(n => n.read_at = new Date().toISOString());
                    this.unreadCount = 0;
                });
            },

            formatTime(dateStr) {
                if(!dateStr) return '';
                const date = new Date(dateStr);
                const now = new Date();
                const diffMs = now - date;
                const diffMins = Math.floor(diffMs / 60000);
                
                if (diffMins < 1) return 'Vừa xong';
                if (diffMins < 60) return `${diffMins} phút trước`;
                const diffHours = Math.floor(diffMins / 60);
                if (diffHours < 24) return `${diffHours} giờ trước`;
                return date.toLocaleDateString('vi-VN');
            }
        }));
    });
</script>
