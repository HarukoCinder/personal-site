<?php
/**
 * ========================================
 * 管理画面 / Admin Panel
 * ========================================
 * Simple photo upload and management
 */
require_once __DIR__ . '/includes/config.php';

// Session for admin login
session_start();

$db = getDB();
$error = '';
$success = '';

// Simple password protection (set your password hash in config.php)
$is_logged_in = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;

// Handle login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    if ($_POST['action'] === 'login') {
        $password = $_POST['password'] ?? '';
        
        // Check against stored hash (or simple comparison for now)
        // In production, use password_verify() with a proper hash
        if (ADMIN_PASSWORD_HASH && password_verify($password, ADMIN_PASSWORD_HASH)) {
            $_SESSION['admin_logged_in'] = true;
            $is_logged_in = true;
            $success = 'ログインしました';
        } elseif (!ADMIN_PASSWORD_HASH && $password === 'changeme') {
            // Default password if none set (CHANGE THIS!)
            $_SESSION['admin_logged_in'] = true;
            $is_logged_in = true;
            $success = 'ログインしました（デフォルトパスワードを変更してください）';
        } else {
            $error = 'パスワードが違います';
        }
    }
    
    if ($_POST['action'] === 'logout') {
        session_destroy();
        header('Location: admin.php');
        exit;
    }
    
    // Handle photo upload (must be logged in)
    if ($_POST['action'] === 'upload' && $is_logged_in) {
        if (!empty($_FILES['photo']['name'])) {
            $file = $_FILES['photo'];
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $category = trim($_POST['category'] ?? 'uncategorized');
            $taken_date = $_POST['taken_date'] ?? null;
            $camera_info = trim($_POST['camera_info'] ?? '');
            
            // Validate file
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = 'アップロードエラーが発生しました';
            } elseif ($file['size'] > MAX_UPLOAD_SIZE) {
                $error = 'ファイルサイズが大きすぎます（最大10MB）';
            } elseif (!in_array($ext, ALLOWED_EXTENSIONS)) {
                $error = '対応していないファイル形式です（jpg, png, gif, webp のみ）';
            } else {
                // Generate unique filename
                $new_filename = uniqid('photo_') . '.' . $ext;
                $upload_path = GALLERY_PATH . '/' . $new_filename;
                $thumb_path = THUMB_PATH . '/' . $new_filename;
                
                // Create directories if needed
                if (!is_dir(GALLERY_PATH)) mkdir(GALLERY_PATH, 0755, true);
                if (!is_dir(THUMB_PATH)) mkdir(THUMB_PATH, 0755, true);
                
                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Get image dimensions
                    $img_info = getimagesize($upload_path);
                    $width = $img_info[0] ?? 0;
                    $height = $img_info[1] ?? 0;
                    
                    // Create thumbnail
                    createThumbnail($upload_path, $thumb_path, THUMB_WIDTH, THUMB_HEIGHT);
                    
                    // Insert into database
                    $stmt = $db->prepare("INSERT INTO gallery (filename, original_filename, title, description, category, taken_date, camera_info, file_size, width, height) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $new_filename,
                        $file['name'],
                        $title ?: null,
                        $description ?: null,
                        $category,
                        $taken_date ?: null,
                        $camera_info ?: null,
                        $file['size'],
                        $width,
                        $height
                    ]);
                    
                    $success = '写真をアップロードしました！';
                } else {
                    $error = 'ファイルの保存に失敗しました';
                }
            }
        } else {
            $error = 'ファイルを選択してください';
        }
    }
    
    // Handle photo delete (must be logged in)
    if ($_POST['action'] === 'delete' && $is_logged_in) {
        $photo_id = (int)($_POST['photo_id'] ?? 0);
        
        if ($photo_id) {
            $stmt = $db->prepare("SELECT filename FROM gallery WHERE id = ?");
            $stmt->execute([$photo_id]);
            $photo = $stmt->fetch();
            
            if ($photo) {
                // Delete files
                @unlink(GALLERY_PATH . '/' . $photo['filename']);
                @unlink(THUMB_PATH . '/' . $photo['filename']);
                
                // Delete from database
                $db->prepare("DELETE FROM gallery WHERE id = ?")->execute([$photo_id]);
                
                $success = '写真を削除しました';
            }
        }
    }
}

