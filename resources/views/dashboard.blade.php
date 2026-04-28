<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">Dashboard</h2>
            <p class="text-sm text-slate-500 leading-[1.6]">Overview of your PDF analysis system.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <span class="material-symbols-outlined">group</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium">Total Customers</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format(App\Models\Customer::count()) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                        <span class="material-symbols-outlined">auto_awesome</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium">Total Prompts</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format(App\Models\Prompt::count()) }}</h3>
            </div>

            <div class="bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                        <span class="material-symbols-outlined">description</span>
                    </div>
                </div>
                <p class="text-sm text-slate-500 font-medium">Analyses</p>
                <h3 class="text-2xl font-bold text-slate-900 mt-1">{{ number_format(App\Models\Analysis::count()) }}</h3>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                <h4 class="text-[20px] font-semibold text-slate-900 mb-4">Quick Actions</h4>
                <div class="space-y-3">
                    <a href="{{ route('analysis.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <span class="material-symbols-outlined">upload_file</span>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Analyze PDF</div>
                            <div class="text-xs text-slate-500">Upload and analyze documents</div>
                        </div>
                    </a>
                    <a href="{{ route('prompts.create') }}" class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all">
                        <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                            <span class="material-symbols-outlined">add</span>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Create Prompt</div>
                            <div class="text-xs text-slate-500">Add new AI prompt template</div>
                        </div>
                    </a>
                    <a href="{{ route('customers.create') }}" class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all">
                        <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                            <span class="material-symbols-outlined">person_add</span>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Add Customer</div>
                            <div class="text-xs text-slate-500">Register new customer</div>
                        </div>
                    </a>
                    <a href="{{ route('settings.index') }}" class="flex items-center gap-3 p-4 rounded-lg border border-slate-200 hover:border-blue-500 hover:bg-blue-50/50 transition-all">
                        <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                            <span class="material-symbols-outlined">settings</span>
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">Settings</div>
                            <div class="text-xs text-slate-500">Configure AI model</div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="p-6 border-b border-slate-200 flex items-center justify-between">
                    <h4 class="text-[20px] font-semibold text-slate-900">Recent Analyses</h4>
                    <a href="{{ route('analysis.index') }}" class="text-blue-600 text-xs font-bold hover:underline">View All</a>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($recentAnalyses ?? [] as $analysis)
                        <div class="p-4 px-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded bg-red-50 flex items-center justify-center text-red-600">
                                    <span class="material-symbols-outlined">picture_as_pdf</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900">{{ $analysis->file_name ?? 'Unknown Document' }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $analysis->customer?->company ?? $analysis->customer?->name ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-slate-900">{{ ucfirst($analysis->status ?? 'Pending') }}</p>
                                <p class="text-[10px] text-slate-400">{{ $analysis->created_at?->diffForHumans() ?? 'Just now' }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-500">
                            <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">description</span>
                            <p class="text-sm">No recent analyses found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
