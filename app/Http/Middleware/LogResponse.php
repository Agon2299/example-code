<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogResponse
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        $method = strtoupper($request->getMethod());

        $uri = $request->getPathInfo();

        $bodyAsJson = json_encode($request->except(config('http-logger.except')));

        $headersAsJson = json_encode($request->headers->all());

        $message = "{$method} {$uri} - Body: {$bodyAsJson} - Headers: {$headersAsJson} - Response: " . html_entity_decode(json_encode($response, JSON_UNESCAPED_UNICODE));

        \Log::info($message);
    }
}
