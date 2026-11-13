@extends('layouts.admin')
@section('title', 'Kelola Kategori Menu — Nucomu Cafe')

@section('content')
<div class="space-y-8 max-w-5xl mx-auto">
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pb-4 border-b border-hairline anim-fade-up">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-warm-stone border border-hairline text-[11px] font-semibold text-charcoal mb-2">
                <i class="fa-solid fa-tags text-[10px]"></i>
                <span>Modul 4 &bull; Kategori &amp; Menu Nucomu</span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-ink-black tracking-tight font-sans">
                Kelola Kategori Menu
            </h1>
            <p class="text-xs text-muted-text mt-1">
                Atur kelompok kategori sajian kopi, minuman, dessert, dan makanan ringan. Kategori aktif langsung muncul sebagai tab filter di katalog web.
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
            <button type="button" onclick="openAddCategoryModal()"
                    class="px-4 py-2.5 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs flex items-center gap-2 btn-press">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Tambah Kategori</span>
            </button>

            <a href="{{ route('admin.menus.index') }}"
               class="px-3.5 py-2.5 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-xs font-bold text-ink-black transition shadow-xs flex items-center gap-2 btn-press"
               title="Kembali ke Katalog Menu">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                <span>Katalog Menu</span>
            </a>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-xs shadow-xs flex items-center justify-between anim-fade-up">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-circle-check text-emerald-600 text-sm"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-xs flex items-center justify-between anim-fade-up">
            <div class="flex items-center gap-2.5">
                <i class="fa-solid fa-triangle-exclamation text-rose-600 text-sm"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-900 text-xs shadow-xs space-y-1 anim-fade-up">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-xmark text-rose-600"></i>
                <span>Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Table of Categories --}}
    <div class="bg-pure-white rounded-3xl border border-hairline overflow-hidden shadow-card">
        <div class="p-5 sm:p-6 border-b border-hairline flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-ink-black">Daftar Kategori Sajian</h3>
                <p class="text-[11px] text-muted-text">Semua kategori yang terdaftar di sistem kafe ({{ $categories->count() }} kategori)</p>
            </div>
            <span class="text-xs font-mono text-muted-text font-bold">Total: {{ $categories->count() }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-warm-stone/50 border-b border-hairline text-[10.5px] font-mono uppercase tracking-wider text-muted-text">
                    <tr>
                        <th class="py-3.5 px-4 font-bold text-center w-16">Urutan</th>
                        <th class="py-3.5 px-4 font-bold">Nama Kategori</th>
                        <th class="py-3.5 px-4 font-bold">Slug URL</th>
                        <th class="py-3.5 px-4 font-bold text-center">Jumlah Menu</th>
                        <th class="py-3.5 px-4 font-bold text-center">Status Web</th>
                        <th class="py-3.5 px-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-hairline">
                    @forelse($categories as $category)
                        <tr class="hover:bg-warm-ivory/50 transition">
                            <td class="py-3 px-4 font-mono font-bold text-center text-charcoal">
                                #{{ $category->sort_order }}
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 rounded-full overflow-hidden border border-hairline bg-warm-stone shrink-0 flex items-center justify-center">
                                        @if($category->image_url)
                                            <img src="{{ $category->image_url }}"
                                                 alt="{{ $category->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <i class="fa-regular fa-image text-muted-text text-sm" aria-hidden="true"></i>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <span class="font-bold text-ink-black text-sm block">{{ $category->name }}</span>
                                        @if($category->description)
                                            <span class="text-[11px] text-muted-text line-clamp-1 mt-0.5 block">{{ $category->description }}</span>
                                        @endif
                                        @if(! $category->image_url)
                                            <span class="text-[10px] text-muted-text/80 italic block mt-0.5">Belum ada foto khusus</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 font-mono text-muted-text text-[11px]">
                                {{ $category->slug }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('admin.menus.index', ['category' => $category->id]) }}" 
                                   class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold font-mono transition {{ $category->menus_count > 0 ? 'bg-warm-stone text-ink-black hover:bg-ink-black hover:text-white' : 'bg-warm-ivory text-muted-text' }}"
                                   title="Lihat {{ $category->menus_count }} menu di kategori ini">
                                    <span>{{ $category->menus_count }} menu</span>
                                    <i class="fa-solid fa-arrow-right text-[9px]"></i>
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <form action="{{ route('admin.categories.toggle', $category) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-xl text-[10.5px] font-bold transition btn-press flex items-center gap-1.5 mx-auto {{ $category->is_active ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-warm-stone text-muted-text border border-hairline' }}"
                                            title="{{ $category->is_active ? 'Klik untuk Sembunyikan Kategori dari Web' : 'Klik untuk Aktifkan di Web' }}">
                                        <i class="fa-solid fa-power-off text-[9.5px]"></i>
                                        <span>{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                                    </button>
                                </form>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <div class="inline-flex items-center gap-1.5">
                                    {{-- Edit Button --}}
                                    <button type="button" 
                                            onclick='openEditCategoryModal(@json($category))'
                                            class="p-2 rounded-xl bg-pure-white hover:bg-warm-stone border border-hairline text-ink-black transition btn-press"
                                            title="Edit Kategori">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </button>

                                    {{-- Delete Button (Opsi 1: Tolak jika ada menu) --}}
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ addslashes($category->name) }}\'?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 rounded-xl bg-pure-white hover:bg-rose-50 border border-hairline text-rose-600 hover:text-rose-700 transition btn-press"
                                                title="{{ $category->menus_count > 0 ? 'Kategori memiliki ' . $category->menus_count . ' menu (penghapusan ditolak)' : 'Hapus Kategori' }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-muted-text">
                                Belum ada kategori yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div id="modalAddCategory" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-pure-white rounded-3xl border border-hairline shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 sm:p-7 space-y-5 anim-fade-up">
        <div class="flex items-center justify-between pb-3 border-b border-hairline">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-xs text-ink-black">
                    <i class="fa-solid fa-plus"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Tambah Kategori Baru</h3>
            </div>
            <button type="button" onclick="closeAddCategoryModal()" class="text-muted-text hover:text-ink-black p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-charcoal mb-1.5">
                    Foto Kategori (Opsional, 1:1)
                </label>
                <div class="flex items-center gap-4 p-3 rounded-2xl bg-warm-ivory border border-hairline">
                    <button type="button"
                            onclick="document.getElementById('add_cat_image').click()"
                            class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-dashed border-hairline bg-white hover:border-ink-black flex items-center justify-center shrink-0 transition cursor-pointer"
                            title="Pilih foto kategori">
                        <img id="add_cat_preview_img" src="" alt="Pratinjau foto kategori" class="hidden w-full h-full object-cover">
                        <span id="add_cat_preview_placeholder" class="text-muted-text flex flex-col items-center justify-center">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </span>
                    </button>
                    <div class="flex-1 min-w-0 space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button"
                                    onclick="document.getElementById('add_cat_image').click()"
                                    class="px-3 py-1.5 rounded-xl bg-white hover:bg-warm-stone border border-hairline text-[11px] font-bold text-ink-black transition cursor-pointer">
                                Pilih Foto
                            </button>
                            <button type="button"
                                    id="add_cat_remove_btn"
                                    onclick="clearCategoryPhotoPreview('add')"
                                    class="hidden px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                Hapus
                            </button>
                        </div>
                        <p class="text-[10.5px] text-muted-text leading-snug">
                            Format JPG, PNG, atau WebP (maks. {{ $categoryPhotoLimits['formatted'] ?? '2 MB' }}). Tampil di ikon kategori beranda.
                        </p>
                        <input type="file"
                               name="image"
                               id="add_cat_image"
                               accept="image/jpeg,image/png,image/webp"
                               class="sr-only"
                               data-max-bytes="{{ $categoryPhotoLimits['effective_bytes'] ?? 2097152 }}"
                               data-allowed-ext="jpg,jpeg,png,webp"
                               data-feedback-target="#addCatImageFeedback"
                               onchange="handleCategoryPhotoChange('add', event)">
                        <div id="addCatImageFeedback" class="hidden text-[11px]"></div>
                    </div>
                </div>
            </div>

            <div>
                <label for="add_cat_name" class="block text-xs font-bold text-charcoal mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="add_cat_name" required placeholder="Contoh: Mocktail, Pastry, Artisan Tea"
                       class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="add_cat_sort" class="block text-xs font-bold text-charcoal mb-1">
                        Urutan Tampil
                    </label>
                    <input type="number" name="sort_order" id="add_cat_sort" value="{{ $nextSortOrder }}" min="0"
                           class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black focus:bg-white transition">
                </div>

                <div class="flex items-end pb-1.5">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" value="1" checked class="rounded border-hairline text-ink-black focus:ring-0 w-4 h-4">
                        <span class="text-xs font-bold text-ink-black">Langsung Aktif</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="add_cat_desc" class="block text-xs font-bold text-charcoal mb-1">
                    Keterangan Singkat (Opsional)
                </label>
                <textarea name="description" id="add_cat_desc" rows="2" placeholder="Catatan singkat tentang kelompok sajian ini..."
                          class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2 text-xs text-ink-black resize-none focus:outline-none focus:border-ink-black focus:bg-white transition"></textarea>
            </div>

            <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeAddCategoryModal()" class="px-4 py-2 rounded-xl border border-hairline text-xs font-bold text-muted-text hover:text-ink-black hover:bg-warm-stone transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                    Simpan Kategori
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL EDIT KATEGORI --}}
<div id="modalEditCategory" class="hidden fixed inset-0 z-50 bg-black/40 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-pure-white rounded-3xl border border-hairline shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6 sm:p-7 space-y-5 anim-fade-up">
        <div class="flex items-center justify-between pb-3 border-b border-hairline">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-warm-stone border border-hairline flex items-center justify-center text-xs text-ink-black">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <h3 class="text-sm font-bold text-ink-black">Edit Kategori</h3>
            </div>
            <button type="button" onclick="closeEditCategoryModal()" class="text-muted-text hover:text-ink-black p-1">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        </div>

        <form id="editCategoryForm" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="remove_image" id="edit_cat_remove_image" value="0">

            <div>
                <label class="block text-xs font-bold text-charcoal mb-1.5">
                    Foto Kategori (Opsional, 1:1)
                </label>
                <div class="flex items-center gap-4 p-3 rounded-2xl bg-warm-ivory border border-hairline">
                    <button type="button"
                            onclick="document.getElementById('edit_cat_image').click()"
                            class="relative w-16 h-16 rounded-full overflow-hidden border-2 border-dashed border-hairline bg-white hover:border-ink-black flex items-center justify-center shrink-0 transition cursor-pointer"
                            title="Pilih atau ganti foto kategori">
                        <img id="edit_cat_preview_img" src="" alt="Pratinjau foto kategori" class="hidden w-full h-full object-cover">
                        <span id="edit_cat_preview_placeholder" class="text-muted-text flex flex-col items-center justify-center">
                            <i class="fa-solid fa-camera text-sm"></i>
                        </span>
                    </button>
                    <div class="flex-1 min-w-0 space-y-1.5">
                        <div class="flex flex-wrap items-center gap-2">
                            <button type="button"
                                    onclick="document.getElementById('edit_cat_image').click()"
                                    class="px-3 py-1.5 rounded-xl bg-white hover:bg-warm-stone border border-hairline text-[11px] font-bold text-ink-black transition cursor-pointer">
                                Pilih / Ganti Foto
                            </button>
                            <button type="button"
                                    id="edit_cat_remove_btn"
                                    onclick="clearCategoryPhotoPreview('edit')"
                                    class="hidden px-2.5 py-1.5 rounded-xl text-[11px] font-bold text-rose-600 hover:bg-rose-50 transition cursor-pointer">
                                Hapus Foto
                            </button>
                        </div>
                        <p class="text-[10.5px] text-muted-text leading-snug">
                            Format JPG, PNG, atau WebP (maks. {{ $categoryPhotoLimits['formatted'] ?? '2 MB' }}). Tampil di ikon kategori beranda.
                        </p>
                        <input type="file"
                               name="image"
                               id="edit_cat_image"
                               accept="image/jpeg,image/png,image/webp"
                               class="sr-only"
                               data-max-bytes="{{ $categoryPhotoLimits['effective_bytes'] ?? 2097152 }}"
                               data-allowed-ext="jpg,jpeg,png,webp"
                               data-feedback-target="#editCatImageFeedback"
                               onchange="handleCategoryPhotoChange('edit', event)">
                        <div id="editCatImageFeedback" class="hidden text-[11px]"></div>
                    </div>
                </div>
            </div>

            <div>
                <label for="edit_cat_name" class="block text-xs font-bold text-charcoal mb-1">
                    Nama Kategori <span class="text-rose-500">*</span>
                </label>
                <input type="text" name="name" id="edit_cat_name" required
                       class="w-full bg-warm-ivory border border-hairline rounded-xl px-4 py-2.5 text-xs text-ink-black font-semibold focus:outline-none focus:border-ink-black focus:bg-white transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label for="edit_cat_sort" class="block text-xs font-bold text-charcoal mb-1">
                        Urutan Tampil
                    </label>
                    <input type="number" name="sort_order" id="edit_cat_sort" min="0"
                           class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2 text-xs text-ink-black font-mono focus:outline-none focus:border-ink-black focus:bg-white transition">
                </div>

                <div class="flex items-end pb-1.5">
                    <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" name="is_active" id="edit_cat_active" value="1" class="rounded border-hairline text-ink-black focus:ring-0 w-4 h-4">
                        <span class="text-xs font-bold text-ink-black">Aktif di Web</span>
                    </label>
                </div>
            </div>

            <div>
                <label for="edit_cat_desc" class="block text-xs font-bold text-charcoal mb-1">
                    Keterangan Singkat (Opsional)
                </label>
                <textarea name="description" id="edit_cat_desc" rows="2"
                          class="w-full bg-warm-ivory border border-hairline rounded-xl px-3.5 py-2 text-xs text-ink-black resize-none focus:outline-none focus:border-ink-black focus:bg-white transition"></textarea>
            </div>

            <div class="pt-3 border-t border-hairline flex items-center justify-end gap-2.5">
                <button type="button" onclick="closeEditCategoryModal()" class="px-4 py-2 rounded-xl border border-hairline text-xs font-bold text-muted-text hover:text-ink-black hover:bg-warm-stone transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-ink-black hover:bg-charcoal text-white font-bold text-xs uppercase tracking-wider transition shadow-xs btn-press">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function getCategoryPhotoEls(mode) {
        return {
            input: document.getElementById(mode + '_cat_image'),
            img: document.getElementById(mode + '_cat_preview_img'),
            placeholder: document.getElementById(mode + '_cat_preview_placeholder'),
            removeBtn: document.getElementById(mode + '_cat_remove_btn'),
            removeFlag: document.getElementById('edit_cat_remove_image'),
            feedback: document.getElementById(mode === 'add' ? 'addCatImageFeedback' : 'editCatImageFeedback')
        };
    }

    function setCategoryPhotoPreview(mode, imageUrl) {
        const els = getCategoryPhotoEls(mode);
        if (!els.img || !els.placeholder || !els.removeBtn) return;

        if (imageUrl) {
            els.img.src = imageUrl;
            els.img.classList.remove('hidden');
            els.placeholder.classList.add('hidden');
            els.removeBtn.classList.remove('hidden');
        } else {
            els.img.src = '';
            els.img.classList.add('hidden');
            els.placeholder.classList.remove('hidden');
            els.removeBtn.classList.add('hidden');
        }
    }

    function handleCategoryPhotoChange(mode, event) {
        const els = getCategoryPhotoEls(mode);
        const file = event.target.files && event.target.files[0];
        if (!file) return;

        const maxBytes = parseInt(event.target.getAttribute('data-max-bytes') || '2097152', 10);
        if (file.size > maxBytes) {
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(evt) {
            setCategoryPhotoPreview(mode, evt.target.result);
            if (mode === 'edit' && els.removeFlag) {
                els.removeFlag.value = '0';
            }
        };
        reader.readAsDataURL(file);
    }

    function clearCategoryPhotoPreview(mode) {
        const els = getCategoryPhotoEls(mode);
        if (els.input) els.input.value = '';
        if (els.feedback) {
            els.feedback.innerHTML = '';
            els.feedback.classList.add('hidden');
        }
        setCategoryPhotoPreview(mode, null);
        if (mode === 'edit' && els.removeFlag) {
            els.removeFlag.value = '1';
        }
    }

    function openAddCategoryModal() {
        const els = getCategoryPhotoEls('add');
        if (els.input) els.input.value = '';
        if (els.feedback) {
            els.feedback.innerHTML = '';
            els.feedback.classList.add('hidden');
        }
        setCategoryPhotoPreview('add', null);
        document.getElementById('modalAddCategory').classList.remove('hidden');
        document.getElementById('add_cat_name').focus();
    }
    function closeAddCategoryModal() {
        document.getElementById('modalAddCategory').classList.add('hidden');
    }

    function openEditCategoryModal(category) {
        const form = document.getElementById('editCategoryForm');
        form.action = '/admin/kategori/' + category.id;
        document.getElementById('edit_cat_name').value = category.name || '';
        document.getElementById('edit_cat_sort').value = category.sort_order || 0;
        document.getElementById('edit_cat_active').checked = !!category.is_active;
        document.getElementById('edit_cat_desc').value = category.description || '';

        const els = getCategoryPhotoEls('edit');
        if (els.input) els.input.value = '';
        if (els.removeFlag) els.removeFlag.value = '0';
        if (els.feedback) {
            els.feedback.innerHTML = '';
            els.feedback.classList.add('hidden');
        }
        setCategoryPhotoPreview('edit', category.image_url || null);

        document.getElementById('modalEditCategory').classList.remove('hidden');
        document.getElementById('edit_cat_name').focus();
    }
    function closeEditCategoryModal() {
        document.getElementById('modalEditCategory').classList.add('hidden');
    }
</script>
@endsection
