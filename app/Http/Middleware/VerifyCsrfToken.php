<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
    ];

    /**
     * The URIs should be forced to CSRF verification.
     *
     * @var array
     */
    protected $force = [
        'create',
    ];

    protected function isReading($request)
    {
        foreach ($this->force as $uri) {
            if ($request->is($uri)) {
                return false;
            }
        }

        return parent::isReading($request);
    }
}
