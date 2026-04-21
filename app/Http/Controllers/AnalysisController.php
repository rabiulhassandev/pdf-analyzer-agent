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

        $file = $request->file('pdf');
        $filePath = $file->getRealPath();

        $cacheKey = 'pdf_analysis_' . md5($file->getClientOriginalName() . filesize($filePath));

        $analysisResult = Cache::remember($cacheKey, 12000, function () use ($filePath, $customer) {
            $res = (new PdfAnalyzer)->prompt(
                $customer->prompt ?? 'Analyze the following PDF and extract key information.',
                model: 'claude-haiku-4-5-20251001',
                attachments: [
                    Document::fromPath($filePath),
                ]
            );

            return $res->text;
        });

        $analysis = json_decode($analysisResult, true);

        // If it still contains "value" as JSON string
        if (isset($analysis['value'])) {
            $analysis = json_decode($analysis['value'], true);
        }

        return response()->json([
            'success' => true,
            'analysis' => $analysis,
        ]);
    }
}
