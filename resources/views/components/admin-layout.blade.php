<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'PDF Analyzer AI') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            .material-symbols-filled {
                font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }
            body { font-family: 'Inter', sans-serif; }
            .custom-scrollbar::-webkit-scrollbar { width: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
        </style>
        @stack('styles')
    </head>
    <body class="bg-background text-on-surface antialiased min-h-screen flex">
        @auth
            @if (auth()->check())
                <aside class="fixed left-0 top-0 h-full w-[280px] bg-white border-r border-slate-200 dark:border-slate-800 flex flex-col h-screen sticky top-0 z-50 font-inter antialiased text-sm font-medium">
                    <div class="p-6 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-[#131b2e] flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">analytics</span>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold tracking-tight text-slate-900">PDF Analyzer AI</h1>
                            <p class="text-[10px] text-slate-500 uppercase tracking-wider">Enterprise Suite</p>
                        </div>
                    </div>

                    @if (request()->routeIs('prompts.*') || request()->routeIs('customers.create'))
                        <div class="px-6 mt-4">
                            <button wire:click="openModal" class="w-full py-2.5 bg-[#0058be] text-white rounded-lg font-semibold flex items-center justify-center gap-2 shadow-sm hover:bg-[#004a9e] transition-all">
                                <span class="material-symbols-outlined">add</span>
                                New Entry
                            </button>
                        </div>
                    @endif

                    <nav class="flex-1 mt-6 px-4 space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ease-in-out {{ request()->routeIs('dashboard') ? 'bg-slate-100 text-[#0058be] border-r-4 border-[#0058be] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('dashboard') ? 'material-symbols-filled' : '' }}">dashboard</span>
                            <span class="text-sm font-semibold uppercase tracking-widest">Dashboard</span>
                        </a>

                        <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ease-in-out {{ request()->routeIs('customers.*') ? 'bg-slate-100 text-[#0058be] border-r-4 border-[#0058be] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('customers.*') ? 'material-symbols-filled' : '' }}">group</span>
                            <span class="text-sm font-semibold uppercase tracking-widest">Customers</span>
                        </a>

                        <a href="{{ route('prompts.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ease-in-out {{ request()->routeIs('prompts.*') ? 'bg-slate-100 text-[#0058be] border-r-4 border-[#0058be] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('prompts.*') ? 'material-symbols-filled' : '' }}">terminal</span>
                            <span class="text-sm font-semibold uppercase tracking-widest">Prompts</span>
                        </a>

                        <a href="{{ route('analysis.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ease-in-out {{ request()->routeIs('analysis.*') ? 'bg-slate-100 text-[#0058be] border-r-4 border-[#0058be] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('analysis.*') ? 'material-symbols-filled' : '' }}">description</span>
                            <span class="text-sm font-semibold uppercase tracking-widest">Analyzer</span>
                        </a>

                        <a href="{{ route('settings.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 ease-in-out {{ request()->routeIs('settings.*') ? 'bg-slate-100 text-[#0058be] border-r-4 border-[#0058be] font-semibold' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                            <span class="material-symbols-outlined {{ request()->routeIs('settings.*') ? 'material-symbols-filled' : '' }}">settings</span>
                            <span class="text-sm font-semibold uppercase tracking-widest">Settings</span>
                        </a>
                    </nav>

                    <div class="mt-auto p-4 border-t border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-[#131b2e] flex items-center justify-center text-white font-bold text-sm">
                                {{ mb_strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-500 uppercase tracking-tighter truncate">System Administrator</p>
                            </div>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="p-2 text-slate-400 hover:text-slate-600 transition-colors" title="Logout">
                                    <span class="material-symbols-outlined">logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </aside>
            @endif
        @endauth

        <main class="flex-1 min-w-0 flex flex-col">
            <header class="flex justify-between items-center w-full px-8 sticky top-0 z-40 h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm font-inter text-sm">
                <div class="flex items-center flex-1 max-w-xl">
                    <div class="relative w-full group">
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                        <input class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all" placeholder="Search..." type="text" id="globalSearch">
                    </div>
                </div>
                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-4 border-r border-slate-200 pr-6">
                        <button class="text-slate-500 hover:text-slate-900 transition-colors relative">
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                        </button>
                        <button class="text-slate-500 hover:text-slate-900 transition-colors">
                            <span class="material-symbols-outlined">help_outline</span>
                        </button>
                    </div>
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-[#131b2e] flex items-center justify-center text-white font-bold text-sm">
                            {{ mb_strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>
                    </a>
                </div>
            </header>

            <div class="p-8 space-y-6 custom-scrollbar overflow-auto flex-1">
                {{ $slot }}
            </div>
        </main>

        @stack('scripts')
    </body>
</html>
