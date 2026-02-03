@extends('layouts.public')

@section('title', 'ข่าวประชาสัมพันธ์ - ระบบงานธุรการ')

@section('content')
<div class="bg-white py-8 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-gray-900">ข่าวประชาสัมพันธ์ทั้งหมด</h1>
        <p class="mt-2 text-gray-600">ติดตามข่าวสารและประกาศล่าสุดจากหน่วยงาน</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Search & Filter -->
    <div class="mb-8 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <form action="{{ route('public.news.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <label for="search" class="sr-only">ค้นหา</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                        placeholder="ค้นหาหัวข้อข่าว...">
                </div>
            </div>
            <div class="w-full md:w-64">
                <label for="category" class="sr-only">หมวดหมู่</label>
                <select name="category" id="category" onchange="this.form.submit()"
                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                    <option value="">ทุกหมวดหมู่</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                            {{ $category }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
        @forelse ($news as $item)
            <article class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition duration-300 flex flex-col h-full">
                <div class="relative h-48 bg-gray-200 overflow-hidden">
                    @if($item->attachment_path && in_array(pathinfo($item->attachment_path, PATHINFO_EXTENSION), ['jpg','jpeg','png']))
                        <img src="{{ Storage::url($item->attachment_path) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="flex items-center justify-center h-full bg-blue-50 text-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-0 right-0 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">
                        {{ $item->category ?? 'ทั่วไป' }}
                    </div>
                </div>
                <div class="p-6 flex-1 flex flex-col">
                    <div class="text-sm text-gray-500 mb-2 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ \Carbon\Carbon::parse($item->news_date)->addYears(543)->format('d/m/Y') }}
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                        <a href="{{ route('public.news.show', $item) }}" class="hover:text-blue-600 transition">
                            {{ $item->title }}
                        </a>
                    </h3>
                     <!-- Content Text only -->
                    <p class="text-gray-600 mb-4 line-clamp-3 text-sm flex-1">
                        {{ Str::limit(strip_tags($item->content), 120) }}
                    </p>
                    <a href="{{ route('public.news.show', $item) }}" class="inline-flex items-center text-blue-600 font-semibold hover:text-blue-800 transition text-sm">
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
                <p class="text-gray-500 text-lg">ไม่พบข่าวประชาสัมพันธ์ที่ค้นหา</p>
                <a href="{{ route('public.news.index') }}" class="mt-4 inline-block text-blue-600 hover:text-blue-800 font-medium">
                    ดูข่าวทั้งหมด
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $news->links() }}
    </div>
</div>
@endsection
