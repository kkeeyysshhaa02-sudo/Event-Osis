<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && in_array($this->user()->role, ['admin', 'panitia']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string'],
            'event_date' => ['required', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['required', 'in:upcoming,ongoing,completed,cancelled'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama event wajib diisi.',
            'category_id.required' => 'Kategori event wajib dipilih.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'event_date.required' => 'Tanggal event wajib diisi.',
            'location.required' => 'Lokasi event wajib diisi.',
            'capacity.required' => 'Kapasitas peserta wajib diisi.',
            'status.required' => 'Status event wajib dipilih.',
        ];
    }
}
