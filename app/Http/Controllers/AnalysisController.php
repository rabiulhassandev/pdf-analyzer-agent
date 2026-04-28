<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PdfAnalyzer;
use App\Models\Customer;
use App\Models\Prompt;
use App\Models\Settings;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Laravel\Ai\Files\Document;
use Laravel\Ai\Files\Image;

class AnalysisController extends Controller
{
    /**
     * Show PDF analysis form.
     */
    public function index(): View
    {
        $customers = Customer::orderBy('name')->get();
        $prompts = Prompt::where('status', 'active')->get();

        return view('analysis.index', compact('customers', 'prompts'));
    }

    /**
     * Process Document analysis via AJAX and return JSON response.
     */
    public function analyzePdf(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png,xlsx,xls,csv', 'max:20480'],
            'customer_id' => ['required', 'exists:customers,id'],
            'prompt_id' => ['nullable', 'exists:prompts,id'],
            'prompt_content' => ['nullable', 'string'],
        ]);

        $prompt = isset($validated['prompt_id']) ? Prompt::find($validated['prompt_id']) : null;
        $promptContent = $validated['prompt_content'] ?? ($prompt->content ?? 'Analyze the following document and extract key information.');

        try {
            $file = $request->file('document');
            $filePath = $file->getRealPath();
            $mimeType = $file->getMimeType();

            $settings = Settings::first();
            $provider = $settings->model_provider ?? 'anthropic';
            $model = $settings->model_version ?? 'claude-haiku-4-5';

            if (! in_array($provider, ['anthropic', 'openai', 'gemini'])) {
                return response()->json([
                    'success' => false,
                    'message' => ucfirst($provider) . ' does not support direct document/image analysis yet. Please configure OpenAI, Anthropic, or Gemini in the Settings.',
                ], 400);
            }

            $cacheKey = 'document_analysis_'.md5($file->getClientOriginalName().filesize($filePath).($validated['prompt_id'] ?? ''));

            $analysisResult = Cache::remember($cacheKey, 60, function () use ($filePath, $mimeType, $promptContent, $provider, $model) {
                // If it's an image, we use Image::fromPath, else Document::fromPath
                $attachment = str_starts_with($mimeType, 'image/') 
                    ? \Laravel\Ai\Files\Image::fromPath($filePath) 
                    : \Laravel\Ai\Files\Document::fromPath($filePath);

                $res = (new PdfAnalyzer)->prompt(
                    $promptContent,
                    provider: \Laravel\Ai\Enums\Lab::tryFrom($provider) ?? \Laravel\Ai\Enums\Lab::Anthropic,
                    model: $model,
                    attachments: [
                        $attachment,
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
                'message' => 'Document analysis failed: '.$e->getMessage(),
            ], 500);
        }
    }
}
