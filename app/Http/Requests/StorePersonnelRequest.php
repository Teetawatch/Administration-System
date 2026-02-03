<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for storing a new Personnel record.
 * 
 * Following software-architecture best practices by separating
 * validation logic from the controller.
 */
class StorePersonnelRequest extends FormRequest
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
        return [
            'employee_id' => 'required|string|unique:personnel,employee_id',
            'rank' => 'nullable|string|max:100',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive,retired',
            'photo' => 'nullable|image|max:2048',
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
            'employee_id.required' => 'กรุณาระบุรหัสบุคลากร',
            'employee_id.unique' => 'รหัสบุคลากรนี้มีอยู่ในระบบแล้ว',
            'first_name.required' => 'กรุณาระบุชื่อ',
            'last_name.required' => 'กรุณาระบุนามสกุล',
            'status.required' => 'กรุณาระบุสถานะ',
            'status.in' => 'สถานะต้องเป็น active, inactive หรือ retired',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'photo.image' => 'ไฟล์ต้องเป็นรูปภาพ',
            'photo.max' => 'ขนาดรูปภาพต้องไม่เกิน 2MB',
        ];
    }
}
