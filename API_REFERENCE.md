# API & Routes Reference

## Application Routes

### Authentication Routes (Breeze)
All routes provided by Laravel Breeze. Unauthenticated users redirected to `/login`.

```
GET|HEAD   /login                          login
POST       /login                          authenticate
GET|HEAD   /register                       show registration form
POST       /register                       register
GET|HEAD   /forgot-password                password reset request form
POST       /forgot-password                send password reset link
GET|HEAD   /reset-password/{token}         reset password form
POST       /reset-password                 update password
POST       /logout                         destroy session
GET|HEAD   /verify-email                   email verification reminder
GET|HEAD   /verify-email/{id}/{hash}       verify email
POST       /email/verification-notification  resend verification email
GET|HEAD   /confirm-password               confirm password before sensitive action
POST       /confirm-password               confirm password
PUT        /password                       update password
```

---

## Admin Application Routes

All routes below require authentication (`auth` middleware).

### Dashboard
```
GET /dashboard
├─ Response: HTML (dashboard view)
├─ Title: Dashboard
├─ Stats: Total customers, Active customers
└─ Auth: Required
```

---

### Customer Management (RESTful Resource)

#### List All Customers
```
GET /customers
├─ Response: HTML table view
├─ Paginated: 15 per page
├─ Fields: Name, Email, Company, Phone, Actions
├─ Route Name: customers.index
├─ Auth: Required
└─ No Parameters
```

#### Create Customer Form
```
GET /customers/create
├─ Response: HTML form
├─ Fields: name, email, phone, company, notes
├─ Route Name: customers.create
├─ Auth: Required
└─ No Parameters
```

#### Create Customer (Submit)
```
POST /customers
├─ Content-Type: application/x-www-form-urlencoded
├─ Required Fields:
│  ├─ name (string, required)
│  └─ email (email, required, unique)
├─ Optional Fields:
│  ├─ phone (string, max:20)
│  ├─ company (string, max:255)
│  └─ notes (string, max:1000)
├─ Validation:
│  ├─ name: required|string|max:255
│  ├─ email: required|email|unique:customers,email
│  ├─ phone: nullable|string|max:20
│  ├─ company: nullable|string|max:255
│  └─ notes: nullable|string|max:1000
├─ Response: Redirect to /customers
├─ Route Name: customers.store
└─ Auth: Required
```

Example cURL:
```bash
curl -X POST http://localhost:8000/customers \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "X-CSRF-TOKEN: token_here" \
  -d "name=John Doe&email=john@example.com&company=Acme&phone=555-1234"
```

#### View Customer Details
```
GET /customers/{id}
├─ URL Parameter: id (customer ID)
├─ Response: HTML details view
├─ Fields: All customer fields with timestamps
├─ Route Name: customers.show
├─ Auth: Required
└─ Binding: Implicit (Customer model)
```

#### Edit Customer Form
```
GET /customers/{id}/edit
├─ URL Parameter: id (customer ID)
├─ Response: HTML form (pre-filled)
├─ Fields: name, email, phone, company, notes
├─ Route Name: customers.edit
├─ Auth: Required
└─ Binding: Implicit (Customer model)
```

#### Update Customer
```
PATCH /customers/{id}
├─ URL Parameter: id (customer ID)
├─ Content-Type: application/x-www-form-urlencoded
├─ Required Fields:
│  ├─ name (string, required)
│  └─ email (email, required, unique except current)
├─ Optional Fields:
│  ├─ phone (string, max:20)
│  ├─ company (string, max:255)
│  └─ notes (string, max:1000)
├─ Validation:
│  ├─ name: required|string|max:255
│  ├─ email: required|email|unique:customers,email,{id}
│  ├─ phone: nullable|string|max:20
│  ├─ company: nullable|string|max:255
│  └─ notes: nullable|string|max:1000
├─ Response: Redirect to /customers
├─ Route Name: customers.update
├─ Auth: Required
└─ Binding: Implicit (Customer model)
```

