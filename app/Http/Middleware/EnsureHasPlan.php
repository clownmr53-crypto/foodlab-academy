<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasPlan
{
    public function handle(Request $request, Closure $next, string $plan = 'starter'): Response
    {
        $user = $request->user();
        if (! $user || (! $user->isAdmin() && ! $user->hasPlan($plan))) {
            return redirect()->route('payments.plans')
                ->with('error', 'Un abonnement est requis pour accéder à cette section.');
        }

        return $next($request);
    }
}
