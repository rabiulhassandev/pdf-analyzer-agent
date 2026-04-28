<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">PDF Analysis</h2>
            <p class="text-sm text-slate-500 leading-[1.6]">Upload PDF documents and analyze with AI-powered prompts.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
            <div class="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-[20px] font-semibold mb-6 text-slate-900">Upload Document</h3>

                <form id="analysisForm" class="space-y-6">
                    

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Upload PDF</label>
                        <div id="uploadArea" class="border-2 border-dashed border-slate-200 rounded-xl p-8 flex flex-col items-center justify-center text-center transition-all hover:border-blue-600 hover:bg-slate-50 group cursor-pointer">
                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center mb-4 group-hover:bg-blue-50 transition-colors">
                                <span class="material-symbols-outlined text-2xl text-slate-400 group-hover:text-blue-600">upload_file</span>
                            </div>
                            <p class="text-sm text-slate-500 mb-2">Drag and drop PDF here, or click to browse</p>
                            <input type="file" id="pdfFile" name="pdf" accept=".pdf" class="hidden">
                            <button type="button" id="selectFileBtn" class="text-blue-600 font-medium text-sm hover:underline">
                                Select File
                            </button>
                        </div>
                        <div id="selectedFile" class="hidden mt-3 p-3 bg-blue-50 border border-blue-100 rounded-lg items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-blue-600">picture_as_pdf</span>
                                <span id="selectedFileName" class="text-sm font-medium text-slate-900"></span>
                            </div>
                            <button type="button" id="removeFileBtn" class="text-slate-400 hover:text-red-600">
                                <span class="material-symbols-outlined">close</span>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Select Customer</label>
                        <select id="customerId" name="customer_id" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-colors cursor-pointer">
                            <option value="">Select Customer</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->company ?? $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Select Prompt</label>
                        <select id="promptId" name="prompt_id" class="w-full appearance-none bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-colors cursor-pointer" disabled>
                            <option value="">Select Prompt</option>
                        </select>
                    </div>
                        
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Prompt Content</label>
                        <textarea id="promptContent" name="prompt_content" rows="8" class="w-full bg-slate-50 border border-slate-200 rounded-lg p-4 text-sm focus:outline-none focus:border-blue-500 transition-all resize-none" placeholder="Prompt content will appear here..." readonly></textarea>
                    </div>

                    <button type="submit" id="analyzeBtn" class="w-full bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        Analyze PDF
                    </button>
                </form>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-slate-50 px-6 py-4 border-b border-slate-200 flex items-center justify-between">
                    <h3 class="text-[20px] font-semibold text-slate-900">Analysis Result</h3>
                    <div class="flex gap-2">
                        <button id="copyBtn" class="p-2 rounded hover:bg-slate-200 text-slate-500 transition-colors" title="Copy" disabled>
                            <span class="material-symbols-outlined text-sm">content_copy</span>
                        </button>
                        <button id="downloadBtn" class="p-2 rounded hover:bg-slate-200 text-slate-500 transition-colors" title="Download" disabled>
                            <span class="material-symbols-outlined text-sm">download</span>
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <div id="loadingState" class="hidden text-center py-12">
                        <div class="animate-spin w-10 h-10 mx-auto mb-4">
                            <svg class="w-10 h-10 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-slate-600 font-medium">Analyzing PDF...</p>
                    </div>
                    <pre id="jsonOutput" class="bg-[#0f172a] text-blue-300 font-mono text-xs leading-relaxed p-4 rounded-lg overflow-auto min-h-[400px]"><code>{
  "status": "waiting_for_input",
  "message": "Upload a PDF and select a customer to begin analysis."
}</code></pre>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            const customerId = document.getElementById('customerId');
            const promptId = document.getElementById('promptId');
            const promptContent = document.getElementById('promptContent');
            const uploadArea = document.getElementById('uploadArea');
            const pdfFile = document.getElementById('pdfFile');
            const selectFileBtn = document.getElementById('selectFileBtn');
            const selectedFile = document.getElementById('selectedFile');
            const selectedFileName = document.getElementById('selectedFileName');
            const removeFileBtn = document.getElementById('removeFileBtn');
            const analysisForm = document.getElementById('analysisForm');
            const analyzeBtn = document.getElementById('analyzeBtn');
            const jsonOutput = document.getElementById('jsonOutput');
            const loadingState = document.getElementById('loadingState');
            const copyBtn = document.getElementById('copyBtn');
            const downloadBtn = document.getElementById('downloadBtn');

            let selectedPdfFile = null;

            // Load prompts when customer is selected
            customerId.addEventListener('change', async function() {
                const customer_id = this.value;
                promptId.innerHTML = '<option value="">Loading...</option>';
                promptId.disabled = !customer_id;
                promptContent.value = '';

                if (customer_id) {
                    try {
                        const response = await fetch(`/api/customers/${customer_id}/prompts`);
                        const prompts = await response.json();

                        if (prompts.length === 0) {
                            promptId.innerHTML = '<option value="">No prompts found</option>';
                        } else {
                            promptId.innerHTML = '<option value="">Select Prompt</option>';
                        }

                        prompts.forEach(prompt => {
                            const option = document.createElement('option');
                            option.value = prompt.id;
                            option.textContent = prompt.name;
                            promptId.appendChild(option);
                        });
                    } catch (error) {
                        console.error('Error loading prompts:', error);
                        promptId.innerHTML = '<option value="">Not Found!</option>';
                    }
                } else {
                    promptId.innerHTML = '<option value="">Select Prompt</option>';
                }
            });

            // Load prompt content when prompt is selected
            promptId.addEventListener('change', async function() {
                const prompt_id = this.value;
                promptContent.value = '';
                // placeholder while loading
                promptContent.placeholder = 'Loading prompt content...';

                if (prompt_id) {
                    try {
                        const response = await fetch(`/api/prompts/${prompt_id}`);
                        const prompt = await response.json();
                        promptContent.value = prompt.content;
                        promptContent.readOnly = false;
                    } catch (error) {
                        console.error('Error loading prompt content:', error);
                        promptContent.placeholder = 'Not Found!';
                    }
                } else {
                    promptContent.placeholder = 'Select a prompt to view its content.';
                }
            });

            // File upload handling
            uploadArea.addEventListener('click', () => pdfFile.click());
            selectFileBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                pdfFile.click();
            });

            pdfFile.addEventListener('change', function() {
                if (this.files.length > 0) {
                    handleFileSelect(this.files[0]);
                }
            });

            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('border-blue-600', 'bg-slate-50');
            });

            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('border-blue-600', 'bg-slate-50');
            });

            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-600', 'bg-slate-50');
                const files = e.dataTransfer.files;
                if (files.length > 0 && files[0].type === 'application/pdf') {
                    handleFileSelect(files[0]);
                }
            });

            function handleFileSelect(file) {
                selectedPdfFile = file;
                selectedFileName.textContent = file.name;
                uploadArea.classList.add('hidden');
                selectedFile.classList.remove('hidden');
                selectedFile.classList.add('flex');
                updateAnalyzeButton();
            }

            removeFileBtn.addEventListener('click', () => {
                selectedPdfFile = null;
                pdfFile.value = '';
                uploadArea.classList.remove('hidden');
                selectedFile.classList.add('hidden');
                selectedFile.classList.remove('flex');
                updateAnalyzeButton();
            });

            function updateAnalyzeButton() {
                analyzeBtn.disabled = !selectedPdfFile || !customerId.value || !promptId.value;
            }

            customerId.addEventListener('change', updateAnalyzeButton);
            promptId.addEventListener('change', updateAnalyzeButton);

            // Form submission
            analysisForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                if (!selectedPdfFile || !customerId.value || !promptId.value) {
                    alert('Please select a customer, prompt, and upload a PDF file.');
                    return;
                }

                const formData = new FormData();
                formData.append('pdf', selectedPdfFile);
                formData.append('customer_id', customerId.value);
                formData.append('prompt_id', promptId.value);
                formData.append('prompt_content', promptContent.value);

                jsonOutput.innerHTML = '';
                loadingState.classList.remove('hidden');
                analyzeBtn.disabled = true;

                try {
                    const response = await fetch('/analysis/analyze', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: formData,
                    });

                    const data = await response.json();

                    loadingState.classList.add('hidden');
                    analyzeBtn.disabled = false;

                    if (data.success) {
                        const formattedJson = JSON.stringify(data.result, null, 2);
                        jsonOutput.innerHTML = `<code>${highlightJson(formattedJson)}</code>`;
                        copyBtn.disabled = false;
                        downloadBtn.disabled = false;
                    } else {
                        jsonOutput.innerHTML = `<code>{
  "error": "${data.message || 'Analysis failed'}"
}</code>`;
                    }
                } catch (error) {
                    loadingState.classList.add('hidden');
                    analyzeBtn.disabled = false;
                    jsonOutput.innerHTML = `<code>{
  "error": "An error occurred during analysis. Please try again."
}</code>`;
                }
            });

            // Copy functionality
            copyBtn.addEventListener('click', () => {
                navigator.clipboard.writeText(jsonOutput.textContent).then(() => {
                    copyBtn.innerHTML = '<span class="material-symbols-outlined text-sm text-emerald-600">check</span>';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<span class="material-symbols-outlined text-sm">content_copy</span>';
                    }, 2000);
                });
            });

            // Download functionality
            downloadBtn.addEventListener('click', () => {
                const blob = new Blob([jsonOutput.textContent], { type: 'application/json' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'analysis-result.json';
                a.click();
                URL.revokeObjectURL(url);
            });

            function highlightJson(json) {
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                    let cls = 'text-blue-300';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'text-emerald-400';
                        } else {
                            cls = 'text-blue-300';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'text-amber-400';
                    } else if (/null/.test(match)) {
                        cls = 'text-slate-400';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            }
        </script>
    @endpush
</x-admin-layout>
