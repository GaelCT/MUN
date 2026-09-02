<?php
$upload_dir = "uploads/";
$images = [];

// Get uploaded images (newest first)
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $images[] = $file;
        }
    }
    rsort($images);
}

// Default hardcoded images (fallback)
$default_images = [
    ['src' => '../images/delegates.jpg', 'alt' => 'Delegates', 'caption' => 'CSUB MUN Delegates'],
    ['src' => '../images/dinner.jpg', 'alt' => 'Dinner', 'caption' => 'CSUB MUN delegates at LAMUN XX enjoying dinner'],
    ['src' => '../images/ElotePresent.jpg', 'alt' => 'Elote Present', 'caption' => 'President Elliott giving a presentation on what to expect'],
    ['src' => '../images/General.jpg', 'alt' => 'General', 'caption' => '01/23/26 MUN General Meeting'],
    ['src' => '../images/image8.jpg', 'alt' => 'Image 8', 'caption' => 'Club Promotion Day'],
    ['src' => '../images/image19.jpg', 'alt' => 'Image 19', 'caption' => 'CSUB MUN delegates working hard'],
    ['src' => '../images/Nayen!.jpg', 'alt' => 'Nayeni', 'caption' => 'Vice President Nayen explaining the differences between committees'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/gallery.css">
</head>
<body>
    <div id="navbar-placeholder"></div>

    <div class="container">
        <h1 class="gallery-title">Photo Gallery</h1>
        <div class="gallery-grid">
            <!-- Uploaded images (dynamic) -->
            <?php foreach ($images as $img): ?>
                <div class="cards">
                    <img src="../uploads/<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($img) ?>">
                    <br>
                    <h3><?= htmlspecialchars($img) ?></h3>
                </div>
            <?php endforeach; ?>
            
            <!-- Default images -->
            <?php foreach ($default_images as $img): ?>
                <div class="cards">
                    <img src="<?= $img['src'] ?>" alt="<?= $img['alt'] ?>">
                    <br>
                    <h3><?= $img['caption'] ?></h3>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../scripts/navbar.js"></script>
    <script src="../scripts/script.js"></script>
</body>
</html>