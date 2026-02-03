<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">รายละเอียดหนังสือรับรอง</h1>
    </x-slot>

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('certificates.index') }}" class="hover:text-navy-600">หนังสือรับรอง</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800">{{ $certificate->certificate_number }}</span>
    </div>

    <div class="card max-w-4xl">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">ข้อมูลหนังสือรับรอง</h3>
            <a href="{{ route('certificates.pdf', $certificate) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-navy-600 text-white rounded-lg hover:bg-navy-700 font-medium">
                <i data-lucide="printer" class="w-4 h-4"></i> พิมพ์
            </a>
            <a href="{{ route('certificates.edit', $certificate) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 font-medium">
                <i data-lucide="pencil" class="w-4 h-4"></i> แก้ไข
            </a>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">เลขที่</dt>
                    <dd class="text-lg font-semibold text-navy-700">{{ $certificate->certificate_number }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">วันที่ออก</dt>
                    <dd class="text-gray-800">{{ $certificate->issue_date?->locale('th')->translatedFormat('j F Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ชื่อบุคลากร</dt>
                    <dd class="text-gray-800">{{ $certificate->personnel_name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ตำแหน่ง</dt>
                    <dd class="text-gray-800">{{ $certificate->position ?: '-' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">วัตถุประสงค์</dt>
                    <dd class="text-gray-800">{{ $certificate->purpose }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">สถานะ</dt>
                    <dd>
                        @php
                            $statusColors = ['draft' => 'bg-gray-100 text-gray-700', 'issued' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-red-100 text-red-700'];
                            $statusLabels = ['draft' => 'ฉบับร่าง', 'issued' => 'ออกแล้ว', 'cancelled' => 'ยกเลิก'];
                        @endphp
                        <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$certificate->status] ?? '' }}">
                            {{ $statusLabels[$certificate->status] ?? $certificate->status }}
                        </span>
                    </dd>
                </div>
                @if ($certificate->content)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 mb-1">เนื้อหา</dt>
                        <dd class="text-gray-800 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $certificate->content }}</dd>
                    </div>
                @endif
            </dl>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('certificates.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-800">
            <i data-lucide="arrow-left" class="w-4 h-4"></i> กลับไปรายการ
        </a>
    </div>
</x-app-layout>
