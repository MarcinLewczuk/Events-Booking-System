<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApproverMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        // Only admin and approver roles can approve
        if (!in_array($user->role, ['admin', 'approver'])) {
            abort(403, 'Only admins and approvers can perform this action.');
        }

        return $next($request);
    }
}