<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">รายชื่อข้าราชการ</h1>
    </x-slot>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">รายชื่อบุคลากร</h2>
            <p class="text-gray-500 mt-1">จัดการข้อมูลบุคลากรทั้งหมด</p>
        </div>
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
            <button onclick="document.getElementById('importModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-medium">
                <i data-lucide="upload" class="w-5 h-5"></i><span>นำเข้า Excel</span>
            </button>
            <a href="{{ route('personnel.pdf.export', request()->query()) }}" target="_blank"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-transform active:scale-95 shadow-sm">
                <i data-lucide="file-text" class="w-5 h-5"></i>
                <span>PDF ({{ $viewMode == 'all' ? 'เรียงทั้งหมด' : 'แยกแผนก' }})</span>
            </a>
            <a href="{{ route('personnel.create') }}"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium"><i
                    data-lucide="plus" class="w-5 h-5"></i><span>เพิ่มบุคลากร</span></a>
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
                        <label class="block text-sm font-medium text-gray-700">เลือกไฟล์ Excel (.xlsx, .xls,
                            .csv)</label>
                        <a href="{{ route('personnel.template') }}"
                            class="text-sm text-navy-600 hover:underline flex items-center gap-1">
                            <i data-lucide="download" class="w-4 h-4"></i> ดาวน์โหลดแบบฟอร์ม
                        </a>
                    </div>
                    <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg">
                    <p class="text-xs text-gray-500 mt-2">รูปแบบหัวตาราง: employee_id, rank, first_name, last_name,
                        position, department, phone, email</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">ยกเลิก</button>
                    <button type="submit"
                        class="px-4 py-2 bg-navy-700 text-white rounded-lg hover:bg-navy-800">นำเข้า</button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .sortable-chosen {
            background-color: #f8fafc !important;
        }

        .sortable-ghost {
            opacity: 0.4;
            background-color: #e2e8f0 !important;
            border: 2px dashed #94a3b8 !important;
        }

        .sortable-selected {
            background-color: #f0fdf4 !important;
            outline: 2px solid #22c55e !important;
        }

        .index-input::-webkit-outer-spin-button,
        .index-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .index-input {
            -moz-appearance: textfield;
        }
    </style>

    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3"><i
                data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i><span
    class="text-emerald-700">{{ session('success') }}</span></div>@endif

    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <form action="{{ route('personnel.index') }}" method="GET"
                    class="flex-1 flex flex-col sm:flex-row gap-4">
                    <div class="relative flex-1">
                        <i data-lucide="search"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="ค้นหาชื่อ, นามสกุล, รหัส..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                    </div>
                    <select name="department"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">
                        <option value="">ทุกหน่วยงาน</option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition-colors">ค้นหาเบื้องต้น</button>
                </form>

                <div class="relative flex-1 max-w-xs">
                    <i data-lucide="filter" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                    <input type="text" id="quickFilter" placeholder="ไฮไลท์รายชื่อในหน้านี้..."
                        class="w-full pl-10 pr-4 py-2.5 border border-amber-200 bg-amber-50/30 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                        title="ช่วยค้นหาเพื่อลากย้ายได้ง่ายขึ้น">
                </div>
            </div>
            <p class="text-xs text-gray-400 mt-2">
                <i data-lucide="info" class="w-3 h-3 inline mr-1"></i> เทคนิค: กด <strong>Ctrl + คลิก</strong>
                เพื่อเลือกหลายคนพร้อมกันแล้วลากย้าย หรือพิมพ์ลำดับที่ต้องการในช่องตัวเลข
            </p>
        </div>
    </div>

    <div class="space-y-8">
        @forelse ($personnelByDepartment as $department => $personnelGroup)
            <div class="card overflow-hidden transition-shadow hover:shadow-md">
                <div class="card-header bg-navy-50/50 border-b border-navy-100 flex items-center justify-between py-3">
                    <h3 class="text-lg font-bold text-navy-700 flex items-center gap-2">
                        <i data-lucide="building-2" class="w-5 h-5 text-navy-500"></i>
                        {{ $department }}
                        <span
                            class="text-xs font-normal text-gray-500 bg-white px-2.5 py-0.5 rounded-full border border-gray-200 ml-2">
                            {{ $personnelGroup->count() }} คน
                        </span>
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50/80 border-b border-gray-200">
                            <tr>
                                <th class="w-16"></th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase w-24">ลำดับ
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ชื่อ-นามสกุล
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ตำแหน่ง</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">เบอร์โทรศัพท์
                                </th>
                                <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 sortable-list" data-department="{{ $department }}">
                            @foreach ($personnelGroup as $index => $person)
                                <tr class="hover:bg-gray-50 bg-white transition-colors" data-id="{{ $person->id }}">
                                    <td class="pl-4 py-4">
                                        <div class="flex items-center gap-1.5">
                                            <div class="cursor-move text-gray-300 hover:text-navy-600 p-1 transition-colors"
                                                title="ลากเพื่อเปลี่ยนลำดับ (Ctrl+คลิก เพื่อเลือกหลายคน)">
                                                <i data-lucide="grip-vertical" class="w-5 h-5"></i>
                                            </div>
                                            <div class="flex flex-col -space-y-1">
                                                <button type="button" onclick="quickMove(this, 'top')"
                                                    class="p-0.5 text-gray-300 hover:text-navy-600 transition-colors"
                                                    title="ย้ายไปบนสุด">
                                                    <i data-lucide="chevrons-up" class="w-4 h-4"></i>
                                                </button>
                                                <button type="button" onclick="quickMove(this, 'bottom')"
                                                    class="p-0.5 text-gray-300 hover:text-navy-600 transition-colors"
                                                    title="ย้ายไปล่างสุด">
                                                    <i data-lucide="chevrons-down" class="w-4 h-4"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" value="{{ $index + 1 }}"
                                            class="index-input w-14 px-2 py-1 text-sm font-medium text-gray-600 border border-transparent hover:border-gray-300 focus:border-navy-500 rounded bg-transparent focus:bg-white text-center transition-all"
                                            onchange="jumpToPosition(this, {{ $index + 1 }})"
                                            title="พิมพ์ตัวเลขลำดับที่ต้องการเพื่อย้ายทันที">
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-gray-800 font-medium person-name">{{ $person->rank }}
                                                {{ $person->first_name }} {{ $person->last_name }}</span>
                                            <span class="text-xs text-gray-400">{{ $person->employee_id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">{{ Str::limit($person->position, 30) ?: '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-600 text-sm">{{ $person->phone ?: '-' }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('personnel.edit', $person) }}"
                                                class="p-2 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                                title="แก้ไข"><i data-lucide="pencil" class="w-4 h-4"></i></a>
                                            <form action="{{ route('personnel.destroy', $person) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('ต้องการลบข้อมูลบุคลากรนี้ใช่หรือไม่?')">
                                                @csrf @method('DELETE')
                                                <button
                                                    class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="ลบ"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                                            </form>
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
                    <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4"><i
                            data-lucide="users" class="w-8 h-8 text-gray-300"></i></div>
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
                    animation: 200,
                    handle: '.cursor-move',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    multiDrag: true, // Enable multi-drag
                    selectedClass: 'sortable-selected',
                    fallbackTolerance: 3,
                    onEnd: function (evt) {
                        updateOrderAndSave(evt.to);
                    }
                });
            });

            // Quick Filter Logic (Client-side highlight)
            const quickFilter = document.getElementById('quickFilter');
            if (quickFilter) {
                quickFilter.addEventListener('input', function (e) {
                    const term = e.target.value.toLowerCase().trim();
                    const rows = document.querySelectorAll('.sortable-list tr');

                    rows.forEach(row => {
                        const text = row.querySelector('.person-name').textContent.toLowerCase();
                        if (term && text.includes(term)) {
                            row.classList.add('bg-amber-50');
                            row.style.opacity = '1';
                        } else if (term) {
                            row.classList.remove('bg-amber-50');
                            row.style.opacity = '0.4';
                        } else {
                            row.classList.remove('bg-amber-50');
                            row.style.opacity = '1';
                        }
                    });
                });
            }
        });

        // Update indexes and send to backend
        function updateOrderAndSave(tbody) {
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Update UI Indexes & Input Values
            rows.forEach((row, index) => {
                const input = row.querySelector('.index-input');
                if (input) {
                    input.value = index + 1;
                    // Also update the oldPos for future jumpToPosition calls
                    input.setAttribute('onchange', `jumpToPosition(this, ${index + 1})`);
                }
            });

            // Get IDs order
            const itemIds = rows.map(row => row.getAttribute('data-id'));

            // Visual feedback - show loading state if needed

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

        // Jump to a specific position by typing
        window.jumpToPosition = function (input, oldPos) {
            const newPos = parseInt(input.value);
            const row = input.closest('tr');
            const tbody = row.closest('tbody');
            const rows = Array.from(tbody.children);
            const total = rows.length;

            if (isNaN(newPos) || newPos < 1 || newPos > total || newPos === oldPos) {
                input.value = oldPos;
                return;
            }

            const targetIndex = newPos - 1;
            if (newPos === 1) {
                tbody.prepend(row);
            } else if (newPos === total) {
                tbody.appendChild(row);
            } else {
                // Determine insertion point
                if (newPos > oldPos) {
                    // Moving down
                    tbody.insertBefore(row, rows[targetIndex].nextSibling);
                } else {
                    // Moving up
                    tbody.insertBefore(row, rows[targetIndex]);
                }
            }

            updateOrderAndSave(tbody);

            // Brief highlight effect
            row.classList.add('bg-blue-50');
            setTimeout(() => row.classList.remove('bg-blue-50'), 1000);

            // Scroll into view if needed
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        };

        // Quick move to Top or Bottom
        window.quickMove = function (button, target) {
            const row = button.closest('tr');
            const tbody = row.closest('tbody');
            const input = row.querySelector('.index-input');

            if (target === 'top') {
                tbody.prepend(row);
            } else {
                tbody.appendChild(row);
            }

            updateOrderAndSave(tbody);
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Brief highlight
            row.classList.add('bg-blue-50');
            setTimeout(() => row.classList.remove('bg-blue-50'), 1000);
        };
    </script>
</x-app-layout>