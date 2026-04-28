<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">Prompt Engineering</h2>
                <p class="text-sm text-slate-500 leading-[1.6] mt-1">Configure AI behavior for specific business contexts and customer requirements.</p>
            </div>
            <a href="{{ route('prompts.create') }}" class="px-4 py-2 bg-amber-600 text-white font-medium rounded-lg flex items-center gap-2 hover:bg-amber-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">add</span>
                Create Prompt
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
            <div class="relative">
                <label class="text-xs font-semibold text-slate-500 mb-1.5 block uppercase tracking-wider">Search</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg">search</span>
                    <input type="text" id="searchInput" class="w-full pl-9 pr-3 py-2 bg-slate-50 border-slate-200 border rounded-lg text-sm focus:border-amber-500 outline-none transition-colors" placeholder="By name..." value="{{ request('search') }}">
                </div>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1.5 block uppercase tracking-wider">Type</label>
                <select id="typeFilter" class="w-full px-3 py-2 bg-slate-50 border-slate-200 border rounded-lg text-sm focus:border-amber-500 outline-none transition-colors">
                    <option value="">All Types</option>
                    <option value="entity_extraction" {{ request('type') === 'entity_extraction' ? 'selected' : '' }}>Entity Extraction</option>
                    <option value="risk_assessment" {{ request('type') === 'risk_assessment' ? 'selected' : '' }}>Risk Assessment</option>
                    <option value="summary" {{ request('type') === 'summary' ? 'selected' : '' }}>Summary</option>
                    <option value="custom" {{ request('type') === 'custom' ? 'selected' : '' }}>Custom</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-semibold text-slate-500 mb-1.5 block uppercase tracking-wider">Status</label>
                <select id="statusFilter" class="w-full px-3 py-2 bg-slate-50 border-slate-200 border rounded-lg text-sm focus:border-amber-500 outline-none transition-colors">
                    <option value="">All Statuses</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="flex items-end pb-0.5">
                <a href="{{ route('prompts.index') }}" class="text-amber-600 font-semibold text-sm hover:underline px-2">Clear All Filters</a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse ($prompts as $prompt)
                <div class="bg-white border border-slate-200 rounded-xl shadow-[0_4px_20px_rgba(15,23,42,0.05)] overflow-hidden hover:shadow-lg transition-shadow group">
                    <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined {{ $prompt->type === 'entity_extraction' ? 'text-amber-600' : ($prompt->type === 'risk_assessment' ? 'text-amber-600' : ($prompt->type === 'summary' ? 'text-amber-600' : 'text-amber-600')) }}">auto_awesome</span>
                            <span class="text-[20px] font-semibold text-slate-900">{{ $prompt->name }}</span>
                        </div>
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $prompt->status === 'active' ? 'bg-amber-50 text-amber-700 border border-amber-100' : ($prompt->status === 'draft' ? 'bg-slate-100 text-slate-600' : 'bg-slate-200 text-slate-500') }}">
                            {{ $prompt->status }}
                        </span>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-slate-500 mb-4 line-clamp-2">{{ $prompt->description ?? 'No description available' }}</p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="px-2 py-1 bg-amber-50 text-amber-700 text-[10px] font-medium rounded">{{ $prompt->model }}</span>
                            <span class="px-2 py-1 bg-slate-100 text-slate-600 text-[10px] font-medium rounded">{{ str_replace('_', ' ', $prompt->type) }}</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center gap-1 text-xs text-slate-400">
                                <span class="material-symbols-outlined text-sm">monitoring</span>
                                <span>{{ $prompt->usage_count }} uses</span>
                            </div>
                            @if ($prompt->accuracy_score)
                                <div class="flex items-center gap-1 text-xs font-medium {{ $prompt->accuracy_score >= 90 ? 'text-amber-600' : 'text-amber-600' }}">
                                    <span class="material-symbols-outlined text-sm">verified</span>
                                    <span>{{ $prompt->accuracy_score }}% accuracy</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="px-5 py-3 bg-slate-50 border-t border-slate-100 flex gap-2">
                        <a href="{{ route('prompts.show', $prompt) }}" class="flex-1 text-center py-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">View</a>
                        <a href="{{ route('prompts.edit', $prompt) }}" class="flex-1 text-center py-2 text-sm font-medium text-amber-600 hover:bg-amber-50 rounded transition-colors">Edit</a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-2 lg:col-span-3 bg-white border border-slate-200 rounded-xl p-12 text-center">
                    <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">terminal</span>
                    <p class="text-slate-600 mb-4">No prompts found</p>
                    <a href="{{ route('prompts.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700">
                        Create First Prompt
                    </a>
                </div>
            @endforelse
        </div>

        @if ($prompts->hasPages())
            <div class="flex items-center justify-center">
                {{ $prompts->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>
        @endif
    </div>

    @stack('scripts')
        <script>
            document.getElementById('searchInput')?.addEventListener('input', function(e) {
                const url = new URL(window.location.href);
                url.searchParams.set('search', e.target.value);
                window.location.href = url.toString();
            });
            document.getElementById('typeFilter')?.addEventListener('change', function(e) {
                const url = new URL(window.location.href);
                url.searchParams.set('type', e.target.value);
                window.location.href = url.toString();
            });
            document.getElementById('statusFilter')?.addEventListener('change', function(e) {
                const url = new URL(window.location.href);
                url.searchParams.set('status', e.target.value);
                window.location.href = url.toString();
            });
        </script>
    @endstack
</x-admin-layout>

