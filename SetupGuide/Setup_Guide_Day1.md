# 🚀 VENDOR ONBOARDING - DAY 1 SETUP GUIDE

## ✅ WHAT WE'VE COMPLETED TODAY

### Phase 0: Foundation & Prerequisites (DONE!)

We've created **8 essential files** that form the foundation of your vendor onboarding system:

---

## 📦 FILES CREATED

### 1. **Database Migrations (3 files)**

#### `database/migrations/2025_01_15_000001_create_otp_verifications_table.php` Done
**Purpose:** Stores OTP codes for email/SMS/WhatsApp verification
**Fields:**
- identifier (email or phone)
- otp (6-digit code)
- expires_at (5 minutes validity)
- attempts (max 3)
- resend_count (tracking)
- purpose (vendor_onboarding, password_reset, etc.)

#### `database/migrations/2025_01_15_000002_create_subscription_plans_table.php` Done
**Purpose:** Stores vendor pricing tiers (Free, Basic, Pro, Enterprise)
**Fields:**
- name, price, commission_rate
- product_limit, features (JSON)
- trial_days, is_popular

---

### 2. **Database Seeders (4 files)**

#### `database/seeders/AdminSeeder.php` Done
**Purpose:** Creates the first admin account
**Credentials:**
- Email: admin@vendora.com
- Password: Admin@123456
- Role: admin

#### `database/seeders/SubscriptionPlanSeeder.php` Done
**Purpose:** Creates 4 subscription plans:
1. **Free** - ৳0/month, 10 products, 15% commission
2. **Basic** - ৳500/month, 100 products, 10% commission (Most Popular)
3. **Pro** - ৳1500/month, unlimited, 8% commission
4. **Enterprise** - Custom pricing, 5% commission

#### `database/seeders/CategorySeeder.php` Done
**Purpose:** Creates 12 product categories:
- Electronics, Fashion, Home & Living, Health & Beauty
- Books, Sports, Toys, Food, Automotive
- Digital Products, Services, Handicrafts

#### `database/seeders/DatabaseSeeder.php` Done
**Purpose:** Orchestrates all seeders

---

### 3. **Middleware (2 files)**

#### `app/Http/Middleware/EnsureVendorRole.php` Done
**Purpose:** Protects vendor-only routes
**Function:** Checks if user has 'vendor' role, redirects otherwise

#### `app/Http/Middleware/EnsureAdminRole.php`
**Purpose:** Protects admin-only routes
**Function:** Checks if user has 'admin' role, returns 403 if not

---

### 4. **Services (1 file)**

#### `app/Services/OtpService.php` Done
**Purpose:** Complete OTP system
**Features:**
- Generate 6-digit OTP codes
- Send via Email (working now)
- Send via SMS (SSL Wireless - TODO)
- Send via WhatsApp (TODO)
- Verify OTP with attempt tracking
- Rate limiting (5 per day, 60s cooldown)
- Auto-expiry (5 minutes)

---

## 🛠️ INSTALLATION STEPS

Follow these steps **EXACTLY** in your Laravel project:

### STEP 1: Copy Files to Your Project

```bash
# Navigate to your project root
cd C:\laragon\www\vendora-marketplace

# Copy all files from the outputs folder to your project
# Database files
cp database/migrations/2025_01_15_000001_create_otp_verifications_table.php database/migrations/
cp database/migrations/2025_01_15_000002_create_subscription_plans_table.php database/migrations/

# Seeders
cp database/seeders/AdminSeeder.php database/seeders/
cp database/seeders/CategorySeeder.php database/seeders/
cp database/seeders/SubscriptionPlanSeeder.php database/seeders/
cp database/seeders/DatabaseSeeder.php database/seeders/

# Middleware
cp app/Http/Middleware/EnsureVendorRole.php app/Http/Middleware/
cp app/Http/Middleware/EnsureAdminRole.php app/Http/Middleware/

# Services
mkdir -p app/Services
cp app/Services/OtpService.php app/Services/
```

---

### STEP 2: Check Categories Table Exists

Before seeding, verify you have a `categories` table. Check if this migration exists:

```bash
# Look for categories migration
ls database/migrations/ | grep categories
```

**If NOT found**, create it:

```bash
php artisan make:migration create_categories_table
```

Then add this structure:

```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('name_bn')->nullable();
    $table->string('slug')->unique();
    $table->text('description')->nullable();
    $table->text('description_bn')->nullable();
    $table->string('icon')->nullable();
    $table->boolean('is_active')->default(true);
    $table->integer('sort_order')->default(0);
    $table->timestamps();
});
```

---

### STEP 3: Register Middleware in Bootstrap

**File:** `bootstrap/app.php`

