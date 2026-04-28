<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Settings::firstOrCreate([], [
            'model_provider' => 'openai',
            'model_version' => 'gpt-4o',
            'system_instructions' => 'You are a helpful AI assistant specialized in PDF document analysis.',
        ]);

        $models = config('ai-models');

        return view('settings.index', compact('settings', 'models'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'model_provider' => 'required|string|in:anthropic,openai,gemini,deepseek',
            'model_version' => 'required|string',
            'system_instructions' => 'nullable|string',
        ]);

        Settings::first()->update($validated);

        return back()->with('success', 'Settings updated successfully.');
    }

    public function getModelVersions(string $provider)
    {
        $models = config("ai-models.{$provider}.models", []);

        return response()->json($models);
    }
}
