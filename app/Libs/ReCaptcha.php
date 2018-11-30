<?php
declare(strict_types=1);

namespace App\Libs;

use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;

trait ReCaptcha
{
    protected function validateReCaptcha(Request $request): bool
    {
        preg_match('/\/\/(.+?)(:\d+)?$/', config('app.url'), $matches);
        $recaptcha = new \ReCaptcha\ReCaptcha(config('recaptcha.secret_key'));

        return $recaptcha->setExpectedHostname($matches[1])
            ->verify($request['g-recaptcha-response'], $request->ip())
            ->isSuccess();
    }

    protected function reCaptchaFailed(): void
    {
        throw ValidationException::withMessages([
            $this->username() => [ __('auth.recaptcha.failed') ],
        ]);
    }
}
