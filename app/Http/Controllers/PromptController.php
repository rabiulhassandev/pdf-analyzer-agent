<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Prompt;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PromptController extends Controller
{
    public function index(Request $request)
    {
        $query = Prompt::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $prompts = $query->latest()->paginate(12);

        return view('prompts.index', compact('prompts'));
    }

    public function create()
    {
        $customers = Customer::all();

        return view('prompts.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'type' => 'required|in:entity_extraction,risk_assessment,summary,custom',
            'status' => 'required|in:draft,active,archived',
            'model' => 'required|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['user_id'] = auth()->id();

        Prompt::create($validated);

        return redirect()->route('prompts.index')->with('success', 'Prompt created successfully.');
    }

    public function show(Prompt $prompt)
    {
        return view('prompts.show', compact('prompt'));
    }

    public function edit(Prompt $prompt)
    {
        $customers = Customer::all();

        return view('prompts.edit', compact('prompt', 'customers'));
    }

    public function update(Request $request, Prompt $prompt)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|string',
            'type' => 'required|in:entity_extraction,risk_assessment,summary,custom',
            'status' => 'required|in:draft,active,archived',
            'model' => 'required|string|max:50',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $prompt->update($validated);

        return redirect()->route('prompts.index')->with('success', 'Prompt updated successfully.');
    }

    public function destroy(Prompt $prompt)
    {
        $prompt->delete();

        return redirect()->route('prompts.index')->with('success', 'Prompt deleted successfully.');
    }

    public function getByCustomer(Customer $customer)
    {
        $prompts = $customer->prompts()->where('status', 'active')->get(['id', 'name']);

        return response()->json($prompts);
    }

    public function getPromptContent(Prompt $prompt)
    {
        return response()->json([
            'id' => $prompt->id,
            'name' => $prompt->name,
            'content' => $prompt->content,
        ]);
    }
}
