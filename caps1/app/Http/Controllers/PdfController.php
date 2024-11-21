<?php
// app/Http/Controllers/PdfController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\eBooks;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class PdfController extends Controller
{
    public function stream(Request $request, $id)
    {
        // Find the PDF by ID
        $pdf = eBooks::findOrFail($id);

        // Validate token
        if ($request->input('token') !== $this->generateToken($id)) {
            return response('Unauthorized', Response::HTTP_UNAUTHORIZED);
        }

        // Path to the PDF file
        $path = storage_path('app/' . $pdf->file_path);

        // Stream the PDF file
        return response()->file($path);
    }

    // Generate a token for the PDF
    private function generateToken($id)
    {
        // Example token generation logic; adjust as needed
        return hash_hmac('sha256', $id, env('APP_KEY'));
    }
    // app/Http/Controllers/PdfController.php
public function getPdfUrl($id)
{
    // Generate a token
    $token = $this->generateToken($id);

    // Return the URL with the token
    return response()->json([
        'url' => route('pdf.stream', ['id' => $id, 'token' => $token])
    ]);
}

}