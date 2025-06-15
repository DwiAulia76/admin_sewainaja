<?php
// Contoh data barang (dalam aplikasi nyata ini akan dari database)
$items = [
    [
        'name' => 'Kamera DSLR Canon EOS 90D',
        'category' => 'Elektronik',
        'image' => 'https://via.placeholder.com/300x200?text=Kamera+DSLR',
        'description' => 'Kamera DSLR profesional dengan sensor 32.5MP, cocok untuk pemotretan profesional.',
        'price' => 150000,
        'status' => 'tersedia'
    ],
    [
        'name' => 'Proyektor Epson EB-U05',
        'category' => 'Elektronik',
        'image' => 'https://via.placeholder.com/300x200?text=Proyektor',
        'description' => 'Proyektor portabel dengan resolusi Full HD dan konektivitas lengkap.',
        'price' => 120000,
        'status' => 'disewa'
    ],
    [
        'name' => 'Sound System Portable',
        'category' => 'Audio',
        'image' => 'https://via.placeholder.com/300x200?text=Sound+System',
        'description' => 'Sound system portabel dengan output 500W, cocok untuk acara indoor/outdoor.',
        'price' => 200000,
        'status' => 'tersedia'
    ],
    [
        'name' => 'Laptop Asus ROG Zephyrus',
        'category' => 'Elektronik',
        'image' => 'https://via.placeholder.com/300x200?text=Laptop+Gaming',
        'description' => 'Laptop gaming dengan prosesor i7 dan GPU RTX 3060, performa tinggi untuk gaming dan desain.',
        'price' => 180000,
        'status' => 'tersedia'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manajemen Barang</title>
    <link rel="stylesheet" href="assets/css/itemsStyle.css">
    <link rel="stylesheet" href="assets/css/sidebarStyle.css">
</head>
<body>
    <!-- Mobile Toggle Button (Hidden on Desktop) -->
    <button class="mobile-toggle" id="sidebarToggle">
        ☰
    </button>

    <!-- Include Sidebar -->
    <?php include 'components/sidebar.php'; ?>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Include Items Content -->
        <?php include 'views/items.php'; ?>
    </main>

    <script src="assets/js/itemScript.js"></script>
</body>
</html>