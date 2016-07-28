<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        # csrf 어차피 로컬에서 쓸거니 검증을 뺌 
    	'code/*', 
    	'kospi/*',
    	'sise/*' 
    ];
}