/**
 * Create thumbnail image
 */
function createThumbnail($source, $dest, $max_width, $max_height) {
    $info = getimagesize($source);
    if (!$info) return false;
    
    $mime = $info['mime'];
    $orig_w = $info[0];
    $orig_h = $info[1];
    
    // Calculate new dimensions (crop to square)
    $size = min($orig_w, $orig_h);
    $src_x = ($orig_w - $size) / 2;
    $src_y = ($orig_h - $size) / 2;
    
    // Create image from source
    switch ($mime) {
        case 'image/jpeg':
            $src_img = imagecreatefromjpeg($source);
            break;
        case 'image/png':
            $src_img = imagecreatefrompng($source);
            break;
        case 'image/gif':
            $src_img = imagecreatefromgif($source);
            break;
        case 'image/webp':
            $src_img = imagecreatefromwebp($source);
            break;
        default:
            return false;
    }
    
    if (!$src_img) return false;
    
    // Create thumbnail
    $thumb = imagecreatetruecolor($max_width, $max_height);
    
    // Preserve transparency for PNG/GIF
    if ($mime === 'image/png' || $mime === 'image/gif') {
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }
    
    // Resize and crop
    imagecopyresampled($thumb, $src_img, 0, 0, $src_x, $src_y, $max_width, $max_height, $size, $size);
    
    // Save thumbnail
    switch ($mime) {
        case 'image/jpeg':
            imagejpeg($thumb, $dest, 85);
            break;
        case 'image/png':
            imagepng($thumb, $dest, 8);
            break;
        case 'image/gif':
            imagegif($thumb, $dest);
            break;
        case 'image/webp':
            imagewebp($thumb, $dest, 85);
            break;
    }
    
    imagedestroy($src_img);
    imagedestroy($thumb);
    
    return true;
}

