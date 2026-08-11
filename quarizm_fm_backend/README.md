# كوارزم FM Backend (Laravel API + لوحة تحكم Blade)

هذا المجلد يحتوي على ملفات المشروع الخاصة بالنظام فقط (بدون ملفات Laravel الأساسية اللي بتتولّد تلقائي). خطوات التركيب:

## 1) إنشاء مشروع Laravel جديد

```bash
composer create-project laravel/laravel quarizm-fm-backend
cd quarizm-fm-backend
composer require laravel/sanctum
php artisan install:api   # أو: php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

## 2) نسخ ملفات هذا المجلد فوق المشروع

انسخ المجلدات دي من هذا الـ zip إلى مشروعك (استبدال/دمج):

```
app/Models/User.php            → app/Models/User.php (استبدال)
app/Models/Ticket.php          → app/Models/
app/Models/ServiceReport.php   → app/Models/
app/Http/Controllers/Api/      → app/Http/Controllers/Api/
app/Http/Controllers/DashboardController.php → app/Http/Controllers/
database/migrations/*          → database/migrations/
database/seeders/FmDemoSeeder.php → database/seeders/
routes/api.php                 → routes/api.php (استبدال)
routes/web.php                 → routes/web.php (استبدال)
resources/views/layouts/       → resources/views/
resources/views/dashboard/     → resources/views/
```

## 3) إعداد قاعدة البيانات وتشغيل الهجرات

عدّل ملف `.env` (DB_DATABASE, DB_USERNAME, DB_PASSWORD) ثم:

```bash
php artisan migrate
php artisan db:seed --class=Database\\Seeders\\FmDemoSeeder
php artisan serve
```

## 4) حسابات تجريبية (بعد الـ Seeder)

| الدور | البريد | كلمة المرور |
|---|---|---|
| عميل | customer@quarizm.tech | 123456 |
| منسق صيانة | coordinator@quarizm.tech | 123456 |
| فني (فريق كهرباء) | tech1@quarizm.tech | 123456 |
| مدير (لوحة التحكم) | manager@quarizm.tech | 123456 |

## 5) الوصول

- API (يُستخدم من تطبيق Flutter): `http://127.0.0.1:8000/api/...`
- لوحة تحكم المدير (Blade عادية بدون Vue): `http://127.0.0.1:8000/login`

## 6) نقاط الـ API الرئيسية

```
POST   /api/login                       تسجيل الدخول → يرجع token
GET    /api/me                          بيانات المستخدم الحالي
POST   /api/logout

GET    /api/tickets                     قائمة البلاغات (حسب دور المستخدم)
POST   /api/tickets                     تسجيل بلاغ جديد (عميل)
GET    /api/tickets/{id}                تفاصيل بلاغ
PATCH  /api/tickets/{id}/assign         إسناد لفريق (منسق)
PATCH  /api/tickets/{id}/accept         قبول المهمة (فني)
PATCH  /api/tickets/{id}/start          بدء التنفيذ (فني)
POST   /api/tickets/{id}/report         إرسال تقرير الخدمة (فني)
PATCH  /api/tickets/{id}/confirm        تأكيد العميل وإغلاق البلاغ

GET    /api/stats                       مؤشرات عامة
```

كل الطلبات المحمية تحتاج الهيدر:
```
Authorization: Bearer {token}
Accept: application/json
```
