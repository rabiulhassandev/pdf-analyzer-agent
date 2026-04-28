<x-admin-layout>
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="text-amber-600 hover:text-amber-700 font-medium">
            &larr; Back to Customers
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Details -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg border border-gray-200 p-8 mb-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $customer->name }}</h1>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Email</p>
                        <p class="text-lg text-gray-900 mt-1">
                            <a href="mailto:{{ $customer->email }}" class="text-amber-600 hover:text-amber-700">
                                {{ $customer->email }}
                            </a>
                        </p>
                    </div>

                    @if ($customer->phone)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Phone</p>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->phone }}</p>
                        </div>
                    @endif

                    @if ($customer->company)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Company</p>
                            <p class="text-lg text-gray-900 mt-1">{{ $customer->company }}</p>
                        </div>
                    @endif

                    @if ($customer->prompt)
                        <div>
                            <p class="text-sm font-medium text-gray-600">Prompt</p>
                            <p class="text-base text-gray-900 mt-1 whitespace-pre-wrap">{{ $customer->prompt }}</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-500">
                            Created: {{ $customer->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="space-y-3">
            <a href="{{ route('customers.edit', $customer) }}" class="w-full block text-center px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors">
                Edit
            </a>
            <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                @csrf
                @method('DELETE')
                <button class="w-full px-4 py-2 bg-amber-600 text-white rounded-lg font-medium hover:bg-amber-700 transition-colors">
                    Delete
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
