@extends('layouts.app')

@section('title', 'Edit Event - ' . $event->name)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div>
        <a href="{{ route('events.show', $event) }}" class="text-xs font-bold text-emerald-700 hover:underline">
            &larr; Batal & Kembali ke Detail
        </a>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight mt-2">Edit Event OSIS</h1>
        <p class="text-xs text-gray-600 mt-1">Perbarui informasi event {{ $event->name }}</p>
    </div>

    <div class="bg-white p-8 rounded-2xl shadow-sm border border-emerald-100">
        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-1">Nama Event *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $event->name) }}" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label for="category_id" class="block text-sm font-bold text-gray-700 mb-1">Kategori Event *</label>
                    <select name="category_id" id="category_id" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $event->category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-bold text-gray-700 mb-1">Status Event *</label>
                    <select name="status" id="status" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                        <option value="upcoming" {{ old('status', $event->status) == 'upcoming' ? 'selected' : '' }}>Akan Datang (Upcoming)</option>
                        <option value="ongoing" {{ old('status', $event->status) == 'ongoing' ? 'selected' : '' }}>Sedang Berlangsung (Ongoing)</option>
                        <option value="completed" {{ old('status', $event->status) == 'completed' ? 'selected' : '' }}>Selesai (Completed)</option>
                        <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Dibatalkan (Cancelled)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label for="event_date" class="block text-sm font-bold text-gray-700 mb-1">Tanggal & Waktu *</label>
                    <input type="datetime-local" name="event_date" id="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>

                <div>
                    <label for="location" class="block text-sm font-bold text-gray-700 mb-1">Lokasi Event *</label>
                    <input type="text" name="location" id="location" value="{{ old('location', $event->location) }}" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>

                <div>
                    <label for="capacity" class="block text-sm font-bold text-gray-700 mb-1">Kapasitas (Kuota) *</label>
                    <input type="number" name="capacity" id="capacity" value="{{ old('capacity', $event->capacity) }}" min="1" required
                        class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Event *</label>
                <textarea name="description" id="description" rows="5" required
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm">{{ old('description', $event->description) }}</textarea>
            </div>

            <div>
                <label for="image" class="block text-sm font-bold text-gray-700 mb-1">Ubah Gambar / Banner (Opsional)</label>
                @if($event->image)
                    <div class="mb-2 flex items-center space-x-3">
                        <img src="{{ asset('storage/' . $event->image) }}" class="w-16 h-12 object-cover rounded-lg border">
                        <span class="text-xs text-gray-500">Gambar saat ini</span>
                    </div>
                @endif
                <input type="file" name="image" id="image" accept="image/*"
                    class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-100 file:text-emerald-800">
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('events.show', $event) }}" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow">
                    Perbarui Event
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
