<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Company;

class TenantMiddleware
{
    public function handle($request, Closure $next)
    {
        $sub = explode('.', $request->getHost())[0];

        if ($sub !== 'brickbox') {
            if ($company = Company::where('subdomain', $sub)->first()) {
                session(['tenant' => $company]);
                app()->instance('tenant', $company);
            }
        }

        return $next($request);
    }
}
