<?php

namespace App\Http\Middleware;

use App\Facades\UserPermission;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\IpUtils;

class RestrictPipelineIp
{
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get the current user
        /** @var User */
        $user = Auth::user();

        if (!$user) {
            return $next($request); // Skip if user is not logged in yet
        }

        // 2. Rule 1: Check if this user is explicitly exempted (Allows any IP)
        if ($user->isIpExempted()) {
            return $next($request);
        }

        // 3. Extract the pipeline tied directly to this user
        $pipeline = $user->pipeline;
        if (!$pipeline) {
            return $next($request); // Fallback: User doesn't have an assigned pipeline
        }

        // If admin Allow any IP
        $isSuperAdmin = UserPermission::isSuperAdmin($user);
        $isPipelineAdmin = UserPermission::isPipelineAdmin($user, $pipeline->id);

        if($isSuperAdmin || $isPipelineAdmin){
            return $next($request);
        }

        // 4. Rule 2: Fetch the allowed IPs for their pipeline
        $allowedIps = $pipeline->allowedIps()->pluck('ip_address')->toArray();
        
        // If the pipeline has no IP rules set up, allow access
        if (empty($allowedIps)) {
            return $next($request);
        }

        // 5. Rule 3: Validate the user's current connection IP
        $clientIp = $request->ip();
        
        if (!IpUtils::checkIp($clientIp, $allowedIps)) {
            abort(403, "Access Denied: Your IP address ($clientIp) is not authorized to access this pipeline.");
        }
        

        return $next($request);
    }
}
