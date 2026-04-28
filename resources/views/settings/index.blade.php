<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">AI Configuration</h2>
            <p class="text-sm text-slate-500 leading-[1.6]">Configure AI model and system instructions for PDF analysis.</p>
        </div>

        <div class="max-w-2xl">
            <form action="{{ route('settings.update') }}" method="POST" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                @csrf
                @method('PATCH')

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Choose Model Provider</label>
                        <select id="modelProvider" name="model_provider" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                            @foreach ($models as $provider => $data)
                                <option value="{{ $provider }}" {{ $settings->model_provider === $provider ? 'selected' : '' }}>{{ $data['name'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Choose Model Version</label>
                        <select id="modelVersion" name="model_version" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-amber-500 transition-colors cursor-pointer">
                            @foreach ($models[$settings->model_provider]['models'] ?? [] as $version => $name)
                                <option value="{{ $version }}" {{ $settings->model_version === $version ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">System Instructions</label>
                        <textarea name="system_instructions" rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm focus:outline-none focus:border-amber-500 transition-all resize-none" placeholder="Enter system instructions...">{{ $settings->system_instructions }}</textarea>
                        <p class="text-xs text-slate-400 mt-2">These instructions guide the AI's behavior when analyzing PDFs.</p>
                    </div>

                    <div class="pt-4 border-t border-slate-200">
                        <button type="submit" class="bg-amber-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-amber-700 transition-all">
                            Save Configuration
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            const models = @json($models);

            const modelProvider = document.getElementById('modelProvider');
            const modelVersion = document.getElementById('modelVersion');

            modelProvider.addEventListener('change', async function() {
                const provider = this.value;
                modelVersion.innerHTML = '<option value="">Loading...</option>';
                modelVersion.disabled = true;

                try {
                    const response = await fetch(`/api/models/${provider}/versions`);
                    const versions = await response.json();

                    modelVersion.innerHTML = '';
                    Object.entries(versions).forEach(([value, name]) => {
                        const option = document.createElement('option');
                        option.value = value;
                        option.textContent = name;
                        modelVersion.appendChild(option);
                    });
                    modelVersion.disabled = false;
                } catch (error) {
                    console.error('Error loading model versions:', error);
                    modelVersion.innerHTML = '<option value="">Error loading versions</option>';
                }
            });
        </script>
    @endpush
</x-admin-layout>

