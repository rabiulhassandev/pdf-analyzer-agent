<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PdfAnalyzer;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Laravel\Ai\Files\Document;

class AnalysisController extends Controller
{
    /**
     * Show the PDF analysis form.
     */
    public function index(): View
    {
        $customers = Customer::orderBy('name')->get();

        return view('analysis.index', compact('customers'));
    }

    /**
     * Process PDF analysis via AJAX and return JSON response.
     */
    public function analyzePdfByAi(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'customer_id' => ['required', 'exists:customers,id'],
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);

        try {
            $file = $request->file('pdf');
            $path = $file->store('pdfs');

            $fullPath = storage_path("app/{$path}");

            if (!file_exists($fullPath)) {
                throw new \Exception("File not found: {$fullPath}");
            }

            $cacheKey = 'pdf_analysis_' . md5_file($fullPath);

            $analysisResult = Cache::remember($cacheKey, 120, function () use ($fullPath, $customer) {
                $res = (new PdfAnalyzer)->prompt(
                    $customer->prompt ?? 'Analyze the following PDF and extract key information.',
                    model: 'claude-haiku-4-5-20251001',
                    attachments: [
                        Document::fromPath($fullPath),
                    ]
                );

                return $res->text;
            });

            return response()->json([
                'success' => true,
                'analysis' => $analysisResult,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Analysis failed. Please try again.',
                'error' => $e->getMessage(), // optional for debugging
            ], 500);
        }
    }
}
