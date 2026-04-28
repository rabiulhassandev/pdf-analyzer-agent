<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">Create Prompt</h2>
            <p class="text-sm text-slate-500 leading-[1.6]">Create a new AI prompt template for PDF analysis.</p>
        </div>

        <div class="max-w-2xl">
            <form action="{{ route('prompts.store') }}" method="POST" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Select Customer</label>
                        <select name="customer_id" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->company ?? $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Prompt Name</label>
                        <input type="text" name="name" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-all" placeholder="e.g., Purchase Order Analysis">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Status</label>
                        <select name="status" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                            <option value="draft">Draft</option>
                            <option value="active" selected>Active</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Prompt Content</label>
                        <textarea name="content" required rows="12" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm focus:outline-none focus:border-amber-500 transition-all resize-none leading-relaxed" placeholder="Enter the prompt content for AI analysis..."></textarea>
                        <p class="text-xs text-slate-400 mt-2">This content will be used by the AI to analyze PDF documents.</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                        <a href="{{ route('prompts.index') }}" class="text-slate-600 font-medium text-sm hover:text-slate-900">
                            Cancel
                        </a>
                        <button type="submit" class="bg-amber-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-amber-700 transition-all">
                            Save Prompt
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>

