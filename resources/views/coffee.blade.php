<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coffee Break | Kedai Kopi</title>
    <meta name="description" content="Coffee Break, kedai kopi lokal dengan racikan kopi berkualitas dan harga bersahabat.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body class="bg-[#6E3511] text-white">

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="#home" class="logo">
                Coffee <span>Break.</span>
            </a>

            <button id="menu-toggle" class="menu-toggle"
                aria-label="Buka menu navigasi" aria-expanded="false">
                ☰
            </button>

            <div id="nav-links" class="nav-links">
                <a href="#home">Home</a>
                <a href="#jelajah">Tentang</a>
                <a href="#menu">Menu</a>
                <a href="#galeri">Galeri</a>
                <a href="#kontak">Kontak</a>
                <a href="{{ auth()->user()?->is_admin ? route('admin.products.index') : route('login') }}">
                    {{ auth()->user()?->is_admin ? 'Kelola Menu' : 'Login Admin' }}
                </a>
            </div>

            <button class="cart-button" id="open-cart">
                🛒 Keranjang <span id="cart-count">0</span>
            </button>
        </div>
    </nav>

    <main>
        <!-- HERO -->
        <section id="home" class="hero-section">
            <div class="hero-container">
                <div class="hero-content fade-up">
                    <p class="eyebrow">KOPI LOKAL, CERITA SPESIAL</p>

                    <h1>Nikmati Setiap <span>Tetesan</span> Kebahagiaan.</h1>

                    <p class="hero-description">
                        Temukan kenikmatan kopi pilihan yang diracik
                        dengan sepenuh hati. Teman terbaik untuk
                        memulai hari dan menikmati waktu santai.
                    </p>

                    <div class="hero-buttons">
                        <a href="#menu" class="btn-primary">Jelajahi Menu →</a>
                        <a href="#jelajah" class="btn-outline">Cerita Kami</a>
                    </div>

                    <div class="hero-stats">
                        <div>
                            <strong>100%</strong>
                            <span>Biji Kopi Pilihan</span>
                        </div>
                        <div>
                            <strong>Fresh</strong>
                            <span>Diracik Setiap Hari</span>
                        </div>
                    </div>
                </div>

                <div class="hero-image fade-up">
                    <img src="{{ asset('images/main-1.jpg') }}"
                        alt="Kopi spesial Coffee Break">
                    <div class="image-label">
                        <span>☕</span>
                        <div>
                            <strong>Made with Love</strong>
                            <p>Secangkir kebahagiaanmu</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- KEUNGGULAN -->
        <section class="features-section">
            <div class="features-container">
                <article class="feature-item">
                    <span>☕</span>
                    <div>
                        <h3>Biji Kopi Berkualitas</h3>
                        <p>Dipilih untuk menghasilkan rasa terbaik.</p>
                    </div>
                </article>

                <article class="feature-item">
                    <span>♡</span>
                    <div>
                        <h3>Dibuat dengan Hati</h3>
                        <p>Setiap cangkir diracik dengan sepenuh hati.</p>
                    </div>
                </article>

                <article class="feature-item">
                    <span>✦</span>
                    <div>
                        <h3>Harga Bersahabat</h3>
                        <p>Kopi nikmat yang ramah di kantong.</p>
                    </div>
                </article>
            </div>
        </section>

        <!-- TENTANG KAMI -->
        <section id="jelajah" class="about-section section-padding">
            <div class="about-container">
                <div class="about-image">
                    <img src="{{ asset('images/main-2.jpg') }}"
                        alt="Suasana dan sajian kopi" loading="lazy">
                </div>

                <div class="about-content">
                    <p class="eyebrow">CERITA KAMI</p>
                    <h2>Lebih dari Sekadar <span>Secangkir Kopi.</span></h2>

                    <p>
                        Coffee Break hadir untuk menemani momen kecil
                        yang berarti. Kami percaya secangkir kopi yang
                        nikmat bisa menjadi awal percakapan,
                        inspirasi, dan cerita baru.
                    </p>

                    <p>
                        Dengan bahan pilihan dan pelayanan yang ramah,
                        kami ingin menghadirkan pengalaman ngopi
                        yang sederhana, nyaman, dan berkesan.
                    </p>

                    <a href="#menu" class="btn-primary">Lihat Menu Kami →</a>
                </div>
            </div>
        </section>

        <!-- MENU -->
        <section id="menu" class="menu-section section-padding">
            <div class="section-heading">
                <p class="eyebrow">FAVORIT PELANGGAN</p>
                <h2>Temukan <span>Kopi Pilihanmu</span></h2>
                <p>
                    Dari kopi klasik sampai racikan spesial,
                    ada teman untuk setiap suasana.
                </p>
            </div>

            <div class="filter-buttons">
                <button class="filter-btn active" data-category="Semua">Semua</button>
                <button class="filter-btn" data-category="Kopi">Kopi</button>
                <button class="filter-btn" data-category="Non-Kopi">Non-Kopi</button>
                <button class="filter-btn" data-category="Makanan">Makanan</button>
            </div>

            <!-- JavaScript menampilkan produk di sini -->
            <div id="product-list" class="product-grid"></div>
        </section>

        <!-- PROMO -->
        <section class="promo-section">
            <div class="promo-content">
                <p class="eyebrow">WAKTUNYA NGOPI</p>
                <h2>Hari yang Baik Dimulai dari Kopi yang Baik.</h2>
                <p>
                    Temukan menu favoritmu dan pesan dengan mudah
                    langsung melalui WhatsApp.
                </p>
                <a href="#menu" class="btn-light">Pesan Sekarang →</a>
            </div>
        </section>

        <!-- GALERI -->
        <section id="galeri" class="gallery-section section-padding">
            <div class="section-heading">
                <p class="eyebrow">MOMEN COFFEE BREAK</p>
                <h2>Secangkir Kopi, <span>Banyak Cerita.</span></h2>
                <p>Tempat sederhana untuk menikmati setiap momen.</p>
            </div>

            <div class="gallery-grid">
                <img src="{{ asset('images/main-1.jpg') }}"
                    alt="Sajian kopi Coffee Break" loading="lazy">

                <img src="{{ asset('images/main-3.jpg') }}"
                    alt="Biji dan racikan kopi" loading="lazy">

                <div class="gallery-message">
                    <span>☕</span>
                    <h3>Take a Break.</h3>
                    <p>Good coffee, good mood.</p>
                </div>
            </div>
        </section>

        <!-- KONTAK -->
        <section id="kontak" class="contact-section section-padding">
            <div class="contact-container">
                <div>
                    <p class="eyebrow">KUNJUNGI KAMI</p>
                    <h2>Yuk, Ngopi <span>Bareng!</span></h2>
                    <p>
                        Ada pertanyaan atau ingin memesan kopi?
                        Hubungi kami melalui informasi di bawah ini.
                    </p>
                </div>

                <div class="contact-details">
                    <p>📍 <strong>Alamat:</strong> JL.PEGANGSAAAN 12 JAKARTA SELATAN</p>
                    <p>📱 <strong>WhatsApp:</strong> 0881025153169</p>
                    <p>🕒 <strong>Jam buka:</strong> 08.00 – 22.00 WIB</p>

                    <a href="#" id="contact-whatsapp" class="btn-primary">
                        Hubungi Kami →
                    </a>
                </div>
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="footer">
        <a href="#home" class="logo">Coffee <span>Break.</span></a>

        <p>© <span id="year"></span> Coffee Break. UMKM lokal, rasa istimewa.</p>

        <a href="#home">Kembali ke atas ↑</a>
    </footer>

    <!-- KERANJANG -->
    <div id="cart-overlay" class="cart-overlay"></div>

    <aside id="cart-drawer" class="cart-drawer"
        aria-label="Keranjang belanja" aria-hidden="true">

        <div class="cart-header">
            <h2>Keranjang Kamu</h2>
            <button id="close-cart" aria-label="Tutup keranjang">✕</button>
        </div>

        <div id="cart-items" class="cart-items">
            <p class="empty-cart">Keranjang kamu masih kosong ☕</p>
        </div>

        <div class="cart-footer">
            <div class="cart-total">
                <span>Total</span>
                <strong id="cart-total">Rp0</strong>
            </div>

            <label for="customer-name">Nama pemesan</label>
            <input id="customer-name" type="text" placeholder="Masukkan nama kamu">

            <label for="customer-note">Catatan pesanan</label>
            <textarea id="customer-note"
                placeholder="Contoh: gula sedikit, es terpisah"></textarea>

            <button id="checkout-button" class="btn-primary checkout-btn">
                Pesan via WhatsApp
            </button>

            <p class="cart-note">
                Pesanan akan dikirim ke WhatsApp untuk konfirmasi.
            </p>
        </div>
    </aside>

    <div id="toast" class="toast" role="status"></div>

    <script>
        window.coffeeProducts = {{ Illuminate\Support\Js::from($products) }};
    </script>
    <script src="{{ asset('js/script.js') }}" defer></script>
</body>
</html>