# 個人サイト / Retro Japanese Personal Site

A nostalgic personal website with classic Japanese web aesthetics (2000s 同人サイト style), featuring:

- 📊 **カウンター** - Visitor counter with database tracking
- 🎯 **キリ番** - Kiriban milestone celebration system
- 💬 **掲示板** - Guestbook/BBS with emoji icons
- 📷 **ギャラリー** - Photo gallery with categories and webp support
- 🔧 **管理画面** - Simple admin panel for photo uploads

## 📁 File Structure

```
your-site/
├── index.php              # Main homepage
├── gallery.php            # Photo gallery
├── guestbook.php          # Guestbook/BBS
├── admin.php              # Admin panel
├── about.php              # About page (create yourself)
├── portfolio.php          # Portfolio page (create yourself)
├── links.php              # Links page (create yourself)
├── kiriban_claim.php      # Kiriban claim handler (create yourself)
│
├── includes/
│   └── config.php         # ⚠️ EDIT THIS - Database config
│
├── css/
│   ├── style.css          # Main stylesheet
│   ├── gallery.css        # Gallery page styles
│   └── guestbook.css      # Guestbook styles
│
├── database/
│   └── setup.sql          # Database schema
│
├── uploads/               # Create this folder
│   ├── gallery/           # Full-size photos
│   └── thumbnails/        # Generated thumbnails
│
└── images/                # Create this folder
    ├── favicon.gif        # Site favicon
    ├── avatar.gif         # Your profile avatar
    ├── banner.gif         # Site banner (200x40)
    └── default_avatar.png # Fallback avatar
```

## 🚀 Setup Instructions for VT.Domains

### Step 1: Create MySQL Database

1. Log into **cPanel** at vt.domains
2. Go to **MySQL Databases**
3. Create a new database (e.g., `youruser_site`)
4. Create a new user with a strong password
5. Add user to database with **ALL PRIVILEGES**
6. Note down:
   - Database name: `youruser_site`
   - Username: `youruser_admin`
   - Password: `your-password`

### Step 2: Import Database Schema

1. Go to **phpMyAdmin** in cPanel
2. Select your database
3. Click **Import** tab
4. Upload `database/setup.sql`
5. Click **Go**

### Step 3: Configure the Site

Edit `includes/config.php`:

```php
// Database settings (from Step 1)
define('DB_NAME', 'youruser_site');
define('DB_USER', 'youruser_admin');
define('DB_PASS', 'your-password');

// Site settings
define('SITE_URL', 'https://yourname.vt.domains');
define('SITE_TITLE', 'Your Site Name');
define('OWNER_NAME', 'Your Name');

// Admin password (generate with: php -r "echo password_hash('yourpassword', PASSWORD_DEFAULT);")
define('ADMIN_PASSWORD_HASH', '$2y$10$...');  // Generate this!
```

### Step 4: Upload Files

1. Go to **File Manager** in cPanel
2. Navigate to `public_html`
3. Upload all files (or use FTP/SFTP)
4. Create folders:
   - `uploads/gallery`
   - `uploads/thumbnails`
   - `images`

### Step 5: Set Permissions

Via File Manager or SSH:

```bash
chmod 755 uploads
chmod 755 uploads/gallery
chmod 755 uploads/thumbnails
chmod 644 includes/config.php
```

### Step 6: Test Your Site

1. Visit `https://yourname.vt.domains`
2. Visit `https://yourname.vt.domains/admin.php`
3. Default admin password is `changeme` (change it!)

## 🎨 Customization

### Change Colors

Edit CSS variables in `css/style.css`:

```css
:root {
    --bg-main: #f5f0e6;           /* Background color */
    --accent-primary: #c4956a;     /* Accent color */
    --text-main: #4a4035;          /* Text color */
    /* ... etc */
}
```

### Add Your Own Pages

Copy the structure from existing pages:

```php
<?php
require_once __DIR__ . '/includes/config.php';
// Your page code here
?>
<!DOCTYPE html>
<!-- Copy header/nav/footer from index.php -->
```

### Custom Background

Replace the tiled pattern in `style.css`:

```css
body {
    background-image: url('../images/your-tile.gif');
    background-repeat: repeat;
}
```

## 📷 Photo Upload Tips

### WebP Conversion (Recommended)

Before uploading, convert your photos to WebP for smaller file sizes:

**Using ImageMagick (SSH):**
```bash
convert photo.jpg -quality 85 photo.webp
```

**Using cwebp (SSH):**
```bash
cwebp -q 85 photo.jpg -o photo.webp
```

**Online tools:**
- squoosh.app (Google)
- cloudconvert.com

### Batch Resize (Optional)

```bash
# Resize all JPGs to max 1920px width
for f in *.jpg; do
    convert "$f" -resize '1920x>' "$f"
done
```

## 🔒 Security Notes

1. **Change the default admin password immediately**
2. Generate a proper password hash:
   ```bash
   php -r "echo password_hash('your-secure-password', PASSWORD_DEFAULT);"
   ```
3. Keep `includes/config.php` outside public access if possible
4. Regularly backup your database via phpMyAdmin

## 🐛 Troubleshooting

### "Database connection error"
- Check DB credentials in `config.php`
- Verify database exists in phpMyAdmin
- Check user has proper permissions

### Photos not uploading
- Check `uploads/` folder permissions (755)
- Verify PHP `upload_max_filesize` (cPanel → PHP Settings)
- Check available disk space (1GB limit on VT.Domains)

### 500 Internal Server Error
- Check PHP error logs (cPanel → Errors)
- Verify all required PHP extensions are enabled

### Counter not incrementing
- Check `visitor_counter` table exists
- Verify INSERT permissions on `visitor_log` table

## 📝 SQL Quick Reference

**Reset visitor counter:**
```sql
UPDATE visitor_counter SET total_count = 0 WHERE id = 1;
TRUNCATE TABLE visitor_log;
```

**Clear guestbook:**
```sql
TRUNCATE TABLE guestbook;
```

**Add new category:**
```sql
INSERT INTO gallery_categories (slug, name_ja, name_en, sort_order) 
VALUES ('newcat', '新カテゴリ', 'New Category', 10);
```

## 🔗 Resources

- [VT.Domains Documentation](https://vt.domains/)
- [Reclaim Hosting Support](https://community.reclaimhosting.com/)
- [PHP Manual](https://www.php.net/manual/)
- [MySQL Reference](https://dev.mysql.com/doc/)

---

Made with ❤️ for VT.Domains

Questions? Check the [Reclaim Hosting community forums](https://community.reclaimhosting.com/)
