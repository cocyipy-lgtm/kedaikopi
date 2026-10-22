javascript
"use strict";

// =====================================
// KONFIGURASI COFFEE BREAK
// =====================================

const WHATSAPP_NUMBER = "62881025153169";

const products = [
    {
        id: 1,
        name: "Espresso",
        category: "Kopi",
        price: 12000,
        description: "Espresso pekat dengan karakter rasa kuat.",
        image: "/images/main-2.jpg"
    },
    {
        id: 2,
        name: "Americano",
        category: "Kopi",
        price: 15000,
        description: "Kopi hitam dengan rasa tegas dan menyegarkan.",
        image: "/images/main-1.jpg"
    },
    {
        id: 3,
        name: "Kopi Susu Gula Aren",
        category: "Kopi",
        price: 18000,
        description: "Espresso, susu creamy, dan gula aren.",
        image: "/images/main-1.jpg"
    },
    {
        id: 4,
        name: "Cafe Latte",
        category: "Kopi",
        price: 20000,
        description: "Espresso dengan susu lembut dan foam tipis.",
        image: "/images/main-3.jpg"
    },
    {
        id: 5,
        name: "Cappuccino",
        category: "Kopi",
        price: 20000,
        description: "Espresso dengan foam susu yang lembut.",
        image: "/images/main-4.jpg"
    }
];

// =====================================
// JALANKAN WEBSITE
// =====================================

