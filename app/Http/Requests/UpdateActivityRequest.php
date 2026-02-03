<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for updating an Activity.
 */
class UpdateActivityRequest extends FormRequest
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
            'activity_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,ongoing,completed,cancelled',
            'priority' => 'required|integer|min:1|max:5',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:personnel,id',
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
            'activity_name.required' => 'กรุณาระบุชื่อกิจกรรม',
            'start_date.required' => 'กรุณาระบุวันที่เริ่ม',
            'end_date.after_or_equal' => 'วันที่สิ้นสุดต้องมากกว่าหรือเท่ากับวันที่เริ่ม',
            'status.required' => 'กรุณาระบุสถานะ',
            'status.in' => 'สถานะไม่ถูกต้อง',
            'priority.required' => 'กรุณาระบุลำดับความสำคัญ',
            'priority.min' => 'ลำดับความสำคัญต้องอยู่ระหว่าง 1-5',
            'priority.max' => 'ลำดับความสำคัญต้องอยู่ระหว่าง 1-5',
        ];
    }
}
