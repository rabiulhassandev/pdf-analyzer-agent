# Quick Start Guide - PDF Analyzer Application

## 🚀 Start the Application

Choose one of the setup methods:

### Option 1: Full Fresh Setup
```bash
# Clone/setup project
cd /path/to/pdf-analyzer

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Create database and seed
php artisan migrate:fresh --seed

# Build frontend
npm run build

# Start server
php artisan serve
```

### Option 2: Quick Start (If Already Setup)
```bash
cd /path/to/pdf-analyzer
php artisan serve
# In another terminal:
npm run dev
```

---

## 📍 Access the Application

**URL**: `http://localhost:8000`

**Login Credentials**:
- Email: `admin@pdfanalyzer.com`
- Password: `password`

---

## 📋 Main Features

### Dashboard
- Overview with quick stats
- Total customer count
- Active customer count
- Links to other modules

### Customers
**Route**: `http://localhost:8000/customers`

Operations:
- ✅ View all customers (paginated)
- ✅ Create new customer
- ✅ Edit customer details
- ✅ View customer profile
- ✅ Delete customer
- ✅ Search/filter by name/email

### PDF Analysis
**Route**: `http://localhost:8000/analysis`

Operations:
- ✅ Upload PDF (max 10MB)
- ✅ Select customer
- ✅ AI analysis via Claude Haiku
- ✅ Get summary/insights
- ✅ Analysis results cached for 60s

---

## 🎨 UI Components Used

### Layout
- **Sidebar Navigation** - Left-aligned, fixed width 264px
- **Top Bar** - Page titles and status
- **Admin Panel** - Full-screen dashboard style
- **Card-based Design** - Clean sections with subtle borders

### Forms
- Clean input fields with labels
- Error messages displayed inline
- Validation feedback
- Success notifications

### Tables
- Striped rows with hover states
- Action buttons (Edit, Delete)
- Pagination at bottom

### Buttons
- Primary (blue) - Main actions
- Secondary (white) - Cancel/Alternative
- Danger (red) - Delete actions
- Various sizes with consistent styling

---

## 🛠 Troubleshooting

### Issue: "Port 8000 already in use"
```bash
# Use different port
php artisan serve --port=8001
```

### Issue: "Database connection error"
Check `.env` file:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pdf_analyzer
DB_USERNAME=root
DB_PASSWORD=
```

### Issue: "Frontend not updating"
```bash
# Rebuild frontend
npm run build

# Or watch for changes
npm run dev
```

### Issue: "Seeding failed"
```bash
# Reset database
php artisan migrate:fresh

# Then seed
php artisan db:seed
```

---

## 📁 Key Files

### Controllers
- `app/Http/Controllers/CustomerController.php` - Customer CRUD
- `app/Http/Controllers/AnalysisController.php` - PDF analysis

### Views
- `resources/views/layouts/app.blade.php` - Main layout
- `resources/views/dashboard.blade.php` - Dashboard
- `resources/views/customers/*.blade.php` - Customer pages
- `resources/views/analysis/index.blade.php` - Analysis page

### Routes
- `routes/web.php` - Application routes
- `routes/auth.php` - Authentication routes (Breeze)

### Models
- `app/Models/Customer.php` - Customer model
- `app/Models/User.php` - User model

---

## 🔐 Security Features

✅ CSRF token protection on all forms  
✅ Authentication middleware on admin routes  
✅ Password hashing (bcrypt)  
✅ Input validation on all endpoints  
✅ SQL injection protection (Eloquent)  
✅ MIME type validation for PDF uploads  
✅ File size limits (10MB max)  
✅ Temporary file cleanup  

---

## 💾 Seeding Info

When you run `php artisan migrate:fresh --seed`, the following is created:

1. **Admin User**
   - Email: `admin@pdfanalyzer.com`
   - Password: `password`

2. **5 Sample Customers**
   - With faker-generated names, emails, companies, phones
   - All marked as active

---

## 📊 Database Schema

### users table
```
id → PRIMARY KEY
name → VARCHAR(255)
email → VARCHAR(255) UNIQUE
email_verified_at → TIMESTAMP (nullable)
password → VARCHAR(255)
remember_token → VARCHAR(100) (nullable)
created_at → TIMESTAMP
updated_at → TIMESTAMP
```

### customers table
```
id → PRIMARY KEY
name → VARCHAR(255)
email → VARCHAR(255) UNIQUE
phone → VARCHAR(20) (nullable)
company → VARCHAR(255) (nullable)
notes → TEXT (nullable)
is_active → BOOLEAN (default: true)
created_at → TIMESTAMP
updated_at → TIMESTAMP
```

---

## 🧪 Testing

Run the test suite:
```bash
php artisan test

# Run with coverage
php artisan test --coverage
```

---

## 📖 API Documentation

### Endpoints (Protected - Require Login)

#### Get Customers
```
GET /customers
Response: HTML table view
```

#### Create Customer
```
POST /customers
Required: name (string), email (email)
Optional: phone, company, notes
Response: Redirect to customers.index
```

#### Upload & Analyze PDF
```
POST /api/analysis
Content-Type: multipart/form-data
Required: pdf (file, max 10MB), customer_id (exists in customers)

Response: JSON
{
  "success": true,
  "customer_name": "John Doe",
  "analysis": "AI-generated analysis text..."
}
```

---

## 🌐 Browser Support

- ✅ Chrome/Edge (Latest)
- ✅ Firefox (Latest)
- ✅ Safari (Latest)
- ✅ Mobile browsers (responsive)

---

## 📞 Support

For help:
1. Check logs: `storage/logs/laravel.log`
2. Run migrations: `php artisan migrate`
3. Clear cache: `php artisan cache:clear`
4. Rebuild assets: `npm run build`

---

## 🎯 Next Steps

Try these flows:
1. **Create Customer** → Customers → Create Form → Submit
2. **Edit Customer** → Customers → Click Edit → Modify → Save
3. **View Customer** → Customers → Click Name → View Details
4. **Analyze PDF** → PDF Analysis → Upload → Select Customer → Get Results
5. **Delete Customer** → Customers → Click Delete → Confirm

**All operations are instant and provide feedback!**

---

Made with ❤️ using Laravel 13 and Tailwind CSS v4