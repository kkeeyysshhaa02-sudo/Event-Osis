<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
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
            'event_date' => ['required', 'date', 'after_or_equal:now'],
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
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'description.required' => 'Deskripsi event wajib diisi.',
            'event_date.required' => 'Tanggal & waktu event wajib diisi.',
            'event_date.after_or_equal' => 'Tanggal event tidak boleh di masa lalu.',
            'location.required' => 'Lokasi event wajib diisi.',
            'capacity.required' => 'Kapasitas/kuota peserta wajib diisi.',
            'capacity.min' => 'Kapasitas minimal 1 peserta.',
            'status.required' => 'Status event wajib dipilih.',
            'image.image' => 'File harus berupa gambar.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
