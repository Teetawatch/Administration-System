@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <div class="relative bg-blue-800 h-[400px] flex items-center justify-center overflow-hidden">
         <div class="absolute inset-0 z-0 opacity-20 bg-[url('https://upload.wikimedia.org/wikipedia/commons/thumb/d/d7/HTMS_Chakri_Naruebet_CVH-911.jpg/1200px-HTMS_Chakri_Naruebet_CVH-911.jpg')] bg-cover bg-center"></div>
         <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 drop-shadow-md">ยินดีต้อนรับสู่ระบบงานธุรการ</h1>
            <p class="text-blue-100 text-lg md:text-xl max-w-2xl mx-auto drop-shadow-sm">
                ศูนย์กลางข้อมูลข่าวสาร การบริหารจัดการเอกสาร และงานธุรการออนไลน์<br>เพื่อประสิทธิภาพและความรวดเร็วในการปฏิบัติงาน
            </p>
            <div class="mt-8">
                 <a href="{{ route('public.news.index') }}" class="bg-white text-blue-900 font-bold py-3 px-8 rounded-full shadow-lg hover:bg-gray-100 transition transform hover:-translate-y-1">
                    อ่านข่าวประชาสัมพันธ์
                 </a>
            </div>
         </div>
    </div>

    <!-- Latest News Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 border-l-4 border-blue-600 pl-4">ข่าวประชาสัมพันธ์ล่าสุด</h2>
            <a href="{{ route('public.news.index') }}" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1 group">
                ดูทั้งหมด 
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse ($latestNews as $news)
                <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition duration-300 border border-gray-100 flex flex-col h-full">
                    <div class="relative h-48 bg-gray-200 overflow-hidden">
                        @if($news->attachment_path && in_array(pathinfo($news->attachment_path, PATHINFO_EXTENSION), ['jpg','jpeg','png']))
                            <img src="{{ Storage::url($news->attachment_path) }}" alt="{{ $news->title }}" class="w-full h-full object-cover transform hover:scale-105 transition duration-500">
                        @else
                            <div class="flex items-center justify-center h-full bg-blue-50 text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                            {{ $news->category ?? 'ทั่วไป' }}
                        </div>
                    </div>
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="text-sm text-gray-500 mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ \Carbon\Carbon::parse($news->news_date)->addYears(543)->format('d/m/Y') }}
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 hover:text-blue-600 transition">
                            <a href="{{ route('public.news.show', $news) }}">
                                {{ $news->title }}
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3 text-sm flex-1">
                            {{ Str::limit(strip_tags($news->content), 120) }}
                        </p>
                        <a href="{{ route('public.news.show', $news) }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition text-sm">
                            อ่านต่อ
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </a>
                    </div>
                </article>
            @empty
                <div class="col-span-3 text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <p class="text-gray-500 text-lg">ยังไม่มีข่าวประชาสัมพันธ์ในขณะนี้</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection
