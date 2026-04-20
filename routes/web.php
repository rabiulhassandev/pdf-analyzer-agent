<?php

use App\Ai\Agents\PdfAnalyzer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Laravel\Ai\Files\Document;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    // cache 
    $response = Cache::remember('pdf_analysis_response', 120, function () {
        $res = (new PdfAnalyzer)->prompt(
            'Find the person information in the following PDF content and list them separately.',
            model: 'claude-haiku-4-5-20251001',
            attachments: [
                Document::fromPath(public_path('sample.pdf')),
            ]
        );

        return $res->text;
    });

    return response()->json(['text' => $response]);
});


Route::get('/test-cache', function () {
    $cachedResponse = Cache::get('pdf_analysis_response');

    if ($cachedResponse) {
        return response()->json(['text' => $cachedResponse, 'cached' => true]);
    }

    return response()->json(['message' => 'No cached response found.'], 404);
})->name('test-cache');
