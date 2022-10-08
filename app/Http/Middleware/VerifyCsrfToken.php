<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\URL;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "http://127.0.0.1:8000/send-payment-authicate",
        "http://127.0.0.1:8000/send-payment-no-auth",
    ];
}