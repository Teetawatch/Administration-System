<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">หนังสือรับรอง</h1>
    </x-slot>

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">รายการหนังสือรับรอง</h2>
            <p class="text-gray-500 mt-1">จัดการหนังสือรับรองทั้งหมด</p>
        </div>
        <a href="{{ route('certificates.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition-colors font-medium">
            <i data-lucide="plus" class="w-5 h-5"></i>
            <span>เพิ่มหนังสือรับรอง</span>
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
            <form action="{{ route('certificates.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="relative flex-1">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาเลขที่, ชื่อบุคลากร, วัตถุประสงค์..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                </div>
                <select name="status" class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                    <option value="">ทุกสถานะ</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>ฉบับร่าง</option>
                    <option value="issued" {{ request('status') == 'issued' ? 'selected' : '' }}>ออกแล้ว</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                </select>
                <button type="submit" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">ค้นหา</button>
            </form>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">เลขที่</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">วันที่ออก</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อหนังสือรับรอง</th>

                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($certificates as $cert)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-navy-700">{{ $cert->certificate_number }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $cert->issue_date?->locale('th')->translatedFormat('j M Y') }}</td>
                            <td class="px-6 py-4 text-gray-800">{{ $cert->personnel_name }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusColors = ['draft' => 'bg-gray-100 text-gray-700', 'issued' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-red-100 text-red-700'];
                                    $statusLabels = ['draft' => 'ฉบับร่าง', 'issued' => 'ออกแล้ว', 'cancelled' => 'ยกเลิก'];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$cert->status] ?? '' }}">
                                    {{ $statusLabels[$cert->status] ?? $cert->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('certificates.show', $cert) }}" class="p-2 text-gray-500 hover:text-navy-600 hover:bg-gray-100 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                    <a href="{{ route('certificates.edit', $cert) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                    <form action="{{ route('certificates.destroy', $cert) }}" method="POST" class="inline" onsubmit="return confirm('ต้องการลบรายการนี้?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="file-badge" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                    <h4 class="text-lg font-medium text-gray-700 mb-1">ยังไม่มีข้อมูล</h4>
                                    <a href="{{ route('certificates.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-800 mt-4">
                                        <i data-lucide="plus" class="w-4 h-4"></i> เพิ่มหนังสือรับรอง
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($certificates->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">{{ $certificates->links() }}</div>
        @endif
    </div>
</x-app-layout>