Example cURL:
```bash
curl -X PATCH http://localhost:8000/customers/1 \
  -H "X-CSRF-TOKEN: token_here" \
  -d "name=Jane Doe&email=jane@example.com&company=NewCo"
```

#### Delete Customer
```
DELETE /customers/{id}
├─ URL Parameter: id (customer ID)
├─ Method: POST with _method=DELETE (or DELETE)
├─ Response: Redirect to /customers
├─ Route Name: customers.destroy
├─ Auth: Required
├─ Binding: Implicit (Customer model)
└─ Confirmation: Browser will ask before delete
```

Example cURL:
```bash
curl -X DELETE http://localhost:8000/customers/1 \
  -H "X-CSRF-TOKEN: token_here"
```

---

### User Profile Management

#### Edit Profile
```
GET /profile
├─ Response: HTML form
└─ Auth: Required
```

#### Update Profile
```
PATCH /profile
├─ Parameters: name, email
├─ Response: Redirect
└─ Auth: Required
```

#### Delete Profile
```
DELETE /profile
├─ Response: Redirect to login
└─ Auth: Required
```

---

### PDF Analysis Module

#### Analysis Form Page
```
GET /analysis
├─ Response: HTML form (Blade view)
├─ Contents:
│  ├─ File upload input (PDF, max 10MB)
│  ├─ Customer dropdown selector
│  ├─ Submit button
│  └─ Results display area
├─ Route Name: analysis.index
├─ Auth: Required
└─ JS: Vanilla fetch() for AJAX submission
```

#### Analyze PDF (API)
```
POST /api/analysis
├─ Content-Type: multipart/form-data
├─ Required Parameters:
│  ├─ pdf (file, mimes:pdf, max:10240)
│  └─ customer_id (int, exists:customers,id)
├─ Validation:
│  ├─ pdf: required|file|mimes:pdf|max:10240
│  └─ customer_id: required|exists:customers,id
├─ Response: JSON
│  └─ Success:
│     ├─ success (boolean): true
│     ├─ customer_name (string): "John Doe"
│     └─ analysis (string): "AI-generated analysis..."
│  └─ Error:
│     ├─ success (boolean): false
│     ├─ message (string): "Analysis failed. Please try again."
│     └─ HTTP Status: 500
├─ Processing:
│  ├─ Store PDF to storage/app/temp/
│  ├─ Generate cache key (SHA256 hash + customer_id)
│  ├─ Check cache (60 seconds)
│  ├─ Call PdfAnalyzer agent if not cached
│  ├─ Attach document to agent prompt
│  ├─ Return analysis result
│  ├─ Clean temp file
│  └─ Cache response
├─ Route Name: analysis.store
└─ Auth: Required
```

Example JavaScript (Vanilla):
```javascript
const formData = new FormData();
formData.append('pdf', fileInput.files[0]);
formData.append('customer_id', customerSelect.value);

const response = await fetch('/api/analysis', {
  method: 'POST',
  headers: {
    'X-CSRF-TOKEN': csrfToken.value,
  },
  body: formData,
});

const data = await response.json();
if (data.success) {
  console.log('Analysis:', data.analysis);
} else {
  console.error('Error:', data.message);
}
```

Example cURL:
```bash
curl -X POST http://localhost:8000/api/analysis \
  -H "X-CSRF-TOKEN: token_here" \
  -F "pdf=@/path/to/file.pdf" \
  -F "customer_id=1"
```

Response Example:
```json
{
  "success": true,
  "customer_name": "John Doe",
  "analysis": "This PDF contains a comprehensive report on Q1 2026 performance metrics. Key findings include: 1. Revenue increased by 15% YoY... [full analysis]"
}
```

---

## Root Route Behavior

