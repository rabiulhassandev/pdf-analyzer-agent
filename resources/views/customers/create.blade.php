<x-admin-layout>
    <div class="space-y-6">
        <div class="flex flex-col gap-1">
            <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">Add Customer</h2>
            <p class="text-sm text-slate-500 leading-[1.6]">Register a new customer to the system.</p>
        </div>

        <div class="max-w-2xl">
            <form action="{{ route('customers.store') }}" method="POST" class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-all">
                        @error('name')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-all">
                        @error('email')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Phone</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Company</label>
                        <input type="text" name="company" value="{{ old('company') }}" class="w-full bg-white border border-slate-200 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label class="flex items-center gap-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-slate-700 font-medium">Active Customer</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-slate-200 flex justify-between items-center">
                        <a href="{{ route('customers.index') }}" class="text-slate-600 font-medium text-sm hover:text-slate-900">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition-all">
                            Save Customer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
