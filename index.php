<?php
/**
 * NUCOMU CAFE — Landing Page & Ordering System
 * Stack: Vanilla PHP + HTML / CSS / JavaScript
 * Can be run directly with: php -S 127.0.0.1:8000
 */

// ==========================================================================
// BACKEND API HANDLERS (JSON RESPONSE)
// ==========================================================================
$menuCatalog = [
    'beef-sourdough' => [
        'id' => 'beef-sourdough',
        'name' => 'Smoked Beef Sourdough',
        'category' => 'meals-toast',
        'is_chef_special' => 0,
        'price' => 38000,
        'unit' => '/porsi',
        'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Roti sourdough panggang, smoked beef tender, telur & saus hollandaise.',
        'opt1_title' => 'Pilihan Telur',
        'opt1_choices' => ['Sunny Side Up', 'Over Medium', 'Well Done'],
        'opt2_title' => 'Spicy Level',
        'opt2_choices' => ['Mild', 'Medium Spicy', 'Extra Hot']
    ],
    'sig-latte' => [
        'id' => 'sig-latte',
        'name' => 'Nucomu Signature Latte',
        'category' => 'coffee',
        'is_chef_special' => 1,
        'price' => 26000,
        'unit' => '/cup',
        'image' => 'https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Espresso blend Sendang dengan susu lembut dan gula aren karamel.',
        'opt1_title' => 'Level Manis',
        'opt1_choices' => ['Normal (100%)', 'Less Sugar (50%)', 'Tanpa Gula (0%)'],
        'opt2_title' => 'Suhu Saji',
        'opt2_choices' => ['Dingin / Iced', 'Hangat / Hot']
    ],
    'cheesecake' => [
        'id' => 'cheesecake',
        'name' => 'Basque Burnt Cheesecake',
        'category' => 'desserts',
        'is_chef_special' => 0,
        'price' => 36000,
        'unit' => '/slice',
        'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Kue keju panggang artisanal dengan tekstur lumer di tengah.',
        'opt1_title' => 'Suhu Saji',
        'opt1_choices' => ['Chilled (Dingin)', 'Room Temp', 'Warm (Hangat)'],
        'opt2_title' => 'Extra Topping',
        'opt2_choices' => ['Vanilla Cream', 'Berry Compote', 'Original']
    ],
    'spaghetti-aglio' => [
        'id' => 'spaghetti-aglio',
        'name' => 'Spaghetti Aglio Olio',
        'category' => 'meals-toast',
        'is_chef_special' => 0,
        'price' => 34000,
        'unit' => '/porsi',
        'image' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Pasta al dente ditumis dengan minyak zaitun, bawang putih renyah, dan cabai kering.',
        'opt1_title' => 'Tingkat Pedas',
        'opt1_choices' => ['Mild Sedang', 'Pedas Gurih', 'Extra Pedas'],
        'opt2_title' => 'Pilihan Topping',
        'opt2_choices' => ['Extra Parmesan', 'Crispy Beef Bits', 'Garlic Toast']
    ],
    'truffle-fries' => [
        'id' => 'truffle-fries',
        'name' => 'Truffle Shoestring Fries',
        'category' => 'snacks',
        'is_chef_special' => 0,
        'price' => 27000,
        'unit' => '/porsi',
        'image' => 'https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Kentang goreng renyah bertabur minyak truffle putih dan parmesan wangi.',
        'opt1_title' => 'Pilihan Saus',
        'opt1_choices' => ['Truffle Aioli', 'Smoky BBQ', 'Sambal Nucomu'],
        'opt2_title' => 'Pilihan Bumbu',
        'opt2_choices' => ['Original Sea Salt', 'Nori Seaweed', 'Spicy Balado']
    ],
    'cold-brew' => [
        'id' => 'cold-brew',
        'name' => 'Cold Brew Reserve',
        'category' => 'coffee',
        'is_chef_special' => 0,
        'price' => 29000,
        'unit' => '/botol',
        'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Slow-steeped 18 jam dengan floral peach notes tanpa gula.',
        'opt1_title' => 'Penyajian',
        'opt1_choices' => ['On The Rocks (Es)', 'Murni Botol Dingin'],
        'opt2_title' => 'Varian Rasa',
        'opt2_choices' => ['Original Pure Black', 'Sweet Cold Foam']
    ],
    'matcha-uji' => [
        'id' => 'matcha-uji',
        'name' => 'Matcha Ceremonial Uji',
        'category' => 'beverages',
        'is_chef_special' => 0,
        'price' => 32000,
        'unit' => '/cup',
        'image' => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Bubuk matcha murni impor Uji Kyoto diseduh bersama susu segar berkualitas.',
        'opt1_title' => 'Level Manis',
        'opt1_choices' => ['Normal Manis', 'Less Sugar', 'Tanpa Gula'],
        'opt2_title' => 'Pilihan Susu',
        'opt2_choices' => ['Fresh Milk', 'Oat Milk (+Rp 5k)']
    ],
    'donut-choco' => [
        'id' => 'donut-choco',
        'name' => 'Donut Choco Pistachio',
        'category' => 'desserts',
        'is_chef_special' => 0,
        'price' => 28000,
        'unit' => '/pcs',
        'image' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Donat ragi alami empuk berlapis cokelat Belgia dan remah pistachio renyah.',
        'opt1_title' => 'Pilihan Glaze',
        'opt1_choices' => ['Dark Choco Pistachio', 'White Choco Almond'],
        'opt2_title' => 'Suhu Saji',
        'opt2_choices' => ['Normal Room Temp', 'Warm Hangat']
    ],
    'americano' => [
        'id' => 'americano',
        'name' => 'Americano Nucomu',
        'category' => 'coffee',
        'is_chef_special' => 1,
        'price' => 20000,
        'unit' => '/cup',
        'image' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Ekstraksi ganda biji kopi Sendang Tulungagung dengan profil citarasa clean.',
        'opt1_title' => 'Suhu Saji',
        'opt1_choices' => ['Dingin / Iced', 'Panas / Hot'],
        'opt2_title' => 'Pilihan Beans',
        'opt2_choices' => ['House Blend Sendang', 'Single Origin Fruity']
    ],
    'tiramisu' => [
        'id' => 'tiramisu',
        'name' => 'Classic Tiramisu',
        'category' => 'desserts',
        'is_chef_special' => 0,
        'price' => 32000,
        'unit' => '/porsi',
        'image' => 'https://images.unsplash.com/photo-1571877227200-a0d98ea607e9?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Ladyfinger berlumur espresso kental berpadu krim mascarpone lembut khas Italia.',
        'opt1_title' => 'Suhu Saji',
        'opt1_choices' => ['Chilled (Dingin)', 'Room Temp'],
        'opt2_title' => 'Taburan',
        'opt2_choices' => ['Dark Cocoa Dusting', 'Extra Espresso Shot']
    ],
    'earl-grey' => [
        'id' => 'earl-grey',
        'name' => 'Earl Grey Blossom Tea',
        'category' => 'beverages',
        'is_chef_special' => 0,
        'price' => 24000,
        'unit' => '/pot',
        'image' => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Seduhan daun teh hitam bergamot dengan bunga lavender kering harum menenangkan.',
        'opt1_title' => 'Penyajian',
        'opt1_choices' => ['Hot Pot (Teko)', 'Iced Carafe (Dingin)'],
        'opt2_title' => 'Pemanis',
        'opt2_choices' => ['Madu Murni', 'Gula Tebu', 'Tanpa Pemanis']
    ],
    'velvet-cake' => [
        'id' => 'velvet-cake',
        'name' => 'Velvet Truffle Cake',
        'category' => 'desserts',
        'is_chef_special' => 0,
        'price' => 30000,
        'unit' => '/slice',
        'image' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?auto=format&fit=crop&w=400&q=80',
        'desc' => 'Lapisan sponge cokelat lembut berpadu ganache truffle pekat yang lumer di lidah.',
        'opt1_title' => 'Suhu Saji',
        'opt1_choices' => ['Chilled (Dingin)', 'Room Temp'],
        'opt2_title' => 'Pilihan Krim',
        'opt2_choices' => ['Pure Whipped Cream', 'Ganache Glaze']
    ]
];

