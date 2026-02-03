<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            เลขหนังสือส่ง
        </h1>
    </x-slot>

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">รายการเลขหนังสือส่ง</h2>
            <p class="text-gray-500 mt-1">จัดการเลขหนังสือส่งทั้งหมด</p>
        </div>
    <div class="flex gap-2">
            <button type="button" onclick="document.getElementById('bulk-print-form').submit()" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
                <i data-lucide="printer" class="w-5 h-5"></i>
                <span>พิมพ์เอกสาร</span>
            </button>
            <a href="{{ route('outgoing-documents.create') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition-colors font-medium">
                <i data-lucide="plus" class="w-5 h-5"></i>
                <span>เพิ่มเลขหนังสือส่ง</span>
            </a>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
            <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
            <span class="text-emerald-700">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Search & Filters -->
    <div class="card mb-6">
        <div class="card-body">
            <form action="{{ route('outgoing-documents.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="ค้นหาเลขหนังสือ, เรื่อง, ผู้รับ..." 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                </div>
                <select name="urgency" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                    <option value="">ทุกชั้นความเร็ว</option>
                    <option value="normal" {{ request('urgency') == 'normal' ? 'selected' : '' }}>ปกติ</option>
                    <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }} class="text-red-600">ด่วน</option>
                    <option value="very_urgent" {{ request('urgency') == 'very_urgent' ? 'selected' : '' }} class="text-red-600">ด่วนมาก</option>
                    <option value="most_urgent" {{ request('urgency') == 'most_urgent' ? 'selected' : '' }} class="text-red-600">ด่วนที่สุด</option>
                </select>
                <select name="department" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                    <option value="">ทุกหน่วยงาน</option>
                    @foreach ($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>
                            {{ $dept }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    ค้นหา
                </button>
                @if (request('search') || request('department') || request('urgency'))
                    <a href="{{ route('outgoing-documents.index') }}" class="px-4 py-2.5 text-gray-500 hover:text-gray-700 transition-colors">
                        ล้าง
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Hidden Bulk Print Form -->
    <form action="{{ route('outgoing-documents.export-pdf') }}" method="POST" target="_blank" id="bulk-print-form" class="hidden">
        @csrf
    </form>

    <!-- Table -->
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-center w-16">
                            <input type="checkbox" onclick="toggleAll(this)" class="rounded border-gray-300 text-navy-600 shadow-sm focus:border-navy-300 focus:ring focus:ring-navy-200 focus:ring-opacity-50">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            เลขที่หนังสือ
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            วันที่
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ถึง
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            เรื่อง
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ชั้นความเร็ว
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            หน่วยงาน
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            ไฟล์แนบ
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            จัดการ
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($documents as $doc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="document_ids[]" value="{{ $doc->id }}" form="bulk-print-form" class="rounded border-gray-300 text-navy-600 shadow-sm focus:border-navy-300 focus:ring focus:ring-navy-200 focus:ring-opacity-50">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-navy-700">{{ $doc->document_number }}</span>
                                    @if($doc->is_secret)
                                        <span class="px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700 border border-red-200">
                                            ลับ
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ $doc->document_date?->locale('th')->translatedFormat('j M Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ Str::limit($doc->to_recipient, 30) }}
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-800">{{ Str::limit($doc->subject, 50) }}</p>
                            </td>
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
                                <span class="{{ $urgencyClasses[$doc->urgency] ?? 'text-gray-600' }}">
                                    {{ $urgencyLabels[$doc->urgency] ?? $doc->urgency }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($doc->department)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $doc->department }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if ($doc->attachment_path)
                                    <a href="{{ Storage::url($doc->attachment_path) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1 text-navy-600 hover:text-navy-800">
                                        <i data-lucide="paperclip" class="w-4 h-4"></i>
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('outgoing-documents.show', $doc) }}" 
                                       class="p-2 text-gray-500 hover:text-navy-600 hover:bg-gray-100 rounded-lg transition-colors"
                                       title="ดูรายละเอียด">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('outgoing-documents.edit', $doc) }}" 
                                       class="p-2 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg transition-colors"
                                       title="แก้ไข">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('outgoing-documents.destroy', $doc) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('ต้องการลบรายการนี้หรือไม่?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg transition-colors"
                                                title="ลบ">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="inbox" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-700 mb-1">ยังไม่มีข้อมูล</h4>
                                    <p class="text-gray-500 mb-4">เริ่มต้นเพิ่มเลขหนังสือส่งแรกของคุณ</p>
                                    <a href="{{ route('outgoing-documents.create') }}" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition-colors">
                                        <i data-lucide="plus" class="w-4 h-4"></i>
                                        <span>เพิ่มเลขหนังสือส่ง</span>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($documents->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $documents->links() }}
            </div>
        @endif
        </div>
    </div>

    <script>
        function toggleAll(source) {
            checkboxes = document.getElementsByName('document_ids[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
                checkboxes[i].checked = source.checked;
            }
        }
    </script>
</x-app-layout>
