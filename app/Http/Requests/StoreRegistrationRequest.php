<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID wajib diisi.',
            'event_id.exists' => 'Event yang dipilih tidak ditemukan.',
        ];
    }
}