// Get all photos for management
if ($is_logged_in) {
    $photos = $db->query("SELECT * FROM gallery ORDER BY uploaded_at DESC")->fetchAll();
    $categories = $db->query("SELECT * FROM gallery_categories ORDER BY sort_order")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理画面 - <?= h(SITE_TITLE) ?></title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 20px auto; padding: 20px; background: var(--bg-content); border: 2px solid var(--border-main); }
        .admin-section { margin-bottom: 30px; padding: 20px; background: var(--bg-sidebar); border: 1px solid var(--border-light); }
        .admin-title { text-align: center; margin-bottom: 20px; }
        .login-form, .upload-form { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-size: 12px; }
        .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 8px; border: 2px inset var(--border-main); }
        .form-group input[type="file"] { padding: 5px; }
        .photo-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; }
        .photo-item { position: relative; border: 2px solid var(--border-main); }
        .photo-item img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }
        .photo-item-info { padding: 8px; font-size: 11px; background: var(--bg-content); }
        .photo-item-actions { padding: 5px; text-align: center; }
        .delete-btn { background: #c44; color: #fff; border: none; padding: 3px 10px; cursor: pointer; font-size: 11px; }
        .delete-btn:hover { background: #a33; }
        .preview-img { max-width: 200px; max-height: 200px; margin-top: 10px; display: none; }
        .logout-btn { position: absolute; top: 10px; right: 10px; }
    </style>
</head>
<body>
    <div class="admin-container">
        <h1 class="admin-title">🔧 管理画面</h1>
        
        <?php if ($error): ?>
        <div class="message-error" style="background:#fff0f0;border:1px solid #e88;color:#c44;padding:10px;margin-bottom:15px;text-align:center;"><?= h($error) ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="message-success" style="background:#f0fff0;border:1px solid #8c8;color:#484;padding:10px;margin-bottom:15px;text-align:center;"><?= h($success) ?></div>
        <?php endif; ?>
        
        <?php if (!$is_logged_in): ?>
        <!-- Login Form -->
        <div class="admin-section">
            <h2>ログイン</h2>
            <form method="POST" class="login-form">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" name="password" required>
                </div>
                <div style="text-align:center;">
                    <button type="submit" class="btn-retro">ログイン</button>
                </div>
            </form>
            <p style="text-align:center;margin-top:15px;font-size:11px;color:var(--text-light);">
                ※ デフォルトパスワードは "changeme" です<br>
                config.php で ADMIN_PASSWORD_HASH を設定してください
            </p>
        </div>
        
        <?php else: ?>
        <!-- Logged In -->
        <form method="POST" style="position:absolute;top:10px;right:10px;">
            <input type="hidden" name="action" value="logout">
            <button type="submit" class="btn-retro btn-small">ログアウト</button>
        </form>
        
        <!-- Upload Form -->
        <div class="admin-section">
            <h2>📷 写真アップロード</h2>
            <form method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="hidden" name="action" value="upload">
                
                <div class="form-group">
                    <label>写真ファイル *</label>
                    <input type="file" name="photo" accept=".jpg,.jpeg,.png,.gif,.webp" required id="photoInput">
                    <img id="preview" class="preview-img" alt="Preview">
                </div>
                
                <div class="form-group">
                    <label>タイトル</label>
                    <input type="text" name="title" maxlength="200" placeholder="タイトル（任意）">
                </div>
                
                <div class="form-group">
                    <label>説明</label>
                    <textarea name="description" rows="3" placeholder="写真の説明（任意）"></textarea>
                </div>
                
                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="category">
                        <?php foreach ($categories as $cat): ?>
                        <option value="<?= h($cat['slug']) ?>"><?= h($cat['name_ja']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>撮影日</label>
                    <input type="date" name="taken_date">
                </div>
                
                <div class="form-group">
                    <label>カメラ情報</label>
                    <input type="text" name="camera_info" maxlength="255" placeholder="例: Sony α7C + 35mm f/1.8">
                </div>
                
                <div style="text-align:center;">
                    <button type="submit" class="btn-retro">アップロード</button>
                </div>
            </form>
        </div>
        
        <!-- Photo Management -->
        <div class="admin-section">
            <h2>📁 写真管理 (<?= count($photos) ?>枚)</h2>
            
            <?php if (empty($photos)): ?>
            <p style="text-align:center;color:var(--text-light);">まだ写真がありません</p>
            <?php else: ?>
            <div class="photo-grid">
                <?php foreach ($photos as $photo): ?>
                <div class="photo-item">
                    <img src="uploads/thumbnails/<?= h($photo['filename']) ?>" 
                         alt="<?= h($photo['title'] ?? 'Photo') ?>">
                    <div class="photo-item-info">
                        <strong><?= h($photo['title'] ?: 'Untitled') ?></strong><br>
                        <?= h($photo['category']) ?><br>
                        <?= formatDateShort($photo['uploaded_at']) ?>
                    </div>
                    <div class="photo-item-actions">
                        <form method="POST" style="display:inline;" onsubmit="return confirm('本当に削除しますか？')">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="photo_id" value="<?= $photo['id'] ?>">
                            <button type="submit" class="delete-btn">削除</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <div style="text-align:center;margin-top:20px;">
            <a href="index.php" class="btn-retro">← サイトに戻る</a>
        </div>
    </div>
    
    <script>
    // Preview uploaded image
    document.getElementById('photoInput')?.addEventListener('change', function(e) {
        const preview = document.getElementById('preview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });
    </script>
</body>
</html>
