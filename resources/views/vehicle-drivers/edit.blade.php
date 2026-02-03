<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">แก้ไขพนักงานขับรถ</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6"><a href="{{ route('vehicle-drivers.index') }}" class="hover:text-navy-600">จัดการคนขับ</a><i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">แก้ไขข้อมูล</span></div>
    
    <div class="card max-w-2xl">
        <div class="card-header"><h3 class="text-lg font-semibold text-gray-800">ข้อมูลคนขับ</h3></div>
        <div class="card-body">
            <form action="{{ route('vehicle-drivers.update', $vehicleDriver) }}" method="POST">
                @csrf @method('PUT')
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชื่อ-นามสกุล <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $vehicleDriver->name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500/20 focus:border-navy-500" required>
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" value="{{ old('phone', $vehicleDriver->phone) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500/20 focus:border-navy-500">
                        @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ $vehicleDriver->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-navy-600 focus:ring-navy-500">
                        <label for="is_active" class="text-sm text-gray-700">เปิดใช้งาน (Active)</label>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('vehicle-drivers.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">ยกเลิก</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
