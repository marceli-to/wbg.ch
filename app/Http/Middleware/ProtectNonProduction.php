<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Keeps non-production environments (e.g. staging.wbg.ch) private:
 *
 * - HTTP Basic Auth when BASIC_AUTH_USER / BASIC_AUTH_PASSWORD are set
 * - X-Robots-Tag: noindex on every response
 *
 * Does nothing in production.
 */
class ProtectNonProduction
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->isProduction()) {
            return $next($request);
        }

        $user = config('app.basic_auth.user');
        $password = config('app.basic_auth.password');

        if ($user && $password && ! $this->authorized($request, $user, $password)) {
            return response('Authentication required.', 401, [
                'WWW-Authenticate' => 'Basic realm="'.config('app.name').'", charset="UTF-8"',
                'X-Robots-Tag' => 'noindex, nofollow',
            ]);
        }

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow');

        return $response;
    }

    private function authorized(Request $request, string $user, string $password): bool
    {
        return hash_equals($user, (string) $request->getUser())
            && hash_equals($password, (string) $request->getPassword());
    }
}
