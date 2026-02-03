<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">เพิ่มกิจกรรม</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6"><a href="{{ route('activities.index') }}" class="hover:text-navy-600">จัดคิวกิจกรรม</a><i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">เพิ่มใหม่</span></div>
    <div class="card max-w-4xl"><div class="card-header"><h3 class="text-lg font-semibold text-gray-800">กรอกข้อมูลกิจกรรม</h3></div><div class="card-body"><form action="{{ route('activities.store') }}" method="POST">@csrf<div class="grid grid-cols-1 md:grid-cols-2 gap-6"><div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2">ชื่อกิจกรรม <span class="text-red-500">*</span></label><input type="text" name="activity_name" value="{{ old('activity_name') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required></div><div><label class="block text-sm font-medium text-gray-700 mb-2">วันที่เริ่ม <span class="text-red-500">*</span></label><input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required></div><div><label class="block text-sm font-medium text-gray-700 mb-2">วันที่สิ้นสุด</label><input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div><div><label class="block text-sm font-medium text-gray-700 mb-2">เวลาเริ่ม</label><input type="time" name="start_time" value="{{ old('start_time') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div><div><label class="block text-sm font-medium text-gray-700 mb-2">เวลาสิ้นสุด</label><input type="time" name="end_time" value="{{ old('end_time') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div><div><label class="block text-sm font-medium text-gray-700 mb-2">สถานที่</label><input type="text" name="location" value="{{ old('location') }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg"></div><div><label class="block text-sm font-medium text-gray-700 mb-2">สถานะ <span class="text-red-500">*</span></label><select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required><option value="pending">รอดำเนินการ</option><option value="ongoing">กำลังดำเนินการ</option><option value="completed">เสร็จสิ้น</option><option value="cancelled">ยกเลิก</option></select></div><div><label class="block text-sm font-medium text-gray-700 mb-2">ความสำคัญ (1-5) <span class="text-red-500">*</span></label><select name="priority" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required><option value="1">1 - ต่ำ</option><option value="2">2</option><option value="3" selected>3 - ปานกลาง</option><option value="4">4</option><option value="5">5 - สูง</option></select></div>
    
    <div class="md:col-span-2" x-data="participantSelector()">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            ผู้เข้าร่วมกิจกรรม
            <span class="ml-2 inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-navy-100 text-navy-700" x-text="selectedCount + ' คน'"></span>
        </label>
        
        {{-- Selected Participants Preview --}}
        <div class="mb-4" x-show="selectedCount > 0" x-cloak>
            <div class="flex flex-wrap gap-2 p-3 bg-navy-50 rounded-lg border border-navy-200">
                <template x-for="person in getSelectedPersonnel()" :key="person.id">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white rounded-full text-sm font-medium text-navy-700 border border-navy-200 shadow-sm">
                        <span x-text="person.name"></span>
                        <button type="button" @click="toggleSelection(person.id)" class="text-navy-400 hover:text-red-500 transition-colors">
                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                        </button>
                    </span>
                </template>
            </div>
        </div>
        
        <div class="border border-gray-300 rounded-lg overflow-hidden">
            {{-- Search & Actions Bar --}}
            <div class="p-3 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col gap-3">
                    <div class="flex flex-col sm:flex-row gap-3">
                        <div class="relative flex-1">
                            <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                            <input type="text" x-model="search" placeholder="ค้นหาชื่อบุคลากร..." 
                                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500">
                        </div>
                        <div class="flex gap-2">
                            <button type="button" @click="selectAll()" 
                                    class="px-3 py-2 text-xs font-medium text-navy-600 bg-navy-50 rounded-lg hover:bg-navy-100 transition-colors">
                                <i data-lucide="check-square" class="w-4 h-4 inline-block mr-1"></i>
                                เลือกทั้งหมด
                            </button>
                            <button type="button" @click="clearAll()" 
                                    class="px-3 py-2 text-xs font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                <i data-lucide="square" class="w-4 h-4 inline-block mr-1"></i>
                                ยกเลิกทั้งหมด
                            </button>
                        </div>
                    </div>
                    
                    {{-- Department Filter Tabs --}}
                    <div class="flex flex-wrap gap-2">
                        <button type="button" @click="filterDepartment = 'all'"
                                :class="filterDepartment === 'all' ? 'bg-navy-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                                class="px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 transition-colors">
                            <i data-lucide="users" class="w-3.5 h-3.5 inline-block mr-1"></i>
                            ทั้งหมด
                        </button>
                        @foreach($departments as $dept)
                        <button type="button" @click="filterDepartment = '{{ addslashes($dept) }}'"
                                :class="filterDepartment === '{{ addslashes($dept) }}' ? 'bg-navy-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                                class="px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 transition-colors">
                            {{ $dept }}
                            <span class="ml-1 opacity-75">({{ $personnelByDepartment[$dept]->count() }})</span>
                        </button>
                        @endforeach
                        @if($personnelByDepartment->has('') || $personnelByDepartment->has(null))
                        <button type="button" @click="filterDepartment = 'none'"
                                :class="filterDepartment === 'none' ? 'bg-navy-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100'"
                                class="px-3 py-1.5 text-xs font-medium rounded-full border border-gray-300 transition-colors">
                            ไม่ระบุแผนก
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Personnel Grid - Grouped by Department --}}
            <div class="max-h-96 overflow-y-auto bg-white">
                @foreach($personnelByDepartment as $department => $deptPersonnel)
                <div x-show="shouldShowDepartment('{{ addslashes($department ?: 'none') }}')" class="border-b border-gray-100 last:border-b-0">
                    {{-- Department Header --}}
                    <div class="sticky top-0 z-10 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i data-lucide="building-2" class="w-4 h-4 text-gray-500"></i>
                                {{ $department ?: 'ไม่ระบุแผนก' }}
                                <span class="text-xs font-normal text-gray-500">({{ $deptPersonnel->count() }} คน)</span>
                            </h4>
                            <button type="button" @click="selectDepartment('{{ addslashes($department ?: 'none') }}')"
                                    class="text-xs text-navy-600 hover:text-navy-800 font-medium">
                                เลือกทั้งแผนก
                            </button>
                        </div>
                    </div>
                    
                    {{-- Department Personnel Grid --}}
                    <div class="p-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($deptPersonnel as $person)
                            <div x-show="filterPerson('{{ addslashes($person->first_name) }} {{ addslashes($person->last_name) }}', '{{ addslashes($person->rank) }}')"
                                 @click="toggleSelection({{ $person->id }})"
                                 :class="isSelected({{ $person->id }}) ? 'ring-2 ring-navy-500 bg-navy-50 border-navy-300' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'"
                                 class="relative flex items-center gap-3 p-3 border rounded-xl cursor-pointer transition-all duration-200">
                                
                                {{-- Checkbox indicator --}}
                                <div :class="isSelected({{ $person->id }}) ? 'bg-navy-600 border-navy-600' : 'bg-white border-gray-300'"
                                     class="absolute top-2 right-2 w-5 h-5 rounded border-2 flex items-center justify-center transition-colors">
                                    <i data-lucide="check" class="w-3 h-3 text-white" x-show="isSelected({{ $person->id }})"></i>
                                </div>
                                
                                {{-- Photo --}}
                                <div class="flex-shrink-0">
                                    @if($person->photo_path)
                                        <img src="{{ asset('storage/' . $person->photo_path) }}" 
                                             alt="{{ $person->first_name }}"
                                             class="w-12 h-12 rounded-full object-cover border-2 border-white shadow">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-navy-100 to-navy-200 flex items-center justify-center">
                                            <span class="text-sm font-semibold text-navy-600">{{ mb_substr($person->first_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                
                                {{-- Info --}}
                                <div class="flex-1 min-w-0 pr-6">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $person->rank }} {{ $person->first_name }} {{ $person->last_name }}
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">{{ $person->position ?? '-' }}</p>
                                </div>
                                
                                {{-- Hidden checkbox for form submission --}}
                                <input type="checkbox" 
                                       name="participants[]" 
                                       value="{{ $person->id }}" 
                                       x-bind:checked="isSelected({{ $person->id }})"
                                       class="hidden"
                                       :id="'participant-{{ $person->id }}'">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
                
                {{-- Empty state --}}
                <div x-show="noResults" class="text-center py-8">
                    <i data-lucide="search-x" class="w-12 h-12 mx-auto text-gray-300 mb-3"></i>
                    <p class="text-gray-500">ไม่พบบุคลากรที่ค้นหา</p>
                </div>
            </div>
            
            {{-- Footer --}}
            <div class="p-3 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">
                        <i data-lucide="users" class="w-4 h-4 inline-block mr-1"></i>
                        ทั้งหมด {{ count($personnel) }} คน
                    </span>
                    <span class="text-navy-600 font-medium" x-show="selectedCount > 0">
                        เลือกแล้ว <span x-text="selectedCount"></span> คน
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function participantSelector() {
            return {
                search: '',
                selected: [],
                filterDepartment: 'all',
                personnel: [
                    @foreach($personnel as $person)
                    { id: {{ $person->id }}, name: '{{ addslashes($person->rank) }} {{ addslashes($person->first_name) }} {{ addslashes($person->last_name) }}', department: '{{ addslashes($person->department ?: 'none') }}' },
                    @endforeach
                ],
                
                get selectedCount() {
                    return this.selected.length;
                },
                
                get noResults() {
                    if (!this.search) return false;
                    const search = this.search.toLowerCase();
                    return !this.personnel.some(p => p.name.toLowerCase().includes(search));
                },
                
                isSelected(id) {
                    return this.selected.includes(id);
                },
                
                toggleSelection(id) {
                    if (this.isSelected(id)) {
                        this.selected = this.selected.filter(i => i !== id);
                    } else {
                        this.selected.push(id);
                    }
                    this.updateCheckboxes();
                },
                
                selectAll() {
                    const search = this.search.toLowerCase();
                    this.personnel.forEach(p => {
                        const matchesSearch = p.name.toLowerCase().includes(search);
                        const matchesDept = this.filterDepartment === 'all' || p.department === this.filterDepartment;
                        if (matchesSearch && matchesDept && !this.selected.includes(p.id)) {
                            this.selected.push(p.id);
                        }
                    });
                    this.updateCheckboxes();
                },
                
                clearAll() {
                    this.selected = [];
                    this.updateCheckboxes();
                },
                
                selectDepartment(dept) {
                    this.personnel.forEach(p => {
                        if (p.department === dept && !this.selected.includes(p.id)) {
                            this.selected.push(p.id);
                        }
                    });
                    this.updateCheckboxes();
                },
                
                shouldShowDepartment(dept) {
                    return this.filterDepartment === 'all' || this.filterDepartment === dept;
                },
                
                filterPerson(name, rank) {
                    const search = this.search.toLowerCase();
                    const fullName = (rank + ' ' + name).toLowerCase();
                    return fullName.includes(search);
                },
                
                getSelectedPersonnel() {
                    return this.personnel.filter(p => this.selected.includes(p.id));
                },
                
                updateCheckboxes() {
                    this.personnel.forEach(p => {
                        const checkbox = document.getElementById('participant-' + p.id);
                        if (checkbox) {
                            checkbox.checked = this.selected.includes(p.id);
                        }
                    });
                    // Refresh Lucide icons for newly rendered elements
                    if (typeof lucide !== 'undefined') {
                        lucide.createIcons();
                    }
                }
            }
        }
    </script>
    
    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 mb-2">รายละเอียด</label><textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">{{ old('description') }}</textarea></div></div><div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200"><a href="{{ route('activities.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">ยกเลิก</a><button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium"><i data-lucide="save" class="w-5 h-5"></i>บันทึก</button></div></form></div></div>
</x-app-layout>
