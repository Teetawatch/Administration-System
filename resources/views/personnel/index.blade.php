<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">รายชื่อข้าราชการ</h1></x-slot>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div><h2 class="text-2xl font-bold text-gray-800">รายชื่อบุคลากร</h2><p class="text-gray-500 mt-1">จัดการข้อมูลบุคลากรทั้งหมด</p></div>
        <div class="flex items-center gap-2">
            <!-- View Mode Toggles -->
            <div class="bg-gray-100 p-1 rounded-lg flex items-center mr-2">
                <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'all']) }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $viewMode == 'all' ? 'bg-white text-navy-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                   เรียงรายชื่อทั้งหมด
                </a>
                <a href="{{ request()->fullUrlWithQuery(['view_mode' => 'department']) }}" 
                   class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors {{ $viewMode == 'department' ? 'bg-white text-navy-700 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                   เรียงตามแผนก
                </a>
            </div>

            <!-- Import Button Code -->
            <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
                <i data-lucide="upload" class="w-5 h-5"></i><span>นำเข้า Excel</span>
            </button>
            <a href="{{ route('personnel.pdf.export') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">
                <i data-lucide="file-text" class="w-5 h-5"></i><span>Export PDF</span>
            </a>
            <a href="{{ route('personnel.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium"><i data-lucide="plus" class="w-5 h-5"></i><span>เพิ่มบุคลากร</span></a>
        </div>
    </div>
    
    <!-- Import Modal -->
    <div id="importModal" class="fixed inset-0 z-50 hidden bg-gray-900/50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-md w-full p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">นำเข้าข้อมูลจาก Excel</h3>
            <form action="{{ route('personnel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-medium text-gray-700">เลือกไฟล์ Excel (.xlsx, .xls, .csv)</label>
                        <a href="{{ route('personnel.template') }}" class="text-sm text-navy-600 hover:underline flex items-center gap-1">
                            <i data-lucide="download" class="w-4 h-4"></i> ดาวน์โหลดแบบฟอร์ม
                        </a>
                    </div>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-2">รูปแบบหัวตาราง: employee_id, rank, first_name, last_name, position, department, phone, email</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">ยกเลิก</button>
                    <button type="submit" class="px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-800">นำเข้า</button>
                </div>
            </form>
        </div>
    </div>

    @if (session('success'))<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i><span class="text-emerald-700">{{ session('success') }}</span></div>@endif
    <div class="card mb-6"><div class="card-body"><form action="{{ route('personnel.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4"><div class="relative flex-1"><i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i><input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อ, นามสกุล, รหัส..." class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg"></div><select name="department" class="px-4 py-2.5 border border-gray-300 rounded-lg"><option value="">ทุกหน่วยงาน</option>@foreach ($departments as $dept)<option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>@endforeach</select><button type="submit" class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">ค้นหา</button></form></div></div>
    <div class="space-y-8">
        @forelse ($personnelByDepartment as $department => $personnelGroup)
            <div class="card overflow-hidden">
                <div class="card-header bg-navy-50 border-b border-navy-100">
                    <h3 class="text-lg font-bold text-navy-700 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                        {{ $department }}
                        <span class="text-xs font-normal text-gray-500 bg-white px-2 py-0.5 rounded-full border border-gray-200 ml-2">
                             {{ $personnelGroup->count() }} คน
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="w-10"></th> <!-- Handle -->
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase w-16">ลำดับ</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อ-นามสกุล</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ตำแหน่ง</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">เบอร์โทรศัพท์</th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 sortable-list" data-department="{{ $department }}">
                            @foreach ($personnelGroup as $index => $person)
                            <tr class="hover:bg-gray-50 bg-white" data-id="{{ $person->id }}">
                                <td class="pl-4 cursor-move text-gray-400 hover:text-navy-600">
                                    <i data-lucide="grip-vertical" class="w-5 h-5"></i>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-600 index-cell">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-gray-800">{{ $person->rank }} {{ $person->first_name }} {{ $person->last_name }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ Str::limit($person->position, 30) ?: '-' }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $person->phone ?: '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <a href="{{ route('personnel.edit', $person) }}" class="p-2 text-gray-500 hover:text-amber-600 hover:bg-gray-100 rounded-lg"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                        <form action="{{ route('personnel.destroy', $person) }}" method="POST" class="inline" onsubmit="return confirm('ต้องการลบ?')">@csrf @method('DELETE')<button class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg"><i data-lucide="trash-2" class="w-4 h-4"></i></button></form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <div class="card p-12 text-center">
                 <div class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"><i data-lucide="users" class="w-8 h-8 text-gray-400"></i></div>
                    <h4 class="text-gray-700 font-medium text-lg">ไม่พบข้อมูลบุคลากร</h4>
                    <p class="text-gray-500 mt-2">ยังไม่มีข้อมูลในระบบ หรือไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- SortableJS -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const draggables = document.querySelectorAll('.sortable-list');
            
            draggables.forEach(draggable => {
                new Sortable(draggable, {
                    animation: 150,
                    handle: '.cursor-move',
                    ghostClass: 'bg-blue-50',
                    onEnd: function (evt) {
                        // Update Index UI
                        const rows = evt.to.querySelectorAll('tr');
                        rows.forEach((row, index) => {
                            row.querySelector('.index-cell').textContent = index + 1;
                        });

                        // Get IDs order
                        const itemIds = Array.from(rows).map(row => row.getAttribute('data-id'));
                        
                        // Send reorder request
                        fetch('{{ route("personnel.reorder") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ ids: itemIds })
                        }).then(response => {
                            if (!response.ok) {
                                alert('เกิดข้อผิดพลาดในการบันทึกการเรียงลำดับ');
                            }
                        });
                    }
                });
            });
        });
    </script>
</x-app-layout>
