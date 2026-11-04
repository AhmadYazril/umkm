<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationField;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReservationController extends Controller
{
    /**
     * Display a listing of reservations and form builder manager.
     */
    public function index(Request $request): View
    {
        $q = trim((string) $request->input('q', ''));
        $status = trim((string) $request->input('status', 'all'));
        $dateFilter = trim((string) $request->input('date_filter', 'all'));

        $query = Reservation::query();

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('notes', 'like', "%{$q}%");
            });
        }

        if ($status !== 'all' && in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        if ($dateFilter === 'today') {
            $query->whereDate('date', today());
        } elseif ($dateFilter === 'upcoming') {
            $query->whereDate('date', '>=', today());
        } elseif ($dateFilter === 'past') {
            $query->whereDate('date', '<', today());
        }

        $reservations = $query->orderByDesc('date')
            ->orderByDesc('time')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        $totalCount = Reservation::count();
        $pendingCount = Reservation::where('status', 'pending')->count();
        $confirmedCount = Reservation::where('status', 'confirmed')->count();
        $completedCount = Reservation::where('status', 'completed')->count();
        $cancelledCount = Reservation::where('status', 'cancelled')->count();

        // Form builder fields
        $fields = ReservationField::orderBy('sort_order')->orderBy('id')->get();

        // Active WhatsApp destination number
        $destinationWhatsapp = Setting::get('reservation_whatsapp')
            ?: Setting::get('cafe_whatsapp', config('nucomu.contact.whatsapp_number', '081234567890'));

        return view('admin.reservations.index', compact(
            'reservations',
            'fields',
            'destinationWhatsapp',
            'totalCount',
            'pendingCount',
            'confirmedCount',
            'completedCount',
            'cancelledCount',
            'q',
            'status',
            'dateFilter'
        ));
    }

    /**
     * Update reservation status (pending / confirmed / completed / cancelled).
     */
    public function updateStatus(Request $request, Reservation $reservation): RedirectResponse|JsonResponse
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        $reservation->update(['status' => $request->status]);

        $label = $reservation->status_label;
        $message = "Status reservasi untuk {$reservation->name} berhasil diubah menjadi: {$label}.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'status' => $reservation->status,
                'status_label' => $label,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Delete reservation with confirmation and auto cleanup of attached uploaded files.
     */
    public function destroy(Reservation $reservation): RedirectResponse
    {
        $name = $reservation->name ?: 'Pelanggan';
        $reservation->deleteUploadedFiles();
        $reservation->delete();

        return back()->with('success', "Data reservasi dari {$name} berhasil dihapus.");
    }

    /**
     * Update admin target WhatsApp number with automatic normalization.
     */
    public function updateWhatsapp(Request $request): RedirectResponse
    {
        $request->validate([
            'whatsapp_number' => ['required', 'string', 'min:9', 'max:25'],
        ], [
            'whatsapp_number.required' => 'Nomor WhatsApp tujuan wajib diisi.',
            'whatsapp_number.min' => 'Nomor WhatsApp minimal 9 digit angka.',
        ]);

        $raw = trim((string) $request->input('whatsapp_number'));
        $clean = preg_replace('/[^0-9]/', '', $raw);

        if (str_starts_with($clean, '08')) {
            $clean = '628'.substr($clean, 2);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '628'.substr($clean, 1);
        }

        if (strlen($clean) < 10) {
            return back()->withErrors(['whatsapp_number' => 'Format nomor WhatsApp tidak valid (terlalu pendek).']);
        }

        Setting::set('reservation_whatsapp', $clean);
        Setting::set('cafe_whatsapp', $clean);

        return back()->with('success', "Nomor WhatsApp tujuan reservasi berhasil disimpan: {$clean} (Link wa.me otomatis aktif).");
    }

    /**
     * Add a new dynamic field to the Reservation Form Builder.
     */
    public function storeField(Request $request): RedirectResponse
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'type' => 'required|in:text,tel,date,time,number,textarea,photo,checkbox',
            'placeholder' => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'checkbox_options' => 'nullable|string',
        ], [
            'label.required' => 'Judul / Label kolom formulir tidak boleh kosong.',
            'label.max' => 'Judul kolom formulir maksimal 100 karakter.',
            'type.required' => 'Tipe kolom formulir wajib dipilih.',
        ]);

        $type = $request->input('type');
        $optionsArray = null;

        if ($type === 'checkbox') {
            $rawOptions = (string) $request->input('checkbox_options', '');
            $parsed = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $rawOptions)))));
            if (count($parsed) < 2) {
                return back()->withInput()->withErrors([
                    'checkbox_options' => 'Kolom checkbox pilihan wajib memiliki minimal 2 pilihan opsi (pisahkan satu opsi per baris).',
                ]);
            }
            $optionsArray = $parsed;
        }

        // Generate clean unique key
        $baseKey = Str::slug($request->input('label'), '_');
        if (empty($baseKey)) {
            $baseKey = 'field_'.time();
        }
        $fieldKey = $baseKey;
        $counter = 1;
        while (ReservationField::where('field_key', $fieldKey)->exists()) {
            $fieldKey = $baseKey.'_'.$counter++;
        }

        $sortOrder = $request->filled('sort_order')
            ? (int) $request->input('sort_order')
            : ((int) ReservationField::max('sort_order') + 1);

        ReservationField::create([
            'label' => trim((string) $request->input('label')),
            'field_key' => $fieldKey,
            'type' => $type,
            'placeholder' => $request->filled('placeholder') ? trim((string) $request->input('placeholder')) : null,
            'options' => $optionsArray,
            'is_required' => $request->boolean('is_required', false),
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);

        return back()->with('success', "Kolom formulir \"{$request->input('label')}\" berhasil ditambahkan.");
    }

    /**
     * Update an existing field in the Form Builder.
     */
    public function updateField(Request $request, ReservationField $field): RedirectResponse
    {
        $request->validate([
            'label' => 'required|string|max:100',
            'type' => 'required|in:text,tel,date,time,number,textarea,photo,checkbox',
            'placeholder' => 'nullable|string|max:255',
            'is_required' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'checkbox_options' => 'nullable|string',
        ], [
            'label.required' => 'Judul / Label kolom formulir tidak boleh kosong.',
            'type.required' => 'Tipe kolom formulir wajib dipilih.',
        ]);

        $type = $request->input('type');
        $optionsArray = null;

        if ($type === 'checkbox') {
            $rawOptions = (string) $request->input('checkbox_options', '');
            $parsed = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", '', $rawOptions)))));
            if (count($parsed) < 2) {
                return back()->withInput()->withErrors([
                    'checkbox_options' => 'Kolom checkbox pilihan wajib memiliki minimal 2 pilihan opsi (pisahkan satu opsi per baris).',
                ]);
            }
            $optionsArray = $parsed;
        }

        $field->label = trim((string) $request->input('label'));
        $field->type = $type;
        $field->placeholder = $request->filled('placeholder') ? trim((string) $request->input('placeholder')) : null;
        $field->options = $optionsArray;
        $field->is_required = $request->boolean('is_required', false);

        if ($request->filled('sort_order')) {
            $field->sort_order = (int) $request->input('sort_order');
        }

        $field->save();

        return back()->with('success', "Kolom formulir \"{$field->label}\" berhasil diperbarui.");
    }

    /**
     * Toggle active/inactive of a form field.
     */
    public function toggleField(ReservationField $field): RedirectResponse
    {
        $field->is_active = ! $field->is_active;
        $field->save();

        $status = $field->is_active ? 'ditampilkan' : 'disembunyikan';

        return back()->with('success', "Kolom \"{$field->label}\" berhasil {$status} pada formulir.");
    }

    /**
     * Delete a field from the Form Builder.
     */
    public function destroyField(ReservationField $field): RedirectResponse
    {
        $label = $field->label;
        $field->delete();

        return back()->with('success', "Kolom \"{$label}\" berhasil dihapus dari formulir. Data reservasi lama tetap tersimpan aman.");
    }
}
