<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

    protected $addHttpCookie = true;

   protected $except = [
    'auth/facebook/callback',
    'http://localhost/api/client/register_action',
    'http://localhost/api/client/login_action'
];
}
