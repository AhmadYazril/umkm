<?php

return [
    'accepted' => 'Isian :attribute harus diterima.',
    'dimensions' => 'Dimensi gambar :attribute tidak memenuhi ketentuan yang ditentukan.',
    'file' => 'Berkas :attribute harus berupa file yang valid.',
    'image' => 'Berkas :attribute harus berupa gambar yang valid (JPG, JPEG, PNG, atau WebP).',
    'max' => [
        'array' => 'Jumlah :attribute tidak boleh lebih dari :max item.',
        'file' => 'Ukuran :attribute terlalu besar (maksimal :max KB). Perkecil ukuran berkas.',
        'numeric' => 'Nilai :attribute tidak boleh lebih dari :max.',
        'string' => 'Panjang :attribute tidak boleh lebih dari :max karakter.',
    ],
    'mimes' => 'Format file :attribute tidak didukung. Harap gunakan format: :values.',
    'mimetypes' => 'Format file :attribute tidak didukung. Harap gunakan format: :values.',
    'required' => ':attribute wajib diisi atau diunggah.',
    'uploaded' => 'Berkas :attribute gagal diunggah karena ukurannya melebihi batas server PHP (maksimal :server_max). Perkecil ukuran file atau hubungi pengelola untuk menaikkan batas upload di php.ini.',

    'attributes' => [
        'media_file' => 'foto atau video banner',
        'image' => 'foto',
        'avatar' => 'foto profil',
        'cafe_logo' => 'logo kafe',
        'cafe_favicon' => 'ikon favicon',
        'photo_files' => 'foto fasilitas',
        'photo_files.*' => 'foto fasilitas',
        'review_image' => 'foto ulasan',
        'media_path' => 'berkas media',
    ],
];