document.addEventListener("DOMContentLoaded", () => {
    const $ = id => document.getElementById(id);

    const productList = $("product-list");
    const cartCount = $("cart-count");
    const cartItems = $("cart-items");
    const cartTotal = $("cart-total");
    const cartDrawer = $("cart-drawer");
    const cartOverlay = $("cart-overlay");
    const toast = $("toast");
    const menuToggle = $("menu-toggle");
    const navLinks = $("nav-links");

    const cart = new Map();
    let toastTimeout;

    // FORMAT RUPIAH
    function formatRupiah(amount) {
        return new Intl.NumberFormat("id-ID", {
            style: "currency",
            currency: "IDR",
            maximumFractionDigits: 0
        }).format(amount);
    }

    // NOTIFIKASI
    function showToast(message) {
        if (!toast) return;

        toast.textContent = message;
        toast.classList.add("show");

        clearTimeout(toastTimeout);

        toastTimeout = setTimeout(() => {
            toast.classList.remove("show");
        }, 2500);
    }

    // TAMPILKAN MENU KOPI
    function renderProducts(category = "Semua") {
        if (!productList) {
            console.error('Elemen "product-list" tidak ditemukan di HTML.');
            return;
        }

        productList.replaceChildren();

        const filtered = category === "Semua"
            ? products
            : products.filter(product => product.category === category);

        if (filtered.length === 0) {
            const message = document.createElement("p");
            message.textContent = "Menu kategori ini belum tersedia.";
            productList.append(message);
            return;
        }

        filtered.forEach(product => {
            const article = document.createElement("article");
            article.className = "product-card";

            const imageWrapper = document.createElement("div");
            imageWrapper.className = "product-image";

            const image = document.createElement("img");
            image.src = product.image;
            image.alt = product.name;
            image.loading = "lazy";

            image.onerror = () => {
                image.onerror = null;
                image.style.display = "none";
            };

            const badge = document.createElement("span");
            badge.className = "product-category";
            badge.textContent = product.category;

            imageWrapper.append(image, badge);

            const info = document.createElement("div");
            info.className = "product-info";

            const name = document.createElement("h3");
            name.textContent = product.name;

            const description = document.createElement("p");
            description.textContent = product.description;

            const bottom = document.createElement("div");
            bottom.className = "product-bottom";

            const price = document.createElement("span");
            price.className = "product-price";
            price.textContent = formatRupiah(product.price);

            const addButton = document.createElement("button");
            addButton.type = "button";
            addButton.className = "add-cart";
            addButton.textContent = "+";
            addButton.setAttribute(
                "aria-label",
                `Tambah ${product.name} ke keranjang`
            );

            addButton.addEventListener("click", () => {
                addToCart(product.id);
            });

            bottom.append(price, addButton);
            info.append(name, description, bottom);
            article.append(imageWrapper, info);
            productList.append(article);
        });
    }

    // FILTER KATEGORI
    document.querySelectorAll(".filter-btn").forEach(button => {
        button.addEventListener("click", () => {
            document.querySelectorAll(".filter-btn").forEach(item => {
                item.classList.remove("active");
            });

            button.classList.add("active");
            renderProducts(button.dataset.category || "Semua");
        });
    });

    // TAMBAH KE KERANJANG
    function addToCart(productId) {
        const product = products.find(item => item.id === productId);
        if (!product) return;

        cart.set(productId, (cart.get(productId) || 0) + 1);

        renderCart();
        showToast(`${product.name} ditambahkan ke keranjang.`);
    }

    // UBAH JUMLAH
    function changeQuantity(productId, amount) {
        const quantity = (cart.get(productId) || 0) + amount;

        if (quantity <= 0) {
            cart.delete(productId);
        } else {
            cart.set(productId, quantity);
        }

        renderCart();
    }

    // TAMPILKAN KERANJANG
    function renderCart() {
        if (!cartItems || !cartCount || !cartTotal) return;

        cartItems.replaceChildren();

        let totalQuantity = 0;
        let totalPrice = 0;

        cart.forEach((quantity, productId) => {
            const product = products.find(item => item.id === productId);
            if (!product) return;

            totalQuantity += quantity;
            totalPrice += product.price * quantity;

            const item = document.createElement("div");
            item.className = "cart-item";

            const details = document.createElement("div");

            const name = document.createElement("h3");
            name.textContent = product.name;

            const subtotal = document.createElement("p");
            subtotal.textContent = formatRupiah(product.price * quantity);

            const controls = document.createElement("div");
            controls.className = "quantity-controls";

            const decrease = document.createElement("button");
            decrease.type = "button";
            decrease.textContent = "−";
            decrease.setAttribute("aria-label", "Kurangi jumlah");
            decrease.addEventListener("click", () => {
                changeQuantity(productId, -1);
            });

            const quantityText = document.createElement("span");
            quantityText.textContent = quantity;

            const increase = document.createElement("button");
            increase.type = "button";
            increase.textContent = "+";
            increase.setAttribute("aria-label", "Tambah jumlah");
            increase.addEventListener("click", () => {
                changeQuantity(productId, 1);
            });

            controls.append(decrease, quantityText, increase);
            details.append(name, subtotal, controls);

            const removeButton = document.createElement("button");
            removeButton.type = "button";
            removeButton.className = "remove-item";
            removeButton.textContent = "Hapus";
            removeButton.addEventListener("click", () => {
                cart.delete(productId);
                renderCart();
                showToast("Produk dihapus dari keranjang.");
            });

            item.append(details, removeButton);
            cartItems.append(item);
        });

        if (cart.size === 0) {
            const empty = document.createElement("p");
            empty.className = "empty-cart";
            empty.textContent = "Keranjang kamu masih kosong ☕";
            cartItems.append(empty);
        }

        cartCount.textContent = totalQuantity;
        cartTotal.textContent = formatRupiah(totalPrice);
    }

    // BUKA DAN TUTUP KERANJANG
    function openCart() {
        cartDrawer?.classList.add("show");
        cartOverlay?.classList.add("show");
        document.body.classList.add("cart-open");
        cartDrawer?.setAttribute("aria-hidden", "false");
    }

    function closeCart() {
        cartDrawer?.classList.remove("show");
        cartOverlay?.classList.remove("show");
        document.body.classList.remove("cart-open");
        cartDrawer?.setAttribute("aria-hidden", "true");
    }

    $("open-cart")?.addEventListener("click", openCart);
    $("close-cart")?.addEventListener("click", closeCart);
    cartOverlay?.addEventListener("click", closeCart);

    document.addEventListener("keydown", event => {
        if (event.key === "Escape") closeCart();
    });

    // CHECKOUT WHATSAPP
    $("checkout-button")?.addEventListener("click", () => {
        if (cart.size === 0) {
            showToast("Keranjang kamu masih kosong.");
            return;
        }

        const customerName = $("customer-name")?.value.trim() || "";
        const customerNote = $("customer-note")?.value.trim() || "";

        if (!customerName) {
            showToast("Masukkan nama pemesan terlebih dahulu.");
            $("customer-name")?.focus();
            return;
        }

        if (!/^\d{10,15}$/.test(WHATSAPP_NUMBER)) {
            showToast("Nomor WhatsApp belum dikonfigurasi.");
            return;
        }

        let total = 0;

        const lines = [
            "*PESANAN COFFEE BREAK*",
            "",
            `Nama: ${customerName}`,
            "",
            "*Detail pesanan:*"
        ];

        cart.forEach((quantity, productId) => {
            const product = products.find(item => item.id === productId);
            if (!product) return;

            const subtotal = product.price * quantity;
            total += subtotal;

            lines.push(
                `- ${product.name} x${quantity} = ${formatRupiah(subtotal)}`
            );
        });

        lines.push("");
        lines.push(`*TOTAL: ${formatRupiah(total)}*`);

        if (customerNote) {
            lines.push("");
            lines.push(`Catatan: ${customerNote}`);
        }

        lines.push("");
        lines.push("Mohon konfirmasi pesanan saya.");

        const url =
            `https://wa.me/${WHATSAPP_NUMBER}?text=` +
            encodeURIComponent(lines.join("\n"));

        window.open(url, "_blank", "noopener,noreferrer");
    });

    // KONTAK WHATSAPP
    const contactWhatsapp = $("contact-whatsapp");

    if (contactWhatsapp) {
        contactWhatsapp.href = `https://wa.me/${WHATSAPP_NUMBER}`;
        contactWhatsapp.target = "_blank";
        contactWhatsapp.rel = "noopener noreferrer";
    }

    // NAVBAR MOBILE
    menuToggle?.addEventListener("click", () => {
        const isOpen = navLinks?.classList.toggle("open") || false;

        menuToggle.setAttribute("aria-expanded", String(isOpen));
        menuToggle.setAttribute(
            "aria-label",
            isOpen ? "Tutup menu navigasi" : "Buka menu navigasi"
        );
    });

    navLinks?.querySelectorAll("a").forEach(link => {
        link.addEventListener("click", () => {
            navLinks.classList.remove("open");
            menuToggle?.setAttribute("aria-expanded", "false");
            menuToggle?.setAttribute("aria-label", "Buka menu navigasi");
        });
    });

    // TAHUN OTOMATIS
    if ($("year")) {
        $("year").textContent = new Date().getFullYear();
    }

    // TAMPILKAN MENU DAN KERANJANG
    renderProducts();
    renderCart();

    console.log("Coffee Break: JavaScript berhasil dimuat.");
})