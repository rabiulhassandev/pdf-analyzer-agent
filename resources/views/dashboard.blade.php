<x-app-layout>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Quick Stats -->
        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Customer::count() }}</p>
                </div>
                <div class="bg-blue-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 8.048M12 4.354L9.172 9.354M12 4.354l2.828 5M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Active Customers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ \App\Models\Customer::active()->count() }}</p>
                </div>
                <div class="bg-green-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Ready to Analyze</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">Yes</p>
                    <p class="text-xs text-gray-500 mt-1">Start analyzing PDFs</p>
                </div>
                <div class="bg-purple-50 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Welcome Section -->
    <div class="bg-white rounded-lg border border-gray-200 p-8">
        <div class="text-center">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Welcome, {{ Auth::user()->name }}!</h3>
            <p class="text-gray-600 mb-6">Manage your customers and analyze PDF documents efficiently.</p>
            
            <div class="flex gap-4 justify-center">
                <a href="{{ route('customers.index') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                    View Customers
                </a>
                <a href="{{ route('analysis.index') }}" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors">
                    Analyze PDF
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
