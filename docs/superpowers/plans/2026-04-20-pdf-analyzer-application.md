# PDF Analyzer Application Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Build a Laravel 13 admin dashboard with customer CRUD management and AI-powered PDF analysis using Laravel Breeze authentication.

**Architecture:** Traditional Laravel MVC with Blade templates, Tailwind CSS v4, session-based authentication, temporary file storage for PDF uploads, and 60-second response caching for AI requests.

**Tech Stack:** Laravel 13, PHP 8.4, MySQL, Tailwind CSS v4, Pest v4, Laravel AI v0.6, Laravel Breeze (Blade stack)

---

## File Structure

### New Files to Create
- `database/migrations/2026_04_20_XXXXXX_create_customers_table.php` - Customers table migration
- `app/Models/Customer.php` - Customer Eloquent model
- `database/factories/CustomerFactory.php` - Customer factory for testing
- `app/Http/Controllers/DashboardController.php` - Dashboard controller
- `app/Http/Controllers/CustomerController.php` - Customer CRUD controller
- `app/Http/Controllers/AnalysisController.php` - PDF analysis controller
- `database/seeders/AdminUserSeeder.php` - Admin user seeder
- `resources/views/layouts/app.blade.php` - Main layout with sidebar
- `resources/views/dashboard/index.blade.php` - Dashboard landing page
- `resources/views/customers/index.blade.php` - Customer list view
- `resources/views/customers/create.blade.php` - Create customer form
- `resources/views/customers/edit.blade.php` - Edit customer form
- `resources/views/customers/show.blade.php` - Customer details view
- `resources/views/analysis/index.blade.php` - PDF analysis form with AJAX
- `tests/Feature/CustomerControllerTest.php` - Customer controller tests
- `tests/Feature/AnalysisControllerTest.php` - Analysis controller tests
- `tests/Unit/CustomerModelTest.php` - Customer model tests

### Files to Modify
- `routes/web.php` - Replace test routes with full application routes
- `database/seeders/DatabaseSeeder.php` - Add AdminUserSeeder call
- `resources/css/app.css` - May need minor adjustments for custom components

---

## Task 1: Install Laravel Breeze

**Files:**
- Modify: `composer.json` (via composer command)

- [ ] **Step 1: Install Laravel Breeze package**

Run: `composer require laravel/breeze --dev --with-all-dependencies`
Expected: Package installed successfully

- [ ] **Step 2: Install Breeze Blade stack**

Run: `php artisan breeze:install blade --no-interaction`
Expected: Breeze installed, views and routes created

- [ ] **Step 3: Install and build frontend dependencies**

Run: `npm install && npm run build`
Expected: Dependencies installed, assets built

- [ ] **Step 4: Run migrations for Breeze tables**

Run: `php artisan migrate --force`
Expected: Migrations completed successfully

- [ ] **Step 5: Commit**

```bash
git add .
git commit -m "feat: install Laravel Breeze (Blade stack)"
```

---

## Task 2: Create Customers Migration

**Files:**
- Create: `database/migrations/2026_04_20_XXXXXX_create_customers_table.php`

- [ ] **Step 1: Generate migration**

Run: `php artisan make:migration create_customers_table --no-interaction`
Expected: Migration file created in database/migrations/

- [ ] **Step 2: Write migration code**

Find the created migration file (latest with create_customers_table) and replace contents:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
```

- [ ] **Step 3: Run migration**

Run: `php artisan migrate --force`
Expected: Migration successful, customers table created

- [ ] **Step 4: Commit**

```bash
git add database/migrations/
git commit -m "feat: create customers table migration"
```

---

## Task 3: Create Customer Model and Factory

**Files:**
- Create: `app/Models/Customer.php`
- Create: `database/factories/CustomerFactory.php`

- [ ] **Step 1: Generate Customer model, factory, and controller**

Run: `php artisan make:model Customer -mfc --no-interaction`
Expected: Model, factory, and controller created (migration already done in Task 2)

- [ ] **Step 2: Write Customer model**

Replace contents of `app/Models/Customer.php`:

```php
<?php

namespace App\Models;

use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

