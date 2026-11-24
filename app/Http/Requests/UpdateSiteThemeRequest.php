<?php

namespace App\Http\Requests;

use App\Http\Controllers\Admin\AuthController;
use App\Models\Setting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteThemeRequest extends FormRequest
{
    /**
     * Only authenticated administrators may update the global site theme.
     */
    public function authorize(): bool
    {
        if (! AuthController::checkOrRestoreAdminAuth($this)) {
            return false;
        }

        return $this->user()?->isAdmin() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'theme' => ['required', 'string', Rule::in(Setting::ALLOWED_THEMES)],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'theme.required' => 'Tema situs wajib dipilih (light atau dark).',
            'theme.string' => 'Format tema situs tidak valid.',
            'theme.in' => 'Tema situs hanya boleh bernilai light (Terang) atau dark (Gelap).',
        ];
    }
}
