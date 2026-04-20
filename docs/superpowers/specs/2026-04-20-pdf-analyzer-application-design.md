# PDF Analyzer Application Design

**Date:** 2026-04-20
**Project:** PDF Analyzer - Admin Dashboard with Customer CRUD and AI-Powered PDF Analysis

## Overview

A Laravel 13 application providing an admin dashboard for managing customers and analyzing PDF documents using AI (Claude Haiku via Laravel AI SDK). Features authentication via Laravel Breeze, customer management with active/inactive status, and AJAX-powered PDF analysis with temporary file storage and response caching.

## Architecture

### Technology Stack
- **Framework:** Laravel 13
- **PHP:** 8.4
- **Database:** MySQL
- **Frontend:** Blade templates, Tailwind CSS v4, Vanilla JavaScript
- **Testing:** Pest v4
- **AI:** Laravel AI v0.6 with Anthropic provider
- **Authentication:** Laravel Breeze (Blade stack)

### Pattern
- Traditional Laravel MVC
- Session-based authentication
- Temporary file storage for uploads (deleted after processing)
- Response caching for AI requests (60 seconds)
- API routes for AJAX operations, web routes for pages

### Middleware
- `auth` middleware on all admin routes
- `guest` middleware on auth routes (login, register)

## Database Schema

### New Table: `customers`

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| `id` | bigint | primary key, auto-increment | Unique identifier |
| `name` | string | required | Customer name |
| `email` | string | unique, required | Customer email |
| `phone` | string | nullable | Phone number |
| `company` | string | nullable | Company name |
| `notes` | text | nullable | Additional notes |
| `is_active` | boolean | default true, indexed | Active/inactive status |
| `created_at` | timestamp | - | Creation time |
| `updated_at` | timestamp | - | Last update time |

### Existing Tables (Unchanged)
- `users` - Authentication
- `sessions` - Session storage
- `password_reset_tokens` - Password resets
- `agent_conversations`, `agent_conversation_messages` - Laravel AI

### Seeded Data
- Admin user: `admin@pdfanalyzer.com` / `password`

## Routes

### Web Routes (Authenticated)
| Method | Path | Controller@Method | Description |
|--------|------|-------------------|-------------|
| GET | `/` | Redirect | To login or dashboard |
| GET | `/dashboard` | DashboardController@index | Dashboard home |
| GET | `/customers` | CustomerController@index | Customer list |
| GET | `/customers/create` | CustomerController@create | Create customer form |
| POST | `/customers` | CustomerController@store | Save new customer |
| GET | `/customers/{id}` | CustomerController@show | Customer details |
| GET | `/customers/{id}/edit` | CustomerController@edit | Edit customer form |
| PUT/PATCH | `/customers/{id}` | CustomerController@update | Update customer |
| DELETE | `/customers/{id}` | CustomerController@destroy | Delete customer |
| GET | `/analysis` | AnalysisController@index | PDF analysis form |

### API Routes (Authenticated, AJAX)
| Method | Path | Controller@Method | Description |
|--------|------|-------------------|-------------|
| POST | `/api/analysis` | AnalysisController@store | Process PDF, return JSON |

### Auth Routes (Breeze)
- `/login`, `/register`, `/password/reset`, `/email/verify`

## UI/Design

### Layout
- **Sidebar** (fixed left): Logo, navigation links, user info, logout
- **Main Content** (right of sidebar): Centered container for pages
- **Responsive**: Sidebar collapses to hamburger menu on mobile

### Color Palette
- Primary: Slate/gray scale
- Accent: Subtle blue/indigo for actions
- Success: Green for confirmations
- Error: Red for validation/errors

### Components
- **Cards**: White background, subtle shadow, rounded corners
- **Buttons**: Solid primary, outlined secondary, hover states
- **Forms**: Clean inputs, labels, inline error messages
- **Tables**: Bordered rows, hover highlight, action column

### Navigation
- Dashboard (`/dashboard`)
- Customers (`/customers`)
- PDF Analysis (`/analysis`)
- Logout (`/logout`)

## PDF Analysis Flow (AJAX)

### Frontend Behavior
1. User selects PDF file and customer from dropdown
2. Click submit → validate client-side
3. Show loading spinner, disable submit button
4. POST `FormData` to `/api/analysis`
5. **Success**: Display result card with customer name and analysis
6. **Error**: Show inline error message, re-enable button
7. Cleanup loading state

### Backend Processing (AnalysisController@store)
1. Validate: `file` (pdf, max:10240kb), `customer_id` (exists)
2. Store file temporarily via `store('temp')`
3. Generate cache key: `pdf_analysis:{md5_file}:{customer_id}`
4. Check cache → if hit, return cached response
5. If miss: Call PdfAnalyzer with `Document::fromPath(temp_path)`
6. Cache response for 60 seconds
7. Delete temporary file
8. Return JSON: `{ success, customer_name, analysis }`

### JSON Responses
**Success (200):**
```json
{
  "success": true,
  "customer_name": "John Doe",
  "analysis": "Analysis text here..."
}
```

**Error (422/500):**
```json
{
  "success": false,
  "message": "Error description..."
}
```

## Controllers

### DashboardController
- `index()`: Display dashboard landing page

### CustomerController
- Full CRUD with validation:
  - `name`: required
  - `email`: required, unique, email format
  - `phone`, `company`, `notes`: optional

### AnalysisController
- `index()`: Display analysis form page
- `store()`: Process PDF upload, call AI, return JSON

## Models

### Customer Model
- Attributes: `$fillable` for all columns
- Scope: `active()` for filtering `is_active = true`
- Factory with fake data generation

## File Storage Strategy

- PDFs stored in `storage/app/temp/`
- Deleted immediately after analysis
- No persistent file storage required
- Cache handles response persistence (60s)

## AI Integration

Reuse existing `App\Ai\Agents\PdfAnalyzer`:
- Model: `claude-haiku-4-5-20251001`
- Attachments via `Laravel\Ai\Files\Document::fromPath()`
- Caching: `Cache::remember()` with unique key

## Testing Strategy

### Pest Tests
- `CustomerControllerTest`: All CRUD operations
- `AnalysisControllerTest`: API endpoint with valid/invalid inputs
- `CustomerModelTest`: Relationships, active scope, factory
- Authentication flows via Breeze

### Manual Testing
- Login/logout flows
- Customer CRUD operations
- PDF analysis with various file sizes
- Mobile responsiveness
- Error handling (invalid files, missing customer, API errors)

## Cleanup Tasks

1. Remove test routes: `/test`, `/test-cache`
2. Remove welcome page route
3. Remove `public/sample.pdf` (if exists)

## Implementation Order

1. Install Laravel Breeze (Blade stack)
2. Configure auth routes and middleware
3. Seed admin user
4. Create Customer model, migration, factory, controller
5. Build Customer CRUD views and routes
6. Create AnalysisController and API route
7. Build PDF analysis form with AJAX
8. Create dashboard layout with sidebar
9. Update routing and cleanup test routes
10. Write tests
11. Run Pint formatting
12. Build frontend assets
