@extends('layouts.app')

@section('title', 'Kelola Kategori Event')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto" x-data="{ showModal: false, editMode: false, form: { id: '', name: '', description: '' } }">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola Kategori Event</h1>
            <p class="text-xs text-gray-600 mt-1">Atur pengelompokan event agar mudah dicari oleh peserta</p>
        </div>

        <button @click="editMode = false; form = { id: '', name: '', description: '' }; showModal = true"
            class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow transition">
            + Tambah Kategori
        </button>
    </div>

    <!-- Category Cards / Table Grid -->
    <div class="bg-white rounded-2xl shadow-sm border border-emerald-100 overflow-hidden">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-emerald-50/80 text-emerald-900 text-xs uppercase font-bold border-b border-emerald-100">
                <tr>
                    <th class="px-6 py-4">#</th>
                    <th class="px-6 py-4">Nama Kategori</th>
                    <th class="px-6 py-4">Deskripsi</th>
                    <th class="px-6 py-4 text-center">Jumlah Event</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-gray-700">
                @forelse($categories as $index => $cat)
                    <tr class="hover:bg-emerald-50/30 transition">
                        <td class="px-6 py-4 font-bold text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-bold text-gray-900">
                            {{ $cat->name }}
                            <span class="block text-[10px] text-gray-400 font-normal">Slug: {{ $cat->slug }}</span>
                        </td>
                        <td class="px-6 py-4 text-xs max-w-xs text-gray-600">
                            {{ $cat->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-full">
                                {{ $cat->events_count }} Event
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button @click="editMode = true; form = { id: '{{ $cat->id }}', name: '{{ addslashes($cat->name) }}', description: '{{ addslashes($cat->description) }}' }; showModal = true"
                                class="px-3 py-1 bg-amber-100 text-amber-800 hover:bg-amber-200 text-xs font-bold rounded-lg transition">
                                Edit
                            </button>
                            <form action="{{ route('categories.destroy', $cat) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-1 bg-red-100 text-red-800 hover:bg-red-200 text-xs font-bold rounded-lg transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-xs text-gray-500">Belum ada kategori event.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Modal Form (Create / Edit) -->
    <div x-show="showModal" x-transition class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 space-y-4" @click.outside="showModal = false">
            <div class="flex justify-between items-center border-b pb-3">
                <h3 class="font-bold text-gray-900 text-base" x-text="editMode ? 'Edit Kategori' : 'Tambah Kategori Baru'"></h3>
                <button @click="showModal = false" class="text-gray-400 hover:text-gray-600 font-bold">&times;</button>
            </div>

            <form :action="editMode ? '/categories/' + form.id : '/categories'" method="POST" class="space-y-4">
                @csrf
                <template x-if="editMode">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Kategori *</label>
                    <input type="text" name="name" x-model="form.name" required
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 text-sm">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Deskripsi (Opsional)</label>
                    <textarea name="description" x-model="form.description" rows="3"
                        class="w-full px-4 py-2 rounded-xl border border-gray-300 focus:ring-2 focus:ring-emerald-500 text-sm"></textarea>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold text-xs rounded-xl">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
