<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for updating an Outgoing Document.
 * 
 * Following software-architecture best practices by separating
 * validation logic from the controller.
 */
class UpdateOutgoingDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $documentId = $this->route('outgoingDocument')?->id ?? $this->route('outgoing_document');

        return [
            'document_number' => "required|string|max:100|unique:outgoing_documents,document_number,{$documentId}",
            'document_date' => 'required|date',
            'to_recipient' => 'required|string|max:255',
            'subject' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'department' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'document_number.required' => 'กรุณาระบุเลขหนังสือ',
            'document_number.unique' => 'เลขหนังสือนี้มีอยู่ในระบบแล้ว',
            'document_date.required' => 'กรุณาระบุวันที่',
            'to_recipient.required' => 'กรุณาระบุผู้รับ',
            'subject.required' => 'กรุณาระบุเรื่อง',
            'urgency.required' => 'กรุณาระบุความเร่งด่วน',
            'urgency.in' => 'ความเร่งด่วนไม่ถูกต้อง',
            'attachment.mimes' => 'ไฟล์แนบต้องเป็น PDF, DOC, DOCX, JPG หรือ PNG',
            'attachment.max' => 'ขนาดไฟล์ต้องไม่เกิน 10MB',
        ];
    }
}