Add these lines:

```php
use App\Http\Middleware\EnsureVendorRole;
use App\Http\Middleware\EnsureAdminRole;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'vendor' => EnsureVendorRole::class,
            'admin' => EnsureAdminRole::class,
        ]);
    })
    // ... rest of config
```

---

### STEP 4: Run Migrations

```bash
# Run new migrations only
php artisan migrate

# You should see:
# - 2025_01_15_000001_create_otp_verifications_table ✓
# - 2025_01_15_000002_create_subscription_plans_table ✓
```

---

### STEP 5: Seed Database

```bash
# Run all seeders
php artisan db:seed

# You should see:
# ✅ Admin user created successfully!
# ✅ Categories seeded successfully!
# ✅ Subscription plans seeded successfully!
```

---

### STEP 6: Verify Setup

#### Check Admin User:
```bash
php artisan tinker
>>> User::where('role', 'admin')->first();
# Should return admin user details
```

#### Check Categories:
```bash
>>> DB::table('categories')->count();
# Should return: 12
```

#### Check Subscription Plans:
```bash
>>> DB::table('subscription_plans')->count();
# Should return: 4
```

---

### STEP 7: Test Admin Login

1. Start your Laravel server:
```bash
php artisan serve
```

2. Visit: http://localhost:8000/login

3. Login with:
   - **Email:** admin@vendora.com
   - **Password:** Admin@123456

4. ✅ If login successful, Phase 0 is COMPLETE!

---

## 📋 WHAT'S NEXT - DAY 2 PLAN

Tomorrow (Day 2), we'll build:

### Phase 1: Application Form (Step 1)
**Time:** 4-5 hours

**Backend:**
1. `OnboardingController.php` - Main logic
2. `VendorApplicationRequest.php` - Form validation
3. `VendorService.php` - Business logic
4. Routes in `web.php`

**Frontend:**
1. `Step1Application.vue` - Application form
2. `ProgressStepper.vue` - Step indicator
3. Form fields with validation
4. Auto-generate slug from business name

**What vendor will fill:**
- Business name
- Business type (dropdown)
- Category (dropdown)
- Expected monthly sales
- Phone number (Bangladesh format)
- Address (district/division dropdowns)

---

## 🧪 TESTING CHECKLIST FOR TODAY

```
Phase 0 - Setup:
[x] Admin user created
[x] Can login as admin
[x] 12 categories seeded
[x] 4 subscription plans seeded
[x] OTP migrations created
[x] Middleware registered
[x] OtpService accessible
```

---

## 📊 PROJECT PROGRESS

```
✅ Phase 0: Setup & Prerequisites (100% - Day 1)
⏳ Phase 1: Application Form (0% - Day 2)
⏳ Phase 2: Document Upload (0% - Day 3)
⏳ Phase 3: OTP Verification (0% - Day 4)
⏳ Phase 4: Admin Review (0% - Day 5)
⏳ Phase 5: Profile Completion (0% - Day 6)
⏳ Phase 6: Testing & Polish (0% - Day 7)

Overall Progress: 14% (1/7 days)
```

---

## 🐛 TROUBLESHOOTING

### Issue: "Class 'AdminSeeder' not found"
**Solution:**
```bash
composer dump-autoload
```

### Issue: "Table 'categories' doesn't exist"
**Solution:** Create the categories migration (see Step 2)

### Issue: "Middleware not found"
**Solution:** Clear config cache
```bash
php artisan config:clear
php artisan route:clear
```

### Issue: "OTP email not sending"
**Solution:** Check your `.env` mail settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog  # or your SMTP host
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@vendora.com"
MAIL_FROM_NAME="Vendora Bangladesh"
```

---

## 📞 SUPPORT

If you encounter any issues:
1. Check the error log: `storage/logs/laravel.log`
2. Run: `php artisan optimize:clear`
3. Ask me for help!

---

## 🎯 SUCCESS CRITERIA

Phase 0 is successful when:
- ✅ Admin user can login
- ✅ Database has 12 categories
- ✅ Database has 4 subscription plans
- ✅ All migrations ran successfully
- ✅ No errors in logs

---

## ⏱️ TIME INVESTMENT

**Today:** 2-3 hours (setup + testing)
**Tomorrow:** 4-5 hours (building Step 1 form)
**Total Week:** 30-35 hours across 7 days

---

## 🚀 READY FOR DAY 2?

Once you've completed all steps above and verified everything works:

1. ✅ Confirm admin login works
2. ✅ Check all seeders ran
3. ✅ No errors in `php artisan migrate`

Then tomorrow we'll start building the actual vendor onboarding forms!

**Questions? Issues? Let me know and I'll help you debug!** 💪