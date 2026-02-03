<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-gray-800">
            เพิ่มเลขหนังสือส่ง
        </h1>
    </x-slot>

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('outgoing-documents.index') }}" class="hover:text-navy-600 transition-colors">เลขหนังสือส่ง</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-800">เพิ่มใหม่</span>
    </div>

    <!-- Form Card -->
    <div class="card max-w-4xl">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-800">กรอกข้อมูลหนังสือส่ง</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('outgoing-documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- ประเภทหนังสือ -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ประเภทหนังสือ</label>
                        <div class="flex items-center gap-6">
                            <label class="inline-flex items-center">
                                <input type="radio" 
                                       name="is_secret" 
                                       value="0" 
                                       class="form-radio text-navy-600 focus:ring-navy-500" 
                                       checked
                                       onchange="toggleDocumentType(this.value)">
                                <span class="ml-2">ปกติ</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" 
                                       name="is_secret" 
                                       value="1" 
                                       class="form-radio text-red-600 focus:ring-red-500"
                                       onchange="toggleDocumentType(this.value)">
                                <span class="ml-2 text-red-600 font-medium">ลับ</span>
                            </label>
                        </div>
                    </div>
                    
                    <!-- ชั้นความเร็ว -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">ชั้นความเร็ว <span class="text-red-500">*</span></label>
                        <select name="urgency" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500">
                            <option value="normal" {{ old('urgency') == 'normal' ? 'selected' : '' }}>ปกติ</option>
                            <option value="urgent" class="text-red-600 font-bold" {{ old('urgency') == 'urgent' ? 'selected' : '' }}>ด่วน</option>
                            <option value="very_urgent" class="text-red-600 font-bold" {{ old('urgency') == 'very_urgent' ? 'selected' : '' }}>ด่วนมาก</option>
                            <option value="most_urgent" class="text-red-600 font-bold" {{ old('urgency') == 'most_urgent' ? 'selected' : '' }}>ด่วนที่สุด</option>
                        </select>
                    </div>

                    <!-- เลขที่หนังสือ -->
                    <div>
                        <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">
                            เลขที่หนังสือ <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="document_number" 
                               name="document_number" 
                               value="{{ old('document_number', $nextNormal) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed focus:ring-0 focus:border-gray-300"
                               readonly>
                    </div>

                    <!-- วันที่ -->
                    <div>
                        <label for="document_date" class="block text-sm font-medium text-gray-700 mb-2">
                            วันที่ <span class="text-red-500">*</span>
                        </label>
                        <input type="date" 
                               id="document_date" 
                               name="document_date" 
                               value="{{ old('document_date', date('Y-m-d')) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('document_date') border-red-500 @enderror"
                               required>
                        @error('document_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                                        <!-- จาก (เดิม หน่วยงาน) -->
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">
                            จาก <span class="text-red-500">*</span>
                        </label>
                        <select id="department" 
                                name="department" 
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('department') border-red-500 @enderror"
                                required>
                            <option value="">-- เลือกหน่วยงานต้นสังกัด --</option>
                            <option value="รร.พธ.พธ.ทร. (แผนกปกครองฯ โทร. ๕๒๓๓๙)" {{ old('department') == 'รร.พธ.พธ.ทร. (แผนกปกครองฯ โทร. ๕๒๓๓๙)' ? 'selected' : '' }}>รร.พธ.พธ.ทร. (แผนกปกครองฯ โทร. ๕๒๓๓๙)</option>
                            <option value="รร.พธ.พธ.ทร. (แผนกศึกษาฯ โทร. ๕๒๓๒๐)" {{ old('department') == 'รร.พธ.พธ.ทร. (แผนกศึกษาฯ โทร. ๕๒๓๒๐)' ? 'selected' : '' }}>รร.พธ.พธ.ทร. (แผนกศึกษาฯ โทร. ๕๒๓๒๐)</option>
                            <option value="รร.พธ.พธ.ทร. (แผนกสนับสนุนฯ โทร. ๕๒๓๒๖)" {{ old('department') == 'รร.พธ.พธ.ทร. (แผนกสนับสนุนฯ โทร. ๕๒๓๒๖)' ? 'selected' : '' }}>รร.พธ.พธ.ทร. (แผนกสนับสนุนฯ โทร. ๕๒๓๒๖)</option>
                            <option value="รร.พธ.พธ.ทร. (ฝกงฯ โทร. ๕๒๓๙๙)" {{ old('department') == 'รร.พธ.พธ.ทร. (ฝกงฯ โทร. ๕๒๓๙๙)' ? 'selected' : '' }}>รร.พธ.พธ.ทร. (ฝกงฯ โทร. ๕๒๓๙๙)</option>
                            <option value="รร.พธ.พธ.ทร. (ฝ่ายธุรการฯ โทร. ๕๒๓๒๖)" {{ old('department') == 'รร.พธ.พธ.ทร. (ฝ่ายธุรการฯ โทร. ๕๒๓๒๖)' ? 'selected' : '' }}>รร.พธ.พธ.ทร. (ฝ่ายธุรการฯ โทร. ๕๒๓๒๖)</option>
                        </select>
                        @error('department')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- ถึง -->
                    <div class="md:col-span-2">
                        <label for="to_recipient" class="block text-sm font-medium text-gray-700 mb-2">
                            ถึง <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="to_recipient" 
                               name="to_recipient" 
                               value="{{ old('to_recipient') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('to_recipient') border-red-500 @enderror"
                               placeholder="ระบุผู้รับหนังสือ"
                               required>
                        @error('to_recipient')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- เรื่อง -->
                    <div class="md:col-span-2">
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            เรื่อง <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('subject') border-red-500 @enderror"
                               placeholder="ระบุหัวเรื่อง"
                               required>
                        @error('subject')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>



                    <!-- ไฟล์แนบ -->
                    <div>
                        <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                            ไฟล์แนบ
                        </label>
                        <input type="file" 
                               id="attachment" 
                               name="attachment"
                               accept=".pdf,.doc,.docx,.jpg,.png"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-navy-50 file:text-navy-700 hover:file:bg-navy-100 @error('attachment') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">รองรับ: PDF, DOC, DOCX, JPG, PNG (ไม่เกิน 10MB)</p>
                        @error('attachment')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- รายละเอียด -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            รายละเอียดเพิ่มเติม
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-navy-500 focus:border-navy-500 @error('description') border-red-500 @enderror"
                                  placeholder="กรอกรายละเอียดเพิ่มเติม (ถ้ามี)">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('outgoing-documents.index') }}" 
                       class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        ยกเลิก
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-navy-700 text-white rounded-lg hover:bg-navy-800 transition-colors font-medium">
                        <i data-lucide="save" class="w-5 h-5"></i>
                        บันทึก
                    </button>
                </div>
            </form>
        </div>
    </div>
    </div>

    <script>
        function toggleDocumentType(value) {
            const docInput = document.getElementById('document_number');
            if (value === '1') { // Secret
                docInput.value = @json($nextSecret);
            } else { // Normal
                docInput.value = @json($nextNormal);
            }
        }
    </script>
</x-app-layout>
