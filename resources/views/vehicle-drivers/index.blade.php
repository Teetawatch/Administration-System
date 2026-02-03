<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">จัดการพนักงานขับรถ</h1></x-slot>
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div><h2 class="text-2xl font-bold text-gray-800">รายชื่อพนักงานขับรถ</h2><p class="text-gray-500 mt-1">จัดการข้อมูลคนขับรถสำหรับระบบจอง</p></div>
        <a href="{{ route('vehicle-drivers.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium"><i data-lucide="plus" class="w-5 h-5"></i><span>เพิ่มคนขับ</span></a>
    </div>

    @if (session('success'))<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i><span class="text-emerald-700">{{ session('success') }}</span></div>@endif

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อ-สกุล</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">เบอร์โทรศัพท์</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($drivers as $driver)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-gray-800 font-medium">{{ $driver->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $driver->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($driver->is_active)
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">ใช้งาน</span>
                            @else
                                <span class="inline-flex px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">ระงับ</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('vehicle-drivers.edit', $driver) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                <form action="{{ route('vehicle-drivers.destroy', $driver) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการลบข้อมูล?')">
                                    @csrf @method('DELETE')
                                    <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"><i data-lucide="users" class="w-8 h-8 text-gray-400"></i></div>
                                <h4 class="text-gray-700 font-medium">ยังไม่มีข้อมูลคนขับ</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($drivers->hasPages())<div class="px-6 py-4 border-t border-gray-200">{{ $drivers->links() }}</div>@endif
    </div>
</x-app-layout>
