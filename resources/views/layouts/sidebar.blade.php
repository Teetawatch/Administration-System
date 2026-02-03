<!-- 
    Responsive Sidebar
    - Mobile (< lg): Hidden by default, slides in when hamburger is clicked
    - Desktop (lg+): Always visible, can be collapsed
-->

<!-- Mobile Overlay -->
<div 
    x-show="$store.sidebar.open"
    @click="$store.sidebar.close()"
    class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    x-transition:enter="transition-opacity ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition-opacity ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-cloak
></div>

<!-- Sidebar -->
<aside 
    :class="[
        collapsed ? 'lg:w-20' : 'lg:w-64',
        $store.sidebar.open ? 'translate-x-0' : '-translate-x-full'
    ]"
    class="fixed inset-y-0 left-0 z-50 flex flex-col w-[280px] bg-white border-r border-gray-200 shadow-xl transition-transform duration-300 ease-in-out lg:translate-x-0 lg:shadow-none"
>
    <!-- Logo & Toggle -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 bg-white shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-navy-600 to-navy-800 shadow-lg">
                <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
            </div>
            <span x-show="!collapsed" x-transition.opacity.duration.200ms class="text-lg font-semibold text-navy-800 whitespace-nowrap lg:block" :class="collapsed ? 'lg:hidden' : ''">
                ระบบงานธุรการ
            </span>
        </a>
        
        <!-- Mobile Close Button -->
        <button 
            @click="$store.sidebar.close()"
            class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 active:bg-gray-200 transition-colors"
            aria-label="ปิดเมนู"
        >
            <i data-lucide="x" class="w-6 h-6"></i>
        </button>
        
        <!-- Desktop Collapse Button -->
        <button 
            @click="collapsed = !collapsed"
            class="hidden lg:flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
            aria-label="ย่อ/ขยายเมนู"
        >
            <i data-lucide="chevrons-left" class="w-5 h-5 transition-transform duration-300" :class="collapsed && 'rotate-180'"></i>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        <!-- หน้าหลัก -->
        <a href="{{ route('dashboard') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('dashboard') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">หน้าหลัก</span>
        </a>

        <!-- Divider - หนังสือราชการ -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider" :class="collapsed ? 'lg:hidden' : 'lg:block'">
                หนังสือราชการ
            </p>
            <div x-show="collapsed" class="hidden lg:block h-px mx-3 bg-gray-200"></div>
        </div>

        <!-- เลขหนังสือส่ง -->
        <a href="{{ route('outgoing-documents.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('outgoing-documents.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="send" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">เลขหนังสือส่ง</span>
        </a>

        <!-- หนังสือรับรอง -->
        <a href="{{ route('certificates.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('certificates.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="file-badge" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">หนังสือรับรอง</span>
        </a>

        <!-- ข่าวราชนาวี -->
        <a href="{{ route('navy-news.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('navy-news.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="newspaper" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">ข่าวราชนาวี</span>
        </a>

        <!-- Divider - คำสั่ง -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider" :class="collapsed ? 'lg:hidden' : 'lg:block'">
                คำสั่ง
            </p>
            <div x-show="collapsed" class="hidden lg:block h-px mx-3 bg-gray-200"></div>
        </div>

        <!-- คำสั่งโรงเรียน -->
        <a href="{{ route('school-orders.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('school-orders.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="clipboard-list" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">คำสั่งโรงเรียน</span>
        </a>

        <!-- คำสั่งโรงเรียน (เฉพาะ) -->
        <a href="{{ route('special-orders.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('special-orders.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="clipboard-signature" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">คำสั่งโรงเรียน (เฉพาะ)</span>
        </a>

        <!-- Divider - กิจกรรม -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider" :class="collapsed ? 'lg:hidden' : 'lg:block'">
                กิจกรรม
            </p>
            <div x-show="collapsed" class="hidden lg:block h-px mx-3 bg-gray-200"></div>
        </div>

        <!-- จัดคิวกิจกรรม -->
        <a href="{{ route('activities.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('activities.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="calendar-check" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">จัดคิวกิจกรรม</span>
        </a>

        <!-- รายชื่อข้าราชการ -->
        <a href="{{ route('personnel.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('personnel.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">รายชื่อข้าราชการ</span>
        </a>

        <!-- Divider - ยานพาหนะ -->
        <div class="pt-4 pb-2">
            <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider" :class="collapsed ? 'lg:hidden' : 'lg:block'">
                ยานพาหนะ
            </p>
            <div x-show="collapsed" class="hidden lg:block h-px mx-3 bg-gray-200"></div>
        </div>

        <!-- ข้อมูลยานพาหนะ -->
        <a href="{{ route('vehicles.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('vehicles.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="car-front" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">ข้อมูลยานพาหนะ</span>
        </a>

        <!-- รายการจองยานพาหนะ -->
        <a href="{{ route('vehicle-bookings.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('vehicle-bookings.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="calendar-clock" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">รายการจอง</span>
        </a>

        <!-- จัดการคนขับ -->
        <a href="{{ route('vehicle-drivers.index') }}" 
           class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px]
                  {{ request()->routeIs('vehicle-drivers.*') 
                     ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' 
                     : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}"
           @click="$store.sidebar.close()">
            <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
            <span x-show="!collapsed" x-transition.opacity class="truncate lg:block" :class="collapsed ? 'lg:hidden' : ''">จัดการคนขับ</span>
        </a>
    </nav>

    <!-- User Section -->
    <div class="border-t border-gray-200 p-4 bg-gray-50 shrink-0">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-navy-100 text-navy-700">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>
            <div x-show="!collapsed" x-transition.opacity class="flex-1 min-w-0 lg:block" :class="collapsed ? 'lg:hidden' : ''">
                <p class="text-sm font-medium text-gray-700 truncate">
                    {{ Auth::user()->name ?? 'ผู้ใช้งาน' }}
                </p>
                <p class="text-xs text-gray-500 truncate">
                    {{ Auth::user()->email ?? '' }}
                </p>
            </div>
            <div x-show="!collapsed" class="relative lg:block" :class="collapsed ? 'lg:hidden' : ''" x-data="{ dropdownOpen: false }">
                <button 
                    @click="dropdownOpen = !dropdownOpen"
                    class="flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-200 transition-colors"
                >
                    <i data-lucide="more-vertical" class="w-5 h-5"></i>
                </button>
                
                <div 
                    x-show="dropdownOpen"
                    @click.outside="dropdownOpen = false"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50"
                    style="display: none;"
                >
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100">
                        <i data-lucide="settings" class="w-4 h-4"></i>
                        ตั้งค่าโปรไฟล์
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                            <i data-lucide="log-out" class="w-4 h-4"></i>
                            ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</aside>