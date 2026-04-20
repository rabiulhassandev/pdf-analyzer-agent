<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">PDF Analysis</h1>
        <p class="text-gray-600 mb-8">Upload a PDF document and select a customer to analyze it</p>

        <!-- Analysis Form -->
        <div class="bg-white rounded-lg border border-gray-200 p-8 mb-8">
            <form id="analysisForm" class="space-y-6">
                @csrf

                <!-- File Upload -->
                <div>
                    <label for="pdf" class="block text-sm font-medium text-gray-900 mb-2">
                        PDF File <span class="text-red-600">*</span>
                    </label>
                    <div class="relative">
                        <input type="file" id="pdf" name="pdf" accept=".pdf" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-sm text-gray-500 mt-2">Max 10MB</p>
                    </div>
                </div>

                <!-- Customer Selection -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-900 mb-2">
                        Customer <span class="text-red-600">*</span>
                    </label>
                    <select id="customer_id" name="customer_id" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select a customer...</option>
                        @foreach ($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" id="submitBtn" class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        Analyze PDF
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden">
            <div class="bg-white rounded-lg border border-gray-200 p-8 text-center">
                <div class="flex justify-center mb-4">
                    <div class="animate-spin">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                    </div>
                </div>
                <p class="text-gray-600 font-medium">Analyzing PDF...</p>
            </div>
        </div>

        <!-- Error State -->
        <div id="errorState" class="hidden">
            <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                <p class="text-red-800 font-medium" id="errorMessage"></p>
                <button type="button" onclick="document.getElementById('errorState').classList.add('hidden')" class="mt-3 text-sm text-red-700 hover:text-red-900">
                    Dismiss
                </button>
            </div>
        </div>

        <!-- Result State -->
        <div id="resultState" class="hidden">
            <div class="bg-white rounded-lg border border-gray-200 p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Analysis Result</h2>
                <p class="text-gray-600 mb-6">Customer: <span id="resultCustomer" class="font-medium text-gray-900"></span></p>

                <div class="prose prose-sm max-w-none">
                    <div id="analysisResult" class="whitespace-pre-wrap text-gray-700 bg-gray-50 p-6 rounded-lg"></div>
                </div>

                <button type="button" onclick="resetForm()" class="mt-6 px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    Analyze Another PDF
                </button>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('analysisForm');
        const submitBtn = document.getElementById('submitBtn');
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const resultState = document.getElementById('resultState');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            // Reset states
            errorState.classList.add('hidden');
            resultState.classList.add('hidden');

            const formData = new FormData(form);

            // Show loading
            form.classList.add('hidden');
            loadingState.classList.remove('hidden');
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("analysis.pdf") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: formData,
                });

                const data = await response.json();

                loadingState.classList.add('hidden');

                if (data.success) {
                    document.getElementById('resultCustomer').textContent = data.customer_name;
                    document.getElementById('analysisResult').textContent = data.analysis;
                    resultState.classList.remove('hidden');
                } else {
                    throw new Error(data.message || 'Analysis failed');
                }
            } catch (error) {
                loadingState.classList.add('hidden');
                form.classList.remove('hidden');
                submitBtn.disabled = false;

                document.getElementById('errorMessage').textContent = error.message;
                errorState.classList.remove('hidden');
            }
        });

        function resetForm() {
            form.reset();
            resultState.classList.add('hidden');
            form.classList.remove('hidden');
            submitBtn.disabled = false;
        }
    </script>
</x-app-layout>