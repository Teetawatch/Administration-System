<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">รายละเอียดข่าวราชนาวี</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('navy-news.index') }}" class="hover:text-navy-600">ข่าวราชนาวี</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">{{ $navyNews->news_number }}</span>
    </div>
    <div class="card max-w-4xl">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">ข้อมูลข่าว</h3>
            <a href="{{ route('navy-news.edit', $navyNews) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 font-medium"><i data-lucide="pencil" class="w-4 h-4"></i>แก้ไข</a>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div><dt class="text-sm font-medium text-gray-500 mb-1">เลขที่ข่าว</dt><dd class="text-lg font-semibold text-navy-700">{{ $navyNews->news_number }}</dd></div>
                <div><dt class="text-sm font-medium text-gray-500 mb-1">วันที่</dt><dd class="text-gray-800">{{ $navyNews->news_date?->locale('th')->translatedFormat('j F Y') }}</dd></div>
                <div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500 mb-1">หัวข้อ</dt><dd class="text-gray-800">{{ $navyNews->title }}</dd></div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ชั้นความเร็ว</dt>
                    <dd>
                        @php
                            $urgencyLabels = [
                                'normal' => 'ปกติ',
                                'urgent' => 'ด่วน',
                                'very_urgent' => 'ด่วนมาก',
                                'most_urgent' => 'ด่วนที่สุด'
                            ];
                            $urgencyClasses = [
                                'normal' => 'text-gray-800',
                                'urgent' => 'text-red-600 font-bold',
                                'very_urgent' => 'text-red-600 font-bold',
                                'most_urgent' => 'text-red-600 font-extrabold'
                            ];
                        @endphp
                        <span class="{{ $urgencyClasses[$navyNews->urgency] ?? 'text-gray-800' }}">
                            {{ $urgencyLabels[$navyNews->urgency] ?? $navyNews->urgency }}
                        </span>
                    </dd>
                </div>
                @if($navyNews->attachment_path)<div><dt class="text-sm font-medium text-gray-500 mb-1">ไฟล์แนบ</dt><dd><a href="{{ Storage::url($navyNews->attachment_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200"><i data-lucide="download" class="w-4 h-4"></i>ดาวน์โหลด</a></dd></div>@endif
                @if($navyNews->content)<div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500 mb-1">เนื้อหา</dt><dd class="text-gray-800 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $navyNews->content }}</dd></div>@endif
            </dl>
        </div>
    </div>
    <div class="mt-6"><a href="{{ route('navy-news.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-800"><i data-lucide="arrow-left" class="w-4 h-4"></i>กลับไปรายการ</a></div>
</x-app-layout>
