<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Cookie;

class VerifyCsrfToken extends Middleware
{

    /**
     * Get the CSRF token from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN') ?: $request->cookies->get("XSRF-TOKEN");

        if (!$token && ($header = $request->header('X-XSRF-TOKEN') ||  $header = $request->cookies->get("XSRF-TOKEN"))) {
            try {
                $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, static::serialized()));
            } catch (DecryptException $e) {
                $token = '';
            }
        }

        return $token;
    }
    // public function handle($request, Closure $next)
    // {
    //     if (
    //         $this->isReading($request) ||
    //         $this->runningUnitTests() ||
    //         $this->inExceptArray($request) ||
    //         $this->tokensMatch($request)

    //     ) {
    //         return tap($next($request), function ($response) use ($request) {

    //             if ($this->shouldAddXsrfTokenCookie()) {
    //                 $this->addCookieToResponse($request, $response);
    //             }
    //         })->header('Access-Control-Allow-Headers', '*');
    //     }

    //     throw new TokenMismatchException('CSRF token mismatch.');
    // }
    // protected function addCookieToResponse($request, $response)
    // {
    //     $config = config('session');


    //     $response->headers->setCookie(
    //         new Cookie(
    //             'XSRF-TOKEN',
    //             $request->session()->token(),
    //             $this->availableAt(60 * $config['lifetime']),
    //             $config['path'],
    //             $config['domain'],
    //             true, //$config['secure'],
    //             false,
    //             false,
    //             "none"
    //             // $config['same_site'] ?? null
    //         )
    //         //  new Cookie(
    //         //     'XSRF-TOKEN',
    //         //     $request->session()->token(),
    //         //     time() + 60 * 120,
    //         //     '/',
    //         //     null,
    //         //     true, // Set this to true for secure.
    //         //     true // Set this to true for httponly.
    //         // )
    //     );
    //     return $response;
    // }
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