#[Fillable(['name', 'email', 'phone', 'company', 'notes', 'is_active'])]
class Customer extends Model
{
    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
```

- [ ] **Step 3: Write Customer factory**

Replace contents of `database/factories/CustomerFactory.php`:

```php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'company' => fake()->company(),
            'notes' => fake()->paragraph(),
            'is_active' => true,
        ];
    }
}
```

- [ ] **Step 4: Commit**

```bash
git add app/Models/Customer.php database/factories/CustomerFactory.php
git commit -m "feat: create Customer model and factory"
```

---

## Task 4: Create Admin User Seeder

**Files:**
- Create: `database/seeders/AdminUserSeeder.php`
- Modify: `database/seeders/DatabaseSeeder.php`

- [ ] **Step 1: Generate seeder**

Run: `php artisan make:seeder AdminUserSeeder --no-interaction`
Expected: Seeder file created

- [ ] **Step 2: Write seeder code**

Replace contents of `database/seeders/AdminUserSeeder.php`:

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@pdfanalyzer.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@pdfanalyzer.com',
                'password' => Hash::make('password'),
            ]
        );
    }
}
```

- [ ] **Step 3: Update DatabaseSeeder to call AdminUserSeeder**

Replace contents of `database/seeders/DatabaseSeeder.php`:

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
        ]);
    }
}
```

- [ ] **Step 4: Run seeder**

Run: `php artisan db:seed --force`
Expected: Seeder completed, admin user created

- [ ] **Step 5: Commit**

```bash
git add database/seeders/
git commit -m "feat: create admin user seeder"
```

---

## Task 5: Create Controllers

**Files:**
- Create: `app/Http/Controllers/DashboardController.php`
- Modify: `app/Http/Controllers/CustomerController.php` (replace default)
- Create: `app/Http/Controllers/AnalysisController.php`

- [ ] **Step 1: Create DashboardController**

Run: `php artisan make:controller DashboardController --no-interaction`
Expected: Controller created

Replace contents of `app/Http/Controllers/DashboardController.php`:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $customerCount = \App\Models\Customer::count();
        $activeCustomerCount = \App\Models\Customer::active()->count();

        return view('dashboard.index', compact('customerCount', 'activeCustomerCount'));
    }
}
```

- [ ] **Step 2: Write CustomerController**

Replace contents of `app/Http/Controllers/CustomerController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(): View
    {
        $customers = Customer::latest()->paginate(10);

        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email,'.$customer->id,
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
```

- [ ] **Step 3: Create AnalysisController**

Run: `php artisan make:controller AnalysisController --no-interaction`
Expected: Controller created

Replace contents of `app/Http/Controllers/AnalysisController.php`:

```php
<?php

namespace App\Http\Controllers;

use App\Ai\Agents\PdfAnalyzer;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Laravel\Ai\Files\Document;

class AnalysisController extends Controller
{
    /**
     * Display the analysis form.
     */
    public function index(): View
    {
        $customers = Customer::active()->get();

        return view('analysis.index', compact('customers'));
    }

    /**
     * Process the PDF analysis.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf|max:10240',
            'customer_id' => 'required|exists:customers,id',
        ]);

        $customer = Customer::findOrFail($validated['customer_id']);
        $file = $request->file('file');

        // Store file temporarily
        $tempPath = $file->store('temp');
        $fullPath = Storage::path($tempPath);
        $fileHash = md5_file($fullPath);

        // Generate cache key
        $cacheKey = "pdf_analysis:{$fileHash}:{$customer->id}";

        // Check cache first
        $cachedResult = Cache::get($cacheKey);
        if ($cachedResult) {
            // Clean up temp file
            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'customer_name' => $customer->name,
                'analysis' => $cachedResult,
                'cached' => true,
            ]);
        }

        try {
            // Call the PDF analyzer agent
            $response = (new PdfAnalyzer)->prompt(
                'Analyze this PDF document and provide a comprehensive summary. Extract key information, main topics, and any important data points.',
                model: 'claude-haiku-4-5-20251001',
                attachments: [
                    Document::fromPath($fullPath),
                ]
            );

            $analysisText = $response->text;

            // Cache the result for 60 seconds
            Cache::put($cacheKey, $analysisText, 60);

            // Clean up temp file
            Storage::delete($tempPath);

            return response()->json([
                'success' => true,
                'customer_name' => $customer->name,
                'analysis' => $analysisText,
                'cached' => false,
            ]);

        } catch (\Exception $e) {
            // Clean up temp file on error
            Storage::delete($tempPath);

            return response()->json([
                'success' => false,
                'message' => 'Analysis failed. Please try again.',
            ], 500);
        }
    }
}
```

