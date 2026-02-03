<x-app-layout>
    <div class="space-y-8">
        <!-- Welcome Section -->
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-blue-900 via-blue-800 to-blue-900 shadow-xl">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl"></div>
            
            <div class="relative z-10 p-8 sm:p-10 text-white">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-blue-700/50 rounded-full text-xs font-semibold backdrop-blur-sm border border-blue-600/50 text-blue-100">
                                Admin Dashboard
                            </span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold mb-2 tracking-tight">
                            สวัสดี, {{ Auth::user()->name }} 
                        </h2>
                        <p class="text-blue-200 text-lg max-w-2xl">
                            ยินดีต้อนรับสู่ระบบบริหารจัดการเอกสารและงานธุรการ โรงเรียนพลาธิการ กรมพลาธิการทหารเรือ
                        </p>
                    </div>
                    <div class="flex items-center gap-4 bg-white/10 px-5 py-3 rounded-2xl backdrop-blur-sm border border-white/10">
                        <div class="text-right">
                            <p class="text-xs text-blue-200 uppercase tracking-wider font-semibold">วันนี้</p>
                            <p class="text-xl font-bold">{{ now()->locale('th')->translatedFormat('j F Y') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center shadow-inner">
                            <i data-lucide="calendar-days" class="w-6 h-6 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Stat Card 1 -->
            <div class="group bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-blue-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-500"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <i data-lucide="send" class="w-6 h-6 text-blue-600 group-hover:text-white"></i>
                        </div>
                        <span class="flex items-center text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-lg">
                            <i data-lucide="trending-up" class="w-3 h-3 mr-1"></i> วันนี้
                        </span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:translate-x-1 transition-transform">{{ $todayOutgoing }}</h3>
                    <p class="text-sm text-gray-500 font-medium">หนังสือส่ง (เรื่อง)</p>
                </div>
            </div>

            <!-- Stat Card 2 -->
            <div class="group bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-500"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                            <i data-lucide="file-badge" class="w-6 h-6 text-emerald-600 group-hover:text-white"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">ปีนี้</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:translate-x-1 transition-transform">{{ $yearCertificates }}</h3>
                    <p class="text-sm text-gray-500 font-medium">หนังสือรับรอง (ฉบับ)</p>
                </div>
            </div>

            <!-- Stat Card 3 -->
            <div class="group bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-amber-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-500"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center group-hover:bg-amber-600 group-hover:text-white transition-colors duration-300">
                            <i data-lucide="clipboard-list" class="w-6 h-6 text-amber-600 group-hover:text-white"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">ปีนี้</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:translate-x-1 transition-transform">{{ $yearSchoolOrders }}</h3>
                    <p class="text-sm text-gray-500 font-medium">คำสั่งโรงเรียน (เรื่อง)</p>
                </div>
            </div>

            <!-- Stat Card 4 -->
            <div class="group bg-white rounded-2xl p-6 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.12)] transition-all duration-300 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-purple-50 rounded-full -mr-8 -mt-8 transition-transform group-hover:scale-150 duration-500"></div>
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:bg-purple-600 group-hover:text-white transition-colors duration-300">
                            <i data-lucide="users" class="w-6 h-6 text-purple-600 group-hover:text-white"></i>
                        </div>
                        <span class="text-xs font-medium text-gray-400 bg-gray-50 px-2 py-1 rounded-lg">ทั้งหมด</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1 group-hover:translate-x-1 transition-transform">{{ $totalPersonnel }}</h3>
                    <p class="text-sm text-gray-500 font-medium">ข้าราชการ (นาย)</p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Quick Actions -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="zap" class="w-5 h-5 text-amber-500 fill-current"></i>
                            ทางลัด (Quick Actions)
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        <a href="{{ route('outgoing-documents.create') }}" class="group flex items-center gap-4 p-4 rounded-xl bg-white border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-blue-700">ออกเลขหนังสือส่ง</h4>
                                <p class="text-sm text-gray-500 group-hover:text-blue-600/70">สร้างหนังสือส่งใหม่</p>
                            </div>
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="chevron-right" class="w-5 h-5 text-blue-400"></i>
                            </div>
                        </a>
                        
                        <a href="{{ route('certificates.create') }}" class="group flex items-center gap-4 p-4 rounded-xl bg-white border border-gray-100 hover:border-emerald-200 hover:bg-emerald-50/50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="file-plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-emerald-700">ออกหนังสือรับรอง</h4>
                                <p class="text-sm text-gray-500 group-hover:text-emerald-600/70">สร้างหนังสือรับรองใหม่</p>
                            </div>
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="chevron-right" class="w-5 h-5 text-emerald-400"></i>
                            </div>
                        </a>

                        <a href="{{ route('school-orders.create') }}" class="group flex items-center gap-4 p-4 rounded-xl bg-white border border-gray-100 hover:border-amber-200 hover:bg-amber-50/50 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 group-hover:scale-110 transition-transform">
                                <i data-lucide="clipboard-plus" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800 group-hover:text-amber-700">ออกคำสั่งโรงเรียน</h4>
                                <p class="text-sm text-gray-500 group-hover:text-amber-600/70">สร้างคำสั่งใหม่</p>
                            </div>
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-lucide="chevron-right" class="w-5 h-5 text-amber-400"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Recent Activity -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                    <div class="p-6 border-b border-gray-50 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i data-lucide="clock" class="w-5 h-5 text-blue-500"></i>
                            ความเคลื่อนไหวล่าสุด
                        </h3>
                        <a href="{{ route('outgoing-documents.index') }}" class="text-sm font-medium text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1">
                            ดูทั้งหมด <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    <div class="p-0">
                        @if($recentDocuments->count() > 0)
                            <div class="divide-y divide-gray-50">
                                @foreach($recentDocuments as $doc)
                                    <div class="group p-4 hover:bg-gray-50/80 transition-colors flex items-center gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shadow-sm border border-gray-100
                                                {{ $doc->type == 'outgoing_document' ? 'bg-blue-50 text-blue-600' : 'bg-emerald-50 text-emerald-600' }}">
                                                @if($doc->type == 'outgoing_document')
                                                    <i data-lucide="send" class="w-6 h-6 transition-transform group-hover:-rotate-12"></i>
                                                @else
                                                    <i data-lucide="file-badge" class="w-6 h-6 transition-transform group-hover:rotate-12"></i>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-1 mb-1">
                                                <p class="text-sm font-bold text-gray-900 truncate pr-4 group-hover:text-blue-700 transition-colors">
                                                    {{ $doc->display_title }}
                                                </p>
                                                <span class="text-xs text-gray-400 whitespace-nowrap bg-gray-100 px-2 py-0.5 rounded-full">
                                                    {{ $doc->display_date->locale('th')->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500 truncate">{{ $doc->display_desc }}</p>
                                        </div>
                                        <div>
                                            <a href="{{ route($doc->route_name, $doc->id) }}" class="p-2 rounded-lg text-gray-400 hover:bg-white hover:text-blue-600 hover:shadow-sm transition-all block ring-1 ring-transparent hover:ring-gray-100">
                                                <i data-lucide="external-link" class="w-5 h-5"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4 inner-shadow">
                                    <i data-lucide="inbox" class="w-10 h-10 text-gray-300"></i>
                                </div>
                                <h4 class="text-lg font-bold text-gray-700">ยังไม่มีข้อมูล</h4>
                                <p class="text-gray-500 max-w-sm mx-auto mt-2 text-sm">
                                    ระบบจะแสดงรายการเอกสารล่าสุดที่นี่เมื่อมีการใช้งาน
                                    เริ่มต้นโดยการใช้งานเมนู "ทางลัด" ด้านซ้าย
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