```
GET /
├─ Unauthenticated: Redirect to /login
├─ Authenticated: Redirect to /dashboard
├─ Response Type: 302 Redirect
└─ No Auth Required (middleware handles)
```

---

## HTTP Methods Summary

| Method | Purpose | Content-Type |
|--------|---------|--------------|
| GET | Retrieve data | N/A |
| POST | Create/submit data | application/x-www-form-urlencoded, multipart/form-data |
| PATCH | Update resource | application/x-www-form-urlencoded |
| PUT | Replace resource | application/x-www-form-urlencoded |
| DELETE | Remove resource | application/x-www-form-urlencoded |

---

## Response Status Codes

| Code | Meaning | Use Case |
|------|---------|----------|
| 200 | OK | Form submission successful |
| 302 | Redirect | POST/PATCH/DELETE redirects |
| 404 | Not Found | Invalid customer ID |
| 422 | Unprocessable Entity | Validation error |
| 500 | Server Error | PDF analysis failed |

---

## Error Responses

### Validation Errors (422)
```html
<!-- Page re-rendered with errors -->
<div class="error">
  <p>The email field is required.</p>
</div>
```

### Not Found (404)
```
If customer ID doesn't exist, Laravel shows 404 page
```

### Analysis Error (500 JSON)
```json
{
  "success": false,
  "message": "Analysis failed. Please try again."
}
```

---

## Caching

### Response Cache
```
Key Format: pdf_analysis_{file_hash}_{customer_id}
Duration: 60 seconds
Type: Laravel cache (configured in config/cache.php)
Invalidation: Automatic after TTL
```

Purpose: Avoid re-processing same PDF for same customer within 60 seconds

---

## File Storage

### Temporary Files
```
Path: storage/app/temp/
Naming: UUID (generated by Laravel)
Duration: Deleted after analysis
Cleanup: Automatic via unlink()
```

### Disk Configuration
```
Local storage driver
Private by default
No direct web access
Cleaned up immediately
```

---

## Security Headers

All requests must include:
```
X-CSRF-TOKEN: {{ csrf_token() }}  (in POST/PATCH/DELETE)
```

Or in HTML forms:
```html
@csrf  <!-- Laravel helper -->
```

---

## Database Interactions

### Customers Table
```sql
SELECT * FROM customers WHERE id = ?          -- Show
INSERT INTO customers (...) VALUES (...)      -- Create
UPDATE customers SET ... WHERE id = ?         -- Update
DELETE FROM customers WHERE id = ?            -- Delete
```

### Users Table
```sql
SELECT * FROM users WHERE email = ?           -- Authentication
```

---

## API Usage Examples

### Create Customer
```bash
curl -X POST http://localhost:8000/customers \
  -H "X-CSRF-TOKEN: abc123" \
  -d "name=John&email=john@example.com&company=Acme"
```

### Update Customer
```bash
curl -X PATCH http://localhost:8000/customers/1 \
  -H "X-CSRF-TOKEN: abc123" \
  -d "name=Jane&email=jane@example.com"
```

### Delete Customer
```bash
curl -X DELETE http://localhost:8000/customers/1 \
  -H "X-CSRF-TOKEN: abc123"
```

### Analyze PDF (AJAX)
```javascript
const form = new FormData();
form.append('pdf', file);
form.append('customer_id', customerId);

fetch('/api/analysis', {
  method: 'POST',
  headers: {'X-CSRF-TOKEN': token},
  body: form,
}).then(r => r.json()).then(data => {
  console.log(data.analysis);
});
```

---

## Notes

- All timestamps are in UTC
- Pagination uses default 15 per page
- Email addresses are case-insensitive in database
- Active status is managed via boolean toggle
- AI model is immutable (always Claude Haiku)
- Cache TTL is 60 seconds (configurable)
- Maximum upload size is 10MB (configurable)

---

**API Reference for PDF Analyzer v1.0**  
**Last Updated**: April 20, 2026