// Handle API POST: Create Order
if (isset($_GET['api']) && $_GET['api'] === 'order' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }

    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if (!$data || !is_array($data)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Format data tidak valid.']);
        exit;
    }

    $customerName = trim($data['customer_name'] ?? '');
    $customerPhone = trim($data['customer_phone'] ?? '');
    $processMethod = in_array($data['process_method'] ?? '', ['kasir', 'zendo']) ? $data['process_method'] : 'kasir';
    $deliveryAddress = trim($data['delivery_address'] ?? '');
    $tableNo = trim($data['table_no'] ?? '');
    $notes = trim($data['notes'] ?? '');
    $items = is_array($data['items'] ?? null) ? $data['items'] : [];

    if (empty($customerName) || empty($customerPhone)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Nama dan nomor WhatsApp pemesan wajib diisi.']);
        exit;
    }

    if ($processMethod === 'zendo' && empty($deliveryAddress)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Alamat pengiriman wajib diisi untuk Kurir Zendo.']);
        exit;
    }

    if (empty($items)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'Keranjang belanja tidak boleh kosong.']);
        exit;
    }

    // Server-side recalculation of subtotal and grand total
    $subtotal = 0;
    $verifiedItems = [];

    foreach ($items as $item) {
        $itemId = $item['id'] ?? '';
        $qty = max(1, intval($item['qty'] ?? 1));
        
        $unitPrice = 0;
        $itemName = $item['name'] ?? 'Menu';
        if (isset($menuCatalog[$itemId])) {
            $unitPrice = $menuCatalog[$itemId]['price'];
            $itemName = $menuCatalog[$itemId]['name'];
        } else {
            $unitPrice = max(0, intval($item['price'] ?? 0));
        }

        $lineTotal = $unitPrice * $qty;
        $subtotal += $lineTotal;

        $verifiedItems[] = [
            'name' => $itemName,
            'unit_price' => $unitPrice,
            'qty' => $qty,
            'line_total' => $lineTotal,
            'options' => $item['options'] ?? ''
        ];
    }

    $deliveryFee = ($processMethod === 'zendo') ? 10000 : 0;
    $grandTotal = $subtotal + $deliveryFee;
    $orderCode = 'NCM-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));

    // Store in SQLite database using PDO
    try {
        $dbPath = __DIR__ . '/nucomu_orders.sqlite';
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $db->exec("
            CREATE TABLE IF NOT EXISTS orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_code TEXT UNIQUE,
                customer_name TEXT,
                customer_phone TEXT,
                process_method TEXT,
                delivery_address TEXT,
                table_no TEXT,
                notes TEXT,
                subtotal INTEGER,
                delivery_fee INTEGER,
                grand_total INTEGER,
                status TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            );
            CREATE TABLE IF NOT EXISTS order_items (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                order_id INTEGER,
                menu_name TEXT,
                unit_price INTEGER,
                qty INTEGER,
                line_total INTEGER,
                options_text TEXT,
                FOREIGN KEY(order_id) REFERENCES orders(id)
            );
        ");

        $stmt = $db->prepare("
            INSERT INTO orders (order_code, customer_name, customer_phone, process_method, delivery_address, table_no, notes, subtotal, delivery_fee, grand_total, status)
            VALUES (:code, :name, :phone, :method, :address, :table, :notes, :subtotal, :fee, :total, 'pending')
        ");
        $stmt->execute([
            ':code' => $orderCode,
            ':name' => $customerName,
            ':phone' => $customerPhone,
            ':method' => $processMethod,
            ':address' => $deliveryAddress,
            ':table' => $tableNo,
            ':notes' => $notes,
            ':subtotal' => $subtotal,
            ':fee' => $deliveryFee,
            ':total' => $grandTotal
        ]);

        $orderId = $db->lastInsertId();

        $stmtItem = $db->prepare("
            INSERT INTO order_items (order_id, menu_name, unit_price, qty, line_total, options_text)
            VALUES (:order_id, :name, :price, :qty, :total, :options)
        ");
        foreach ($verifiedItems as $vItem) {
            $stmtItem->execute([
                ':order_id' => $orderId,
                ':name' => $vItem['name'],
                ':price' => $vItem['unit_price'],
                ':qty' => $vItem['qty'],
                ':total' => $vItem['line_total'],
                ':options' => $vItem['options']
            ]);
        }
    } catch (\Throwable $e) {}

    echo json_encode([
        'success' => true,
        'order_code' => $orderCode,
        'customer_name' => $customerName,
        'process_method' => $processMethod,
        'subtotal' => $subtotal,
        'delivery_fee' => $deliveryFee,
        'grand_total' => $grandTotal,
        'message' => 'Pesanan Anda berhasil dikonfirmasi!'
    ]);
    exit;
}

// Handle API GET: Menu list
if (isset($_GET['api']) && $_GET['api'] === 'menu') {
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
    }
    $cat = $_GET['category'] ?? 'all';
    $results = [];
    foreach ($menuCatalog as $m) {
        if ($cat === 'all' || $m['category'] === $cat || ($cat === 'chef-specials' && $m['is_chef_special'] === 1)) {
            $results[] = $m;
        }
    }
    echo json_encode(['success' => true, 'data' => $results]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nucomu Cafe — Tempat Kembali, Rasa yang Dirindukan</title>
    <meta name="description" content="Kafe bernuansa warm minimalist di Tulungagung untuk menikmati kopi single origin, artisan pastry, dan waktu yang tenang.">

    <!-- Google Fonts: Cormorant Garamond, Plus Jakarta Sans, Manrope, JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=JetBrains+Mono:wght@400;600;700&family=Manrope:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- =========================================================================
         1. NAVBAR (STICKY + BLUR ON SCROLL + SCROLL-SPY)
         ========================================================================= -->
    <header class="navbar-wrapper">
        <div class="container navbar-inner">
            <!-- Brand Logo & Name -->
            <a href="#home" class="navbar-brand" aria-label="Nucomu Cafe Beranda">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 8h1a4 4 0 1 1 0 8h-1"></path>
                    <path d="M3 8h14v9a4 4 0 0 1-4 4H7a4 4 0 0 1-4-4Z"></path>
                    <line x1="6" y1="2" x2="6" y2="4"></line>
                    <line x1="10" y1="2" x2="10" y2="4"></line>
                    <line x1="14" y1="2" x2="14" y2="4"></line>
                </svg>
                <span>NUCOMU CAFE</span>
            </a>

            <!-- Desktop Navigation Links (with scroll-spy) -->
            <nav>
                <ul class="navbar-nav">
                    <li><a href="#home" class="nav-link active">Home</a></li>
                    <li><a href="#about" class="nav-link">About</a></li>
                    <li><a href="#menu" class="nav-link">Menu</a></li>
                    <li><a href="#gallery" class="nav-link">Gallery</a></li>
                    <li><a href="#layanan" class="nav-link">Layanan</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                </ul>
            </nav>

            <!-- Right Actions: Cart Pill Button -->
            <div style="display: flex; align-items: center; gap: 12px;">
                <button type="button" class="nav-cart-btn" onclick="openModalCart()" aria-label="Buka Keranjang Belanja">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                    <span>My cart</span>
                    <span class="nav-cart-badge" id="navCartBadge">0</span>
                </button>
            </div>
        </div>
    </header>


    <!-- =========================================================================
         2. HERO SECTION
         ========================================================================= -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                <span>BUKA SETIAP HARI · 08:00 – 23:00</span>
            </div>
            <h1 class="hero-title">
                Tempat Kembali, <em>Rasa yang Dirindukan</em>
            </h1>
            <p class="hero-desc">
                Ruang hangat dan tenang di sudut kota Tulungagung untuk menyesap racikan kopi single origin Sendang, menikmati sepotong cheesecake artisan, dan merayakan percakapan berharga.
            </p>
            <div class="hero-actions">
                <a href="#menu" class="btn-pill-mono">
                    <span>Jelajahi Menu Pilihan</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <polyline points="19 12 12 19 5 12"></polyline>
                    </svg>
                </a>
                <a href="#about" class="btn-pill-white">Tentang Nucomu</a>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         3. MENU SECTION (CATEGORY TABS + CLEAN GRID + HOVER EXPANSION)
         ========================================================================= -->
    <section class="menu-section" id="menu">
        <div class="container">
            
            <!-- Section Header -->
            <div class="text-center" style="margin-bottom: 24px;">
                <span class="section-label">DAFTAR SAJIAN ARTISANAL</span>
                <h2 class="section-title">Katalog Menu Nucomu</h2>
                <p class="section-desc">
                    Sentuh atau dekatkan kursor pada kartu sajian untuk memilih racikan suhu, tingkat manis, saus, dan varian favorit Anda.
                </p>
            </div>

            <!-- 3a. Tab Kategori (6 Foto Lingkaran) -->
            <div class="category-tabs-wrapper">
                
                <!-- 1. Coffee -->
                <button type="button" class="category-tab-btn" data-category="coffee" aria-label="Filter Coffee">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1541167760496-1628856ab772?auto=format&fit=crop&w=200&q=80" alt="Coffee">
                    </div>
                    <span class="cat-label">Coffee</span>
                </button>

                <!-- 2. Meals & Toast -->
                <button type="button" class="category-tab-btn" data-category="meals-toast" aria-label="Filter Meals & Toast">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=200&q=80" alt="Meals & Toast">
                    </div>
                    <span class="cat-label">Meals & Toast</span>
                </button>

                <!-- 3. Desserts -->
                <button type="button" class="category-tab-btn" data-category="desserts" aria-label="Filter Desserts">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1533134242443-d4fd215305ad?auto=format&fit=crop&w=200&q=80" alt="Desserts">
                    </div>
                    <span class="cat-label">Desserts</span>
                </button>

                <!-- 4. Beverages -->
                <button type="button" class="category-tab-btn" data-category="beverages" aria-label="Filter Beverages">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1536256263959-770b48d82b0a?auto=format&fit=crop&w=200&q=80" alt="Beverages">
                    </div>
                    <span class="cat-label">Beverages</span>
                </button>

                <!-- 5. Food & Snacks -->
                <button type="button" class="category-tab-btn" data-category="snacks" aria-label="Filter Food & Snacks">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1576107232684-1279f3908594?auto=format&fit=crop&w=200&q=80" alt="Food & Snacks">
                    </div>
                    <span class="cat-label">Food & Snacks</span>
                </button>

                <!-- 6. Chef Specials -->
                <button type="button" class="category-tab-btn" data-category="chef-specials" aria-label="Filter Chef Specials">
                    <div class="cat-thumb-circle">
                        <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=200&q=80" alt="Chef Specials">
                    </div>
                    <span class="cat-label">Chef Specials</span>
                </button>

            </div>

            <!-- Divider Line with 3 Centered Dots -->
            <div class="category-divider">
                <span class="divider-line"></span>
                <div class="divider-dots">
                    <span></span><span></span><span></span>
                </div>
                <span class="divider-line"></span>
            </div>

            <!-- 3b & 3c. Grid Kartu Menu (Comfortable Grid + Overhanging Plate + Rich Options) -->
            <div class="menu-grid" id="menuGrid">
                <?php foreach ($menuCatalog as $item): ?>
                    <div class="menu-card-slot" 
                         data-category="<?= htmlspecialchars($item['category']) ?>" 
                         data-chef-special="<?= $item['is_chef_special'] ?>"
                         data-id="<?= htmlspecialchars($item['id']) ?>"
                         data-name="<?= htmlspecialchars($item['name']) ?>"
                         data-price="<?= $item['price'] ?>"
                         data-unit="<?= htmlspecialchars($item['unit']) ?>"
                         data-image="<?= htmlspecialchars($item['image']) ?>">
                        
                        <div class="menu-card">
                            <!-- Foto Bulat Besar Overhang ±94px -->
                            <div class="menu-card-avatar">
                                <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" loading="lazy">
                            </div>

                            <!-- Name & Price (Always Visible, Single Line Clean) -->
                            <h3 class="menu-card-name"><?= htmlspecialchars($item['name']) ?></h3>
                            <div class="menu-card-price-row">
                                <span>Rp <?= number_format($item['price'], 0, ',', '.') ?></span>
                                <span class="menu-card-unit"><?= htmlspecialchars($item['unit']) ?></span>
                            </div>

                            <!-- 3c. Panel Opsi Berbeda Per Menu (Expands Downwards on Hover) -->
                            <div class="menu-card-options">
                                <div class="options-columns">
                                    <!-- Column 1 -->
                                    <div>
                                        <span class="option-group-title"><?= htmlspecialchars($item['opt1_title']) ?></span>
                                        <?php foreach ($item['opt1_choices'] as $idx => $choice): ?>
                                            <label class="radio-pill-label">
                                                <input type="radio" 
                                                       name="opt1_<?= htmlspecialchars($item['id']) ?>" 
                                                       value="<?= htmlspecialchars($choice) ?>" 
                                                       <?= $idx === 0 ? 'checked' : '' ?>>
                                                <span><?= htmlspecialchars($choice) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>

                                    <!-- Column 2 -->
                                    <div>
                                        <span class="option-group-title"><?= htmlspecialchars($item['opt2_title']) ?></span>
                                        <?php foreach ($item['opt2_choices'] as $idx => $choice): ?>
                                            <label class="radio-pill-label">
                                                <input type="radio" 
                                                       name="opt2_<?= htmlspecialchars($item['id']) ?>" 
                                                       value="<?= htmlspecialchars($choice) ?>" 
                                                       <?= $idx === 0 ? 'checked' : '' ?>>
                                                <span><?= htmlspecialchars($choice) ?></span>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <!-- Add to Cart Button (Warm Monochrome Pill) -->
                                <button type="button" class="btn-add-to-cart" aria-label="Tambah <?= htmlspecialchars($item['name']) ?> ke keranjang">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    <span>+ Add to Cart</span>
                                </button>
                            </div>
                        </div>

                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </section>


    <!-- =========================================================================
         4. ABOUT SECTION
         ========================================================================= -->
    <section class="about-section" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-img-wrap">
                    <img src="https://images.unsplash.com/photo-1554118811-1e0d58224f24?auto=format&fit=crop&w=900&q=80" alt="Suasana Nucomu Cafe">
                </div>
                <div>
                    <span class="section-label">TENTANG KAMI</span>
                    <h2 class="section-title">Dedikasi Pada Setiap Tetes & Rasa</h2>
                    <p style="color: var(--mono-medium); font-size: 0.95rem; margin-bottom: 16px; line-height: 1.7;">
                        Nucomu Cafe berakar dari kecintaan terhadap hasil tani kopi lereng Gunung Wilis Sendang, Tulungagung. Kami mempertemukan biji kopi pilihan lokal dengan teknik seduh presisi dan bakery buatan tangan yang dipanggang segar setiap pagi.
                    </p>
                    <p style="color: var(--mono-medium); font-size: 0.95rem; margin-bottom: 28px; line-height: 1.7;">
                        Dengan atmosfer minimalis bernuansa hangat, Nucomu diciptakan bukan hanya untuk menikmati sajian nikmat, tetapi sebagai ruang tenang untuk singgah, berkarya, dan bercerita.
                    </p>
                    <div style="display: flex; gap: 28px;">
                        <div>
                            <span style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 700; color: var(--mono-black); display: block;">100%</span>
                            <span style="font-size: 0.76rem; color: var(--mono-subtle); text-transform: uppercase; font-weight: 700;">Arabika & Robusta Asli</span>
                        </div>
                        <div>
                            <span style="font-family: var(--font-serif); font-size: 2.2rem; font-weight: 700; color: var(--mono-black); display: block;">18 Jam</span>
                            <span style="font-size: 0.76rem; color: var(--mono-subtle); text-transform: uppercase; font-weight: 700;">Slow Brew Extraction</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         5. GALLERY SECTION
         ========================================================================= -->
    <section class="gallery-section" id="gallery">
        <div class="container">
            <div class="text-center">
                <span class="section-label">GALERI FOTO</span>
                <h2 class="section-title">Ruang, Rasa & Cerita</h2>
                <p class="section-desc">Merekam kilas momen hangat dan detail ketenangan di balik bar Nucomu.</p>
            </div>

            <div class="gallery-grid">
                <div class="gallery-card">
                    <img src="https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?auto=format&fit=crop&w=600&q=80" alt="Manual Brew Pour Over" loading="lazy">
                    <div class="gallery-overlay">
                        <span class="gallery-caption">Manual Pour Over Bar</span>
                    </div>
                </div>
                <div class="gallery-card">
                    <img src="https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80" alt="Fresh Bakery Kitchen" loading="lazy">
                    <div class="gallery-overlay">
                        <span class="gallery-caption">Artisanal Bakery Kitchen</span>
                    </div>
                </div>
                <div class="gallery-card">
                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" alt="Sudut Tenang Nucomu" loading="lazy">
                    <div class="gallery-overlay">
                        <span class="gallery-caption">Sudut Tenang Diskusi</span>
                    </div>
                </div>
                <div class="gallery-card">
                    <img src="https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&w=600&q=80" alt="Signature Hot Coffee" loading="lazy">
                    <div class="gallery-overlay">
                        <span class="gallery-caption">Signature Coffee Blend</span>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         6. LAYANAN SECTION
         ========================================================================= -->
    <section class="services-section" id="layanan">
        <div class="container">
            <div class="text-center">
                <span class="section-label">LAYANAN UNGGULAN</span>
                <h2 class="section-title">Kenyamanan Berkunjung & Menikmati</h2>
                <p class="section-desc">Kami menghadirkan fleksibilitas menikmati hidangan Nucomu baik di kafe maupun di rumah.</p>
            </div>

            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 1 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700; margin-bottom: 8px;">Dine-In & Slow Bar</h3>
                    <p style="font-size: 0.86rem; color: var(--mono-medium); line-height: 1.6;">
                        Nikmati racikan langsung dari barista, colokan listrik memadai, Wi-Fi berkecepatan tinggi, serta alunan musik lo-fi tenang.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="5" cy="18" r="3"></circle>
                            <circle cx="19" cy="18" r="3"></circle>
                            <path d="M12 18v-7l-3-3h5l2 3h3"></path>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700; margin-bottom: 8px;">Kurir Zendo Tulungagung</h3>
                    <p style="font-size: 0.86rem; color: var(--mono-medium); line-height: 1.6;">
                        Pengiriman pesanan langsung terhubung otomatis ke nomor kurir Zendo siaga dengan tarif bersahabat Rp 10.000 ke alamat Anda.
                    </p>
                </div>

                <div class="service-card">
                    <div class="service-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <h3 style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700; margin-bottom: 8px;">Reservasi & Acara Privat</h3>
                    <p style="font-size: 0.86rem; color: var(--mono-medium); line-height: 1.6;">
                        Tersedia area semi-private untuk workshop, meeting komunitas, atau perayaan intim dengan paket konsumsi artisanal khusus.
                    </p>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         7. CONTACT SECTION
         ========================================================================= -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center">
                <span class="section-label">LOKASI & KONTAK</span>
                <h2 class="section-title">Kunjungi Nucomu Cafe</h2>
                <p class="section-desc">Pintu kami selalu terbuka untuk menyambut kedatangan Anda.</p>
            </div>

            <div class="contact-grid">
                <div class="contact-info-card">
                    <h3 style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700; margin-bottom: 12px;">Alamat & Operasional</h3>
                    <p style="font-size: 0.88rem; color: var(--mono-medium); margin-bottom: 8px;">
                        <strong>Nucomu Cafe Tulungagung</strong><br>
                        Jl. Mayor Sujadi No. 88, Plosokandang, Kec. Kedungwaru, Kabupaten Tulungagung, Jawa Timur 66221
                    </p>
                    <p style="font-size: 0.88rem; color: var(--mono-medium); margin-bottom: 16px;">
                        <strong>Jam Operasional:</strong><br>
                        Senin – Minggu : 08:00 – 23:00 WIB
                    </p>
                    <p style="font-size: 0.88rem; color: var(--mono-medium);">
                        <strong>WhatsApp Official:</strong> +62 812-3456-7890<br>
                        <strong>Instagram:</strong> @nucomucafe
                    </p>
                </div>

                <div class="contact-info-card" style="display: flex; flex-direction: column; justify-content: center; background-color: #faf7f2;">
                    <h3 style="font-family: var(--font-serif); font-size: 1.35rem; font-weight: 700; margin-bottom: 8px;">Punya Pertanyaan Khusus?</h3>
                    <p style="font-size: 0.86rem; color: var(--mono-medium); margin-bottom: 20px;">
                        Hubungi barista atau admin kami langsung melalui WhatsApp untuk informasi katering, beans sangrai, atau pesanan kustom.
                    </p>
                    <a href="https://wa.me/6281234567890?text=Halo%20Nucomu%20Cafe%2C%20saya%20ingin%20bertanya" target="_blank" class="btn-pill-mono" style="width: fit-content;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                        </svg>
                        <span>Chat via WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>
    </section>


    <!-- =========================================================================
         8. SITE FOOTER
         ========================================================================= -->
    <footer class="site-footer">
        <div class="container footer-inner">
            <div>
                <strong>NUCOMU CAFE</strong> — Tempat Kembali, Rasa yang Dirindukan.
            </div>
            <div>
                &copy; <?= date('Y') ?> Nucomu Cafe Tulungagung. All rights reserved.
            </div>
        </div>
    </footer>


    <!-- =========================================================================
         9. FLOATING CART PILL (POJOK KANAN BAWAH, WARM MONOCHROME CAPSULE)
         ========================================================================= -->
    <div class="floating-cart-pill" id="floatingCartPill" onclick="openModalCart()" role="button" aria-label="Lihat Keranjang Belanja">
        <div class="floating-cart-icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="9" cy="21" r="1"></circle>
                <circle cx="20" cy="21" r="1"></circle>
                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
            </svg>
            <span class="floating-cart-badge" id="floatingCartBadge">0</span>
        </div>
        <span class="floating-cart-text" id="floatingCartTotal">My cart: Rp 0</span>
    </div>


    <!-- =========================================================================
         10. CENTERED FLOATING CARD MODAL (KERANJANG & TRANSAKSI DI TENGAH)
         ========================================================================= -->
    <div class="modal-backdrop-centered" id="nucomuModalBackdrop" onclick="handleBackdropClick(event)">
        <div class="modal-card-centered" id="nucomuCenteredModal" role="dialog" aria-modal="true">
            
            <!-- Modal Header (Dynamic: Keranjang vs Transaksi) -->
            <div class="modal-header-centered">
                <!-- Header in Cart Mode -->
                <div class="modal-header-title-wrap" id="headerCartView">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <h3 class="modal-header-title">Keranjang Pesanan</h3>
                    <span class="modal-header-badge" id="modalHeaderBadgeCount">0 item</span>
                </div>

                <!-- Header in Checkout Mode -->
                <div class="modal-header-title-wrap hidden" id="headerCheckoutView">
                    <button type="button" class="btn-back-to-cart-link" onclick="switchModalView('cart')" aria-label="Kembali ke Keranjang">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="19" y1="12" x2="5" y2="12"></line>
                            <polyline points="12 19 5 12 12 5"></polyline>
                        </svg>
                        <span>Keranjang</span>
                    </button>
                    <h3 class="modal-header-title" style="margin-left: 6px;">Checkout Transaksi</h3>
                </div>

                <!-- Close Modal Button -->
                <button type="button" class="modal-close-btn" onclick="closeCenteredModal()" aria-label="Tutup Jendela">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <!-- Modal Scrollable Body -->
            <div class="modal-body-scrollable">

                <!-- =============================================================
                     VIEW 1: KERANJANG PESANAN (Tampil pertama saat Add to Cart)
                     ============================================================= -->
                <div class="modal-view" id="modalCartView">
                    <!-- Empty State -->
                    <div class="cart-empty-state" id="modalEmptyState">
                        <svg class="cart-empty-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="9" cy="21" r="1"></circle>
                            <circle cx="20" cy="21" r="1"></circle>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                        </svg>
                        <h4 class="cart-empty-title">Keranjangmu masih kosong</h4>
                        <p class="cart-empty-sub">Pilih menu favoritmu di atas dan klik "+ Add to Cart" untuk mulai memesan.</p>
                    </div>

                    <!-- Items Container -->
                    <div id="modalCartItemsList"></div>
                </div>

                <!-- =============================================================
                     VIEW 2: FORM TRANSAKSI / CHECKOUT (Tampil setelah klik Lanjut)
                     ============================================================= -->
                <div class="modal-view hidden" id="modalCheckoutView">
                    <form id="centeredCheckoutForm" onsubmit="submitCenteredOrder(event)">
                        
                        <!-- 1. METODE PROSES TRANSAKSI -->
                        <div class="checkout-block">
                            <div class="checkout-block-header">
                                <span class="checkout-block-title">1. METODE PROSES TRANSAKSI</span>
                                <span class="checkout-tag">KASIR / ZENDO</span>
                            </div>

                            <div class="method-grid">
                                <!-- Option Kasir -->
                                <div class="method-card active" id="methodCardKasir">
                                    <div class="method-card-top">
                                        <svg class="method-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="3" width="7" height="7"></rect>
                                            <rect x="14" y="14" width="7" height="7"></rect>
                                            <rect x="3" y="14" width="7" height="7"></rect>
                                        </svg>
                                        <span class="method-radio-dot"></span>
                                    </div>
                                    <div class="method-name">Proses Kasir</div>
                                    <div class="method-sub">Slip & QR Kasir</div>
                                </div>

                                <!-- Option Kurir Zendo -->
                                <div class="method-card" id="methodCardZendo">
                                    <div class="method-card-top">
                                        <svg class="method-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="5" cy="18" r="3"></circle>
                                            <circle cx="19" cy="18" r="3"></circle>
                                            <path d="M12 18v-7l-3-3h5l2 3h3"></path>
                                        </svg>
                                        <span class="method-radio-dot"></span>
                                    </div>
                                    <div class="method-name">Kurir Zendo</div>
                                    <div class="method-sub">Pesan & Kirim Otomatis</div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. DAFTAR MENU PESANAN -->
                        <div class="checkout-block">
                            <div class="checkout-block-header">
                                <span class="checkout-block-title">2. DAFTAR MENU PESANAN</span>
                                <span class="checkout-tag" id="checkoutItemCountTag">0 item</span>
                            </div>

                            <div class="checkout-items-list">
                                <!-- Monospace Item Pricing Breakdown -->
                                <div id="checkoutMonospaceList"></div>

                                <!-- Totals Box -->
                                <div class="checkout-totals-box">
                                    <div class="summary-row" style="font-size: 0.8rem;">
                                        <span>Subtotal Menu</span>
                                        <span id="checkoutSubtotalAmount">Rp 0</span>
                                    </div>
                                    <div class="summary-row promo" id="checkoutShippingRow" style="display: none; font-size: 0.8rem;">
                                        <span>Ongkir Kurir Zendo</span>
                                        <span id="checkoutShippingFeeAmount">Rp 10.000</span>
                                    </div>
                                    <div class="summary-divider"></div>
                                    <div class="summary-row total" style="margin-bottom: 4px;">
                                        <span>Total Pembayaran</span>
                                        <span id="checkoutTotalAmount">Rp 0</span>
                                    </div>
                                    <p class="checkout-note-helper" id="checkoutPaymentHint">
                                        *Bayar di kasir setelah menunjukkan slip/QR.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- 3. DATA PEMESAN & LOKASI -->
                        <div class="checkout-block">
                            <div class="checkout-block-header">
                                <span class="checkout-block-title">3. DATA PEMESAN & LOKASI</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="inputCustomerName">
                                    Nama Pemesan <span class="req">*</span>
                                </label>
                                <input type="text" id="inputCustomerName" class="form-control" placeholder="Masukkan nama lengkap Anda" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="inputCustomerPhone">
                                    No. WhatsApp <span class="req">*</span>
                                </label>
                                <input type="tel" id="inputCustomerPhone" class="form-control" placeholder="Contoh: 081234567890" required>
                            </div>

                            <!-- Mode Kasir Specific -->
                            <div id="fieldsKasirSpecific">
                                <div class="form-group">
                                    <label class="form-label" for="inputTableNo">
                                        Nomor Meja / Catatan Tempat <span style="font-weight: 400; color: #888;">(opsional)</span>
                                    </label>
                                    <input type="text" id="inputTableNo" class="form-control" placeholder="Contoh: Meja 05 / Bungkus Takeaway">
                                </div>
                            </div>

                            <!-- Mode Zendo Specific -->
                            <div id="fieldsZendoSpecific" style="display: none;">
                                <div class="form-group">
                                    <label class="form-label" for="inputDeliveryAddress">
                                        Alamat Lengkap Pengiriman <span class="req">*</span>
                                    </label>
                                    <textarea id="inputDeliveryAddress" class="form-control" placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Patokan"></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="inputCourierNotes">
                                        Catatan untuk Kurir <span style="font-weight: 400; color: #888;">(opsional)</span>
                                    </label>
                                    <input type="text" id="inputCourierNotes" class="form-control" placeholder="Contoh: Pagar hitam seberang musholla">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="inputOrderNotes">
                                    Catatan Tambahan Pesanan <span style="font-weight: 400; color: #888;">(opsional)</span>
                                </label>
                                <input type="text" id="inputOrderNotes" class="form-control" placeholder="Contoh: Less ice, saus dipisah">
                            </div>
                        </div>

                        <!-- Submit Order Button -->
                        <button type="submit" class="btn-pill-mono btn-modal-action" id="btnSubmitOrderForm">
                            Konfirmasi & Buat Pesanan (Kasir)
                        </button>
                    </form>
                </div>

            </div>

            <!-- Modal Footer (Only shown in Cart View) -->
            <div class="modal-footer-centered" id="modalCartFooter">
                <div class="summary-row">
                    <span>Subtotal Menu</span>
                    <span id="cartSubtotalAmount">Rp 0</span>
                </div>
                <div class="summary-row promo">
                    <span>Diskon / Promo</span>
                    <span>Rp 0</span>
                </div>
                <div class="summary-divider"></div>
                <div class="summary-row total">
                    <span>Final Total</span>
                    <span id="cartFinalTotalAmount">Rp 0</span>
                </div>

                <button type="button" class="btn-pill-mono btn-modal-action" onclick="switchModalView('checkout')">
                    <span>Lanjut ke Pembayaran / Transaksi</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                        <polyline points="12 5 19 12 12 19"></polyline>
                    </svg>
                </button>

                <button type="button" class="btn-wa-order" onclick="orderViaWhatsAppDirect()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path>
                    </svg>
                    <span>Atau Pesan Cepat via WhatsApp</span>
                </button>
            </div>

        </div>
    </div>


    <!-- =========================================================================
         11. RECEIPT SUCCESS MODAL
         ========================================================================= -->
    <div class="receipt-modal" id="receiptModal" onclick="if(event.target===this) closeReceiptModal()">
        <div class="receipt-card">
            <div style="width: 52px; height: 52px; border-radius: 50%; background-color: #f0fdf4; color: #2e7d32; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
            </div>
            <span class="section-label" style="color: #2e7d32;">PESANAN BERHASIL DIBUAT</span>
            <h3 class="font-serif" style="font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; color: var(--mono-black);" id="receiptOrderCode">NCM-ORDER</h3>
            <p style="font-size: 0.84rem; color: var(--mono-medium); margin-bottom: 18px;" id="receiptNote">
                Silakan tunjukkan Kode Pesanan ke kasir untuk proses pembayaran.
            </p>

            <div style="background-color: #faf7f2; border: 1.5px solid var(--mono-border); border-radius: 16px; padding: 16px; text-align: left; font-size: 0.82rem; margin-bottom: 20px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="color: var(--mono-muted);">Pemesan:</span>
                    <strong id="receiptCustomerName" style="color: var(--mono-black);">-</strong>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="color: var(--mono-muted);">Metode:</span>
                    <strong id="receiptMethod" style="color: var(--mono-black);">Kasir</strong>
                </div>
                <div style="display: flex; justify-content: space-between; border-top: 1px solid var(--mono-border-light); padding-top: 8px;">
                    <span style="color: var(--mono-muted);">Total:</span>
                    <strong style="font-size: 1.05rem; color: var(--mono-black);" id="receiptTotal">Rp 0</strong>
                </div>
            </div>

            <button type="button" class="btn-pill-mono" style="width: 100%;" onclick="closeReceiptModal()">
                Selesai / Pesan Menu Lain
            </button>
        </div>
    </div>


    <!-- Client Engine Script -->
    <script src="script.js"></script>

</body>
</html>
