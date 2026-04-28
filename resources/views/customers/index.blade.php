<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-[30px] font-semibold text-slate-900 leading-[1.3]">Customers</h2>
                <p class="text-sm text-slate-500 leading-[1.6] mt-1">Manage customer accounts and their documents.</p>
            </div>
            <a href="{{ route('customers.create') }}" class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg flex items-center gap-2 hover:bg-blue-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">person_add</span>
                Add Customer
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Prompts</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-slate-900">{{ $customer->company ?? $customer->name }}</div>
                                <div class="text-xs text-slate-400">ID: {{ $customer->id }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <div>{{ $customer->email }}</div>
                                <div class="text-xs text-slate-400">{{ $customer->phone }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $customer->is_active ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-slate-100 text-slate-600' }}">
                                    {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $customer->prompts()->count() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center gap-2 justify-end">
                                    <a href="{{ route('customers.edit', $customer) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="Edit">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                            <span class="material-symbols-outlined">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                <span class="material-symbols-outlined text-5xl mb-4 text-slate-300">group</span>
                                <p class="text-sm">No customers found</p>
                                <a href="{{ route('customers.create') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700">
                                    Add First Customer
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if ($customers->hasPages())
                <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-200 flex items-center justify-center">
                    {{ $customers->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</x-admin-layout>
