<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ErrorHandler
{
    public static function fail(\Throwable $e, string $message)
    {
        Log::error($message, ['error' => $e->getMessage()]);
        return back()->with('danger', $message);
    }
}
