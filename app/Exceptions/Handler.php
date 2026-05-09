<?php

namespace App\Exceptions;

use Exception;

// class Handler extends Exception
// {
//     //
// }
use Illuminate\Http\Exceptions\PostTooLargeException;
use Symfony\Component\HttpFoundation\Response;

class Handler extends \Illuminate\Foundation\Exceptions\Handler
{
    public function register(): void
    {
        $this->renderable(function (PostTooLargeException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'The uploaded file is too large. Please upload a smaller file.'
                ], Response::HTTP_REQUEST_ENTITY_TOO_LARGE);
            }

            return back()->with('error', 'The uploaded file is too large. Please upload a smaller file.');
        });
    }
}
