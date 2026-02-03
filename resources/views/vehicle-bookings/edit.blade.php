<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">แก้ไขการจองยานพาหนะ</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6"><a href="{{ route('vehicle-bookings.index') }}" class="hover:text-navy-600">รายการจอง</a><i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">แก้ไข</span></div>
    
    <div class="card max-w-3xl">
        <div class="card-header"><h3 class="text-lg font-semibold text-gray-800">แก้ไขข้อมูลการจอง</h3></div>
        <div class="card-body">
            <form action="{{ route('vehicle-bookings.update', $vehicleBooking) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">เลือกรถที่ต้องการ <span class="text-red-500">*</span></label>
                        <select name="vehicle_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id', $vehicleBooking->vehicle_id) == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->name }} ({{ $vehicle->license_plate }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">วัน-เวลา เริ่มเดินทาง <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="start_time" value="{{ old('start_time', $vehicleBooking->start_time->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">วัน-เวลา สิ้นสุด <span class="text-red-500">*</span></label>
                        <input type="datetime-local" name="end_time" value="{{ old('end_time', $vehicleBooking->end_time->format('Y-m-d\TH:i')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">สถานที่ไปราชการ / จุดหมาย <span class="text-red-500">*</span></label>
                        <input type="text" name="destination" value="{{ old('destination', $vehicleBooking->destination) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">วัตถุประสงค์ <span class="text-red-500">*</span></label>
                        <textarea name="purpose" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg" required>{{ old('purpose', $vehicleBooking->purpose) }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">พนักงานขับรถ (แนะนำให้ระบุ)</label>
                        <select name="vehicle_driver_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg">
                            <option value="">-- ไม่ระบุ / ขับเอง --</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->id }}" {{ old('vehicle_driver_id', $vehicleBooking->vehicle_driver_id) == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} @if($driver->phone) ({{ $driver->phone }}) @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('vehicle-bookings.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">ยกเลิก</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
