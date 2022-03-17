<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LanguageBelongsToPosition
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->language->position_id != $request->position->id) {
            return response()->json(['error' => 'Language Does Not Belong To This Position'], 403);
        }

        return $next($request);
    }
}
