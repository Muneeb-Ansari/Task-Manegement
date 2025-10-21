<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function fail(\Throwable $e, string $message = 'Something went wrong.'): RedirectResponse
    {
        Log::error($message, [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->with('error', $message)
            ->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}
