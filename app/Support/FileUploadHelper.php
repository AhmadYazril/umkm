<?php

namespace App\Support;

class FileUploadHelper
{
    /**
     * Parse human-readable INI size (e.g. '2M', '8M', '512K', '1G') into bytes.
     */
    public static function parseIniSize(string|int|null $size): int
    {
        if (empty($size) || $size === -1 || $size === '-1') {
            return PHP_INT_MAX;
        }

        $size = trim((string) $size);
        $unit = strtolower(substr($size, -1));
        $value = (float) substr($size, 0, -1);

        return match ($unit) {
            'g' => (int) round($value * 1024 * 1024 * 1024),
            'm' => (int) round($value * 1024 * 1024),
            'k' => (int) round($value * 1024),
            default => (int) round((float) $size),
        };
    }

    /**
     * Format bytes into a clean, human-readable string (e.g. '2 MB', '500 KB').
     */
    public static function formatBytes(int $bytes, int $precision = 1): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $power = min((int) floor(log($bytes, 1024)), count($units) - 1);
        $val = round($bytes / (1024 ** $power), $precision);

        // Remove trailing .0 for cleaner display (e.g. 2 MB instead of 2.0 MB)
        $valFormatted = (floor($val) == $val) ? (int) $val : $val;

        return $valFormatted.' '.$units[$power];
    }

    /**
     * Get server PHP upload configurations.
     *
     * @return array{
     *     upload_max_filesize_raw: string,
     *     upload_max_filesize_bytes: int,
     *     post_max_size_raw: string,
     *     post_max_size_bytes: int,
     *     memory_limit_raw: string,
     *     loaded_ini: ?string,
     *     effective_max_bytes: int,
     *     effective_max_formatted: string
     * }
     */
    public static function getServerUploadLimits(): array
    {
        $uploadRaw = ini_get('upload_max_filesize') ?: '2M';
        $postRaw = ini_get('post_max_size') ?: '8M';
        $memoryRaw = ini_get('memory_limit') ?: '128M';

        $uploadBytes = self::parseIniSize($uploadRaw);
        $postBytes = self::parseIniSize($postRaw);
        $effectiveBytes = min($uploadBytes, $postBytes);
        $formatted = self::formatBytes($effectiveBytes);

        return [
            'upload_max_filesize_raw' => $uploadRaw,
            'upload_max_filesize_bytes' => $uploadBytes,
            'post_max_size_raw' => $postRaw,
            'post_max_size_bytes' => $postBytes,
            'memory_limit_raw' => $memoryRaw,
            'loaded_ini' => php_ini_loaded_file() ?: null,
            'effective_max_bytes' => $effectiveBytes,
            'effective_max_formatted' => $formatted,
            'formatted' => $formatted,
            'limit_formatted' => $formatted,
            'raw' => $uploadRaw,
            'bytes' => $effectiveBytes,
            'effective_bytes' => $effectiveBytes,
            'limit_bytes' => $effectiveBytes,
        ];
    }

    /**
     * Calculate effective upload limit by taking the strictest between application limit and server limit.
     *
     * @return array<string, mixed>
     */
    public static function getEffectiveLimit(int $appLimitBytes, string $fileTypeLabel = 'foto'): array
    {
        $serverLimits = self::getServerUploadLimits();
        $serverBytes = $serverLimits['effective_max_bytes'];

        $isLimitedByServer = ($serverBytes < $appLimitBytes);
        $effectiveBytes = min($appLimitBytes, $serverBytes);
        $effectiveFormatted = self::formatBytes($effectiveBytes);
        $appFormatted = self::formatBytes($appLimitBytes);
        $serverFormatted = self::formatBytes($serverBytes);

        if ($isLimitedByServer) {
            $guideText = "Maksimal {$effectiveFormatted} (dibatasi oleh server PHP; perkecil file atau naikkan batas upload_max_filesize di php.ini ke 32 MB agar mendukung hingga {$appFormatted}).";
        } else {
            $guideText = "Maksimal {$effectiveFormatted} (disarankan di bawah 2 MB agar halaman web cepat dibuka).";
        }

        return [
            'formatted' => $effectiveFormatted,
            'limit_formatted' => $effectiveFormatted,
            'effective_formatted' => $effectiveFormatted,
            'raw' => $serverLimits['upload_max_filesize_raw'] ?? '2M',
            'server_limit_raw' => $serverLimits['upload_max_filesize_raw'] ?? '2M',
            'bytes' => $effectiveBytes,
            'effective_bytes' => $effectiveBytes,
            'limit_bytes' => $effectiveBytes,
            'limit_kb' => (int) round($effectiveBytes / 1024),
            'limit_mb' => round($effectiveBytes / (1024 * 1024), 2),
            'server_limit_bytes' => $serverBytes,
            'server_limit_formatted' => $serverFormatted,
            'app_limit_bytes' => $appLimitBytes,
            'app_limit_formatted' => $appFormatted,
            'is_throttled' => $isLimitedByServer,
            'is_limited_by_server' => $isLimitedByServer,
            'guide_text' => $guideText,
        ];
    }

    /**
     * Map PHP upload error codes to friendly Indonesian messages with actionable suggestions.
     */
    public static function getErrorMessageForCode(int $errorCode, string $label = 'Berkas'): ?string
    {
        $serverLimits = self::getServerUploadLimits();
        $serverMax = $serverLimits['upload_max_filesize_raw'];

        return match ($errorCode) {
            UPLOAD_ERR_OK => null,
            UPLOAD_ERR_INI_SIZE => "{$label} gagal diunggah karena ukurannya melebihi batas server PHP (upload_max_filesize: {$serverMax}). Perkecil ukuran file atau hubungi pengelola server untuk menaikkan nilai upload_max_filesize di php.ini.",
            UPLOAD_ERR_FORM_SIZE => "{$label} gagal diunggah karena ukurannya melebihi batas yang ditentukan oleh formulir. Silakan perkecil ukuran file.",
            UPLOAD_ERR_PARTIAL => "{$label} hanya terunggah sebagian (terpotong) karena koneksi internet terputus saat proses pengiriman. Silakan coba unggah kembali.",
            UPLOAD_ERR_NO_FILE => "Tidak ada {$label} yang dipilih untuk diunggah. Silakan pilih berkas terlebih dahulu.",
            UPLOAD_ERR_NO_TMP_DIR => "Server tidak memiliki folder penyimpanan sementara (temporary directory) untuk memproses unggahan {$label}. Silakan hubungi pengelola server.",
            UPLOAD_ERR_CANT_WRITE => "Server gagal menyimpan {$label} ke media penyimpanan disk. Periksa ruang penyimpanan atau hak akses folder server.",
            UPLOAD_ERR_EXTENSION => "Proses unggah {$label} dihentikan oleh ekstensi PHP di server.",
            default => "Terjadi kesalahan sistem saat mengunggah {$label} (Kode error: {$errorCode}). Silakan coba beberapa saat lagi.",
        };
    }

    /**
     * Inspect an uploaded file object or $_FILES entry for upload errors before validation.
     */
    public static function inspectUploadedFile(mixed $file, string $label = 'Berkas'): ?string
    {
        if (! $file) {
            return null;
        }

        if (is_object($file) && method_exists($file, 'getError')) {
            $errorCode = $file->getError();
            if ($errorCode !== UPLOAD_ERR_OK) {
                return self::getErrorMessageForCode($errorCode, $label);
            }
        }

        return null;
    }

    /**
     * Ensure destination upload directory exists and is writable.
     *
     * @throws \RuntimeException
     */
    public static function ensureDirectoryWritable(string $dirPath, string $label = 'Folder penyimpanan'): void
    {
        if (! file_exists($dirPath)) {
            if (! @mkdir($dirPath, 0755, true) && ! is_dir($dirPath)) {
                throw new \RuntimeException("{$label} ('{$dirPath}') gagal dibuat oleh server. Periksa hak akses direktori.");
            }
        }

        if (! is_writable($dirPath)) {
            throw new \RuntimeException("{$label} ('{$dirPath}') tidak memiliki izin tulis (write permission). Berikan hak akses tulis ke folder ini.");
        }
    }
}