- [ ] **Step 4: Run Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: Code formatted according to project standards

- [ ] **Step 5: Commit**

```bash
git add app/Http/Controllers/
git commit -m "feat: create Dashboard, Customer, and Analysis controllers"
```

---

## Task 6: Create Main Layout with Sidebar

**Files:**
- Create: `resources/views/layouts/app.blade.php`

- [ ] **Step 1: Create layout directory**

Run: `mkdir -p resources/views/layouts`
Expected: Directory created

- [ ] **Step 2: Write main layout with sidebar**

Create `resources/views/layouts/app.blade.php`:

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PDF Analyzer') }} - {{ isset($title) ? $title : 'Dashboard' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 text-white flex flex-col fixed h-full">
            <!-- Logo -->
            <div class="p-6 border-b border-slate-700">
                <h1 class="text-xl font-bold">PDF Analyzer</h1>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('dashboard') ? 'bg-slate-700' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('customers.index') }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('customers.*') ? 'bg-slate-700' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Customers
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('analysis.index') }}"
                           class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-slate-700 transition {{ request()->routeIs('analysis.*') ? 'bg-slate-700' : '' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            PDF Analysis
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-slate-700">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center text-sm font-medium">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg transition text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/layouts/
git commit -m "feat: create main layout with sidebar navigation"
```

---

## Task 7: Create Dashboard View

**Files:**
- Create: `resources/views/dashboard/index.blade.php`

- [ ] **Step 1: Create dashboard directory and view**

Run: `mkdir -p resources/views/dashboard`
Expected: Directory created

Create `resources/views/dashboard/index.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome to PDF Analyzer</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Customers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $customerCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Active Customers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $activeCustomerCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="{{ route('customers.create') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Add New Customer</p>
                    <p class="text-sm text-gray-600">Create a customer profile</p>
                </div>
            </a>

            <a href="{{ route('analysis.index') }}" class="flex items-center gap-3 p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Analyze PDF</p>
                    <p class="text-sm text-gray-600">Upload and analyze a PDF document</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 2: Commit**

```bash
git add resources/views/dashboard/
git commit -m "feat: create dashboard view with stats and quick actions"
```

---

## Task 8: Create Customer Views

**Files:**
- Create: `resources/views/customers/index.blade.php`
- Create: `resources/views/customers/create.blade.php`
- Create: `resources/views/customers/edit.blade.php`
- Create: `resources/views/customers/show.blade.php`

- [ ] **Step 1: Create customers directory**

Run: `mkdir -p resources/views/customers`
Expected: Directory created

- [ ] **Step 2: Create customer index view**

Create `resources/views/customers/index.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Customers</h1>
        <a href="{{ route('customers.create') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
            Add Customer
        </a>
    </div>

    @if (session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $customer->name }}</div>
                        <div class="text-sm text-gray-500">{{ $customer->phone ?? '-' }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->company ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($customer->is_active)
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                        @else
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <a href="{{ route('customers.show', $customer) }}" class="text-blue-600 hover:text-blue-800 mr-3">View</a>
                        <a href="{{ route('customers.edit', $customer) }}" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete this customer?')"
                                    class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{ $customers->links() }}
    </div>
</div>
@endsection
```

- [ ] **Step 3: Create customer create view**

Create `resources/views/customers/create.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Add New Customer</h1>
        <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-800">← Back to customers</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 max-w-2xl">
        <form method="POST" action="{{ route('customers.store') }}">
            @csrf

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" id="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('name') }}">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('email') }}">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('phone') }}">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" id="company"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('company') }}">
                    @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" checked
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Save Customer
                    </button>
                    <a href="{{ route('customers.index') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
```

- [ ] **Step 4: Create customer edit view**

Create `resources/views/customers/edit.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Edit Customer</h1>
        <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-800">← Back to customers</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 max-w-2xl">
        <form method="POST" action="{{ route('customers.update', $customer) }}">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" name="name" id="name" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('name', $customer->name) }}">
                    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('email', $customer->email) }}">
                    @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="phone" id="phone"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('phone', $customer->phone) }}">
                    @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                    <input type="text" name="company" id="company"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ old('company', $customer->company) }}">
                    @error('company') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $customer->notes) }}</textarea>
                    @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                           {{ old('is_active', $customer->is_active) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-2 text-sm text-gray-700">Active</label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Update Customer
                    </button>
                    <a href="{{ route('customers.index') }}"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
```

- [ ] **Step 5: Create customer show view**

Create `resources/views/customers/show.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Customer Details</h1>
        <a href="{{ route('customers.index') }}" class="text-blue-600 hover:text-blue-800">← Back to customers</a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-2xl">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">{{ $customer->name }}</h2>
                @if($customer->is_active)
                <span class="px-3 py-1 text-sm font-medium bg-green-100 text-green-800 rounded-full">Active</span>
                @else
                <span class="px-3 py-1 text-sm font-medium bg-gray-100 text-gray-800 rounded-full">Inactive</span>
                @endif
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Email</p>
                <p class="text-gray-900">{{ $customer->email }}</p>
            </div>

            @if($customer->phone)
            <div>
                <p class="text-sm text-gray-600 mb-1">Phone</p>
                <p class="text-gray-900">{{ $customer->phone }}</p>
            </div>
            @endif

            @if($customer->company)
            <div>
                <p class="text-sm text-gray-600 mb-1">Company</p>
                <p class="text-gray-900">{{ $customer->company }}</p>
            </div>
            @endif

            @if($customer->notes)
            <div>
                <p class="text-sm text-gray-600 mb-1">Notes</p>
                <p class="text-gray-900 whitespace-pre-wrap">{{ $customer->notes }}</p>
            </div>
            @endif

            <div class="pt-4 border-t border-gray-200 flex gap-3">
                <a href="{{ route('customers.edit', $customer) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Edit Customer
                </a>
                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            onclick="return confirm('Are you sure you want to delete this customer?')"
                            class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
```

- [ ] **Step 6: Commit**

```bash
git add resources/views/customers/
git commit -m "feat: create customer CRUD views"
```

---

## Task 9: Create Analysis View with AJAX

**Files:**
- Create: `resources/views/analysis/index.blade.php`

- [ ] **Step 1: Create analysis directory**

Run: `mkdir -p resources/views/analysis`
Expected: Directory created

- [ ] **Step 2: Create analysis form view with AJAX**

Create `resources/views/analysis/index.blade.php`:

```blade
@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">PDF Analysis</h1>
        <p class="text-gray-600">Upload a PDF document to analyze its content using AI.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 max-w-2xl">
        <form id="analysisForm" class="space-y-4">
            @csrf

            <div>
                <label for="customer_id" class="block text-sm font-medium text-gray-700 mb-1">Customer *</label>
                <select name="customer_id" id="customer_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select a customer...</option>
                    @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->company ?? 'No company' }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="file" class="block text-sm font-medium text-gray-700 mb-1">PDF File *</label>
                <input type="file" name="file" id="file" accept=".pdf" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p class="mt-1 text-sm text-gray-500">Maximum file size: 10MB</p>
            </div>

            <button type="submit" id="submitBtn"
                    class="w-full px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                Analyze PDF
            </button>
        </form>

        <!-- Loading State -->
        <div id="loadingState" class="hidden mt-6">
            <div class="flex items-center justify-center gap-3">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-700">Analyzing PDF...</span>
            </div>
        </div>

        <!-- Error Message -->
        <div id="errorMessage" class="hidden mt-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg"></div>

        <!-- Result Card -->
        <div id="resultCard" class="hidden mt-6 border border-gray-200 rounded-lg overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">Analysis Result</h3>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Customer</p>
                    <p id="resultCustomer" class="font-medium text-gray-900"></p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-2">Analysis</p>
                    <div id="resultAnalysis" class="bg-gray-50 p-4 rounded-lg text-gray-800 whitespace-pre-wrap"></div>
                </div>
                @if(session('cached'))
                <p class="mt-2 text-sm text-blue-600">* Result retrieved from cache</p>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analysisForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingState = document.getElementById('loadingState');
    const errorMessage = document.getElementById('errorMessage');
    const resultCard = document.getElementById('resultCard');
    const resultCustomer = document.getElementById('resultCustomer');
    const resultAnalysis = document.getElementById('resultAnalysis');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Reset states
        errorMessage.classList.add('hidden');
        resultCard.classList.add('hidden');
        loadingState.classList.remove('hidden');
        submitBtn.disabled = true;

        const formData = new FormData(form);

        try {
            const response = await fetch('{{ route('analysis.store') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                // Show result
                resultCustomer.textContent = data.customer_name;
                resultAnalysis.textContent = data.analysis;
                resultCard.classList.remove('hidden');

                // Reset form
                form.reset();
            } else {
                // Show error
                errorMessage.textContent = data.message || 'Analysis failed. Please try again.';
                errorMessage.classList.remove('hidden');
            }
        } catch (error) {
            console.error('Error:', error);
            errorMessage.textContent = 'An error occurred. Please try again.';
            errorMessage.classList.remove('hidden');
        } finally {
            loadingState.classList.add('hidden');
            submitBtn.disabled = false;
        }
    });
});
</script>
@endpush
@endsection
```

- [ ] **Step 3: Commit**

```bash
git add resources/views/analysis/
git commit -m "feat: create PDF analysis form with AJAX submission"
```

---

## Task 10: Update Routes

**Files:**
- Modify: `routes/web.php`

- [ ] **Step 1: Replace routes/web.php contents**

Replace entire contents of `routes/web.php`:

```php
<?php

use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Redirect root based on auth status
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

// Auth routes (Breeze)
require __DIR__.'/auth.php';

// Protected routes - require authentication
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::resource('customers', CustomerController::class);

    // PDF Analysis
    Route::get('/analysis', [AnalysisController::class, 'index'])->name('analysis.index');
    Route::post('/api/analysis', [AnalysisController::class, 'store'])->name('analysis.store');
});
```

- [ ] **Step 2: Run Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: Code formatted

- [ ] **Step 3: Commit**

```bash
git add routes/web.php
git commit -m "feat: update routes with full application routing"
```

---

## Task 11: Create Customer Model Tests

**Files:**
- Create: `tests/Unit/CustomerModelTest.php`

- [ ] **Step 1: Generate unit test**

Run: `php artisan make:test CustomerModelTest --unit --no-interaction`
Expected: Test file created

- [ ] **Step 2: Write Customer model tests**

Replace contents of `tests/Unit/CustomerModelTest.php`:

```php
<?php

use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a customer', function () {
    $customer = Customer::factory()->create();

    expect($customer->id)->toBeInt();
    expect($customer->name)->not->toBeEmpty();
    expect($customer->email)->not->toBeEmpty();
});

it('has fillable attributes', function () {
    $customer = Customer::create([
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'notes' => 'Test notes',
        'is_active' => true,
    ]);

    expect($customer->name)->toBe('Test Customer');
    expect($customer->email)->toBe('test@example.com');
    expect($customer->phone)->toBe('123-456-7890');
    expect($customer->company)->toBe('Test Company');
    expect($customer->notes)->toBe('Test notes');
    expect($customer->is_active)->toBeTrue();
});

it('can filter active customers', function () {
    Customer::factory()->create(['is_active' => true]);
    Customer::factory()->create(['is_active' => false]);
    Customer::factory()->create(['is_active' => true]);

    $activeCustomers = Customer::active()->get();

    expect($activeCustomers)->toHaveCount(2);
    expect($activeCustomers->every->is_active)->toBeTrue();
});

it('enforces unique email', function () {
    Customer::factory()->create(['email' => 'duplicate@example.com']);

    expect(fn () => Customer::factory()->create(['email' => 'duplicate@example.com']))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
```

- [ ] **Step 3: Run tests**

Run: `php artisan test --compact --filter=CustomerModelTest`
Expected: All tests pass

- [ ] **Step 4: Run Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: Code formatted

- [ ] **Step 5: Commit**

```bash
git add tests/Unit/CustomerModelTest.php
git commit -m "test: add Customer model unit tests"
```

---

## Task 12: Create Customer Controller Tests

**Files:**
- Create: `tests/Feature/CustomerControllerTest.php`

- [ ] **Step 1: Generate feature test**

Run: `php artisan make:test CustomerControllerTest --no-interaction`
Expected: Test file created

- [ ] **Step 2: Write Customer controller tests**

Replace contents of `tests/Feature/CustomerControllerTest.php`:

```php
<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs(User::first());
});

it('displays customers index page', function () {
    Customer::factory()->count(5)->create();

    $response = $this->get('/customers');

    $response->assertStatus(200);
    $response->assertViewIs('customers.index');
    $response->assertViewHas('customers');
});

it('displays create customer form', function () {
    $response = $this->get('/customers/create');

    $response->assertStatus(200);
    $response->assertViewIs('customers.create');
});

it('stores a new customer', function () {
    $response = $this->post('/customers', [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
        'phone' => '123-456-7890',
        'company' => 'Test Company',
        'notes' => 'Test notes',
        'is_active' => true,
    ]);

    $response->assertRedirect('/customers');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('customers', [
        'name' => 'Test Customer',
        'email' => 'test@example.com',
    ]);
});

it('validates required fields when storing customer', function () {
    $response = $this->post('/customers', []);

    $response->assertSessionHasErrors(['name', 'email']);
});

it('displays customer details', function () {
    $customer = Customer::factory()->create();

    $response = $this->get("/customers/{$customer->id}");

    $response->assertStatus(200);
    $response->assertViewIs('customers.show');
    $response->assertViewHas('customer', function ($viewCustomer) use ($customer) {
        return $viewCustomer->id === $customer->id;
    });
});

it('displays edit customer form', function () {
    $customer = Customer::factory()->create();

    $response = $this->get("/customers/{$customer->id}/edit");

    $response->assertStatus(200);
    $response->assertViewIs('customers.edit');
});

it('updates a customer', function () {
    $customer = Customer::factory()->create();

    $response = $this->put("/customers/{$customer->id}", [
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
        'is_active' => false,
    ]);

    $response->assertRedirect('/customers');
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Updated Name',
        'email' => 'updated@example.com',
    ]);
});

it('deletes a customer', function () {
    $customer = Customer::factory()->create();

    $response = $this->delete("/customers/{$customer->id}");

    $response->assertRedirect('/customers');
    $response->assertSessionHas('success');

    $this->assertDatabaseMissing('customers', [
        'id' => $customer->id,
    ]);
});

it('requires authentication for customer routes', function () {
    auth()->logout();

    $response = $this->get('/customers');
    $response->assertRedirect('/login');
});
```

- [ ] **Step 3: Run tests**

Run: `php artisan test --compact --filter=CustomerControllerTest`
Expected: All tests pass

- [ ] **Step 4: Run Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: Code formatted

- [ ] **Step 5: Commit**

```bash
git add tests/Feature/CustomerControllerTest.php
git commit -m "test: add Customer controller feature tests"
```

---

## Task 13: Create Analysis Controller Tests

**Files:**
- Create: `tests/Feature/AnalysisControllerTest.php`

- [ ] **Step 1: Generate feature test**

Run: `php artisan make:test AnalysisControllerTest --no-interaction`
Expected: Test file created

- [ ] **Step 2: Write Analysis controller tests**

Replace contents of `tests/Feature/AnalysisControllerTest.php`:

```php
<?php

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password'),
    ]);

    $this->actingAs(User::first());
    Storage::fake('local');
});

it('displays analysis form page', function () {
    Customer::factory()->count(3)->create(['is_active' => true]);
    Customer::factory()->create(['is_active' => false]);

    $response = $this->get('/analysis');

    $response->assertStatus(200);
    $response->assertViewIs('analysis.index');
    $response->assertViewHas('customers', function ($customers) {
        return $customers->count() === 3;
    });
});

it('requires authentication for analysis routes', function () {
    auth()->logout();

    $response = $this->get('/analysis');
    $response->assertRedirect('/login');
});

it('validates required fields for analysis', function () {
    $response = $this->post('/api/analysis', [], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['file', 'customer_id']);
});

it('validates file type and size', function () {
    $customer = Customer::factory()->create(['is_active' => true]);

    // Wrong file type
    $file = UploadedFile::fake()->create('document.txt', 100);

    $response = $this->post('/api/analysis', [
        'file' => $file,
        'customer_id' => $customer->id,
    ], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['file']);
});

it('validates customer exists', function () {
    $file = UploadedFile::fake()->create('document.pdf', 1000);

    $response = $this->post('/api/analysis', [
        'file' => $file,
        'customer_id' => 999,
    ], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['customer_id']);
});

it('validates file size limit (10MB)', function () {
    $customer = Customer::factory()->create(['is_active' => true]);

    $file = UploadedFile::fake()->create('document.pdf', 12000); // 12MB

    $response = $this->post('/api/analysis', [
        'file' => $file,
        'customer_id' => $customer->id,
    ], [
        'Accept' => 'application/json',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['file']);
});
```

- [ ] **Step 3: Run tests**

Run: `php artisan test --compact --filter=AnalysisControllerTest`
Expected: All tests pass

- [ ] **Step 4: Run Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: Code formatted

- [ ] **Step 5: Commit**

```bash
git add tests/Feature/AnalysisControllerTest.php
git commit -m "test: add Analysis controller feature tests"
```

---

## Task 14: Build Frontend Assets

**Files:**
- Modify: `resources/css/app.css` (if needed)

- [ ] **Step 1: Build frontend assets**

Run: `npm run build`
Expected: Assets built successfully

- [ ] **Step 2: Commit**

```bash
git add resources/css/app.css resources/js/app.js public/build/
git commit -m "build: compile frontend assets"
```

---

## Task 15: Cleanup Test Routes and Files

**Files:**
- Modify: `routes/web.php` (already done in Task 10, this is verification)

- [ ] **Step 1: Verify test routes are removed**

Check that `/test` and `/test-cache` routes no longer exist:

Run: `php artisan route:list --except-vendor`
Expected: Only application routes shown, no test routes

- [ ] **Step 2: Remove sample.pdf if exists**

Run: `rm -f public/sample.pdf`
Expected: File removed (or no error if doesn't exist)

- [ ] **Step 3: Run all tests to verify everything works**

Run: `php artisan test --compact`
Expected: All tests pass

- [ ] **Step 4: Final Pint formatting**

Run: `vendor/bin/pint --dirty --format agent`
Expected: All PHP files formatted

- [ ] **Step 5: Final commit**

```bash
git add .
git commit -m "chore: cleanup test files and finalize implementation"
```

---

## Task 16: Verification and Documentation

**Files:**
- None (verification steps)

- [ ] **Step 1: Verify all routes are accessible**

Run: `php artisan route:list --except-vendor`
Expected: Shows all expected routes (dashboard, customers, analysis, auth)

- [ ] **Step 2: Verify database schema**

Run: `php artisan db:show pdf_analyzer`
Expected: Shows all tables including customers

- [ ] **Step 3: Run complete test suite**

Run: `php artisan test`
Expected: All tests pass

- [ ] **Step 4: Check for any remaining formatting issues**

Run: `vendor/bin/pint --test --format agent`
Expected: No formatting errors

- [ ] **Step 5: Verify admin user exists**

Run: `php artisan tinker --execute 'echo User::where("email", "admin@pdfanalyzer.com")->exists();'`
Expected: Output is `1` (true)

- [ ] **Step 6: Final commit for verification**

```bash
git commit --allow-empty -m "chore: verify implementation complete and all tests pass"
```

---

## Final Steps Summary

After completing all tasks:

1. Login to application at `http://localhost:8000/login` using:
   - Email: `admin@pdfanalyzer.com`
   - Password: `password`

2. Test the following manually:
   - Dashboard displays correctly with stats
   - Customer CRUD operations work
   - PDF analysis form submits and displays results
   - Navigation and logout work correctly
   - Mobile responsiveness is acceptable

3. Ensure environment variables are set in `.env`:
   - `ANTHROPIC_API_KEY` - Required for PDF analysis
   - `APP_URL`, `APP_ENV`, `DB_*` - Standard Laravel config
