@extends('layouts.public')

@section('title', $navyNews->title . ' - ระบบงานธุรการ')

@section('content')
<div class="bg-gray-50 py-8 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Cover Image (if image) -->
                @if($navyNews->attachment_path && in_array(pathinfo($navyNews->attachment_path, PATHINFO_EXTENSION), ['jpg','jpeg','png']))
                    <div class="w-full h-auto">
                        <img src="{{ Storage::url($navyNews->attachment_path) }}" alt="{{ $navyNews->title }}" class="w-full h-auto object-cover">
                    </div>
                @endif

                <div class="p-8">
                    <!-- Meta Header -->
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-6 pb-6 border-b border-gray-100">
                        <div class="flex items-center gap-1 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs font-bold">
                            {{ $navyNews->category ?? 'ทั่วไป' }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($navyNews->news_date)->addYears(543)->format('d M Y') }}
                        </div>
                        <div class="flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10v10" />
                            </svg>
                            เลขที่ข่าว: {{ $navyNews->news_number }}
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 mb-6 leading-tight">{{ $navyNews->title }}</h1>

                    <!-- Content -->
                    <div class="prose prose-blue max-w-none text-gray-700 leading-relaxed mb-8">
                        {!! nl2br(e($navyNews->content)) !!}
                    </div>

                    <!-- Attachments -->
                    @if($navyNews->attachment_path && !in_array(pathinfo($navyNews->attachment_path, PATHINFO_EXTENSION), ['jpg','jpeg','png']))
                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200 mt-8">
                            <h3 class="text-lg font-bold text-gray-900 mb-3 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                                </svg>
                                เอกสารแนบ
                            </h3>
                            <a href="{{ Storage::url($navyNews->attachment_path) }}" target="_blank" class="flex items-center p-3 bg-white rounded-md border border-gray-200 hover:border-blue-500 hover:shadow-sm transition group">
                                <div class="bg-blue-100 text-blue-600 p-2 rounded mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                     <p class="text-sm font-medium text-gray-900 truncate group-hover:text-blue-600 transition">ดาวน์โหลดไฟล์แนบ</p>
                                     <p class="text-xs text-gray-500 uppercase">{{ pathinfo($navyNews->attachment_path, PATHINFO_EXTENSION) }}</p>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 group-hover:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </article>

            <!-- Back Button -->
            <div class="mt-6">
                <a href="{{ route('public.news.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600 font-medium transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    กลับไปหน้ารวมข่าว
                </a>
            </div>
        </div>

        <!-- Sidebar / Recent News -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b border-gray-100">ข่าวล่าสุดอื่นๆ</h3>
                <div class="space-y-6">
                    @forelse($recentNews as $recent)
                        <div class="flex gap-3 group">
                            <div class="w-20 h-20 flex-shrink-0 bg-gray-200 rounded-md overflow-hidden">
                                @if($recent->attachment_path && in_array(pathinfo($recent->attachment_path, PATHINFO_EXTENSION), ['jpg','jpeg','png']))
                                    <img src="{{ Storage::url($recent->attachment_path) }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                @else
                                    <div class="flex items-center justify-center h-full bg-blue-50 text-blue-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h4 class="text-sm font-semibold text-gray-800 line-clamp-2group-hover:text-blue-600 transition">
                                    <a href="{{ route('public.news.show', $recent) }}">
                                        {{ $recent->title }}
                                    </a>
                                </h4>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ \Carbon\Carbon::parse($recent->news_date)->addYears(543)->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">ไม่มีข่าวอื่นๆ</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
