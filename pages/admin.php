<?php
session_start();

$admin_password = "mun2025";
$upload_dir = "../uploads/";
$newsletter_dir = "../assets/";
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
$max_size = 5 * 1024 * 1024;

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function validate_file_type($type) {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'application/pdf'];
    return in_array($type, $allowed);
}

function validate_csrf() {
    return isset($_POST['csrf_token']) && hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

if (isset($_POST['login'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit;
    } else {
        $error = "Incorrect password";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

$upload_msg = "";
$category = "gallery";

if (isset($_SESSION['admin_logged_in']) && isset($_POST['upload'])) {
    if (!validate_csrf()) {
        $upload_msg = "CSRF validation failed<br>";
    } else {
        $category = isset($_POST['category']) ? $_POST['category'] : "gallery";
        $overwrite = isset($_POST['overwrite']) ? true : false;

        if (isset($_FILES['images'])) {
            $files = $_FILES['images'];
            $count = count($files['name']);

            for ($i = 0; $i < $count; $i++) {
                $name = $files['name'][$i];
                $tmp = $files['tmp_name'][$i];
                $size = $files['size'][$i];
                $type = $files['type'][$i];
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (!validate_file_type($type)) {
                    $upload_msg .= "Invalid file type: $name<br>";
                    continue;
                }
                if ($size > $max_size) {
                    $upload_msg .= "File too large (max 5MB): $name<br>";
                    continue;
                }

                if ($category === "newsletter" && $ext === "pdf") {
                    $dest = $newsletter_dir . "newsletter.pdf";
                    if (file_exists($dest) && !$overwrite) {
$upload_msg .= "A newsletter.pdf already exists in assets/. Check 
overwrite to replace.<br>";
                        continue;
                    }
                    if (move_uploaded_file($tmp, $dest)) {
                        if (file_exists($dest)) {
                            $upload_msg .= "Newsletter PDF saved to assets/<br>";
                        } else {
                            $upload_msg .= "Newsletter PDF uploaded<br>";
                        }
                    } else {
                        $upload_msg .= "Upload failed: $name<br>";
                    }
                } else {
                    $new_name = uniqid() . "." . $ext;
                    $dest = $upload_dir . $new_name;
                    if (move_uploaded_file($tmp, $dest)) {
                        $upload_msg .= "Uploaded: $name<br>";
                    } else {
                        $upload_msg .= "Upload failed: $name<br>";
                    }
                }
            }
        }
    }
}

if (isset($_SESSION['admin_logged_in']) && isset($_POST['delete'])) {
    if (!validate_csrf()) {
        $upload_msg = "CSRF validation failed<br>";
    } else {
        $file = basename($_POST['delete']);
        $path_uploads = $upload_dir . $file;
        $path_newsletter = $newsletter_dir . $file;
        if (file_exists($path_uploads)) {
            unlink($path_uploads);
            $upload_msg = "Deleted $file from uploads<br>";
        } elseif (file_exists($path_newsletter)) {
            unlink($path_newsletter);
            $upload_msg = "Deleted $file from assets<br>";
        } else {
            $upload_msg = "File not found: $file<br>";
        }
    }
}

$images = [];
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $images[] = $file;
        }
    }
    rsort($images);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Upload Gallery Files</title>
    <link rel="stylesheet" href="../styles/style.css">
    <style>
        .admin-container { max-width: 800px; margin: 2rem auto; padding: 1rem; }
        .login-form { background: #f5f5f5; padding: 2rem; border-radius: 8px; max-width: 400px; margin: 0 auto; }
        .upload-form { background: #f5f5f5; padding: 2rem; border-radius: 8px; margin-top: 1rem; }
        .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1rem; }
        .image-item { position: relative; border: 1px solid #ddd; border-radius: 4px; overflow: hidden; }
        .image-item img { width: 100%; height: 150px; object-fit: cover; }
        .image-item a { display: block; padding: 2rem; text-align: center; text-decoration: none; color: #333; }
        .image-item a:hover { background: #f0f0f0; }
        .delete-btn { position: absolute; top: 5px; right: 5px; background: red; color: white; border: none; padding: 5px 10px; border-radius: 3px; cursor: pointer; }
        .msg { padding: 1rem; margin: 1rem 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        button, input[type="submit"] { background: #007bff; color: white; border: none; padding: 0.75rem 1.5rem; border-radius: 4px; cursor: pointer; }
        button:hover, input[type="submit"]:hover { background: #0056b3; }
        input[type="password"], input[type="file"] { width: 100%; padding: 0.5rem; margin: 0.5rem 0; }
        .logout { float: right; }
        .radio-group { margin: 1rem 0; }
        .radio-group label { margin-right: 1rem; cursor: pointer; }
        .checkbox-group { margin: 1rem 0; }
        .checkbox-group label { cursor: pointer; }
    </style>
</head>
<body>
    <div id="navbar-placeholder"></div>

    <div class="admin-container">
        <h1>Gallery Admin</h1>

        <?php if (!isset($_SESSION['admin_logged_in'])): ?>
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
            <a href="?logout=1" class="logout">Logout</a>

            <div class="upload-form">
                <h2>Upload Files</h2>
                <?php if ($upload_msg): ?>
                    <div class="msg success"><?= $upload_msg ?></div>
                <?php endif; ?>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                    <div class="radio-group">
                        <label>
                            <input type="radio" name="category" value="gallery" <?= ($category === 'gallery') ? 'checked' : '' ?>>
                            Gallery
                        </label>
                        <label>
                            <input type="radio" name="category" value="newsletter" <?= ($category === 'newsletter') ? 'checked' : '' ?>>
                            Newsletter
                        </label>
                    </div>

                    <div class="checkbox-group" id="overwrite-group" style="display: none;">
                        <label>
                            <input type="checkbox" name="overwrite" value="1">
                            Overwrite existing newsletter.pdf
                        </label>
                    </div>

                    <input type="file" name="images[]" accept="image/*,.pdf" multiple required>
                    <input type="submit" name="upload" value="Upload Files">
                </form>
                <p><small>Allowed: JPG, PNG, GIF, WebP, PDF â€¢ Max 5MB each</small></p>
            </div>

            <h2>Uploaded Files (<?= count($images) ?>)</h2>
            <?php if (empty($images)): ?>
                <p>No files uploaded yet.</p>
            <?php else: ?>
                <div class="image-grid">
                    <?php foreach ($images as $img):
                        $ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));
                        $is_pdf = ($ext === 'pdf');
                        $is_newsletter = ($img === 'newsletter.pdf');
                        $img_path = $is_newsletter ? '../assets/' : '../uploads/';
                    ?>
                        <div class="image-item">
                            <?php if ($is_pdf): ?>
                                <a href="<?= $img_path . htmlspecialchars($img) ?>" target="_blank" style="display: block; padding: 2rem; text-align: center;">
                                    <span style="font-size: 2rem;">PDF</span>
                                    <br>
                                    <small><?= htmlspecialchars($img) ?></small>
                                </a>
                            <?php else: ?>
                                <img src="<?= $img_path . htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($img) ?>">
                            <?php endif; ?>
                            <form method="POST" style="margin:0;">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="delete" value="<?= htmlspecialchars($img) ?>">
                                <button type="submit" class="delete-btn" onclick="return confirm('Delete this file?')">Delete</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <script src="../scripts/navbar.js"></script>
    <script>
        const newsletterRadio = document.querySelector('input[name="category"][value="newsletter"]');
        const overwriteGroup = document.getElementById('overwrite-group');

        function updateOverwrite() {
            if (newsletterRadio && newsletterRadio.checked) {
                overwriteGroup.style.display = 'block';
            } else {
                overwriteGroup.style.display = 'none';
            }
        }

        document.querySelectorAll('input[name="category"]').forEach(function(radio) {
            radio.addEventListener('change', updateOverwrite);
        });

        updateOverwrite();
    </script>
</body>
</html>
