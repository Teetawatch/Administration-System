<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">รายละเอียดการจองยานพาหนะ</h1></x-slot>
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6"><a href="{{ route('vehicle-bookings.index') }}" class="hover:text-navy-600">รายการจอง</a><i data-lucide="chevron-right" class="w-4 h-4"></i><span class="text-gray-800">รายละเอียด</span></div>
    
    <div class="card max-w-4xl">
        <div class="card-header flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">ข้อมูลการจอง</h3>
            <div class="flex gap-2">
                @if($vehicleBooking->status == 'pending')
                    <form action="{{ route('vehicle-bookings.update', $vehicleBooking) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการอนุมัติ?')">
                        @csrf @method('PUT')
                        <input type="hidden" name="status_update" value="1">
                        <input type="hidden" name="status" value="approved">
                        <button class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 text-sm font-medium">อนุมัติ</button>
                    </form>
                    <form action="{{ route('vehicle-bookings.update', $vehicleBooking) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการยกเลิก?')">
                        @csrf @method('PUT')
                        <input type="hidden" name="status_update" value="1">
                        <input type="hidden" name="status" value="cancelled">
                        <button class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 text-sm font-medium">ยกเลิก</button>
                    </form>
                @endif
                <a href="{{ route('vehicle-bookings.edit', $vehicleBooking) }}" class="px-4 py-2 bg-amber-100 text-amber-700 rounded-lg hover:bg-amber-200 text-sm font-medium">แก้ไข</a>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">รถที่จอง</dt>
                    <dd class="text-lg font-semibold text-navy-700">{{ $vehicleBooking->vehicle->name }}</dd>
                    <dd class="text-sm text-gray-600">ทะเบียน: {{ $vehicleBooking->vehicle->license_plate }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">สถานะ</dt>
                    <dd>
                        @php
                            $statusColors = [
                                'pending' => 'bg-gray-100 text-gray-700',
                                'approved' => 'bg-emerald-100 text-emerald-700',
                                'completed' => 'bg-blue-100 text-blue-700',
                                'cancelled' => 'bg-red-100 text-red-700'
                            ];
                            $labels = [
                                'pending' => 'รออนุมัติ',
                                'approved' => 'อนุมัติแล้ว',
                                'completed' => 'เสร็จสิ้น',
                                'cancelled' => 'ยกเลิก'
                            ];
                        @endphp
                        <span class="px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$vehicleBooking->status] ?? '' }}">
                            {{ $labels[$vehicleBooking->status] ?? $vehicleBooking->status }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">เวลาเดินทาง</dt>
                    <dd class="text-gray-800">{{ $vehicleBooking->start_time->locale('th')->translatedFormat('d F Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ถึงเวลา</dt>
                    <dd class="text-gray-800">{{ $vehicleBooking->end_time->locale('th')->translatedFormat('d F Y H:i') }}</dd>
                </div>

                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">จุดหมาย / สถานที่ไปราชการ</dt>
                    <dd class="text-gray-800 bg-gray-50 p-3 rounded-lg">{{ $vehicleBooking->destination }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 mb-1">วัตถุประสงค์ / ภารกิจ</dt>
                    <dd class="text-gray-800 bg-gray-50 p-3 rounded-lg whitespace-pre-wrap">{{ $vehicleBooking->purpose }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">ผู้จอง</dt>
                    <dd class="text-gray-800">{{ $vehicleBooking->user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">พนักงานขับรถ</dt>
                    <dd class="text-gray-800">
                        @if($vehicleBooking->driver)
                            {{ $vehicleBooking->driver->name }}
                            @if($vehicleBooking->driver->phone)
                                <div class="text-sm text-gray-500">โทร: {{ $vehicleBooking->driver->phone }}</div>
                            @endif
                        @else
                            ไม่ระบุ / ขับเอง
                        @endif
                    </dd>
                </div>

                @if($vehicleBooking->status == 'completed' || $vehicleBooking->status == 'approved')
                <div class="md:col-span-2 mt-4 pt-4 border-t border-gray-200">
                    <h4 class="font-medium text-gray-800 mb-4">บันทึกการใช้งาน</h4>
                    @if($vehicleBooking->status == 'approved')
                    <form action="{{ route('vehicle-bookings.update', $vehicleBooking) }}" method="POST" class="bg-blue-50 p-4 rounded-lg">
                        @csrf @method('PUT')
                        <input type="hidden" name="status_update" value="1">
                        <input type="hidden" name="status" value="completed">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">เลขไมล์เริ่มต้น</label>
                                <input type="number" name="start_mileage" value="{{ old('start_mileage', $vehicleBooking->start_mileage) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">เลขไมล์สิ้นสุด</label>
                                <input type="number" name="end_mileage" value="{{ old('end_mileage', $vehicleBooking->end_mileage) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">ค่าน้ำมัน (บาท)</label>
                                <input type="number" step="0.01" name="fuel_cost" value="{{ old('fuel_cost', $vehicleBooking->fuel_cost) }}" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            </div>
                        </div>
                        <div class="mt-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm hover:bg-blue-700">บันทึกจบงาน</button>
                        </div>
                    </form>
                    @else
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div class="bg-gray-50 p-3 rounded">
                            <span class="text-gray-500 block text-xs">เลขไมล์เริ่มต้น</span>
                            <span class="font-medium">{{ $vehicleBooking->start_mileage ?: '-' }}</span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <span class="text-gray-500 block text-xs">เลขไมล์สิ้นสุด</span>
                            <span class="font-medium">{{ $vehicleBooking->end_mileage ?: '-' }}</span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <span class="text-gray-500 block text-xs">ระยะทางรวม</span>
                            <span class="font-medium">{{ ($vehicleBooking->end_mileage && $vehicleBooking->start_mileage) ? ($vehicleBooking->end_mileage - $vehicleBooking->start_mileage) . ' กม.' : '-' }}</span>
                        </div>
                        <div class="bg-gray-50 p-3 rounded">
                            <span class="text-gray-500 block text-xs">ค่าน้ำมัน</span>
                            <span class="font-medium">{{ $vehicleBooking->fuel_cost ? number_format($vehicleBooking->fuel_cost, 2) . ' บาท' : '-' }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </dl>
        </div>
    </div>
</x-app-layout>
