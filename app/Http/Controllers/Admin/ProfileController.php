<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\FileUploadHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ProfileController extends Controller
{
    /**
     * Display the admin profile management page.
     */
    public function index()
    {
        $user = auth()->user();

        return view('admin.profile.index', compact('user'));
    }

    /**
     * Update admin profile info (name, email, avatar).
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150|unique:users,email,'.$user->id,
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'Nama lengkap admin wajib diisi.',
            'name.max' => 'Nama admin maksimal 100 karakter.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format alamat email tidak valid.',
            'email.unique' => 'Alamat email ini sudah digunakan oleh akun lain.',
            'avatar.uploaded' => 'File foto profil avatar gagal diunggah ke server.',
            'avatar.image' => 'Berkas foto profil harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil harus JPG, JPEG, PNG, atau WebP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB (2048 KB).',
        ]);

        try {
            $user->name = trim($request->input('name'));
            $user->email = trim($request->input('email'));

            if ($request->hasFile('avatar')) {
                $uploadDir = public_path('uploads/avatars');
                FileUploadHelper::ensureDirectoryWritable($uploadDir, 'folder upload Foto Profil');

                $file = $request->file('avatar');

                // Delete old avatar if exists
                if ($user->avatar && file_exists(public_path('uploads/avatars/'.$user->avatar))) {
                    @unlink(public_path('uploads/avatars/'.$user->avatar));
                }

                $filename = 'avatar_'.$user->id.'_'.time().'.'.$file->getClientOriginalExtension();
                $file->move($uploadDir, $filename);
                $user->avatar = $filename;
            }

            $user->save();

            return back()->with('success', 'Profil admin berhasil diperbarui.');
        } catch (\Throwable $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui profil: '.$e->getMessage());
        }
    }

    /**
     * Update admin account password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password saat ini wajib diisi untuk keamanan.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal harus terdiri dari 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok. Harap periksa kembali.',
        ]);

        if (! Hash::check($request->input('current_password'), $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Password saat ini yang Anda masukkan salah.',
            ]);
        }

        $user->password = Hash::make($request->input('password'));
        $user->save();

        return back()->with('success', 'Password akun admin berhasil diubah.');
    }

    /**
     * Remove custom profile photo and reset to default initials avatar.
     */
    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar && file_exists(public_path('uploads/avatars/'.$user->avatar))) {
            @unlink(public_path('uploads/avatars/'.$user->avatar));
        }

        $user->avatar = null;
        $user->save();

        return back()->with('success', 'Foto profil berhasil dihapus dan kembali ke avatar standar.');
    }
}
