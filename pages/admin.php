<?php
session_start();

$admin_password = "mun2025"; // Change this password
$upload_dir = "../uploads/";
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$max_size = 5 * 1024 * 1024; // 5MB

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validate_csrf() {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

// Handle login
if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Incorrect password";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

// Handle upload
$upload_msg = "";
if (isset($_SESSION['admin_logged_in']) && isset($_POST['upload'])) {
    if (!validate_csrf()) {
        $upload_msg = "❌ CSRF validation failed<br>";
    } elseif (isset($_FILES['images'])) {
        $files = $_FILES['images'];
        $count = count($files['name']);
        
        for ($i = 0; $i < $count; $i++) {
            $name = $files['name'][$i];
            $tmp = $files['tmp_name'][$i];
            $size = $files['size'][$i];
            $type = $files['type'][$i];
            $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
            $new_name = uniqid() . "." . $ext;
            $dest = $upload_dir . $new_name;
            
            if (!in_array($type, $allowed_types)) {
                $upload_msg .= "❌ $name: Invalid file type<br>";
                continue;
            }
            if ($size > $max_size) {
                $upload_msg .= "❌ $name: File too large (max 5MB)<br>";
                continue;
            }
            if (move_uploaded_file($tmp, $dest)) {
                $upload_msg .= "✅ $name uploaded<br>";
            } else {
                $upload_msg .= "❌ $name: Upload failed<br>";
            }
        }
    }
}

// Handle delete
if (isset($_SESSION['admin_logged_in']) && isset($_POST['delete'])) {
    if (!validate_csrf()) {
        $upload_msg = "❌ CSRF validation failed<br>";
    } else {
        $file = basename($_POST['delete']);
        $path = $upload_dir . $file;
        if (file_exists($path)) {
            unlink($path);
            $upload_msg = "🗑️ Deleted $file<br>";
        }
    }
}

// Get uploaded images
$images = [];
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $images[] = $file;
        }
    }
    rsort($images); // Newest first
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload Gallery Images</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 2rem auto; padding: 1rem; }
        .login-form { background: #f5f5f5; padding: 2rem; border-radius: 8px; max-width: 400px; margin: 0 auto; }
        .upload-form { background: #f5f5f5; padding: 2rem; border-radius: 8px; margin-top: 1rem; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .image-item { position: relative; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .image-item img { width: 100%; height: 150px; object-fit: cover; }
        .delete-btn { position: absolute; top: 5px; right: 5px; background: rgba(255,0,0,0.8); color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .msg { padding: 1rem; margin: 1rem 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        button, input[type="submit"] { background: #007bff; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; }
        button:hover, input[type="submit"]:hover { background: #0056b3; }
        input[type="password"], input[type="file"] { width: 100%; padding: 0.5rem; margin: 0.5rem 0; }
        .logout { float: right; background: #dc3545; }
        .logout:hover { background: #c82333; }
    </style>
</head>
<body>
    <div id="navbar-placeholder"></div>
    
    <div class="admin-container">
        <h1>🖼️ Gallery Admin</h1>
        
        <?php if (!isset($_SESSION['admin_logged_in'])): ?>
            <!-- Login Form -->
            <div class="login-form">
                <h2>Admin Login</h2>
                <?php if (isset($error)): ?>
                    <div class="msg error"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <input type="password" name="password" placeholder="Enter admin password" required>
                    <input type="submit" name="login" value="Login">
                </form>
            </div>
            
        <?php else: ?>
            <!-- Logged In View -->
            <a href="?logout=1" class="logout">Logout</a>
            
            <!-- Upload Form -->
            <div class="upload-form">
                <h2>Upload Images</h2>
                <?php if ($upload_msg): ?>
                    <div class="msg success"><?= $upload_msg ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="file" name="images[]" accept="image/*" multiple required>
                    <input type="submit" name="upload" value="Upload Images">
                </form>
                <p><small>Allowed: JPG, PNG, GIF, WebP • Max 5MB each</small></p>
            </div>
            
            <!-- Uploaded Images -->
            <h2>Uploaded Images (<?= count($images) ?>)</h2>
            <?php if (empty($images)): ?>
                <p>No images uploaded yet.</p>
            <?php else: ?>
                <div class="image-grid">
                    <?php foreach ($images as $img): ?>
                        <div class="image-item">
                            <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($img) ?>">
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="delete" value="<?= htmlspecialchars($img) ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Delete this image?')">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="../scripts/navbar.js"></script>
</body>
</html>