<!-- Sidebar -->
<aside 
    :class="[
        collapsed ? 'w-64 lg:w-20' : 'w-64',
        $store.sidebar.open ? 'translate-x-0 shadow-xl' : '-translate-x-full lg:translate-x-0'
    ]"
    class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-gray-200 transition-all duration-300"
    x-cloak
>
    <!-- Logo & Toggle -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-navy-700">
                <i data-lucide="file-text" class="w-6 h-6 text-white"></i>
            </div>
            <span x-show="!collapsed" x-transition class="text-lg font-semibold text-navy-800">
                ระบบงานธุรการ
            </span>
        </a>
        <button 
            @click="$store.sidebar.open = false"
            class="lg:hidden flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
        >
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
        <button 
            @click="collapsed = !collapsed"
            class="hidden lg:flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors"
        >
            <i data-lucide="chevrons-left" class="w-5 h-5 transition-transform" :class="collapsed && 'rotate-180'"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin">
        <!-- หน้าหลัก -->
        <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">หน้าหลัก</span>
        </x-sidebar-link>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                หนังสือราชการ
            </p>
            <div x-show="collapsed" class="h-px mx-4 bg-gray-200"></div>
        </div>

        <!-- เลขหนังสือส่ง -->
        <x-sidebar-link :href="route('outgoing-documents.index')" :active="request()->routeIs('outgoing-documents.*')">
            <i data-lucide="send" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">เลขหนังสือส่ง</span>
        </x-sidebar-link>

        <!-- หนังสือรับรอง -->
        <x-sidebar-link :href="route('certificates.index')" :active="request()->routeIs('certificates.*')">
            <i data-lucide="file-badge" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">หนังสือรับรอง</span>
        </x-sidebar-link>

        <!-- ข่าวราชนาวี -->
        <x-sidebar-link :href="route('navy-news.index')" :active="request()->routeIs('navy-news.*')">
            <i data-lucide="newspaper" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">ข่าวราชนาวี</span>
        </x-sidebar-link>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                คำสั่ง
            </p>
            <div x-show="collapsed" class="h-px mx-4 bg-gray-200"></div>
        </div>

        <!-- คำสั่งโรงเรียน -->
        <x-sidebar-link :href="route('school-orders.index')" :active="request()->routeIs('school-orders.*')">
            <i data-lucide="clipboard-list" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">คำสั่งโรงเรียน</span>
        </x-sidebar-link>

        <!-- คำสั่งโรงเรียน (เฉพาะ) -->
        <x-sidebar-link :href="route('special-orders.index')" :active="request()->routeIs('special-orders.*')">
            <i data-lucide="clipboard-signature" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">คำสั่งโรงเรียน (เฉพาะ)</span>
        </x-sidebar-link>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                กิจกรรม
            </p>
            <div x-show="collapsed" class="h-px mx-4 bg-gray-200"></div>
        </div>

        <!-- จัดคิวกิจกรรม -->
        <x-sidebar-link :href="route('activities.index')" :active="request()->routeIs('activities.*')">
            <i data-lucide="calendar-check" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">จัดคิวกิจกรรม</span>
        </x-sidebar-link>

        <!-- รายชื่อข้าราชการ -->
        <x-sidebar-link :href="route('personnel.index')" :active="request()->routeIs('personnel.*')">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">รายชื่อข้าราชการ</span>
        </x-sidebar-link>

        <!-- Divider -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                ยานพาหนะ
            </p>
            <div x-show="collapsed" class="h-px mx-4 bg-gray-200"></div>
        </div>

        <!-- ข้อมูลยานพาหนะ -->
        <x-sidebar-link :href="route('vehicles.index')" :active="request()->routeIs('vehicles.*')">
            <i data-lucide="car-front" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">ข้อมูลยานพาหนะ</span>
        </x-sidebar-link>

        <!-- รายการจองยานพาหนะ -->
        <x-sidebar-link :href="route('vehicle-bookings.index')" :active="request()->routeIs('vehicle-bookings.*')">
            <i data-lucide="calendar-clock" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">รายการจอง</span>
        </x-sidebar-link>

        <!-- จัดการคนขับ -->
        <x-sidebar-link :href="route('vehicle-drivers.index')" :active="request()->routeIs('vehicle-drivers.*')">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition class="truncate">จัดการคนขับ</span>
        </x-sidebar-link>
    </nav>

    <!-- User Section -->
    <div class="border-t border-gray-200 p-4">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-navy-100 text-navy-700">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <div x-show="!collapsed" x-transition class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-700 truncate">
                    {{ Auth::user()->name ?? 'ผู้ใช้งาน' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ Auth::user()->email ?? '' }}
                </p>
            </div>
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center justify-center w-8 h-8 rounded-lg text-gray-500 hover:bg-gray-100 transition-colors">
                        <i data-lucide="more-vertical" class="w-5 h-5"></i>
                    </button>
                </x-slot>
                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">
                        <i data-lucide="settings" class="w-4 h-4 mr-2"></i>
                        ตั้งค่าโปรไฟล์
                    </x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                            ออกจากระบบ
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</aside>

<!-- Mobile Overlay -->
<div 
    x-show="$store.sidebar.open"
    x-transition:enter="transition-opacity ease-linear duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-linear duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @click="$store.sidebar.open = false"
    class="fixed inset-0 z-40 bg-gray-600/50 lg:hidden"
    x-cloak
></div>
