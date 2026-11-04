<?php

namespace App\Providers;

use App\Support\FileUploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share server upload limits with all views
        try {
            View::share('serverUploadLimits', FileUploadHelper::getServerUploadLimits());
        } catch (\Throwable $e) {
            // Ignore if in CLI/testing without full view environment
        }

        // Custom replacer for 'uploaded' validation rule to provide specific Indonesian error messages
        Validator::replacer('uploaded', function ($message, $attribute, $rule, $parameters, $validator) {
            $value = $validator->getValue($attribute);
            $label = $validator->customAttributes[$attribute]
                ?? trans("validation.attributes.{$attribute}")
                ?? str_replace('_', ' ', $attribute);

            if ($value instanceof UploadedFile) {
                $errorCode = $value->getError();
                $specificMessage = FileUploadHelper::getErrorMessageForCode($errorCode, $label);
                if ($specificMessage) {
                    return $specificMessage;
                }
            } elseif (isset($_FILES[$attribute]['error'])) {
                $errorCode = $_FILES[$attribute]['error'];
                $specificMessage = FileUploadHelper::getErrorMessageForCode($errorCode, $label);
                if ($specificMessage) {
                    return $specificMessage;
                }
            }

            $serverLimits = FileUploadHelper::getServerUploadLimits();

            return str_replace(
                [':server_max', ':max_server'],
                $serverLimits['upload_max_filesize_raw'],
                $message
            );
        });

        // Custom replacer for 'required' to detect when a required file upload actually failed due to PHP ini size
        Validator::replacer('required', function ($message, $attribute, $rule, $parameters, $validator) {
            if (isset($_FILES[$attribute]) && is_array($_FILES[$attribute])) {
                $errorCode = $_FILES[$attribute]['error'] ?? UPLOAD_ERR_OK;
                if ($errorCode !== UPLOAD_ERR_OK && $errorCode !== UPLOAD_ERR_NO_FILE) {
                    $label = $validator->customAttributes[$attribute]
                        ?? trans("validation.attributes.{$attribute}")
                        ?? str_replace('_', ' ', $attribute);

                    $specificMessage = FileUploadHelper::getErrorMessageForCode($errorCode, $label);
                    if ($specificMessage) {
                        return $specificMessage;
                    }
                }
            }

            return $message;
        });
    }
}
