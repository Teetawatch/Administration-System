<x-app-layout>
    <x-slot name="header"><h1 class="text-xl font-semibold text-gray-800">รายการจองยานพาหนะ</h1></x-slot>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div><h2 class="text-2xl font-bold text-gray-800">ตารางการใช้รถ</h2><p class="text-gray-500 mt-1">จัดการและตรวจสอบสถานะการจองรถ</p></div>
        <a href="{{ route('vehicle-bookings.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium"><i data-lucide="plus" class="w-5 h-5"></i><span>จองรถ</span></a>
    </div>
    @if (session('success'))<div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3"><i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i><span class="text-emerald-700">{{ session('success') }}</span></div>@endif
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">วันที่ใช้รถ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">รถที่จอง</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">ผู้จอง / คนขับ</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase">จุดหมาย / ภารกิจ</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">สถานะ</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase">จัดการ</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-navy-700">{{ $booking->start_time->locale('th')->translatedFormat('d M Y H:i') }}</div>
                            <div class="text-xs text-gray-500">ถึง {{ $booking->end_time->locale('th')->translatedFormat('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-800">{{ $booking->vehicle->name }}</div>
                            <div class="text-xs text-gray-500">{{ $booking->vehicle->license_plate }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800"><span class="font-medium">ผู้จอง:</span> {{ $booking->user->name }}</div>
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">คนขับ:</span> 
                                @if($booking->driver)
                                    {{ $booking->driver->name }}
                                    @if($booking->driver->phone) <span class="text-xs text-gray-400">({{ $booking->driver->phone }})</span> @endif
                                @else
                                    ไม่ระบุ
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800 font-medium">{{ Str::limit($booking->destination, 30) }}</div>
                            <div class="text-xs text-gray-500">{{ Str::limit($booking->purpose, 40) }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-gray-100 text-gray-700',
                                    'approved' => 'bg-emerald-100 text-emerald-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    'cancelled' => 'bg-red-100 text-red-700'
                                ];
                                $statusLabels = [
                                    'pending' => 'รออนุมัติ',
                                    'approved' => 'อนุมัติแล้ว',
                                    'completed' => 'เสร็จสิ้น',
                                    'cancelled' => 'ยกเลิก'
                                ];
                            @endphp
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusColors[$booking->status] ?? '' }}">
                                {{ $statusLabels[$booking->status] ?? $booking->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('vehicle-bookings.show', $booking) }}" class="p-2 text-gray-500 hover:text-navy-600 hover:bg-gray-100 rounded-lg"><i data-lucide="eye" class="w-4 h-4"></i></a>
                                @if($booking->status == 'pending')
                                    <form action="{{ route('vehicle-bookings.update', $booking) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการอนุมัติ?')">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status_update" value="1">
                                        <input type="hidden" name="status" value="approved">
                                        <button class="p-2 text-gray-500 hover:text-emerald-600 hover:bg-gray-100 rounded-lg" title="อนุมัติ"><i data-lucide="check" class="w-4 h-4"></i></button>
                                    </form>
                                    <form action="{{ route('vehicle-bookings.update', $booking) }}" method="POST" class="inline" onsubmit="return confirm('ยืนยันการยกเลิก?')">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status_update" value="1">
                                        <input type="hidden" name="status" value="cancelled">
                                        <button class="p-2 text-gray-500 hover:text-red-600 hover:bg-gray-100 rounded-lg" title="ยกเลิก"><i data-lucide="x" class="w-4 h-4"></i></button>
                                    </form>
                                @elseif($booking->status == 'approved')
                                    <a href="{{ route('vehicle-bookings.show', $booking) }}" class="p-2 text-gray-500 hover:text-blue-600 hover:bg-gray-100 rounded-lg" title="บันทึกการใช้งาน"><i data-lucide="check-square" class="w-4 h-4"></i></a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"><i data-lucide="calendar-x" class="w-8 h-8 text-gray-400"></i></div>
                                <h4 class="text-gray-700 font-medium">ยังมีการจองยานพาหนะ</h4>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($bookings->hasPages())<div class="px-6 py-4 border-t border-gray-200">{{ $bookings->links() }}</div>@endif
    </div>
</x-app-layout>
