<?php

namespace MoamalatPay\Http\Middleware;

use Closure;
use MoamalatPay\Events\DisallowedRequestEvent;

/**
 * Validate sender ip is allowed to send notifications.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return string|null
 */
class AllowedIps
{
    public function handle($request, Closure $next)
    {
        $allowed = config('moamalat-pay.notification.allowed_ips');
        if (!in_array('*', $allowed) && !in_array($request->ip(), $allowed)) {
            DisallowedRequestEvent::dispatch();
            abort(403);
        }

        return $next($request);
    }
}
