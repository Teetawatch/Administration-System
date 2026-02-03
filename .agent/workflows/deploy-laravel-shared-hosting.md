---
description: Deploy Laravel on Shared Hosting with WordPress (subdirectory setup)
---

# Deploy Laravel บน Shared Hosting ที่มี WordPress

## โครงสร้างโฟลเดอร์บน Hosting

```
/home/username/domains/example.com/
├── public_html/              <- WordPress อยู่ที่นี่
│   ├── adm/                  <- Laravel public folder
│   │   ├── index.php
│   │   ├── .htaccess
│   │   └── build/            <- Vite assets
│   ├── wp-login.php
│   └── .htaccess             <- WordPress .htaccess
└── adm-core/                 <- Laravel core (นอก public_html)
    ├── app/
    ├── bootstrap/
    ├── config/
    ├── routes/
    ├── storage/
    ├── vendor/
    └── .env
```

## ไฟล์สำคัญที่ต้องตั้งค่า

### 1. `/public_html/adm/index.php`

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../../adm-core/storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../../adm-core/vendor/autoload.php';

$app = require_once __DIR__.'/../../adm-core/bootstrap/app.php';

// สำคัญ: บอก Laravel ว่า public path อยู่ที่ไหน
$app->bind('path.public', function() {
    return __DIR__;
});

$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### 2. `/public_html/adm/.htaccess`

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 3. `/public_html/.htaccess` (WordPress)

เพิ่มบรรทัดนี้ **ก่อน** WordPress rules:

```apache
# BEGIN Laravel ADM
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^adm(/.*)?$ - [L]
</IfModule>
# END Laravel ADM

# BEGIN WordPress
...
# END WordPress
```

### 4. `/adm-core/.env`

```env
APP_URL=https://example.com/adm
```

## ข้อควรระวัง - Redirect ใน routes/web.php

> [!CAUTION]
> **ห้ามใช้ `Route::redirect()` กับ relative path!**

### ❌ ผิด (จะ redirect ไป WordPress)
```php
Route::redirect('/', '/login');
```

### ✅ ถูก (ใช้ route helper)
```php
Route::get('/', function () {
    return redirect()->route('login');
});
```

**เหตุผล:** `Route::redirect('/', '/login')` จะสร้าง relative path `/login` ซึ่งเบราว์เซอร์จะไปที่ root ของเว็บ (WordPress) แทน

## Checklist การ Deploy

- [ ] อัปโหลด Laravel core ไปที่ `/adm-core/`
- [ ] อัปโหลด public files ไปที่ `/public_html/adm/`
- [ ] ตั้งค่า `.env` ให้ `APP_URL` ถูกต้อง
- [ ] สร้าง `.htaccess` ใน `/public_html/adm/`
- [ ] แก้ไข `.htaccess` ใน `/public_html/` ให้ยกเว้น `/adm`
- [ ] ลบ cache plugins rules เก่า (W3TC, WP Super Cache) ใน `.htaccess`
- [ ] ตรวจสอบ routes ว่าไม่มี relative redirect
- [ ] Clear browser cache และทดสอบ
