<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">แก้ไขหนังสือรับรอง</h1>
    </x-slot>

    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('certificates.index') }}" class="hover:text-navy-600">หนังสือรับรอง</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800">แก้ไข</span>
    </div>

    <div class="card max-w-4xl">
        <div class="card-header"><h3 class="text-lg font-semibold text-gray-800">แก้ไขข้อมูลหนังสือรับรอง</h3></div>
        <div class="card-body">
            <form action="{{ route('certificates.update', $certificate) }}" method="POST">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="certificate_number" class="block text-sm font-medium text-gray-700 mb-2">เลขที่หนังสือรับรอง <span class="text-red-500">*</span></label>
                        <input type="text" id="certificate_number" name="certificate_number" value="{{ old('certificate_number', $certificate->certificate_number) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500" required>
                    </div>
                    <div>
                        <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">วันที่ออก <span class="text-red-500">*</span></label>
                        <input type="date" id="issue_date" name="issue_date" value="{{ old('issue_date', $certificate->issue_date?->format('Y-m-d')) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500" required>
                    </div>
                    <div>
                        <label for="personnel_name" class="block text-sm font-medium text-gray-700 mb-2">ชื่อบุคลากร <span class="text-red-500">*</span></label>
                        <input type="text" id="personnel_name" name="personnel_name" value="{{ old('personnel_name', $certificate->personnel_name) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500" required>
                    </div>
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">ตำแหน่ง</label>
                        <input type="text" id="position" name="position" value="{{ old('position', $certificate->position) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">
                    </div>
                    <div class="md:col-span-2">
                        <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">วัตถุประสงค์ <span class="text-red-500">*</span></label>
                        <input type="text" id="purpose" name="purpose" value="{{ old('purpose', $certificate->purpose) }}" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500" required>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">สถานะ <span class="text-red-500">*</span></label>
                        <select id="status" name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500" required>
                            <option value="draft" {{ old('status', $certificate->status) == 'draft' ? 'selected' : '' }}>ฉบับร่าง</option>
                            <option value="issued" {{ old('status', $certificate->status) == 'issued' ? 'selected' : '' }}>ออกแล้ว</option>
                            <option value="cancelled" {{ old('status', $certificate->status) == 'cancelled' ? 'selected' : '' }}>ยกเลิก</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">เนื้อหา</label>
                        <textarea id="content" name="content" rows="4" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">{{ old('content', $certificate->content) }}</textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('certificates.index') }}" class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">ยกเลิก</a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 font-medium">
                        <i data-lucide="save" class="w-5 h-5"></i> บันทึกการแก้ไข
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
