<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontFlash = ['current_password', 'password', 'password_confirmation'];
    protected $dontReport = [];
    protected $levels = [];

    public function register()
    {
        $this->reportable(function (Throwable $e) {});
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof \Illuminate\Encryption\MissingAppKeyException) {
            if (!File::exists(base_path('.env'))) {
                File::copy(base_path('.env.example'), base_path('.env'));
            }
            return redirect()->to('/install');
        }

        return parent::render($request, $e);
    }
}
