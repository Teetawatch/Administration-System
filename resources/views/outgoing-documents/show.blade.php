<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            รายละเอียดหนังสือส่ง
        </h1>
    </x-slot>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('outgoing-documents.index') }}" class="hover:text-navy-600 transition-colors">เลขหนังสือส่ง</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800">{{ $outgoingDocument->document_number }}</span>
    </div>

    <!-- Detail Card -->
    <div class="card max-w-4xl">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">ข้อมูลหนังสือส่ง</h3>
            <div class="flex items-center gap-2">
                <a href="{{ route('outgoing-documents.edit', $outgoingDocument) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 transition-colors font-medium">
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    แก้ไข
                </a>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- เลขที่หนังสือ -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">เลขที่หนังสือ</dt>
                    <dd class="text-lg font-semibold text-navy-700">{{ $outgoingDocument->document_number }}</dd>
                </div>

                <!-- วันที่ -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">วันที่</dt>
                    <dd class="text-gray-800">{{ $outgoingDocument->document_date?->locale('th')->translatedFormat('j F Y') }}</dd>
                </div>

                <!-- ถึง -->
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">ถึง</dt>
                    <dd class="text-gray-800">{{ $outgoingDocument->to_recipient }}</dd>
                </div>

                <!-- เรื่อง -->
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">เรื่อง</dt>
                    <dd class="text-gray-800">{{ $outgoingDocument->subject }}</dd>
                </div>

                <!-- ชั้นความเร็ว -->
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
                        <span class="{{ $urgencyClasses[$outgoingDocument->urgency] ?? 'text-gray-800' }}">
                            {{ $urgencyLabels[$outgoingDocument->urgency] ?? $outgoingDocument->urgency }}
                        </span>
                    </dd>
                </div>

                <!-- หน่วยงาน -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">หน่วยงาน</dt>
                    <dd class="text-gray-800">
                        @if ($outgoingDocument->department)
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ $outgoingDocument->department }}
                            </span>
                        @else
                            <span class="text-gray-400">ไม่ระบุ</span>
                        @endif
                    </dd>
                </div>

                <!-- ไฟล์แนบ -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ไฟล์แนบ</dt>
                    <dd>
                        @if ($outgoingDocument->attachment_path)
                            <a href="{{ Storage::url($outgoingDocument->attachment_path) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                ดาวน์โหลดไฟล์
                            </a>
                        @else
                            <span class="text-gray-400">ไม่มีไฟล์แนบ</span>
                        @endif
                    </dd>
                </div>

                <!-- รายละเอียด -->
                @if ($outgoingDocument->description)
                    <div class="md:col-span-2">
                        <dt class="text-sm font-medium text-gray-500 mb-1">รายละเอียดเพิ่มเติม</dt>
                        <dd class="text-gray-800 whitespace-pre-wrap bg-gray-50 rounded-lg p-4">{{ $outgoingDocument->description }}</dd>
                    </div>
                @endif

                <!-- ผู้สร้าง -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">สร้างโดย</dt>
                    <dd class="text-gray-800">{{ $outgoingDocument->creator?->name ?? 'ไม่ระบุ' }}</dd>
                </div>

                <!-- วันที่สร้าง -->
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">วันที่สร้าง</dt>
                    <dd class="text-gray-800">{{ $outgoingDocument->created_at?->locale('th')->translatedFormat('j F Y H:i') }}</dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('outgoing-documents.index') }}" 
           class="inline-flex items-center gap-2 px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            กลับไปรายการ
        </a>
    </div>
</x-app-layout>
