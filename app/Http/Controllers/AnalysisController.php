<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PdfAnalyzer;
use App\Models\Customer;
use App\Models\Prompt;
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
        $prompts = Prompt::where('status', 'active')->get();

        return view('analysis.index', compact('customers', 'prompts'));
    }

    /**
     * Process PDF analysis via AJAX and return JSON response.
     */
    public function analyzePdf(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
            'customer_id' => ['required', 'exists:customers,id'],
            'prompt_id' => ['nullable', 'exists:prompts,id'],
            'prompt_content' => ['nullable', 'string'],
        ]);

        $prompt = isset($validated['prompt_id']) ? Prompt::find($validated['prompt_id']) : null;
        $promptContent = $validated['prompt_content'] ?? ($prompt->content ?? 'Analyze the following PDF and extract key information.');

        try {
            $file = $request->file('pdf');
            $filePath = $file->getRealPath();

            $cacheKey = 'pdf_analysis_'.md5($file->getClientOriginalName().filesize($filePath).$validated['prompt_id']);

            $analysisResult = Cache::remember($cacheKey, 60, function () use ($filePath, $promptContent) {
                $res = (new PdfAnalyzer)->prompt(
                    $promptContent,
                    model: $prompt->model ?? 'claude-haiku-4-5-20251001',
                    attachments: [
                        Document::fromPath($filePath),
                    ]
                );

                return $res->text;
            });

            $analysis = json_decode($analysisResult, true);

            if (isset($analysis['value'])) {
                $analysis = json_decode($analysis['value'], true);
            }

            return response()->json([
                'success' => true,
                'result' => $analysis,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'PDF analysis failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
