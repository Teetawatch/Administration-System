{{--
Responsive Sidebar
- Mobile (< 1024px): Hidden by default, show with hamburger - Desktop (≥ 1024px): Always visible Uses parent's x-data
    variable: sidebarOpen, collapsed --}} {{-- Mobile Overlay --}} <div x-show="sidebarOpen"
    x-transition.opacity.duration.300ms @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"
    x-cloak>
    </div>

    {{-- Sidebar --}}
    <aside id="sidebar"
        class="fixed inset-y-0 left-0 z-50 flex flex-col w-[280px] bg-white border-r border-gray-200 shadow-xl lg:shadow-none transition-transform duration-300 ease-in-out -translate-x-full lg:translate-x-0"
        :class="{
        '!translate-x-0': sidebarOpen,
        'lg:w-64': !collapsed,
        'lg:w-20': collapsed
    }">
        {{-- Logo & Toggle --}}
        <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 bg-white shrink-0">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-gradient-to-br from-navy-600 to-navy-800 shadow-lg shrink-0">
                    <i data-lucide="file-text" class="w-5 h-5 text-white"></i>
                </div>
                <span x-show="!collapsed" x-transition.opacity
                    class="text-lg font-semibold text-navy-800 whitespace-nowrap">
                    ระบบงานธุรการ
                </span>
            </a>

            {{-- Mobile Close Button --}}
            <button type="button" @click="sidebarOpen = false"
                class="flex lg:hidden items-center justify-center w-11 h-11 rounded-xl text-gray-500 hover:bg-gray-100 hover:text-gray-700 active:bg-gray-200 transition-colors"
                aria-label="ปิดเมนู">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

            {{-- Desktop Collapse Button --}}
            <button type="button" @click="collapsed = !collapsed"
                class="hidden lg:flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-100 hover:text-gray-700 transition-colors"
                aria-label="ย่อ/ขยายเมนู">
                <i data-lucide="chevrons-left" class="w-5 h-5 transition-transform duration-300"
                    :class="collapsed && 'rotate-180'"></i>
            </button>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto scrollbar-thin">
            {{-- หน้าหลัก --}}
            <a href="{{ route('dashboard') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('dashboard') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="layout-dashboard" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">หน้าหลัก</span>
            </a>

            {{-- Divider --}}
            <div class="pt-4 pb-2">
                <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    หนังสือราชการ</p>
                <div x-show="collapsed" class="h-px mx-3 bg-gray-200 hidden lg:block"></div>
            </div>

            <a href="{{ route('outgoing-documents.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('outgoing-documents.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="send" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">เลขหนังสือส่ง</span>
            </a>

            <a href="{{ route('certificates.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('certificates.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="file-badge" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">หนังสือรับรอง</span>
            </a>

            <a href="{{ route('navy-news.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('navy-news.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="newspaper" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">ข่าวราชนาวี</span>
            </a>

            {{-- Divider --}}
            <div class="pt-4 pb-2">
                <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">คำสั่ง
                </p>
                <div x-show="collapsed" class="h-px mx-3 bg-gray-200 hidden lg:block"></div>
            </div>

            <a href="{{ route('school-orders.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('school-orders.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="clipboard-list" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">คำสั่งโรงเรียน</span>
            </a>

            <a href="{{ route('special-orders.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('special-orders.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="clipboard-signature" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">คำสั่งโรงเรียน (เฉพาะ)</span>
            </a>

            {{-- Divider --}}
            <div class="pt-4 pb-2">
                <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">กิจกรรม
                </p>
                <div x-show="collapsed" class="h-px mx-3 bg-gray-200 hidden lg:block"></div>
            </div>

            <a href="{{ route('activities.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('activities.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="calendar-check" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">จัดคิวกิจกรรม</span>
            </a>

            <a href="{{ route('personnel.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('personnel.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">รายชื่อข้าราชการ</span>
            </a>

            {{-- Divider --}}
            <div class="pt-4 pb-2">
                <p x-show="!collapsed" class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    ยานพาหนะ</p>
                <div x-show="collapsed" class="h-px mx-3 bg-gray-200 hidden lg:block"></div>
            </div>

            <a href="{{ route('vehicles.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('vehicles.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="car-front" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">ข้อมูลยานพาหนะ</span>
            </a>

            <a href="{{ route('vehicle-bookings.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('vehicle-bookings.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="calendar-clock" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">รายการจอง</span>
            </a>

            <a href="{{ route('vehicle-drivers.index') }}" @click="sidebarOpen = false"
                class="flex items-center gap-3 px-3 py-3 rounded-xl text-sm font-medium transition-all duration-200 min-h-[48px] {{ request()->routeIs('vehicle-drivers.*') ? 'bg-navy-50 text-navy-700 border-l-4 border-navy-600' : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900' }}">
                <i data-lucide="users" class="w-5 h-5 shrink-0"></i>
                <span x-show="!collapsed" x-transition.opacity class="truncate">จัดการคนขับ</span>
            </a>
        </nav>

        {{-- User Section --}}
        <div class="border-t border-gray-200 p-4 bg-gray-50 shrink-0">
            <div class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-navy-100 text-navy-700 shrink-0">
                    <i data-lucide="user" class="w-5 h-5"></i>
                </div>
                <div x-show="!collapsed" x-transition.opacity class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-700 truncate">{{ Auth::user()->name ?? 'ผู้ใช้งาน' }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <div x-show="!collapsed" x-data="{ userMenu: false }" class="relative">
                    <button type="button" @click="userMenu = !userMenu"
                        class="flex items-center justify-center w-9 h-9 rounded-lg text-gray-500 hover:bg-gray-200 transition-colors">
                        <i data-lucide="more-vertical" class="w-5 h-5"></i>
                    </button>
                    <div x-show="userMenu" @click.outside="userMenu = false" x-transition
                        class="absolute bottom-full right-0 mb-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                        <a href="{{ route('profile.edit') }}"
                            class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100">
                            <i data-lucide="settings" class="w-4 h-4"></i>
                            ตั้งค่าโปรไฟล์
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex items-center gap-2 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                <i data-lucide="log-out" class="w-4 h-4"></i>
                                ออกจากระบบ
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>