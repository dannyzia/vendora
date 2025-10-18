# ✅ DAY 1 - QUICK CHECKLIST

## COPY FILES TO PROJECT
```
[ ] Copy 2 migration files to database/migrations/
[ ] Copy 4 seeder files to database/seeders/
[ ] Copy 2 middleware files to app/Http/Middleware/
[ ] Copy OtpService.php to app/Services/
```

## VERIFY PREREQUISITES
```
[ ] Categories table migration exists
[ ] vendor column in vendors table exists
[ ] user role enum includes 'admin' and 'vendor'
```

## REGISTER MIDDLEWARE
```
[ ] Add EnsureVendorRole to bootstrap/app.php
[ ] Add EnsureAdminRole to bootstrap/app.php
[ ] Alias 'vendor' middleware
[ ] Alias 'admin' middleware
```

## RUN COMMANDS
```bash
[ ] composer dump-autoload
[ ] php artisan migrate
[ ] php artisan db:seed
[ ] php artisan optimize:clear
```

## VERIFY RESULTS
```
[ ] Admin user exists (admin@vendora.com)
[ ] 12 categories in database
[ ] 4 subscription plans in database
[ ] otp_verifications table exists
[ ] subscription_plans table exists
[ ] No errors in laravel.log
```

## TEST FUNCTIONALITY
```
[ ] Can login as admin@vendora.com
[ ] Can access admin panel
[ ] Middleware blocks unauthorized access
[ ] OtpService class loads without errors
```

## CONFIGURE EMAIL (Optional but Recommended)
```
[ ] Set MAIL_* variables in .env
[ ] Test email sending with php artisan tinker
```

## READY FOR DAY 2?
```
[ ] All above items checked
[ ] No blocking errors
[ ] Admin dashboard accessible
[ ] Excited to continue! 🚀
```

---

## ADMIN CREDENTIALS
**Email:** admin@vendora.com  
**Password:** Admin@123456  
⚠️ Change after first login!

---

## QUICK TROUBLESHOOTING
**Error:** "Class not found"  
→ Run: `composer dump-autoload`

**Error:** "Table doesn't exist"  
→ Run: `php artisan migrate:fresh --seed`

**Error:** "Route not found"  
→ Run: `php artisan route:clear`

**Error:** "Config cached"  
→ Run: `php artisan optimize:clear`

---

## ESTIMATED TIME: 2-3 hours

**Questions?** Ask me for help! 💪