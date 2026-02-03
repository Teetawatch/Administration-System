<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">รายละเอียดกิจกรรม</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6"><a href="{{ route('activities.index') }}" class="hover:text-navy-600">จัดคิวกิจกรรม</a><i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">{{ Str::limit($activity->activity_name, 30) }}</span></div>
    <div class="card max-w-4xl"><div class="card-header flex items-center justify-between"><h3 class="text-lg font-semibold text-gray-800">ข้อมูลกิจกรรม</h3><a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 font-medium"><i data-lucide="pencil" class="w-4 h-4"></i>แก้ไข</a></div><div class="card-body"><dl class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500 mb-1">ชื่อกิจกรรม</dt><dd class="text-lg font-semibold text-navy-700">{{ $activity->activity_name }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">วันที่เริ่ม</dt><dd class="text-gray-800">{{ $activity->start_date?->locale('th')->translatedFormat('j F Y') }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">วันที่สิ้นสุด</dt><dd class="text-gray-800">{{ $activity->end_date?->locale('th')->translatedFormat('j F Y') ?: '-' }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">เวลาเริ่ม</dt><dd class="text-gray-800">{{ $activity->start_time ? \Carbon\Carbon::parse($activity->start_time)->format('H:i') . ' น.' : '-' }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">เวลาสิ้นสุด</dt><dd class="text-gray-800">{{ $activity->end_time ? \Carbon\Carbon::parse($activity->end_time)->format('H:i') . ' น.' : '-' }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">สถานที่</dt><dd class="text-gray-800">{{ $activity->location ?: '-' }}</dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">ความสำคัญ</dt><dd><div class="flex items-center gap-1">@for($i = 1; $i <= 5; $i++)<i data-lucide="star" class="w-5 h-5 {{ $i <= $activity->priority ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}"></i>@endfor</div></dd></div><div><dt class="text-sm font-medium text-gray-500 mb-1">สถานะ</dt><dd>@php $statusColors = ['pending' => 'bg-yellow-100 text-yellow-700', 'ongoing' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-red-100 text-red-700']; $statusLabels = ['pending' => 'รอดำเนินการ', 'ongoing' => 'กำลังดำเนินการ', 'completed' => 'เสร็จสิ้น', 'cancelled' => 'ยกเลิก']; @endphp<span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$activity->status] ?? '' }}">{{ $statusLabels[$activity->status] ?? $activity->status }}</span></dd></div>@if($activity->description)<div class="md:col-span-2"><dt class="text-sm font-medium text-gray-500 mb-1">รายละเอียด</dt><dd class="text-gray-800 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $activity->description }}</dd></div>@endif</dl></div></div>
    {{-- Participants Section --}}
    <div class="card max-w-4xl mt-6">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                <i data-lucide="users" class="w-5 h-5 inline-block mr-2 text-navy-600"></i>
                ผู้เข้าร่วมกิจกรรม
                <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-navy-100 text-navy-700">
                    {{ $activity->participants->count() }} คน
                </span>
            </h3>
        </div>
        <div class="card-body">
            @if($activity->participants->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($activity->participants as $participant)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0">
                                @if($participant->photo_path)
                                    <img src="{{ asset('storage/' . $participant->photo_path) }}"
                                         alt="{{ $participant->full_name }}"
                                         class="w-10 h-10 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-navy-100 flex items-center justify-center">
                                        <i data-lucide="user" class="w-5 h-5 text-navy-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $participant->full_name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $participant->position ?? $participant->department ?? '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 mx-auto bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i data-lucide="users" class="w-8 h-8 text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">ยังไม่มีผู้เข้าร่วมกิจกรรมนี้</p>
                    <a href="{{ route('activities.edit', $activity) }}" class="inline-flex items-center gap-2 mt-4 text-sm text-navy-600 hover:text-navy-800">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        เพิ่มผู้เข้าร่วม
                    </a>
                </div>
            @endif
        </div>
    </div>
    
    <div class="mt-6"><a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-800"><i data-lucide="arrow-left" class="w-4 h-4"></i>กลับไปรายการ</a></div>
</x-app-layout>
