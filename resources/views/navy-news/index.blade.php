<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">ข่าวราชนาวี</h1></x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">รายการข่าวราชนาวี</h2>
            <p class="text-gray-500 mt-1">จัดการข่าวราชนาวีทั้งหมด</p>
        </div>
        <a href="{{ route('navy-news.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition-colors font-medium">
            <i data-lucide="plus" class="w-5 h-5"></i><span>เพิ่มข่าว</span>
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span class="text-emerald-700">{{ session('success') }}</span>
        </div>
    @endif

    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('navy-news.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาเลขที่ข่าว, หัวข้อ..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">
                </div>
                <select name="urgency" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">
                    <option value="">ทุกชั้นความเร็ว</option>
                    <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>ปกติ</option>
                    <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }} class="text-red-600">ด่วน</option>
                    <option value="very_urgent" {{ request('urgency') == 'very_urgent' ? 'selected' : '' }} class="text-red-600">ด่วนมาก</option>
                    <option value="most_urgent" {{ request('urgency') == 'most_urgent' ? 'selected' : '' }} class="text-red-600">ด่วนที่สุด</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">ค้นหา</button>
            </form>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">เลขที่ข่าว</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">วันที่</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">หัวข้อ</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">ชั้นความเร็ว</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($news as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-navy-700">{{ $item->news_number }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $item->news_date?->locale('th')->translatedFormat('j M Y') }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ Str::limit($item->title, 50) }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $urgencyLabels = [
                                        'normal' => 'ปกติ',
                                        'urgent' => 'ด่วน',
                                        'very_urgent' => 'ด่วนมาก',
                                        'most_urgent' => 'ด่วนที่สุด'
                                    ];
                                    $urgencyClasses = [
                                        'normal' => 'text-gray-600',
                                        'urgent' => 'text-red-600 font-bold',
                                        'very_urgent' => 'text-red-600 font-bold',
                                        'most_urgent' => 'text-red-600 font-extrabold'
                                    ];
                                @endphp
                                <span class="{{ $urgencyClasses[$item->urgency] ?? 'text-gray-600' }}">
                                    {{ $urgencyLabels[$item->urgency] ?? $item->urgency }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('navy-news.show', $item) }}" class="p-2 text-gray-500 hover:text-navy-600 hover:bg-gray-100 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                    <a href="{{ route('navy-news.edit', $item) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                    <form action="{{ route('navy-news.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('ต้องการลบ?')">@csrf @method('DELETE')<button class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button></form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-16 text-center"><div class="flex flex-col items-center"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"><i data-lucide="newspaper" class="w-8 h-8 text-gray-400"></i></div><h4 class="text-gray-700 font-medium">ยังไม่มีข้อมูล</h4></div></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($news->hasPages())<div class="px-6 py-4 border-t border-gray-200">{{ $news->links() }}</div>@endif
    </div>
</x-app-layout>
